<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("调拨");
include_once("inc/header.inc.php");


if($PRO_ID == $PRO_ID1 || $OFFICE_PROTYPE == $OFFICE_PROTYPE1)
{
    Message(_("错误"),_("调拨信息有误,请重新填写"));
    ?>
    <br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="parent.location.href='change_index.php'"></center>
    <?
    exit;
}

$sql="SELECT * FROM office_products WHERE PRO_ID = '$PRO_ID'";
$res = exequery(TD::conn(),$sql);
$arr = mysql_fetch_array($res);

if($PRO_ID1!='-1')
{
    $sql="SELECT * FROM office_products WHERE PRO_ID = '$PRO_ID1'";
    $res = exequery(TD::conn(),$sql);
    $arr2 = mysql_fetch_array($res);
}


$mytime = date('YmyHis',time());

if($PRO_ID1 =='-1')
{
    $query = "SELECT * FROM office_products WHERE OFFICE_PROTYPE = '$OFFICE_PROTYPE1' AND PRO_NAME = '{$arr['PRO_NAME']}' AND ALLOCATION = 1";
    $cursor= exequery(TD::conn(),$query);
    if(mysql_affected_rows()>0)
    {
        if($rows=mysql_fetch_assoc($cursor))
        {
            $query_u = "UPDATE office_products SET PRO_STOCK=PRO_STOCK+'$TRANS_QTY' where PRO_ID='{$rows['PRO_ID']}'";
            $cursor_u= exequery(TD::conn(),$query_u);
        }
    }
    else
    {
        $query_add1 = "INSERT INTO office_products(PRO_NAME,PRO_DESC,OFFICE_PROTYPE,PRO_CODE,PRO_UNIT,PRO_PRICE,PRO_SUPPLIER,PRO_LOWSTOCK,PRO_MAXSTOCK,PRO_STOCK
            ,PRO_CREATOR,PRO_ORDER,OFFICE_PRODUCT_TYPE,ALLOCATION) VALUES ('{$arr['PRO_NAME']}','{$arr['PRO_DESC']}','$OFFICE_PROTYPE1','$mytime','{$arr['PRO_UNIT']}','{$arr['PRO_PRICE']}','{$arr['PRO_SUPPLIER']}','{$arr['PRO_LOWSTOCK']}','{$arr['PRO_MAXSTOCK']}','$TRANS_QTY','{$_SESSION['LOGIN_USER_ID']}','{$arr['PRO_ORDER']}','{$arr['OFFICE_PRODUCT_TYPE']}','1')";
        $office_cursor = exequery(TD::conn(), $query_add1);
    }
}
else
{
    if($arr['PRO_NAME'] == $arr2['PRO_NAME'] &&  $arr2['ALLOCATION']=='1' && $arr['PRO_PRICE'] == $arr2['PRO_PRICE'] && $arr['OFFICE_PRODUCT_TYPE'] == $arr2['OFFICE_PRODUCT_TYPE'])
    {
        $query_2u = "UPDATE office_products SET PRO_STOCK=PRO_STOCK+'$TRANS_QTY' where PRO_ID='{$arr2['PRO_ID']}'";
        $cursor_u= exequery(TD::conn(),$query_2u);
    }else
    {
        $query_add1 = "INSERT INTO office_products(PRO_NAME,PRO_DESC,OFFICE_PROTYPE,PRO_CODE,PRO_UNIT,PRO_PRICE,PRO_SUPPLIER,PRO_LOWSTOCK,PRO_MAXSTOCK,PRO_STOCK
            ,PRO_CREATOR,PRO_ORDER,OFFICE_PRODUCT_TYPE,ALLOCATION) VALUES ('{$arr['PRO_NAME']}','{$arr['PRO_DESC']}','$OFFICE_PROTYPE1','$mytime','{$arr['PRO_UNIT']}','{$arr['PRO_PRICE']}','{$arr['PRO_SUPPLIER']}','{$arr['PRO_LOWSTOCK']}','{$arr['PRO_MAXSTOCK']}','$TRANS_QTY','{$_SESSION['LOGIN_USER_ID']}','{$arr['PRO_ORDER']}','{$arr['OFFICE_PRODUCT_TYPE']}','1')";
        $office_cursor = exequery(TD::conn(), $query_add1);
    }
}
$query_update = "UPDATE office_products SET PRO_STOCK=PRO_STOCK-'$TRANS_QTY' where PRO_ID='{$arr['PRO_ID']}'";
$cursor = exequery(TD::conn(),$query_update);

$NEW_PRO_STOCK=$arr['PRO_STOCK']-$TRANS_QTY;
if($NEW_PRO_STOCK==0 && $arr['ALLOCATION']=='1')
{
    $sql_del="DELETE FROM office_products WHERE PRO_ID = '{$arr['PRO_ID']}'";
    $cursor_del = exequery(TD::conn(),$sql_del);
}

$this_time = time();


$TYPE_NAME = get_type_name($OFFICE_PROTYPE);

$TYPE_NAME2 = get_type_name($OFFICE_PROTYPE1);


$contents = sprintf(_("办公用品[%s]从%s调拨%s个到%s下"),$arr['PRO_NAME'],$TYPE_NAME,$TRANS_QTY,$TYPE_NAME2);


$sql_log = "INSERT INTO office_log (type,user_id,office_str,add_time,remark) VALUES ('1','{$_SESSION['LOGIN_USER_ID']}','$contents','$this_time','')";
exequery(TD::conn(),$sql_log);


if($cursor)
{
    Message(_("提示"),_("调拨成功！"));
}else
{
    Message(_("错误"),_("请返回重试！"));
}
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="parent.location.href='change_index.php'"></center>













<!--


if($PRO_ID1 =='-1')//新增调拨
{
    $query_add1 = "insert into office_products(PRO_NAME,PRO_DESC,OFFICE_PROTYPE,PRO_CODE,PRO_UNIT,PRO_PRICE,PRO_SUPPLIER,PRO_LOWSTOCK,PRO_MAXSTOCK,PRO_STOCK,PRO_DEPT,PRO_MANAGER
            ,PRO_CREATOR,PRO_AUDITER,PRO_ORDER,ATTACHMENT_ID,ATTACHMENT_NAME,OFFICE_PRODUCT_TYPE) select PRO_NAME,PRO_DESC,OFFICE_PROTYPE,PRO_CODE,PRO_UNIT,PRO_PRICE,PRO_SUPPLIER,PRO_LOWSTOCK,PRO_MAXSTOCK,PRO_STOCK,PRO_DEPT,PRO_MANAGER
            ,PRO_CREATOR,PRO_AUDITER,PRO_ORDER,ATTACHMENT_ID,ATTACHMENT_NAME,OFFICE_PRODUCT_TYPE from office_products where PRO_ID ='$PRO_ID';";
    $office_cursor = exequery(TD::conn(), $query_add1);
    //echo $query_add1;exit;
    $new_id = mysql_insert_id();
    //更新库存
    $query_add  = "UPDATE office_products SET PRO_STOCK=PRO_STOCK-'$TRANS_QTY' where PRO_ID='$PRO_ID';";
    $cursor = exequery ( TD::conn (), $query_add );
    $mytime = date('YmyHis',time());
    $query_add2 = "UPDATE office_products SET PRO_STOCK ='$TRANS_QTY', OFFICE_PROTYPE = '$OFFICE_PROTYPE1',PRO_CODE = '$mytime' where PRO_ID='$new_id';";
    $cursor = exequery ( TD::conn (), $query_add2 );
    $contents = " 从办公用品id = '".$PRO_ID."' 调拨".$TRANS_QTY."个到 办公用品id = '".$new_id."'";
}
else //更新调拨 修改数量
{
    $query_update = "UPDATE office_products SET PRO_STOCK=PRO_STOCK-'$TRANS_QTY' where PRO_ID='$PRO_ID';";
    $cursor = exequery ( TD::conn (), $query_update );
    $query_update1 = "UPDATE office_products SET PRO_STOCK=PRO_STOCK+'$TRANS_QTY' where PRO_ID='$PRO_ID1';";
    $cursor = exequery ( TD::conn (), $query_update1 );

    $contents = " 从办公用品id = '".$PRO_ID."' 调拨".$TRANS_QTY."个到 办公用品id = ".$PRO_ID1."'";
}
if($cursor)
{
    Message ( _ ( "提示" ), _ ( "调拨成功！" ) );
    $html = "调拨成功:";
    $html .= $contents."\r\n";
    //file_put_contents("change.txt", $html, FILE_APPEND);
}
else
{
    Message ( _ ( "错误" ), _ ( "请返回重试！" ) );
    $html = "调拨失败:";
    $html .= $contents."\r\n";
    //file_put_contents("change.txt", $html, FILE_APPEND);
}
?>
<div style="margin-top: 10px; width: 65px;margin-left: auto;margin-right: auto;">
<input type="button" onClick="parent.location.href='change_index.php'" value="返回" class="btn  btn-success bottom_left" style="margin-top: 10px; width: 65px;margin-left: auto;margin-right: auto;">
</div>
-->