<?
include_once("inc/auth.inc.php");
ob_end_clean();

$SAL_ITEM="";
$query = "SELECT ITEM_ID,ITEM_NAME from SAL_ITEM ORDER BY `ITEM_ID`";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $SAL_ITEM.=",".$ROW["ITEM_NAME"];
}

$SAL_ITEM.=","._("���ջ���");
$SAL_ITEM.=","._("���ϱ���");
$SAL_ITEM.=","._("��λ����");
$SAL_ITEM.=","._("��������");
$SAL_ITEM.=","._("ҽ�Ʊ���");
$SAL_ITEM.=","._("��λҽ��");
$SAL_ITEM.=","._("����ҽ��");
$SAL_ITEM.=","._("��������");
$SAL_ITEM.=","._("��λ����");
$SAL_ITEM.=","._("ʧҵ����");
$SAL_ITEM.=","._("��λʧҵ");
$SAL_ITEM.=","._("����ʧҵ");
$SAL_ITEM.=","._("���˱���");
$SAL_ITEM.=","._("��λ����");
$SAL_ITEM.=","._("ס��������");
$SAL_ITEM.=","._("��λס��");
$SAL_ITEM.=","._("����ס��");

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME".$SAL_ITEM.",MEMO";
else
   $EXCEL_OUT=_("�û���").","._("����").$SAL_ITEM;

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա��н�����"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>