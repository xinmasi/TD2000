<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

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

$query="select * from URL where URL='".$_POST["URL"]."' and USER=''";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该网址已存在"));
  Button_Back();
  exit;
}

$SUB_TYPE = $URL_TYPE == "1" ? $SUB_TYPE : "";

if($URL_TYPE == 3)
{
	$query="select * from URL where URL_DESC='$URL_DESC' and URL_TYPE = 3 and USER=''";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		Message(_("错误"),_("该应用已存在"));
  		Button_Back();
  		exit;			
	}
	
}
	$query="insert into URL(URL_NO,URL_DESC,URL,URL_TYPE,SUB_TYPE,URL_ICON) values ($URL_NO,'$URL_DESC','".$_POST["URL"]."','$URL_TYPE','$SUB_TYPE','$EWP_ICONURL')";

exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
