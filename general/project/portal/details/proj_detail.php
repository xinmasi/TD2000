<? 
include_once("inc/utility_project.php");
include_once("inc/utility_all.php");
include_once("details.inc.php");

include_once("general/workflow/prcs_role.php");

if($PROJ_ID)
{
    //修改事务提醒状态--yc
    update_sms_status('42',$PROJ_ID);
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

<script>
function del(PROJ_ID){
	var flag=window.confirm("<?=_('确认要销毁项目吗？');?>");
	if(flag == true){
	    URL = "../../proj/delete.php?PROJ_ID_STR="+PROJ_ID;
        $.get(URL,function(data) {
            alert(td_lang.general.project.msg.msg_5);//'所选项目已全部删除'
            window.opener.load_tmpl();
            window.close();
        });
	}
}
function regain(PROJ_ID)
{
    var flag = window.confirm("<?=_('确认要恢复项目吗？');?>");
    if(flag==true){
        URL = "regain.php?PROJ_ID="+PROJ_ID;
        window.location.href = URL;
    }
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >><?=_("项目详情")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    <?php
    if( $_SESSION['LOGIN_USER_ID']==$s_leader_id || $_SESSION['LOGIN_USER_ID']==$s_owner_id ||  $_SESSION['LOGIN_USER_ID'] == $s_manager_id)
    {
    ?>
  <p align="center" class="xjxm_xjxm_xg">
    <a class="btn btn-warning" href="#" onClick="javascript:del('<?=$i_proj_id?>')" ><span class="detail_destroy"><?=_("销毁项目")?></span></a>
<?php 
    }
?>
    <?php 
    if( ( $_SESSION['LOGIN_USER_ID']==$s_leader_id || $_SESSION['LOGIN_USER_ID']==$s_owner_id ) && $i_status==3)
    {
    ?>
    <a class="btn btn-success" href="#" onClick="javascript:regain('<?=$i_proj_id?>')" ><span class="regain_proj_job"><?=_("恢复执行")?></span></a> 
    <?php 
    }
    ?>
    <?php
    if($_SESSION['LOGIN_USER_ID']==$s_leader_id || $_SESSION['LOGIN_USER_ID']==$s_owner_id)
    {
    ?>
    <a style="display:<?php echo $s_dis?>;" class="btn btn-success" href="proj_edit.php?VALUE=1&PROJ_ID=<?=$i_proj_id?>"><span class="detail_revise"><?=_("修改项目")?></span></a>
    <?php 
    }
    ?>
  </p>

    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 
                <?php include_once("details/details.php");?>
            </div>
        </div>
    </div>
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>