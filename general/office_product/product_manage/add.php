<?
include_once ("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("���Ӱ칫��Ʒ");
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
    Message(_("��ʾ"), _("��ӳɹ�"));
}else
{
    Message(_("����"), _("�뷵������"));
}

?>
<br><center><input type="button" class="BigButtonA" value="<?=_("����")?>" onclick="javascript:window.location='new.php'"></center>
