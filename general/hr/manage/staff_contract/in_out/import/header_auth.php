<?php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = $title._("导入");
include_once("inc/header.inc.php");
include_once("import_config.php");
include_once("import_func.func.php");
include_once("general/crm/inc/header.php");
require_once 'inc/PHPExcel/PHPExcel.php';//包含类
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel2007.php';//包含读功能实现的2007格式的类
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel5.php';//包含excel5读功能的类
include_once("inc/utility_file.php");
//文件上传

$ATTACHMENTS 	 = upload('upload_file','hr');
$ATTACHMENT_ID	 = substr($ATTACHMENTS["ID"],0,-1);
$ATTACHMENT_NAME = substr($ATTACHMENTS["NAME"],0,-1);
$filePath  		 = attach_real_path($ATTACHMENT_ID, $ATTACHMENT_NAME, "hr");
if($filePath === FALSE)
{
   echo _("文件上传失败");
   exit;
}

$tmp_filename = get_tmp_filename("hr_import", basename($filePath));
$is_tmp_file = decrypt_attach($filePath, $tmp_filename);
if($is_tmp_file)
   $filePath = $tmp_filename;
      
//Excel处理
$PHPExcel 		 = new PHPExcel();
$PHPReader 		 = new PHPExcel_Reader_Excel2007();    //新建excel2007读对象

if(!$PHPReader->canRead($filePath)){      //如果读对象格式不合，新建excel5读对象
	$PHPReader = new PHPExcel_Reader_Excel5();
	if(!$PHPReader->canRead($filePath)){      //如果还不对，输出没有excel
		echo _("格式不符合Excel");
		return ;
	}
}
$PHPExcel = $PHPReader->load($filePath);
$currentSheet = $PHPExcel->getSheet(0);  //取得excel工作“分页”

/**取得一共有多少列*/
$allColumn = $currentSheet->getHighestColumn();
$MAX_COl = 10*260;

/**取得一共有多少行*/
$allRow = $currentSheet->getHighestRow();
for($currentRow = 1; $currentRow<=$allRow; $currentRow++){//获取excel文件数据到数组
	for($currentColumn='A',$i=1; $i < $MAX_COl; $currentColumn++,$i++){
		$address = $currentColumn.$currentRow;
		$abc=iconv("utf-8", MYOA_CHARSET, $currentSheet->getCell($address)->getValue());
		if(trim($abc) == ""){
			break;
		}
		$arr[$currentColumn]=trim($abc);
	}
	$lines[]=$arr;
}
//print_r($lines);
//exit;

headThan($lines, $conn, $filePath);//表头处理

if($is_tmp_file)
   @unlink($tmp_filename);
?>