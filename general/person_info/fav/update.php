<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(!is_number($URL_NO))
{
    Message("",_("序号必须为整数"));
    Button_Back();
    exit;
}

$query="select * from URL where URL_NO='$URL_NO' and URL_DESC='$URL_DESC' and URL='$URL' and USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_TYPE='2' and URL_ID!='$URL_ID'";
$cursor= exequery(TD::conn(),$query,true);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该收藏已存在"));
  Button_Back();
  exit;
}

if($OPEN_WINDOW == "on")
   $URL = "1:".$URL;

$query="update URL set URL_NO='$URL_NO',URL_DESC='$URL_DESC',URL='$URL',URL_TYPE='2' where USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_ID='$URL_ID'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
