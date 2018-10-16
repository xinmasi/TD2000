<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("产品管理");
include_once("general/crm/inc/header.php");
$PARA_TARGET =  "product_main";
$PARA_URL    =  "/module/crm2010/product/select/product.php";
$TYPE_URL    =  "/module/crm2010/product/type_list.php?PARA_TARGET=".$PARA_TARGET."&PARA_URL=".$PARA_URL;
?>

<frameset rows="40,*"  cols="*" frameborder="NO" border="0" framespacing="0" id="frame1">
    <frame name="product_title" scrolling="no" noresize src="product_title.php" frameborder="NO">
    <frameset rows="*"  cols="150,*" frameborder="no" border="0" framespacing="0" id="frame2">
       <frame name="type_list" scrolling="auto" noresize src="<?=$TYPE_URL?>" frameborder="no">
       <frame name="product_main" scrolling="auto" src="<?=$PARA_URL?>" frameborder="no">
    </frameset>
</frameset>