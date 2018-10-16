<?php
//$HTML_PAGE_TITLE = _("时间管理");
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

    
//------进度条插件------
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >> <?=_("时间管理")?>
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
                        <form action="edit_task.php" method="post">
                            <input type="hidden" name="proj_id" value="<?php echo $i_proj_id?>">
                            <input type="hidden" name="task_id" value="<?php echo $i_task_id?>">
                            <thead>
                            <tr class="info table_top">
                                <td nowrap colspan='4'>
                                    <h3><?=_("任务一览")?>
                                        <span class="pull-right">
                                        <input type="submit" name="sub" class="btn btn-success" value="<?=_("保存")?>"/>&nbsp;
                                            <button type="button" class="btn btn-info" onclick="location='proj_time.php?VALUE=3&PROJ_ID=<?php echo $i_proj_id;?>'"><?=_("返回")?></button>
                                        </span>
                                    </h3>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td nowrap width="20%"><?=_("任务序号")?></td>
                                <td><?php echo $i_task_no;?></td>
                                <td nowrap width="20%"><?=_("任务名称")?></td>
                                <td><?php echo $s_task_name;?></td>
                            </tr>
                            <tr>
                                <td nowrap width="20%"><?=_("任务执行人")?></td>
                                <td><?php echo $s_task_user;?></td>
                                <td><?=_("任务级别")?></td>
                                <td><?php echo $s_task_level;?></td>
                            </tr>
                            <tr>
                                <td><?=_("任务开始时间")?></td>
                                <td><?php echo $s_task_start_time;?></td>
                                <td><?=_("任务结束时间")?></td>
                                <td><?php echo $s_task_end_time;?></td>
                            </tr>
                            <tr>
                                <td><?=_("任务状态")?></td>
                                <td><?php echo $s_status_str;?></td>
                                <td nowrap width="20%"><?=_("任务进度")?></td>
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
                                    <?=_("任务已完成")?> <?php echo $i_task_percent_complete;?> %
                                    <?
                                    }
                                    ?>
                                </td> 
                            </tr>
                            <tr>
                                <td><?=_("实际结束时间")?></td>
                                <td><?php echo $s_task_act_end_time;?></td>
                                <td><?=_("任务工期")?></td>
                                <td><?php echo $i_task_time;?><?=_("天")?></td>
                            </tr>
                            <tr>
                                <td><?=_("前置任务")?></td>
                                <td><?php echo $s_task_pre_task;?></td>
                                <td><?=_("上级任务")?></td>
                                <td><?php echo $s_task_parent_task;?></td>
                            </tr>
                            <?
                             require_once("proj_task/proj_task_new.php");
                            ?>
                            <tr>
                                <td><?=_("任务描述")?></td>
                                <td colspan="3"><?php echo $s_task_description;?></td>
                            </tr>
                            <tr>
                                <td><?=_("任务备注")?></td>
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
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>