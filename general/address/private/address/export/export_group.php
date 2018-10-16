<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导出分组");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>

$(function(){
    //我的分组选择
    $("#all_my_group").click(function(){
        //if($("#all_my_group").attr("checked")==true)
        if($("#all_my_group").is(":checked"))
        {
            $("[name='my_select']").attr("checked",'true');
        }
        else
        {
            $("[name='my_select']").removeAttr("checked");
        }
    })
    
    $("[name='my_select']").click(function(){
        $("#all_my_group").removeAttr("checked");
    })
    
    //共享分组选择
    $("#all_share_group").click(function(){
        if($("#all_share_group").is(":checked"))
        {
            $("[name='share_select']").attr("checked",'true');
        }
        else
        {
            $("[name='share_select']").removeAttr("checked");
        }
    })
    
    $("[name='share_select']").click(function(){
        $("#all_share_group").removeAttr("checked");
    })
});

function CheckForm()
{
    var export_str="";
    
    $("input[name='my_select']:checkbox").each(function(){ 
        if($(this).attr("checked")){
            export_str += $(this).val()+","
        }
    })
    $("input[name='share_select']:checkbox").each(function(){ 
        if($(this).attr("checked")){
            export_str += $(this).val()+","
        }
    })
    
    if(export_str=="")
    {
        alert("<?=_("请选择需要导出的分组！！！")?>");
        return;
    }
    
    document.form1.export_group.value = export_str;
    
	document.form1.submit();
}
</script>
<body>

<?
$s_group_str = "";
//============================ 个人分组 =======================================
$query = "SELECT * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO asc,GROUP_ID asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    
    $s_group_name = "";
    $s_group_name = (strlen($GROUP_NAME) > 10) ? csubstr($GROUP_NAME,0,10)."..." : $GROUP_NAME;
    
    $s_group_str .= '<li class="fz"><a href="#" style="color:#000;" title="'.$GROUP_NAME._("组").'"><label class="checkbox" style="height:44px; position:relative;padding-top:10px;">'.$s_group_name._("组").'<input type="checkbox" name="my_select" id="my_select" style=" position:absolute;right:0px;top:10px;" value="'.$GROUP_ID.'"/></label></a></li>';
}

$s_share_group = "";
//============================ 共享分组 =======================================
$query = "select * from ADDRESS_GROUP where SHARE_GROUP_ID!='' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER_ID) order by USER_ID desc,ORDER_NO asc,GROUP_NAME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    
    $s_share_name = "";
    $s_share_name = (strlen($GROUP_NAME) > 10) ? csubstr($GROUP_NAME,0,10)."..." : $GROUP_NAME;
    
    $s_share_group .= '<li class="fz"><a href="#" style="color:#000;" title="'.$GROUP_NAME._("组").'"><label class="checkbox" style="height:44px; position:relative;padding-top:10px;">'.$s_share_name._("组").'<input type="checkbox" name="share_select" id="share_select" style=" position:absolute;right:0px;top:10px;" value="'.$GROUP_ID.'" /></label></a></li>';
}
?>

<div class="dc" style="width: 100%;height:390px;">
    <form style="margin:0px; height:380px;" action="export_group_info.php" name="form1">
        <div class="dc_r" style="width: 100%;">
            <div id="radio_n" style="height: 380px;">
                <label class="radio" style="width:105px; position:absolute; top:100px;left:10px;font-size:14px;"><input type="radio" name="daoc" checked="checked"/><?=_("导出为")?>foxmail</label>
                <label class="radio" style="width:105px; position:absolute; top:130px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>outlook</label>
                <!--
                <label class="radio" style="width:100px; position:absolute; top:160px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>csv</label>
                <label class="radio" style="width:100px; position:absolute; top:190px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>vcard</label>
                -->
            </div>
            <div id="right_fz" style="height: 380px;">
                <div id="rightfz" style="height: 380px;">
                    <div id="l" style="height: 380px;">
                        <dl style="height: 380px;">
                            <dt class="left1"><span><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/group.png" /></span><?=_("我的分组")?></dt>
                            <dd>
                                <ul class="leftul">
                                    <li class="all"><a href="#" style="color:#000;"><label class="checkbox" for="all_my_group" style="height:44px; position:relative;padding-top:10px;"><span style="position:absolute;left:20px;top:10px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/all.png" /></span><?=_("全部分组")?><input type="checkbox" name="all_my_group" id="all_my_group" style=" position:absolute;right:0px;top:10px;"/></label></a></li>
                                    <?=$s_group_str?>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <div id="r" style="height: 380px;">
                        <dl style="height: 380px;">
                            <dt class="left1"><span><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/share.png" /></span><?=_("共享组")?></dt>
                            <dd style="margin:0px;">
                                <ul class="leftul">
                                    <li class="all"><a href="#" style="color:#000;"><label class="checkbox" for="all_share_group" style="height:44px; position:relative;padding-top:10px;"><span style="position:absolute;left:20px;top:10px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/all.png" /></span><?=_("全部分组")?><input type="checkbox" name="all_share_group" id="all_share_group" type="checkbox" style=" position:absolute;right:0px;top:10px;"/></label></a></li>
                                    <?=$s_share_group?>
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <input name="export_group" type="hidden">
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>
