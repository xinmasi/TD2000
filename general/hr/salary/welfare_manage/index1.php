<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("员工福利管理");
include_once("inc/header.inc.php");
?>


<script>
function delete_welfare(WELFARE_ID)
{
  msg='<?=_("确认要删除该项员工福利信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?WELFARE_ID=" + WELFARE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("要删除员工福利信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项员工信息吗？")?>';
  if(window.confirm(msg))
  {
    URL="delete.php?WELFARE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=URL;
  }
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
   $query = "SELECT count(*) from HR_WELFARE_MANAGE where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理员工福利")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("福利项目")?></td>
      <td nowrap align="center"><?=_("工资月份")?></td>
      <td nowrap align="center"><?=_("发放日期")?></td>
      <td nowrap align="center"><?=_("福利金额")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
$query = "SELECT * from  HR_WELFARE_MANAGE where ".$WHERE_STR." order by PAYMENT_DATE desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$WELFARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $WELFARE_COUNT++;

   $WELFARE_ID=$ROW["WELFARE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WELFARE_MONTH=$ROW["WELFARE_MONTH"];
   $PAYMENT_DATE=$ROW["PAYMENT_DATE"];
   $WELFARE_ITEM=$ROW["WELFARE_ITEM"];
   $WELFARE_PAYMENT=$ROW["WELFARE_PAYMENT"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 $WELFARE_ITEM=get_hrms_code_name($WELFARE_ITEM,"HR_WELFARE_MANAGE");	 
?>
	<tr class="TableData">
    <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$WELFARE_ID?>" onClick="check_one(self);">
    <td nowrap align="center"><?=$STAFF_NAME1?></td>
    <td nowrap align="center"><?=$WELFARE_ITEM?></td>
    <td nowrap align="center"><?=$WELFARE_MONTH?></td>
    <td nowrap align="center"><?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?></td>
    <td nowrap align="center"><?=$WELFARE_PAYMENT?></td>
    <td nowrap align="center">
     <a href="javascript:;" onClick="window.open('welfare_detail.php?WELFARE_ID=<?=$WELFARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
     <a href="modify.php?WELFARE_ID=<?=$WELFARE_ID?>"> <?=_("修改")?></a>&nbsp;
	   <a href="javascript:delete_welfare(<?=$WELFARE_ID?>);"> <?=_("删除")?></a>&nbsp;
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
       <a href="javascript:delete_mail();" title="<?=_("删除所选员工福利信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("无员工福利记录"));	
}
?>
</table>
</body>
</html>