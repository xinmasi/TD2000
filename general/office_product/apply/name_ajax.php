<?
include_once("inc/auth.inc.php");
ob_end_clean();
$name=$_POST['name']==""?$_GET['term']:$_POST['name'];
$name=iconv("utf-8",MYOA_CHARSET,$name);

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
    $query = "SELECT PRO_NAME,PRO_STOCK,PRO_ID,PRO_PRICE,AVAILABLE,OFFICE_PROTYPE FROM office_products WHERE PRO_NAME LIKE '%$name%' ";
}
else
{
    $query = "select a.PRO_NAME,a.PRO_STOCK,a.PRO_ID,a.PRO_PRICE,a.AVAILABLE,a.OFFICE_PROTYPE from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY=c.ID where a.PRO_NAME like '%$name%' and ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',a.PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',a.PRO_DEPT)) or (a.PRO_MANAGER='' and a.PRO_DEPT='') or PRO_DEPT='ALL_DEPT') and (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',c.DEPT_ID) or c.DEPT_ID='ALL_DEPT' or c.DEPT_ID='')";
}

$cursor= exequery(TD::conn(),$query);
$option_str = array();

$this_date = date('Y-m-d',time());
$this_time = strtotime($this_date);

while($ROW=mysql_fetch_array($cursor))
{
    $AVAILABLE = $ROW['AVAILABLE'];
    $OFFICE_PROTYPE = $ROW['OFFICE_PROTYPE'];
    $sql="select * from office_depository where find_in_set('".$OFFICE_PROTYPE."',OFFICE_TYPE_ID)";
    $result= exequery(TD::conn(),$sql);
    if(mysql_num_rows($result)<=0)
    {
        continue;
    }
    if($AVAILABLE!="")
    {
        $time_array =  explode("|",$AVAILABLE);
        if($this_time>=$time_array[0] && $this_time<=$time_array[1])
        {
            continue;
        }
    }
    $value = iconv(MYOA_CHARSET, "utf-8",$ROW['PRO_NAME']._("/¿â´æ").$ROW['PRO_STOCK']);
    $option_str[] = array('id' => $ROW['PRO_ID'], 'text' => $value,'stock'=>$ROW['PRO_STOCK'],'pro_price'=>$ROW['PRO_PRICE']);
}
ob_end_clean();
header('Content-Type:text/javascript; charset=gbk');
echo json_encode($option_str);
?>