<?
include_once("inc/auth.inc.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

$HTML_PAGE_TITLE = _("增加积分项");
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

$query = "SELECT * from hr_integral_item_type where TYPE_FROM = '3'";
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

$query="insert into hr_integral_item_type (TYPE_NO,TYPE_NAME,TYPE_ORDER,TYPE_BRIEF,CREATE_PERSON,CREATE_TIME,TYPE_FROM) values ('$TYPE_NO','$TYPE_NAME','$TYPE_ORDER','$TYPE_BRIEF','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','3')";
exequery(TD::conn(),$query);
Message("",_("增加成功！"));

?>
<br />
<div align="center">
	<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php';"> 
</div>
</body>
</html>