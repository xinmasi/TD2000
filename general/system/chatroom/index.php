<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',120000);
</script>

<script>
function delete_chatroom(CHAT_ID)
{
 msg='<?=_("ȷ��Ҫɾ������������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?CHAT_ID=" + CHAT_ID;
  window.location=URL;
 }
}
function delete_record(CHAT_ID)
{
 msg='<?=_("ȷ��Ҫɾ�������¼��")?>';
 if(window.confirm(msg))
 {
  URL="delete_record.php?CHAT_ID=" + CHAT_ID;
  window.location=URL;
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�����µ�������")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
  <input type="button"  value="<?=_("�½�������")?>" class="BigButton" onClick="location='new/?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&DEPT_ID=<?=$_SESSION["LOGIN_DEPT_ID"]?>';" title="<?=_("���������ң���ָ���λ᷶Χ��������ʱ��")?>">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<?
 $CUR_TIME=date("Y-m-d H:i:s",time());

 $query = "SELECT count(*) from CHATROOM";
 $cursor= exequery(TD::conn(),$query);
 $CHATROOM_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $CHATROOM_COUNT=$ROW[0];

 if($CHATROOM_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�����Ѵ�����������")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
   Message("",_("���Ѵ�����������"));
   exit;
 }

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�����Ѵ�����������")?></span><br>
    </td>

    <td valign="bottom" class="small1"><?=sprintf(_("��%s��������"),"<span class='big4'>&nbsp;".$CHATROOM_COUNT."</span>&nbsp;")?>
    </td>
    </tr>
</table>

<br>
<table class="TableList" width="60%" align="center" >
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("����������")?></td>
      <td nowrap align="center"><?=_("��ǰ״̬")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>

<?
 //============================ ��ʾ�Ѵ��������� =======================================
 $CUR_TIME=date("Y-m-d H:i:s",time());

 $query = "SELECT * from CHATROOM";
 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $CHAT_ID=$ROW["CHAT_ID"];
    $SUBJECT=$ROW["SUBJECT"];

    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
    $SUBJECT=stripslashes($SUBJECT);

    $STOP=$ROW["STOP"];

    if($STOP=="0")
    {
       $CHATROOM_STATUS=1;
       $CHATROOM_STATUS_STR="<font color='#00AA00'><b>"._("����")."</span>";
    }
    else
    {
          $CHATROOM_STATUS=2;
          $CHATROOM_STATUS_STR="<font color='#FF0000'><b>"._("�ѹر�")."</span>";
    }
?>
    <tr class="TableData">
      <td align="center"><?=$SUBJECT?></td>
      <td nowrap align="center"><?=$CHATROOM_STATUS_STR?></td>
      <td nowrap align="center">
      <a href="javascript:delete_chatroom('<?=$CHAT_ID?>');"> <?=_("ɾ��")?></a>
<?
      if($CHATROOM_STATUS==1)
      {
?>
      <a href="manage.php?CHAT_ID=<?=$CHAT_ID?>&OPERATION=1"> <?=_("�ر�")?></a>
<?
      }
      else if($CHATROOM_STATUS==2)
      {
?>
      <a href="manage.php?CHAT_ID=<?=$CHAT_ID?>&OPERATION=2"> <?=_("�ָ�����")?></a>
<?
      }
?>
      <a href="javascript:delete_record('<?=$CHAT_ID?>');"> <?=_("ɾ�������¼")?></a>
      </td>
    </tr>
<?
 }
?>

</table>
</body>

</html>
