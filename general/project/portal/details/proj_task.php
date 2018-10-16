<?php
//$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("details.inc.php");

include_once("general/workflow/prcs_role.php");
include_once("task.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/jquery.stickytableheaders.js"></script>
<script>
$(function()
{
//------��ʼ������Ԫ��------
	function init(){
        var height = $(window).height(); 
        var width = $(window).width()-182;
        $(".proj_sidebar, .content").height(height); 
        $(".content").height(height-$(".navbar").height());
        $(".proj_content").height(height-80);
        $('.proj_container').width(width);
        $('.proj_navbar').width(width-20);
        $('.proj_content').width(width);
        $('.mancontent_d').height(height-102);
        $('.mancontent').height(height-210);
    }
    $(window).resize(function(){init();});
    $(document).ready(function(){
        init();
    });
});
</script>
<body style=" overflow:hidden;">
<!--�����-->
<div class="proj_sidebar" style="overflow: visible; padding: 0px; width: 182px;">
        <?php 
    //���������
    include_once("public_left.php"); 
    ?>
</div>
<!--������-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
             <strong>
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("��Ŀ����")?>
        </strong>
        </p>
        <img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/info.png" width="20" height="20">
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <div style="overflow-y:auto;height:99%">
                    <table class="table table-bordered table-striped table_task" align="center">
                        <thead>
                        <tr class="time_table_top">
                            <td nowrap colspan='4'><h3><strong>
                            <?=_("����һ��")?>
                            <button type="button" class="btn btn-info pull-right" onclick="javascript:history.back();"><?=_("����")?></button>
                            </strong></h3></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td nowrap width="20%"><strong>
                            <?=_("�������")?>
                            </strong></td>
                            <td><?php echo $i_task_no;?></td>
                            <td nowrap width="20%"><strong>
                            <?=_("��������")?>
                            </strong></td>
                            <td><?php echo $s_task_name;?></td>
                        </tr>
                        <tr>
                            <td nowrap width="20%"><strong>
                            <?=_("����ִ����")?>
                            </strong></td>
                            <td><?php echo $s_task_user;?></td>
                            <td><strong>
                            <?=_("���񼶱�")?>
                            </strong></td>
                            <td><?php echo $s_task_level;?></td>
                        </tr>                
                        <tr>
                            <td><strong>
                            <?=_("����ʼʱ��")?>
                            </strong></td>
                            <td><?php echo $s_task_start_time;?></td>
                            <td><strong>
                            <?=_("�������ʱ��")?>
                            </strong></td>
                            <td><?php echo $s_task_end_time;?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("����״̬")?>
                            </strong></td>
                            <td><?php echo $s_status_str;?></td>
                            <td nowrap width="20%"><strong>
                            <?=_("�������")?>
                            </strong></td>
                            <td> <?=_("���������")?><?php echo $i_task_percent_complete;?> %</td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("ʵ�ʽ���ʱ��")?>
                            </strong></td>
                            <td><?php echo $s_task_act_end_time;?></td>
                            <td><strong>
                            <?=_("������")?>
                            </strong></td>
                            <td><?php echo $i_task_time." ��";?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("ǰ������")?>
                            </strong></td>
                            <td><?php echo $s_task_pre_task;?></td>
                            <td><strong>
                            <?=_("�ϼ�����")?>
                            </strong></td>
                            <td><?php echo $s_task_parent_task;?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("��������")?>
                            </strong></td>
                            <td colspan="3">
                                <pre><?php echo $s_task_description;?></pre></td>
                        </tr>
                        <!--
                        <tr>
                            <td><strong>
                            <?=_("����ע")?>
                            </strong></td>
                            <td colspan="3"><?php echo $s_task_remark;?></td>
                        </tr>
                        -->


                        <!--zfcflow-->                        
                        <?php
                        
                            require_once("proj_task/proj_task_new.php");
                            
                        ?>
                        
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rightbar_bottom">
                <?php include_once("column.php"); ?>
            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->


</div>

</body>
</html>