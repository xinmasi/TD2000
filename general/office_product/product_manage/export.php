<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../function_type.php");
ob_end_clean();

$EXCEL_OUT = array (
    _("�칫��Ʒ����"),
    _("�칫��Ʒ��"),
    _("�칫��Ʒ���"),
    _("����"),
    _("����"),
    _("�칫��Ʒ����"),
    _("������λ"),
    _("��Ӧ��"),
    _("��;�����"),
    _("��߾�����"),
    _("��ǰ���"),
    _("������"),
    _("�Ǽ�Ȩ��(�û�)"),
    _("������"),
    _("�Ǽ�Ȩ��(����)"),
    _("�Ǽ�����")
);

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�칫��Ʒ"));
$objExcel->addHead($EXCEL_OUT);


function GETDEPOSITORY($OFFICE_PROTYPE) {
    $ARRAY = array ();
    $query = "SELECT OFFICE_TYPE.TYPE_NAME as TYPE_NAME,OFFICE_DEPOSITORY.DEPOSITORY_NAME as DEPOSITORY_NAME from OFFICE_DEPOSITORY,OFFICE_TYPE where OFFICE_TYPE.ID= '$OFFICE_PROTYPE' and OFFICE_TYPE.TYPE_DEPOSITORY=OFFICE_DEPOSITORY.ID  ";
    $cursor = exequery ( TD::conn (), $query );
    if ($ROW = mysql_fetch_array ( $cursor )) {
        $TYPE_NAME = $ROW ["TYPE_NAME"];
        $DEPOSITORY_NAME = $ROW ["DEPOSITORY_NAME"];
    } else {
        $TYPE_NAME = _("δ����칫��Ʒ");
        $DEPOSITORY_NAME = "";
    }
    $ARRAY = array (
        $TYPE_NAME,
        $DEPOSITORY_NAME
    );
    return $ARRAY;
}

$where ="";
if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
    $where = " AND ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT' or PRO_CREATOR='".$_SESSION["LOGIN_USER_ID"]."')";
}


$office_query = "select * from office_products where OFFICE_PROTYPE = '$_GET[TYPE]'".$where;


if($_POST['project-id']=='')
{
    if($mytag=='0')
    {
        $office_query = "select * from office_products where PRO_NAME LIKE '%$PRO_NAME%'".$where;
    }
    else
    {
        if($OFFICE_DEPOSITORY==-1)
        {
            $office_query = "select * from office_products";
        }elseif($OFFICE_PROTYPE==-1)
        {
            $OFFICE_DEPOSITORY=$OFFICE_DEPOSITORY==''?'0':$OFFICE_DEPOSITORY;
            $office_query="select * from office_products where OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)".$where;
        }elseif($PRO_ID==-1)
        {
            $office_query = "select * from office_products where OFFICE_PROTYPE = '".$OFFICE_PROTYPE."'".$where;
        }else
        {
            $office_query = "select * from office_products where PRO_ID = '".$PRO_ID."'".$where;
        }
    }
}else
{
    $office_query = "select * from office_products where PRO_ID = '".$_POST['project-id']."'".$where;
}


$cursor = exequery(TD::conn(), $office_query);
while($ROW = mysql_fetch_array($cursor))
{

    $OFFICE_ARRAY = GETDEPOSITORY ($ROW["OFFICE_PROTYPE"]);
    $OFFICE_PROTYPE = $OFFICE_ARRAY [0];
    $DEPOSITORY_NAME = $OFFICE_ARRAY [1];

    $PRO_MANAGER = GetUserNameById($ROW["PRO_MANAGER"]);
    $PRO_CREATOR = GetUserNameById($ROW["PRO_CREATOR"]);
    $PRO_AUDITER = GetUserNameById($ROW["PRO_AUDITER"]);
    if($ROW["PRO_DEPT"] == "ALL_DEPT" || $ROW["PRO_DEPT"] == "")
    {
        $PRO_DEPT = _("ȫ�岿��");
    }else{
        $PRO_DEPT = GetDeptNameById($ROW["PRO_DEPT"]);
    }
    if($ROW["OFFICE_PRODUCT_TYPE"] == '2')
    {
        $OFFICE_PRODUCT_TYPE = _("����");
    }else{
        $OFFICE_PRODUCT_TYPE = _("����");
    }
    $EXCEL_OUT = '"' . str_replace ( '"', '""', $ROW["PRO_NAME"]) . '","' . $DEPOSITORY_NAME . '","' . $OFFICE_PROTYPE . '","' . str_replace ( '"', '""', $ROW["PRO_CODE"]) . '","' . str_replace('"', '""', $ROW["PRO_PRICE"]) . '","' . str_replace ( '"', '""', $ROW["PRO_DESC"]) . '","' . str_replace ( '"', '""', $ROW["PRO_UNIT"]) . '","' . str_replace ( '"', '""', $ROW["PRO_SUPPLIER"]) . '","' . $ROW["PRO_LOWSTOCK"] . '","' . $ROW["PRO_MAXSTOCK"] . '","' . $ROW["PRO_STOCK"] . '","' . $PRO_CREATOR . '","' . $PRO_MANAGER . '","' . $PRO_AUDITER . '","' . $PRO_DEPT . '","' . $OFFICE_PRODUCT_TYPE . '"';
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();

?>
