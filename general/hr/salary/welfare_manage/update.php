<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("Ա�������޸ı���");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------�Ϸ���У��-------------------------------------
if($PAYMENT_DATE!="" && !is_date($PAYMENT_DATE))
{
   Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_WELFARE_MANAGE 
		              SET 
			STAFF_NAME='$STAFF_NAME',
			WELFARE_MONTH='$WELFARE_MONTH',
			PAYMENT_DATE='$PAYMENT_DATE',
			WELFARE_ITEM='$WELFARE_ITEM',
			WELFARE_PAYMENT='$WELFARE_PAYMENT',
			FREE_GIFT='$FREE_GIFT',
			TAX_AFFAIRS='$TAX_AFFAIRS',
			REMARK='$REMARK',
			ADD_TIME='$CUR_TIME' 
		  WHERE WELFARE_ID = '$WELFARE_ID'";
exequery(TD::conn(),$query);

header("location:index1.php?WELFARE_ID=$WELFARE_ID&connstatus=1")

?>
</body>
</html>
