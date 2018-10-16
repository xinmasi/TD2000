<?
if(file_exists('./index2.php'))
   header("location: index2.php");
   
$pagestarttime=microtime();

include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/td_core.php");
ob_end_clean();
//session_start();
//$_SESSION["LOGIN_THEME"] = 1;
$POSTFIX = _("，");

//------ 取得系统参数

$PARA_ARRAY=get_sys_para("DESKTOP_SELF_DEFINE,DESKTOP_LEFT_WIDTH,MYTABLE_BKGROUND");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;

$DESKTOP_POS  =find_id($DESKTOP_SELF_DEFINE,"POS");
$DESKTOP_WIDTH=find_id($DESKTOP_SELF_DEFINE,"WIDTH");
$DESKTOP_LINES=find_id($DESKTOP_SELF_DEFINE,"LINES");
$DESKTOP_SCROLL=find_id($DESKTOP_SELF_DEFINE,"SCROLL");
$DESKTOP_EXPAND=find_id($DESKTOP_SELF_DEFINE,"EXPAND");

//------ 左右两拦宽度
$COL_WIDTH_LEFT=$DESKTOP_WIDTH && intval($_COOKIE["my_width_".$_SESSION["LOGIN_UID"]])>0 ? intval($_COOKIE["my_width_".$_SESSION["LOGIN_UID"]]) : $DESKTOP_LEFT_WIDTH;

$DESKTOP_EXPAND_ALL=!$DESKTOP_POS || $DESKTOP_POS && $_COOKIE["my_expand_all_".$_SESSION["LOGIN_UID"]]!="0";
$CONTENT_LEFT_COOKIE = $_COOKIE["left_content"];

$HTML_PAGE_TITLE = _("信息中心");
include_once("inc/header.inc.php");
/*
if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/13/portal.css">
<?
}
*/
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"])? 13:$_SESSION["LOGIN_THEME"]?>/mytable.css?t=20150521" />
<script src="<?=MYOA_JS_SERVER?>/static/js/ispirit.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.cookie.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript">
window.setTimeout('this.location.reload();',1200000);
var my_pos=<?=$DESKTOP_POS ? "true":"false"?>;
var my_width=<?=$COL_WIDTH_LEFT?>;
var my_expand=<?=$DESKTOP_EXPAND_ALL ? "true":"false"?>;
function _edit(module_id)
{
	 show_msg("optionBlock");
    $("optionModuleTitle").innerText=$("module_"+module_id+"_text").innerText;
<?
if($DESKTOP_LINES)
{
?>
    $('display_lines').focus();
<?
}
?>
	 $('display_lines').value=lines_per_page(module_id);
	 $('scroll').checked=getCookie("my_scroll_<?=$_SESSION["LOGIN_UID"]?>_"+module_id)=="true";
	 $('col_width').value=my_width;
	 $('module_id').value=module_id;
}

function call_workflow_user_func(){
	window.location.reload();
}
function _resize(module_id)
{
	 var module_i=$("module_"+module_id);
	 var head_i=$("module_"+module_id+"_head");
	 var body_i=$("module_"+module_id+"_body");
	 var img_i=$("img_resize_"+module_id);
	 var my_cookie=getCookie("my_expand_<?=$_SESSION["LOGIN_UID"]?>");
	 my_cookie = (my_cookie==null || my_cookie=="undefined") ? "" : my_cookie;//alert(my_cookie)
	 if(body_i.style.display=="none")
	 {
	    module_i.className=module_i.className.substr(0,module_i.className.lastIndexOf(" "));
	    head_i.className=head_i.className.substr(0,head_i.className.lastIndexOf(" "));
	    body_i.style.display="block";
	    if(img_i.className.match("collapse_arrow"))
	    	img_i.className=img_i.className.replace("collapse_arrow","expand_arrow");
	    img_i.title="<?=_("折叠")?>";

	    if(my_cookie.indexOf(module_id+",") == 0)
	       my_cookie = my_cookie.replace(module_id+",", "");
	    else if(my_cookie.indexOf(","+module_id+",") > 0)
	       my_cookie = my_cookie.replace(","+module_id+",", ",");

	    //my_expand=true;
       setCookie("my_expand_all_<?=$_SESSION["LOGIN_UID"]?>", "");
	 }
	 else
	 {
	    module_i.className=module_i.className+" listColorCollapsed";
	    head_i.className=head_i.className+" moduleHeaderCollapsed";
	    body_i.style.display="none";
	    if(img_i.className.match("expand_arrow"))
	    	img_i.className=img_i.className.replace("expand_arrow","collapse_arrow");
	    img_i.title="<?=_("展开")?>";

	    if(my_cookie.indexOf(module_id+",") != 0 && my_cookie.indexOf(","+module_id+",") <= 0)
	       my_cookie += module_id+",";
	 }
	 setCookie("my_expand_<?=$_SESSION["LOGIN_UID"]?>", my_cookie);
}
function resize_all()
{
   var img_all_resize=$("img_all_resize");
   var imgs=document.getElementsByTagName("a");
   var module_id_str="";
   for(var i=0;i<imgs.length;i++)
   {
      if(imgs[i].id.substr(0,11)!="img_resize_")
         continue;

      var module_id=imgs[i].id.substr(11,imgs[i].id.length);
      module_id_str+=module_id+",";
      if(my_expand && $("module_"+module_id+"_body").style.display!="none" || !my_expand && $("module_"+module_id+"_body").style.display=="none")
         _resize(module_id);
   }

   if(my_expand)
   {
      img_all_resize.className= img_all_resize.className.replace("expand_arrow","collapse_arrow");
      setCookie("my_expand_<?=$_SESSION["LOGIN_UID"]?>",module_id_str);
   }
   else
   {
      img_all_resize.className= img_all_resize.className.replace("collapse_arrow","expand_arrow");
      setCookie("my_expand_<?=$_SESSION["LOGIN_UID"]?>","");
   }

   my_expand=!my_expand;
   setCookie("my_expand_all_<?=$_SESSION["LOGIN_UID"]?>", my_expand ? "" : "0");
}
function SetNums()
{
	 var today_lines=$('display_lines').value;
	 var col_width=$('col_width').value;
   if(today_lines=="" || checkNum(today_lines) || col_width=="" || checkNum(col_width))
   {
      alert("<?=_("显示条数和栏目宽度必须是数字")?>");
      return;
   }

   if(parseInt(today_lines)<=0 || parseInt(today_lines)>=1000)
   {
      alert("<?=_("显示条数必须在1-1000之间")?>");
      return;
   }
   if(parseInt(col_width)<=0 || parseInt(col_width)>100)
   {
      alert("<?=_("栏目宽度必须在1-100之间")?>");
      return;
   }
   setCookie("my_nums_<?=$_SESSION["LOGIN_UID"]?>_"+$('module_id').value, today_lines);
   setCookie("my_scroll_<?=$_SESSION["LOGIN_UID"]?>_"+$('module_id').value, $('scroll').checked);
   setCookie("my_width_<?=$_SESSION["LOGIN_UID"]?>", col_width);

   my_width=col_width;

   $("msgBody").style.display = "none";
   $("msgCommand").style.display = "none";
   $("msgSuccess").style.display = "block";

   window.location.reload();
}

function getId(f)
{
   if(!window.top.func_array)
   {
      if(f=="f17")	
   	     window.location="../../info/unit/" ;
   	  else if(f=="f18")
   	  	 window.location="../../info/dept/" ;
   	  else if(f=="f19")
   	  	 window.location="../../info/user/" ;
   }
   else
      window.top.openURL(f, window.top.func_array[f][0], window.top.func_array[f][1]);		 
}

function changeTab(num)
{
	var liAry=document.getElementById("arraw").getElementsByTagName("li");
	var divAry = new Array(document.getElementById("content-left1"),document.getElementById("content-left2"));
	var liLen=liAry.length;
    var liID= "arraw"+num;
    for(var i=0; i<liLen; i++){    	
    	var divID = "content"+num;
        if(liAry[i].id==liID)
        {
	        if (liAry[i].className.indexOf("active")<=0){
	        	liAry[i].className += " active"; 
	        }	
            divAry[i].style.display = 'block';
        }
        else
        {
        	liAry[i].className=liAry[i].className.replace(" active",""); 
            divAry[i].style.display = 'none';
        }
    }
}

function view_more(name,label,url)
{
   openURL(url, name, label);
}

function openURL(url, name, label)
{
   if(window.top.bTabStyle)
      window.top.openURL(name, label, url);
   else if(window.top != self && typeof(window.top.openURL) == 'function')
      window.top.openURL(url);
   else
      window.open(url,'name','height=600,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=0,left=(screen.availWidth-500)/2,resizable=yes');
}

function add_handle(MENU_FLAG,RUN_ID,PRCS_KEY_ID,FLOW_ID,PRCS_ID,FLOW_PRCS)
{
	var URL = "/general/workflow/list/input_form/?menu_flag="+MENU_FLAG+"&RUN_ID="+RUN_ID+"&PRCS_KEY_ID="+PRCS_KEY_ID+"&FLOW_ID="+FLOW_ID+"&PRCS_ID="+PRCS_ID+"&FLOW_PRCS="+FLOW_PRCS+"&GETDATA_SEARCH=2";
	width=(window.screen.availWidth-10);//设置打开的窗口的宽度
	height=(window.screen.availHeight-30);//设置打开的窗口的高度
	window.open (URL, '', 'height='+height+', width='+width+', top=0, left=0, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,location=no, status=no') ;
}
function resize_left(){
	var switch_btn = jQuery('#content-left-toggle');
	if(switch_btn.hasClass('content_left_show')){
		jQuery('#content-left-toggle').removeClass('content_left_show').addClass('content_left_hide');
		jQuery('#content-left-toggle').attr('title','隐藏理念和搜索');
		var content_state = 'content_left_hide';
	}else{	
		jQuery('#content-left-toggle').removeClass('content_left_hide').addClass('content_left_show');
		jQuery('#content-left-toggle').attr('title','显示理念和搜索');
		var content_state = 'content_left_show';
	}
	// setCookie("left_content",content_state);
	setCookie("left_content", content_state);

}
function showLeftContent(){
	jQuery('#content-left-toggle').removeClass('content_left_show').addClass('content_left_hide');
	jQuery('#content-left-toggle').attr('title','隐藏理念和搜索');
	jQuery('#content-left-wrap').show();
}
function hideLeftContent(){	
	jQuery('#content-left-toggle').removeClass('content_left_hide').addClass('content_left_show');
	jQuery('#content-left-toggle').attr('title','显示理念和搜索');
	jQuery('#content-left-wrap').hide();
}
jQuery(document).ready(function(){	
    jQuery('.inner12 img').removeAttr('width');
    jQuery('.inner12 img').removeAttr('height');
	jQuery('.inner12 img').removeAttr('style');
    jQuery(window).resize(function(){
        jQuery(colArray).find('.module, .module_right').each(function(){
            this.id && fixTitleWidth(this.id);
        });
        
    });
	if(jQuery.cookie('left_content')=="content_left_hide"){
		showLeftContent();
		
	}else{
		hideLeftContent();
		
	}

	jQuery('.viewmore').delegate('#content-left-close','click',function(){
		jQuery('.content-left-toggle').removeClass('content_left_show').addClass('content_left_hide');
		jQuery('#content-left-wrap').hide();
		resize_left();
	});
	jQuery('#desktop_config').delegate('#content-left-toggle','click',function(){
		jQuery('#content-left-wrap').toggle();
		resize_left();	
	});

}); 

</script>
<?
$login_user_info = GetUserInfoByUID($_SESSION["LOGIN_UID"], "MYTABLE_LEFT,MYTABLE_RIGHT,NOT_VIEW_TABLE,BKGROUND", true);
$USER_MODULE_LEFT = isset($login_user_info["MYTABLE_LEFT"]) ? $login_user_info["MYTABLE_LEFT"] : '';
$USER_MODULE_RIGHT = isset($login_user_info["MYTABLE_RIGHT"]) ? $login_user_info["MYTABLE_RIGHT"] : '';
$NOT_VIEW_TABLE = isset($login_user_info["NOT_VIEW_TABLE"]) ? $login_user_info["NOT_VIEW_TABLE"] : '';
if($MYTABLE_BKGROUND == "-1" || (isset($login_user_info["BKGROUND"]) && stristr($login_user_info["BKGROUND"], ":")))
{
    $BKGROUND = "";
}else
{
    if(isset($login_user_info["BKGROUND"]))
    {
        $BKGROUND = $login_user_info["BKGROUND"];
    }
    else
    {
        $BKGROUND = '';
    }
}

if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"])){//时尚主题
	$fation = 1;
	$button_prev = '<img src="'.MYOA_STATIC_SERVER.'/static/images/prev.png" />';
	$button_next = '<img src="'.MYOA_STATIC_SERVER.'/static/images/next.png" />';	
	$button_edit = '<img src="'.MYOA_STATIC_SERVER.'/static/images/pencil_fation.png" />';
	$button_close = '<img src="'.MYOA_STATIC_SERVER.'/static/images/close_x_fation.png" />';
    
	$button_prev = '<i class="module-icon-prev"></i>';
	$button_next = '<i class="module-icon-next"></i>';	
	$button_edit = '<i class="module-icon-edit"></i>';
	$button_close = '<i class="module-icon-close"></i>';
}
else{
	$button_prev = '<b>▲</b>';
	$button_next = '<b>▼</b>';
	$button_edit = '<img src="'.MYOA_STATIC_SERVER.'/static/images/pencil.png" />';
	$button_close = '<img src="'.MYOA_STATIC_SERVER.'/static/images/close_x.png" />';
}

if($BKGROUND!="")
{
   include_once("inc/utility_file.php");
   $ATTACH_URL = attach_url_old('background', $BKGROUND);
   $body_style = " style=\"background:url('".$ATTACH_URL['view']."') left top;background-attachment:fixed;\"";
}
?>
<body <?=$body_style ?>>
<? 
//$_COOKIE["LOGIN_FIRST"]="0";
//if (isset($_COOKIE["LOGIN_FIRST"]) && $_COOKIE["LOGIN_FIRST"]=="0")
//{
//    include_once ("setting_guide/setting_guide.php");
//}
if($NOT_VIEW_TABLE)
{
?>
   <br><br><br>
   <div align=center style="color:#000000;filter:dropshadow(color=#FFFFFF,offx=1,offy=1,positive=1); WIDTH: 100%; FONT-SIZE: 30pt;"><i><?=_("欢迎使用本系统")?></i></div>
<?
   exit;
}
if($FROM_ISPIRIT && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"])){
?>
<div class="ispirt_title"><?=_("企业门户")?></div>
<?
}

if($DESKTOP_EXPAND)
{
?>
<div id="desktop_config">
	
	<a id="img_all_resize" class="<?=$DESKTOP_EXPAND_ALL ? "expand_arrow":"collapse_arrow"?>" onClick="resize_all()" title="<?=_("全部展开/折叠")?>"></a>
	<a id="content-left-toggle" class="<?=$CONTENT_LEFT_COOKIE?>"  title=""> </a>
	
</div>
<?
}
else
{
?>
<div id="desktop_config" style="height:5px;"></div>
<?
}
if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
   if(MYOA_IS_UN == 1)
	   $MODULE_EXCLUDE = "url,address,user,shortcut,out_person,online,";
	else
	   $MODULE_EXCLUDE = _("常用网址,公共通讯簿查询,员工查询,菜单快捷组,外出人员公告牌,在线时长排行榜,");
}
$MYTABLE=array();
$MODULE_STR_HIDDEN=$MODULE_LEFT_STR=$MODULE_RIGHT_STR="";
$query = "SELECT * from MYTABLE order by MODULE_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MODULE_ID=$ROW["MODULE_ID"];
   $MODULE_POS=$ROW["MODULE_POS"];
   $MODULE_FILE=$ROW["MODULE_FILE"];
   $VIEW_TYPE=$ROW["VIEW_TYPE"];
   
   if(find_id($MODULE_EXCLUDE, substr($MODULE_FILE, 0, strpos($MODULE_FILE, "."))))
      continue;

   if($VIEW_TYPE=="2")
   {
      if(!find_id($USER_MODULE_LEFT,$MODULE_ID) && !find_id($USER_MODULE_RIGHT,$MODULE_ID))
      {
         if($MODULE_POS=="l" && $USER_MODULE_LEFT!="ALL")
            $USER_MODULE_LEFT.=$MODULE_ID.",";
         else if($MODULE_POS=="r" && $USER_MODULE_RIGHT!="ALL")
            $USER_MODULE_RIGHT.=$MODULE_ID.",";
      }
   }
   else if($VIEW_TYPE=="3")
   {
      $MODULE_STR_HIDDEN.=$MODULE_ID.",";
   }

   if($VIEW_TYPE!='3')
   {
      if($MODULE_POS=='l')
         $MODULE_LEFT_STR.=$MODULE_ID.",";
      else
         $MODULE_RIGHT_STR.=$MODULE_ID.",";
   }

   $MYTABLE[$MODULE_ID] = $ROW;
}

if($DESKTOP_POS && $USER_MODULE_LEFT!="ALL")
  $MODULE_LEFT_STR=$USER_MODULE_LEFT;
if($DESKTOP_POS && $USER_MODULE_RIGHT!="ALL")
  $MODULE_RIGHT_STR=$USER_MODULE_RIGHT;
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr id="content-wrap" >
<?
if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
 	<td width="255" valign="top" id="content-left-wrap">
		<div id='content-left'>
 		<ul id='arraw' class="clearfix">
			<li id='arraw1' class="blue active" onClick="changeTab(1)"><?=_("理念")?></li>
			<li id='arraw2' class="orange" onClick="changeTab(2)"><?=_("搜索")?></li>
		</ul>
		<?
		$query = "SELECT * from UNIT";
		$cursor= exequery(TD::conn(),$query);
 		if($ROW=mysql_fetch_array($cursor))
 		{
 			$CONTENT = $ROW["CONTENT"];
 		}
		?>
			<div id='content-left1' style="display:block">
				<div class="listColor module_nav clearfix">
					<div class='inner12'>
						<?=$CONTENT?>
					</div>
					<div class='viewmore' >
						<a href="javascript:openURL('/general/info/unit/', 'UnitInfo','<?=_("单位信息")?>');"><?=_("更多")?> </a>	
						<a href="#" id="content-left-close" onClick=""><?=_("关闭")?> </a>
											
					</div>
				</div>
			</div>
			<div id='content-left2' style="display:none">
				<div class="content-left2-inner">
				<?
				$MODULE_ID = 'address';
				$MODULE_FILE = './address.php';
				$MODULE_DESC = _("通讯簿查询");
				if(file_exists($MODULE_FILE))
				{
					$MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);
					
					$MODULE_RESIZE='';
					if($DESKTOP_EXPAND)
					   $MODULE_RESIZE='<a href="javascript:_resize(\''.$MODULE_ID.'\');" id="img_resize_'.$MODULE_ID.'" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'" title="'.($MODULE_EXPAND?_("折叠"):_("展开")).'"></a>';
					?>
  					<div id="module_<?=$MODULE_ID?>" class="module listColor module_nav<?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
    					<div class="head body">
      						<h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader<?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>" <?=$DESKTOP_EXPAND ? " onclick=\"_resize('".$MODULE_ID."');\"":""?> style="cursor:pointer;">
        					<?=$MODULE_RESIZE?>
        					<span id="module_<?=$MODULE_ID?>_text" class="text"><?=$MODULE_DESC?></span>
      						</h4>
    					</div>
    					<div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
							<?
								include($MODULE_FILE);
							?>
    					</div>
					</div>
					<?
				}
				$MODULE_ID = 'url';
				$MODULE_FILE = './url.php';
				$MODULE_DESC = _("常用网址");
				if(file_exists($MODULE_FILE))
				{
					$MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);
					
					$MODULE_RESIZE='';
					if($DESKTOP_EXPAND)
					   $MODULE_RESIZE='<a href="javascript:_resize(\''.$MODULE_ID.'\');" id="img_resize_'.$MODULE_ID.'" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'" title="'.($MODULE_EXPAND?"折叠":"展开").'"></a>';
					?>
					  <div id="module_<?=$MODULE_ID?>" class="module listColor module_nav<?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
					    <div class="head body">
					      <h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader<?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>" <?=$DESKTOP_EXPAND ? " onclick=\"_resize('".$MODULE_ID."');\"":""?> style="cursor:pointer;">
					        <?=$MODULE_RESIZE?>
					        <span id="module_<?=$MODULE_ID?>_text" class="text"><?=$MODULE_DESC?></span>
					      </h4>
					    </div>
					    <div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
					<?
					include($MODULE_FILE);
					?>
					    </div>
					  </div>
					<?
				}
				$MODULE_ID = 'out_person';
				$MODULE_FILE = './out_person.php';
				if(file_exists($MODULE_FILE))
				{
					$MODULE_DESC = _("外出人员");
					$MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);
					
					$MODULE_RESIZE='';
					if($DESKTOP_EXPAND)
					   $MODULE_RESIZE='<a href="javascript:_resize(\''.$MODULE_ID.'\');" id="img_resize_'.$MODULE_ID.'" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'" title="'.($MODULE_EXPAND?"折叠":"展开").'"></a>';
					?>
					  <div id="module_<?=$MODULE_ID?>" class="module listColor module_nav<?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
					    <div class="head body">
					      <h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader<?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>" <?=$DESKTOP_EXPAND ? " onclick=\"_resize('".$MODULE_ID."');\"":""?> style="cursor:pointer;">
					        <?=$MODULE_RESIZE?>
					        <span id="module_<?=$MODULE_ID?>_text" class="text"><?=$MODULE_DESC?></span>
					      </h4>
					    </div>
					    <div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
					<?
					include($MODULE_FILE);
					?>
					    </div>
					  </div>
					<?
				}
				$MODULE_ID = 'online';
				$MODULE_FILE = './online.php';
				if(file_exists($MODULE_FILE))
				{
					$MODULE_DESC = _("在线时长排行榜");
					$MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);
					
					$MODULE_RESIZE='';
					if($DESKTOP_EXPAND)
					   $MODULE_RESIZE='<a href="javascript:_resize(\''.$MODULE_ID.'\');" id="img_resize_'.$MODULE_ID.'"  title="'.($MODULE_EXPAND?_("折叠"):_("展开")).'" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'"></a>';
					?>
					  <div id="module_<?=$MODULE_ID?>" class="module listColor module_nav<?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
					    <div class="head body">
					      <h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader<?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>" <?=$DESKTOP_EXPAND ? " onclick=\"_resize('".$MODULE_ID."');\"":""?> style="cursor:pointer;">
					        <?=$MODULE_RESIZE?>
					        <span id="module_<?=$MODULE_ID?>_text" class="text"><?=$MODULE_DESC?></span>
					      </h4>
					    </div>
					    <div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
					<?
					include($MODULE_FILE);
					?>
					    </div>
					  </div>
					<?
				}
				?>
				<div class="listColor module_nav module_nav_links">
					<div class="head body">
						<h4 class="moduleHeader">
							<a class="nav" href="#" onClick="window.open('../../info/unit/');"><?=_("单位信息")?></a>
						</h4>
					</div>
					<div class="head body">
						<h4 class="moduleHeader">
							<a class="nav"  href="#" onClick="window.open('../../info/dept/');"><?=_("部门信息")?></a>
						</h4>
					</div>
					<div class="head body">
						<h4 class="moduleHeader">
							<a class="nav"  href="#" onClick="window.open('../../info/user/');"><?=_("用户查询")?></a>
						</h4>
					</div>
				</div>
			</div>
		</div>
		</div>
	</td>
<?
}
?>
  <td valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-layout-fixed">
 <tr>
  <td id="col_l" width="<?=$COL_WIDTH_LEFT?>%" valign="top">
<?
 //-------------------------------------- 左半部分 -----------------------------------
 $MODULE_ARRAY=explode(",",$MODULE_LEFT_STR);
 $MODULE_ARRAY_COUNT=sizeof($MODULE_ARRAY);
 for($MODULE_I=0;$MODULE_I<$MODULE_ARRAY_COUNT;$MODULE_I++)
 {

    $MODULE_ID=$MODULE_ARRAY[$MODULE_I];
    $VIEW_TYPE=$MYTABLE[$MODULE_ID]["VIEW_TYPE"];
    $MODULE_NAME_SER = unserialize($MYTABLE[$MODULE_ID]["MODULE_NAME"]);
    $MODULE_NAME=$MODULE_NAME_SER["zh-CN"];
    $MODULE_FILE=$MYTABLE[$MODULE_ID]["MODULE_FILE"];
    $MODULE_BORDER_COLOR = $MYTABLE[$MODULE_ID]["MODULE_BORDER_COLOR"];
    
    if(find_id($MODULE_STR_HIDDEN,$MODULE_ID) || $MODULE_FILE=="" || !file_exists(iconv2os("../$MODULE_FILE")))
       continue;

    $SHOW_COUNT=$DESKTOP_LINES && $_COOKIE["my_nums_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID] ? intval($_COOKIE["my_nums_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]) : intval($MYTABLE[$MODULE_ID]["MODULE_LINES"]);
    if($SHOW_COUNT<=0 || $SHOW_COUNT>100)
       $SHOW_COUNT=5;
    $MAX_COUNT=$SHOW_COUNT*3;

    $MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);

    $MODULE_SCROLL=$DESKTOP_SCROLL && ($_COOKIE["my_scroll_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]=="true" || $_COOKIE["my_scroll_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]=="" && $MYTABLE[$MODULE_ID]["MODULE_SCROLL"]=="1") ? "true" : "false";

    $MODULE_TYPE = "";
    $MODULE_HEAD_CLASS = '';
    
    include(iconv2os("../$MODULE_FILE"));
    $MODULE_TYPE = trim($MODULE_TYPE, " | ");

    $module_type_tabs_styles = $MODULE_TYPE != '' ? ' has_module_type_tabs ' : ' no_module_type_tabs ';
    
    if($MODULE_FUNC_ID!="" && !find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID) && $MODULE_FUNC_ID!='106')
       continue;

    $color_style = '';
    if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
    {
       $MODULE_HEAD_CLASS = !$MODULE_HEAD_CLASS && !$MODULE_BORDER_COLOR ? 'default' : $MODULE_HEAD_CLASS;
     	 $color_style = "win8_module_".$MODULE_HEAD_CLASS." color_style_".$MODULE_BORDER_COLOR;
    }
    $scroll_style = '';
    if($MODULE_SCROLL=="true" && $MODULE_ID!="61")
    {
       $MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$MODULE_BODY.'</marquee>';
       $scroll_style = ' is_marquee ';
    }
    
    $MODULE_RESIZE='';
    if($DESKTOP_EXPAND)
       $MODULE_RESIZE='<a href="javascript:_resize('.$MODULE_ID.');" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'" id="img_resize_'.$MODULE_ID.'" title="'.($MODULE_EXPAND?_("折叠"):_("展开")).'"></a>';
    if($DESKTOP_WIDTH || $DESKTOP_LINES || $DESKTOP_SCROLL)
       $MODULE_OP.='<a href="javascript:_edit('.$MODULE_ID.');" title="'._("设置").'">'.$button_edit.'</a>';
    if($VIEW_TYPE!="2")
       $MODULE_OP.='<a href="javascript:_del('.$MODULE_ID.');" title="'._("关闭模块").'">'.$button_close.'</a>';
?>
<div id="module_<?=$MODULE_ID?>" class="<?=$color_style.$scroll_style.$module_type_tabs_styles?> module listColor<?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
  <div class="head">
   <h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader<?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>">
      <?=$MODULE_RESIZE?>
      <?
      	$MODULE_DESC = $MODULE_NAME=="" ? $MODULE_DESC:$MODULE_NAME;
      ?>
      <span id="module_<?=$MODULE_ID?>_text" class="text"<?=$DESKTOP_EXPAND ? " onclick=\"_resize(".$MODULE_ID.");\"":""?>><?=$MODULE_DESC?></span>
      <span id="module_<?=$MODULE_ID?>_title" class="title"<?=$DESKTOP_EXPAND ? " onclick=\"_resize(".$MODULE_ID.");\"":""?> <?=$DESKTOP_POS ? " style=\"cursor:move;\"":""?>></span>
      <span id="module_<?=$MODULE_ID?>_op" class="close">
        <a href="javascript:NextPage('<?=$MODULE_ID?>',-1);" id="module_<?=$MODULE_ID?>_link_pre"  title="<?=_("上一页")?>" class="page-link-prev PageLinkDisable"><?=$button_prev?></a>
        <a href="javascript:NextPage('<?=$MODULE_ID?>',1);"  id="module_<?=$MODULE_ID?>_link_next" title="<?=_("下一页")?>" class="page-link-next"><?=$button_next?></a>
        <?=$MODULE_OP?>
        </span>
    </h4>
  </div>
  <div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
<?
if($MODULE_TYPE != "")
{
?>
    <div id="module_<?=$MODULE_ID?>_type" class="moduleType">
      <div id="module_<?=$MODULE_ID?>_type_link" class="moduleTypeLink"><?=$MODULE_TYPE?></div>
      <div id="module_<?=$MODULE_ID?>_type_op" class="moduleTypeOp"><a href="javascript:ScrollType('<?=$MODULE_ID?>');" title="<?=_("更多类型")?>"><?=$button_next?></a></div>
    </div>
<?
}
?>
    <div id="module_<?=$MODULE_ID?>_ul" class="module_div" style="height:<?=$SHOW_COUNT*20?>px;"><?=$MODULE_BODY?></div>
    <?=$MORE_URL?>
   
  </div>
</div>
<?
 }
?>
<div class="shadow"></div>
  </td>
  <td id="col_r" valign="top">
<?
 //-------------------------------------- 右半部分 ----------------------------------
 $MODULE_ARRAY=explode(",",$MODULE_RIGHT_STR);
 $MODULE_ARRAY_COUNT=sizeof($MODULE_ARRAY);
 for($MODULE_I=0;$MODULE_I<$MODULE_ARRAY_COUNT;$MODULE_I++)
 {
    $MODULE_ID=$MODULE_ARRAY[$MODULE_I];
    $VIEW_TYPE=$MYTABLE[$MODULE_ID]["VIEW_TYPE"];
    $MODULE_NAME_SER = unserialize($MYTABLE[$MODULE_ID]["MODULE_NAME"]);
    $MODULE_NAME=$MODULE_NAME_SER["zh-CN"];
    $MODULE_FILE=$MYTABLE[$MODULE_ID]["MODULE_FILE"];
    $MODULE_BORDER_COLOR = $MYTABLE[$MODULE_ID]["MODULE_BORDER_COLOR"];
    
    if(find_id($MODULE_STR_HIDDEN,$MODULE_ID) || find_id($MODULE_LEFT_STR,$MODULE_ID) || $MODULE_FILE=="" || !file_exists(iconv2os("../$MODULE_FILE")))
       continue;

    $SHOW_COUNT=$DESKTOP_LINES && $_COOKIE["my_nums_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID] ? intval($_COOKIE["my_nums_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]) : intval($MYTABLE[$MODULE_ID]["MODULE_LINES"]);
    if($SHOW_COUNT<=0 || $SHOW_COUNT>100)
       $SHOW_COUNT=5;
    $MAX_COUNT=$SHOW_COUNT*3;

    $MODULE_EXPAND=!$DESKTOP_EXPAND || $DESKTOP_EXPAND && !find_id($_COOKIE["my_expand_".$_SESSION["LOGIN_UID"]], $MODULE_ID);

    $MODULE_SCROLL=$DESKTOP_SCROLL && ($_COOKIE["my_scroll_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]=="true" || $_COOKIE["my_scroll_".$_SESSION["LOGIN_UID"]."_".$MODULE_ID]=="" && $MYTABLE[$MODULE_ID]["MODULE_SCROLL"]=="1") ? "true" : "false";

    $MODULE_TYPE = "";
    $MODULE_HEAD_CLASS = '';
    include(iconv2os("../$MODULE_FILE"));
    
    $module_type_tabs_styles = $MODULE_TYPE != '' ? ' has_module_type_tabs ' : ' no_module_type_tabs ';
    
    if($MODULE_FUNC_ID!="" && !find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID) && $MODULE_FUNC_ID!='106')
       continue;

    $color_style = '';
    if(find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
    {
       $MODULE_HEAD_CLASS = !$MODULE_HEAD_CLASS && !$MODULE_BORDER_COLOR ? 'default' : $MODULE_HEAD_CLASS;
     	 $color_style = "win8_module_".$MODULE_HEAD_CLASS." color_style_".$MODULE_BORDER_COLOR;
    }
    $scroll_style = '';
    if($MODULE_SCROLL=="true" && $MODULE_ID!="61")
    {
       $MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$MODULE_BODY.'</marquee>';
       $scroll_style = ' is_marquee ';
    }

    $MODULE_RESIZE='';
    if($DESKTOP_EXPAND)
       $MODULE_RESIZE='<a href="javascript:_resize('.$MODULE_ID.');" id="img_resize_'.$MODULE_ID.'" class="expand '.($MODULE_EXPAND?"expand_arrow":"collapse_arrow").'" title="'.($MODULE_EXPAND?_("折叠"):_("展开")).'"></a>';
    if($DESKTOP_WIDTH || $DESKTOP_LINES || $DESKTOP_SCROLL)
       $MODULE_OP.='<a href="javascript:_edit('.$MODULE_ID.');" title="'._("设置").'">'.$button_edit.'</a>';
    if($VIEW_TYPE!="2")
       $MODULE_OP.='<a href="javascript:_del('.$MODULE_ID.');" title="'._("关闭模块").'">'.$button_close.'</a>';
?>
<div id="module_<?=$MODULE_ID?>" class="<?=$color_style.$scroll_style.$module_type_tabs_styles?> module_right listColor <?=$MODULE_EXPAND?"":" listColorCollapsed"?>">
  <div class="head">
   <h4 id="module_<?=$MODULE_ID?>_head" class="moduleHeader <?=$MODULE_EXPAND?"":" moduleHeaderCollapsed"?>">
      <?=$MODULE_RESIZE?>      
      <?
      	$MODULE_DESC = $MODULE_NAME=="" ? $MODULE_DESC:$MODULE_NAME;
      ?>
      <span id="module_<?=$MODULE_ID?>_text" class="text"<?=$DESKTOP_EXPAND ? " onclick=\"_resize(".$MODULE_ID.");\"":""?>><?=$MODULE_DESC?></span>
      <span id="module_<?=$MODULE_ID?>_title" class="title"<?=$DESKTOP_EXPAND ? " onclick=\"_resize(".$MODULE_ID.");\"":""?><?=$DESKTOP_POS ? " style=\"cursor:move;\"":""?>></span>
      <span id="module_<?=$MODULE_ID?>_op" class="close">
        <a href="javascript:NextPage('<?=$MODULE_ID?>',-1);" id="module_<?=$MODULE_ID?>_link_pre"  title="<?=_("上一页")?>" class="page-link-prev PageLinkDisable"><?=$button_prev?></a>
        <a href="javascript:NextPage('<?=$MODULE_ID?>',1);"  id="module_<?=$MODULE_ID?>_link_next" title="<?=_("下一页")?>" class="page-link-next"><?=$button_next?></a>
        <?=$MODULE_OP?>
        </span>
    </h4>
  </div>
  <div id="module_<?=$MODULE_ID?>_body" class="module_body" style="<?=$MODULE_EXPAND?"":"display:none;"?>">
<?
if($MODULE_TYPE != "")
{
?>
    <div id="module_<?=$MODULE_ID?>_type" class="moduleType">
      <div id="module_<?=$MODULE_ID?>_type_link" class="moduleTypeLink"><?=$MODULE_TYPE?></div>
      <div id="module_<?=$MODULE_ID?>_type_op" class="moduleTypeOp"><a href="javascript:ScrollType('<?=$MODULE_ID?>');" title="<?=_("更多类型")?>"><?=$button_next?></a></div>
    </div>
<?
}
?>

<?
   //2012/5/16 14:44:26 lp 笔记模块单独增加滚动条 
   $MODULE_DIV_SRCOLL = $MODULE_ID == 23 ? "overflow-y:auto;" : "";
?>
    <div id="module_<?=$MODULE_ID?>_ul" class="module_div" style="height:<?=$SHOW_COUNT*20?>px;<?=$MODULE_DIV_SRCOLL?>"><?=$MODULE_BODY?></div>
  </div>
</div>
<?
 }
 ?>
<div class="shadow"></div>
  </td>
 </tr>
</table>
  </td>
 </tr>
</table>

<div id="overlay"></div>
<div id="optionBlock" class="dialogBlock module" style="display: none;width:367px;height:247px">
  <h4 class="modulemsgHeader">
  	<a class="option" href="javascript:hide_msg('optionBlock');"></a>
  	<span id="optionModuleTitle" class="title"></span>
  </h4>
  <div id="msgBody" class="msgBody">
  	<h4 class="msgHeader"><span class="title"><?=_("模块选项")?></span></h4>
    <div class="msgdiv" style="display:<?=$DESKTOP_LINES ? "":"none"?>">&nbsp;&nbsp;<?=_("显示的行数：")?>
    	<input type="text" id="display_lines" class="SmallInput" size="3" value="5"/>
    </div>
    <div class="msgdiv" style="display:<?=$DESKTOP_SCROLL ? "":"none"?>">&nbsp;&nbsp;<label for="scroll"><?=_("列表上下滚动显示")?></label>
    	<input type="checkbox" id="scroll" />
    </div>
  	<h4 class="msgHeader"><span class="title"><?=_("全局选项")?></span></h4>
    <div class="msgdiv" style="display:<?=$DESKTOP_WIDTH ? "":"none"?>">&nbsp;&nbsp;<?=_("左侧栏目宽度：")?><input type="text" id="col_width" class="SmallInput" size="3" value="65"/>%</div>
  </div>
  <div id="msgCommand" class="moduleFooter">
    <input type="hidden" value="" id="module_id"/>
    <input class="msgButton" onClick="SetNums()" type="button" value="<?=_("确定")?>"/>&nbsp;&nbsp;
    <input class="msgButton" onClick="hide_msg('optionBlock')" type="button" value="<?=_("取消")?>"/>
  </div>
  <div id="msgSuccess" style="display:none;" class="moduleFooter">
     <h2><?=_("保存成功！")?></h2><input type="button" class="msgButton" onClick="hide_msg('optionBlock')" value="<?=_("关闭")?>"/>
  </div>
</div>
<?
if($SHOW_NOTIFY)
{
   $SYS_INTERFACE = TD::get_cache("SYS_INTERFACE");
   $IE_TITLE=$SYS_INTERFACE["IE_TITLE"];
   $NOTIFY_TEXT=$SYS_INTERFACE["NOTIFY_TEXT"];
?>
<div id="notifyBlock" class="dialogBlock module" style="display: none;width:600px;height:370px;">
  <h4 class="moduleHeader"><span class="title"><?=$IE_TITLE?></span><a class="option" href="javascript:hide_msg('notifyBlock');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></h4>
  <div class="module_body" style="height:280px;">
     <?=$NOTIFY_TEXT?>
  </div>
  <div class="moduleFooter">
    <input class="BigButtonB" onClick="setCookie('mytable_notify_<?=$_SESSION["LOGIN_UID"]?>','<?=dechex(crc32($NOTIFY_TEXT))?>', {path:'/'});hide_msg('notifyBlock');" type="button" value="<?=_("我知道了")?>"/>&nbsp;&nbsp;
    <input class="BigButtonB" onClick="hide_msg('notifyBlock');" type="button" value="<?=_("关闭")?>"/>
  </div>
</div>
<?
}

if($_SESSION["LOGIN_USER_PRIV"]=="1")
{
      $pageendtime=microtime();
      $starttime=explode(" ",$pagestarttime);
      $endtime=explode(" ",$pageendtime);

      $beforetime=abs($endtime[1]-$starttime[1]+$endtime[0]-$starttime[0]);

$MSG = sprintf(_("管理员提示：本页面执行时间%s秒"),$beforetime);
?>
<div id="tip" class="tip">
<span style="float:left;"><?=$MSG?></span>
<span style="float:right;padding:3px 3px;">
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/close_x.png" onClick="$('tip').style.display='none';" style="cursor:pointer;">
	&nbsp;
	</span>
</div>
<?
}
?>
<script type="text/javascript">
<!--
var interval = window.setInterval(function(){
   if(document.body.scrollWidth > 0)
   {
      window.clearInterval(interval);
      _upc(2);
<?
if($SHOW_NOTIFY)
{
?>
      show_msg('notifyBlock');
<?
}
?>
   }
}, 1000);
//-->
</script>
</body>
</html>