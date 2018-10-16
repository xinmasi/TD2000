<?
include_once("inc/auth.inc.php");
include_once("inc/td_core.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("快捷组管理");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.WIN_DESC.value=="")
   { alert("<?=_("快捷方式名称不能为空！")?>");
     return (false);
   }
   if(document.form1.WIN_PATH.value=="")
   { alert("<?=_("程序路径不能为空！")?>");
     return (false);
   }
}

function set_path()
{
  document.form1.WIN_PATH.value=document.form1.WIN_PATH_FILE.value;
}

function delete_WIN(WIN_ID)
{
 msg='<?=_("确认要删除该项快捷方式吗？")?>';
 if(window.confirm(msg))
 {
  WIN="delete.php?WIN_ID=" + WIN_ID;
  window.location=WIN;
 }
}


function delete_all()
{
 msg='<?=_("确认要删除所有快捷方式吗？")?>';
 if(window.confirm(msg))
 {
  WIN="delete_all.php";
  window.location=WIN;
 }
}

function selectType(type)
{
	if(type==1)
	{
		document.form1.type1.style.display="";
		document.form1.type2.style.display="none";
	}
	else
  {
	  document.form1.type2.style.display="";
		document.form1.type1.style.display="none";
	}
}
</script>

<body class="bodycolor" onload="document.form1.WIN_NO.focus();">



<table class="table table-bordered">
    <thead>
      <tr>
        <td colspan="2" class="Big"><span class=""> <?=_("添加")?>Windows<?=_("快捷方式")?></span>
        </td>
      </tr>
    </thead>
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="100"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WIN_NO" class="" size="10" maxlength="25">
    </td>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe63e;</i><?=_("快捷方式名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WIN_DESC" class="" size="25">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe604;</i><?=_("程序路径：")?></td>
    <td nowrap class="TableData">
    	<select id="selType" style="margin: 0px;margin-bottom: 5px;" onchange=selectType(this.value)>
    		<option value='1'><?=_("手工输入路径")?></options>
    		<option value='2'><?=_("浏览选择路径")?></options>
    	</select>
    	<br>
    	  <input type="text" id="type1" name="WIN_PATH" class="" size="50">
        <input type="file" id="type2" style="display:none" name="WIN_PATH_FILE" class="BigInput" size="50" onchange="set_path()"><br>
        <span><?=_("本机Windows程序路径，如 C:\Program Files\Windows Media Player\wmplayer.exe，")?></span><br>
        <span> <?=_("也可以定义使用IE访问网址，如 iexplore http://").TD_MYOA_WEB_SITE?></span>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" style="text-align:center" colspan="2" align="center">
        <input type="submit" value="<?=_("添加")?>" class="btn btn-primary" title="<?=_("添加快捷方式")?>" name="button">
    </td>
  </form>
</table>

<div style="width: 800px; margin-left:20px; height:25px; line-height:25px;">
   <span class="big3"> <?=_("管理快捷方式")?></span>
</div>

<div>
<?
 $query = "SELECT * from WINEXE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by WIN_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

 $WIN_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $WIN_COUNT++;
    $WIN_ID=$ROW["WIN_ID"];
    $WIN_NO=$ROW["WIN_NO"];
    $WIN_DESC=$ROW["WIN_DESC"];
    $WIN_PATH=$ROW["WIN_PATH"];

    if($WIN_COUNT==1)
    {
?>

    <table class="table table-bordered" width="600">

    <thead style="background-color:#ebebeb;">
      <tr>
        <th nowrap style="text-align: center;"><?=_("序号")?></th>
        <th nowrap style="text-align: center;"><?=_("快捷方式名称")?></th>
        <th nowrap style="text-align: center;"><?=_("程序路径")?></th>
        <th nowrap style="text-align: center;"><?=_("操作")?></th>
      <tr>
    </thead>
<?
    }
?>
    <tr class="TableData">
      <td nowrap style="text-align: center;"><?=$WIN_NO?></td>
      <td nowrap style="text-align: center;"><?=$WIN_DESC?></td>
      <td><?=$WIN_PATH?></td>
      <td nowrap style="text-align: center; width: 80px;">
      <a href="edit.php?WIN_ID=<?=$WIN_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("编辑")?></a>
      <a href="javascript:delete_WIN('<?=$WIN_ID?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }

 if($WIN_COUNT>0)
 {
?>
    <tr class="TableData">
      <td nowrap align="center" colspan="5" style="text-align:center">
      <input type="button" class="btn btn-danger" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>">
      </td>
    </tr>
    </table>
<?
 }
 else
    Message("",_("尚未添加Windows快捷方式"));
?>

</div>

</body>
</html>