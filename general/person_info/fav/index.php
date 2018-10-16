<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("页面收藏夹管理");
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
   return true;
}

function delete_url(URL_ID)
{
 msg='<?=_("确认要删除该网址吗？")?>';
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
</script>

<body class="bodycolor" onLoad="document.form1.URL_NO.focus();">


<table class="table table-bordered" width="450">
    <thead>
      <tr>
    <td class="Big" colspan="2"><span class=""> <?=_("添加页面收藏")?></span>
    </td>
  </tr>
    </thead>
  <form action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120px"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_NO" class="" size="10" maxlength="25">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe61a;</i><?=_("名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL_DESC" class="" size="25" maxlength="200">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe627;</i><?=_("网址：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="URL" class="" size="50" maxlength="200" value="">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe645;</i><?=_("选项：")?></td>
    <td nowrap class="TableData">
        <label for="OPEN_WINDOW"><input type="checkbox" id="OPEN_WINDOW" name="OPEN_WINDOW"><?=_("在新窗口打开")?></label>
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
   <span class="big3"> 管理页面收藏夹</span>
</div>

<div>

<?
 //============================ 显示已定义URL =======================================
 $query = "SELECT * from URL where USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_TYPE='2' order by URL_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $URL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $URL_COUNT++;
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];
    
    $pos1 = strstr($URL, "workflow");     
    $pos2 = strstr($URL, "general");
	
    if($pos1==true && $pos2==false)
    {
        $URL = str_replace("workflow","/general/workflow",$URL);
    }
 
    $OPEN_WINDOW = substr($URL, 0, 2) == "1:" ? 1 : 0;
    if($OPEN_WINDOW)
       $URL = substr($URL, 2);

    if($URL_COUNT==1)
    {
?>

    <table class="table table-bordered" width="90%">

    <thead style="background-color:#ebebeb;">
      <tr>
        <th nowrap style="text-align: center;"><?=_("序号")?></th>
        <th nowrap style="text-align: center;"><?=_("说明")?></th>
        <th nowrap style="text-align: center;"><?=_("网址")?></th>
        <th nowrap style="text-align: center;"><?=_("新窗口打开")?></th>
        <th nowrap style="text-align: center;"><?=_("操作")?></th>
      </tr>
    </thead>
<?
    }
?>
    <tr class="TableData">
      <td nowrap style="text-align: center;"><?=$URL_NO?></td>
      <td nowrap style="text-align: center;"><?=$URL_DESC?></td>
      <td><A href="<?=$URL?>" target="_blank"><?=$URL?></A></td>
      <td nowrap style="text-align: center;"><?=$OPEN_WINDOW ? _("是") : _("否")?></td>
      <td nowrap style="text-align: center;width: 80px;">
      <a href="edit.php?URL_ID=<?=$URL_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("编辑")?></a>
      <a href="javascript:delete_url('<?=$URL_ID?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }

 if($URL_COUNT>0)
 {
?>
    <tr>
      <td nowrap colspan="6" style="text-align:center" class="TableData">
      <input type="button" class="btn btn-danger" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>">
      </td>
    </tr>
    </table>
<?
 }
 else
    Message("",_("尚未收藏项目"));
?>

</div>

</body>
</html>