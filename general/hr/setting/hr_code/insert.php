<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加代码");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT * from HR_CODE where CODE_NO='$CODE_NO' and PARENT_NO=''";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("提示"),_("代码编号").$CODE_NO._("已存在！"));
   Button_Back();
   exit;
}

$query="insert into HR_CODE (CODE_NO,CODE_NAME,CODE_ORDER) values ('$CODE_NO','$CODE_NAME','$CODE_ORDER')";
exequery(TD::conn(),$query);
Message("",_("增加成功！"));
?>

<script>
parent.location.reload();
</script>


</body>
</html>
