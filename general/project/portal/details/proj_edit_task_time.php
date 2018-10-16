<?php
//$HTML_PAGE_TITLE = _("ʱ�����");
include_once("details.inc.php");

include_once("task.inc.php");

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?> "/>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/jquery.stickytableheaders.js"></script>
<script>
$(function(){

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

    
//------���������------
    $( "#slider-range-max" ).slider({
        range: "max",
        min: 0,
        max: 100,
        value: <?php echo $i_task_percent_complete?>,
        slide: function( event, ui ) {
            $( "#amount" ).val( ui.value+"%" );
        }
    });
    $( "#amount" ).val( $( "#slider-range-max" ).slider( "value" )+"%" );
});
</script>
<style>
input[type="text"] {
  height:20px;
  margin-bottom: 0px;
  background-color:#fff;
  border:0;
  box-shadow:none;
}
</style>

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
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("ʱ�����")?>
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
                        <form action="edit_task.php" method="post">
                            <input type="hidden" name="proj_id" value="<?php echo $i_proj_id?>">
                            <input type="hidden" name="task_id" value="<?php echo $i_task_id?>">
                            <thead>
                            <tr class="info table_top">
                                <td nowrap colspan='4'>
                                    <h3><?=_("����һ��")?>
                                        <span class="pull-right">
                                        <input type="submit" name="sub" class="btn btn-success" value="<?=_("����")?>"/>&nbsp;
                                            <button type="button" class="btn btn-info" onclick="location='proj_time.php?VALUE=3&PROJ_ID=<?php echo $i_proj_id;?>'"><?=_("����")?></button>
                                        </span>
                                    </h3>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td nowrap width="20%"><?=_("�������")?></td>
                                <td><?php echo $i_task_no;?></td>
                                <td nowrap width="20%"><?=_("��������")?></td>
                                <td><?php echo $s_task_name;?></td>
                            </tr>
                            <tr>
                                <td nowrap width="20%"><?=_("����ִ����")?></td>
                                <td><?php echo $s_task_user;?></td>
                                <td><?=_("���񼶱�")?></td>
                                <td><?php echo $s_task_level;?></td>
                            </tr>
                            <tr>
                                <td><?=_("����ʼʱ��")?></td>
                                <td><?php echo $s_task_start_time;?></td>
                                <td><?=_("�������ʱ��")?></td>
                                <td><?php echo $s_task_end_time;?></td>
                            </tr>
                            <tr>
                                <td><?=_("����״̬")?></td>
                                <td><?php echo $s_status_str;?></td>
                                <td nowrap width="20%"><?=_("�������")?></td>
                                <td>
                                    <?
                                    if($i_status == 2)
                                    {
                                    ?>
                                    <div id="slider-range-max" style="float:left;display:block;width:65%;margin:5px"></div>
                                    <div style="float:right;display:block;width:30%">
                                        <input type="text" name="task_percent_complete" id="amount" readonly style="width:55%;border: 0; color: #f6931f; font-weight: bold;"/>
                                    </div>
                                    <?
                                    }
                                    else
                                    {
                                    ?>
                                    <?=_("���������")?> <?php echo $i_task_percent_complete;?> %
                                    <?
                                    }
                                    ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?=_("ʵ�ʽ���ʱ��")?></td>
                                <td><?php echo $s_task_act_end_time;?></td>
                                <td><?=_("������")?></td>
                                <td><?php echo $i_task_time;?><?=_("��")?></td>
                            </tr>
                            <tr>
                                <td><?=_("ǰ������")?></td>
                                <td><?php echo $s_task_pre_task;?></td>
                                <td><?=_("�ϼ�����")?></td>
                                <td><?php echo $s_task_parent_task;?></td>
                            </tr>
                            <?
                             require_once("proj_task/proj_task_new.php");
                            ?>
                            <tr>
                                <td><?=_("��������")?></td>
                                <td colspan="3"><?php echo $s_task_description;?></td>
                            </tr>
                            <tr>
                                <td><?=_("����ע")?></td>
                                <td colspan="3"><?php echo $s_task_remark;?></td>
                            </tr>
                            </tbody>
                        </form>
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