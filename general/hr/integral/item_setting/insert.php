<?
include_once("inc/auth.inc.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

$HTML_PAGE_TITLE = _("���ӻ�����");
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

$query = "SELECT * from hr_integral_item_type where TYPE_FROM = '3'";
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

$query="insert into hr_integral_item_type (TYPE_NO,TYPE_NAME,TYPE_ORDER,TYPE_BRIEF,CREATE_PERSON,CREATE_TIME,TYPE_FROM) values ('$TYPE_NO','$TYPE_NAME','$TYPE_ORDER','$TYPE_BRIEF','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','3')";
exequery(TD::conn(),$query);
Message("",_("���ӳɹ���"));

?>
<br />
<div align="center">
	<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php';"> 
</div>
</body>
</html>