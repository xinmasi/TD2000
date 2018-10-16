<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ڹ���");
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
    <div class="content" value="<?=_("�Ű�����")?>" onClick="location='duty';">
        <span class="iconfont duty-icon dicon-1">&#xe6d7;</span>
        <p><?=_("�Ű�����")?></p>
    </div>
    <div class="content" value="<?=_("��ǩ��Ա")?>" onClick="location='no_duty';">
        <span class="iconfont duty-icon dicon-2">&#xe67f;</span>
        <p><?=_("��ǩ��Ա")?></p>
    </div>
    <div class="content" value="<?=_("��ǩ�ڼ���")?>" onClick="location='holiday';">
        <span class="iconfont duty-icon dicon-3">&#xe6dd;</span>
        <p><?=_("��ǩ�ڼ���")?></p>
    </div>
    <div class="content" value="<?=_("ѡ���ڷ�ʽ")?>" onClick="location='punch';">
        <span class="iconfont duty-icon dicon-4">&#xe857;</span>
        <p><?=_("ѡ���ڷ�ʽ")?></p>
    </div>
    <div class="content" value="<?=_("�������")?>" onClick="location='leave';">
        <span class="iconfont duty-icon dicon-5">&#xe6dc;</span>
        <p><?=_("�������")?></p>
    </div>
    <div class="content" value="<?=_("���ڷ�ʽ")?>" onClick="location='duty_type';">
        <span class="iconfont duty-icon dicon-6">&#xe6db;</span>
        <p><?=_("���ڷ�ʽ����")?></p>
    </div>

    <div class="content" value="<?=_("ɾ����������")?>" onClick="location='data';">
        <span class="iconfont duty-icon dicon-7">&#xe6d4;</span>
        <p><?=_("�������ݹ���")?></p>      
    </div>

    <div class="content" value="<?=_("���ÿ�����������")?>" onClick="location='manager?MANAGER_ID=1';">
       <span class="iconfont duty-icon dicon-8">&#xe6d9;</span>
       <p> <?=_("���ÿ�����������")?></p> 
    </div>

    <div class="content" value="<?=_("¼�����ԭ����дҪ��")?>" onClick="location='out_requirement';">
        <span class="iconfont duty-icon dicon-9">&#xe6e0;</span>
        <p><?=_("���ԭ����дҪ��")?></p>   
    </div>

    <div class="content" value="<?=_("���ڻ�����")?>" onClick="location='attend_machine';">
        <span class="iconfont duty-icon dicon-10">&#xe6d6;</span>
        <p><?=_("���ڻ�����")?></p>
    </div>  

    <div class="content" value="<?=_("���ô�������Ա")?>" onClick="location='hr_agent';">
        <span class="iconfont duty-icon dicon-11">&#xe6da;</span>
        <p><?=_("���ô�������Ա")?></p>
    </div>
    

    <div class="content" value="<?=_("���ÿ����Ű�����")?>" id="setHrType">
        <span class="iconfont duty-icon dicon-12">&#xe6d8;</span>
        <p><?=_("���ÿ����Ű�����")?></p>    
    </div>
    <span class="warnspan">
        (<?=_("˵����1�������������١�����Ӱ�Ĵ�������Ա���Ǽ�Ȩ�޾�Ϊ���ô�������Ա")?>)<br/>
        (<?=_("˵����2�����ӻ��޸��Ű๫���ա���ǩ�ڼ��պ���Ҫ��������Ա���Ű�")?>)<br/>
        (<?=_("˵����3���޸��Ű����ͺ�ֻ��δ�򿨵�Ա����Ч")?>)
    </span>
</div>
<script type="text/javascript">
    $(function() {
        $('#setHrType').click(function(){
			if( parent && parent.createTab){
				parent.createTab('hr_type','���ÿ����Ű�����','hr/setting/attendance/hr_type/','');
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
