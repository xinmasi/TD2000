<?
include_once("inc/auth.inc.php");
include_once("inc/td_core.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


$HTML_PAGE_TITLE = _("编辑快捷方式");
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
   { alert("<?=_("快捷方式路径不能为空！")?>");
     return (false);
   }
}

function set_path()
{
  document.form1.WIN_PATH.value=document.form1.WIN_PATH_FILE.value;
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


<?
 $query = "SELECT * from WINEXE where WIN_ID='$WIN_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
    $WIN_ID=$ROW["WIN_ID"];
    $WIN_NO=$ROW["WIN_NO"];
    $WIN_DESC=$ROW["WIN_DESC"];
    $WIN_PATH=$ROW["WIN_PATH"];
}
?>

<body class="bodycolor" onload="document.form1.WIN_NO.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑快捷方式")?></span>
    </td>
  </tr>
</table>

<table class="table table-bordered" width="600" align="center">
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="100"><i class="iconfont">&#xe644;</i><?=_("序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WIN_NO" class="BigInput" size="10" maxlength="25" value="<?=$WIN_NO?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe63e;</i><?=_("快捷方式名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="WIN_DESC" class="BigInput" size="25" value="<?=$WIN_DESC?>">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><i class="iconfont">&#xe604;</i><?=_("程序路径：")?></td>
    <td nowrap class="TableData">
      <select id="selType" style=" margin: 0; margin-bottom: 10px; "onchange=selectType(this.value)>
    		<option value='1'><?=_("手工输入路径")?></options>
    		<option value='2'><?=_("浏览选择路径")?></options>
    	</select><br>
    	  <input type="text" id="type1" name="WIN_PATH" value="<?=$WIN_PATH?>" class="BigInput" size="50">
        <input type="file" id="type2" name="WIN_PATH_FILE" style="display:none" value="<?=$WIN_PATH?>" class="BigInput" size="50">
        <br>
        <span><?=_("本机Windows程序路径，如")?></span><br>
        <span>C:\Program Files\Windows Media Player\wmplayer.exe</span><br><br>
        <span><?=_("也可以定义使用IE访问网址，如")?></span><br>
        <span>iexplore http://<?=TD_MYOA_WEB_SITE?></span>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableData" colspan="2" align="center" style="text-align:center">
        <input type="hidden" value="<?=$WIN_ID?>" name="WIN_ID">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn" onclick="location='index.php'">
    </td>
  </form>
</table>

</body>
</html>