<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ���Զ����ֶ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

	//ɾ���Զ����ֶι���������
	if($CODE_ID == "G") {
		$query="delete from PROJ_FIELD_DATE where TYPE_CODE_NO like '".$CODE_ID."%' AND FIELDNO='$FIELDNO'";
	}else{
		$query="delete from PROJ_FIELD_DATE where TYPE_CODE_NO like '$CODE_ID' AND FIELDNO='$FIELDNO'";
	}
	$rs_d = exequery(TD::conn(),$query);

	//ɾ���Զ����ֶι���������
	$query="delete from PROJ_FIELDSETTING where TYPE_CODE_NO = '$CODE_ID' AND FIELDNO='$FIELDNO'";
	$rs_s = exequery(TD::conn(),$query);

	if($rs_d && $rs_s) {
		Message("",_("�ֶ�ɾ���ɹ�"));
		Button_Back();
	}else{
		Message(_("��ʾ"),_("�ֶ�ɾ������"));
		Button_Back();
	}

?>
</body>
</html>
