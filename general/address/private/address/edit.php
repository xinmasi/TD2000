<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("编辑联系人");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<script Language="JavaScript">
function previewImg(path)
{
    var pathLength=path.length;
    // substring 从 path 长度-3 的开始截取, 截止位置是 path 的长度，结果就是 path 的最后三位: 
    var additionName=path.substring(pathLength-3,pathLength);
    // 判断是否为 .jpg .png .gif格式图片，是，则显示，否，则提示错误
    if(additionName=="jpg" || additionName=="png" || additionName=="gif")
    {
        document.getElementById('img').innerHTML = '<img  src= "' + path + '"  style="width:170px;height:175px"/>';
    }
    else
    {
        document.getElementById('img').innerHTML="<font color=red>请选择格式为jpg,png或gif的图片!否则无法预览及上传</font>";
    }
}
function clear_photo()
{
    document.getElementById("photo").value="";
    document.getElementById('img').innerHTML = '<img  src= "<?=MYOA_JS_SERVER?>/static/modules/address/images/man_big.png"  style="width:170px;height:175px"/>';
}

$(document).ready(function(){
    if(typeof FileReader =='undefined')
    {
        $("#photo").change(function(event){
            var src = event.target.value;
            
            var pathLength=src.length;
            var additionName=src.substring(pathLength-3,pathLength);
            if(additionName=="jpg" || additionName=="png" || additionName=="gif")
            {
                var img = '<img src="'+src+'" style="width:170px;height:175px;" />';
                $("#img").empty().append(img);
            }
            else
            {
                var img = "<font color=red>请选择格式为jpg,png或gif的图片!否则无法预览及上传</font>";
                $("#img").empty().append(img);
            }
        });
    }
    else
    {
        $("#photo").change(function(e){
            for(var i=0;i<e.target.files.length;i++)
            {
                var file = e.target.files.item(i);
                if(!(/^image\/.*$/i.test(file.type)))
                {
                    var img = "<font color=red>请选择格式为jpg,png或gif的图片!否则无法预览及上传</font>";
                    $("#img").empty().append(img);
                    continue;
                }
                
                //实例化FileReader API
                var freader = new FileReader();
                freader.readAsDataURL(file);
                freader.onload=function(e)
                {
                    var img = '<img src="'+e.target.result+'" style="width:170px;height:175px;"/>';
                    $("#img").empty().append(img);
                }
            }
        });
    }
    $('#notes').focus(function(){
        var notes = $("#notes").val();
        if(notes == '<?=_("备注：")?>')
        {
            $("#notes").val("");
        }
    })
    $("#notes").blur(function(){
        var notes = $("#notes").val();
        if(notes == "")
        {
            $("#notes").val("<?=_("备注：")?>");
        }
    })
    bindListener();
});
function bindListener(){
    $("a[name=rmlink]").unbind().click(function(){
        var show_count = parseInt($('*[id=add_button]').size()) - 2;
        var del_count = parseInt($('*[name=rmlink]').size()) - 2;
        $('*[id=add_button]').eq(show_count).show();
        $('*[name=rmlink]').eq(del_count).show();
        $(this).parent().remove();
    })
}
function addimg(){
    var all_count = parseInt($('#add_input_count').val()) + 1;/*新增input总个数 + 1*/
    $('#add_input_count').val(all_count);
    var add_select_name = "qt_name"+all_count;/*给新增select赋值name*/
    var add_input_name = "input_name"+all_count;/*给新增input赋值name*/
    var input_c = parseInt($("input").length) - 5;/*定位马上新增的input*/
    var select_c = parseInt($('select').size());/*定位马上新增的select*/
    
    $('[id=add_button]').hide();
    $('[name=rmlink]').hide();
    $("#qt").append('<div class="input-prepend" style="margin-top:20px;"><a href="#" name="rmlink" style="float:left;margin-left:-30px;"> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a><select class="input-small" name="qt_name" id="qt_name" style="width:81px;height:30px;padding-left: 0px;padding-right: 1px;font-weight: normal; float:left;"><option value="oicq_no">QQ</option><option value="icq_no">MSN</option><option value="tel_no_dept">工作电话</option><option value="tel_no_home">家庭电话</option><option value="bp_no">小灵通</option><option value="fax_no_dept">工作传真</option><option value="sex">性别</option><option value="birthday">生日</option><option value="nick_name">昵称</option><option value="add_dept">办公地址</option><option value="post_no_dept">邮政编码</option><option value="add_home">家庭住址</option><option value="per_page">个人主页</option></select><input class="span2" id="prependedDropdownButton" name="input_name" type="text" style="width: 180px;"/><button class="btn" id="add_button" style="width:40px;margin-left: 4px;" type="button" onClick="addimg()">+</button></div>');
    $("select:eq("+select_c+")").attr("name",add_select_name);
    $("input:eq("+input_c+")").attr("name",add_input_name);
    bindListener();
}
function add_photo(){
    $("#photo_no").append('<div class="control-group"><a href="#" name="rmlink"> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a><label class="control-label" for="dh">电话：</label><div class="controls"><input type="text" style="width: 200px;"><button class="btn btn-small" type="button" onclick="add_photo()">+</button></div></div>');
    bindListener();
}
function show_share()
{
/*    var all_public = $('#public_group_id_str').val();
    var all_public_arr = all_public.split(",");
    if($.inArray(group_id, all_public_arr) > -1)
    {
        alert("<?=_("公共分组中联系人不可共享！")?>");
        return;
    }*/
    
    var obj = document.getElementById("show_share");
	if(obj.style.display=='none')
	{
	    obj.style.display='';
	    $('#show_right').scrollTop($("#show_share").offset().top);
	}
	else
	{
	    obj.style.display='none';
	}
}
/*
function hide_dialog()
{
    parent.document.all('hide_edit').click();
}
function SelectAdd(add_id_str, add_name_str, group_id, FORM_NAME)
{
    URL="select_add/select_add.php?add_id_str="+add_id_str+"&group_id="+group_id+"&add_name_str="+add_name_str+"&FORM_NAME="+FORM_NAME;
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-200;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 250, 350);
}*/
function IsNumber(str)
{
    return str.match(/^[0-9]*$/)!=null;
}

function IsValidEmail(str)
{
    var re = /@/;
    return str.match(re)!=null;
}
function CheckForm()
{
    if(document.form1.psn_name.value=="")
    {
        alert("<?=_("姓名不能为空！")?>");
        return (false);
    }
    if(document.form1.email.value!="" && !IsValidEmail(document.form1.email.value))
    {
        alert("<?=_("请输入有效的电子信箱！")?>");
        return (false);
    }
    
    document.form1.submit();
}

function show_hidden(show_id)
{
    $('#'+show_id+'_div').show();
}

function hide_div(show_id)
{
    $('#'+show_id).val("");
    $('#'+show_id+'_div').hide();
}
function hide_show(group_id)
{
    var all_public = $('#public_group_id_str').val();
    var all_public_arr = all_public.split(",");
    if($.inArray(group_id, all_public_arr) > -1)
    {
        $('#share').hide();
        $('#show_share').hide();
    }
    else
    {
        $('#share').show();
    }
}
</script>
<style>
.form-horizontal .control-label {
    width: 110px;
}
.form-horizontal .controls {
    margin-left: 130px;
}
</style>
<body>
<?
//当前用户部门ID
$query = "SELECT DEPT_ID FROM user where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
if($row = mysql_fetch_array($cursor))
{
    $dept_id = $row[0];
}

$s_url_pic = '';
$s_share_name = '';
$query = "SELECT * FROM address WHERE ADD_ID='$add_id'";
$cursor = exequery(TD::conn(), $query);
if($row = mysql_fetch_array($cursor))
{
    while(list($key, $value) = each($row))
    {
        $$key = $value;
    }
    
    $notes = ($notes == "") ? _("备注：") : $notes;
    
    if($ATTACHMENT_NAME=="" && $SEX==0)
    {
        $s_url_pic = MYOA_STATIC_SERVER."/static/modules/address/images/man_big.png";
    }
    else if($ATTACHMENT_NAME=="" && $SEX==1)
    {
        $s_url_pic = MYOA_STATIC_SERVER."/static/modules/address/images/w_big.png";
    }
    else
    {
        $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
        $s_url_pic = $URL_ARRAY["view"];
    }
    
    if($SHARE_END == "0000-00-00 00:00:00")
    {
        $SHARE_END = '';
    }
    
    $s_share_name = GetUserNameById($SHARE_USER);
}
?>
    <div class="new_f">
        <form enctype="multipart/form-data" action="edit_update.php"  method="post" name="form1" id="form1" class="form-horizontal" onSubmit="return CheckForm();" style="margin: 0px;">
            <div class="left" style="width: 220px;height: 400px;">
                <div id="img">
                    <img src="<?=$s_url_pic?>" style="width:170px;height:175px"/>
                </div>
                <div id="left_bts">
                    <button type="button" class="btn btn-success" style="width:80px;margin-right: 3px;margin-bottom:5px;" id="upload" onClick="photo.click()" title="<?=_("请选择格式为jpg/png/gif格式的图片")?>"><i class="icon-arrow-up icon-white"></i><?=_("上传")?></button>
                    <button type="button" class="btn btn-danger" style="width:80px;margin-right: 0px;margin-bottom:5px;" id="clear" onClick="clear_photo()"><i class="icon-remove icon-white"></i><?=_("清除")?></button><br />
                    <?
                    if($USER_ID != "")
                    {
                    ?>
                        <button type="button" class="btn btn-info" style="width:170px;" id="share" onClick="show_share()"><?=_("共享")?></button>
                    <?
                    }
                    ?>
                    <input type='file' id="photo" name="ATTACHMENT" style="cursor:pointer; position:absolute; top:0; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:100px" size='1'  hideFocus='' title="<?=_("请选择格式为jpg/png/gif格式的图片")?>"/><!-- onChange="previewImg(this.value);"-->
                </div>
            </div>
            
            <div class="right" id="show_right" style="height:400px;width: 780px;">
                <div id="name">
                    <div class="control-group" style="margin-top:20px;">
                        <label class="control-label" for="psn_name"><?=_("姓名：")?></label>
                        <div class="controls" style="position:relative;">
                            <input type="text" id="psn_name" name="psn_name" style="width: 180px;" value="<?=$PSN_NAME?>"><span class="red">&nbsp;&nbsp;(<?=_("必填项")?>)</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mobil_no"><?=_("移动电话：")?></label>
                        <div class="controls">
                            <input type="text" id="mobil_no" name="mobil_no" value="<?=$MOBIL_NO?>" style="width: 180px;">
                        </div>
                    </div>
                    <div class="control-group" style="width:480px;">
                        <label class="control-label" for="email"><?=_("电子邮件：")?></label>
                        <div class="controls">
                            <input type="text" id="email" name="email" value="<?=$EMAIL?>" style="width: 180px;">
                        </div>
                    </div>
                    
                    <!--<div class="control-group">
                        <label class="control-label" for="nc">昵称：</label>
                        <div class="controls">
                            <input type="text" id="nc" style="width: 120px;">
                        </div>
                    </div>-->
                </div>
                <div id="add">
                    <div class="control-group">
                        <label class="control-label" for="group_id"><?=_("分组：")?></label>
                        <div class="controls">
                            <select name="group_id" id="group_id" class="input-large" style="width: 195px;" onChange="hide_show(this.value)">
                                <option value="0" <?if($GROUP_ID == 0) echo "selected";?>><?=_("默认")?></option>
                <?
                //if($USER_ID != "")
                //{
                    $query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by GROUP_ID asc";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                       $GROUP_ID1=$ROW["GROUP_ID"];
                       $GROUP_NAME=$ROW["GROUP_NAME"];
                ?>
                                    <option value="<?=$GROUP_ID1?>" <?if($GROUP_ID==$GROUP_ID1) echo "selected";?>><?=$GROUP_NAME?></option>
                <?
                    }
                //}
                //else
                //{
                    //有维护权限的公共分组
                    $s_public_group_id_str = '';
                    $query = "select * from ADDRESS_GROUP where USER_ID='' and (find_in_set('$dept_id',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER)) order by GROUP_ID asc";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                       $GROUP_ID1=$ROW["GROUP_ID"];
                       $GROUP_NAME=$ROW["GROUP_NAME"]._("(公共)");
                       
                       $s_public_group_id_str .= $GROUP_ID1.",";
                ?>
                                    <option value="<?=$GROUP_ID1?>" <?if($GROUP_ID==$GROUP_ID1) echo "selected";?>><?=$GROUP_NAME?></option>
                <?
                    }
                //}
                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="ministration"><?=_("职位：")?></label>
                        <div class="controls" style="position:relative;">
                            <input type="text" id="ministration" name="ministration" style="width: 180px;" value="<?=$MINISTRATION?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dept_name"><?=_("单位：")?></label>
                        <div class="controls">
                            <input type="text" id="dept_name" name="dept_name" value="<?=$DEPT_NAME?>" style="width: 180px;">
                        </div>
                    </div>
                    <div id="tel_no_dept_div" class="control-group" style="width:480px;display:<?=(($TEL_NO_DEPT != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="tel_no_dept"><?=_("办公电话：")?></label>
                        <div class="controls">
                            <input type="text" id="tel_no_dept" name="tel_no_dept" value="<?=$TEL_NO_DEPT?>" style="width: 180px;"><a href="#" onClick="hide_div('tel_no_dept')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="tel_no_home_div" class="control-group" style="width:480px;display:<?=(($TEL_NO_HOME != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="tel_no_home"><?=_("住宅电话：")?></label>
                        <div class="controls">
                            <input type="text" id="tel_no_home" name="tel_no_home" value="<?=$TEL_NO_HOME?>" style="width: 180px;"><a href="#" onClick="hide_div('tel_no_home')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="fax_no_dept_div" class="control-group" style="width:480px;display:<?=(($FAX_NO_DEPT != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="fax_no_dept"><?=_("工作传真：")?></label>
                        <div class="controls">
                            <input type="text" id="fax_no_dept" name="fax_no_dept" value="<?=$FAX_NO_DEPT?>" style="width: 180px;"><a href="#" onClick="hide_div('fax_no_dept')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="per_web_div" class="control-group" style="width:480px;display:<?=(($PER_WEB != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="per_web"><?=_("个人主页：")?></label>
                        <div class="controls">
                            <input type="text" id="per_web" name="per_web" value="<?=$PER_WEB?>" style="width: 180px;"><a href="#" onClick="hide_div('per_web')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="icq_no_div" class="control-group" style="width:480px;display:<?=(($ICQ_NO != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="icq_no">MSN：</label>
                        <div class="controls">
                            <input type="text" id="icq_no" name="icq_no" value="<?=$ICQ_NO?>" style="width: 180px;"><a href="#" onClick="hide_div('icq_no')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="oicq_no_div" class="control-group" style="width:480px;display:<?=(($OICQ_NO != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="oicq_no">QQ：</label>
                        <div class="controls">
                            <input type="text" id="oicq_no" name="oicq_no" value="<?=$OICQ_NO?>" style="width: 180px;"><a href="#" onClick="hide_div('oicq_no')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="sex_div" class="control-group" style="width:480px;display:<?=(($SEX != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="sex"><?=_("性别：")?></label>
                        <div class="controls">
                            <select name="sex" id="sex" class="input-large" style="width: 135px;">
                                <option value="" <?if($SEX=="") echo "selected";?>></option>
                                <option value="0" <?if($SEX=="0") echo "selected";?>><?=_("男")?></option>
                                <option value="1" <?if($SEX=="1") echo "selected";?>><?=_("女")?></option>
                            </select>
                            <a href="#" onClick="hide_div('sex')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="birthday_div" class="control-group" style="width:480px;display:<?=(($BIRTHDAY != '0000-00-00') ? 'block' : 'none')?>;">
                        <label class="control-label" for="birthday"><?=_("生日日期：")?></label>
                        <div class="controls">
                            <input type="text" id="birthday" name="birthday" value="<?=$BIRTHDAY?>" style="width: 180px;" onClick="WdatePicker()"><a href="#" onClick="hide_div('birthday')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="nick_name_div" class="control-group" style="width:480px;display:<?=(($NICK_NAME != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="nick_name"><?=_("昵称：")?></label>
                        <div class="controls">
                            <input type="text" id="nick_name" name="nick_name" value="<?=$NICK_NAME?>" style="width: 180px;"><a href="#" onClick="hide_div('nick_name')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="add_dept_div" class="control-group" style="width:480px;display:<?=(($ADD_DEPT != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="add_dept"><?=_("办公地址：")?></label>
                        <div class="controls">
                            <input type="text" id="add_dept" name="add_dept" value="<?=$ADD_DEPT?>" style="width: 180px;"><a href="#" onClick="hide_div('add_dept')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="add_home_div" class="control-group" style="width:480px;display:<?=(($ADD_HOME != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="add_home"><?=_("住宅地址：")?></label>
                        <div class="controls">
                            <input type="text" id="add_home" name="add_home" value="<?=$ADD_HOME?>" style="width: 180px;"><a href="#" onClick="hide_div('add_home')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    <div id="post_no_dept_div" class="control-group" style="width:480px;display:<?=(($POST_NO_DEPT != '') ? 'block' : 'none')?>;">
                        <label class="control-label" for="post_no_dept"><?=_("邮政编码：")?></label>
                        <div class="controls">
                            <input type="text" id="post_no_dept" name="post_no_dept" value="<?=$POST_NO_DEPT?>" style="width: 180px;"><a href="#" onClick="hide_div('post_no_dept')" style=" cursor: pointer; text-decoration: none; height: 20px; "> <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/delete.png" /> </a>
                        </div>
                    </div>
                    
                    <div class="control-group" style="width:480px;">
                        <div class="btn-group dropup" style="margin-left: 40px;">
                            <button data-toggle="dropdown" class="btn dropdown-toggle"><?=_("更多")?><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onClick="show_hidden('tel_no_dept')"><?=_("办公电话")?></a></li>
                                <li><a href="#" onClick="show_hidden('tel_no_home')"><?=_("住宅电话")?></a></li>
                                <li><a href="#" onClick="show_hidden('fax_no_dept')"><?=_("工作传真")?></a></li>
                                <li><a href="#" onClick="show_hidden('per_web')"><?=_("个人主页")?></a></li>
                                <li><a href="#" onClick="show_hidden('icq_no')">MSN</a></li>
                                <li><a href="#" onClick="show_hidden('oicq_no')">QQ</a></li>
                                <li><a href="#" onClick="show_hidden('sex')"><?=_("性别")?></a></li>
                                <li><a href="#" onClick="show_hidden('birthday')"><?=_("生日日期")?></a></li>
                                <li><a href="#" onClick="show_hidden('nick_name')"><?=_("昵称")?></a></li>
                                <li><a href="#" onClick="show_hidden('add_dept')"><?=_("办公地址")?></a></li>
                                <li><a href="#" onClick="show_hidden('add_home')"><?=_("住宅地址")?></a></li>
                                <li><a href="#" onClick="show_hidden('post_no_dept')"><?=_("邮政编码")?></a></li>
                            </ul>
                        </div>
                        <input class="span2" id="prependedDropdownButton" name="input_name0" type="text" style="width: 180px;margin-left: 25px;"/>
                    </div>
                </div>
                <div id="beizhu">
                    <textarea name="notes" id="notes" style="margin-top: 0px; margin-bottom: 0px; height: 170px;"><?=$NOTES!=""?$NOTES:_("备注：")?></textarea>
                </div>
                <div id="show_share" style="display:none;width:500px;clear:both; padding-top:10px; float:left;">
                    <div class="control-group" style="height:150px;">
                        <label class="control-label" for="yx"><?=_("共享时间：")?></label>
                        <div class="controls" style="margin-bottom:30px; width:500px; ">
                            <input type="text" id="share_start" name="share_start" style="width:180px;" size="20" maxlength="19" value="<?=$ADD_START?>" title="<?=_("开始时间")?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">&nbsp;<?=_("至")?>&nbsp;
                            <input type="text" id="share_end" name="share_end" style="width: 180px;" size="20" maxlength="19" value="<?=$ADD_END?>" title="<?=_("结束时间")?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"><br>
                            <font color=red><b><?=_("注：")?></b></font><?=_("如果开始时间为空，视为立即开始共享；如果结束时间为空，视为永久共享！")?>
                        </div>
                    
                        <label class="control-label" for="yx"><?=_("共享范围：")?></label>
                        <div class="controls" style="margin-bottom:30px;width: 500px;">
                            <input type="hidden" name="to_id" id="to_id" value="<?=$SHARE_USER?>">
                            <textarea rows="3" class="SmallStatic" name="to_name" id="to_name" style="width:270px;" readonly><?=$s_share_name?> </textarea>
                            <a href="#" class="orgAdd" onClick="SelectUser('10', '', 'to_id', 'to_name')"><?=_("添加")?></a>
                            <a href="#" class="orgClear" onClick="ClearUser('to_id', 'to_name')"><?=_("清空")?></a><br>
                            <input type='checkbox' style="width:20px; margin-bottom:6px;" NAME='sms'/><label style="display:inline;"><?=_("向共享人员发送事务提醒")?></label>
                        </div>
                        
                    <!--    <label class="control-label" for="yx">共享内容：</label>
                        <div class="controls">
                            <input type="hidden" name="add_id_str" id="add_id_str" value="">
                            <textarea name="add_name_str" id="add_name_str" rows="3" style="overflow-y:auto;width:270px;" class="SmallStatic" wrap="yes" readonly></textarea>
                            <a href="javascript:;" class="orgAdd" onClick="SelectAdd('add_id_str','add_name_str','')"><?=_("添加")?></a>
                            <a href="javascript:;" class="orgClear" onClick="ClearUser('add_id_str', 'add_name_str')"><?=_("清空")?></a>&nbsp;&nbsp;
                        </div>-->
                    </div>
                </div>
            
                    <input type="hidden" name="add_input_count" id="add_input_count" value="0">
                    <input type="hidden" name="add_id" id="add_id" value="<?=$add_id?>">
                    <input type="hidden" name="attachment_id_old" value="<?=$ATTACHMENT_ID?>">
                    <input type="hidden" name="attachment_name_old" value="<?=$ATTACHMENT_NAME?>">
                <!--<div id="bts">
                    <button type="submit" class="button button-primary button-rounded" id="bc" style="margin-right:20px;">保存</button>
                    <button type="button" class="btn button-rounded" id="fh" onClick="hide_dialog()">关闭</button> 
                </div>-->
            </div>
            <input type="hidden" name="public_group_id_str" id="public_group_id_str" value="<?=$s_public_group_id_str?>">
        </form>
    </div>
</body>
</html>