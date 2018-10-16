<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建分组");
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

function SelectAdd(add_id_str, add_name_str, FORM_NAME)
{
    var URL="select_add/select_add.php?add_id_str="+add_id_str+"&group_id=<?=$GROUP_ID?>"+"&add_name_str="+add_name_str+"&FORM_NAME="+FORM_NAME;
    var loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-200;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    alert(URL);
    LoadDialogWindow(URL,self,loc_x, loc_y, 250, 350);
}
function show_share()
{
    var obj = document.getElementById("show_share");
	if(obj.style.display=='none')
	{
	    obj.style.display='';
	}
	else
	{
	    obj.style.display='none';
	}
}
function hide_dialog()
{
    parent.parent.document.getElementById('hide_group').click();
}

function CheckForm()
{
    if(document.form1.group_name.value=="")
    {
        alert("<?=_("组名不能为空！")?>");
        return false;
    }
    
    var oSelect1 = document.form1.select1;
    fld_str="";
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
<body style="overflow-x:hidden">
<?
$a_name_all = array();
$query="SELECT * FROM address WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by GROUP_ID";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $group_id2  = $ROW["GROUP_ID"];
    $psn_name   = $ROW["PSN_NAME"];
    $add_id     = $ROW["ADD_ID"];
    
    $a_name_all[] = array("PSN_NAME" => $psn_name,"ADD_ID" => $add_id);
}
?>

<form action="group_new_submit.php" method="post" name="form1" class="form-horizontal" style="width: 787px;height: 400px">
<div class="group" style="width: 100%;height: 400px;">
    <div class="right" style="width: 100%;height: 400px;">
        <div class="gname" style="width: 100%">
            <div class="control-group">
                <div class="controls" style="margin-left:0px; margin-top:30px; padding-left:0px;">
                    <label class="control-label" for="zm" id="bj"><?=_("新增组名：")?></label>
                    <input type="text" id="zm" name="group_name" value="">
                    <span class="red" id="pp">(<?=_("必填项")?>)</span>
                </div>
            </div>
        </div>
        
        <div style="width:100%;">
            <div style="width:543px;margin-left: 90px;">
                <div style="width:200px; float:left">
                    <div style="width:200px; text-align:center;"><?=_("已选成员")?></div>
                    <div style="width:200px; background-color:#f8f8f8;">
                        <select name="select1" ondblclick="func_delete();" style="width:200px;height:200px;padding-left: 30px;" multiple="multiple">
                        </select>
                    </div>
                    <div style="width:200px; text-align:center; margin-top: 5px;"><button type="button" class="btn" onClick="func_select_all1();"><?=_("全选")?></button></div>
                </div>
                
                <div style="width:143px;height:250px; float:left; position:relative;">
                   <button type="button" style="position:absolute;top:30%;left:25%;" class="btn btn-success" onClick="func_insert();"><i class="icon-arrow-left icon-white"></i><?=_("添加")?></button>
                   <button type="button" style="position:absolute;top:50%;left:25%;" class="btn btn-danger" onClick="func_delete();"><i class="icon-arrow-right icon-white"></i><?=_("移除")?></button>
                </div>
                
                <div style="width:200px; float:left">
                    <div style="width:200px; text-align:center;"><?=_("备选成员")?></div>
                    <div style="width:200px;background-color:#f8f8f8;">
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
                    <div style="width:200px; text-align:center;margin-top: 10px;"><button type="button" class="btn" onClick="func_select_all2();"><?=_("全选")?></button></div>
                </div>
                <div style="clear:both; width:480px; text-align:center; padding-top:10px;"><p><span style="color:#F00;"><?=_("注：")?></span><?=_("点击条目时，可以组合CTRL或SHIFT键进行多选！")?></p></div>
            </div>
        </div>
        
        <div id="show_share" style="width:490px;height:150px;clear:both;padding-top:20px; display:none;">
            <div class="control-group" style="height:150px;">
                <label class="control-label" for="yx"><?=_("共享时间：")?></label>
                <div class="controls" style="margin-bottom:30px; width:480px; ">
                    <input type="text" id="share_start" name="share_start" style="width:180px;" size="20" maxlength="19" value="" title="<?=_("开始时间")?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">&nbsp;<?=_("至")?>&nbsp;
                    <input type="text" id="share_end" name="share_end" style="width: 180px;" size="20" maxlength="19" value="" title="<?=_("结束时间")?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"><br>
                    <font color=red><b><?=_("注：")?></b></font><?=_("如果开始时间为空，视为立即开始共享；如果结束时间为空，视为永久共享！")?>
                </div>
                <label class="control-label" for="yx"><?=_("共享范围：")?></label>
                <div class="controls" style="margin-bottom:30px; width:480px;">
                    <input type="hidden" name="to_id" id="to_id" value="">
                    <textarea rows="3" class="SmallStatic" name="to_name" id="to_name" style="width:270px;" readonly> </textarea>
                    <a href="#" class="orgAdd" onClick="SelectUser('10', '', 'to_id', 'to_name')"><?=_("添加")?></a>
                    <a href="#" class="orgClear" onClick="ClearUser('to_id', 'to_name')"><?=_("清空")?></a><br>
                    <input type='checkbox' style="width:20px;" NAME='sms'/><?=_("向共享人员发送事务提醒")?>
                </div>
                
                <label class="control-label" for="yx"><?=_("共享内容：")?></label>
                <div class="controls" style="margin-bottom:30px; width:480px;">
                    <input type="hidden" name="add_id_str" id="add_id_str" value="">
                    <textarea name="add_name_str" id="add_name_str" rows="3" style="overflow-y:auto;width:270px;" class="SmallStatic" wrap="yes" readonly></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectAdd('add_id_str','add_name_str','')"><?=_("添加")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('add_id_str', 'add_name_str')"><?=_("清空")?></a>&nbsp;&nbsp;
                </div>
            </div>
        </div><!--共享隐藏区域-->
        <input type="hidden" name="FLD_STR" value="<?=$FLD_STR?>">
        <!--<div class="bts" style="margin-bottom: 50px;">
            <button type="button" class="button button-primary button-rounded" id="bc" onClick="mysubmit()">保存</button>
            <button type="button" class="btn button-rounded" id="fh" onClick="hide_dialog()">关闭</button>
        </div>-->
    </div>
</div>
</form>
</body>