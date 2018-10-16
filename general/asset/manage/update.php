<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("修改固定资产");
include_once("inc/header.inc.php");
?>


<script>
function closeWindow(){
   window.parent.opener.location.href=window.parent.opener.location.href;window.parent.close();
}
</script>


<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
if($CPTL_NAME=="")
   $MSG_ST.=_("资产名称,");
if($DEPT_ID=="")
   $MSG_ST.=_("所属部门,");
if($CPTL_KIND=="")
   $MSG_ST.=_("资产性质,");
if($PRCS_ID=="")
   $MSG_ST.=_("增加类型,");
if($KEEPER=="")
   $MSG_ST.=_("保管人,");
if($CPTL_KIND=="01")
{
   if($FINISH_FLAG=="")
      $MSG_ST.=_("折旧,");
}

if($MSG_STR!="")
{
   $MSG_STR=substr($MSG_STR,0,-1)._("不能为空");
   Message("",$MSG_STR);
}

$MSG_STR="";
if($CPTL_KIND=="01")
{
   if(!settype($CPTL_BAL,"float"))
      $MSG_STR.=_("折旧(率),");
   if(!settype($DPCT_YY,"float"))
      $MSG_STR.=_("折旧年限,");
   if(!settype($SUM_DPCT,"float"))
      $MSG_STR.=_("累计折旧,");
   if(!settype($MON_DPCT,"float"))
      $MSG_STR.=_("月折旧额,");
}
else
{
   if($CPTL_BAL!=""&&!settype($CPTL_BAL,"float"))
      $MSG_STR.=_("折旧(率),");
   if($DPCT_YY!=""&&!settype($DPCT_YY,"float"))
      $MSG_STR.=_("折旧年限,");
   if($SUM_DPCT!=""&&!settype($SUM_DPCT,"float"))
      $MSG_STR.=_("累计折旧,");
   if($MON_DPCT!=""&&!settype($MON_DPCT,"float"))
      $MSG_STR.=_("月折旧额,");
}
if($MSG_STR!="")
{
   $MSG_STR=substr($MSG_STR,0,-1)._("必须为数字");
   Message("",$MSG_STR);
   Button_Back();
   exit;
}

if($FROM_YYMM!=""&&!is_date($FROM_YYMM))
{
    Message("",_("启用日期应为日期型"));
    Button_Back();
    exit;
}

$UPDATE_STR="CPTL_NAME='$CPTL_NAME',TYPE_ID='$TYPE_ID',DEPT_ID='$DEPT_ID',CPTL_VAL='$CPTL_VAL',CPTL_KIND='$CPTL_KIND',PRCS_ID='$PRCS_ID'";
if($CPTL_BAL!="")
   $UPDATE_STR.=",CPTL_BAL='$CPTL_BAL'";
else
   $UPDATE_STR.=",CPTL_BAL=0";
if($DPCT_YY!="")
   $UPDATE_STR.=",DPCT_YY='$DPCT_YY'";
else
   $UPDATE_STR.=",DPCT_YY=0";
if($SUM_DPCT!="")
   $UPDATE_STR.=",SUM_DPCT='$SUM_DPCT'";
else
   $UPDATE_STR.=",SUM_DPCT=0";
if($MON_DPCT!="")
   $UPDATE_STR.=",MON_DPCT='$MON_DPCT'";
else
   $UPDATE_STR.=",MON_DPCT=0";
if($FINISH_FLAG!="")
   $UPDATE_STR.=",FINISH_FLAG='$FINISH_FLAG'";

//...........
if($TO_NAME!="")
{
  $query1="select USER_ID from USER where USER_NAME='$TO_NAME'";
  $cursor1= exequery(TD::conn(),$query1);
  $NUM=mysql_num_rows($cursor1);
  if($ROWs=mysql_fetch_array($cursor1))
  {
  	 $USER_ID=$ROWs['USER_ID'];
  }
  if($NUM==0)
  {
	   $KEEPER=$TO_NAME;
  }
  else
  {
  	 $KEEPER =$USER_ID;
  }
  
}
if($TO_ID!="")
    $KEEPER=$TO_ID;
//...................

$UPDATE_STR.=",CREATE_DATE='$CUR_DATE',FROM_YYMM='$FROM_YYMM',KEEPER='$KEEPER',REMARK='$REMARK'";

//2011-06-07 LP 照片修改功能
if($_FILES['PHOTO']['tmp_name']!="")
{
   if($ATTACHMENT_ID_OLD!=""&&$ATTACHMENT_NAME_OLD!="")
      delete_attach($ATTACHMENT_ID_OLD,$ATTACHMENT_NAME_OLD);
		$PHOTO = '';
		if($_FILES['PHOTO']['error'] == 0 && $_FILES['PHOTO']['size'] > 0)
		{
			 $ATTACHMENT = $_FILES["PHOTO"]["tmp_name"];
			 $EXT_NAME = strtolower(substr($_FILES["PHOTO"]["name"], strrpos($_FILES["PHOTO"]["name"], ".")));
			 if($EXT_NAME != ".jpg" && $EXT_NAME != ".gif" && $EXT_NAME != ".png")
			 {
					unlink($ATTACHMENT);
					Message(_("错误"), _("上传文件非jpg、gif、png格式"), '', $BUTTON_BACK);
					exit;
			 }

			 $ATTACHMENTS=upload("PHOTO");
			 $ATTACHMENT_ID = trim($ATTACHMENTS["ID"],",");
			 $ATTACHMENT_NAME = trim($ATTACHMENTS["NAME"],"*");
		}
}
else
{
	 $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
	 $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

if($ATTACHMENT_ID!="")
   $UPDATE_STR.=",ATTACHMENT_ID='$ATTACHMENT_ID', ATTACHMENT_NAME='$ATTACHMENT_NAME'";

//------------------- 增加固定资产 -----------------------
$query="update CP_CPTL_INFO set $UPDATE_STR where CPTL_ID='$CPTL_ID'";
exequery(TD::conn(),$query);

save_field_data("CP_CPTL_INFO",$CPTL_ID,$_POST);

echo "<script>closeWindow();</script>";
?>
</body>
</html>
