<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("项目管理");
include_once("inc/header.inc.php");
/**
 *
 * 项目管理看板首页 项目查询看板首页
 * 采用jquery.tmpl + $.getJSON 实现
 * 调用可选参数 PROJ_STATUS
 * @name index.php
 * @version 1.0 2013-10-22
 * @author zfc
 *
 */
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js"></script>

<script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.css" />
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.js"></script>


<script type="text/javascript">
//------------zfc-------------
var obj_op = false;
var now_proj_status = <?=isset($PROJ_STATUS) ? intval($PROJ_STATUS) : 2;?>;
var url = "";
var page = 1;

function open_project(PROJ_ID,FORMAT)
{
    if(obj_op)
        obj_op.close();
    URL="details/?PROJ_ID="+PROJ_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100;
    mywidth=780;
    myheight=500;
    if(FORMAT == "1")
    {
        myleft=0;
        mytop=0;
        mywidth=screen.availWidth-25;
        myheight=screen.availHeight-70;
    }
    obj_op = window.open(URL,"project_detail_"+PROJ_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function hide_mask(){
    $("#nonediv").hide();
    $("#alertdiv").hide();
    $("#alert-iframe").attr("src","");
}


function open_mask(){
    $('#nonediv').show();
    $('#alertdiv').show();
    $('#alert-iframe').attr("src","new/index.php");
}
</script>
<script id="myTemplate" type="text/x-jquery-tmpl">
    <li class="span3">
        <a href="#" onclick="javascript:open_project('${PROJ_ID}',1)" class="thumbnail {{if XMYJ != -1 }}{{if PROJ_STATUS == 3 || XMYJ == 0 }}warning{{else}}error{{/if}}{{/if}} "title='<?=_("单击进入项目详情界面")?>' >
            <div class="info-position">
            <h3 class="h3title" title="${PROJ_NAME}">${PROJ_NAME}&nbsp;</h3>
            <p class='ppimg'>
            {{if PROJ_LEVEL == 'a'}}
            <img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("A级：非常重要")?>'>
            {{else PROJ_LEVEL == 'b'}}
            <img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("B级：重要")?>'>
            {{else PROJ_LEVEL == 'c'}}
            <img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("C级：普通")?>'>
            {{else}}
            <img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("C级：普通")?>'>
            {{/if}}
            </p>
                        <div class="clear"></div>
            </div>


            <p class="ppfwr"><?=_("负责人")?>:${PROJ_LEADER}&nbsp;</p>
            <p><?=_("开始日期")?>:${PROJ_START_TIME}&nbsp;</p>
            <p><?=_("计划完成")?>:${PROJ_END_TIME}&nbsp;
                {{if NEW_CHANGE == 1 }}<img class="pull-right" src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/proj_change.png' title='<?=_("项目有变化")?>'>{{/if}}
                <img class="pull-right" src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/proj_warning.png'  {{if XMYJ == -1 }}style="visibility:hidden"{{/if}} title='{{if XMYJ == 0 }}<?=_("今天是项目最后一天")?>{{else}}<?=_("项目已经超出计划完成时间")?>${XMYJ}<?=_("天")?>{{/if}}'></p>
            <div class="progress progress-striped{{if XMYJ != -1 }}{{if PROJ_STATUS == 3 || XMYJ == 0 }} progress-warning{{else}} progress-danger{{/if}}{{/if}}" style="margin-top:14px" title='<?=_("项目进度")?>${PROJ_PERCENT_COMPLETE}%' >
                <div class="bar" style="width:${PROJ_PERCENT_COMPLETE}%"  ><span style="color:black">${PROJ_PERCENT_COMPLETE}%</span></div>
            </div>
        </a>
    </li>
</script>
<script>
/**获取li标签的value值，以url的方式传到后台。*/

function load_tmpl(){
    url_for_load = "proj_list.php?PROJ_STATUS=" + now_proj_status ;
    $.getJSON(url_for_load + url,function(data)
    {
        $("#append_id").empty();
        $('#myTemplate').tmpl(data.data).appendTo('#append_id');
        $append_last_li = $("<li id='newE' class='span3'>"+"<a href='#' class='xjxm_a add_new_proj'>"+"<h2 style='text-align:center;'><?=_("新建项目")?></h2></a></li>");
        $append_last_li.appendTo('#append_id');
    });
}

/**设置初始化长度，宽度*/
function init()
{
    var width = $(window).width();
    var height = $(window).height();
    $('.proj_sidebar').height(height);
    $('.proj_container').width(width-190);
    $('.proj_navbar').width(width-202);
    $('.index_content').height(height-110);
    $('.index_content').width(width-200);

}

$(window).resize(function(){init();});
$(document).ready(function(){
    init();
    if(window.external && typeof window.external.OA_SMS != 'undefined')
    {
        var h = Math.min(800, screen.availHeight - 100),
            w = Math.min(1280, screen.availWidth - 180);
        window.external.OA_SMS(w, h, "SET_SIZE");
    }
    //初始化显示的项目
    load_tmpl();

    /* set tab change */
    var notify_menu = $('#notify_menu');
    notify_menu.delegate('li[id^=li_]','click',function()
    {
        if($(this).hasClass('nav-header'))
        {
            return;
        }

        notify_menu.find("li.active").removeClass("active");
        $(this).addClass("active");
        now_proj_status = $(this).attr('id').substr(3,1);
        load_tmpl();
    });

    //显示新建页面
    $('body').delegate(".xjxm_a","click",function()
    {
        open_mask();
    });


    //显示查询页面
    $('body').delegate(".find_proj","click",function()
    {
        $('#nonediv').show();
        $('#alertdiv-for-find').show();
    });

    //判断页面如果是查询页面 直接显示查询
    <?php
    if(isset($find)){
    ?>
    $(".find_proj").click();
    <?
    }
    ?>

});

//隐藏查询
function quxiao_find(){
    $('#nonediv').hide();
    $('#alertdiv-for-find').hide();
}


//查询
function find_find(){
    page = 1;
    $(".find_exit").show();
    quxiao_find();
    in_url();
    load_tmpl();
    proj_num();

}

//退出查询  数据初始化  主要:分页page  url串
function find_exit(){
    $(".find_exit").hide();
    url = "";
    page = 1;
    load_tmpl();
    proj_num();
    clear_find();
}


//退出查询 条件 初始化
function clear_find(){
    $("#PROJ_NAME").val("");
    $("#PROJ_NUM").val("");
    $("#PROJ_START_TIME").val("");
    $("#PROJ_END_TIME").val("");
    $("#PROJ_TYPE option").eq(0).attr("selected",true);
    $(".level input").attr("checked",false);
    $("#PROJ_NUM_M,#PROJ_NAME_M").attr("checked",false);
}

//拼接url
function in_url(){
    var url1 = "";
    var PROJ_NUM = $("#PROJ_NUM").val();
    var PROJ_TYPE = $("#PROJ_TYPE").val();
    var PROJ_NAME = $("#PROJ_NAME").val();
    var PROJ_START_TIME = $("#PROJ_START_TIME").val();
    var PROJ_END_TIME = $("#PROJ_END_TIME").val();
    var PROJ_LEVEL = $(".level input:checked");
    var LEVEL = "";

    for(i=0 ;i<PROJ_LEVEL.length; i++){
        LEVEL += PROJ_LEVEL.eq(i).val();
    }

    if(PROJ_NUM != "")
        url1 += "&PROJ_NUM=" + PROJ_NUM;
    if(PROJ_TYPE != "")
        url1 += "&PROJ_TYPE=" + PROJ_TYPE;
    if(PROJ_NAME != "")
        url1 += "&PROJ_NAME=" + PROJ_NAME;
    if(LEVEL != "")
        url1 += "&PROJ_LEVEL=" + LEVEL;
    if(PROJ_START_TIME != "")
        url1 += "&PROJ_START_TIME=" + PROJ_START_TIME;
    if(PROJ_END_TIME != "")
        url1 += "&PROJ_END_TIME=" + PROJ_END_TIME;
    if($("#PROJ_NAME_M").is(":checked"))
        url1 += "&PROJ_NAME_M=1";
    if($("#PROJ_NUM_M").is(":checked"))
        url1 += "&PROJ_NUM_M=1";


    url = "&find=find" + url1;
}
</script>
<style>

#page_handle{
    overflow-y:scroll;
}

.thumbnails
{
    margin-left:40px;
}
/*灰色底*/
#nonediv
{
    width:100%;
    height:100%;
    background:#000;
    filter:alpha(Opacity=50);
    -moz-opacity:0.5;
    opacity: 0.5;
    position:absolute;
    z-index:10;
}

/*鼠标悬浮样式*/
#append_id a:hover
{
    text-decoration: none;
    color:white;
}

/*弹出的div层*/
#alertdiv-for-find,#alertdiv
{
    width:100%;
    height:100%;
    position:absolute;
    left:0px;
    top:0px;
    z-index:50;
}

/*框架*/
#alert-iframe
{
    width:910px;
    height:90%;
    margin:20px auto;
    background:#FFFFFF;
}
.h3title
{
    font-family: '微软雅黑';
    line-height:20px;
    font-size:18px;
}
#loading{
    height:30px;
    line-height:30px;
    width:100%;
    display:none;
    text-align:center;

}
.hide{
    display:none;
}
</style>


<script>
$(function(){
    var dataE = true;
    //距离底部多少 百分比的小数形式 开始读取数据
    var drop = 0.1;
    var handle = $("#page_handle");
    var handleH = handle.height();;
    var scrollTop = 0;
    var speed = 500;
    //获取padding
    var padding = {top : 0, bottom : 0};
    handle.scroll(function(){
        scrollTop = $(this).scrollTop();
        scrollH = $(this)[0].scrollHeight;
        padding.top = parseInt($(this).css("padding-top"));
        padding.bottom = parseInt($(this).css("padding-bottom"));

        if(handleH + scrollTop + padding.top + padding.bottom >= scrollH - drop * scrollH){
            if(dataE){
                dataE = false;
                $("#loading").text("正在加载...").fadeIn(speed).fadeOut(speed);
                $.getJSON("proj_list.php?PROJ_STATUS=" + now_proj_status + url + "&PAGE=" + page++)
                    .success(function(data){
                        if(data.count > 0){
                            $("#newE").remove();
                            //卡的话去除动画
                            $('#myTemplate').tmpl(data.data).appendTo('#append_id').hide().delay(50).fadeIn(speed);
                            $append_last_li = $("<li id='newE' class='span3'>"+"<a href='#' class='xjxm_a add_new_proj'>"+"<h2 style='text-align:center;'><?=_("新建项目")?></h2></a></li>");
                            $append_last_li.appendTo('#append_id');
                            $("#loading").text("加载完成").fadeIn(speed).fadeOut(speed);
                            dataE = true;
                        }else{

                            $("#loading").text("没有更多的数据").fadeIn(speed).fadeOut(speed);
                        }
                    })
                    .fail(function( jqxhr, textStatus, error ) {
                        //加载失败点击重新加载
                        $("#loading").html("加载失败... <a href='#' onclick='load_tmpl()'>点击这里重新载入</a> [" + error + "]").show(speed);
                    });

            }

        }

    })

    $("#notify_menu li").click(function(){
        //页面初始化
        $("#totop").click();
        page = 1;
        dataE = true;
        $("#loading").html("");
        proj_num();
    })

    //返回顶部插件
    handle.TOP({showHeight:150,speed:500,animate:false})
})
</script>

<body style="overflow:hidden">
<!--侧边栏-->
<div class="proj_sidebar" style="overflow:visible; padding:0px; width:182px; ">
    <div class="sidebar_content">
        <p class="new_button">
            <a class="btn btn-success btn-large xjxm_a" href="#" name="xjxm_a"><span><?=_("新建项目")?></span></a>
        </p>
        <?php
        $act = isset($PROJ_STATUS)?$PROJ_STATUS:2;
        ?>
        <ul id="notify_menu" class="nav nav-list">
            <li class="<?if($act == 0) echo "active";?>" id="li_0"><a href="#"><i class="doing-1"></i><?=_("立项中")?></a><button class="btn btn-mini btn-info proj_num" style="float:right; margin:13px 10px;" type="button">0<!--proj_num--></button></li>
            <li class="<?if($act == 1) echo "active";?>" id="li_1"><a href="#"><i class="approve-1"></i><?=_("审批中")?></a><button class="btn btn-mini btn-info proj_num" style="float:right; margin:13px 10px;" type="button">0<!--proj_num--></button></li>
            <li class="<?if($act == 2) echo "active";?>" id="li_2"><a href="#"><i class="doinging-1"></i><?=_("办理中")?></a><button class="btn btn-mini btn-info proj_num" style="float:right; margin:13px 10px;" type="button">0<!--proj_num--></button></li>
            <li class="<?if($act == 4) echo "active";?>" class="<?if($act == 0) echo "active";?>"  id="li_4"><a href="#"><i class="hang-1"></i><?=_("挂起中")?></a><button class="btn btn-mini btn-info proj_num" style="float:right; margin:13px 10px;" type="button">0<!--proj_num--></button></li>
            <li class="<?if($act == 3) echo "active";?>" id="li_3"><a href="#"><i class="done-1"></i><?=_("已办结")?></a><button class="btn btn-mini btn-info proj_num" style="float:right; margin:13px 10px;" type="button">0<!--proj_num--></button></li>
            <li><a href="#" class="find_proj" style="width:75%;"><i class="search-1-1"></i><?=_("查&nbsp;&nbsp;询")?></a><button class="btn btn-mini find_exit hide btn-danger" style="float:right; width:21px; margin:13px 10px;" onclick="find_exit()" title="退出查询模式" type="button"><span style="margin-left:-2px;">&times;</span><!--exit--></button></li>
        </ul>
    </div>
</div>

<!--导航栏-->
<div class="proj_container">
    <div class="proj_navbar">
        <p class="proj_navbar_header">
            <strong>
                <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=_("项目中心")?>
            </strong>
        </p>
        <p style="float:right;"><? help('008','skill/project'); //帮助?></p>
    </div>
    <div class="index_content" id="page_handle">
        <ul class="thumbnails" id="append_id">
            <!--ajax显示数据-->
        </ul>
    </div>
    <div id="loading"></div>
</div>



<div id="alertdiv" class="hide">
    <div style="width:910px; height:100%; margin:0px auto;">
        <iframe id="alert-iframe" scrolling="no" frameborder="0"></iframe>
    </div>
</div>
<div id="nonediv" class="hide"><!--灰色底--></div>
<div id="alertdiv-for-find" class="hide">
    <?
    require_once("proj_find_temp.php");
    ?>
</div>

<script>
//返回对应状态下的对应数量并对应赋值  查询需要 + url 条件  *所有
function proj_num(){
    $.getJSON("get_proj_num.php?" + url,function(data)
    {
        $(".proj_num").text(0);
        for(var i in data){
            var str = data[i];
            $("#notify_menu li[id=li_" + i + "]").children(".proj_num").text(str);//.removeClass("hide");
        }
    });
}
proj_num();
</script>

</body>
</html>
