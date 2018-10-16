<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
ob_end_clean();
function HandleError($message)
{
    echo "-ERR ".iconv(MYOA_CHARSET,"utf-8",$message);
    exit;
}
while (list($key, $value) = each($_GET))
    $$key=$value;
while (list($key, $value) = each($_POST))
    $$key=$value;
if($SUB_DIR!="")
    $SUB_DIR=urldecode($SUB_DIR);

$query = "SELECT PIC_NAME,PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PIC_NAME=$ROW["PIC_NAME"];
    $PIC_PATH=$ROW["PIC_PATH"];
}
else
    exit;

if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./"))
    exit;

if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
    $CUR_DIR = $PIC_PATH.$SUB_DIR;
else
    $CUR_DIR = $PIC_PATH."/".$SUB_DIR;

if(stristr($CUR_DIR,".."))
{
    Message(_("错误"),_("参数含有非法字符。"));
    exit;
}

// Check the upload
if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
{
    header("HTTP/1.1 500 Internal Server Error");
    echo _("上传失败");
    exit(0);
}

//类型检查
$MY_FILE_NAME=iconv("utf-8",MYOA_CHARSET,$_FILES["Filedata"]["name"]);
$EXT_NAME=substr($MY_FILE_NAME,-4);

if(stristr($EXT_NAME,".php")||stristr($EXT_NAME,".jsp"))
{
    header("HTTP/1.1 500 Internal Server Error");
    echo _("上传文件被禁止1");
    exit(0);
}
if(strstr($MY_FILE_NAME,"/") || strstr($MY_FILE_NAME,"\\") || strstr($MY_FILE_NAME,"..") || strstr($MY_FILE_NAME,"!") || strstr($MY_FILE_NAME,"@")|| strstr($MY_FILE_NAME,"#") || strstr($MY_FILE_NAME,"$") || strstr($MY_FILE_NAME,"%") || strstr($MY_FILE_NAME,"^") || strstr($MY_FILE_NAME,"&") || strstr($MY_FILE_NAME,"*") || strstr($MY_FILE_NAME,"{") || strstr($MY_FILE_NAME,"}") || strstr($MY_FILE_NAME,"[") || strstr($MY_FILE_NAME,"]"))
{
    header("HTTP/1.1 500 Internal Server Error");
    echo _("上传文件被禁止2");
    exit(0);
}

//-- 拷贝文件 --
$TMP_FILE_URL = str_replace("//","/",$CUR_DIR."/".$MY_FILE_NAME);
$TMP_FILE_URL = iconv2os($TMP_FILE_URL);
if(file_exists($TMP_FILE_URL))
{
    HandleError(_("文件已存在"));
}
td_copy(iconv2os($_FILES["Filedata"]["tmp_name"]),iconv2os(str_replace("//","/",$CUR_DIR."/".$MY_FILE_NAME)));

$query="select PARA_VALUE from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PARA_VALUE=$ROW["PARA_VALUE"];
    $SMS_PRIV_ARRAY=explode("|",$PARA_VALUE);
}
if(find_id($SMS_PRIV_ARRAY[2],"67"))
{
    $REMIND_URL="picture/picture.php?SUB_DIR=".urlencode($SUB_DIR)."&PIC_ID=".$PIC_ID."&CUR_DIR=".urlencode($CUR_DIR);
    $query="select TO_DEPT_ID,TO_PRIV_ID,TO_USER_ID from PICTURE where PIC_ID='$PIC_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TO_DEPT_ID=$ROW["TO_DEPT_ID"];
        $TO_PRIV_ID=$ROW["TO_PRIV_ID"];
        $TO_USER_ID=$ROW["TO_USER_ID"];
    }

    $USER_ID_STR="";
    $query="select USER_ID from USER where NOT_LOGIN='0'";
    if($TO_DEPT_ID!="ALL_DEPT")
        $query.=" and (find_in_set(DEPT_ID,'$TO_DEPT_ID') or find_in_set(USER_PRIV,'$TO_PRIV_ID') or find_in_set(USER_ID,'$TO_USER_ID'))";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
        $USER_ID_STR.=$ROW["USER_ID"].",";

    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("上传图片，图片名为：").$MY_FILE_NAME;
    if($USER_ID_STR!="")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,67,$SMS_CONTENT,$REMIND_URL,$PIC_ID);
}
$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
echo $file_id;// Return the file id to the script
?>