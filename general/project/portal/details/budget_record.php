<?
include_once("details.inc.php");

//ȡ����Ŀ��   ---ֻȡ��Ŀʵ���ʽ����е���Ŀ
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

//ȡ����Ŀ�ʽ�������   ---ȡȫ��
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
//------��ʼ������Ԫ��------
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
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >><?=_("���ü�¼")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#record_ing" data-toggle="tab"><?=_("������")?></a>
                        </li>
                        <li>
                            <a href="#record_ed" data-toggle="tab"><?=_("��ͨ��")?></a>
                        </li>
                        
                         <li>
                            <a href="#record_no" data-toggle="tab"><?=_("δͨ��")?></a>
                        </li>                               
                    </ul>
                <div class="tab-content" style="height:100%;">
                    <!-- ������ -->
                    <div class="tab-pane active" id="record_ing" style="overflow-y:auto;height:90%">
                        <?php include_once("budget_record/budget_ing.php");?>
                    </div>
                    <!-- ��ͨ�� -->
                    <div class="tab-pane " id="record_ed" style="height:90%" >
                        <? include_once("budget_record/budget_ed.php");?>
                    </div>
                    <!-- δͨ�� -->
                    <div class="tab-pane " id="record_no" style="height:90%; overflow:hidden">
                        <? include_once("budget_record/budget_no.php");?>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>