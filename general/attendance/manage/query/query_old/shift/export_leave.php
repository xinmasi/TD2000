<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

function get_work_days($date1,$date2,$duty_type)
{
   $date1=date("Y-m-d",strtotime($date1));
   $date2=date("Y-m-d",strtotime($date2));
   
   $j=0;
   
   $m = date("m",strtotime($date1));
   $d = date("d",strtotime($date1));
   $Y = date("Y",strtotime($date1));
   
   $query = "SELECT GENERAL FROM ATTEND_CONFIG WHERE DUTY_TYPE = '$duty_type'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
      $GENERAL = $ROW['GENERAL'];
        
   $work_days = 0; 
   for($i = strtotime($date1); $i <= strtotime($date2); $i+= 86400) 
   {
   	
      $y=mktime(0,0,0,$m,$d,$Y);
      $date = $y+$j*24*3600;
      $week = date("w",$date);   
      //echo $week.'<br/>';
      if(find_id($GENERAL,$week))
      {
         $j++;
         continue;           
      }
      
      $t=date("Y-m-d",$date);
      
      $query2 = "SELECT * FROM ATTEND_HOLIDAY";
      $cursor2 = exequery(TD::conn(),$query2);
      $flag = 0;
      while($ROW2 = mysql_fetch_array($cursor2))
      {
         $BEGIN_DATE = $ROW2['BEGIN_DATE'];
         $END_DATE = $ROW2['END_DATE'];
         if(compare_date($t,$BEGIN_DATE)!=-1 && compare_date($END_DATE,$t)!=-1)
            $flag=1;
      }
      
      if($flag==1)
      {
         $j++;         
         continue;
      }   
      
      //echo $t.'<br/>';
      $work_days++;
      $j++;
   }  
   return $work_days; 
}

if(MYOA_IS_UN == 1)
	$EXCEL_OUT="DEPARTMENT,NAME,REASON,ACCOUNT_OF_ANNUAL_LEAVE_TIME,IP,DATE_OF_START,DATE_OF_END,APPROVAL_OF_STAFF,STATUS\n";
else
	$EXCEL_OUT=_("部门").","._("姓名").","._("请假原因").","._("请假类型").","._("请假天数").","._("占年休假").","._("登记IP").","._("开始日期").","._("结束日期").","._("审批人员").","._("状态")."\n";

if(MYOA_IS_UN == 1)
	$FILE_NAME = "ATTENDANCE DATA FOR LEAVE";
else
	$FILE_NAME = _("考勤请假数据");

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILE_NAME);
$objExcel->addHead($EXCEL_OUT);

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTEND_LEAVE,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and ATTEND_LEAVE.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=format_cvs($ROW["USER_ID"]);
  $DEPT_ID=format_cvs($ROW["DEPT_ID"]);
  $USER_NAME=format_cvs($ROW["USER_NAME"]);
  $LEAVE_ID=format_cvs($ROW["LEAVE_ID"]);
  $LEAVE_DATE1=format_cvs($ROW["LEAVE_DATE1"]);
  $LEAVE_DATE2=format_cvs($ROW["LEAVE_DATE2"]);
  $REGISTER_IP=format_cvs($ROW["REGISTER_IP"]);
  $ANNUAL_LEAVE=format_cvs($ROW["ANNUAL_LEAVE"]);
  $DUTY_TYPE=$ROW["DUTY_TYPE"];
  $DAY_DIFF= get_work_days($LEAVE_DATE1,$LEAVE_DATE2,$DUTY_TYPE);
  $LEAVE_TYPE=str_replace("\r\n","",format_cvs($ROW["LEAVE_TYPE"]));
  $LEAVE_TYPE=str_replace(",",_("，"),format_cvs($LEAVE_TYPE));
  $LEADER_ID=format_cvs($ROW["LEADER_ID"]);
  $STATUS=format_cvs($ROW["STATUS"]);

  $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
  $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
  
   $LEADER_NAME="";
   $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $LEADER_NAME=format_cvs($ROW["USER_NAME"]);

  if(!is_dept_priv($DEPT_ID))
     continue;

  $LEAVE_COUNT++;

  if($STATUS==1)
     $STATUS=_("现行");
  else
     $STATUS=_("已销假");
  $DEPT_ID=intval($DEPT_ID);
  $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW=mysql_fetch_array($cursor1))
     $USER_DEPT_NAME=$ROW["DEPT_NAME"];

  $EXCEL_OUT ="$USER_DEPT_NAME,$USER_NAME,$LEAVE_TYPE,$LEAVE_TYPE2,$DAY_DIFF,$ANNUAL_LEAVE,$REGISTER_IP,$LEAVE_DATE1,$LEAVE_DATE2,$LEADER_NAME,$STATUS\n";
	
	$objExcel->addRow($EXCEL_OUT);
}

ob_end_clean();
$objExcel->Save();
?>