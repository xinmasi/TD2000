<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("���ÿ����Ű�����");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript">
function delete_dep(MANAGER_ID)
{
 msg='<?=_("ȷ��Ҫɾ��������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?MANAGER_ID=" + MANAGER_ID;
  window.location=URL;
 }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�༭�����Ű�����")?></span>
    </td>
  </tr>
</table>
<br><br>
<table class="TableBlock" width="70%" align="center" >
  <form action="update.php"  method="post" name="form1">  
   <tr>
      <td nowrap class="TableData">
<?
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_NAME=$ROW["DUTY_NAME"];
if($DUTY_TYPE=="99")
   $DUTY_NAME=_("�ְ���");
echo $DUTY_NAME._("��Ա��");   
?> 	
   	 </td>
      <td class="TableData"> 
<?
$query1 = "select USER_ID from USER_EXT where DUTY_TYPE='$DUTY_TYPE'";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{
	$USER_ID.=$ROW1["USER_ID"].",";
}
$USER_NAME=td_trim(GetUserNameById($USER_ID));
echo $USER_NAME;
?>
      </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=sprintf(_("����").$DUTY_NAME._("��Ա��"))?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
      <textarea cols="50" name="USER_NAME" rows="5" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','USER_ID', 'USER_NAME')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("���")?></a> 
    </td>
   </tr>
  <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$DUTY_TYPE?>" name="DUTY_TYPE">
      <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index.php'">
      </td>
    </td>
  </tr>
</form>
</table>
</body>
</html>