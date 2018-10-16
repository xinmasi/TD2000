<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("聊天室");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',120000);
</script>

<script>
function delete_chatroom(CHAT_ID)
{
 msg='<?=_("确认要删除该聊天室吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?CHAT_ID=" + CHAT_ID;
  window.location=URL;
 }
}
function delete_record(CHAT_ID)
{
 msg='<?=_("确认要删除聊天记录吗？")?>';
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("创建新的聊天室")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
  <input type="button"  value="<?=_("新建聊天室")?>" class="BigButton" onClick="location='new/?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>&DEPT_ID=<?=$_SESSION["LOGIN_DEPT_ID"]?>';" title="<?=_("创建聊天室，并指定参会范围和聊天室时间")?>">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理已创建的聊天室")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
   Message("",_("无已创建的聊天室"));
   exit;
 }

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理已创建的聊天室")?></span><br>
    </td>

    <td valign="bottom" class="small1"><?=sprintf(_("共%s个聊天室"),"<span class='big4'>&nbsp;".$CHATROOM_COUNT."</span>&nbsp;")?>
    </td>
    </tr>
</table>

<br>
<table class="TableList" width="60%" align="center" >
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("聊天室名称")?></td>
      <td nowrap align="center"><?=_("当前状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
 //============================ 显示已创建聊天室 =======================================
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
       $CHATROOM_STATUS_STR="<font color='#00AA00'><b>"._("开放")."</span>";
    }
    else
    {
          $CHATROOM_STATUS=2;
          $CHATROOM_STATUS_STR="<font color='#FF0000'><b>"._("已关闭")."</span>";
    }
?>
    <tr class="TableData">
      <td align="center"><?=$SUBJECT?></td>
      <td nowrap align="center"><?=$CHATROOM_STATUS_STR?></td>
      <td nowrap align="center">
      <a href="javascript:delete_chatroom('<?=$CHAT_ID?>');"> <?=_("删除")?></a>
<?
      if($CHATROOM_STATUS==1)
      {
?>
      <a href="manage.php?CHAT_ID=<?=$CHAT_ID?>&OPERATION=1"> <?=_("关闭")?></a>
<?
      }
      else if($CHATROOM_STATUS==2)
      {
?>
      <a href="manage.php?CHAT_ID=<?=$CHAT_ID?>&OPERATION=2"> <?=_("恢复开放")?></a>
<?
      }
?>
      <a href="javascript:delete_record('<?=$CHAT_ID?>');"> <?=_("删除聊天记录")?></a>
      </td>
    </tr>
<?
 }
?>

</table>
</body>

</html>
