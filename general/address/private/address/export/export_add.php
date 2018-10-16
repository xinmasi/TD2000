<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");
include_once("show_add.php");

$HTML_PAGE_TITLE = _("导出指定联系人");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link href="<?=MYOA_JS_SERVER?>/static/modules/address/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/inc/js/jquery/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/address/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
    $("#rightli").css("background-color","#59a0e2")
    $("#rightli").siblings().css("background-color","#f0f0f0")
        
    $(".TabbedPanelsTab a").click(function(){
        $(this).parent().css("background-color","#59a0e2")
        $(this).parent().siblings().css("background-color","#f0f0f0")
        $(this).css("color","#fff")
        $(this).parent().siblings().children().css("color","#000")
    })
    $(".f_group ul li a").click(function(){
        $(this).parent().css("background-color","#59a0e2")
        $(this).parent().siblings().css("background-color","#f0f0f0")
        $(this).css("color","#fff")
        $(this).parent().siblings().children().css("color","#000")
    })
    $("#rightli a").click(function(){
        $("#c1").show();
        $("#c2").hide();
    })
    $("#rightli1 a").click(function(){
        $("#c1").hide();
        $("#c2").show();
    })
    $(".lxr ul li a").mouseover(function(){
        $(this).parent().css("background-color","#ececec")
    })
	$(".lxr ul li a").mouseout(function(){
        $(this).parent().css("background-color","#f7f7f7")
    })
    
    $("#clickright").click(function(){
        var show_id,hide_id;
        var left_count = $("#group_show_count").val();
        var my_count = $("#my_count_all").val();
        var share_count = $("#share_count_all").val();
        var share_type = $("#share_type").val();
        
        if(share_type == '0')
        {
            if(my_count - left_count < 5) return;
        }
        else
        {
            if(share_count - left_count < 5) return;
        }
        show_id = parseInt(left_count) + 6;
        hide_id = parseInt(left_count) + 2;
        
        $("li:eq("+show_id+")").show();
        $("li:eq("+hide_id+")").hide();
        $("#group_show_count").val(parseInt(left_count)+1);
        
        $("li:eq("+show_id+")").css("background-color","#59a0e2")
        $("li:eq("+show_id+")").siblings().css("background-color","#f0f0f0")
        $("li:eq("+show_id+")").css("color","#fff")
        $("li:eq("+show_id+")").siblings().children().css("color","#000")
        $("li:eq("+show_id+")").click();
        
    });
    $("#clickleft").click(function(){
        var show_id,hide_id;
        var left_count = $("#group_show_count").val();
        var my_count = $("#my_count_all").val();
        var share_count = $("#share_count_all").val();
        var share_type = $("#share_type").val();
        
        if(share_type == '0')
        {
            if(left_count < 1) return;
        }
        else
        {
            if(left_count < 1) return;
        }
        show_id = parseInt(left_count) + 1;
        hide_id = parseInt(left_count) + 5;
        
        $("li:eq("+show_id+")").show();
        $("li:eq("+hide_id+")").hide();
        $("#group_show_count").val(parseInt(left_count)-1);
        
        $("li:eq("+show_id+")").css("background-color","#59a0e2")
        $("li:eq("+show_id+")").siblings().css("background-color","#f0f0f0")
        $("li:eq("+show_id+")").css("color","#fff")
        $("li:eq("+show_id+")").siblings().children().css("color","#000")
        $("li:eq("+show_id+")").click();
    });
})

function check_one(el)
{
    var show_id_str = $("#show_add_id_str").val();
    
    _get('export_add_show.php?show_id_str='+show_id_str+'&el='+el, '', function(req){
        var data_text = req.responseText;
        var strs= new Array();
        strs=data_text.split("*");
        $("#show_add_id_str").val(strs[0]);
        $('[id=select_add]').html(strs[1]);
    });
}

function change_group(share_type,group_id)
{
    var show_add_str = $("#show_add_id_str").val();
    _get('change_group.php?share_type='+share_type+'&group_id='+group_id+'&show_add_str='+show_add_str, '', function(req){
        if(share_type == '0')
        {
            $("#my_add").html(req.responseText);
        }
        else
        {
            $("#share_add").html(req.responseText);
        }
        $("#share_type").val(share_type);
        if(group_id == '0')
        {
            $("#group_show_count").val('0');
        }
    });
}

function CheckForm()
{
    if(document.form1.show_add_id_str.value=="")
    {
        alert("<?=_("请选择需要导出的联系人！！！")?>");
        return (false);
    }
	document.form1.submit();
}
</script>
</head>

<body>
<?
include_once('inc/name.php');

$TIME=date("Y-m-d H:i:s",time());

$s_my_str = "";//个人分组显示串
$s_share_str = "";//共享分组显示串
$i_my_count = 0;//个人分组个数
$i_share_count = 0;//共享分组个数
//============================ 个人和共享分组 =======================================
$query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or (SHARE_GROUP_ID!='' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER_ID)) order by GROUP_ID";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $GROUP_ID       = $ROW["GROUP_ID"];
    $GROUP_NAME     = $ROW["GROUP_NAME"];
    $USER_ID        = $ROW["USER_ID"];
    
    if($USER_ID != $_SESSION["LOGIN_USER_ID"])
    {
        $s_share_str .= '<li onclick="change_group(1,'.$GROUP_ID.');"><a href="#">'.$GROUP_NAME._("组").'</a></li>';
        $i_share_count++;
    }
    else
    {
        $s_my_str .= '<li onclick="change_group(0,'.$GROUP_ID.');"><a href="#">'.$GROUP_NAME._("组").'</a></li>';
        $i_my_count++;
    }
}

$a_show_list = array();
for($i=ord('A'); $i<=ord('Z'); ++$i)
{
    $name[chr($i)] = array();
    $nidx[chr($i)] = array();
    
    $a_show_list[chr($i)] = array();
}
$a_show_list["#"] = array();
$name['#']=array();
$nidx['#']=array();

$query_add = "select * from address where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and GROUP_ID='0'";
$cursor_add = exequery(TD::conn(), $query_add);
while($add_row = mysql_fetch_array($cursor_add))
{
    $s_url_pic = "";
    $s_short_name = "";
    
    $ADD_ID             = $add_row["ADD_ID"];
    $USER_ID            = $add_row["USER_ID"];
    $PSN_NAME           = $add_row["PSN_NAME"];
    $SEX                = $add_row["SEX"];
    $ATTACHMENT_ID      = $add_row["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $add_row["ATTACHMENT_NAME"];
    
    $s_short_name = (strlen($PSN_NAME) > 8) ? csubstr($PSN_NAME,0,8).".." : $PSN_NAME;
    
    if($ATTACHMENT_NAME=="" && $SEX==0)
    {
        $s_url_pic = MYOA_JS_SERVER."/static/modules/address/images/man_s.png";
    }
    else if($ATTACHMENT_NAME=="" && $SEX==1)
    {
        $s_url_pic = MYOA_JS_SERVER."/static/modules/address/images/w_s.png";
    }
    else
    {
        $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
        $s_url_pic = $URL_ARRAY["view"];
    }
    
    $s = $PSN_NAME;
    $idx = '#';
    if(ord($s[0]) >= 128)
    {
        $FirstName=substr($s, 0, MYOA_MB_CHAR_LEN);
        foreach($mb as $key => $s)
        {
            if(strpos($s, $FirstName))
            {
                $idx=strtoupper($key);
                break;
            }
        }
    }
    else
    {
        $FirstName=strtoupper($s[0]);
        if($FirstName>='A' && $FirstName<='Z')
        {
            $idx=$FirstName;
        }
        else
        {
            $idx='#';
        }
    }
    
    if(count($name[$idx]) == 0)
    {
        $a_show_list[$idx]['add_str'] .= '<div class="lxr" style="height: auto;"><p class="zimu" style="margin-bottom:0px">'.$idx.'</p><ul class="namelist"><li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;" title="'.$PSN_NAME.'"><input type="checkbox" style="position:absolute;top:0px;left:20px;" value="'.$ADD_ID.'" onclick="check_one(this.value)"/>'.$s_short_name.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
    }
    else
    {
        $a_show_list[$idx]['add_str'] .= '<li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;" title="'.$PSN_NAME.'"><input type="checkbox" style="position:absolute;top:0px;left:20px;" value="'.$ADD_ID.'" onclick="check_one(this.value)"/>'.$s_short_name.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
    }
    
    if(!in_array($FirstName, $nidx[$idx]))
    {
        array_push($nidx[$idx], $FirstName);
    }
    
    array_push($name[$idx], $row);
}

$a_add_str = "";
foreach($a_show_list as $key => $str)
{
    if($str['add_str'] != "")
    {
        $a_add_str .= $str['add_str']. '</ul></div>';
    }
}
?>

<div class="dc" style="width: 100%;">
    <div class="dc_r">
        <form style="margin:0px;" action="export_add_info.php" name="form1">
            <div id="radio_n">
                <label class="radio" style="width:105px; position:absolute; top:100px;left:10px;font-size:14px;"><input type="radio" name="daoc" checked="checked"/><?=_("导出为")?>foxmail</label>
                <label class="radio" style="width:105px; position:absolute; top:130px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>outlook</label>
                <!--
                <label class="radio" style="width:100px; position:absolute; top:160px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>csv</label>
                <label class="radio" style="width:100px; position:absolute; top:190px;left:10px;font-size:14px;"><input type="radio" name="daoc"/><?=_("导出为")?>vcard</label>
                -->
            </div>
            
            <div id="rightfz1">
                <div>
                    <div id="TabbedPanels1" class="TabbedPanels">
                        <ul class="TabbedPanelsTabGroup">
                            <li class="TabbedPanelsTab" id="rightli" style="top: 0px;margin: 0px;padding: 0px;width: 220px;"><a href="#" onClick="change_group(0,0);"><?=_("我的分组")?></a></li>
                            <li class="TabbedPanelsTab" id="rightli1" style="top: 0px;margin: 0px;padding: 0px;width: 220px;"><a href="#" onClick="change_group(1,0);"><?=_("共享组")?></a></li>
                        </ul>
                        <div class="TabbedPanelsContentGroup">
                            <div class="TabbedPanelsContent" id="c1">
                                <div class="f_group">
                                    <span class="leftbt" id="clickleft"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/leftbt.jpg" /></span>
                                    <ul>
                                        <li onClick="change_group(0,0);"><a href="#"><?=_("默认组")?></a></li>
                                        <?=$s_my_str?>
                                    </ul>
                                    <span class="rightbt" id="clickright"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/righttn.jpg" /></span>
                                </div>
                                <div class="zimulist">
                                    <div class="zimulist_l" style="width:100%;overflow-y: auto;" id="my_add" name="my_add">
                                        <?=$a_add_str?>
                                    </div>
                                </div>
                                <div class="select" name="select_add" id="select_add">
                                </div>
                            </div>
                            <div class="TabbedPanelsContent" id="c2">
                            <div class="f_group">
                                <span class="leftbt"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/leftbt.jpg" /></span>
                                <ul>
                                    <li onClick="change_group(1,0);"><a href="#"><?=_("默认组")?></a></li>
                                    <?=$s_share_str?>
                                </ul>
                                <span class="rightbt"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/righttn.jpg" /></span>
                            </div>
                            <div class="zimulist">
                                <div class="zimulist_l" id="share_add" name="share_add" style="width:440px;">
                                </div>
                            </div>
                            <div class="select" name="select_add" id="select_add">
                                <!--显示选择结果-->
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="group_show_count" id="group_show_count" value="0">
            <input type="hidden" name="share_type" id="share_type" value="0">
            <input type="hidden" name="my_count_all" id="my_count_all" value="<?=$i_my_count?>">
            <input type="hidden" name="share_count_all" id="share_count_all" value="<?=$i_share_count?>">
            <input type="hidden" name="show_add_id_str" id="show_add_id_str" value="">
        </form>
    </div>
</div>

<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
