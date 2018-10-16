<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="";
$MODULE_DESC=_("待办事宜");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'todo';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$COUNT=0;
$USER_FUNC_ID_STR=$_SESSION["LOGIN_FUNC_STR"];
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());

$MODULE_BODY.= "<ul>";

//------------------------------------------ 个人考勤 --------------------------------------------
if(find_id($USER_FUNC_ID_STR,"7"))
{
  if($COUNT>$MAX_COUNT)
     break;

$query1="select DUTY_TYPE from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
   $DUTY_TYPE=$ROW["DUTY_TYPE"];

//---- 取规定上下班时间 -----
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $DUTY_TIME1=$ROW["DUTY_TIME1"];
   $DUTY_TIME2=$ROW["DUTY_TIME2"];
   $DUTY_TIME3=$ROW["DUTY_TIME3"];
   $DUTY_TIME4=$ROW["DUTY_TIME4"];
   $DUTY_TIME5=$ROW["DUTY_TIME5"];
   $DUTY_TIME6=$ROW["DUTY_TIME6"];

   $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
   $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
   $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
   $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
   $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
   $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
}

$PARA_ARRAY = get_sys_para("DUTY_INTERVAL_BEFORE1,DUTY_INTERVAL_AFTER1,DUTY_INTERVAL_BEFORE2,DUTY_INTERVAL_AFTER2,NO_DUTY_USER");
while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
   $$PARA_NAME = $PARA_VALUE;
   
$REGISTER_TIME1=$CUR_DATE." ".$DUTY_TIME1;
$REGISTER_TIME2=$CUR_DATE." ".$DUTY_TIME2;
$REGISTER_TIME3=$CUR_DATE." ".$DUTY_TIME3;
$REGISTER_TIME4=$CUR_DATE." ".$DUTY_TIME4;
$REGISTER_TIME5=$CUR_DATE." ".$DUTY_TIME5;
$REGISTER_TIME6=$CUR_DATE." ".$DUTY_TIME6;

$DUTY_INTERVAL_BEFORE11="DUTY_INTERVAL_BEFORE".$DUTY_TYPE1;
$DUTY_INTERVAL_AFTER11="DUTY_INTERVAL_AFTER".$DUTY_TYPE1;

$DUTY_INTERVAL_BEFORE22="DUTY_INTERVAL_BEFORE".$DUTY_TYPE2;
$DUTY_INTERVAL_AFTER22="DUTY_INTERVAL_AFTER".$DUTY_TYPE2;

$DUTY_INTERVAL_BEFORE33="DUTY_INTERVAL_BEFORE".$DUTY_TYPE3;
$DUTY_INTERVAL_AFTER33="DUTY_INTERVAL_AFTER".$DUTY_TYPE3;

$DUTY_INTERVAL_BEFORE44="DUTY_INTERVAL_BEFORE".$DUTY_TYPE4;
$DUTY_INTERVAL_AFTER44="DUTY_INTERVAL_AFTER".$DUTY_TYPE4;

$DUTY_INTERVAL_BEFORE55="DUTY_INTERVAL_BEFORE".$DUTY_TYPE5;
$DUTY_INTERVAL_AFTER55="DUTY_INTERVAL_AFTER".$DUTY_TYPE5;

$DUTY_INTERVAL_BEFORE66="DUTY_INTERVAL_BEFORE".$DUTY_TYPE6;
$DUTY_INTERVAL_AFTER66="DUTY_INTERVAL_AFTER".$DUTY_TYPE6;

$BEFORE_UNIX_TIME1=strtotime($REGISTER_TIME1)-$$DUTY_INTERVAL_BEFORE11*60;
$AFTER_UNIX_TIME1=strtotime($REGISTER_TIME1)+$$DUTY_INTERVAL_AFTER11*60;
$BEFORE_UNIX_TIME2=strtotime($REGISTER_TIME2)-$$DUTY_INTERVAL_BEFORE22*60;
$AFTER_UNIX_TIME2=strtotime($REGISTER_TIME2)+$$DUTY_INTERVAL_AFTER22*60; 	 
$BEFORE_UNIX_TIME3=strtotime($REGISTER_TIME3)-$$DUTY_INTERVAL_BEFORE33*60;
$AFTER_UNIX_TIME3=strtotime($REGISTER_TIME3)+$$DUTY_INTERVAL_AFTER33*60; 	 
$BEFORE_UNIX_TIME4=strtotime($REGISTER_TIME4)-$$DUTY_INTERVAL_BEFORE44*60;
$AFTER_UNIX_TIME4=strtotime($REGISTER_TIME4)+$$DUTY_INTERVAL_AFTER44*60;
$BEFORE_UNIX_TIME5=strtotime($REGISTER_TIME5)-$$DUTY_INTERVAL_BEFORE55*60;
$AFTER_UNIX_TIME5=strtotime($REGISTER_TIME5)+$$DUTY_INTERVAL_AFTER55*60; 	 
$BEFORE_UNIX_TIME6=strtotime($REGISTER_TIME6)-$$DUTY_INTERVAL_BEFORE66*60;
$AFTER_UNIX_TIME6=strtotime($REGISTER_TIME6)+$$DUTY_INTERVAL_AFTER66*60;  	 

$WHERE_STR = "";
if($DUTY_TIME1!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME1 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME1)
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME1 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME1)";
if($DUTY_TIME2!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME2 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME2) 	     
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME2 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME2)";
if($DUTY_TIME3!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME3 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME3) 
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME3 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME3)";
if($DUTY_TIME4!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME4 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME4) 
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME4 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME4)";
if($DUTY_TIME5!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME5 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME5) 
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME5 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME5)";
if($DUTY_TIME6!="" && strtotime($CUR_TIME) > $BEFORE_UNIX_TIME6 && strtotime($CUR_TIME) < $AFTER_UNIX_TIME6) 
   $WHERE_STR.=" and  (UNIX_TIMESTAMP(REGISTER_TIME) >$BEFORE_UNIX_TIME6 and UNIX_TIMESTAMP(REGISTER_TIME) < $AFTER_UNIX_TIME6)";

if((!find_id($NO_DUTY_USER,$_SESSION["LOGIN_USER_ID"])) && trim($WHERE_STR)!="")
{
   $query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query);
   if(!$ROW=mysql_fetch_array($cursor))
   {
      $COUNT++;
      $MODULE_BODY.='<li>'._("个人考勤：").'<a href="/general/attendance/personal/duty/" target="_blank">'._("上下班登记").'</a></li>';   	
   }
}

//------------------------------------------ 出差 --------------------------------------------
  $query = "SELECT * from ATTEND_EVECTION where STATUS='1' and ALLOW='0' and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by EVECTION_DATE1 limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;
     $EVECTION_ID=$ROW["EVECTION_ID"];
     $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
     $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
     $EVECTION_DEST=$ROW["EVECTION_DEST"];
     $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
     $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
     $EVECTION_DEST=td_htmlspecialchars($EVECTION_DEST);

     $MODULE_BODY.='<li>'._("出差审批：").'<a href="/general/attendance/manage/confirm/" target="_blank">'.$EVECTION_DEST.'</a> ('.sprintf(_("%s 至 %s"),$EVECTION_DATE1,$EVECTION_DATE2).')</li>';
  }

  $query = "SELECT * from ATTEND_EVECTION where STATUS='1' and ALLOW='1' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by EVECTION_DATE1 limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;

     $MODULE_BODY.='<li>'._("个人考勤：").'<a href="/general/attendance/personal/evection/" target="_blank">'._("出差归来").'</a></li>';
  }

//------------------------------------------ 外出 --------------------------------------------
  $query = "SELECT * from ATTEND_OUT where STATUS='0' and ALLOW='1' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by SUBMIT_TIME limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;
     $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
     $OUT_TIME1=$ROW["OUT_TIME1"];
     $OUT_TIME2=$ROW["OUT_TIME2"];
     $OUT_TYPE=$ROW["OUT_TYPE"];
     $SUBMIT_TIME=strtok($SUBMIT_TIME," ");
     $OUT_TYPE=td_htmlspecialchars($OUT_TYPE);

     $MODULE_BODY.='<li>'._("个人考勤：").'<a href="/general/attendance/personal/out/" target="_blank">'._("外出归来").'</a></li>';
  }
}

//----------------------------------------- 今日外出、请假批示 ----------------------------------------
if(find_id($USER_FUNC_ID_STR,"26"))
{
  $query = "SELECT USER_NAME from ATTEND_OUT,USER where ATTEND_OUT.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and to_days(SUBMIT_TIME)>=to_days('$CUR_DATE') and ALLOW='0' limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);

  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     $USER_NAME=$ROW["USER_NAME"];
     if($COUNT>$MAX_COUNT)
        break;

     $MODULE_BODY.='<li>'._("考勤管理：").'<a href="/general/attendance/manage/confirm/" target="_blank">'.sprintf(_("批示%s的外出申请"),$USER_NAME).'</a></li>';
  }

  $query = "SELECT USER_NAME,ALLOW from ATTEND_LEAVE,USER where ATTEND_LEAVE.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and status='1' and allow in('0','3') limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     $USER_NAME=$ROW["USER_NAME"];
     $ALLOW=$ROW["ALLOW"];
     if($COUNT>$MAX_COUNT)
        break;

     if($ALLOW=="0")
     {
      	$MSG1 = sprintf(_("批示%s的请假申请"), $USER_NAME);
        $ALLOW=$MSG1;
     }
     else
     {
     		$MSG2 = sprintf(_("批示%s的销假申请"), $USER_NAME);
        $ALLOW=$MSG2;
     }

     $MODULE_BODY.='<li>'._("考勤管理：").'<a href="/general/attendance/manage/confirm/" target="_blank">'.$ALLOW.'</a></li>';
  }

  $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and status='1' and ALLOW='1' limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;

     $MODULE_BODY.='<li>'._("个人考勤：").'<a href="/general/attendance/personal/leave/" target="_blank">'._("申请销假").'</a></li>';
  }

}
//------------------------------------------ 办公用品登记审批 --------------------------------------------
/*
if(find_id($USER_FUNC_ID_STR,"126"))
{
	
  $query = "select TRANS_ID,OFFICE_PRODUCTS.PRO_NAME,DEPT_STATUS, TRANS_FLAG, USER.USER_NAME from OFFICE_TRANSHISTORY 
				left join USER on USER.USER_ID = OFFICE_TRANSHISTORY.BORROWER 
				left join OFFICE_PRODUCTS on OFFICE_PRODUCTS.PRO_ID = OFFICE_TRANSHISTORY.PRO_ID
				where TRANS_STATE = '0' and (FIND_IN_SET( '".$_SESSION["LOGIN_USER_ID"]."', `PRO_AUDITER` ))";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;

     $TRANS_FLAG_ARR = array(1 => _("领用"), 2 => _("借用"), 3 => _("归还"));
	 $TRANS_FLAG = $TRANS_FLAG_ARR[$ROW['TRANS_FLAG']];
	 $SHOW_FLAG = $ROW['DEPT_STATUS'];
	 
	 if($SHOW_FLAG=="1")
	 {
     	$MODULE_BODY.='<li>'._("办公用品登记审批：").$SHOW_FLAG.'<a href="/general/office_Product/manage/trans_detail.php?TRANS_ID='.$ROW['TRANS_ID'].'" target="_blank">'.$ROW['USER_NAME'].$TRANS_FLAG.$ROW['PRO_NAME'].'</a></li>';
	 }
  }
}*/
//----------------------------------------- 待批会议 ----------------------------------------
if(find_id($USER_FUNC_ID_STR,"88"))
{
   $query = "SELECT M_NAME from MEETING where M_STATUS='0' and  M_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' order by M_START desc limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
   	 $COUNT++;
      if($COUNT>$MAX_COUNT)
         break;
         
   	 $M_NAME=$ROW["M_NAME"];	 
   	 $MODULE_BODY.='<li>'._("待批会议：").'<a href="/general/meeting/manage/" target="_blank">'.$M_NAME.'</a></li>';
   }
}

//----------------------------------------- 部门待批车辆 ----------------------------------------
if(find_id($USER_FUNC_ID_STR,"152"))
{
   $query = "SELECT vehicle_usage.VU_USER,user.USER_NAME FROM vehicle_usage,user where user.USER_ID = vehicle_usage.VU_USER and DEPT_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' and DMER_STATUS='0' order by VU_START desc limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $COUNT++;
      if($COUNT>$MAX_COUNT)
         break;
   
      $VU_USER   = $ROW["VU_USER"];
	  $USER_NAME = $ROW["USER_NAME"];     
	  $MODULE_BODY.='<li>'._("待批车辆：").'<a href="/general/vehicle/dept_manage/" target="_blank">'.$USER_NAME._("用车").'</a></li>';
   }
}
//----------------------------------------- 车辆调度员待批车辆 ----------------------------------------
if(find_id($USER_FUNC_ID_STR,"93"))
{
   $query = "SELECT vehicle_usage.VU_USER,user.USER_NAME FROM vehicle_usage,user where user.USER_ID = vehicle_usage.VU_USER and VU_OPERATOR='".$_SESSION["LOGIN_USER_ID"]."' and VU_STATUS='0' and SHOW_FLAG='1' order by VU_START desc limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $COUNT++;
      if($COUNT>$MAX_COUNT)
         break;
         
      $VU_USER   = $ROW["VU_USER"];
	  $USER_NAME = $ROW["USER_NAME"];
	  $MODULE_BODY.='<li>'._("待批车辆：").'<a href="/general/vehicle/checkup/" target="_blank">'.$USER_NAME._("用车").'</a></li>';
   }
}

//------------------------------------------ 工资上报 --------------------------------------------
if(find_id($USER_FUNC_ID_STR,"28"))
{
  $query = "SELECT FLOW_ID,CONTENT from SAL_FLOW where to_days(BEGIN_DATE)<=to_days('$CUR_DATE') and to_days(END_DATE)>=to_days('$CUR_DATE') order by BEGIN_DATE desc limit 0,$MAX_COUNT";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;

     $FLOW_ID=$ROW["FLOW_ID"];
     $CONTENT=$ROW["CONTENT"];

     $MODULE_BODY.='<li>'._("工资上报：").'<a href="/general/hr/salary/submit/" target="_blank">'.$CONTENT.'</a></li>';
  }
}

//------------------------------------------ 公告审批 --------------------------------------------
if(find_id($USER_FUNC_ID_STR,"196"))
{
  $query = "SELECT SUBJECT from NOTIFY where PUBLISH=2 and AUDITER='".$_SESSION["LOGIN_USER_ID"]."' order by NOTIFY_ID";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;

     $SUBJECT=$ROW["SUBJECT"];
     $MODULE_BODY.='<li>'._("公告审批：").'<a href="/general/notify/auditing/unaudited.php" target="_blank">'.$SUBJECT.'</a></li>';
  }
}
//------------------------------------------办公室用品审批------------------------------------------
/*
if(find_id($USER_FUNC_ID_STR,"126"))
{
	include_once("general/office_product/function_type.php");
	$num= get_transhistory($_SESSION['LOGIN_USER_ID']);
	$query = "SELECT trans_flag,trans_date from office_transhistory where (trans_flag in (1,2,3) and dept_manager='{$_SESSION['LOGIN_USER_ID']}' and dept_status=0) or (trans_flag in (1,2,3) and dept_status=1 and trans_state=0 and pro_id in ($num)) ";
	$cursor = exequery(TD::conn(), $query);
	while($ROW = mysql_fetch_array($cursor))
	{
		$COUNT ++;
		if($COUNT>$MAX_COUNT)
			break;	
		$trans_flag = $ROW ["trans_flag"]==1?'领用记录':$ROW ["trans_flag"]==2?'借用记录':'归还记录';
		$MODULE_BODY .= '<li>' . _ ( "办公室用品申请：" ) . '<a href="/general/office_product/dept_approval/pending_list.php" target="_blank">' . $trans_flag . '审批 </a> '.$ROW['trans_date'].'</li>';
	}
}
*/
	
$MODULE_BODY.= "</ul>";
}
if($MODULE_BODY=="<ul></ul>")
	$MODULE_BODY=_("暂无待办事宜");
?>
