<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$FILENAME=_("轮班人员考勤记录");
  //----------- 合法性校验 ---------
   if($DATE1!="")
   {
      $TIME_OK=is_date($DATE1);
   
      if(!$TIME_OK)
      {
         Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if($DATE2!="")
   {
      $TIME_OK=is_date($DATE2);
   
      if(!$TIME_OK)
      {
         Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if(compare_date($DATE1,$DATE2)==1)
   { 
      Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
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
    	Message(_("错误"),_("不属于管理范围内的用户").$DEPT_ID);
      exit;
   }
   
   $CUR_DATE=date("Y-m-d",time());
   
   $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DAY_TOTAL=$ROW[0]+1;
      
$MSG = sprintf(_("共（ %d ）天"), $DAY_TOTAL);

$query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
$cursor5 = exequery(TD::conn(),$query5);
$DJCS=mysql_fetch_row($cursor5);
$LOG = sprintf(_("共登记（ %d ）次"), $DJCS[0]);

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILENAME);


?>

<!------------------------------------- 上下班 ------------------------------->
<table border="1" width="100%" cellspacing="0" cellpadding="3" >
  <tr>
    <td colspan="8" align="center"><?=_("上下班统计")?>
    (<?=$USER_NAME?> <?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </td>
  </tr>
</table>

<?
if(MYOA_IS_UN == 1)
	$OUTPUT_HEAD_DUTY="SXB,TIME,IP";
else
	$OUTPUT_HEAD_DUTY=array(_("登记类型"),_("登记时间"),_("登记IP"));
	
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
     $SXB=_("上班登记");
   else if($SXB=="2") 
     $SXB=_("下班登记");
$OUTPUT_BODY_DUTY=format_cvs($SXB).','.format_cvs($REGISTER_TIME).','.format_cvs($REGISTER_IP);
$objExcel->addRow($OUTPUT_BODY_DUTY);
}
$MEMO=$USER_NAME._("从").format_date($DATE1)._("至").format_date($DATE2).$MSG.",".$LOG;
$MEMO=format_cvs($MEMO);
$objExcel->addRow($MEMO);
$objExcel->Save();
?>