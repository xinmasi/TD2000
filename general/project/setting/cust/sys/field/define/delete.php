<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除自定义字段");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

	//删除自定义字段关联的数据
	if($CODE_ID == "G") {
		$query="delete from PROJ_FIELD_DATE where TYPE_CODE_NO like '".$CODE_ID."%' AND FIELDNO='$FIELDNO'";
	}else{
		$query="delete from PROJ_FIELD_DATE where TYPE_CODE_NO like '$CODE_ID' AND FIELDNO='$FIELDNO'";
	}
	$rs_d = exequery(TD::conn(),$query);

	//删除自定义字段关联的数据
	$query="delete from PROJ_FIELDSETTING where TYPE_CODE_NO = '$CODE_ID' AND FIELDNO='$FIELDNO'";
	$rs_s = exequery(TD::conn(),$query);

	if($rs_d && $rs_s) {
		Message("",_("字段删除成功"));
		Button_Back();
	}else{
		Message(_("提示"),_("字段删除错误"));
		Button_Back();
	}

?>
</body>
</html>
