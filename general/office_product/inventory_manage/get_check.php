<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$where = "";
if(isset($PRO_ID) && $PRO_ID!='-1')
{
    $where = "PRO_ID='$PRO_ID'";
}
else
{
    $PRO_NAME = (mb_detect_encoding($PRO_NAME,"UTF-8, gbk")==MYOA_CHARSET || (mb_detect_encoding($PRO_NAME,"UTF-8,gbk")!="UTF-8" && MYOA_CHARSET=="gbk")) ? $PRO_NAME : iconv('utf-8', MYOA_CHARSET, $PRO_NAME);
    if($PRO_NAME=="")
    {
        $PRO_NAME = is_default_charset($_POST['PRO_NAME']) ? $_POST['PRO_NAME'] : iconv('utf-8', MYOA_CHARSET, $_POST['PRO_NAME']);
    }
    $PRO_NAME_ARRAY = explode("/",$PRO_NAME);
    if(preg_match('/\d+/',$PRO_NAME_ARRAY[1],$arr))
    {
        $stock = $arr[0];
    }
    $where = "PRO_NAME = '{$PRO_NAME_ARRAY[0]}' and PRO_STOCK = '$stock'";
}

$sql = "SELECT PRO_MAXSTOCK,PRO_STOCK FROM office_products WHERE ".$where;
$cursor = exequery(TD::conn(),$sql);
if($ROW = mysql_fetch_array($cursor))
{
    $PRO_MAXSTOCK = $ROW['PRO_MAXSTOCK'];
    $stock        = $ROW['PRO_STOCK'];
}

$this_stock = $stock+$TRANS_QTY;

if($this_stock>$PRO_MAXSTOCK && $PRO_MAXSTOCK != 0)
{
    echo 'error';
}else
{
    echo 'ok';
}
?>

