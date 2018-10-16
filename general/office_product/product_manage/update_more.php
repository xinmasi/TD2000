<?
include_once ("inc/auth.inc.php");
include_once ("inc/header.inc.php");

$PRO_ID_STR = '';
for($i = 1; $i <= $COUNT; $i ++)
{
    $temp = "pro_id_" . $i;
    $PRO_ID = $$temp;
    if ($PRO_ID != '')
        $PRO_ID_STR .= $PRO_ID . ",";
}
if ($PRO_ID_STR != '') {
    $PRO_ID_STR = td_trim ( $PRO_ID_STR );
    if ($item == 'SHENPI_ID')
        $query = "update office_products set PRO_AUDITER = '$values' where pro_id in ($PRO_ID_STR)";
    if ($item == 'DENGJI_USER')
        $query = "update office_products set PRO_MANAGER = '$values' where pro_id in ($PRO_ID_STR)";
    if ($item == 'DENGJI_DEPT')
        $query = "update office_products set PRO_DEPT = '$values' where pro_id in ($PRO_ID_STR)";

    exequery ( TD::conn (), $query );
}

Message("", _("设置成功"));
?>
<br />
<center>
    <input class="BigButton" type="button" value="<?=_("关闭")?>"
           onclick="window.close()">
</center>