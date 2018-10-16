<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(!is_number($WIN_NO))
{
    Message("",_("序号必须为整数"));
    Button_Back();
    exit;
}
$query="select * from WINEXE where WIN_NO='$WIN_NO' and WIN_DESC='$WIN_DESC' and WIN_PATH='$WIN_PATH' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and WIN_ID!='$WIN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该快捷方式已存在"));
  Button_Back();
  exit;
}

$query="update WINEXE set WIN_NO='$WIN_NO',WIN_DESC='$WIN_DESC',WIN_PATH='$WIN_PATH' where WIN_ID='$WIN_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
