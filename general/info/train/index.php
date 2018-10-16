<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("列车时刻查询");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function CheckForm1()
{
   if(document.form1.START.value=="" || document.form1.END.value=="")
   { alert("<?=_("模糊查询中起始站与终到站均不能为空！可以输入不完整名称")?>");
     return (false);
   }
   return (true);
}

function CheckForm2()
{
   if(document.form2.TRAIN.value=="")
   { alert("<?=_("精确查询中车次不能为空！")?>");
     return (false);
   }
   return (true);
}
</script>


<body class="bodycolor">
<?
if(!mysql_select_db("TRAIN", TD::conn()))
{
   Message(_("提示"),_("请到网站下载中心下载 基础可选组件包，用于安装列车时刻数据库"));
   exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("列车时刻模糊查询")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
 <form action="search.php"  name="form1" onsubmit="return CheckForm1();">
    	<?=_("起始站：")?><input type="text" name="START" size="10" maxlength="50" class="BigInput" value="<?=$START_NAME?>" title="<?=_("起始站与终到站可以输入不完整名称")?>">&nbsp;&nbsp;
        <?=_("终到站：")?><input type="text" name="END" size="10" maxlength="50" class="BigInput" value="<?=$END_NAME?>" title="<?=_("起始站与终到站可以输入不完整名称")?>">
      	<input type="submit" value="<?=_("查询")?>" class="BigButton" name="button" title="<?=_("开始查询")?>">
 </form>
 </span>
</div>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("列车时刻精确查询")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<span class="big3">
 <form action="search.php" name="form2" onsubmit="return CheckForm2();">
	<?=_("车次：")?><input type="text" name="TRAIN" size="10" maxlength="50" class="Biginput" value="<?=$TRAIN?>">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" name="button">
 </form>
 </span>
</div>

<br>
<?
Message("",_("数据仅供参考"));
?>
</body>
</html>
