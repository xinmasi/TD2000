<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");

$EXCEL_OUT=array(_("部门"), _("姓名"), _("出差地点"), _("登记IP"), _("开始日期"), _("结束日期"), _("审批人员"), _("状态"));

if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

$query = "SELECT * from ATTEND_EVECTION,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_EVECTION.USER_ID=USER.USER_ID and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
$EVECTION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $DEPT_ID=$ROW["DEPT_ID"];
  $USER_NAME=$ROW["USER_NAME"];
  $EVECTION_ID=$ROW["EVECTION_ID"];
  $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
  $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
  $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
  $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
  $EVECTION_DEST=str_replace("\r\n","",$ROW["EVECTION_DEST"]);
  $EVECTION_DEST=str_replace(",",_("，"),$EVECTION_DEST);
  $STATUS=$ROW["STATUS"];
  $REGISTER_IP=$ROW["REGISTER_IP"];
  $LEADER_ID=$ROW["LEADER_ID"];

  $LEADER_NAME="";
  $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
  $cursor1= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
     $LEADER_NAME=$ROW["USER_NAME"];

 if(!is_dept_priv($DEPT_ID))
    continue;

 $EVECTION_COUNT++;

 if($STATUS=="1")
    $STATUS=_("在外");
 else
    $STATUS=_("归来");
 $DEPT_ID=intval($DEPT_ID);
 $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
    $USER_DEPT_NAME=$ROW["DEPT_NAME"];

 $EXCEL_OUT.="$USER_DEPT_NAME,$USER_NAME,$EVECTION_DEST,$REGISTER_IP,$EVECTION_DATE1,$EVECTION_DATE2,$LEADER_NAME,$STATUS\n";
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($EXCEL_OUT));
Header("Content-Length: ".strlen($EXCEL_OUT));
Header("Content-Disposition: attachment; ".get_attachment_filename(_("考勤出差数据").".csv"));

if(MYOA_IS_UN == 1)
   echo chr(0xEF).chr(0xBB).chr(0xBF);

echo $EXCEL_OUT;
?>