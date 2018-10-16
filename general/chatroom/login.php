<?
//URL:webroot\general\chatroom\login.php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ÁÄÌìÊÒµÇÂ¼");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.USER_NAME.value=="")
   { alert("<?=_("êÇ³Æ²»ÄÜÎª¿Õ£¡")?>");
     return (false);
   }
   return (true);
}

</script>

<body class="bodycolor" onLoad="document.form1.USER_NAME.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/chatroom.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("ÁÄÌìÊÒ - %s - µÇÂ¼"),$SUBJECT)?></span><br>
    </td>
    </tr>
</table>

<br>

<?
//============================ êÇ³Æ =======================================
//$query = "SELECT NICK_NAME from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$query = "SELECT NICK_NAME from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
$USER_IP=get_client_ip();
if($ROW=mysql_fetch_array($cursor))
   $NICK_NAME=$ROW["NICK_NAME"];
?>

<div align="center">
<b>

 <form action="chat/index.php" name="form1" onSubmit="return CheckForm();">
	<span class="big1"><?=_("êÇ³Æ£º")?></span><input type="text" name="USER_NAME" size="10" maxlength="25" class="Biginput" value="<?=$NICK_NAME?>">
	<input type="hidden" value="<?=$CHAT_ID?>" name="CHAT_ID">
	<input type="hidden" value="<?=$USER_IP?>" name="USER_IP">
        <input type="submit" value="<?=_("½øÈëÁÄÌìÊÒ")?>" class="BigButton" name="button">
 </form>
</font>
</b>
</div>

<?
Button_Back();
?>

</body>
</html>