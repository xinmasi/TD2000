<?php
//$HTML_PAGE_TITLE = _("项目进度");
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
//------初始化布局元素------
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
<!--侧边栏-->
<div class="proj_sidebar" style="overflow: visible; padding: 0px; width: 182px;">
        <?php 
    //引入左边栏
    include_once("public_left.php"); 
    ?>
</div>
<!--导航栏-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
             <strong>
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >> <?=_("项目进度")?>
        </strong>
        </p>
        <img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/info.png" width="20" height="20">
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <div style="overflow-y:auto;height:99%">
                    <table class="table table-bordered table-striped table_task" align="center">
                        <thead>
                        <tr class="time_table_top">
                            <td nowrap colspan='4'><h3><strong>
                            <?=_("任务一览")?>
                            <button type="button" class="btn btn-info pull-right" onclick="javascript:history.back();"><?=_("返回")?></button>
                            </strong></h3></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td nowrap width="20%"><strong>
                            <?=_("任务序号")?>
                            </strong></td>
                            <td><?php echo $i_task_no;?></td>
                            <td nowrap width="20%"><strong>
                            <?=_("任务名称")?>
                            </strong></td>
                            <td><?php echo $s_task_name;?></td>
                        </tr>
                        <tr>
                            <td nowrap width="20%"><strong>
                            <?=_("任务执行人")?>
                            </strong></td>
                            <td><?php echo $s_task_user;?></td>
                            <td><strong>
                            <?=_("任务级别")?>
                            </strong></td>
                            <td><?php echo $s_task_level;?></td>
                        </tr>                
                        <tr>
                            <td><strong>
                            <?=_("任务开始时间")?>
                            </strong></td>
                            <td><?php echo $s_task_start_time;?></td>
                            <td><strong>
                            <?=_("任务结束时间")?>
                            </strong></td>
                            <td><?php echo $s_task_end_time;?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("任务状态")?>
                            </strong></td>
                            <td><?php echo $s_status_str;?></td>
                            <td nowrap width="20%"><strong>
                            <?=_("任务进度")?>
                            </strong></td>
                            <td> <?=_("任务已完成")?><?php echo $i_task_percent_complete;?> %</td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("实际结束时间")?>
                            </strong></td>
                            <td><?php echo $s_task_act_end_time;?></td>
                            <td><strong>
                            <?=_("任务工期")?>
                            </strong></td>
                            <td><?php echo $i_task_time." 天";?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("前置任务")?>
                            </strong></td>
                            <td><?php echo $s_task_pre_task;?></td>
                            <td><strong>
                            <?=_("上级任务")?>
                            </strong></td>
                            <td><?php echo $s_task_parent_task;?></td>
                        </tr>
                        <tr>
                            <td><strong>
                            <?=_("任务描述")?>
                            </strong></td>
                            <td colspan="3">
                                <pre><?php echo $s_task_description;?></pre></td>
                        </tr>
                        <!--
                        <tr>
                            <td><strong>
                            <?=_("任务备注")?>
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
<!-- 右侧主体内容区域 END -->


</div>

</body>
</html>