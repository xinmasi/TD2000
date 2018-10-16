<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("人事调动信息查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_transfer(TRANSFER_ID)
{
  msg='<?=_("确认要删除该项人事调动信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?TRANSFER_ID=" + TRANSFER_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("要删除人事调动信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项人事调动信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?TRANSFER_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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

if($TRANSFER_DATE1!="")
{
  $TIME_OK=is_date($TRANSFER_DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_DATE1=$TRANSFER_DATE1." 00:00:00";
}

if($TRANSFER_DATE2!="")
{
  $TIME_OK=is_date($TRANSFER_DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_DATE2=$TRANSFER_DATE2." 23:59:59";
}

if($TRANSFER_EFFECTIVE_DATE1!="")
{
  $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_EFFECTIVE_DATE1=$TRANSFER_EFFECTIVE_DATE1." 00:00:00";
}

if($TRANSFER_EFFECTIVE_DATE2!="")
{
  $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $TRANSFER_EFFECTIVE_DATE2=$TRANSFER_EFFECTIVE_DATE2." 23:59:59";
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($TRAN_REASON!="")
   $CONDITION_STR.=" and TRAN_REASON like '%".$TRAN_REASON."%'";
if($TRANSFER_PERSON!="")
   $CONDITION_STR.=" and TRANSFER_PERSON='$TRANSFER_PERSON'";
if($TRANSFER_TYPE!="")
   $CONDITION_STR.=" and TRANSFER_TYPE='$TRANSFER_TYPE'";
if($TRANSFER_DATE1!="")
  $CONDITION_STR.=" and TRANSFER_DATE>='$TRANSFER_DATE1'";
if($TRANSFER_DATE2!="")
  $CONDITION_STR.=" and TRANSFER_DATE<='$TRANSFER_DATE2'";   
if($TRANSFER_EFFECTIVE_DATE1!="")
   $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE>='$TRANSFER_EFFECTIVE_DATE1'";
if($TRANSFER_EFFECTIVE_DATE2!="")
   $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE<='$TRANSFER_EFFECTIVE_DATE2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("人事调动信息查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("TRANSFER_PERSON").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_TRANSFER where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$TRANSFER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_COUNT++;

   $TRANSFER_ID=$ROW["TRANSFER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
   $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
   $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
   $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
  $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");
  
   $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
   if($TRANSFER_PERSON_NAME=="")
   {    
     $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$TRANSFER_PERSON'";
     $cursor2= exequery(TD::conn(),$query2);
     if($ROW2=mysql_fetch_array($cursor2))
        $TRANSFER_PERSON_NAME=$ROW2["STAFF_NAME"];    	
    $TRANSFER_PERSON_NAME=$TRANSFER_PERSON_NAME."(<font color='red'>"._("用户已删除")."</font>)";
   }
  
  if($TRANSFER_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("调动人员")?></td>
      <td nowrap align="center"><?=_("调动类型")?></td>
      <td nowrap align="center"><?=_("调动日期")?></td>
      <td nowrap align="center"><?=_("调动生效日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$TRANSFER_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$TRANSFER_PERSON_NAME?></td>
      <td nowrap align="center"><?=$TRANSFER_TYPE?></td>
      <td nowrap align="center"><?=$TRANSFER_DATE=="0000-00-00"?"":$TRANSFER_DATE;?></td>
      <td nowrap align="center"><?=$TRANSFER_EFFECTIVE_DATE=="0000-00-00"?"":$TRANSFER_EFFECTIVE_DATE;?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('transfer_detail.php?TRANSFER_ID=<?=$TRANSFER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?TRANSFER_ID=<?=$TRANSFER_ID?>"> <?=_("修改")?></a>&nbsp;
			<a href="javascript:delete_transfer(<?=$TRANSFER_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}

if($TRANSFER_COUNT==0)
{
   Message("",_("无符合条件的人事调动信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选人事调动信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
