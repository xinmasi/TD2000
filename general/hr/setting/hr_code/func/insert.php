<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加代码");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT * from HR_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("提示"),_("代码编号").$CODE_NO._("已存在！"));
   Button_Back();
   exit;
}
    
$query="insert into HR_CODE (CODE_NO,CODE_NAME,CODE_ORDER,PARENT_NO) values ('$CODE_NO','$CODE_NAME','$CODE_ORDER','$PARENT_NO')";
exequery(TD::conn(),$query);
Message("",_("增加成功！"));

$query = "SELECT * from HR_CODE where CODE_NO='$PARENT_NO';";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $CODE_ID=$ROW["CODE_ID"];
}
?>

<center>
<input type="button" value="返回" class="BigButtonA" onclick="location='index.php?CODE_ID=<?=$CODE_ID?>';">
</center>


</body>
</html>
