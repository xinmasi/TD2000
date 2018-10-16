<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("职称评定查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_evaluation(EVALUATION_ID)
{
  msg='<?=_("确认要删除该项职称评定信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?EVALUATION_ID=" + EVALUATION_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").item(i).checked=true;
      else
         document.getElementsByName("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").checked=true;
      else
         document.getElementsByName("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
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
     alert("<?=_("要删除职称评定信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项职称评定信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?EVALUATION_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
if($REPORT_TIME1!="")
{
  $TIME_OK=is_date($REPORT_TIME1);
  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REPORT_TIME1=$REPORT_TIME1." 00:00:00";
}

if($REPORT_TIME2!="")
{
  $TIME_OK=is_date($REPORT_TIME2);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $REPORT_TIME2=$REPORT_TIME2." 23:59:59";
}

if($RECEIVE_TIME1!="")
{
  $TIME_OK=is_date($RECEIVE_TIME1);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $RECEIVE_TIME1=$RECEIVE_TIME1." 00:00:00";
}

if($RECEIVE_TIME2!="")
{
  $TIME_OK=is_date($RECEIVE_TIME2);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $RECEIVE_TIME2=$RECEIVE_TIME2." 23:59:59";
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($REMARK!="")
   $CONDITION_STR.=" and REMARK like '%".$REMARK."%'";
if($EMPLOY_COMPANY!="")
   $CONDITION_STR.=" and EMPLOY_COMPANY like '%".$EMPLOY_COMPANY."%'";
if($EMPLOY_POST!="")
   $CONDITION_STR.=" and EMPLOY_POST like '%".$EMPLOY_POST."%'";
if($GET_METHOD!="")
   $CONDITION_STR.=" and GET_METHOD like '%".$GET_METHOD."%'";
if($POST_NAME!="")
   $CONDITION_STR.=" and POST_NAME='$POST_NAME'";
if($APPROVE_PERSON!="")
   $CONDITION_STR.=" and APPROVE_PERSON='$APPROVE_PERSON'";
if($BY_EVALU_STAFFS!="")
   $CONDITION_STR.=" and BY_EVALU_STAFFS='$BY_EVALU_STAFFS'";  
if($REPORT_TIME1!="")
  $CONDITION_STR.=" and REPORT_TIME>='$REPORT_TIME1'";
if($REPORT_TIME2!="")
  $CONDITION_STR.=" and REPORT_TIME<='$REPORT_TIME2'";   
if($RECEIVE_TIME1!="")
   $CONDITION_STR.=" and RECEIVE_TIME>='$RECEIVE_TIME1'";
if($RECEIVE_TIME2!="")
   $CONDITION_STR.=" and RECEIVE_TIME<='$RECEIVE_TIME2'";   
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("职称评定查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("BY_EVALU_STAFFS").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$CARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EVALUATION_COUNT++;
  
   $EVALUATION_ID=$ROW["EVALUATION_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $POST_NAME=$ROW["POST_NAME"];
   $GET_METHOD=$ROW["GET_METHOD"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
   $GET_METHOD=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");
   
   $BY_EVALU_NAME=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1);
	  if($BY_EVALU_NAME=="")
	  {
	  	 $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_EVALU_STAFFS'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $BY_EVALU_NAME=$ROW1["STAFF_NAME"];
	    $BY_EVALU_NAME=$BY_EVALU_NAME."("."<font color='red'>"._("用户已删除")."</font>".")";
	   }
   $APPROVE_PERSON_NAME=substr(GetUserNameById($APPROVE_PERSON),0,-1);
  
  if($EVALUATION_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("评定对象")?></td>
      <td nowrap align="center"><?=_("批准人")?></td>
      <td nowrap align="center"><?=_("获取职称")?></td>
      <td nowrap align="center"><?=_("获取方式")?></td>
      <td nowrap align="center"><?=_("获取时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$EVALUATION_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$BY_EVALU_NAME?></td>
      <td nowrap align="center"><?=$APPROVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$POST_NAME?></td>
      <td nowrap align="center"><?=$GET_METHOD?></td>
      <td nowrap align="center"><?=$RECEIVE_TIME=="0000-00-00"?"":$RECEIVE_TIME;?></td>
      <td align="center">
      <a href="javascript:;" onClick="window.open('evaluation_detail.php?EVALUATION_ID=<?=$EVALUATION_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?EVALUATION_ID=<?=$EVALUATION_ID?>"> <?=_("修改")?></a>&nbsp;
			<a href="javascript:delete_evaluation(<?=$EVALUATION_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
      </td>
	</tr>
<?
}

if($EVALUATION_COUNT==0)
{
   Message("",_("无符合条件的职称评定信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选职称评定信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
