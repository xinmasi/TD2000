<?
include_once("details.inc.php");

//取出项目名   ---只取项目实际资金中有的项目
$query = "select proj_project.PROJ_OWNER as proj_owner,proj_project.PROJ_MANAGER as proj_manager,proj_project.PROJ_NAME as proj_name,proj_project.PROJ_ID as proj_id,proj_project.PROJ_LEADER as proj_leader from proj_project,proj_budget_real where proj_project.PROJ_ID = proj_budget_real.proj_id";
$cur = exequery(TD::conn(),$query);
$PROJ_NAME_ARR = ARRAY();
$PROJ_LEADER = ARRAY();
$PROJ_OWNER = ARRAY();
$PROJ_MANAGER = ARRAY();
while($ROW = mysql_fetch_array($cur)){
    $PROJ_NAME_ARR[$ROW['proj_id']] = $ROW['proj_name'];
    $PROJ_LEADER[$ROW['proj_id']] = $ROW['proj_leader'];
    $PROJ_OWNER[$ROW['proj_id']] = $ROW['proj_owner'];
    $PROJ_MANAGER[$ROW['proj_id']] = $ROW['proj_manager'];
}

//$PROJ_NAME_ARR = array_unique($PROJ_NAME_ARR);
// print_r($PROJ_NAME_ARR);
// exit();

function is_in($str,$str1){
    $pos = strpos($str, $str1);
    if( $pos !== false){
        return true;
    }else{
        return false;
    }
}

//取出项目资金类型名   ---取全部
$query = "select TYPE_NAME,ID from proj_budget_type";
$cur = exequery(TD::conn(),$query);
$PROJ_TYPE_ARR = array();
while($ROW = mysql_fetch_array($cur)){
    $PROJ_TYPE_ARR[$ROW['ID']] = $ROW['TYPE_NAME'];
}

?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >><?=_("费用记录")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#record_ing" data-toggle="tab"><?=_("申请中")?></a>
                        </li>
                        <li>
                            <a href="#record_ed" data-toggle="tab"><?=_("已通过")?></a>
                        </li>
                        
                         <li>
                            <a href="#record_no" data-toggle="tab"><?=_("未通过")?></a>
                        </li>                               
                    </ul>
                <div class="tab-content" style="height:100%;">
                    <!-- 申请中 -->
                    <div class="tab-pane active" id="record_ing" style="overflow-y:auto;height:90%">
                        <?php include_once("budget_record/budget_ing.php");?>
                    </div>
                    <!-- 已通过 -->
                    <div class="tab-pane " id="record_ed" style="height:90%" >
                        <? include_once("budget_record/budget_ed.php");?>
                    </div>
                    <!-- 未通过 -->
                    <div class="tab-pane " id="record_no" style="height:90%; overflow:hidden">
                        <? include_once("budget_record/budget_no.php");?>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>