<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���Ӱ�������ȷ�ϲ�ȷ��");
include_once("inc/header.inc.php");
?>


<script>
function overtime_confirm(OVERTIME_ID)
{
  msg='<?=_("ȷ��Ҫ����Ӱ�������ȷ�ϲ�ȷ����")?>';
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
     alert("<?=_("������ѡ��һ���Ӱ��¼��")?>");
     return;
  }
 
  msg='<?=_("ȷ��Ҫ����Ӱ�������ȷ�ϲ�ȷ����")?>';
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("���Ӱ���ȷ�����벢ȷ��")?></span>
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
        <a href=javascript:overtime_confirm('<?=$OVERTIME_ID?>');><?=_("������ȷ�ϲ�ȷ��")?></a>
      </td>
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("ѡ��")?></td>    	
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("�Ӱ�����")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("��ʼʱ��")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ǽ�")?>IP</td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    <tr class="TableControl" style="text-align: left">
    	<td colspan="10">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
         <label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;&nbsp;
         <a href="javascript:consignment();" title="<?=_("�������Ӱ�������ȷ�ϲ�ȷ��")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inmail.gif" align="absMiddle"><?=_("����������ȷ�ϲ�ȷ��")?></a>&nbsp;
      </td>
    </tr>    
    </table>
  </div>
<?
 }
 else
    message("",_("�޼Ӱ�ȷ�ϼ�¼"));
?>
</div>	
<br><br><br>
<center>	
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
</center>
</form>
</body>
</html>