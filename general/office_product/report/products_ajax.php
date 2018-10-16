<?
include_once("inc/auth.inc.php");
ob_end_clean();
echo '<select name="PRO_ID" id="PRO_ID" onchange = "depositoryOfProid(this.value);">';
echo '<option value="-1">'._("«Î—°‘Ò").'</option>';
$query_products = "select * from OFFICE_PRODUCTS where OFFICE_PROTYPE = '$id'";
$cursor_products= exequery(TD::conn(),$query_products);
while($ROW_PRODUCTS=mysql_fetch_array($cursor_products))
{
    $PRO_NAME=td_htmlspecialchars($ROW_PRODUCTS['PRO_NAME']);
    $PRO_NAME=str_replace("\"","&quot;",$PRO_NAME);

    echo "<option value=".$ROW_PRODUCTS['PRO_ID'].">".$PRO_NAME._("/ø‚¥Ê").td_htmlspecialchars($ROW_PRODUCTS['PRO_STOCK'])."</option>";
}
?>