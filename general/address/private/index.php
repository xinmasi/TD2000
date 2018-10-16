<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("通讯簿");
include_once("inc/header.inc.php");

$SPLITTERLEFT=intVal($_COOKIE["addrbarleft"]);
if($SPLITTERLEFT!=0)
{
    $centerleft = $SPLITTERLEFT+216;
    $leftwidth  = $SPLITTERLEFT+207;
}
else{
    $centerleft=216;
}
/*
if($FROM_FLAG==1)
{
    //修改事务提醒状态--yc
    $sql = "update sms set REMIND_FLAG = '0' where WORK_ID = '0' and WORK_TYPE = '20' and REMIND_FLAG!='0' and TO_ID =
'".$_SESSION["LOGIN_USER_ID"]."'";
    exequery(TD::conn(),$sql);
}
*/
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/index.css" />
<?
echo "<style>";
echo ".splitter-bar-vertical{left:".$SPLITTERLEFT."px}";
echo "#splitter-bar{left:".$SPLITTERLEFT."px}";
echo ".center{left:".$centerleft."px}";
echo ".left{width:".$leftwidth."px}";
echo "</style>";
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
//如果从OA精灵打开，则最大化窗口
if(window.external && typeof window.external.OA_SMS != 'undefined')
{        
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
function goto(SHARE_TYPE,GROUP_ID,PUBLIC_FLAG,TYPE)
{
    var power   = document.getElementById("power").value;
    var sharing = document.getElementById("sharing").value;
    var url = "address/talklist.php?GROUP_ID="+GROUP_ID+"&PUBLIC_FLAG="+PUBLIC_FLAG+"&SHARE_TYPE="+SHARE_TYPE+"&TYPE="+TYPE+"&POWER="+power+"&SHARING="+sharing;
    document.getElementById('GROUP_ID').value    = GROUP_ID;
    //document.getElementById('iframe_export').src = "address/export.php?GROUP_ID="+GROUP_ID;
    document.getElementById('talklist').src      = url;
    document.getElementById('search_list').value = '<?=_("搜索联系人...")?>';
    document.getElementById("gettype").value     = TYPE;
    document.getElementById("share_type").value  = SHARE_TYPE;
    $('[name=list_hover]').css("background","url(<?=MYOA_JS_SERVER?>/static/modules/address/images/white-tras.png) no-repeat");
}

$(function(){
	dragSplitter();
    /*第一次加载时定位分组高度*/
    var my_group_count    = $('#my_group_count').val();
    var share_group_count = $('#share_group_count').val();
    var work_group_count = $('#work_group_count').val();
    var height            = $(window).height() - $('#lianxi').offset().top - 132;
    
    if(height - my_group_count*47 > 0)
    {
        $('#my_group').height(my_group_count*47);
    }
    else
    {
        $('#my_group').height(height);
    }
    
    if(height - share_group_count*47 > 0)
    {
        $('#share').height(share_group_count*47);
    }
    else
    {
        $('#share').height(height);
    }
    
    if(height - work_group_count*47 > 0)
    {
        $('#work').height(work_group_count*47);
    }
    else
    {
        $('#work').height(height);
    }    
    
    /*第一次加载时字母列取高度*/
    var letter_arr   = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    var circle_count = Math.floor(($(window).height() - $('#list').offset().top) / 38)//圆的个数
    var letter_count = Math.ceil(26/circle_count);//每个圆最多放字母数
    var el_count     = (letter_count*circle_count)%26;//少字母的圆（放字母letter_count-1个）
    var all_count    = 26-el_count;//满字母的圆（放字母letter_count个）
    var for_count    = 0;//循环计数
    
    var s_letter_str = '<ul id="list_letter">';
    $("#list_letter").remove();
    if(letter_count > 26)
    {
        for(var i = 0; i < 26; i++)
        {
            s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_arr[i]+'\');" hidefocus="true">'+letter_arr[i]+'</a></li>';
        }
    }
    else
    {
        for(var i = 0; i < circle_count; i++)
        {
            var letter_old  = "";//临时存储字母
            var letter_show = ""//显示字母
            if(for_count < (el_count*(letter_count-1)))
            {
                for(var j = 0; j < (letter_count-1); j++)
                {
                    letter_old += letter_arr[for_count];
                    for_count++;
                }
                if(letter_count > 3)
                {
                    letter_show = letter_old.substring(0,1) + letter_old.substring(letter_count-2);
                }
                else
                {
                    letter_show = letter_old;
                }
                s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_old+'\');" hidefocus="true">'+letter_show+'</a></li>';
            }
            else
            {
                for(var j = 0; j < letter_count; j++)
                {
                    letter_old += letter_arr[for_count];
                    for_count++;
                }
                
                if(letter_count > 2)
                {
                    letter_show = letter_old.substring(0,1) + letter_old.substring(letter_count-1);
                }
                else
                {
                    letter_show = letter_old;
                }
                s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_old+'\');" hidefocus="true">'+letter_show+'</a></li>';
            }
        }
    }
    s_letter_str += '</ul>';
    
    $("#list_letter").remove();
    $("#list").append(s_letter_str);
    
    /*第一次加载时定义联系人iframe高度*/
    $('#talklist').height($(window).height() - $('#lianxi').offset().top);
    
    $(window).resize(function(){
        var height = $(window).height() - $('#lianxi').offset().top - 132;
        if(height - my_group_count*47 > 0)
        {
            $('#my_group').height(my_group_count*47);
        }
        else
        {
            $('#my_group').height(height);
        }
        
        if(height - share_group_count*47 > 0)
        {
            $('#share').height(share_group_count*47);
        }
        else
        {
            $('#share').height(height);
        }
        
        if(height - work_group_count*47 > 0)
        {
            $('#work').height(work_group_count*47);
        }
        else
        {
            $('#work').height(height);
        }    
        /*字母列取高度*/
        var letter_arr   = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        var circle_count = Math.floor(($(window).height() - $('#list').offset().top) / 38)//圆的个数
        var letter_count = Math.ceil(26/circle_count);//每个圆最多放字母数
        var el_count     = (letter_count*circle_count)%26;//少字母的圆（放字母letter_count-1个）
        var all_count    = 26-el_count;//满字母的圆（放字母letter_count个）
        var for_count    = 0;//循环计数
        
        var s_letter_str = '<ul id="list_letter">';
        $("#list_letter").remove();
        if(letter_count > 26)
        {
            for(var i = 0; i < 26; i++)
            {
                s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_arr[i]+'\');" hidefocus="true">'+letter_arr[i]+'</a></li>';
            }
        }
        else
        {
            for(var i = 0; i < circle_count; i++)
            {
                var letter_old = "";//临时存储字母
                var letter_show = ""//显示字母
                if(for_count < (el_count*(letter_count-1)))
                {
                    for(var j = 0; j < (letter_count-1); j++)
                    {
                        letter_old += letter_arr[for_count];
                        for_count++;
                    }
                    if(letter_count > 3)
                    {
                        letter_show = letter_old.substring(0,1) + letter_old.substring(letter_count-2);
                    }
                    else
                    {
                        letter_show = letter_old;
                    }
                    s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_old+'\');" hidefocus="true">'+letter_show+'</a></li>';
                }
                else
                {
                    for(var j = 0; j < letter_count; j++)
                    {
                        letter_old += letter_arr[for_count];
                        for_count++;
                    }
                    if(letter_count > 2)
                    {
                        letter_show = letter_old.substring(0,1) + letter_old.substring(letter_count-1);
                    }
                    else
                    {
                        letter_show = letter_old;
                    }
                    s_letter_str += '<li><a href="#" name="list_hover" onClick="show_list(\''+letter_old+'\');" hidefocus="true">'+letter_show+'</a></li>';
                }
            }
        }
        s_letter_str += '</ul>';
        
        $("#list_letter").remove();
        $("#list").append(s_letter_str);
        
        /*字母选择样式效果*/
        $('[name=list_hover]').click(function(){
            $(this).css("background","url(<?=MYOA_JS_SERVER?>/static/modules/address/images/black-tras.png) no-repeat")
            $(this).parent().siblings().children().css("background","url(<?=MYOA_JS_SERVER?>/static/modules/address/images/white-tras.png) no-repeat")
        })
        
        /*定义联系人列的高度*/
        $('#talklist').height($(window).height() - $('#lianxi').offset().top);
        
        /*联系人详细信息高度*/
	    $("#show_add").contents().find("#right").height($(window).height());
    });
    
    $("#my_group_show").click(function(){
        $("#my_group").toggle();
        $("#share").hide();
        $("#work").hide();
    })
    $("#share_show").click(function(){
        $("#share").toggle();
        $("#work").hide();
        $("#my_group").hide();
    })
    $("#work_show").click(function(){
        $("#work").toggle();
        $("#my_group").hide();
        $("#share").hide();
    })
    $("#new_add2").click(function(){
        $('#iframe_new').attr("src","address/new.php");
    })
    $("#edit").click(function(){
        var show_add_id = $('#show_add_id').val();
        $('#iframe_edit').attr("src","address/edit.php?add_id="+show_add_id);
    })
    $("#group_manage2").click(function(){
        $('#iframe_group').attr("src","address/group_manage.php");
    })
    $("#import_info2").click(function(){
        $('#iframe_import').attr("src","address/import.php");
    })
    $("#export_info2").click(function(){
        var keyword = $("#search_list").val();
        if(keyword.indexOf("<?=_("搜索")?>")>-1)
        {
            keyword = "";
        }
        $('#iframe_export').attr("src","address/export.php?keyword="+keyword);
    })
    $(".nav ul li a").click(function(){
        $(this).css("background-color","rgb(238, 238, 238)")
        $(this).parent().siblings().children().css("background-color","#F8F8F8")
    })
    $("#search_list").keyup(function(){
        var keyword    = $("#search_list").val();
        var key1       = $("#key1").val();
        var key2       = $("#key2").val();
        var GROUP_ID   = $("#GROUP_ID").val();
        var flag       = $("#key3").val();
        var gettype    = $("#gettype").val();//点击我的分组与共享状态
        var power      = $("#power").val();  //我的分组有权限的ID
        var sharing    = $("#sharing").val();//共享的GROUP_ID
        var share_type = $("#share_type").val();//我的分组与共享的区别状态
        frames['talklist'].location="address/talklist.php?GROUP_ID="+GROUP_ID+"&keyword="+keyword+"&key1="+key1+"&key2="+key2+"&PUBLIC_FLAG="+flag+"&TYPE="+gettype+"&POWER="+power+"&SHARING="+sharing+"&SHARE_TYPE="+share_type;
    })
    $("#search_list").focus(function(){
        var keyword = $("#search_list").val();
        if(keyword.indexOf("<?=_("搜索")?>")>-1)
        {
            $("#search_list").val("");
        }
    })
    $("#search_list").blur(function(){
        var keyword = $("#search_list").val();
        if(keyword == "")
        {
            $("#search_list").val($("#search_box").val());
        }
    })
    $('[name=list_hover]').click(function(){
        $(this).css("background","url(<?=MYOA_JS_SERVER?>/static/modules/address/images/black-tras.png) no-repeat")
        $(this).parent().siblings().children().css("background","url(<?=MYOA_JS_SERVER?>/static/modules/address/images/white-tras.png) no-repeat")
    })
    //关闭分组管理进行操作
    $("#hide_group").click(function(){
        window.location.reload();
    })
})

/*各种层提交事件*/
function submit_new()
{
    document.getElementById('iframe_new').contentWindow.CheckForm();
    //frames["iframe_new"].document.getElementById("form1").submit();
}
function submit_edit()
{
    document.getElementById('iframe_edit').contentWindow.CheckForm();
}
function submit_group()
{
    document.getElementById('iframe_group').contentWindow.group_submit();
}
function submit_import()
{
    document.getElementById('iframe_import').contentWindow.CheckForm();
}
function submit_export()
{
    document.getElementById('iframe_export').contentWindow.export_submit();
}

/*字母定位函数*/
function show_list(key)
{
    var list_arr = key.split("");
    var top_height;
    for(var i = 0; i < list_arr.length; i++)
    {
        if($(window.frames["talklist"].document).find("#"+list_arr[i]+"").length > 0)
        {
            top_height = $(window.frames["talklist"].document).find("#"+list_arr[i]+"").offset().top;
            
            //$(window.frames["talklist"].document)find("#show_talklist").animate({scrollTop: top_height }, 500);
            $('html,body',window.frames["talklist"].document).animate({scrollTop: top_height }, 500);
            return;
        }
    }
}
function setdragcookie(splitterbarleft){
	 var today = new Date();
    var expires = new Date();
    expires.setTime(today.getTime() + 1000*60*60*24*365);
    document.cookie = "addrbarleft="+splitterbarleft+"; path=/general/address/; expires=" + expires.toGMTString();
}
function dragSplitter(){
	var splitterwidth=$("#splitter-bar-vertical").width();
    $( "#splitter-bar-vertical" ).draggable({
        containment: "#splitter-bar-scroll", 
        axis: "x",
        start: function(){
            $(".splitter-bar-scroll").addClass("splitter-bar-scroll-on");
            $(".splitter-overlay").show();
        },
        drag: function(){
            $(".splitter-bar-scroll").addClass("splitter-bar-scroll-on");
        },
        stop: function()
        {
            $(".splitter-overlay").hide();
            $(".splitter-bar-scroll").removeClass("splitter-bar-scroll-on");
            var verticalleft=$( "#splitter-bar-vertical" ).offset().left;
            $(".left").css("width",verticalleft-1);
            $(".center").css("left",verticalleft+splitterwidth);
            $("#splitter-bar").css("left",$("#splitter-bar-vertical").position().left);
            setdragcookie($("#splitter-bar-vertical").position().left);
        }
    });
}
function search_box(value)
{
	newStr=value.replace(/\([^\)]*\)/g,"");
	if(newStr.length>7)
	{
		newStr=newStr.substr(0,6)+"...";
	}
	document.getElementById("search_list").value="<?=_("搜索")?> "+newStr+" <?=_("联系人")?>";
	document.getElementById("search_box").value="<?=_("搜索")?> "+newStr+" <?=_("联系人")?>";
	
}
</script>
<style>
ul
{
    padding: 0;
    margin: 0;
}
input[type="button"]
{
    width: 88px;
}
</style>

<body marginwidth="0" marginheight="0" style="height: 100%;overflow:hidden;background:#f6f7f9;">
<?
$linkman_out = "";
$i_group_count = 0;
$query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or USER_ID='' order by USER_ID desc,ORDER_NO asc,GROUP_NAME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID        = $ROW["USER_ID"];
    $PRIV_USER      = $ROW["PRIV_USER"];
    $GROUP_ID       = $ROW["GROUP_ID"];
    $GROUP_NAME     = $ROW["GROUP_NAME"];
    $PRIV_DEPT      = $ROW["PRIV_DEPT"];
    $PRIV_ROLE      = $ROW["PRIV_ROLE"];
    
    if($USER_ID == "")
    {
        if($PRIV_DEPT != "ALL_DEPT")
        {
            if(!find_id($PRIV_DEPT, $_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE, $_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER, $_SESSION["LOGIN_USER_ID"]) && !check_id($PRIV_ROLE, $_SESSION["LOGIN_USER_PRIV_OTHER"], true) != "" && !check_id($PRIV_DEPT, $_SESSION["LOGIN_DEPT_ID_OTHER"], true) != "")
            {
                continue;
            }
        }
    }
    $s_group_name = "";
    $i_group_count++;
    //$s_group_name = (strlen($GROUP_NAME) >12) ? csubstr($GROUP_NAME,0,12)."..." : $GROUP_NAME._("组");
    
    if($USER_ID == "" and (find_id($PRIV_USER, $_SESSION["LOGIN_USER_ID"]) or $PRIV_DEPT == 'ALL_DEPT' or find_id($PRIV_DEPT, $_SESSION["LOGIN_DEPT_ID"]) or find_id($PRIV_ROLE, $_SESSION["LOGIN_USER_PRIV"]) or check_id($PRIV_ROLE, $_SESSION["LOGIN_USER_PRIV_OTHER"], true) != "" or check_id($PRIV_DEPT, $_SESSION["LOGIN_DEPT_ID_OTHER"], true) != ""))
    {
        $flag=1;
        $arr.=$GROUP_ID.",";
		
        $linkman_out .= '<li><a onclick="goto(0,'.$GROUP_ID.',1,0),search_box(this.innerText)" title="'.$GROUP_NAME._("组").'">'.$GROUP_NAME._("(公共)").'</a></li>';
    }
    else
    {
        $flag=0;
		$arr.=$GROUP_ID.",";
        $linkman_out .= '<li><a onclick="goto(0,'.$GROUP_ID.',0,0),search_box(this.innerText)" title="'.$GROUP_NAME._("组").'">'.$GROUP_NAME.'</a></li>';
    }
}

$linkman_share = "";
$query  = "select * from ADDRESS_GROUP where SHARE_GROUP_ID!='' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER_ID) order by USER_ID desc,ORDER_NO asc,GROUP_NAME asc";
$cursor = exequery(TD::conn(),$query);
$i_share_count = 0;
while($ROW=mysql_fetch_array($cursor))
{
    $i_share_count++;
    
    $USER_ID    = $ROW["USER_ID"];
    $PRIV_USER  = $ROW["PRIV_USER"];
    $GROUP_ID   = $ROW["GROUP_ID"];
    $GROUP_NAME = $ROW["GROUP_NAME"];
    $PRIV_DEPT  = $ROW["PRIV_DEPT"];
    $PRIV_ROLE  = $ROW["PRIV_ROLE"];
    
    $USER_NAME    = td_trim(GetUserNameById($USER_ID));
    $sharing[]    = $GROUP_ID;
    $s_share_name = "";
    //$s_share_name = (strlen($GROUP_NAME) > 6) ? csubstr($GROUP_NAME,0,6)."..." : $GROUP_NAME;
    
    $linkman_share .= '<li><a onclick="goto(1,'.$GROUP_ID.',0,0),search_box(this.innerText)" style="cursor:pointer;" title="'.$GROUP_NAME._("组").'">'.$GROUP_NAME.'['.$USER_NAME._("共享").']</a></li>';  
}
//OA自定义分组
$linkman_oa   = "";
$i_work_count = 1;
$query="SELECT * FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' ORDER BY ORDER_NO";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$i_work_count++;
	
	$GROUP_ID   = $ROW['GROUP_ID'];
	$GROUP_NAME = $ROW['GROUP_NAME'];
	
	//$s_share_name = (strlen($GROUP_NAME) > 20) ? csubstr($GROUP_NAME,0,20)."..." : $GROUP_NAME;
	$linkman_oa .= '<li><a onclick="goto(2,'.$GROUP_ID.',0,0),search_box(this.innerText)" title="'.$GROUP_NAME.'">'.$GROUP_NAME.'</a></li>';	
}
if($sharing)
{
	$sharing = implode(',',$sharing);
}
$i_group_count++;
$i_share_count++;
?>
<!--新建联系人-->
<div id="new_add" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -500px;width: 1000px;">
    <div class="modal-body" style="max-height: 400px;height: 400px;padding: 0px;overflow: hidden;">
        <iframe width="100%" height="100%" id="iframe_new" name="iframe_new" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_new()" style="margin-left:220px;"><?=_("保存")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_new"><?=_("关闭")?></button>
    </div>
</div>

<!--编辑联系人-->
<div id="edit_add" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -500px;width: 1000px;">
    <div class="modal-body" style="max-height: 400px;height: 400px;padding: 0px;overflow: hidden;">
        <iframe width="100%" height="100%" id="iframe_edit" name="iframe_edit" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_edit()" style="margin-left:220px;"><?=_("保存")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_edit"><?=_("关闭")?></button>
    </div>
</div>

<!--分组管理-->
<div id="group_manage" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -500px;width: 1000px;">
    <div class="modal-body" style="max-height: 400px;height: 400px;padding: 0px;overflow: hidden;">
        <iframe width="100%" height="100%" id="iframe_group" name="iframe_group" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_group()" style="margin-left:213px;"><?=_("保存")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_group"><?=_("关闭")?></button>
    </div>
</div>

<!--导入数据-->
<div id="import_info" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -211px;left: 50%; margin-left: -375px;width: 750px;">
    <div class="modal-body" style="max-height: 370px;height: 370px;padding: 0px;overflow: hidden;">
        <iframe width="100%" height="100%" id="iframe_import" name="iframe_import" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_import()"><?=_("导入")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_import"><?=_("关闭")?></button>
    </div>
</div>

<!--导出数据-->
<div id="export_info" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -226px;left: 50%; margin-left: -450px;width: 900px;">
    <div class="modal-body" style="max-height: 400px;height: 400px;padding: 0px;overflow: hidden;">
        <iframe width="900px" height="100%" id="iframe_export" name="iframe_export" frameborder="0" src="">
        </iframe>
    </div>
    <div class="modal-footer" style="padding-bottom: 10px;padding-top: 10px;text-align:center;">
        <button class="btn btn-primary" onClick="submit_export()" style="margin-left:190px;"><?=_("导出")?></button> <button class="btn" data-dismiss="modal" aria-hidden="true" id="hide_export"><?=_("关闭")?></button>
    </div>
</div>

<div class="content" style="min-width: 800px;height: 100%;width: 100%;">
	<div class="splitter-overlay"></div>
    <div class="splitter-bar-scroll" id="splitter-bar-scroll">
		<div class="splitter-bar-bgd"></div>
		<div class="splitter-bar-vertical" id="splitter-bar"></div>
		<div class="splitter-bar-vertical ui-draggable" id="splitter-bar-vertical"></div>
	</div>
    <div class="left">
        <div class="bts">
            <form id="form">
                <button href="#new_add" id="new_add2" type="button" role="button" style="width:80px;height:35px;" class="btn btn-success" data-toggle="modal"/><i class="icon-plus icon-white"></i><?=_("新建")?></button>
                <input name="" href="#edit_add" type="hidden" value='<?=_("编辑")?>' role="button" class="bt" id="edit" data-toggle="modal"/>
                <div class="btn-group">
                    <button hidefocus="true" type="submit" class="btn btn-info dropdown-toggle" style="width:80px;height:35px;" data-toggle="dropdown"/><?=_("更多")?><span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a id="group_manage2" href="#group_manage" role="button" data-toggle="modal"><?=_("分组管理")?></a>
                        </li>
                        <li><a id="import_info2" href="#import_info" role="button" data-toggle="modal"><?=_("导入数据")?></a>
                        </li>
                        <li><a id="export_info2" href="#export_info" role="button" data-toggle="modal"><?=_("导出数据")?></a>
                        </li>
                        <li><a id="search_info" onClick="window.open('./address/query/search.php');"><?=_("联系人查询")?></a>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="lianxi" id="lianxi">
            <ul>
                <li class="nav firstnav">
                    <span>
                        <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/group.png" width="22" height="22" />
                    </span>
                    <a id="my_group_show" href="javascript:;" onClick="goto(0,0,1,1),search_box(this.innerText)"><?=_("我的分组")?>(<?=$i_group_count?>)</a>
                    <ul id="my_group" style="height: <?=$height_my?>;">
                        <li class="all"><a href="#" onClick="goto(0,0,0,0),search_box(this.innerText)"><?=_("默认组")?></a></li>
                        <?=$linkman_out?>
                    </ul>
                </li>
                <li class="nav secondnav">
                    <span>
                        <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/share.png" width="22" height="22" />
                    </span>
                    <a id="share_show" href="javascript:;" onClick="goto(1,0,0,1),search_box(this.innerText)"><?=_("共享")?>(<?=$i_share_count?>)</a>
                    <ul id="share" style="height: <?=$height_share?>;">
                        <li class="all">
                            <a href="#" onClick="goto(1,0,0,0),search_box(this.innerText)"><?=_("默认组")?></a></li>
                        <?=$linkman_share?>
                    </ul>
                </li>
                <li class="nav thirdnav">
                    <span>
                        <img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/fellow.png" width="22" height="22" />
                    </span>
                    <a id="work_show" href="javascript:;" onClick="goto(2,0,0,2),search_box(this.innerText)"><?=_("我的同事")?>(<?=$i_work_count?>)</a>
                    <ul id="work" style="height: <?=$height_work?>;">
                        <li class="all" onClick="goto(2,0,0,2),search_box(this.innerText)"><a href="#"><?=_("全部")?></a></li>
                        <?=$linkman_oa?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="center">
    	<div class="middle">
	        <div class="input-append" style="margin-top:12px;margin-left:10px;">
	            <input name="" type="text" style="width:180px;" class="input-medium search-query span3" id="search_list" value='<?=_("搜索 我的分组 联系人")?>' />
	            <span class="add-on"><i class="icon-search icon-white"></i></span>
	        </div>
	        <div id="list">
	            <ul id="list_letter">
	            </ul>
	        </div>
	        <div id="name">
	            <input type="hidden" name="GROUP_ID" id="GROUP_ID" value="0">
	            <iframe width="100%" id="talklist" name="talklist" frameborder="0" src="address/talklist.php?TYPE=1&SHARE_TYPE=0&arr=<?=$arr?>&PUBLIC_FLAG=1">
	            </iframe>
	        </div>
	    </div>
        <input type="hidden" name="show_add_id" id="show_add_id" value="">
        <input type="hidden" name="key1" id="key1" value="">
        <input type="hidden" name="key2" id="key2" value="">
        <input type="hidden" name="key3" id="key3" value="<?=$flag?>">
        <input type="hidden" name="type" id="gettype" value="1">
        <input type="hidden" name="power" id="power" value="<?=$arr?>">
        <input type="hidden" name="sharing" id="sharing" value="<?=$sharing?>">
        <input type="hidden" name="share_type" id="share_type" value="0">
        <input type="hidden" name="my_group_count" id="my_group_count" value="<?=$i_group_count?>">
        <input type="hidden" name="share_group_count" id="share_group_count" value="<?=$i_share_count?>">
        <input type="hidden" name="work_group_count" id="work_group_count" value="<?=$i_work_count?>">
        <input type="hidden" name="search_box" id="search_box" value="<?=_("搜索 我的分组 联系人")?>">
	    <div class="right" >
	        <iframe width="100%" height="100%" id="show_add" name="show_add" frameborder="0" src="address/show_add.php?ADD_ID=2">
	        </iframe>
	    </div>
    </div>
</div>
<!--编辑返回时返回编辑ID-->
<script>
<?
if(isset($_GET['GETUPID'])) 
{
?>
   location="address/talkList.php?GETUPID=<?=$_GET['GETUPID']?>&PUBLIC_FLAG=1";
<?
}
?>
</script>
</body>
</html>
