<?php
//$HTML_PAGE_TITLE = _("��Դ����");
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
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("��Դ����")?>
        </strong>
        </p>
        <? help('012','skill/project');?>
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#Tzone" data-toggle="tab"> <?=_("��Դ����")?> </a>
                </li>
                <li>
                    <a href="#Pzone" data-toggle="tab"> <?=_("��Դ����")?> </a>
                </li>
                <li class="pull-right">
                    <!--<a href="flow/proj_flow_source.php?PROJ_ID=<?=$i_proj_id?>"> <?=_("������Դ")?> </a>-->
                        <a href="proj_resource_new.php?VALUE=5&PROJ_ID=<?=$i_proj_id?>"><?=_("������Դ")?></a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- ��Դ���� -->
                <div class="tab-pane active" id="Tzone" style="overflow-y:auto;height:90%">
                    <? 
						include_once("resource/resource.php");
					?>
                </div>
                <!-- ��Դ���� -->
                <div class="tab-pane " id="Pzone" style="height:90%">
                   <?
					    include_once("resource/resource_type.php");
				   ?>
                </div>
            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>