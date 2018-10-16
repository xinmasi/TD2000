<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/header.inc.php");

//$TmpFileNameStr = unescape($TmpFileNameStr);
$zip_name = time()."_"._("图片文件").".zip";
$zip_file = get_tmp_filename("picture_bat_down", $zip_name);
$img_type_str = "gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,tif";

$zip = new ZipArchive;
$res = $zip->open($zip_file, ZipArchive::CREATE|ZIPARCHIVE::OVERWRITE);
if($res !== TRUE)
{
    Message(_("提示"),_("创建文件错误，代码：").$res);
    Button_Back();
    exit;
}

if(strpos("$PIC_PATH","../")!==FALSE)//strpos("$PIC_PATH",":")!==FALSE || 
{
    Message(_("提示"),_("图片路径错误！"));
    Button_Back();
    exit;
}

$query = "SELECT PIC_PATH from picture where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID)) and PIC_PATH='$PIC_PATH'";
$cursor = exequery(TD::conn(), $query);
if(!$row = mysql_fetch_array($cursor))
{
    Message(_("提示"),_("无权下载此路径下文件！"));
    Button_Back();
    exit;
}
if(stristr($TmpFileNameStr,"|")!="")
{
    $loc_and_filename=explode("@~@",$TmpFileNameStr);
    for($i=0;$i< count($loc_and_filename);$i++)
    {
        //跳过空文件
        if($loc_and_filename[$i] == "")
            continue;

        $temp_loc_and_filename=explode("|",$loc_and_filename[$i]);
        $SUB_DIR = $temp_loc_and_filename[1];
        $FILE_NAME = $temp_loc_and_filename[0];

        $FILE_NAME = iconv('GBK', MYOA_CHARSET, $FILE_NAME);
        $tep_type = strtolower(substr(strrchr($FILE_NAME,"."),1));
        if($tep_type=="db" || !find_id($img_type_str, $tep_type))
            continue;

        if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
            $CUR_DIR = $PIC_PATH.$SUB_DIR;
        else
        {
            if($SUB_DIR=="")
                $CUR_DIR = $PIC_PATH."/";
            else
                $CUR_DIR = $PIC_PATH."/".$SUB_DIR."/";
        }
        $true_file = $CUR_DIR.$FILE_NAME;
        $true_file = iconv2os($true_file);
        if(is_readable($true_file))
            $res = $zip->addFile($true_file, iconv2os($FILE_NAME));
    }
}
else
{
    if($SUB_DIR!="")
        $SUB_DIR=urldecode($SUB_DIR);
    if($TmpFileNameStr!="")
        $TmpFileNameStr = iconv('utf-8', MYOA_CHARSET, $TmpFileNameStr);
    //$TmpFileNameStr=urldecode($TmpFileNameStr);
    if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
        $CUR_DIR = $PIC_PATH.$SUB_DIR;
    else
        $CUR_DIR = $PIC_PATH."/".$SUB_DIR;   

    $FILE_ARRAY=explode("@~@",$TmpFileNameStr); 
    $FILE_ARRAY_COUNT = count($FILE_ARRAY);  
    if($FILE_ARRAY[$FILE_ARRAY_COUNT-1]=="")$FILE_ARRAY_COUNT--;
    for($I=0;$I< $FILE_ARRAY_COUNT; $I++)
    {  
        $tep_type = strtolower(substr(strrchr($FILE_ARRAY[$I],"."),1));
        if($tep_type=="db" || !find_id($img_type_str, $tep_type))
            continue;

        if(substr($CUR_DIR,strlen($CUR_DIR)-1,1)!="/")
            $true_file = $CUR_DIR."/".$FILE_ARRAY[$I];
        else
            $true_file = $CUR_DIR.$FILE_ARRAY[$I];		

        $true_file = iconv2os($true_file);
        if(is_readable($true_file))
            $res = $zip->addFile($true_file, iconv2os($FILE_ARRAY[$I]));    
    }
}

$zip->close();

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/x-zip");
Header("Accept-Ranges: bytes");
header("Accept-Length: ".sprintf("%u", filesize($zip_file)));
Header("Content-Disposition: attachment; ".get_attachment_filename($zip_name));

readfile($zip_file);
@unlink($zip_file);
?>