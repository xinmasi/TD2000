<?php
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = $title._("����");
include_once("inc/header.inc.php");
include_once("import_config.php");
include_once("import_func.func.php");
include_once("general/crm/inc/header.php");
require_once 'inc/PHPExcel/PHPExcel.php';//������
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel2007.php';//����������ʵ�ֵ�2007��ʽ����
require_once 'inc/PHPExcel/PHPExcel/Reader/Excel5.php';//����excel5�����ܵ���
include_once("inc/utility_file.php");
//�ļ��ϴ�

$ATTACHMENTS 	 = upload('upload_file','hr');
$ATTACHMENT_ID	 = substr($ATTACHMENTS["ID"],0,-1);
$ATTACHMENT_NAME = substr($ATTACHMENTS["NAME"],0,-1);
$filePath  		 = attach_real_path($ATTACHMENT_ID, $ATTACHMENT_NAME, "hr");
if($filePath === FALSE)
{
   echo _("�ļ��ϴ�ʧ��");
   exit;
}

$tmp_filename = get_tmp_filename("hr_import", basename($filePath));
$is_tmp_file = decrypt_attach($filePath, $tmp_filename);
if($is_tmp_file)
   $filePath = $tmp_filename;
      
//Excel����
$PHPExcel 		 = new PHPExcel();
$PHPReader 		 = new PHPExcel_Reader_Excel2007();    //�½�excel2007������

if(!$PHPReader->canRead($filePath)){      //����������ʽ���ϣ��½�excel5������
	$PHPReader = new PHPExcel_Reader_Excel5();
	if(!$PHPReader->canRead($filePath)){      //��������ԣ����û��excel
		echo _("��ʽ������Excel");
		return ;
	}
}
$PHPExcel = $PHPReader->load($filePath);
$currentSheet = $PHPExcel->getSheet(0);  //ȡ��excel��������ҳ��

/**ȡ��һ���ж�����*/
$allColumn = $currentSheet->getHighestColumn();
$MAX_COl = 10*260;

/**ȡ��һ���ж�����*/
$allRow = $currentSheet->getHighestRow();
for($currentRow = 1; $currentRow<=$allRow; $currentRow++){//��ȡexcel�ļ����ݵ�����
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

headThan($lines, $conn, $filePath);//��ͷ����

if($is_tmp_file)
   @unlink($tmp_filename);
?>