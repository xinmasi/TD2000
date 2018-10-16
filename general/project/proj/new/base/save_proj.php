<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
include_once("inc/sys_code_field.php");

$PROJ_DESCRIPTION=strip_unsafe_tags($PROJ_DESCRIPTION);


if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $PROJ_DESCRIPTION=ReplaceImageSrc($PROJ_DESCRIPTION, $ATTACHMENTS);
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
  $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
  $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$PROJ_DESCRIPTION);
$PROJ_DESCRIPTION = replace_attach_url($PROJ_DESCRIPTION);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$CUR_TIME = date("Y-m-d H:i:s",time());
// LP 添加自由创建人
if($PROJ_USER_TO_ID == "") {
	$PROJ_USER_TO_ID = $_SESSION["LOGIN_USER_ID"];
}
if(!$PROJ_ID)
{
   $query = "insert into PROJ_PROJECT (PROJ_NUM, PROJ_NAME, PROJ_DESCRIPTION, PROJ_TYPE, PROJ_DEPT, PROJ_UPDATE_TIME, PROJ_START_TIME, PROJ_END_TIME, PROJ_OWNER, PROJ_LEADER, PROJ_VIEWER, PROJ_MANAGER, PROJ_STATUS, ATTACHMENT_ID, ATTACHMENT_NAME) values ('$PROJ_NUM', '$PROJ_NAME', '$PROJ_DESCRIPTION', '$PROJ_TYPE', '$PROJ_DEPT', '$CUR_TIME', '$PROJ_START_TIME', '$PROJ_END_TIME', '$PROJ_USER_TO_ID', '$PROJ_LEADER', '$PROJ_VIEWER', '$PROJ_MANAGER','0', '$ATTACHMENT_ID', '$ATTACHMENT_NAME')";
   exequery(TD::conn(),$query);
   $PROJ_ID=mysql_insert_id();
   
   //插入附加数据到 equip_field_date
   proj_save_field_data($PROJ_TYPE,$PROJ_ID,$_POST);

   //插入全局自定义数据
   proj_save_field_data_g($PROJ_TYPE,$PROJ_ID,$_POST);
}
else
{
	$query = "UPDATE PROJ_PROJECT SET PROJ_NAME = '$PROJ_NAME', PROJ_NUM = '$PROJ_NUM', PROJ_DESCRIPTION = '$PROJ_DESCRIPTION', PROJ_TYPE = '$PROJ_TYPE', PROJ_DEPT = '$PROJ_DEPT', PROJ_UPDATE_TIME = '$CUR_TIME',PROJ_OWNER = '$PROJ_USER_TO_ID', PROJ_START_TIME = '$PROJ_START_TIME', PROJ_END_TIME = '$PROJ_END_TIME', PROJ_LEADER = '$PROJ_LEADER', PROJ_VIEWER = '$PROJ_VIEWER', PROJ_MANAGER = '$PROJ_MANAGER', ATTACHMENT_ID = '$ATTACHMENT_ID', ATTACHMENT_NAME = '$ATTACHMENT_NAME' WHERE PROJ_ID = '$PROJ_ID'";
   exequery(TD::conn(),$query);
   
   //先删除所有已有的数据再重新更新
   proj_del_field_data($PROJ_ID);
   
   //更新附加数据到 equip_field_date
   proj_save_field_data($PROJ_TYPE ,$PROJ_ID,$_POST);

   //插入全局自定义数据
   proj_save_field_data_g($PROJ_TYPE,$PROJ_ID,$_POST);
}
?>