<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("编辑公共分组");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function func_color(select_obj)
{
    font_color="black";
    option_text="";
    for (j=0; j<select_obj.options.length; j++)
    {
        str=select_obj.options[j].text;
        if(str.indexOf(option_text)<0)
        {
            if(font_color=="red")
            {
                font_color="blue";
            }
            else
            {
                font_color="red";
            }
        }
        select_obj.options[j].style.color=font_color;
        
        pos=str.indexOf("] ")+1;
        option_text=str.substr(0,pos);
    }//for
    
    return j;
}

function func_insert()
{
    var oSelect1 = document.form1.select1;
    var oSelect2 = document.form1.select2;
    for (i=oSelect2.options.length-1; i>=0; i--)
    {
        if(oSelect2.options[i].selected)
        {
            option_text=oSelect2.options[i].text;
            option_value=oSelect2.options[i].value;
            option_style_color=oSelect2.options[i].style.color;
            
            var my_option = document.createElement("OPTION");
            my_option.text=option_text;
            my_option.value=option_value;
            my_option.style.color=option_style_color;
            
            oSelect1.options.add(my_option);
            oSelect2.remove(i);
        }
    }//for
    
    func_init();
}

function func_delete()
{
    var oSelect1 = document.form1.select1;
    var oSelect2 = document.form1.select2;
    for (i=oSelect1.options.length-1; i>=0; i--)
    {
        if(oSelect1.options[i].selected)
        {
            option_text=oSelect1.options[i].text;
            option_value=oSelect1.options[i].value;
            
            var my_option = document.createElement("OPTION");
            my_option.text=option_text;
            my_option.value=option_value;
            
            oSelect2.options.add(my_option);
            oSelect1.remove(i);
        }
    }//for
    
    func_init();
}

function func_select_all1()
{
    var oSelect1 = document.form1.select1;
    for (i=oSelect1.options.length-1; i>=0; i--)
    {
        oSelect1.options[i].selected=true;
    }
}

function func_select_all2()
{
    var oSelect2 = document.form1.select2;
    for (i=oSelect2.options.length-1; i>=0; i--)
    {
        oSelect2.options[i].selected=true;
    }
}

function func_init()
{
    func_color(document.form1.select2);
    func_color(document.form1.select1);
}
function hide_dialog()
{
    parent.parent.document.getElementById('hide_group').click();
}
function CheckForm()
{
    var oSelect1 = document.form1.select1;
    var fld_str="";
    
    for (i=0; i< oSelect1.options.length; i++)
    {
        options_value=oSelect1.options[i].value;
        fld_str+=options_value+",";
    }
    document.form1.FLD_STR.value=fld_str;
    
	document.form1.submit();
}
</script>
<style>
form {
  margin: 0;
}
</style>
<body>

<?
$s_share_user_name = "";
if($GROUP_ID)
{
    $query="select * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $group_name     = $ROW['GROUP_NAME'];
        $order_no       = $ROW['ORDER_NO'];
    }
}
else
{
    $group_name = _("默认");
    $GROUP_ID = '0';
}

//获取有权限的公共分组ID(先获取当前用户部门ID)
$s_group_id_str = '';
$query = "SELECT DEPT_ID FROM user where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
if($row = mysql_fetch_array($cursor))
{
    $dept_id = $row[0];
}

$query = "SELECT GROUP_ID FROM address_group where USER_ID='' and (find_in_set('$dept_id',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
$cursor=exequery(TD::conn(),$query);
while($row = mysql_fetch_array($cursor))
{
    $s_group_id_str .= $row[0].",";
}

$a_name_all = array();
$a_name_group = array();

if($s_group_id_str != '')
{
    $query="SELECT * FROM address WHERE USER_ID='' and (find_in_set(GROUP_ID,'$s_group_id_str') or GROUP_ID='0') order by GROUP_ID";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $group_id2  = $ROW["GROUP_ID"];
        $psn_name   = $ROW["PSN_NAME"];
        $add_id     = $ROW["ADD_ID"];
        
        if($group_id2 == $GROUP_ID)
        {
            $a_name_group[] = array("PSN_NAME" => $psn_name,"ADD_ID" => $add_id);
        }
        else
        {
            $a_name_all[] = array("PSN_NAME" => $psn_name,"ADD_ID" => $add_id,"GROUP_ID" => $group_id2);
        }
    }
}

$s_url_pic = MYOA_JS_SERVER."/static/modules/address/images/man_s.png";
?>

<div class="group" style="position:relative;width:787px;height:400px;overflow-x: hidden;">
    <div class="right" id="show_right" style="width:787px;height:400px;overflow-y:auto;overflow-x: hidden;">
        <form action="group_public_submit.php" method="post" name="form1" class="form-horizontal" style="height:400px;">
        
        <div class="gname" style="width: 100%;height: 69px;">
            <div class="control-group">
                <label class="control-label" for="zm" id="bj"><?=_("编辑组名：")?></label>
                <div class="controls">
                    <input type="text" id="zm" name="group_name" value="<?=$group_name?>" readonly>
                    <span id="pp">(<?=_("公共")?>)</span>
                </div>
            </div>
        </div>

        <div style="width:100%;margin-top: 10px;">
            <div style="width:543px;margin-left: 90px;">
                <div style="width:200px; float:left">
                    <div style="width:200px; text-align:center;"><?=_("已选成员")?></div>
                    <div style="width:200px; background-color:#f8f8f8;/* border:1px #CCCCCC solid;*/">
                        <select name="select1" ondblclick="func_delete();" style="width:200px;height:200px;padding-left: 30px;" multiple="multiple">
                    <?
                    $ARRAY_COUNT=sizeof($a_name_group);
                    if($a_name_group[$ARRAY_COUNT-1]=="")
                    {
                        $ARRAY_COUNT--;
                    }
                    
                    for($I=0;$I< $ARRAY_COUNT;$I++)
                    {
                        $item_id    = $a_name_group[$I]['ADD_ID'];
                        $item_name  = $a_name_group[$I]['PSN_NAME'];
                    
                    ?>
                            <option value="<?=$item_id?>"><?=$item_name?></option>
                    <?
                    }
                    ?>
                        </select>
                    </div>
                    <div style="width:200px; text-align:center; margin-top: 5px;"><button type="button" class="btn" onClick="func_select_all1();"><?=_("全选")?></button></div>
                </div>
                
                <div style="width:143px;height:250px; float:left;line-height:250px;">
                    <div style="width: 70px; height: 32px; position:absolute; left: 42%; top:22%; margin:0px; padding:0px;">
                        <button type="button" class="btn btn-success" onClick="func_insert();"><i class="icon-arrow-left icon-white"></i><?=_("添加")?></button>
                    </div>
                    <div style="width: 70px; height: 32px; position:absolute; left: 42%; top:32%; margin:0px; padding:0px;">
                        <button type="button" class="btn btn-danger" onClick="func_delete();"><i class="icon-arrow-right icon-white"></i><?=_("移除")?></button>
                    </div>
                </div>
                
                <div style="width:200px; float:left">
                    <div style="width:200px; text-align:center;"><?=_("备选成员")?></div>
                    <div style="width:200px;background-color:#f8f8f8;/* border:1px #CCCCCC solid;*/">
                        <select name="select2" ondblclick="func_insert();" style="width:200px;height:200px;padding-left: 30px;" multiple="multiple">
                    <?
                    $ARRAY_COUNT=sizeof($a_name_all);
                    if($a_name_all[$ARRAY_COUNT-1]=="")
                    {
                        $ARRAY_COUNT--;
                    }
                    
                    for($I=0;$I < $ARRAY_COUNT;$I++)
                    {
                        $item_id    = $a_name_all[$I]['ADD_ID'];
                        $item_name  = $a_name_all[$I]['PSN_NAME'];
                    ?>
                            <option value="<?=$item_id?>"><?=$item_name?></option>
                    <?
                    }
                    ?>
                        </select>
                    </div>
                    <div style="width:200px; text-align:center;margin-top: 10px;"><button type="button" class="btn" onClick="func_select_all2();"><?=_("备选成员")?></button></div>
                </div>
                <div style="clear:both; width:480px; text-align:center; padding-top:10px;"><p><span style="color:#F00;"><?=_("注：")?></span>①<?=_("点击条目时，可以组合CTRL或SHIFT键进行多选！")?><br>②<?=_("去掉当前分组中联系人将被调到默认组中，所有人均可维护，请尽快处理！")?></p></div>
            </div>
        </div>
        
        <input type="hidden" name="add_id_str" id="add_id_str" value="">
        <input type="hidden" name="FLD_STR" value="<?=$FLD_STR?>">
        <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
    </form>
    </div>
</div>
</body>