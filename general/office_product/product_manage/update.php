<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");
include_once("inc/itask/itask.php");
$HTML_PAGE_TITLE = _("�ύ��Ʒ�޸�");
include_once("inc/header.inc.php");
//������Ʒ�������ID��ѯ�ֿ����Ա��USER_ID
function SelectStockMan($OFFICE_PROTYPE){
    $MANAGER  = "";
    $query1   = "SELECT TYPE_DEPOSITORY FROM office_type WHERE ID = '$OFFICE_PROTYPE'";
    $cursor1  = exequery(TD::conn(),$query1);
    if($ROW1  = mysql_fetch_array($cursor1)){
        $TYPE_DEPOSITORY = $ROW1["TYPE_DEPOSITORY"];
        $query2   = "SELECT MANAGER FROM office_depository WHERE ID = '$TYPE_DEPOSITORY'";
        $cursor2  = exequery(TD::conn(),$query2);
        if($ROW2  = mysql_fetch_array($cursor2)){
            $MANAGER = $ROW2["MANAGER"];
        }
    }
    return $MANAGER;
}
//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
//��ѯ��֮ǰ�Ŀ������
$query  = "SELECT * FROM office_products WHERE PRO_ID='$PRO_ID'";
$cursor = exequery(TD::conn (),$query);
if($ROW = mysql_fetch_array($cursor)){
    $PRO_STOCK_OLD = $ROW["PRO_STOCK"];
}
$query  = "UPDATE office_products SET PRO_NAME='$PRO_NAME',PRO_DESC='$PRO_DESC',OFFICE_PROTYPE='$OFFICE_PROTYPE',PRO_CODE='$PRO_CODE',PRO_UNIT='$PRO_UNIT',PRO_PRICE='$PRO_PRICE',PRO_SUPPLIER='$PRO_SUPPLIER',PRO_LOWSTOCK='$PRO_LOWSTOCK',PRO_MAXSTOCK='$PRO_MAXSTOCK',PRO_STOCK='$PRO_STOCK',PRO_DEPT='$TO_ID',PRO_MANAGER='$COPY_TO_ID',PRO_CREATOR='$PRO_CREATOR',PRO_AUDITER='$AUDIT_TO_ID',PRO_ORDER='',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',OFFICE_PRODUCT_TYPE='$OFFICE_TYPE2',OFFICE_PRODUCT_TYPE='{$OFFICE_TYPE2}' where PRO_ID='$PRO_ID'";
$cursor = exequery ( TD::conn (), $query );
if($cursor)
{
    //���ֿ����Ա������������
    if($PRO_STOCK_OLD != $PRO_STOCK){
        $TO_USER = '';
        $query1   = "SELECT USER_ID FROM user WHERE USER_PRIV = 6";
        $cursor1  = exequery(TD::conn(),$query1);
        while($ROW=mysql_fetch_array($cursor1))
        {
            $TO_USER .= $ROW["USER_ID"].",";
        }
        $MANAGER_USER_ID = SelectStockMan($OFFICE_PROTYPE);
        if($MANAGER_USER_ID != ""){
            $TO_USER .= $MANAGER_USER_ID;
        }
        $MSG =$_SESSION["LOGIN_USER_NAME"]._("��").$PRO_NAME._("�ĵ�ǰ���������").$PRO_STOCK_OLD._("����Ϊ").$PRO_STOCK;
        send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_USER,46,$MSG,$REMIND_URL);
    }
    Message(_("��ʾ"), _("�޸ĳɹ�"));
}else
{
    Message(_("����"), _("�뷵������"));
}
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("����")?>" onclick="self.location=document.referrer;"></center>
