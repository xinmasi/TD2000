<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�޸Ļ��������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

if($TYPE_NAME == "")
{
   Message(_("��ʾ"),_("���������Ʋ���Ϊ�գ�"));
   Button_Back();
   exit;
}

$query = "SELECT * from hr_integral_item_type where TYPE_FROM = '3' and TYPE_ID!='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	if($ROW['TYPE_NO']==$TYPE_NO)
	{
		Message(_("��ʾ"),sprintf(_("��������%s�Ѵ��ڣ�"),$ITEM_NO));
		Button_Back();
		exit;
	}
	if($ROW['TYPE_NAME']==$TYPE_NAME)
	{
		Message(_("��ʾ"),sprintf(_("����������<%s>�Ѵ��ڣ�"),$ITEM_NAME));
		Button_Back();
		exit;
	}
}




$query="update HR_INTEGRAL_ITEM_TYPE set TYPE_NO='$TYPE_NO',TYPE_NAME='$TYPE_NAME',TYPE_ORDER='$TYPE_ORDER',TYPE_BRIEF='$TYPE_BRIEF',TYPE_FROM='$TYPE_FROM' where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

Message("",_("�޸ĳɹ���"));
Button_Close();
?>

<!--<script>
parent.location.reload();
</script>-->

</body>
</html>
