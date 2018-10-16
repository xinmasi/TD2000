<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("职称评定管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
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
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA管理员 档案管理员 新建人 
$WHERE_STR = hr_priv("BY_EVALU_STAFFS");
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_TITLE_EVALUATION where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理职称评定")?></span>&nbsp;
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("评定对象")?></td>
      <td nowrap align="center"><?=_("批准人")?></td>
      <td nowrap align="center"><?=_("获取职称")?></td>
      <td nowrap align="center"><?=_("获取方式")?></td>
      <td nowrap align="center"><?=_("获取时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$EVALUATION_COUNT=0;
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
   
	 $GET_METHOD_NAME=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");
	 
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
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$EVALUATION_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$BY_EVALU_NAME?></td>
      <td nowrap align="center"><?=$APPROVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$POST_NAME?></td>
      <td nowrap align="center"><?=$GET_METHOD_NAME?></td>
      <td nowrap align="center"><?=$RECEIVE_TIME=="0000-00-00"?"":$RECEIVE_TIME;?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('evaluation_detail.php?EVALUATION_ID=<?=$EVALUATION_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?EVALUATION_ID=<?=$EVALUATION_ID?>"> <?=_("修改")?></a>&nbsp;
			<a href="javascript:delete_evaluation(<?=$EVALUATION_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选职称评定信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("无职称评定信息记录"));	
}
?>
</table>
</body>

</html>
