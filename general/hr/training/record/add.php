<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建培训记录");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$j=substr_count("$T_JOIN_PERSON",",");
$STAFF_USER_ID_PIECES= explode(",",$T_JOIN_PERSON);
$buf=0;
for($i=0;$i<$j;$i++){
$STAFF_USER_ID_EACH=$STAFF_USER_ID_PIECES[$i];
$query1= "SELECT * from  HR_TRAINING_RECORD WHERE 1='1' and STAFF_USER_ID='$STAFF_USER_ID_EACH' and T_PLAN_NO='$T_PLAN_NO'";
$cursor1=exequery(TD::conn(),$query1);
$COUNT = mysql_num_rows($cursor1);

if($COUNT <= 0){
$query="INSERT INTO HR_TRAINING_RECORD( 
CREATE_USER_ID,
CREATE_DEPT_ID, 
STAFF_USER_ID, 
T_PLAN_NO, 
T_PLAN_NAME, 
T_INSTITUTION_NAME, 
TRAINNING_COST,
ATTACHMENT_ID,
ATTACHMENT_NAME
) VALUES (
'{$_SESSION['LOGIN_USER_ID']}',
'{$_SESSION['LOGIN_DEPT_ID']}',
'$STAFF_USER_ID_EACH',
'$T_PLAN_NO',
'$T_PLAN_NAME',
'$T_INSTITUTION_NAME',
'$TRAINNING_COST',
'$ATTACHMENT_ID',
'$ATTACHMENT_NAME'
)";
exequery(TD::conn(),$query);
}

else{	
		  $buf++;
}

}
if($buf==0){
Message("",_("成功增加培训记录信息！"));
Button_Back();
}
else
{
	Message("",_("成功增加培训记录信息！"));
	Message("",_("以下人员信息已存在：$T_JOIN_PERSON_NAME"));
	Button_Back();
}
?>

</body>
</html>
