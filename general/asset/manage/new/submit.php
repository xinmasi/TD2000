<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("���ӹ̶��ʲ�");
include_once("inc/header.inc.php");
?>




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
if($TO_NAME=="")
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
if(!settype($CPTL_VAL,"float"))
   $MSG_ST.=_("�ʲ�ԭֵ,");
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

$FIELD_STR="CPTL_NO,CPTL_NAME,TYPE_ID,DEPT_ID,CPTL_VAL,CPTL_KIND,PRCS_ID";
$VALUE_STR="'$CPTL_NO','$CPTL_NAME',$TYPE_ID,$DEPT_ID,$CPTL_VAL,'$CPTL_KIND',$PRCS_ID";
if($CPTL_BAL!="")
{
   $FIELD_STR.=",CPTL_BAL";
   $VALUE_STR.=",".$CPTL_BAL;
}
if($DPCT_YY!="")
{
   $FIELD_STR.=",DPCT_YY";
   $VALUE_STR.=",".$DPCT_YY;
}
if($SUM_DPCT!="")
{
   $FIELD_STR.=",SUM_DPCT";
   $VALUE_STR.=",".$SUM_DPCT;
}
if($MON_DPCT!="")
{
   $FIELD_STR.=",MON_DPCT";
   $VALUE_STR.=",".$MON_DPCT;
}
if($FINISH_FLAG!="")
{
   $FIELD_STR.=",FINISH_FLAG";
   $VALUE_STR.=",'".$FINISH_FLAG."'";
}

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


//2011-06-07 LP �����ϴ���Ƭ��������
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

if($ATTACHMENT_ID!="")
{
   $FIELD_STR.=",ATTACHMENT_ID,ATTACHMENT_NAME";
   $VALUE_STR.=",'".$ATTACHMENT_ID."','".$ATTACHMENT_NAME."'";
}


//...................
$FIELD_STR.=",CREATE_DATE,FROM_YYMM,KEEPER,REMARK";
$VALUE_STR.=",'$CUR_DATE','$FROM_YYMM','$KEEPER','$REMARK'";


//------------------- ���ӹ̶��ʲ� -----------------------
$query="insert into CP_CPTL_INFO (".$FIELD_STR.") values(".$VALUE_STR.");";
exequery(TD::conn(),$query);

$CPTL_ID1 =mysql_insert_id();
save_field_data("CP_CPTL_INFO",$CPTL_ID1,$_POST);

header("location: ./");
?>

</body>
</html>
