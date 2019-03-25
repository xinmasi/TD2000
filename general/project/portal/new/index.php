<?php
include_once("inc/auth.inc.php");//��֤���������ݿ��
include_once("inc/sys_code_field.php");
include_once("inc/sys_code_field_get.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");//�ı��༭��
include_once("general/workflow/prcs_role.php");

include_once("inc/utility_org.php");

include_once("inc/utility_project.php");

$priv = check_project_priv();

$HTML_PAGE_TITLE = _("�½���Ŀ");
include_once("inc/header.inc.php");
/************����ҳ*************/
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
    Message("",_("��û������Ȩ�ޣ�������Ŀ����Ȩ���������Ա��ϵ��ͨ��<br> ����ϵͳ����Ա��ȥ�˵�->��Ŀ����->������������->��ĿȨ������->�½�Ȩ��ȥ���á�"));
?>
    <center>
        <input type="button" class="BigButtonA" value="<?=_("�ر�")?>" onClick="parent.hide_mask();">
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
<script src="/module/DatePicker/WdatePicker.js">/*ʱ��ؼ�*/</script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>">/*ѡ���š���Ա���*/</script>
<script src="/inc/js_lang.php">/*����*/</script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/choose_div.js">/*Ԥ��*/</script>
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
            'title'     : '�˳��½����񵼺�',
            'message'   : '���Ƿ�����½���Ŀ',
            'buttons'   : {
                '����'    : {
                    'class' : 'blue',
                    'action': function()
                    {
//                         parent.hide_mask();
                        history.go(-1);
                    }
                },
                '������д'  : {
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

//��������֤
function form_check(){
    if($("#PROJ_MANAGER").val() == "choose")
    {
        alert("�����˲���Ϊ�գ�");
        return (false);
    }
    else
    {
        return (true);
    }
}
//ȷ������״̬
function submit_proj(status){
    //����Ԥ����Ϣ
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
                alert(td_lang.general.project.guide.number);//��������Ч����
                flag = false;
                return false;
            }  
        }
    });
    if(flag)
    {
        $("#total").val(total);
    }
    
    //����״̬
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
/*������ʾ*/
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
/*�Զ���select��ʽ*/
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
        <div class="new-container" style="overflow-y:auto;overflow-x:hidden;" ><!--��ӵĹ�����-->
        <div class="container-info">
            <div class="welcome">
                <h3><?=_("��ӭ�����½���Ŀ��")?></h3>
                <h5><?=_("����򵼽�ָ��������½���Ŀ����������")?></h5>
            </div>
            <div id="proj_select"  >
            <div class="proj_select_left">
                <p><?=_("���Ԥ�㡢�������Ǳ�ѡ��")?><br><?=_("��ѡ����Ҫ��д����Ϣ")?><br><?=_("���\"��һ��\"")?></p>
            </div>
            <div class="proj_select_center">
                <table class="table-hover">
                    <tbody>
                    <tr>
                        <td>
                            <label class="checkbox" for="_new">
                            <b>1.</b>
                            <p>
                            <input type="checkbox" name="ck1" id="_new" checked="checked" disabled="disabled"><?=_("������Ϣ��Ԥ��")?>
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_user" >
                            <b>2.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_user" value="proj_user"><?=_("��Ӹ�ϵ��")?>    
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_task">
                            <b>3.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_task" value="proj_task"><?=_("��һ������")?>
                            </p>
                            </label>    
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_file_sort">
                            <b>4.</b>
                            <p>
                            <input type="checkbox" name="ck" id="_file_sort" value="proj_file_sort"><?=_("��һ���ĵ���")?>
                            </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="checkbox" for="_diy">
                                <b>5.</b>
                                <p>
                                <input type="checkbox" name="ck" id="_diy" value="proj_diy"><?=_("�Զ����ֶ�")?>
                                </p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                            <label class="checkbox" for="_approve">
                                <b>6.</b>
                                <p>
                                <input type="checkbox" name="ck1" id="_approve" checked="checked" disabled="disabled"><?=_("����������")?>
                                </p>
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div align="center" style="position:fixed;padding-top: 0px">
                    <button class="btn btn-primary" type="button" name="button" id="check_all" ><?=_("ȫѡ")?></button>
                    <button class="btn btn-info" type="button" name="button" id="check_reset" ><?=_("ȡ��")?></button>
                </div>
            </div>
                <div class="proj_select_right">
                    <h3><?=_("����")?></h3>
                    <div class="well describe">
                        <div class="describe-one" style="">
                            <p><?=_("�ƶ�������굽��Ӧ������֮�ϣ���ɿ�������������Ϣ��")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò�������������д����Ŀ�Ļ�����Ϣ�Լ������Ŀ��ص�Ԥ����Ϣ��")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò�����������ѡ���Ե���Ӹ���Ŀ�ĸ�ϵ�ˣ����磬��Ŀ�����ˡ���������ʦ����Ŀ�鿴�ߵȵȡ�")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò���������Ϊ����Ŀ����׸����񣬲�Ϊ����д��ص�������Ϣ��")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò���������Ϊ����Ŀ�����׸���Ŀ�ĵ�Ŀ¼��")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò�������������д�����Ŀ��ص��Զ����ֶ���Ϣ��")?></p>  
                        </div>
                        <div class="describe-one" style="display:none;">
                            <p><?=_("�ڸò�����������ѡ�����Ŀ�������˲��ύ���������Ǳ����Ѿ���д����Ŀ��Ϣ��")?></p>  
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>    
        </div>
        <div class="new_leftbar" style="display:none" id="proj_left"><!--�޸�����Ŀ��-->
            <div class="new_step">
                <h4><?=_("�����");?></h4>
            </div>
            <ul id="xmglbar">
                <!--choose_div.js-->
            </ul>
        </div>
        <div class="new_content">
            <div class="new_content-item" id="proj_attribute" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="new_proj_span"></span><?=_("�������Ԥ��")?></p>
                    <? help('004','skill/project');?>
                </div>
                <div class="basic_info"  >
                <?php 
                    //��������
                    include_once("proj_attribute.php");
                ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_user" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="write_proj_span"></span><?=_("���ø�ϵ��")?></p>
                     <? help('007','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //��ϵ��
                        include_once("proj_user.php");
                    ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_task" style="display:none" >
                <input type="hidden" id="create_task" value="1">
                <div class="person_info">
                    <p class="person_info_p"><span class="first_proj_span"></span><?=_("��������(��һ������)")?></p>
                     <? help('001','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //��Ŀ����
                        include_once("proj_task.php");
                    ?>
                </div>
            </div>
            <div class="new_content-item" id="proj_file_sort" style="display:none">
                <input type="hidden" id="create_file" value="1">
                <div class="person_info">
                    <p class="person_info_p"><span class="first_file_proj_span"></span><?=_("�����ĵ�Ŀ¼")?></p>
                     <? help('002','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //��Ŀ�ĵ�
                        include_once("proj_file_sort.php");
                    ?>
                </div>
            </div>  
            <div class="new_content-item" id="proj_diy" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="diy_proj_span"></span><?=_("�����Զ����ֶ�")?></p>
                     <? help('013','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //�Զ���
                        include_once("proj_diy.php");
                        
                    ?>
                    <!--�Զ���ֲ��ֶ�-->
                    <div id="DEFINE_SYSCODE_CONTENT" class="controls" style="margin:20px" ></div>
                </div>
            </div>
            <div class="new_content-item" id="proj_approve" style="display:none">
                <div class="person_info">
                    <p class="person_info_p"><span class="approve_proj_span"></span><?=_("������Ŀ������")?></p>
                     <? help('005','skill/project');?>
                </div>
                <div class="basic_info">
                    <?php
                        //������
                         include_once("proj_approve.php");
                    ?>
                </div>
            </div>
        </div>
        </div>
        <input type="hidden" name="PROJ_STATUS" value="0">
        <div class="new-bottom">
            <div class="pull-right ">
            <button class="btn btn-primary btn-large" type="button" id="select_next"><?=_("��һ��")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="select_prev"><?=_("��һ��")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="guide_prev"><?=_("��һ��")?></button>
            <button class="btn btn-primary btn-large hide" type="button" id="guide_next"><?=_("��һ��")?></button>
            <?php 
            //�ж���û�������ˣ�û�в���ʾȷ��������ť
            if($priv != 1)//
            {
            ?>
            <!-- 
            <button class="btn btn-large btn-primary hide" type="button" onClick="submit_proj(1)" id="sure_approve"><?=_("ȷ������")?></button>
             -->
            <?php
            }
            ?>
            <button class="btn btn-large btn-success hide" type="button" onClick="submit_proj(0)" id="sure_proj" ><?=_("����")?></button>
            <button class="btn btn-info btn-large" type="button" id="quxiao"><?=_("ȡ��")?></button>
            </div>
        </div>
    </form>
</div>
</body>
</html>