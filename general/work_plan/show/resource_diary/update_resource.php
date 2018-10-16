<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());

//--------- 上传附件 ----------
//echo $ATTACHMENT;
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

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

$query="update WRESOURCE_DETAIL set PROGRESS='$PROGRESS',PERCENT='$PERCENT',WRITE_TIME='$WRITE_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where AUTO_DETAIL='$DETAIL_ID'";
exequery(TD::conn(),$query);

/*$sql1="select PLAN_ID from WORK_DETAIL where DETAIL_ID='$DETAIL_ID'";
$re=exequery($connection,$sql1);
if ($ROW=mysql_fetch_array($re)){
	$PLAN_ID=$ROW['PLAN_ID'];
}

$sql="select MAX(PERCENT) AS PERCENT from WORK_DETAIL WHERE PLAN_ID='$PLAN_ID' AND WRITER='$LOGIN_USER_ID'";
$re1=exequery($connection,$sql);
if ($ROW1=mysql_fetch_array($re1)){
	$PERCENT=$ROW1['PERCENT'];
}*/

//$sql2="update TASK set RATE='$PERCENT',EDIT_TIME='$CUR_TIME' where WORK_PLAN_ID='$PLAN_ID' AND USER_ID='$LOGIN_USER_ID'";
//exequery($connection,$sql2);


//加入工作日志
/*if($WRITE_IN_WORK=="on")
{
   $query="insert into DIARY(USER_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT) values ('$LOGIN_USER_ID','$CUR_DATE','$CUR_TIME','1','"._("工作计划进度")."','$PROGRESS')";
   exequery($connection,$query);	
}*/

if($OP==1)
   header("location: add_resource.php?AUTO_PERSON=$RPERSON_ID");
else
   header("location: edit_resurce.php?PLAN_ID=$RPERSON_ID&DETAIL_ID=$DETAIL_ID");
?>
</div>
</body>
</html>
