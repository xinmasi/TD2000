<?
include_once("inc/auth.inc.php");
include_once("general/crm/inc/header.php");
include_once("import_config.php");

$HTML_PAGE_TITLE = $title;
include_once("inc/header.inc.php");
?>
<link type="text/css" rel="stylesheet" media="screen" href="/general/CRM/inc/css/menu.css">
<script>
function upload(){
	if(document.getElementById("upload_file").value == ""){
		alert("<?=_("上传路径不能为空")?>");
		return false;
	}else{
		document.form1.submit();
	}
}
</script>

<body>
<br>
<form name='form1' method='post' enctype='multipart/form-data' action='header_auth.php'>	
<table cellspacing='0' cellpadding='' width="90%" align='center' border='0' style='margin-top:4px;margin-bottom: 4px;'>
<tr>
	<td colspan=4 class='blockHeader' colspan ='2'><?=_("上传")?><?=$title?></td>
</tr>
<tr>
	<td class='efCellCtrl' width='120' nowrap> <?=_("上传")?>EXCEL <font color="red">*</font></td>
	<td class='efCellCtrl' height = '35'>
		<input type='hidden' name='file_name' id = 'file_name' value=''>
		<input type='file' name='upload_file' id = 'upload_file' class='BigInput'>
		<input type='button' value='<?=_("上传")?>' class='BigButton' onClick="upload();">&nbsp;
	</td>
</tr>
</table>
</form>
</body>
</html>