<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("���ӿ�ݷ�ʽ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(!is_number($WIN_NO))
{
    Message("",_("��ű���Ϊ����"));
    Button_Back();
    exit;
}

$query="select * from WINEXE where WIN_NO='$WIN_NO' and WIN_DESC='$WIN_DESC' and WIN_PATH='$WIN_PATH' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("����"),_("�ÿ�ݷ�ʽ�Ѵ���"));
  Button_Back();
  exit;
}

$query="insert into WINEXE(WIN_NO,WIN_DESC,WIN_PATH,USER_ID) values ($WIN_NO,'$WIN_DESC','$WIN_PATH','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
