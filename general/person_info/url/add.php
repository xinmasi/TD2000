<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

while (list($key, $value) = each($_POST))
   $$key=$value;

$HTML_PAGE_TITLE = _("增加网址");
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

$query="select * from URL where URL='$URL' and USER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该网址已存在"));
  Button_Back();
  exit;
}

$SUB_TYPE = $URL_TYPE == "1" ? $SUB_TYPE : "";
$query="insert into URL(URL_NO,URL_DESC,URL,USER,URL_TYPE,SUB_TYPE) values ($URL_NO,'$URL_DESC','$URL','".$_SESSION["LOGIN_USER_ID"]."','$URL_TYPE','$SUB_TYPE')";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
