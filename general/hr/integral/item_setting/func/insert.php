<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ӻ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

if($ITEM_NAME == "")
{
   Message(_("��ʾ"),_("���������Ʋ���Ϊ�գ�"));
   Button_Back();
   exit;
}

$query = "SELECT * from hr_integral_item where TYPE_ID='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	if($ROW['ITEM_NO']==$ITEM_NO)
	{
		Message(_("��ʾ"),sprintf(_("��������%s�Ѵ��ڣ�"),$ITEM_NO));
		Button_Back();
		exit;
	}
	if($ROW['ITEM_NAME']==$ITEM_NAME)
	{
		Message(_("��ʾ"),sprintf(_("����������<%s>�Ѵ��ڣ�"),$ITEM_NAME));
		Button_Back();
		exit;
	}
   
}

$query="insert into hr_integral_item (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('$ITEM_NO','$ITEM_NAME','$TYPE_ID','$ITEM_BRIEF','$ITEM_VALUE','$CREATE_PERSON','$CREATE_TIME','$USED','$ITEM_ORDER','$WEIGHT')";
//echo $query;
//exit;
exequery(TD::conn(),$query);
Message("",_("���ӳɹ���"));
?>

<div align="center">
<input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back();">
</div>


</body>
</html>
