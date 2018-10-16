<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("代出差人归来");
include_once("inc/header.inc.php");
?>


<script>
function evection_confirm(EVECTION_ID)
{
  msg='<?=_("确认要代替出差人归来吗？")?>';
  if(window.confirm(msg))
  {
     URL="back.php?EVECTION_ID="+EVECTION_ID;
     window.location=URL;
  }
}

function consignment()
{
  selected_str=get_checked();
  if(selected_str=="")
  {
     alert("<?=_("请至少选择一条出差记录。")?>");
     return;
  }
 
  msg='<?=_("确认要代替出差人归来吗？")?>';
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("代出差人归来")?></span>
    </td>
  </tr>
</table>
<br>
<div align=center>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<?
 $query = "SELECT * from ATTEND_EVECTION,USER where ATTEND_EVECTION.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and ALLOW='1' order by USER.USER_ID";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $EVECTION_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $EVECTION_COUNT++;
   $EVECTION_ID=$ROW["EVECTION_ID"];
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");

   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");

   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $REASON=$ROW["REASON"]; 
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($EVECTION_COUNT==1)
    {
?>
    <table class="TableList" width="95%">
<?
    }

    if($EVECTION_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
    	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$EVECTION_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=substr(GetUserNameById($LEADER_ID),0,-1)?></td>
      <td nowrap align="center"><?=$EVECTION_DEST?></td>
      <td><?=$REASON?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center">
      <a href="javascript:evection_confirm('<?=$EVECTION_ID?>');"><?=_("代出差人归来")?></a>
      </td>
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("出差地区")?></td>
      <td nowrap align="center"><?=_("出差原因")?></td>
      <td nowrap align="center"><?=_("开始时间")?></td>
      <td nowrap align="center"><?=_("结束时间")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    <tr class="TableControl" style="text-align: left">
    	<td colspan="10">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
         <label for="allbox_for"><?=_("全选")?></label>&nbsp;&nbsp;
         <a href="javascript:consignment();" title="<?=_("批量代出差人归来")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inmail.gif" align="absMiddle"><?=_("批量代出差人归来")?></a>&nbsp;
      </td>
    </tr>
    </table>
  </div>
<?
 }
 else
    message("",_("无出差代归来记录"));
?>
</div>	
<br><br><br>
<center>	
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
</center>
</form>
</body>
</html>