<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
$IS_USE_EMAILSEND=$_POST['IS_USE_EMAILSEND'];
$IS_USE_EMAILSEND=$IS_USE_EMAILSEND=="on" ? "1":"0";
$EMAIL_GETBOX=$_POST['EMAIL_GETBOX'];
$query_ext="update USER_EXT set IS_USE_EMAILSEND='$IS_USE_EMAILSEND',EMAIL_GETBOX='$EMAIL_GETBOX' where UID='".$_SESSION["LOGIN_UID"]."' ";   
exequery(TD::conn(),$query_ext);
Message(_("��ʾ"),_("�ѱ����޸�"));
?>
 <center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?IS_MAIN=1'"></center>
</body>
</html>