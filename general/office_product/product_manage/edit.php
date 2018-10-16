<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("../function_type.php");
ob_end_clean();

$HTML_PAGE_TITLE = _("办公用品编辑");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"    href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/product.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/product_manage.js"></script>
<script  type="text/javascript">
jQuery(document).ready(function(){

    jQuery('#OFFICE_DEPOSITORY option[value="<?=$DEPOSITORY?>"]').attr("selected", true);
    jQuery('#OFFICE_PROTYPE option[value="<?=$TYPE?>"]').attr("selected", true);
});
</script>
<script type="text/javascript">
//jQuery.noConflict();
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
String.prototype.trim= function()
{
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
</script>
<script type="text/javascript">
function delete_vote(PRO_ID,TYPE_ID)
{
    msg='<?=_("确认删除该用品吗？删除后该用品的全部信息将被清除！")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?DELETE_STR="+PRO_ID+"&CODE_ID="+TYPE_ID;
        window.location=URL;
    }
}

function depositoryOfType(id)
{
    if(id != '')
    {
        var url_data = "id="+id;
        jQuery.ajax({
            type: "POST",
            url: "type_ajax.php",
            data: url_data,
            success: function(data){
                jQuery('#OFFICE_TYPE').html(data);
            }
        });
    }else
    {
        jQuery('#OFFICE_PROTYPE').html('<option value="-1"><?=_("请选择")?></option>');
    }
}

function audit_to_id(it)
{
    var url_data = "id="+it;
    jQuery.ajax({
        type: "POST",
        url: "audit_ajax.php",
        data: url_data,
        success: function(data){
            jQuery('#AUDIT_TOID').html(data);
        }
    });
}

function SelectManager(MODULE_ID,TO_ID, TO_NAME, FORM_NAME)
{
    URL="/module/product_manager/?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&ID=<?=$DEPOSITORY_ID?>";
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-100;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 250, 350);
}

function set_more(str,item,depository_id,pro_id)
{
    URL="/general/office_product/product_manage/set_more.php?item="+item+"&values="+str+"&depository_id="+depository_id+"&pro_id="+pro_id;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"set_more","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?PRO_ID=<?=$PRO_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>
<?
$query = "SELECT * from OFFICE_PRODUCTS where PRO_ID='$PRO_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PRO_ID              = $ROW["PRO_ID"];
    $PRO_NAME1           = $ROW["PRO_NAME"];
    $PRO_DESC1           = $ROW["PRO_DESC"];
    $PRO_UNIT1           = $ROW["PRO_UNIT"];
    $PRO_PRICE1          = $ROW["PRO_PRICE"];
    $PRO_SUPPLIER1       = $ROW["PRO_SUPPLIER"];
    $PRO_LOWSTOCK1       = $ROW["PRO_LOWSTOCK"];
    $PRO_MAXSTOCK1       = $ROW["PRO_MAXSTOCK"];
    $PRO_STOCK1          = $ROW["PRO_STOCK"];
    $TO_ID               = $ROW["PRO_DEPT"];
    $PRO_MANAGER1        = $ROW["PRO_MANAGER"];
    $PRO_AUDITER1        = $ROW["PRO_AUDITER"];
    $PRO_CREATOR1        = $ROW["PRO_CREATOR"];
    $OFFICE_PROTYPE1     = $ROW["OFFICE_PROTYPE"];
    $PRO_CODE1           = $ROW["PRO_CODE"];
    $PRO_ORDER           = $ROW["PRO_ORDER"];
    $ATTACHMENT_ID       = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME     = $ROW["ATTACHMENT_NAME"];
    $OFFICE_PRODUCT_TYPE = $ROW['OFFICE_PRODUCT_TYPE'];

}
$TO_NAME="";
if($TO_ID=="ALL_DEPT")
    $TO_NAME=_("全体部门");
else
    $TO_NAME=GetDeptNameById($TO_ID);

if($PRO_CREATOR1=="")
    $PRO_CREATOR1=$_SESSION["LOGIN_USER_ID"];

$PRO_MANAGERNAME=GetUserNameById($PRO_MANAGER1);

$PRO_CREATORNAME=GetUserNameById($PRO_CREATOR1);

$PRO_AUDITERNAME=GetUserNameById($PRO_AUDITER1);

?>
<body class="bodycolor" style="background:white;">
<div class="product_main_div">
    <form enctype="multipart/form-data"  action="update.php" id="check_product"  method="post" name="form1" style="margin:0px;">
        <h3><?=_("办公用品信息编辑")?></h3>
        <div class="wrap_center_div clear">
            <!-- 左侧布局开始 -->
            <div class="float_left info_left">
                <div class="flied_info">
                    <span class="public_font_color padding_left_1" ><?=_("办公用品名称")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <input type="text" name="PRO_NAME" class="filed_info_input"  value="<?=td_htmlspecialchars($PRO_NAME1)?>">
                </div>
                <div>
                    <span class="public_font_color padding_left_3"><?=_("登记类型")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <select name="OFFICE_TYPE2" class="filed_info_input">
                        <option value=""><?=_("请选择")?></option>
                        <option value='1' <?=$OFFICE_PRODUCT_TYPE==1?'selected':''?>><?=_("领用")?></option>
                        <option value='2' <?=$OFFICE_PRODUCT_TYPE==2?'selected':''?>><?=_("借用")?></option>
                    </select>
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_2" ><?=_("办公用品库")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <select name="OFFICE_DEPOSITORY"  onchange = "depositoryOfType(this.value);" id="OFFICE_DEPOSITORY" class="filed_info_input">
                        <option value=""><?=_("请选择")?></option>
                        <?
                        $query = "select * from OFFICE_DEPOSITORY where FIND_IN_SET(".$_SESSION["LOGIN_DEPT_ID"].",DEPT_ID) or DEPT_ID = '' or DEPT_ID = 'ALL_DEPT' or ".$_SESSION["LOGIN_USER_PRIV"]."= 1";
                        $cursor = exequery(TD::conn(),$query);
                        while($ROW = mysql_fetch_array($cursor))
                        {
                            echo "<option value=".$ROW['OFFICE_TYPE_ID'].">".$ROW['DEPOSITORY_NAME']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_1"><?=_("办公用品类别")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <span id="OFFICE_TYPE">
                        <select name="OFFICE_PROTYPE"  id ='OFFICE_PROTYPE' class="filed_info_input" >
                            <?
                            $query = "SELECT * FROM OFFICE_TYPE WHERE ID IN ($DEPOSITORY)";
                            $cursor = exequery(TD::conn(),$query);
                            while($ROW = mysql_fetch_array($cursor))
                            {
                                echo "<option value=".$ROW['ID'].">".$ROW['TYPE_NAME']."</option>";
                            }
                            ?>
                        </select>
                    </span>
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_1"><?=_("办公用品编码")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <input type="text" name="PRO_CODE"  value="<?=td_htmlspecialchars($PRO_CODE1)?>" class="filed_info_input">
                    <input type="hidden" name="iExpand" value="<?=$iExpand?>">
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_3"><?=_("当前库存")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <input type="text" name="PRO_STOCK"  value="<?=$PRO_STOCK1?>" class="filed_info_input">
                    <? if ($PRO_STOCK1 > $PRO_MAXSTOCK1 && $PRO_MAXSTOCK1!=0) echo "<font color=red>"._("大于最高警戒库存")."</font>"; if($PRO_STOCK1<$PRO_LOWSTOCK1) echo "<font color=red>"._("小于最低警戒库存")."</font>";?>
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_1"><?=_("最低警戒库存")?>&nbsp;</span>
                    <input type="text" name="PRO_LOWSTOCK"  value="<?=$PRO_LOWSTOCK1?>" class="filed_info_input">
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_1"><?=_("最高警戒库存")?>&nbsp;</span>
                    <input type="text" name="PRO_MAXSTOCK" value="<?=$PRO_MAXSTOCK1?>" class="filed_info_input">
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_1"><?=_("审批权限(用户)")?></span>
                    <input type="hidden" name="AUDIT_TO_ID" value="<?=$PRO_AUDITER1?>">
                    <textarea cols=37 name="AUDIT_TO_NAME" rows=2 wrap="yes" readonly class="filed_info_textarea"><?=td_trim($PRO_AUDITERNAME)?></textarea>
                    <a href="javascript:;" class="orgAdd"  onClick="SelectManager('','AUDIT_TO_ID', 'AUDIT_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('AUDIT_TO_ID', 'AUDIT_TO_NAME')"><?=_("清空")?></a>
                </div>
                <div style="margin-left: 130px;">
                    <a href="javascript:;" onClick="set_more('<?=$PRO_AUDITER1?>','SHENPI_ID','<?=$_GET['DEPOSITORY_ID']?>','<?=$PRO_ID?>')"><?=_("将此设置应用到本库其他物品")?></a>
                </div>
            </div>
            <!-- 左侧布局结束 -->
            <!-- 右侧布局开始 -->
            <div  class="float_left info_right" style="margin-left:30px;">
                <div class="flied_info">
                    <span class="public_font_color padding_left_7"><?=_("规格/型号")?><font style="color: red;padding-left: 1px;">*</font></span>
                    <textarea cols=35 name="PRO_DESC" rows="1"  wrap="yes" class="filed_info_textarea"><?=$PRO_DESC1?></textarea>
                </div>
                <div class="flied_info" style="clear:both;display:none;" id="attachment2">
                    <span class="public_font_color padding_left_6" style="float:left;"><?=_("附件文档")?></span>
                    <span id="attachment_content" style="float:left;margin-left:18px;margin-top:3px;"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1)?></span>
                </div>
                <div class="flied_info" id="attachment1" style="clear:both;">
                    <div style="float:left;">
                        <span class="public_font_color padding_left_6" id="ATTACH_LABEL"><?=_("附件上传")?></span>
                    </div>
                    <div style="float:left;margin-left:15px;">
                        <script>ShowAddFile();</script>
                        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
                    </div>
                </div>
                <div class="flied_info" style="clear:both;">
                    <span class="public_font_color padding_left_6" ><?=_("计量单位")?></span>
                    <input type="text" name="PRO_UNIT" class="filed_info_input"  value="<?=td_htmlspecialchars($PRO_UNIT1)?>">
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_5"><?=_(" 单价")?></span>
                    <input type="text" name="PRO_PRICE" class="filed_info_input" value="<?=td_htmlspecialchars($PRO_PRICE1)?>">(<?=_("元")?>)
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_4"><?=_(" 供应商")?></span>
                    <input type="text" name="PRO_SUPPLIER" class="filed_info_input" value="<?=td_htmlspecialchars($PRO_SUPPLIER1)?>">
                </div>
                <div class="flied_info">
                    <span class="public_font_color padding_left_4"><?=_(" 创建人")?></span>
                    <input type="hidden" name="PRO_CREATOR" value="<?=$PRO_CREATOR1?>" >
                    <input type="text"  class="filed_info_input" name="PRO_CREATORNAME"  value="<?=td_trim($PRO_CREATORNAME)?>" readonly>
                    <!--<span style="color: green;"><?=_("创建人可以修改自己创建的办公用品信息。")?></span>-->
                </div>
                <div class="flied_info">
                    <span class="public_font_color"><?=_(" 登记权限(用户)")?></span>
                    <input type="hidden" name="COPY_TO_ID" value="<?=$PRO_MANAGER1?>">
                    <textarea cols=37 name="COPY_TO_NAME" rows=2 class="filed_info_textarea"  wrap="yes" readonly><?=td_trim($PRO_MANAGERNAME)?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('126','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
                    <a href="javascript:;" onClick="set_more('<?=$PRO_MANAGER1?>','DENGJI_USER','<?=$_GET['DEPOSITORY_ID']?>','<?=$PRO_ID?>')"><?=_("将此设置应用到本库其他物品")?></a>
                </div>
                <div class="flied_info">
                    <span class="public_font_color"><?=_(" 登记权限(部门)")?></span>
                    <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                    <input type="hidden" name="OLD_TYPE" value="<?=$OFFICE_PROTYPE1?>">
                    <textarea cols=37 name=TO_NAME rows=2 class="filed_info_textarea" wrap="yes" readonly><?=td_trim($TO_NAME)?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                    <a href="javascript:;" onClick="set_more('<?=$TO_ID?>','DENGJI_DEPT','<?=$_GET['DEPOSITORY_ID']?>','<?=$PRO_ID?>')"><?=_("将此设置应用到本库其他物品")?></a><br/><br/>
                    <div style="color:green;"><?=_("有登记权限的用户或部门，可以申请领用、借用该办公用品")?></div>
                </div>
            </div>
            <!-- 右侧布局结束 -->
        </div>
        <div class="bottom">
            <input type="button" value="<?=_("保存")?>" onClick="check_product_manage()" class="btn  btn-primary bottom_left" title="<?=_("修改办公用品")?>" name="button" style="margin-top:10px;width:65px;">
            <input type="button" value="<?=_("删除")?>"  class="btn btn-danger" style="margin-top:10px;width:65px;" onClick="delete_vote('<?=$PRO_ID?>','<?=$OFFICE_PROTYPE1?>');">
            <?
            if ($iExpand=="1")
                echo '<input type="button" value='._("返回").'  class="btn  btn-warning" style="margin-right:5px;margin-top:10px;width:65px;" onClick="javascript:window.history.go(-1);">';
            ?>
        </div>
        <input type="hidden" value="<?=$PRO_ID?>" name="PRO_ID">
        <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
        <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
        <input type="hidden" value="PRO_NAME=<?=$PRO_NAME?>&OFFICE_PROTYPE=<?=$OFFICE_PROTYPE?>&PRO_DESC=<?=$PRO_DESC?>&PAGE_START=<?=$PAGE_START?>&LOW_Stock=<?=$LOW_Stock?>" name="QUERY_LIST">
    </form>
</div>
</body>
</html>
<script type="text/javascript">
window.onload = function(){
    jQuery(function(){
        var selected_category='<?=$OFFICE_PROTYPE1?>';
        jQuery('#OFFICE_DEPOSITORY').attr("value", '<?=$DEPOSITORY?>');
        jQuery('#OFFICE_PROTYPE option[value='+selected_category+']').attr('selected',true);
        if(jQuery('#attachment_content').text()!=='')
        {
            jQuery('#attachment2').show();
        }

    });
}
</script>