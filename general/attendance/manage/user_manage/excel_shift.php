<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$FILENAME=_("�ְ���Ա���ڼ�¼");
  //----------- �Ϸ���У�� ---------
   if($DATE1!="")
   {
      $TIME_OK=is_date($DATE1);
   
      if(!$TIME_OK)
      {
         Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if($DATE2!="")
   {
      $TIME_OK=is_date($DATE2);
   
      if(!$TIME_OK)
      {
         Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if(compare_date($DATE1,$DATE2)==1)
   { 
      Message(_("����"),_("��ѯ����ʼ���ڲ������ڽ�ֹ����"));
      Button_Back();
      exit;
   }

   $query = "SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   $LINE_COUNT=0;
   if($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $DUTY_TYPE=$ROW["DUTY_TYPE"];
      $DEPT_ID=$ROW["DEPT_ID"];
   }
   
   if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
   {
    	Message(_("����"),_("�����ڹ���Χ�ڵ��û�").$DEPT_ID);
      exit;
   }
   
   $CUR_DATE=date("Y-m-d",time());
   
   $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DAY_TOTAL=$ROW[0]+1;
      
$MSG = sprintf(_("���� %d ����"), $DAY_TOTAL);

$query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
$cursor5 = exequery(TD::conn(),$query5);
$DJCS=mysql_fetch_row($cursor5);
$LOG = sprintf(_("���Ǽǣ� %d ����"), $DJCS[0]);

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILENAME);


?>

<!------------------------------------- ���°� ------------------------------->
<table border="1" width="100%" cellspacing="0" cellpadding="3" >
  <tr>
    <td colspan="8" align="center"><?=_("���°�ͳ��")?>
    (<?=$USER_NAME?> <?=_("��")?> <?=format_date($DATE1)?> <?=_("��")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </td>
  </tr>
</table>

<?
if(MYOA_IS_UN == 1)
	$OUTPUT_HEAD_DUTY="SXB,TIME,IP";
else
	$OUTPUT_HEAD_DUTY=array(_("�Ǽ�����"),_("�Ǽ�ʱ��"),_("�Ǽ�IP"));
	
$objExcel->addHead($OUTPUT_HEAD_DUTY);
$query = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$CUR_DATE') and REGISTER_TYPE='$I'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $SXB=$ROW["SXB"];
   $REGISTER_TIME=strtok($REGISTER_TIME," ");
   $REGISTER_TIME=strtok(" ");
   if($SXB=="1")
     $SXB=_("�ϰ�Ǽ�");
   else if($SXB=="2") 
     $SXB=_("�°�Ǽ�");
$OUTPUT_BODY_DUTY=format_cvs($SXB).','.format_cvs($REGISTER_TIME).','.format_cvs($REGISTER_IP);
$objExcel->addRow($OUTPUT_BODY_DUTY);
}
$MEMO=$USER_NAME._("��").format_date($DATE1)._("��").format_date($DATE2).$MSG.",".$LOG;
$MEMO=format_cvs($MEMO);
$objExcel->addRow($MEMO);
$objExcel->Save();
?>