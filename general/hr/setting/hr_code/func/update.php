<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改代码");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT * from HR_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO' and CODE_ID!='$CODE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("提示"),_("代码编号").$CODE_NO._("已存在！"));
   Button_Back();
   exit;
}

$query="update HR_CODE set CODE_NO='$CODE_NO',CODE_NAME='$CODE_NAME',CODE_ORDER='$CODE_ORDER' where CODE_ID='$CODE_ID'";
exequery(TD::conn(),$query);

$query = "SELECT * from HR_CODE where CODE_NO='$PARENT_NO' and PARENT_NO=''";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CODE_ID=$ROW["CODE_ID"];
    
header("location:index.php?CODE_ID=$CODE_ID");
?>