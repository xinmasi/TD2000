<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("������˹���");
include_once("inc/header.inc.php");
?>


<script>
function out_confirm(OUT_ID)
{
  msg='<?=_("ȷ��Ҫ��������˹�����")?>';
  if(window.confirm(msg))
  {
     URL="back.php?OUT_ID="+OUT_ID;
     window.location=URL;
  }
}

function consignment()
{
  selected_str=get_checked();
  if(selected_str=="")
  {
     alert("<?=_("������ѡ��һ�������¼��")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫ��������˹�����")?>';
  if(window.confirm(msg))
  {
    url="back.php?SELECTED_STR="+selected_str;
    location=url;
  }
}

function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
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

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("������˹���")?></span>
    </td>
  </tr>
</table>
<br>
<div align=center>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<?
 $query = "SELECT * from ATTEND_OUT,USER where ATTEND_OUT.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='1' and STATUS='0' order by USER.USER_ID";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OUT_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OUT_COUNT++;
   $OUT_ID=$ROW["OUT_ID"];
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $ALLOW=$ROW["ALLOW"];
   $CREATE_DATE1=$ROW["CREATE_DATE"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $SUBMIT_TIME1=substr($SUBMIT_TIME,0,10);
   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

   $OUT_TYPE=str_replace("<","&lt",$OUT_TYPE);
   $OUT_TYPE=str_replace(">","&gt",$OUT_TYPE);
   $OUT_TYPE=stripslashes($OUT_TYPE);

    if($OUT_COUNT==1)
    {
?>
    <table class="TableList" width="95%">
<?
    }

    if($OUT_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
    	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$OUT_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=substr(GetUserNameById($LEADER_ID),0,-1)?></td>
      <td><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME2?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center">
      <a href="javascript:out_confirm('<?=$OUT_ID?>');"><?=_("���������")?></a>
      </td>
    </tr>
<?
 }

 if($OUT_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("���ԭ��")?></td>
      <td nowrap align="center"><?=_("��ʼʱ��")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ǽ�")?>IP</td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    <tr class="TableControl" style="text-align: left">
    	<td colspan="10">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
         <label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;&nbsp;
         <a href="javascript:consignment();" title="<?=_("�������������")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inmail.gif" align="absMiddle"><?=_("�������������")?></a>&nbsp;
      </td>
    </tr>
    </table>
  </div>
<?
 }
 else
    message("",_("�������������¼"));
?>
</div>
<br><br><br>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
</center>
</form>
</body>
</html>