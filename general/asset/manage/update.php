<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�޸Ĺ̶��ʲ�");
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
   $MSG_ST.=_("�ʲ�����,");
if($DEPT_ID=="")
   $MSG_ST.=_("��������,");
if($CPTL_KIND=="")
   $MSG_ST.=_("�ʲ�����,");
if($PRCS_ID=="")
   $MSG_ST.=_("��������,");
if($KEEPER=="")
   $MSG_ST.=_("������,");
if($CPTL_KIND=="01")
{
   if($FINISH_FLAG=="")
      $MSG_ST.=_("�۾�,");
}

if($MSG_STR!="")
{
   $MSG_STR=substr($MSG_STR,0,-1)._("����Ϊ��");
   Message("",$MSG_STR);
}

$MSG_STR="";
if($CPTL_KIND=="01")
{
   if(!settype($CPTL_BAL,"float"))
      $MSG_STR.=_("�۾�(��),");
   if(!settype($DPCT_YY,"float"))
      $MSG_STR.=_("�۾�����,");
   if(!settype($SUM_DPCT,"float"))
      $MSG_STR.=_("�ۼ��۾�,");
   if(!settype($MON_DPCT,"float"))
      $MSG_STR.=_("���۾ɶ�,");
}
else
{
   if($CPTL_BAL!=""&&!settype($CPTL_BAL,"float"))
      $MSG_STR.=_("�۾�(��),");
   if($DPCT_YY!=""&&!settype($DPCT_YY,"float"))
      $MSG_STR.=_("�۾�����,");
   if($SUM_DPCT!=""&&!settype($SUM_DPCT,"float"))
      $MSG_STR.=_("�ۼ��۾�,");
   if($MON_DPCT!=""&&!settype($MON_DPCT,"float"))
      $MSG_STR.=_("���۾ɶ�,");
}
if($MSG_STR!="")
{
   $MSG_STR=substr($MSG_STR,0,-1)._("����Ϊ����");
   Message("",$MSG_STR);
   Button_Back();
   exit;
}

if($FROM_YYMM!=""&&!is_date($FROM_YYMM))
{
    Message("",_("��������ӦΪ������"));
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

//2011-06-07 LP ��Ƭ�޸Ĺ���
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
					Message(_("����"), _("�ϴ��ļ���jpg��gif��png��ʽ"), '', $BUTTON_BACK);
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

//------------------- ���ӹ̶��ʲ� -----------------------
$query="update CP_CPTL_INFO set $UPDATE_STR where CPTL_ID='$CPTL_ID'";
exequery(TD::conn(),$query);

save_field_data("CP_CPTL_INFO",$CPTL_ID,$_POST);

echo "<script>closeWindow();</script>";
?>
</body>
</html>
