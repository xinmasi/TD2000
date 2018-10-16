<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改积分项分类");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

if($TYPE_NAME == "")
{
   Message(_("提示"),_("积分项名称不能为空！"));
   Button_Back();
   exit;
}

$query = "SELECT * from hr_integral_item_type where TYPE_FROM = '3' and TYPE_ID!='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	if($ROW['TYPE_NO']==$TYPE_NO)
	{
		Message(_("提示"),sprintf(_("积分项编号%s已存在！"),$ITEM_NO));
		Button_Back();
		exit;
	}
	if($ROW['TYPE_NAME']==$TYPE_NAME)
	{
		Message(_("提示"),sprintf(_("积分项名称<%s>已存在！"),$ITEM_NAME));
		Button_Back();
		exit;
	}
}




$query="update HR_INTEGRAL_ITEM_TYPE set TYPE_NO='$TYPE_NO',TYPE_NAME='$TYPE_NAME',TYPE_ORDER='$TYPE_ORDER',TYPE_BRIEF='$TYPE_BRIEF',TYPE_FROM='$TYPE_FROM' where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

Message("",_("修改成功！"));
Button_Close();
?>

<!--<script>
parent.location.reload();
</script>-->

</body>
</html>
