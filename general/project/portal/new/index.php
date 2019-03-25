<?php
include_once("inc/auth.inc.php");//验证和连接数据库的
include_once("inc/sys_code_field.php");
include_once("inc/sys_code_field_get.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");//文本编辑器
include_once("general/workflow/prcs_role.php");

include_once("inc/utility_org.php");

include_once("inc/utility_project.php");

$priv = check_project_priv();

$HTML_PAGE_TITLE = _("新建项目");
include_once("inc/header.inc.php");
/************向导首页*************/
$s_query = "select PRIV_USER FROM PROJ_PRIV WHERE PRIV_CODE='NEW'";
$a_cursor = exequery(TD::conn(), $s_query);

if($ROW = mysql_fetch_array($a_cursor))
{
   $NEW_PRIV = $ROW["PRIV_USER"];
}
$NEW_PRIV = explode("|",$NEW_PRIV);

if($NEW_PRIV[0]=="ALL_DEPT" 
        || find_id($NEW_PRIV[0], $_SESSION["LOGIN_DEPT_ID"]) 
        || find_id($NEW_PRIV[1], $_SESSION["LOGIN_USER_PRIV"]) 
        || find_id($NEW_PRIV[2], $_SESSION["LOGIN_USER_ID"]))
{
    $NewPriv = 1;
}
else
{
    $NewPriv = 0;
    Message("",_("您没有立项权限，如需项目立项权限请与管理员联系开通！<br> 您是系统管理员请去菜单->项目管理->基础数据设置->项目权限设置->新建权限去设置。"));
?>
    <center>
        <input type="button" class="BigButtonA" value="<?=_("关闭")?>" onClick="parent.hide_mask();">
    </center>
<?
    exit;
}
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css"<?=$GZIP_POSTFIX?> />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/jquery.confirm.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jscrollpane.css"/>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jquery.mousewheel.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jscrollpane/jquery.jscrollpane.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/jquery.confirm.js" ></script>
<script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>">/*选择部门、人员组件*/</script>
<script src="/inc/js_lang.php">/*中文*/</script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/choose_div.js">/*预算*/</script>
<script type="text/javascript" language="javascript">
$(function()
{
    function init()
    {
        var height = $(window).height();
        $('.new-container').height(height-64);
        $('.new_leftbar').height(height-64);
        $('#xmglbar').height(height-110);
        $('.jspContainer').height(height-110);
        $('.basic_info').height(height-112);
    }
        $(document).ready(function(){init();});
        $(window).resize(function(){init();})

    $('#quxiao').click(function(){
        var elem = $(this).closest('.inputnextandre');
        $.confirm({
            'title'     : '退出新建任务导航',
            'message'   : '您是否放弃新建项目',
            'buttons'   : {
                '放弃'    : {
                    'class' : 'blue',
                    'action': function()
                    {
//                         parent.hide_mask();
                        history.go(-1);
                    }
                },
                '继续填写'  : {
                    'class' : 'gray',
                    'action': function()
                    {
                        return false;
                    }
                }
            }
        });
    });
    
});

//审批人验证
function form_check(){
    if($("#PROJ_MANAGER").val() == "choose")
    {
        alert("审批人不能为空！");
        return (false);
    }
    else
    {
        return (true);
    }
}
//确认审批状态
function submit_proj(status){
    //保存预算信息
    var total = 0.00;
    var flag = true; 
    $(".budget-amount").each(function()
    {   
        if($.trim($(this).val())!= "")
        {   
            if(!isNaN($(this).val()))
            {   
                total += parseFloat($.trim($(this).val()));
                flag = true;
            }
            else
            {
                alert(td_lang.general.project.guide.number);//请输入有效数字
                flag = false;
                return false;
            }  
        }
    });
    if(flag)
    {
        $("#total").val(total);
    }
    
    //审批状态
    if(status == 0)
    {
        $("[name='PROJ_STATUS']").val(status);
        //console.log($("#form1"));return false;
        var form_data = $("#form1").serializeArray();
        form1.submit();
    }
    else if(status == 1 && form_check())
    { 
        $("[name='PROJ_STATUS']").val(status);
        var form_data = $("#form1").serializeArray();
        form1.submit();
    }   
}
        
function check_no()
{
    var PROJ_NUM = $("#PROJ_NUM").val();
    $.get('check_proj_no.php',{"PROJ_NUM":PROJ_NUM}, function(d){
            if(d!="OK")
            {
                alert(d);
                $("#PROJ_NUM").focus();
            }
        }
    );
}

//20161028 spz 
function get_define_type(CODE_ID)
{
    $.get('/inc/sys_code_field_get.php',{"CODE_ID":CODE_ID},function(data){
        $("#DEFINE_SYSCODE_CONTENT").empty(); 
        $("#DEFINE_SYSCODE_CONTENT").append(data);
    });
}


window.onload=myfun; 
function myfun(){
    var codeid= $("#USER_CUST_DEFINE option:selected").val();
   
    $.get('/inc/sys_code_field_get.php',{"CODE_ID":codeid},function(date){
        $("#DEFINE_SYSCODE_CONTENT").empty(); 
        $("#DEFINE_SYSCODE_CONTENT").append(date);
    });
}
</script>

<script>

</script>
<style>
/*上面显示*/
#nonediv2
{
overflow:hidden;
background:#fff;
z-index:50;
margin:0 auto;
}
legend
{
 font-size:18px;   
}
/*自定义select样式*/
select.BigSelect {
  height:30px;
  margin-bottom: 0px;
}
select.BigSelect:hover{
  height:30px;
  margin-bottom: 0px;
}
.jspTrack{background:#ccc;}
.jspDrag{background:#bbd;}
</style>
</head>
<body style="background:none;cursor:default;">
<div id="nonediv2" >
    <form name="form1" id="form1" action="project_add.php" method="post" class="form-horizontal">
        <div class="new-container" style="overflow-y:auto;overflow-x:hidden;" ><!--添加的滚动条-->
        <div class="container-info">
            <div class="welcome">
                <h3><?=_("欢迎来到新建项目向导")?></h3>
                <h5><?=_("这个向导将指引你完成新建项目的整个流程")?></h5>
            </div>
            <div id="proj_select"  >
            <div class="proj_select_left">
                <p><?=_("立项及预算、审批人是必选项")?><br><?=_("勾选你想要填写的信息")?><br><?=_("点击\"下一步\"")?></p>
            </div>
            <div class="proj_select_center">
                <table class="table-hover">
                    <tbody>
                    <tr>
                        <td>
                            <label class="checkbox" for="_new">
                            <b>1.</b>
                            <p>
                            <input type="checkbox" name="ck1" id="_new" checked="checked" disabled="disabled"><?=_("立项信息及预算")?>
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_user" >
                            <b>2.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_user" value="proj_user"><?=_("添加干系人")?>    
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_task">
                            <b>3.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_task" value="proj_task"><?=_("第一个任务")?>
                            </p>
                            </label>    
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_file_sort">
                            <b>4.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_file_sort" value="proj_file_sort"><?=_("第一个文档库")?>
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_diy">
                                <b>5.</b>
                                <p>
                                <input type="checkbox" name="ck" id="_diy" value="proj_diy"><?=_("自定义字段")?>
                                </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                            <label class="checkbox" for="_approve">
                                <b>6.</b>
                                <p>
                                <input type="checkbox" name="ck1" id="_approve" checked="checked" disabled="disabled"><?=_("立项与审批")?>
                                </p>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div align="center" style="position:fixed;padding-top: 0px">
                    <button class="btn btn-primary" type="button" name="button" id="check_all" ><?=_("全选")?></button>
                    <button class="btn btn-info" type="button" name="button" id="check_reset" ><?=_("取消")?></button>
                </div>
            </div>
                <div class="proj_select_right">
                    <h3><?=_("描述")?></h3>
                    <div class="well describe">
                        <div class="describe-one" style="">
                            <p><?=_("移动您的鼠标到相应的流程之上，便可看到它的描述信息。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您可以填写该项目的基本信息以及与该项目相关的预算信息。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您可以选择性地添加该项目的干系人，例如，项目负责人、开发工程师、项目查看者等等。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您将为该项目添加首个任务，并为其填写相关的任务信息。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您将为该项目设立首个项目文档目录。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您可以填写与该项目相关的自定义字段信息。")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("在该步骤中您可以选择该项目的审批人并提交审批，或是保存已经填写的项目信息。")?></p>  
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>    
        </div>
        <div class="new_leftbar" style="display:none" id="proj_left"><!--修改右面的宽度-->
            <div class="new_step">
                <h4><?=_("立项步骤");?></h4>
            </div>
            <ul id="xmglbar">
                <!--choose_div.js-->
            </ul>
        </div>
        <div class="new_content">
            <div class="new_content-item" id="proj_attribute" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="new_proj_span"></span><?=_("设置立项及预算")?></p>
                    <? help('004','skill/project');?>
                </div>
                <div class="basic_info"  >
                <?php 
                    //基本属性
                    include_once("proj_attribute.php");
                ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_user" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="write_proj_span"></span><?=_("设置干系人")?></p>
                     <? help('007','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //干系人
                        include_once("proj_user.php");
                    ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_task" style="display:none" >
                <input type="hidden" id="create_task" value="1">
                <div class="person_info">
                    <p class="person_info_p"><span class="first_proj_span"></span><?=_("设置任务(第一个任务)")?></p>
                     <? help('001','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //项目任务
                        include_once("proj_task.php");
                    ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_file_sort" style="display:none">
                <input type="hidden" id="create_file" value="1">
                <div class="person_info">
                    <p class="person_info_p"><span class="first_file_proj_span"></span><?=_("设置文档目录")?></p>
                     <? help('002','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //项目文档
                        include_once("proj_file_sort.php");
                    ?>
                </div>
            </div>  
            <div class="new_content-item" id="proj_diy" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="diy_proj_span"></span><?=_("设置自定义字段")?></p>
                     <? help('013','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //自定义
                        include_once("proj_diy.php");
                        
                    ?>
                    <!--自定义局部字段-->
                    <div id="DEFINE_SYSCODE_CONTENT" class="controls" style="margin:20px" ></div>
                </div>
            </div>
            <div class="new_content-item" id="proj_approve" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="approve_proj_span"></span><?=_("设置项目审批人")?></p>
                     <? help('005','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //审批人
                         include_once("proj_approve.php");
                    ?>
                </div>
            </div>
        </div>
        </div>
        <input type="hidden" name="PROJ_STATUS" value="0">
        <div class="new-bottom">
            <div class="pull-right ">
            <button class="btn btn-primary btn-large" type="button" id="select_next"><?=_("下一步")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="select_prev"><?=_("上一步")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="guide_prev"><?=_("上一步")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="guide_next"><?=_("下一步")?></button>
            <?php 
            //判断有没有审批人，没有不显示确认审批按钮
            if($priv != 1)//
            {
            ?>
            <!-- 
            <button class="btn btn-large btn-primary hide" type="button" onClick="submit_proj(1)" id="sure_approve"><?=_("确认审批")?></button>
             -->
            <?php
            }
            ?>
            <button class="btn btn-large btn-success hide" type="button" onClick="submit_proj(0)" id="sure_proj" ><?=_("保存")?></button>
            <button class="btn btn-info btn-large" type="button" id="quxiao"><?=_("取消")?></button>
            </div>
        </div>
    </form>
</div>
</body>
</html>