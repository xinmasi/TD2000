<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("������ַ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(!is_number($URL_NO))
{
    Message("",_("��ű���Ϊ����"));
    Button_Back();
    exit;
}

$query="select * from URL where URL='".$_POST["URL"]."' and USER=''";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("����"),_("����ַ�Ѵ���"));
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
		Message(_("����"),_("��Ӧ���Ѵ���"));
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
