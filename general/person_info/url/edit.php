<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("编辑网址");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
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
      $('SUB_TYPE_TR').style.display='';
      $('btnGetTitle').style.display='inline';
   }
   else
   {
      $('SUB_TYPE_TR').style.display='none';
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
</script>


<?
 $query = "SELECT * from URL where USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_ID='$URL_ID'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 if($ROW=mysql_fetch_array($cursor))
 {
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];
    $URL_TYPE=$ROW["URL_TYPE"];
    $SUB_TYPE=$ROW["SUB_TYPE"];
}
?>

<body class="bodycolor" onload="document.form1.URL_NO.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑网址")?></span>
    </td>
  </tr>
</table>

<table class="table table-bordered" width="450" align="center">
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe617;</i><?=_("类型：")?></td>
    <td nowrap class="TableData">
      <select name="URL_TYPE" class="" onchange="change_type();" style="margin:0">
        <option value=""<?if($URL_TYPE=="")echo " selected";?>><?=_("普通网址")?></option>
        <option value="1"<?if($URL_TYPE=="1")echo " selected";?>><?=_("RSS订阅")?></option>
        <option value="2"<?if($URL_TYPE=="2")echo " selected";?>><?=_("收藏夹")?></option>
      </select>
    </td>
   </tr>
   <tr id="SUB_TYPE_TR" style="display:<?if($URL_TYPE!="1")echo "none";?>;">
    <td nowrap class="TableData"><?=_("RSS类别：")?></td>
    <td nowrap class="TableData">
      <select name="SUB_TYPE" class="BigSelect">
        <?=code_list("RSS_TYPE",$SUB_TYPE);?>
      </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="" size="10" maxlength="25" value="<?=$URL_NO?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe642;</i><?=_("说明：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="" size="25" maxlength="200" value="<?=$URL_DESC?>">
        <input type="button" id="btnGetTitle" class="SmallButton" value="<?=_("根据RSS地址获取")?>" onclick="get_title();" style="display:<?if($URL_TYPE=="")echo "none";?>;">
        <br><span id="loading" style="display:none;" class="TextColor2"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在获取……")?></span>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe627;</i><?=_("网址：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="" size="50" maxlength="200" value="<?=$URL?>">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableData" colspan="2" align="center" style="text-align:center">
        <input type="hidden" value="<?=$URL_ID?>" name="URL_ID">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn" onclick="location='index.php'">
    </td>
  </form>
</table>

</body>
</html>