<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�½�Ա��������Ϣ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
	<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($PAYMENT_DATE!="" && !is_date($PAYMENT_DATE))
{
   Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

//------------------- ����Ա��������Ϣ -----------------------
$STAFF_ARRAY=explode(",",$STAFF_NAME);
$STAFF_ARRAY_NUM=sizeof($STAFF_ARRAY);
if($STAFF_ARRAY[$STAFF_ARRAY_NUM-1]=="")$STAFF_ARRAY_NUM--;
for($I=0;$I < $STAFF_ARRAY_NUM;$I++)
{	   
   $query="insert into HR_WELFARE_MANAGE(CREATE_USER_ID,CREATE_DEPT_ID,STAFF_NAME,PAYMENT_DATE,WELFARE_MONTH,WELFARE_ITEM,FREE_GIFT,WELFARE_PAYMENT,TAX_AFFAIRS,REMARK,ADD_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','".$STAFF_ARRAY[$I]."','$PAYMENT_DATE','$WELFARE_MONTH','$WELFARE_ITEM','$FREE_GIFT','$WELFARE_PAYMENT','$TAX_AFFAIRS','$REMARK','$CUR_TIME')";
   exequery(TD::conn(),$query);
}

Message("",_("�ɹ�����Ա��������Ϣ��"));
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>