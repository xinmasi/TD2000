<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("查岗质询查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_ask_duty(ASK_DUTY_ID)
{
  msg='<?=_("确认要删除该查岗信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?ASK_DUTY_ID=" + ASK_DUTY_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.all("email_select").length;i++)
   {
      if(document.all("allbox").checked)
         document.all("email_select").item(i).checked=true;
      else
         document.all("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.all("allbox").checked)
         document.all("email_select").checked=true;
      else
         document.all("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.all("allbox").checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.all("email_select").length;i++)
   {

      el=document.all("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.all("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}

function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要删除查岗信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该查岗信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?ASK_DUTY_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------
if($CHECK_DUTY_TIME1!="")
{
  $TIME_OK=is_date($CHECK_DUTY_TIME1);
  if(!$TIME_OK)
  { Message(_("错误"),sprintf(_("日期的格式不对，应形如 %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $CHECK_DUTY_TIME1=$CHECK_DUTY_TIME1;
}

if($CHECK_DUTY_TIME2!="")
{
  $TIME_OK=is_date($CHECK_DUTY_TIME2);

  if(!$TIME_OK)
  { Message(_("错误"),sprintf(_("日期的格式不对，应形如 %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $CHECK_DUTY_TIME2=$CHECK_DUTY_TIME2;
}

if($RECORD_TIME1!="")
{
  $TIME_OK=is_date($RECORD_TIME1);

  if(!$TIME_OK)
  { Message(_("错误"),sprintf(_("日期的格式不对，应形如 %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $RECORD_TIME1=$RECORD_TIME1;
}

if($RECORD_TIME2!="")
{
  $TIME_OK=is_date($RECORD_TIME2);

  if(!$TIME_OK)
  { Message(_("错误"),sprintf(_("日期的格式不对，应形如 %s"),$CUR_DATE));
    Button_Back();
    exit;
  }
  $RECORD_TIME2=$RECORD_TIME2;
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($CHECK_USER_ID!="")
{
   $CHECK_USER_ID=td_trim($CHECK_USER_ID);
   $CONDITION_STR.=" and find_in_set('$CHECK_USER_ID',CHECK_USER_ID) ";
 }  
if($CHECK_DUTY_MANAGER!="")
{
	$manage_arr=explode(",",trim($CHECK_DUTY_MANAGER,","));
	$manage_tem="";
	foreach($manage_arr as $value)
   $manage_tem.=" find_in_set('$value',CHECK_DUTY_MANAGER) or";
	$CONDITION_STR.=" and ( ".trim($manage_tem," or")." ) ";
}

if($CHECK_DUTY_TIME1!="")
  $CONDITION_STR.=" and CHECK_DUTY_TIME>='$CHECK_DUTY_TIME1'";
if($CHECK_DUTY_TIME2!="")
  $CONDITION_STR.=" and CHECK_DUTY_TIME<='$CHECK_DUTY_TIME2'";
if($RECORD_TIME1!="")
   $CONDITION_STR.=" and RECORD_TIME>='$RECORD_TIME1'";
if($RECORD_TIME2!="")
   $CONDITION_STR.=" and RECORD_TIME<='$RECORD_TIME2'";
if($NOEXP==1)
	 $CONDITION_STR=" and EXPLANATION='' ";
if($NOEXP==2)
	 $CONDITION_STR=" and EXPLANATION!='' ";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("查岗质询查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
	$query = "SELECT * from ATTEND_ASK_DUTY where 1=1".$CONDITION_STR." order by ASK_DUTY_ID desc";
else
	$query = "SELECT * from ATTEND_ASK_DUTY where (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CREATE_USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',CHECK_DUTY_MANAGER)) ".$CONDITION_STR." order by ASK_DUTY_ID desc";

$cursor=exequery(TD::conn(),$query);
$ASK_DUTY_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ASK_DUTY_COUNT++;
   $ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
   $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];

   $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $CHECK_DUTY_REMARK=$ROW["CHECK_DUTY_REMARK"];
   $EXPLANATION=$ROW["EXPLANATION"];

	 $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
   $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);

  if($ASK_DUTY_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("缺岗人")?></td>
      <td nowrap align="center"><?=_("查岗人")?></td>
      <td nowrap align="center"><?=_("查岗时间")?></td>
      <td nowrap align="center"><?=_("缺岗人说明时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead>

<?
   }
?>
     <tr class="TableData">
<?
	if($_SESSION["LOGIN_USER_ID"]==$CREATE_USER_ID || $_SESSION["LOGIN_USER_PRIV"]==1)
	{
?>
      <td align="center">&nbsp;<input type="checkbox" name="email_select" value="<?=$ASK_DUTY_ID?>" onClick="check_one(self);"></td>
<?
	}
	else
	{
?>
	<td align="center">&nbsp;--</td>
<?
	}
?>
      <td nowrap align="center"><span <? if($EXPLANATION==""){ ?> style="color=red;" <? } ?> > <?=$CHECK_USER_NAME?></span></td>
      <td nowrap align="center"><?=$CHECK_MANAGER_NAME?></td>
      <td nowrap align="center"><?=$CHECK_DUTY_TIME=="0000-00-00 00:00:00"?"":$CHECK_DUTY_TIME;?></td>
      <td nowrap align="center"><?=$RECORD_TIME=="0000-00-00 00:00:00"?_("未说明"):$RECORD_TIME;?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('ask_duty_detail.php?ASK_DUTY_ID=<?=$ASK_DUTY_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
<?
	if($_SESSION["LOGIN_USER_ID"]==$CREATE_USER_ID || $_SESSION["LOGIN_USER_PRIV"]==1)
	{
?>
      <a href="modify.php?ASK_DUTY_ID=<?=$ASK_DUTY_ID?>"> <?=_("修改")?></a>&nbsp;
	 <a href="javascript:delete_ask_duty(<?=$ASK_DUTY_ID?>);"> <?=_("删除")?></a>&nbsp;
<?
	}
?>
    </td>
	</tr>
<?
}

if($ASK_DUTY_COUNT==0)
{
   Message("",_("无符合条件的查岗质询信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选查岗信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
