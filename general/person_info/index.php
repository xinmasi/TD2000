<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
if($MAIN_URL=="")
    $MAIN_URL="theme";

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
//ʱ�н�������ʱ����������ֱ����ʾ������
$i_is_fashion = 0;
if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
    $i_is_fashion = 1;
}
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<?
if(MYOA_IS_UN && find_id("zh-TW,en,", MYOA_LANG_COOKIE) && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/un_<?=MYOA_LANG_COOKIE?>.css" />
<?
}
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/person_info/index.css" />
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
if(window.external && typeof window.external.OA_SMS != 'undefined')
{        
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1094, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
</script>
<script>
var MAIN_URL = "<?=$MAIN_URL?>";
$(function(){
    if(MAIN_URL == "theme" || MAIN_URL == "theme"){
        $('.seltheme').addClass('on');
        $('.theme').addClass('active');
    }
    else if(MAIN_URL == "avatar"){
        $('.selperson').addClass('on');
        $('.avatar').addClass('active');
    }
    else if(MAIN_URL == "shortcut"){
        $('.seltheme').addClass('on');
        $('.shortcut').addClass('active');
    }
    $('.nav').click(function(){
        $('.nav').removeClass('on');
        $(this).addClass('on');
        $(this).find('.person-info:last').addClass('last');
    });
    $('.person-info').click(function(){
        $('.person-info').removeClass('active');
        $(this).addClass('active');
    });
});
$(window).resize((function(t){
    var func = function(){
        var height = $(c_main.document.body).attr('scrollHeight')+10,
        windowHeight = $(window).height();
        height = height > windowHeight ? height : windowHeight;
        $('#c_main').height(height);   
    };
    return function(){
        t && clearTimeout(t);
        t = setTimeout(func, 300);
    }
})());
var ostheme = <?=$_SESSION["LOGIN_THEME"]?>;
function settingguide(is_fashion)
{
    if(is_fashion == 1 && window.parent != window && document.getElementById("guide").length<=0)
    {
        window.parent.setting_guide && window.parent.setting_guide('1');
    }
    else
    {
        document.getElementById("guide").style.display="block";
        document.getElementById("guide-main").style.display="block";
        document.getElementById("personlay").style.display="block";   
        jQuery("#guide-main").css("top","30px");
        $(".wizard").scrollable().begin(0);
        //location.reload();
    }
}
</script>

<body class="bodycolor">
<div id="guide" style="display:none">
<? 
if($_SESSION["LOGIN_THEME"] == 15)
{
    include_once("../setting_guide/setting_guide_tos.php"); 
}else{
    include_once("../setting_guide/setting_guide.php"); 
}
?>
</div>

<div class="content">
    <div class="info-left">
        <div class="menu_container">
            <ul>
                <li class="nav seltheme">                    
                    <a href="javascript:;" class="menu_title" id="menu_first_head"><i class="iconfont">&#xe615;</i><?=_("��������")?><span class="arrow_icon"></span></a>
                    <ul id="menu_first" class="menulist">
                        <li class="person-info theme"><a href="theme/" target="c_main"><i class="iconfont">&#xe616;</i><?=_("��������")?></a></li>
                        <li class="person-info portal"><a href="portal/" target="c_main"><i class="iconfont">&#xe618;</i><?=_("�Ż�����")?></a></li>
                        <li class="person-info mytable"><a href="mytable/" target="c_main"><i class="iconfont">&#xe62d;</i><?=_("��Ϣ��������")?></a></li>
                        <li class="person-info shortcut"><a href="shortcut/" target="c_main"><i class="iconfont">&#xe603;</i><?=_("�˵������")?></a></li>
                        <li class="person-info winexe"><a href="winexe/" target="c_main"><i class="iconfont">&#xe601;</i><?=_("Windows�����")?></a></li>
                        <li class="person-info fav"><a href="fav/" target="c_main"><i class="iconfont">&#xe632;</i><?=_("ҳ���ղؼ�")?></a></li>
                        <li class="person-info url last"><a href="url/" target="c_main"><i class="iconfont">&#xe60b;</i><?=_("������ַ")?></a></li>
                    </ul>
                </li>
                <li class="nav selperson">
                    <a href="javascript:;" class="menu_title" id="menu_second_head"><i class="iconfont">&#xe60d;</i><?=_("������Ϣ")?><span class="arrow_icon"></span></a>
                    <ul id="menu_second" class="menulist">
                        <li class="person-info info"><a href="info/" target="c_main"><i class="iconfont">&#xe60e;</i><?=_("��������")?></a></li>
                        <li class="person-info avatar"><a href="avatar/" target="c_main"><i class="iconfont">&#xe61e;</i><?=_("�ǳ���ͷ��")?></a></li>
                        <!--<li class="person-info group"><a href="group/" target="c_main"><i class="iconfont">&#xe638;</i><?=_("�Զ����û���")?></a></li>-->
                        <li class="person-info concern_user"><a href="concern_user/" target="c_main"><i class="iconfont">&#xe620;</i><?=_("���ѷ���")?></a></li>
                        <?
                        $SEAL_FROM=get_sys_para("SEAL_FROM");
						if($SEAL_FROM["SEAL_FROM"]==2)
						{
						?>
                        <li class="person-info seal"><a href="seal_pass/" target="c_main"><i class="iconfont">&#xe641;</i><?=_("ӡ�������޸�")?></a></li>
                        <?
						}
                        ?>
                    </ul>
                </li>
                <li class="nav">
                    <a href="javascript:;" class="menu_title" id="menu_third_head"><i class="iconfont">&#xe635;</i><?=_("�ʺ��밲ȫ")?><span class="arrow_icon"></span></a>
                    <ul id="menu_third" class="menulist">
                        <li class="person-info mypriv"><a href="mypriv/" target="c_main"><i class="iconfont">&#xe628;</i><?=_("�ҵ�OA�ʻ�")?></a></li>
                        <li class="person-info pass"><a href="pass/" target="c_main"><i class="iconfont">&#xe631;</i><?=_("�޸�OA����")?></a></li>
                        <li class="person-info log"><a href="log/" target="c_main"><i class="iconfont">&#xe602;</i><?=_("��ȫ��־")?></a></li>
                        <li class="person-info email"><a href="email/" target="c_main"><i class="iconfont">&#xe62b;</i><?=_("��Ϣ��������")?></a></li>
                    </ul>
                </li>
                
                <li class="nav">
                    <a href="javascript:;" class="menu_title" id="menu_fouth_head"><i class="iconfont">&#xe615;</i><?=_("������")?><span class="arrow_icon">&#xe622;</span></a>
                    <ul id="menu_forth" class="menulist">
                        <li class="person-info guide"><a href="javascript:void(0);" onClick="settingguide('<?=$i_is_fashion?>');"><i class="iconfont">&#xe60a;</i><?=_("����������")?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="info-right">
        <iframe id="c_main" name="c_main" src="<?=$MAIN_URL?>" width="100%" height="100%" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" src="<?=$MAIN_URL?>"></iframe>
    </div>
</div>
</body>
</html>
