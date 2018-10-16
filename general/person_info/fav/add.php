<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

while (list($key, $value) = each($_POST))
   $$key=$value;

$HTML_PAGE_TITLE = _("增加收藏");
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

$query="select * from URL where URL_NO='$URL_NO' and URL_DESC='$URL_DESC' and URL='$URL' and USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_TYPE='2'";
$cursor= exequery(TD::conn(),$query,true);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该收藏已存在"));
  Button_Back();
  exit;
}

if($OPEN_WINDOW == "on")
   $URL = "1:".$URL;

$query="insert into URL(URL_NO,URL_DESC,URL,USER,URL_TYPE) values ($URL_NO,'$URL_DESC','$URL','".$_SESSION["LOGIN_USER_ID"]."','2')";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
