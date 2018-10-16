<?php
//$HTML_PAGE_TITLE = _("Ԥ�㼰�ɱ�");
include_once("details.inc.php");

include_once("general/workflow/prcs_role.php");
include_once("budget.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery-ui.custom.min.js<?=$GZIP_POSTFIX?>"></script>
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
    $(document).ready(function(){init();});
    $(window).resize(function(){init();});
    
});

</script>
<!--Ԥ��ɱ�-->
    <!--��ͼ-->
    <script src="/module/DatePicker/WdatePicker.js">/*ʱ��ؼ�*/</script>
    <!--��״ͼ����״ͼ-->
    <script src="<?=MYOA_JS_SERVER?>/static/js/highcharts/highcharts.js"></script>
    <script src="<?=MYOA_JS_SERVER?>/static/js/highcharts/modules/exporting.js"></script>
    <script src="<?=MYOA_JS_SERVER?>/static/js/highcharts/modules/data.js"></script>


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
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("Ԥ�㼰�ɱ�")?>
        </strong>
        </p>
        <? help('011','skill/project');?>
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <?php include_once("budget/budget_logic.php");?>
            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>