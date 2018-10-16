<?php
//$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("details.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_project.php");

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap-responsive.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="/inc/js_lang.php">/*����*/</script>
<script src="/module/DatePicker/WdatePicker.js">/*ʱ��ؼ�*/</script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>">/*ѡ���š���Ա���*/</script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/choose_div.js"></script>
<script>
jQuery(function()
{
//------��ʼ������Ԫ��------
    function init(){
        var height = jQuery(window).height(); 
        var width = jQuery(window).width()-182;
        jQuery(".proj_sidebar, .content").height(height); 
        jQuery(".content").height(height-jQuery(".navbar").height());
        jQuery(".proj_content").height(height-80);
        jQuery('.proj_container').width(width);
        jQuery('.proj_navbar').width(width-20);
        jQuery('.proj_content').width(width-20);
        jQuery('.mancontent_d').height(height-102);
        jQuery('.mancontent').height(height-204);
        jQuery('.scrollzone').height(height-234);
    }
    
    jQuery(window).resize(function(){init();});
    jQuery(document).ready(function(){
        init();
    });
    
    // jQuery("#save_all").click(function()
    // { 
        // var total = 0.00;
        // var flag = true; 
        // jQuery(".budget-amount").each(function()
        // {   
            // if(jQuery(this).val().trim() != "")
            // {   
                // if(!isNaN(jQuery(this).val()))
                // {   
                    // total += parseFloat(jQuery(this).val().trim());
                    // flag = true;
                // }
                // else
                // {
                    // alert(td_lang.general.project.guide.number);//��������Ч����
                    // flag = false;
                    // return false;
                // }  
            // }
        // });
        // if(flag)
        // {
            // jQuery("#total").val(total);
        // }
    // });
});


//ɾ������ delete_attach.php  jquery post
function delete_attach(ATTACHMENT_ID1,ATTACHMENT_NAME1){
    jQuery.post("delete_attach.php",{
                        ATTACHMENT_ID:ATTACHMENT_ID1,
                        ATTACHMENT_NAME:ATTACHMENT_NAME1,
                        PROJ_ID:"<?=$i_proj_id?>"
                    },
                    function(data){
                        if(data === "true"){
                            location.reload();
                            //�ļ���ɾ�������
                            // for(i=0;i<jQuery(".attach_name").length;i++){
                                // if(jQuery(".attach_name").eq(i).text() == ATTACHMENT_NAME1){
                                    // jQuery(".attach_name").eq(i).parent(".attach_link").hide();
                                // }
                            // }
                        }else{
                            alert("�ļ�"+ATTACHMENT_NAME1+"ɾ��ʧ�ܻ��ļ���ɾ��!");
                        }
                    })
}

</script>

<body style=" overflow:hidden;">
<!--�����-->
<div class="proj_sidebar" style="overflow: visible; padding: 0px; width: 182px;">
<?php 
include_once("public_left.php"); 
?>
</div>
<!--������-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
        <strong>
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >> <?=_("��Ŀ����")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>
   <!-- <div class="tabbable" ><br/>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#Ezone" data-toggle="tab">������Ϣ�޸�</a>
            </li>
        </ul>
    </div>-->
<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <?php include_once("edit/edit_logic.php");?>
            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>