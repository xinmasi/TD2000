<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("导入固定资产");
include_once("inc/header.inc.php");
?>

<script>
function CheckForm()
{
   if(document.getElementById("upload_file").value == "")
   {
	    alert("<?=_("导入文件不能为空")?>");
		  return false;
	 }
	 else
   {
   	  document.form1.submit();
	 }
}	
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" align="absmiddle"><span class="big3"> <?=_("导入固定资产")?></span><br></td>
    </tr>
</table>
<br />
<br />
<form name="form1" method="post" action="import_new.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <table class="TableBlock" align="center" width="70%">
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>下载导入模板：</b></td>
            <td width="400" align="left">
                <a href="#" onClick="window.location='templet_export.php'"><?=_("固定资产管理导入模板下载")?></a>
            </td>
        </tr>
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>&nbsp;&nbsp;选择导入文件：</b></td>
            <td align="left" width="400">
                <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
            </td>
        </tr>
        <tr class="TableData" align="center" height="30">
            <td width="150" align="right"><b>说明：</b></td>
            <td width="400" align="left">
  	<span>
    1、导入文件请到参数设置进行导入设置，设置相应的导入字段匹配；    <br>
    2、模板中，保管人不能为空，为空则不能导入；    <br>
    3、日期的格式应如：2009-10-15。    </span>
            </td>
        </tr>
        <tr>
            <td nowrap  class="TableControl" colspan="3" align="center">
                <input type="submit" value="导入" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
