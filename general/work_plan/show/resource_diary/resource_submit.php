<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("添加工作日志");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
//--------- 上传附件 ----------

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

$DETAIL_ID1=intval($DETAIL_ID1);


if($DETAIL_ID1=="")
    $query="insert into WRESOURCE_DETAIL (RPERSON_ID,WRITE_TIME,PROGRESS,PERCENT,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$RPERSON_ID','$WRITE_TIME','$PROGRESS','$PERCENT','".$_SESSION["LOGIN_USER_ID"]."','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
else
    $query="update WRESOURCE_DETAIL set RPERSON_ID='$RPERSON_ID',WRITE_TIME='$WRITE_TIME',PROGRESS='$PROGRESS',PERCENT='$PERCENT',WRITER='".$_SESSION["LOGIN_USER_ID"]."',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where DETAIL_ID='$DETAIL_ID1'";
exequery(TD::conn(),$query);

if($OP=="0" && $DETAIL_ID1=="")
    $DETAIL_ID1=mysql_insert_id();


//加入工作日志
/*if($WRITE_IN_WORK=="on")
{
   $query = "SELECT DIA_ID,CONTENT from DIARY where to_days(DIA_TIME)=to_days('$CUR_TIME') and DIA_TYPE='1' and USER_ID='$LOGIN_USER_ID' order by DIA_TIME desc limit 0,1";
   $cursor= exequery($connection,$query);
   if($ROW=mysql_fetch_array($cursor))
	 {
	 	  $DIA_ID = $ROW["DIA_ID"];
	 	  $CONTENT = $ROW["CONTENT"];

	 	  $CONTENT .= "<br><br>"._("工作计划进度日志（").$CUR_TIME._("）")."<br>".str_replace("\n","<br>",$PROGRESS);
	 	  $query="update DIARY set CONTENT='$CONTENT' where DIA_ID='$DIA_ID'";
      exequery($connection,$query);
	 }else{
	 	  $PROGRESS = _("工作计划进度日志")."<br>".str_replace("\n","<br>",$PROGRESS);
      $query="insert into DIARY(USER_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT) values ('$LOGIN_USER_ID','$CUR_DATE','$CUR_TIME','1','"._("工作计划进度")."','$PROGRESS')";
      exequery($connection,$query);
   }
}*/

//事务提醒
if($OP!="0")
{
    $query = "SELECT * from WORK_PLAN  where PLAN_ID='$PLAN_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $CREATOR=$ROW["CREATOR"];
        $MANAGER=$ROW["MANAGER"];
        $PARTICIPATOR=$ROW["PARTICIPATOR"];
        $USER_ID_STR1=	$CREATOR.",".$MANAGER.",".$PARTICIPATOR;

        $MY_ARRAY=explode(",",$USER_ID_STR1);
        $ARRAY_COUNT=sizeof($MY_ARRAY);
        if($MY_ARRAY[$ARRAY_COUNT-1]=="")
            $ARRAY_COUNT--;
        for($I=0;$I<$ARRAY_COUNT;$I++){
            if($MY_ARRAY[$I]!=$LOGIN_USER_ID)
                $USER_ID_STR.=$MY_ARRAY[$I].",";}

    }

    $SMS_CONTENT=_("有新的计划任务进度日志，请查看。");

    $REMIND_URL="1:work_plan/show/resource_diary/resource_detail.php?RPERSON_ID=".$RPERSON_ID;
    if($SMS_REMIND=="on")
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,12,$SMS_CONTENT,$REMIND_URL,$RPERSON_ID);

    if($SMS2_REMIND=="on")
        send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,12);
}

if($FLAG==1)
    $DETAIL_ID1="";
if($OP==0)
    header("location: add_resource.php?DETAIL_ID1=$DETAIL_ID1&PLAN_ID=$PLAN_ID");
else
    header("location: add_resource.php?AUTO_PERSON=$RPERSON_ID");
?>
</body>
</html>