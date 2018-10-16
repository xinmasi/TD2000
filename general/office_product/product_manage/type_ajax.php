<?
include_once("inc/auth.inc.php");
ob_end_clean();
echo '<select name="OFFICE_PROTYPE" id ="OFFICE_PROTYPE" class="filed_info_input">';
echo '<option value="-1">'._("«Î—°‘Ò").'</option>';
if($id!="")
{
    $query_type = "select * from OFFICE_TYPE where ID in ($id)";
    $cursor_type= exequery(TD::conn(),$query_type);
    while($ROW_TYPE=mysql_fetch_array($cursor_type))
    {
        echo "<option value=".$ROW_TYPE['ID'].">".$ROW_TYPE['TYPE_NAME']."</option>";
    }
}
echo '</select>';
?>