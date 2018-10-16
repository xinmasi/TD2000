<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加积分项");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

if($ITEM_NAME == "")
{
   Message(_("提示"),_("积分项名称不能为空！"));
   Button_Back();
   exit;
}

$query = "SELECT * from hr_integral_item where TYPE_ID='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	if($ROW['ITEM_NO']==$ITEM_NO)
	{
		Message(_("提示"),sprintf(_("积分项编号%s已存在！"),$ITEM_NO));
		Button_Back();
		exit;
	}
	if($ROW['ITEM_NAME']==$ITEM_NAME)
	{
		Message(_("提示"),sprintf(_("积分项名称<%s>已存在！"),$ITEM_NAME));
		Button_Back();
		exit;
	}
   
}

$query="insert into hr_integral_item (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('$ITEM_NO','$ITEM_NAME','$TYPE_ID','$ITEM_BRIEF','$ITEM_VALUE','$CREATE_PERSON','$CREATE_TIME','$USED','$ITEM_ORDER','$WEIGHT')";
//echo $query;
//exit;
exequery(TD::conn(),$query);
Message("",_("增加成功！"));
?>

<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
</div>


</body>
</html>
