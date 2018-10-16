<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("个人网址管理");
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
   { alert("<?=_("请选择RSS订阅类型")?>");
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
   return true;
}

function delete_url(URL_ID)
{
 msg='<?=_("确认要删除该项网址吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?URL_ID=" + URL_ID;
  window.location=URL;
 }
}

function delete_all()
{
 msg='<?=_("确认要删除所有网址吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
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

<body class="bodycolor" onload="document.form1.URL_NO.focus();">

<table class="table table-bordered" width="450">
<thead>
    <tr>
    <td class="Big" colspan="4"><span class=""> <?=_("添加个人网址")?></span>
    </td>
  </tr>
</thead>
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" style="width: 120px;"><i class="iconfont">&#xe617;</i><?=_("类型：")?></td>
    <td nowrap class="TableData">
      <select name="URL_TYPE" class="" onchange="change_type();" style="margin:0">
        <option value=""><?=_("普通网址")?></option>
        <option value="1"><?=_("RSS订阅")?></option>
      </select>
    </td>
   </tr>
   <tr id="SUB_TYPE_TR" style="display:none;">
    <td nowrap class="TableData"><?=_("RSS类别：")?></td>
    <td nowrap class="TableData">
      <select name="SUB_TYPE" class="">
        <?=code_list("RSS_TYPE",$SUB_TYPE);?>
      </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="" size="10" maxlength="25">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe642;</i><?=_("说明：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="" size="25" maxlength="200">
        <input type="button" id="btnGetTitle" class="btn" value="<?=_("根据RSS地址获取")?>" onclick="get_title();" style="display:<?if($URL_TYPE=="")echo "none";?>;">
        <br><span id="loading" style="display:none;" class="TextColor2"><img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在获取……")?></span>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe627;</i><?=_("网址：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="" size="50" maxlength="200" value="http://">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableData" colspan="2" align="center" style="text-align:center">
        <input type="submit" value="<?=_("添加")?>" class="btn btn-primary" title="<?=_("添加网址")?>" name="button">&nbsp;&nbsp;
    </td>
   </tr>
  </form>
</table>

<div style="width: 800px; margin-left:20px; height:25px; line-height:25px;">
   <span class="big3"> <?=_("管理个人网址")?></span>
</div>

<div >

<?
 //============================ 显示已定义URL =======================================
 $query = "SELECT * from URL where USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_TYPE!='2' order by URL_TYPE,URL_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 $URL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $URL_COUNT++;
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];
    $URL_TYPE=$ROW["URL_TYPE"];
    $SUB_TYPE=$ROW["SUB_TYPE"];
    $SUB_TYPE_DESC=$URL_TYPE=="1" ? get_code_name($SUB_TYPE,"RSS_TYPE") : "";
    if($URL_TYPE =="1")
       $URL_TYPE_DESC = _("RSS订阅");
    else if($URL_TYPE =="2")
       $URL_TYPE_DESC = _("收藏夹");
    else
       $URL_TYPE_DESC = _("普通网址");

    if($URL_COUNT==1)
    {
?>

    <table class="table table-bordered" width="90%">

    <thead style="background-color:#ebebeb;">
      <tr>
      <th nowrap style="text-align: center;"><?=_("序号")?></th>
      <th nowrap style="text-align: center;"><?=_("说明")?></th>
      <th nowrap style="text-align: center;"><?=_("网址")?></th>
      <th nowrap style="text-align: center;"><?=_("类别")?></th>
      <th nowrap style="text-align: center;"><?=_("子类别")?></th>
      <th nowrap style="text-align: center;"><?=_("操作")?></th>
    </tr>
    </thead>
<?
    }
?>
    <tr class="TableData addclass" align="center" >
      <td nowrap style="text-align: center;"><?=$URL_NO?></td>
      <td nowrap style="text-align: center;"><?=$URL_DESC?></td>
      <td><A href="<?=$URL?>" target="_blank"><?=$URL?></A></td>
      <td nowrap style="text-align: center;"><?=$URL_TYPE_DESC?></td>
      <td nowrap style="text-align: center;"><?=$SUB_TYPE_DESC?></td>
      <td nowrap style="text-align: center;" width="80">
      <a href="edit.php?URL_ID=<?=$URL_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("编辑")?></a>
      <a href="javascript:delete_url('<?=$URL_ID?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }

 if($URL_COUNT>0)
 {
?>
      <td nowrap align="center" colspan="6" style="text-align:center" class="TableData">
      <input type="button" class="btn btn-danger" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>">
      </td>
    </table>
<?
 }
 else
    Message("",_("尚未添加网址"));
?>

</div>

</body>
</html>