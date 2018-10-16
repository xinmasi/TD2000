<?php
//$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("action/details.inc.php");

?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
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
    <div class="sidebar_content">
        <p class="sidebar_contentp"><img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/logo.png"></p>
        <ul id="notify_menu" class="nav nav-list">
            <li value="1"><a href="proj_detail.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="search-1"></span><?=_("��Ŀ����")?></a> </li>
            <li class="active" value="2"><a href="proj_progression.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="progress-1"></span><?=_("��Ŀ����")?></a></li>
            <li value="1"><a href="proj_time.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="time-1"></span><?=_("ʱ�����")?></a> </li>
            <li value="2"><a href="proj_budget.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="money-1"></span><?=_("Ԥ�㼰�ɱ�")?></a> </li>
            <li value="1"><a href="proj_resource.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="setting-1"></span><?=_("��Դ����")?></a></li>         
            <li value="2"><a href="proj_report.php?PROJ_ID=<?php echo $i_proj_id?>"><span class="paper-1"></span><?=_("���ܱ���")?></a> </li>
        </ul>
    </div>
</div>
<!--������-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
           <strong>
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("��Ŀ����")?>
        </strong>
        </p>
        <? help('001','skill/project');?>
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <?php include_once("../../proj/new/task/index.php");?>
            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>