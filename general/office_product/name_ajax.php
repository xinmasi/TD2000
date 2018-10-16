<?
include_once("inc/auth.inc.php");
ob_end_clean();
$name=iconv("utf-8",MYOA_CHARSET,$name);
$query = "select a.PRO_NAME,a.PRO_STOCK,a.PRO_ID from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY=c.ID where a.PRO_NAME like '%$name%' and ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',a.PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',a.PRO_DEPT)) or (a.PRO_MANAGER='' and a.PRO_DEPT='') or PRO_DEPT='ALL_DEPT') and (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',c.DEPT_ID) or c.DEPT_ID='ALL_DEPT' or c.DEPT_ID='')";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $value=urlencode(iconv(MYOA_CHARSET, "utf-8",$ROW['PRO_ID']."|".$ROW['PRO_NAME']._("/¿â´æ").$ROW['PRO_STOCK']));
    echo "<a href='javascript:;' onclick='submitit(\"".$value."\")'>".$ROW['PRO_NAME']._("/¿â´æ").$ROW['PRO_STOCK']."</a><br>";
}
?>