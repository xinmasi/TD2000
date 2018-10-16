<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("奖惩管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_incentive(INCENTIVE_ID)
{
  msg='<?=_("确认要删除该奖惩信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?INCENTIVE_ID=" + INCENTIVE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("要删除奖惩信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该奖惩信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?INCENTIVE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
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
$WHERE_STR = hr_priv("STAFF_NAME");

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_INCENTIVE where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理奖惩信息")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("奖惩项目")?></td>
      <td nowrap align="center"><?=_("奖惩日期")?></td>
      <td nowrap align="center"><?=_("奖惩属性")?></td>
      <td nowrap align="center"><?=_("奖惩金额")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_INCENTIVE where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$INCENTIVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $INCENTIVE_COUNT++;

   $INCENTIVE_ID=$ROW["INCENTIVE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
   $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"]=="0000-00-00"?"":$ROW["INCENTIVE_TIME"];
   $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
   $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
   $ADD_TIME=$ROW["ADD_TIME"]=="0000-00-00"?"":$ROW["ADD_TIME"]; 
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 if($STAFF_NAME1=="")
	 {
	   $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
       $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
          $STAFF_NAME1=$ROW1["STAFF_NAME"];

	    $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("用户已删除")."</font>".")";
	  }
	 $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
	 $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");

?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$INCENTIVE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$INCENTIVE_ITEM?></td>
      <td nowrap align="center"><?=$INCENTIVE_TIME?></td>
      <td nowrap align="center"><?=$INCENTIVE_TYPE?></td>
      <td nowrap align="center"><?=$INCENTIVE_AMOUNT?><?=_("元")?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('incentive_detail.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>"> <?=_("修改")?></a>&nbsp;
			<a href="javascript:delete_incentive(<?=$INCENTIVE_ID?>);"> <?=_("删除")?></a>&nbsp;
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
       <a href="javascript:delete_mail();" title="<?=_("删除所选奖惩信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("无奖惩信息记录"));	
}
?>
</table>
</body>

</html>
