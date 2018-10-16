<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("分组管理");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<script type="text/javascript">
function delete_group(group_id)
{
    msg='<?=_("确认要删除该分组吗？")?>\n<?=_("注意：该分组下的联系人将全部转入到默认分组中！")?>';
    if(window.confirm(msg))
    {
        URL="delete_group.php?GROUP_ID=" + group_id;
        window.location=URL;
    }
}
function show_group(group_id,group_type)
{
    if(group_type == '0')
    {
        document.getElementById('group_edit').src = "show_group.php?GROUP_ID="+group_id;
    }
    else
    {
        document.getElementById('group_edit').src = "show_public_group.php?GROUP_ID="+group_id;
    }
}
function group_new()
{
    document.getElementById('group_edit').src = "group_new.php";
}

function group_submit()
{
    document.getElementById('group_edit').contentWindow.CheckForm();
}

$(document).ready(function(){
    $(".imgx").mouseover(function(){
        $(this).attr("src","<?=MYOA_JS_SERVER?>/static/modules/address/images/x-hover-gray.png");
    });
    $(".imgx").mouseout(function(){
        $(this).attr("src","<?=MYOA_JS_SERVER?>/static/modules/address/images/x.png");
    });
    
    $(".fz").mouseover(function(){
        $(this).css("background-color","rgb(238, 238, 238)")
    });
    $(".fz").mouseout(function(){
        $(this).css("background-color","#f7f7f7")
    });
    
    jQuery(".leftul li").click(function(){
        jQuery(this).css("background-color","rgb(238, 238, 238)")
        jQuery(this).parent().siblings().children().css("background-color","#f7f7f7")
    })
});
</script>

<body>
<?
$s_group_str = "";
//============================ 管理分组 =======================================
$query = "SELECT * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO asc,GROUP_ID asc";
$cursor= exequery(TD::conn(),$query);
$i_group_count = 0;
while($ROW=mysql_fetch_array($cursor))
{
    $i_group_count++;
    
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    
    $s_group_name = "";
    $s_group_name = (strlen($GROUP_NAME) > 10) ? csubstr($GROUP_NAME,0,10)."..." : $GROUP_NAME._("组");
    
    $s_group_str .= '<li class="fz" style="position:relative" onClick="show_group('.$GROUP_ID.',0);" title="'.$GROUP_NAME._("组").'"><span style="margin-right:54px;">'.$s_group_name.'</span><span class="dl" style="position:absolute;right:20px;*+top:5px;" onClick="delete_group('.$GROUP_ID.');"><img class="imgx" src="'.MYOA_JS_SERVER.'/static/modules/address/images/x.png" /></span></li>';
}

//有维护该公共分组权限可操作(先获取当前用户部门ID)
$query = "SELECT DEPT_ID FROM user where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
if($row = mysql_fetch_array($cursor))
{
    $dept_id = $row[0];
}

$query = "SELECT * from ADDRESS_GROUP where USER_ID='' and (find_in_set('$dept_id',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER)) order by ORDER_NO asc,GROUP_ID asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $i_group_count++;
    
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    
    $s_group_name = "";
    $s_group_name = (strlen($GROUP_NAME) > 10) ? csubstr($GROUP_NAME,0,10)."..." : $GROUP_NAME._("组");
    
    $s_group_str .= '<li class="fz" onClick="show_group('.$GROUP_ID.',1);" title="'.$GROUP_NAME._("组").'"><span style="margin-right:54px">'.$s_group_name.' (公共)</span></li>';
}

$group_height = ($i_group_count > 5) ? "270px" : "auto";
?>

<div class="group" style="width: 1000px;height: 400px;">
    <div class="left" style="width:213px;">
        <dl>
            <dt class="left1"><span><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/group.png" /></span><?=_("我的分组")?></dt>
            <dd style="margin-left:0px;">
                <ul class="leftul" style="height:<?=$group_height?>;overflow-y:auto;overflow-x: hidden;position:relative;top:0px;left:0px;">
                    <li class="fz" onClick="show_group('0','0');" style="text-align: left;"><span class="left-span" style="top:0px;*+top:10px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/all.png" width="22" height="22"></span><span style="margin-left:61px;"><?=_("默认组")?></span></li>
                    <?=$s_group_str?>
                </ul>
            </dd>
            <button type="button" class="btn btn-success" style="width:120px;height:35px;margin-left:40px;margin-top: 10px;" onClick="group_new();"><?=_("新建分组")?></button>
        </dl>
    </div>
    
    <div class="right" style="width: 787px;height: 400px;overflow: hidden;">
        <iframe width="100%" height="400px" id="group_edit" name="group_edit" frameborder="0" src="show_group.php?GROUP_ID=0">
        </iframe>
    </div>
</div>
</body>
</html>