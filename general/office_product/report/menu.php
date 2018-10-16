<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("办公用品登记报表");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>

<body>
<table>
    <tr>
        <td><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="18" HEIGHT="18" align="absmiddle"> <b><?=_("办公用品登记报表")?></b></td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=product_info.php"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("物品总表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=purchase.php&trans_flag=0"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("采购物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=dept_Sum.php"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("部门、人员领用物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td class="TableData">
            <a href="main.php?url=borrow_sum.php&trans_flag=2"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("借用物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=borrow_sum.php&trans_flag=3"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("归还物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=noreturn.php"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("未归还物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=report.php&trans_flag=4"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("报废物品报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td>
            <a href="main.php?url=repair.php"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("维护记录报表")?></b></a>
        </td>
    </tr>
    <tr>
        <td class="TableData">
            <a href="main.php?url=ledger.php"  target="main">
                <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/menu/office_product.gif" WIDTH="16" HEIGHT="16" align="absmiddle">
                <b><?=_("台帐报表")?></b></a>
        </td>
    </tr>
</table>
</body>
</html>
