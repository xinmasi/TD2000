<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("代加班人申请确认并确认");
include_once("inc/header.inc.php");
?>


<script>
function overtime_confirm(OVERTIME_ID)
{
  msg='<?=_("确认要代替加班人申请确认并确认吗？")?>';
  if(window.confirm(msg))
  {
     URL="back.php?OVERTIME_ID="+OVERTIME_ID;
     window.location=URL;
  }
}

function consignment()
{
  selected_str=get_checked();
  if(selected_str=="")
  {
     alert("<?=_("请至少选择一条加班记录。")?>");
     return;
  }
 
  msg='<?=_("确认要代替加班人申请确认并确认吗？")?>';
  if(window.confirm(msg))
  {
    url="back.php?SELECTED_STR="+selected_str;
    location=url;
  }
}

function check_all()
{
 
 for (i=0;i<document.all("email_select").length;i++)
 {
   if(allbox.checked)
      document.all("email_select").item(i).checked=true;
   else
      document.all("email_select").item(i).checked=false;
 }
 
 if(i==0)
 {
   if(allbox.checked)
      document.all("email_select").checked=true;
   else
      document.all("email_select").checked=false;
 }
}
 
function check_one(el)
{
   if(!el.checked)
      allbox.checked=false;
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

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("代加班人确认申请并确认")?></span>
    </td>
  </tr>
</table>    
<br>
<div align=center>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<?
 $query = "SELECT * from ATTENDANCE_OVERTIME,USER where ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and APPROVE_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='0' and ALLOW ='1' order by USER.USER_ID";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OVERTIME_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
     $OVERTIME_COUNT++;
     $OVERTIME_ID=$ROW["OVERTIME_ID"];
     $USER_ID=$ROW["USER_ID"];
     $USER_NAME=$ROW["USER_NAME"];
     $DEPT_ID=$ROW["DEPT_ID"];
     $APPROVE_ID=$ROW["APPROVE_ID"];
     $RECORD_TIME=$ROW["RECORD_TIME"];
     $START_TIME=$ROW["START_TIME"];
     $END_TIME=$ROW["END_TIME"];
     $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
     $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
     $REGISTER_IP=$ROW["REGISTER_IP"];
     $DEPT_ID=intval($DEPT_ID);
     $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
        $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OVERTIME_COUNT==1)
    {
?>
    <table class="TableList" width="95%">
<?
    }

    if($OVERTIME_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
    	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$OVERTIME_ID?>" onClick="check_one(self);">    	
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=substr(GetUserNameById($APPROVE_ID),0,-1)?></td>
      <td><?=$OVERTIME_CONTENT?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td nowrap align="center"><?=$START_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center">
        <a href=javascript:overtime_confirm('<?=$OVERTIME_ID?>');><?=_("代申请确认并确认")?></a>
      </td>
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("选择")?></td>    	
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("加班内容")?></td>
      <td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("开始时间")?></td>
      <td nowrap align="center"><?=_("结束时间")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    <tr class="TableControl" style="text-align: left">
    	<td colspan="10">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
         <label for="allbox_for"><?=_("全选")?></label>&nbsp;&nbsp;
         <a href="javascript:consignment();" title="<?=_("批量代加班人申请确认并确认")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inmail.gif" align="absMiddle"><?=_("批量代申请确认并确认")?></a>&nbsp;
      </td>
    </tr>    
    </table>
  </div>
<?
 }
 else
    message("",_("无加班确认记录"));
?>
</div>	
<br><br><br>
<center>	
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
</center>
</form>
</body>
</html>