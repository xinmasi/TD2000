<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_folder.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
ob_end_clean();

function HandleError($message)
{
    echo "-ERR ".iconv(MYOA_CHARSET,"utf-8",$message);
    exit;
}
//提醒对象：项目创建者、负责人、审批者、查看者、项目成员并且有此目录权限
$query = "SELECT PROJ_NAME,PROJ_OWNER,PROJ_LEADER,PROJ_MANAGER,PROJ_VIEWER,PROJ_USER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

    $PROJ_NAME=$ROW["PROJ_NAME"];
    $PROJ_OWNER=$ROW["PROJ_OWNER"];
    $PROJ_LEADER=$ROW["PROJ_LEADER"];
    $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
    $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
    $PROJ_USER=str_replace("|","",$ROW["PROJ_USER"]);
}

$query = "SELECT SORT_NAME,NEW_USER,VIEW_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SORT_NAME=$ROW["SORT_NAME"];
    $VIEW_USER=$ROW["VIEW_USER"];
    $NEW_USER=$ROW["NEW_USER"];

}

if(!find_id($NEW_USER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"] && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"])
    exit;

while (list($key, $value) = each($_GET))
    $$key=$value;
while (list($key, $value) = each($_POST))
    $$key=$value;

if(!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
{
    HandleError(_("上传出现错误"));
}

//$SMS_SELECT_REMIND_TO_ID=iconv("utf-8",MYOA_CHARSET,$SMS_SELECT_REMIND_TO_ID);

$FILE_NAME=iconv("utf-8",MYOA_CHARSET,$_FILES["Filedata"]["name"]);
//将附件信息从utf8转码为系统默认编码 add by ljc 2012-05-03
while (list($key, $value) = each($_FILES["Filedata"])){
    $_FILES["Filedata"][$key] = iconv("utf-8",MYOA_CHARSET,$value);
}
if(strstr($FILE_NAME,"/") || strstr($FILE_NAME,"\\"))
{
    HandleError(_("文件名无效"));
}

if(!is_uploadable($FILE_NAME))
{
    HandleError(_("禁止上传该类文件"));
}

if(file_exists($_FILES["Filedata"]["tmp_name"]))
{
    $ATTACH_NAME=$FILE_NAME;
    $SUBJECT=substr($FILE_NAME,0,strrpos($FILE_NAME,"."));
    $SEND_TIME=date("Y-m-d H:i:s",time());
    //上传程序，调用系统公用函数 add by ljc 2012-05-03
    $ATTACHMENTS=upload("Filedata","",FALSE);
    if(!is_array($ATTACHMENTS))
    {
        HandleError(_($ATTACHMENTS));
    }
    $ATTACHMENT_ID =$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME =$ATTACHMENTS["NAME"];
    //照搬new/submit.php
    $SEND_TIME=date("Y-m-d H:i:s",time());
    $query="insert into PROJ_FILE(PROJ_ID,SORT_ID,SUBJECT,FILE_DESC,UPDATE_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,UPLOAD_USER) values ('$PROJ_ID','$SORT_ID','$SUBJECT','$FILE_DESC','$SEND_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','".$_SESSION["LOGIN_USER_ID"]."')";
    exequery(TD::conn(),$query);
    $FILE_ID=mysql_insert_id();
    $ACTION=1;

}
else
{
    HandleError(_("无文件上传"));
}
//记录
$query = "insert into PROJ_FILE_LOG (FILE_ID,ACTION,USER_ID,ACTION_TIME) VALUES ('$FILE_ID','$ACTION','".$_SESSION["LOGIN_USER_ID"]."','$SEND_TIME') ";
exequery(TD::conn(),$query);
if($SMS_REMIND==1)
{

    $query = "SELECT USER_ID from USER where (FIND_IN_SET(USER_ID,'$PROJ_USER') AND FIND_IN_SET(USER_ID,'$VIEW_USER')) OR FIND_IN_SET(USER_ID,'$PROJ_VIEWER')";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
        $TO_ID_STR.=$ROW["USER_ID"].",";

    if(!find_id($TO_ID_STR,$PROJ_OWNER))
        $TO_ID_STR.=$PROJ_OWNER.",";
    if(!find_id($TO_ID_STR,$PROJ_MANAGER))
        $TO_ID_STR.=$PROJ_MANAGER.",";

    $SMS_FILE_DESC=sprintf(_("%s在项目文档-%s-%s 下建添加文件：%s"),$_SESSION["LOGIN_USER_NAME"],$PROJ_NAME,$SORT_NAME,$SUBJECT);

    $REMIND_URL="1:project/file/read.php?PROJ_ID=".$PROJ_ID."&SORT_ID=".$SORT_ID."&FILE_ID=".$FILE_ID;

    send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,42,$SMS_FILE_DESC,$REMIND_URL,$PROJ_ID);
}
echo md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
?>