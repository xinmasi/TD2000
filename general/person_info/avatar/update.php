<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");


$HTML_PAGE_TITLE = _("昵称与头像");
include_once("inc/header.inc.php");

?>

<body>

<?
if($NICK_NAME!="")
{
   $query ="select NICK_NAME from USER_EXT,USER where USER.UID=USER_EXT.UID and USER_EXT.NICK_NAME='$NICK_NAME' and USER.USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' or USER.USER_ID='$NICK_NAME' or USER.USER_NAME='$NICK_NAME'";
   $cursor=exequery(TD::conn(),$query,true);
   if(mysql_num_rows($cursor)>0)
   {
      Message(_("错误"),_("昵称不能设置为当前用户名或昵称已被其他人员使用，请更换昵称"));
      Button_Back();
      exit;
   }
}

$NICK_NAME     = addslashes($NICK_NAME);
$BBS_SIGNATURE = addslashes($BBS_SIGNATURE);


//------------------- 保存 -----------------------
$query ="update USER_EXT set NICK_NAME='$NICK_NAME',BBS_SIGNATURE='$BBS_SIGNATURE' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);



Message(_("提示"),_("已保存修改"));
cache_users();
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='index.php?start=<?=$start?>&IS_MAIN=1'"></center>    
</body>
</html>
