<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");


$HTML_PAGE_TITLE = _("�ǳ���ͷ��");
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
      Message(_("����"),_("�ǳƲ�������Ϊ��ǰ�û������ǳ��ѱ�������Աʹ�ã�������ǳ�"));
      Button_Back();
      exit;
   }
}

$NICK_NAME     = addslashes($NICK_NAME);
$BBS_SIGNATURE = addslashes($BBS_SIGNATURE);


//------------------- ���� -----------------------
$query ="update USER_EXT set NICK_NAME='$NICK_NAME',BBS_SIGNATURE='$BBS_SIGNATURE' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);



Message(_("��ʾ"),_("�ѱ����޸�"));
cache_users();
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='index.php?start=<?=$start?>&IS_MAIN=1'"></center>    
</body>
</html>
