<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("��ٴ����ټ�ȷ��");
include_once("inc/header.inc.php");
?>


<script>
//����޸�
function leave_confirm(LEAVE_ID)
{
	URL="update_back_time.php?LEAVE_ID="+LEAVE_ID;
	myleft=(screen.availWidth-650)/2;
	window.open(URL,"formul_edit","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
//����
function consignment()
{
  selected_str=get_checked();
  if(selected_str=="")
  {
     alert("<?=_("������ѡ��һ����ټ�¼��")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫ������������ٲ�ȷ��������")?>';
  if(window.confirm(msg))
  {
    url="back.php?SELECTED_STR="+selected_str;
    location=url;
  }
}

function check_all()
{
    var allbox = document.getElementsByName("allbox")[0];
    for (i=0;i<document.all("email_select").length;i++)
    {
        if(allbox.checked)
        {
            document.all("email_select").item(i).checked=true;
        }
        else
        {
            document.all("email_select").item(i).checked=false;
        }
    }

    if(i == 0)
    {
        if(allbox.checked)
        {
            document.all("email_select").checked=true;
        }
        else
        {
            document.all("email_select").checked=false;
        }
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("����������ٲ�ȷ������")?></span>
    </td>
  </tr>
</table>
<br>
<div align=center>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<?
 $query = "SELECT * from ATTEND_LEAVE,USER where ATTEND_LEAVE.USER_ID=USER.USER_ID and LEADER_ID ='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and ALLOW='1' order by LEAVE_DATE2 desc";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $LEAVE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $LEAVE_COUNT++;
   $LEAVE_ID=$ROW["LEAVE_ID"];
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
   $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
   $REASON=$ROW["REASON"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $DEPT_ID=intval($DEPT_ID);
   $query1="select DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    $LEAVE_TYPE=str_replace("<","&lt",$LEAVE_TYPE);
    $LEAVE_TYPE=str_replace(">","&gt",$LEAVE_TYPE);
    $LEAVE_TYPE=stripslashes($LEAVE_TYPE);

    if($LEAVE_COUNT==1)
    {
?>
    <table class="TableList" width="95%">
<?
    }

    if($LEAVE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
    	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$LEAVE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><b><?=$USER_DEPT_NAME?></b></td>
      <td nowrap align="center"><b><?=$USER_NAME?></b></td>
      <td nowrap align="center"><b><?=substr(GetUserNameById($LEADER_ID),0,-1)?></b></td>
      <td>
      <?
        echo $LEAVE_TYPE;
        if($REASON!="")
        {
          echo "<br>";
          echo _("<font color=red>δ׼ԭ��$REASON</font>");
        }
      ?>
      </td>
      <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
      <td nowrap align="center"><?=$ANNUAL_LEAVE?></td>
      <td nowrap align="center"><?=$LEAVE_DATE1?></td>
      <td nowrap align="center"><?=$LEAVE_DATE2?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center">
      <a href="javascript:leave_confirm('<?=$LEAVE_ID?>');"><?=_("�����ٲ�ȷ��")?></a>
      </td>
    </tr>
<?
 }
 if($LEAVE_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("���ԭ��")?></td>
      <td nowrap align="center"><?=_("�������")?></td>
      <td nowrap align="center"><?=_("ռ���ݼ�")?></td>
      <td nowrap align="center"><?=_("��ʼʱ��")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ǽ�")?>IP</td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    <tr class="TableControl" style="text-align: left">
    	<td colspan="11">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
         <label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;&nbsp;
         <a href="javascript:consignment();" title="<?=_("���������ٲ�ȷ��")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inmail.gif" align="absMiddle"><?=_("���������ٲ�ȷ��")?></a>&nbsp;
      </td>
    </tr>
 </table>
</div>
<?
 }
 else
    message("",_("����ٴ����ټ�¼"));
?>
</div>
<br><br><br>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
</center>
</form>
</body>
</html>