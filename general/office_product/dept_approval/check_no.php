<?
include_once("inc/auth.inc.php");
ob_end_clean();
$pro_num = td_htmlspecialchars($pro_num);
$transid = td_htmlspecialchars($transid);
$id      = td_htmlspecialchars($id);

$query="select PRO_STOCK from `office_products` where PRO_ID='$id'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PRO_STOCK = $ROW['PRO_STOCK'];
}
if($pro_num>$PRO_STOCK)
{
    echo _("修改的数量大于库存数!");
}
else
{
    $pro_num_fu = $pro_num*(-1);
    $sql = "UPDATE office_transhistory SET TRANS_QTY='$pro_num_fu',FACT_QTY='$pro_num' WHERE TRANS_ID = '$transid'";
    exequery(TD::conn(),$sql);
    echo "OK";
}
?>