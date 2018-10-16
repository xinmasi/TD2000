<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改积分项");
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
if($ITEM_NAME == "")
{
   Message(_("提示"),_("积分项编号不能为空！"));
   Button_Back();
   exit;
}

$query = "SELECT * from hr_integral_item where TYPE_ID='$TYPE_ID' and ITEM_ID!='$ITEM_ID'";
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

$query="update hr_integral_item set ITEM_NO='$ITEM_NO',ITEM_NAME='$ITEM_NAME',ITEM_ORDER='$ITEM_ORDER',ITEM_BRIEF='$ITEM_BRIEF',ITEM_VALUE='$ITEM_VALUE',USED='$USED',WEIGHT='$WEIGHT' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location:index.php?TYPE_ID=$TYPE_ID");
?>