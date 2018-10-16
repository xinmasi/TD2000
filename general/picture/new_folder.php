<?
include_once("inc/auth.inc.php");
//读取新建图片目录路径及名称
$query = "SELECT PIC_NAME,PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PIC_NAME=$ROW["PIC_NAME"];
   $PIC_PATH=$ROW["PIC_PATH"];
}
else
   exit;
   
//当前目录路径
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$CUR_DIR_RELAT;
else
   $CUR_DIR = $PIC_PATH."/".$CUR_DIR_RELAT;

if(stristr($CUR_DIR,".."))
{
    Message(_("错误"),_("参数含有非法字符。"));
    exit;
}   

$HTML_PAGE_TITLE = _("新建文件夹");
include_once("inc/header.inc.php");
?>




<body class="bodycolor" onload="form1.FILE_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big3"><img src="<?=MYOA_STATIC_SERVER?>/static/images/newfolder.gif" align="absmiddle"> <b><?=_("新建文件夹")?></b></td>
  </tr>
</table>
<br>
<table class="TableBlock" width="90%" align="center">
  <form action="add_folder.php"  method="post" name="form1">
    <tr class="TableData">
      <td width="60"><?=_("文件夹名：")?></td>
      <td><input type="text" class="BigInput" size="20" name="FILE_NAME" value=""></td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="5" nowrap>
        <input type="hidden" name="CUR_DIR_RELAT" value="<?=urlencode($CUR_DIR_RELAT)?>">
        <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">
      </td>
    </tr>
  </table>
</form>
</body>
</html>