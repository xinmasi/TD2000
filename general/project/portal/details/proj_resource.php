<?php
//$HTML_PAGE_TITLE = _("资源管理");
include_once("details.inc.php");

include_once("general/workflow/prcs_role.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >> <?=_("资源管理")?>
        </strong>
        </p>
        <? help('012','skill/project');?>
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#Tzone" data-toggle="tab"> <?=_("资源详情")?> </a>
                </li>
                <li>
                    <a href="#Pzone" data-toggle="tab"> <?=_("资源汇总")?> </a>
                </li>
                <li class="pull-right">
                    <!--<a href="flow/proj_flow_source.php?PROJ_ID=<?=$i_proj_id?>"> <?=_("申请资源")?> </a>-->
                        <a href="proj_resource_new.php?VALUE=5&PROJ_ID=<?=$i_proj_id?>"><?=_("申请资源")?></a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- 资源详情 -->
                <div class="tab-pane active" id="Tzone" style="overflow-y:auto;height:90%">
                    <? 
						include_once("resource/resource.php");
					?>
                </div>
                <!-- 资源汇总 -->
                <div class="tab-pane " id="Pzone" style="height:90%">
                   <?
					    include_once("resource/resource_type.php");
				   ?>
                </div>
            </div>
        </div>
    </div>
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>