<?    
	include_once("inc/auth.inc.php");
	include_once("general/crm/inc/header.php");
	include_once("general/crm/utils/import/errorReport.php");
	ob_end_clean();
	$file_path = stripslashes(stripslashes($file_path));
	$filePath  = $file_path =="" ? $_FILES['upload_file']['tmp_name'] : $file_path;
	$error_line_arr		= explode(",",substr($error_line, 0, -1));
	$error_reason_arr	= explode(",",substr($error_reason, 0, -1));
	$errorReportTitle	= _("产品导入错误报告");
	exportExcelErrorReport($filePath, $errorReportTitle, $error_line_arr, $error_reason_arr);
?>    
