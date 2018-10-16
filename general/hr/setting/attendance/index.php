<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("考勤管理");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="/static/common/iconfont/iconfont.css?160505">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/setting/attendance.css?123">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<style type="text/css">
    body{
        font-size: 13px;
    }
    body .warnspan{
        font-size: 13px; 
        position: static; 
        margin-top: 60px;
    }
</style>
<body class="" >
<div class="wrapper">
    <div class="content" value="<?=_("排班类型")?>" onClick="location='duty';">
        <span class="iconfont duty-icon dicon-1">&#xe6d7;</span>
        <p><?=_("排班类型")?></p>
    </div>
    <div class="content" value="<?=_("免签人员")?>" onClick="location='no_duty';">
        <span class="iconfont duty-icon dicon-2">&#xe67f;</span>
        <p><?=_("免签人员")?></p>
    </div>
    <div class="content" value="<?=_("免签节假日")?>" onClick="location='holiday';">
        <span class="iconfont duty-icon dicon-3">&#xe6dd;</span>
        <p><?=_("免签节假日")?></p>
    </div>
    <div class="content" value="<?=_("选择考勤方式")?>" onClick="location='punch';">
        <span class="iconfont duty-icon dicon-4">&#xe857;</span>
        <p><?=_("选择考勤方式")?></p>
    </div>
    <div class="content" value="<?=_("年假设置")?>" onClick="location='leave';">
        <span class="iconfont duty-icon dicon-5">&#xe6dc;</span>
        <p><?=_("年假设置")?></p>
    </div>
    <div class="content" value="<?=_("考勤方式")?>" onClick="location='duty_type';">
        <span class="iconfont duty-icon dicon-6">&#xe6db;</span>
        <p><?=_("考勤方式设置")?></p>
    </div>

    <div class="content" value="<?=_("删除考勤数据")?>" onClick="location='data';">
        <span class="iconfont duty-icon dicon-7">&#xe6d4;</span>
        <p><?=_("考勤数据管理")?></p>      
    </div>

    <div class="content" value="<?=_("设置考勤审批规则")?>" onClick="location='manager?MANAGER_ID=1';">
       <span class="iconfont duty-icon dicon-8">&#xe6d9;</span>
       <p> <?=_("设置考勤审批规则")?></p> 
    </div>

    <div class="content" value="<?=_("录入外出原因填写要求")?>" onClick="location='out_requirement';">
        <span class="iconfont duty-icon dicon-9">&#xe6e0;</span>
        <p><?=_("外出原因填写要求")?></p>   
    </div>

    <div class="content" value="<?=_("考勤机设置")?>" onClick="location='attend_machine';">
        <span class="iconfont duty-icon dicon-10">&#xe6d6;</span>
        <p><?=_("考勤机设置")?></p>
    </div>  

    <div class="content" value="<?=_("设置代考勤人员")?>" onClick="location='hr_agent';">
        <span class="iconfont duty-icon dicon-11">&#xe6da;</span>
        <p><?=_("设置代考勤人员")?></p>
    </div>
    

    <div class="content" value="<?=_("设置考勤排班类型")?>" id="setHrType">
        <span class="iconfont duty-icon dicon-12">&#xe6d8;</span>
        <p><?=_("设置考勤排班类型")?></p>    
    </div>
    <span class="warnspan">
        (<?=_("说明：1、设置外出、请假、出差、加班的代考勤人员代登记权限均为设置代考勤人员")?>)<br/>
        (<?=_("说明：2、增加或修改排班公休日、免签节假日后需要重新设置员工排班")?>)<br/>
        (<?=_("说明：3、修改排班类型后，只对未打卡的员工生效")?>)
    </span>
</div>
<script type="text/javascript">
    $(function() {
        $('#setHrType').click(function(){
			if( parent && parent.createTab){
				parent.createTab('hr_type','设置考勤排班类型','hr/setting/attendance/hr_type/','');
			}else if(top && top.openURL){
				top &&  top.openURL && top.openURL('/general/hr/setting/attendance/hr_type/','')
			}else{
				location.href = '/general/hr/setting/attendance/hr_type/';
			}
        })
    function isMatch (){
        var $urlStr = location.hash.split("#");
        if($urlStr != ''){
            var $idHash = $urlStr[1];
            for( var i= 0;i<$("div.accordion-body").length;i++){
                if($("div.accordion-body").eq(i).attr('id')==$idHash){
                    $("div.accordion-body").eq(i).removeClass('in');
                    $("div.accordion-body").eq(i).addClass('in');
                }else{
                    $("div.accordion-body").eq(i).removeClass('in');
                }
            }
        }
    }
    isMatch();
    });
</script>
</body>
</html>
