<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("编辑网址");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/today.js"></script>
<script Language="JavaScript">
var $ = function(id) {return document.getElementById(id);};
function CheckForm()
{
   if(document.form1.URL_TYPE.value=="1" && document.form1.SUB_TYPE.value=="")
   { alert("<?=_("请选择RSS订阅类别！")?>");
     return (false);
   }
   if(document.form1.URL_DESC.value=="")
   { alert("<?=_("说明不能为空！")?>");
     return (false);
   }
   if(document.form1.URL.value=="")
   { alert("<?=_("网址不能为空！")?>");
     return (false);
   }
}
function change_type()
{
   if(document.form1.URL_TYPE.value=='1')
   {
   	$('EWP_TYPE_TR').style.display='none';
      $('RSS_TYPE_TR').style.display='';
      $('btnGetTitle').style.display='inline';
   }else if(document.form1.URL_TYPE.value=='3'){
   	$('EWP_TYPE_TR').style.display='';
   	$('RSS_TYPE_TR').style.display='none';
      $('btnGetTitle').style.display='none';
   }else
   {
   	$('EWP_TYPE_TR').style.display='none';
      $('RSS_TYPE_TR').style.display='none';
      $('btnGetTitle').style.display='none';
   }
}
function get_title()
{
   var theURL=document.form1.URL.value
   if(theURL=="")
   { alert("<?=_("请填写网址！")?>");
     return;
   }
   $("loading").style.display='';
   var req=getXMLHttpObj();
	req.open("GET","/inc/RSS/title.php?RSSURL="+encodeURIComponent(theURL),true);
	req.onreadystatechange=function()
	{
	   if(req.readyState==4)
	   {
         if(req.responseText.indexOf("<?=_("连接不到地址：")?>")>=0)
            $("loading").innerHTML=req.responseText;
         else
         {
            document.form1.URL_DESC.value=req.responseText;
            $("loading").style.display='none';
         }
      }
   };
   req.send(null);
}

function get_icon()
{
   var theURL=document.form1.EWP_ICONURL.value
   if(theURL=="")
   { 
   	alert("<?=_("请填写图标地址！")?>");
   	return;
   }
   if(CheckImgExists(theURL)){
   	$("ckIconMsg").innerHTML = '<span style="color:green"><?=_("文件存在")?></span>';
   	return;
  	}else{
  		$("ckIconMsg").innerHTML = '<span style="color:red"><?=_("文件不存在，请检查！")?></span>';
  		$("EWP_ICONURL").focus();
  		return;
  	}
}

/*function CheckImgExists(imgurl)
{
  var baseurl = "<?=MYOA_STATIC_SERVER?>/static/images/app_icons/";
  var ImgObj = null;
  ImgObj = new Image();
  ImgObj.src = baseurl + encodeURIComponent(imgurl);
  if(!0)
  {
    if (ImgObj.width > 0 && ImgObj.height > 0)
    {
      return true;
    }
  }else{
    if(ImgObj.fileUpdatedDate!=""){
      return true;
    }
  }
  return false;
}*/
//lp 检查图片是否存在
function CheckImgExists(imgurl) {
    try 
    {
        var xmlhttp;
        var baseurl = "<?=MYOA_STATIC_SERVER?>/static/images/app_icons/";
        var url = baseurl + encodeURIComponent(imgurl);
        if (window.XMLHttpRequest) 
        { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("get", url, false);
        xmlhttp.send();
        if (xmlhttp.status == "404") 
        {
            return false;
        }
        else
        {
            return true;
        }
    } catch (e) 
    {
        return false;
    }
}

//lp 返回时检查状态
function checkSelect()
{
	 if(document.form1.URL_TYPE.value=='3')
	 {
   	$('EWP_TYPE_TR').style.display='';
   	$('RSS_TYPE_TR').style.display='none';
      $('btnGetTitle').style.display='none';		
   }			
}
</script>


<?
 $query = "SELECT * from URL where USER='' and URL_ID='$URL_ID'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 if($ROW=mysql_fetch_array($cursor))
 {
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];
    $URL_ICON=$ROW["URL_ICON"];
    $URL_TYPE=$ROW["URL_TYPE"];
    $SUB_TYPE=$ROW["SUB_TYPE"];
}
?>

<body class="bodycolor" onload="document.form1.URL_NO.focus();checkSelect();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑网址")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="90%" align="center">
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("类型：")?></td>
    <td nowrap class="TableData">
      <select name="URL_TYPE" class="BigSelect" onchange="change_type();">
        <option value=""><?=_("普通网址")?></option>
        <option value="1"<?if($URL_TYPE==1)echo " selected";?>><?=_("RSS订阅")?></option>
        <option value="3"<?if($URL_TYPE==3)echo " selected";?>><?=_("互联网应用自定义")?></option>
      </select>
    </td>
   </tr>
   <tr id="RSS_TYPE_TR" style="display:<?=$URL_TYPE=="1" ? "" : "none";?>;">
    <td nowrap class="TableData">RSS<?=_("类别：")?></td>
    <td nowrap class="TableData">
      <select name="SUB_TYPE" class="BigSelect">
        <?=code_list("RSS_TYPE",$SUB_TYPE);?>
      </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="BigInput" size="10" maxlength="25" value="<?=$URL_NO?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("说明：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="BigInput" size="25" maxlength="200" value="<?=td_htmlspecialchars($URL_DESC)?>">
        <input type="button" id="btnGetTitle" class="SmallButton" value="<?=_("根据RSS地址获取")?>" onclick="get_title();" style="display:<?=$URL_TYPE=="1" ? "" : "none";?>;">
        <br><span id="loading" style="display:none;" class="TextColor2"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在获取……")?></span>
    </td>
   </tr>
   <tr id="EWP_TYPE_TR" style="display:<?=$URL_TYPE=="3" ? "" : "none";?>;">
    <td nowrap class="TableData"><?=_("应用图标文件名：")?></td>
    <td nowrap class="TableData">
      <input type="text" name="EWP_ICONURL" id="EWP_ICONURL" value="<?=$URL_ICON?>" class="BigInput" size="25" maxlength="200">
      <input type="button" id="btnGetIcon" class="SmallButton" value="<?=_("检查文件是否存在")?>" onclick="get_icon();"> <span id="ckIconMsg" style="font-weight:bold;"></span>
      <br /><?=_("请将对应的图标文件存放在服务器 OA所在目录\webroot\static\images\app_icons\ 目录下，如不填写则显示默认图片，图标尺寸64*64效果最佳")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("网址：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="BigInput" size="50" maxlength="200" value="<?=td_htmlspecialchars($URL)?>">
    </td>
   </tr>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="hidden" value="<?=$URL_ID?>" name="URL_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index.php'">
    </td>
  </form>
</table>

</body>
</html>