<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());

//--------- �ϴ����� ----------
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

$query="update WORK_DETAIL set PROGRESS='$PROGRESS',PERCENT='$PERCENT',WRITE_TIME='$WRITE_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where DETAIL_ID='$DETAIL_ID'";
exequery(TD::conn(),$query);

$sql1="select PLAN_ID from WORK_DETAIL where DETAIL_ID='$DETAIL_ID'";
$re=exequery(TD::conn(),$sql1);
if ($ROW=mysql_fetch_array($re)){
	$PLAN_ID=$ROW['PLAN_ID'];
}

$sql="select MAX(PERCENT) AS PERCENT from WORK_DETAIL WHERE PLAN_ID='$PLAN_ID' AND WRITER='".$_SESSION["LOGIN_USER_ID"]."'";
$re1=exequery(TD::conn(),$sql);
if ($ROW1=mysql_fetch_array($re1)){
	$PERCENT=$ROW1['PERCENT'];
}

$sql2="update TASK set RATE='$PERCENT',EDIT_TIME='$CUR_TIME' where WORK_PLAN_ID='$PLAN_ID' AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$sql2);


//���빤����־
if($WRITE_IN_WORK=="on")
{
   $query="insert into DIARY(USER_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$CUR_DATE','$CUR_TIME','1','"._("�����ƻ�����")."','$PROGRESS')";
   exequery(TD::conn(),$query);	
}

if($OP==1)
   header("location: add_diary.php?PLAN_ID=$PLAN_ID");
else
   header("location: edit_diary.php?PLAN_ID=$PLAN_ID&DETAIL_ID=$DETAIL_ID");
?>
</div>
</body>
</html>
