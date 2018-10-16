<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("编辑收藏");
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
   if(document.form1.URL_DESC.value=="")
   {
	   alert("<?=_("名称不能为空！")?>");
	   return (false);
   }
   if(document.form1.URL.value=="")
   {
	   alert("<?=_("网址不能为空！")?>");
	   return (false);
   }
   if(document.form1.URL_NO.value=="")
   {
	   alert("<?=_("序号不能为空！")?>");
	   return (false);
   }else
   {
	   if(!/^\+?[1-9][0-9]*$/.test(document.form1.URL_NO.value))
	   {
		   alert("<?=_("请输入正确的序号！")?>");
		   return (false);
	   }  
   }
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
    
    $OPEN_WINDOW = substr($URL, 0, 2) == "1:" ? 1 : 0;
    if($OPEN_WINDOW)
       $URL = substr($URL, 2);
}
?>

<body class="bodycolor" onLoad="document.form1.URL_NO.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑收藏")?></span>
    </td>
  </tr>
</table>

<table class="table table-bordered" width="450" align="center">
  <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="BigInput" size="10" maxlength="25" value="<?=$URL_NO?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe61a;</i><?=_("说明：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="BigInput" size="25" maxlength="200" value="<?=$URL_DESC?>">
        <input type="button" id="btnGetTitle" class="SmallButton" value="<?=_("根据RSS地址获取")?>" onClick="get_title();" style="display:<?if($URL_TYPE=="")echo "none";?>;">
        <br><span id="loading" style="display:none;" class="TextColor2"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在获取……")?></span>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe627;</i><?=_("网址：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="BigInput" size="50" maxlength="200" value="<?=$URL?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe645;</i><?=_("选项：")?></td>
    <td nowrap class="TableData">
        <label for="OPEN_WINDOW"><input type="checkbox" id="OPEN_WINDOW" name="OPEN_WINDOW"<?=$OPEN_WINDOW ? " checked" : ""?>><?=_("在新窗口打开")?></label>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableData" colspan="2" align="center" style="text-align:center">
        <input type="hidden" value="<?=$URL_ID?>" name="URL_ID">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn" onClick="location='index.php'">
    </td>
  </form>
</table>

</body>
</html>