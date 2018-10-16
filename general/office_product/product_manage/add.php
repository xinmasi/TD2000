<?
include_once ("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("增加办公用品");
include_once("inc/header.inc.php");


if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();   
    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$query="INSERT INTO office_products(PRO_NAME,PRO_DESC,OFFICE_PROTYPE,PRO_CODE,PRO_UNIT,PRO_PRICE,PRO_SUPPLIER,PRO_LOWSTOCK,PRO_MAXSTOCK,PRO_STOCK,PRO_DEPT,PRO_MANAGER,PRO_CREATOR,PRO_AUDITER,PRO_ORDER,ATTACHMENT_ID,ATTACHMENT_NAME,OFFICE_PRODUCT_TYPE)values('$PRO_NAME','$PRO_DESC','$OFFICE_PROTYPE','$PRO_CODE','$PRO_UNIT','$PRO_PRICE','$PRO_SUPPLIER','$PRO_LOWSTOCK','$PRO_MAXSTOCK','$PRO_STOCK','$TO_ID','$COPY_TO_ID','$PRO_CREATOR','$AUDIT_TO_ID','','$ATTACHMENT_ID','$ATTACHMENT_NAME','$OFFICE_TYPE2')";
$cursor = exequery ( TD::conn (), $query );
if($cursor)
{
    Message(_("提示"), _("添加成功"));
}else
{
    Message(_("错误"), _("请返回重试"));
}

?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="javascript:window.location='new.php'"></center>
