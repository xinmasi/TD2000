<?php  
//$HTML_PAGE_TITLE = _("时间管理");
include_once("details.inc.php");

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
        $("table").stickyTableHeaders();
    });
});
//------------zfc---------------
var p_t = false;
function print_time(){
    if(p_t)
        p_t.close();
	p_t = window.open('time_print.php?PROJ_ID=<?=$i_proj_id?>');
}
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >> <?=_("时间管理")?>
        </strong>
        </p>
        <? help('006','skill/project');?>
		<p align="center" class="xjxm_xjxm_xg"> 
		    <a style="width:100px; margin:5px auto; " class="btn btn-success" onclick="print_time()" ><?=_("打印")?></a> 
		</p>
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <?php include_once("time/time.php"); ?>
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