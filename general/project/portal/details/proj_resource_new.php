<? 
include_once("details.inc.php");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>
<script src="/inc/js_lang.php"></script>
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
        $('.proj_content').width(width-20);
        $('.mancontent_d').height(height-102);
        $('.mancontent').height(height-204);
        $('.scrollzone').height(height-234);
    }
    
    $(window).resize(function(){init();});
    $(document).ready(function(){init();});
});
</script>


<body style=" overflow:hidden;">
<?
$query = "";
?>
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
            <?=_("项目管理")?> >> <?=_("资源管理")?> >> <?=$s_name?> >><?=_("申请资源")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <br><br>
                <div style="margin-left:200px">
                <form class="form-horizontal" action="resource_add.php?VALUE=5&PROJ_ID=<?=$i_proj_id?>" method="post">   
                    <input type="hidden" name="proj_id" value="<?=$i_proj_id?>">
                    <div class="control-group">
                        <label class="control-label" for="source_name"><?=_("资源名称")?></label>
                        <div class="controls">
                            <input class="input-large" type="text" placeholder="<?=_("请输入资源名称")?>" id="source_name" name="source_name" autofocus>
                        </div>
                    </div>   
                    <div class="control-group">
                        <label class="control-label" for="source_type"><?=_("资源类型")?></label>
                        <div class="controls">
                            <input class="input-large" type="text"  placeholder="<?=_("请输入资源类型")?>" id="source_type" name="source_type">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="source_start_time"><?=_("开始时间")?></label>
                        <div class="controls">
                            <input type="text" class="SmallInput" onClick="WdatePicker()" name="source_start_time" id="source_start_time" readonly/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="source_end_time"><?=_("结束时间")?></label>
                        <div class="controls">
                            <input type="text" class="SmallInput" onClick="WdatePicker()" name="source_end_time" id="source_end_time" readonly/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="source_count"><?=_("资源数量")?></label>
                        <div class="controls">
                            <input class="input-large" type="text"  placeholder="<?=_("请输入资源数量")?>" id="source_count" name="source_count" onKeyPress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onKeyUp="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="source_price"><?=_("资源单价")?></label>
                        <div class="controls">
                            <input class="input-large" type="text"  placeholder="<?=_("请输入资源单价")?>" id="source_price" name="source_price"  onKeyPress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onKeyUp="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value">
                        </div>
                    </div>
                    <div class="control-group">
                    <div class="controls">
                    <button class="btn btn-primary" type="submit" name="submit" id="submit"><?=_("确定")?></button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn" type="button" onclick="location='proj_resource.php?VALUE=5&PROJ_ID=<?php echo $i_proj_id;?>'"><?=_("返回")?></button>
                    </div>
                    </div>
                </form> 
                </div>
            </div>
        </div>
    </div>
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>