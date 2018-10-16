<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("办公用品详情");
include_once("inc/header.inc.php");
// $query1= "select a.PRO_AUDITER,a.PRO_NAME,c.MANAGER,c.PRO_KEEPER 
// from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer 
// join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY= c.ID where a.PRO_ID='$PRO_ID'";
 
$query_type = "select a.*,b.PRO_NAME,b.PRO_PRICE,b.PRO_LOWSTOCK,b.PRO_MAXSTOCK,b.PRO_STOCK from office_transhistory a left outer join office_products b on a.PRO_ID=b.PRO_ID where TRANS_ID ='$id'";
$cursor_type= exequery(TD::conn(),$query_type);

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<style type="text/css">
.align-left td{
    text-align: left;
}
</style>
<body marginwidth="0" marginheight="0">
<h3>申领详情</h3>
<div class="container" style="width:700px;">
<table class="table table-bordered center table-hover ">
<tbody class="align-left" >
<? 
if($ROW_TYPE=mysql_fetch_array($cursor_type))
{
    $tag_tyle = array(
        '1'=>_('领用'),
        '2'=>_('借用'),
        '3'=>_('归还'),
        '4'=>_('报废'),
        '5'=>_('维护')
    );
?>
<tr>
<td nowrap="" width="30%" ><?=_("申请类型:")?></td>
<td><?=$tag_tyle[$ROW_TYPE['TRANS_FLAG']]?></td>
</tr>
<tr>
<td nowrap="" width="30%"><?=_("申请人:")?></td>
<td><?=substr(GetUserNameById($ROW_TYPE['BORROWER']),0,-1)?></td>
</tr>
<tr>
<td nowrap="" width="30%"><?=_("部门审批状态:")?></td>
<td><?=$ROW_TYPE['DEPT_STATUS']==1?_("已审批"):_("未审批")?></td>
</tr>
<tr>
<td nowrap="" width="30%"><?=_("申请时间:")?></td>
<td><?=$ROW_TYPE['TRANS_DATE']?></td>
</tr>
<tr>
<td nowrap=""><?=_("办公用品名称:")?></td>
<td><?=$ROW_TYPE['PRO_NAME']?>(<?=_("库存:").$ROW_TYPE['PRO_STOCK']?>)</td>
</tr>
<tr>
</tr>
<tr>
<td nowrap=""><?=_("警戒库存范围:")?></td>
<td><?=$ROW_TYPE['PRO_LOWSTOCK']._("至").$ROW_TYPE['PRO_MAXSTOCK']?></td>
</tr>
<tr>
<td nowrap=""><?=_("单价:")?></td>
<td><?=$ROW_TYPE['PRO_PRICE']._("元")?></td>
</tr>
<tr>
<td nowrap=""><?=_("申请数量:")?></td>
<td><?=abs($ROW_TYPE['TRANS_QTY'])?></td>
</tr>
<? 
}
?>
</tbody>
<tfoot align="center" class="TableFooter">
    <tr>
        <td colspan="2" nowrap="">
            <input type="button" value="<?=_("返回")?>" class="btn" onClick="javascript:history.go(-1)">
        </td>
    </tr>
</tfoot>
</table>
</div>
</body>
</html>