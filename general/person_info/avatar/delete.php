<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");

$query = "SELECT SEX,AVATAR,PHOTO from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $AVATAR=$ROW["AVATAR"];
   $PHOTO=$ROW["PHOTO"];
   $SEX=$ROW["SEX"];
}

if($_GET['TYPE'] == 'PHOTO')
{
   delete_attach_old("photo", $PHOTO);   
   $PHOTO = '';
}
else if(strpos($AVATAR, "."))
{
   delete_attach_old("avatar", $AVATAR);
   $_SESSION['LOGIN_AVATAR']=$SEX;//头像不为带“.”的文件名称即可，无实际意义
   imtask("S^b^".$_SESSION["LOGIN_UID"]."^".$_SESSION['LOGIN_AVATAR']);
}

$query = "update USER set AVATAR='".$_SESSION["LOGIN_AVATAR"]."',PHOTO='$PHOTO' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

header("location:index.php?IS_MAIN=1");
?>