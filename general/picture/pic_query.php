<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("文件检索");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="js/pic_control.js"></script>


<body class="bodycolor" onload="document.form1.file_name.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
    	<a href="javascript:hide_tree();" id="tree_img"></a>
    	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle">&nbsp;<b><span class="Big1"><?=$LOCATION?>-<?=_("文件检索")?></span></b><br>
    </td>
  </tr>
</table>

<script>

//树结构图片初始化
if(parent.test.cols=="200,*")
{
	var temp = document.getElementById("tree_img")
	temp.className='scroll-left-active';
	temp.title="<?=_("隐藏目录树")?>";
}
else
{
	var temp = document.getElementById("tree_img")
	temp.className='scroll-right-active';
	temp.title="<?=_("显示目录树")?>";
}
</script>

<br>

<table class="TableBlock" width="450" align="center">
<form action="pic_search.php" name="form1">
  <tr class="TableData">
      <td nowrap align="center"><?=_("文件名包含文字：")?></td>
      <td nowrap><input type="text" name="file_name" class="BigInput" size="20"></td>
  </tr>
  <tr class="TableData">
      <td nowrap align="center"><?=_("文件类型：")?></td>
      <td nowrap><select name="file_type">
      	<option value=""><?=_("请选择图片类型")?></option>
      	<option value="gif">*.gif</option>
      	<option value="jpg">*.jpg</option>
      	<option value="png">*.png</option>
      	<option value="swf">*.swf</option>
      	<option value="swc">*.swc</option>
      	<option value="tiff">*.tiff</option>
      	<option value="iff">*.iff</option>
      	<option value="jp2">*.jp2</option>
      	<option value="jpx">*.jpx</option>
      	<option value="jb2">*.jb2</option>
      	<option value="jpc">*.jpc</option>
      	<option value="xbm">*.xbm</option>
      	<option value="wbmp">*.wbmp</option>
      	<option value="">*.*</option>
      </select>
      </td>
  </tr>
  <tr >
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>"><!--文件夹ID-->
          <input type="hidden" name="PIC_DIR" value="<?=$PIC_DIR?>"><!--文件夹完整路径-->
          <input type="hidden" name="SUB_DIR" value="<?=$SUB_DIR?>"><!--文件所在的文件夹目录-->
          <input type="hidden" name="PIC_PATH" value="<?=$PIC_PATH?>">
          <input type="hidden" name="LOCATION" value="<?=$LOCATION?>">
          <input type="hidden" name="DLL_PRIV" value="<?=$DLL_PRIV?>">
          <input type="hidden" name="ORDER_BY" value="">
          <input type="hidden" name="ASC_DESC" value="">
          <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行文件查询")?>">&nbsp;&nbsp;
          <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='picture.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&CUR_DIR=<?=$CUR_DIR?>'">
      </td>
  </tr>
</table>
</form>
</body>
</html>