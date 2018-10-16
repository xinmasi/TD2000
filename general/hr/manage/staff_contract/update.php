<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("合同信息修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor" topmargin="5">
<?
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

$CUR_TIME=date("Y-m-d H:i:s",time());
//------------------- 合同信息 -----------------------
if($TRAIL_PASS_OR_NOT==_("是"))
{
    $TRAIL_PASS_OR_NOT=1;
    if($PASS_OR_NOT==_("是"))
        $PASS_OR_NOT=1;
    if($PASS_OR_NOT==_("否"))
        $PASS_OR_NOT=0;
}
if($TRAIL_PASS_OR_NOT==_("否"))
{
    $TRAIL_PASS_OR_NOT=0;
    $TRAIL_OVER_TIME="";
}

if($REMOVE_OR_NOT==_("是"))
   $REMOVE_OR_NOT=1;
if($REMOVE_OR_NOT==_("否"))
{
    $REMOVE_OR_NOT=0;
    $CONTRACT_REMOVE_TIME="";
}

if($RENEW_OR_NOT==_("是"))
   $RENEW_OR_NOT=1;
if($RENEW_OR_NOT==_("否"))
{
    $RENEW_OR_NOT=0;
    $CONTRACT_RENEW_TIME="0000-00-00";
}
if($TRAIL_PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    $PASS_OR_NOT=1;
}
//提醒设置
$REMIND_TYPE=0;
if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
	$REMIND_TYPE=3;
else if($SMS_REMIND=="on" && $SMS2_REMIND!="on")
	$REMIND_TYPE=1;
else if($SMS2_REMIND=="on" && $SMS_REMIND!="on")
	$REMIND_TYPE=2;
//------- 判断合同状态 --------
if($TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    $STATUS=1;
}
elseif($REMOVE_OR_NOT==1)
{
    $STATUS=3;
}
else
{
    $STATUS=2;
}

if($STAFF_CONTRACT_NO =="")
{
    $STAFF_CONTRACT_NO=  time();
}
$REMIND_TYPE=0;
if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
	$REMIND_TYPE=3;
else if($SMS_REMIND=="on" && $SMS2_REMIND!="on")
	$REMIND_TYPE=1;
else if($SMS2_REMIND=="on" && $SMS_REMIND!="on")
	$REMIND_TYPE=2;
//-----------------合法性校验-------------------------------------
//-----------------校验-------------------------------------
if($MAKE_CONTRACT!="" && !is_date($MAKE_CONTRACT))
{
   Message("",_("合同签订日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($CONTRACT_EFFECTIVE_TIME!="" && !is_date($CONTRACT_EFFECTIVE_TIME))
{
   Message("",_("生效日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($CONTRACT_END_DATE!="" && !is_date($CONTRACT_END_DATE))
{
   Message("",_("合同终止日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($TRAIL_OVER_TIME!="" && !is_date($TRAIL_OVER_TIME))
{
   Message("",_("试用截止日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($CONTRACT_REMOVE_TIME!="" && !is_date($CONTRACT_REMOVE_TIME))
{
   Message("",_("合同解除日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($CONTRACT_RENEW_TIME!="" && !is_date($CONTRACT_RENEW_TIME))
{
   Message("",_("续约日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
//------------------- 续签日期 -----------------------
$query = "SELECT RENEW_TIME from  hr_staff_contract where CONTRACT_ID = '$CONTRACT_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $RENEW_TIME_QUERY=$ROW['RENEW_TIME'];
    if($RENEW_TIME_QUERY != "0000-00-00")
    {
        $RENEW_TIME_QUERY1 = trim($RENEW_TIME_QUERY,"|");
        $A_RENEW_TIME_QUERY = explode("|",$RENEW_TIME_QUERY1);
        $COUNT_RENEW_TIME_QUERY= count($A_RENEW_TIME_QUERY);
        if($RENEW_TIME_QUERY1=="0000-00-00")
        {
            $time3 = $CONTRACT_END_TIME;
        }
        else
        {
                if($COUNT_RENEW_TIME_QUERY==1)
                {
                    $time3 = $RENEW_TIME_QUERY1;
                }
                else
                {
                    $time3 = $A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-2];
                }
        }
        if($COUNT_RENEW_TIME_QUERY==1)
        {
            $time2 = $CONTRACT_END_TIME;
        }
        else
        {
            $time2 = $A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-2];
        }

        if($A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-1] != "0000-00-00" && $A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY] != $CONTRACT_RENEW_TIME && $CONTRACT_RENEW_TIME != "" && $time3 < $CONTRACT_RENEW_TIME && $CONTRACT_RENEW_TIME !="0000-00-00")
        {
            $REMIND_TIME1=$RENEW_TIME_QUERY.$CONTRACT_RENEW_TIME."|";
        }
        else
        {
            if($time3 > $CONTRACT_RENEW_TIME)
            {
                Message("",_("续签日期不能小于上次续签日期或者小于合同终止日期"));
                Button_Back();
                exit();
            }
            else
            {
                $REMIND_TIME1=$RENEW_TIME_QUERY;
            }
        }
    }
    else
    {
        if($CONTRACT_RENEW_TIME =="" && $RENEW_OR_NOT==1)
        {
            Message("",_("请输入续约日期"));
            Button_Back();
            exit;
        }
        elseif($RENEW_OR_NOT !=1)
        {
            $REMIND_TIME1="0000-00-00";
        }
        else
        {
            $REMIND_TIME1=$CONTRACT_RENEW_TIME."|";
        }
    }
}

//------------------- 信息提示日期 -----------------------
$query = "SELECT PARA_VALUE from  sys_para where PARA_NAME='TRIAL_LABOR_DAY'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $TRIAL_LABOR_DAY=explode(",",$ROW['PARA_VALUE']);
}
if($TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[0]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($TRAIL_OVER_TIME."-".$TRIAL_LABOR_DAY[0]." days +9 hours"));
    }
}

if($PASS_OR_NOT==1 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_END_DATE."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}
if($REMOVE_OR_NOT==1 && $RENEW_OR_NOT==0)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_REMOVE_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}
if($RENEW_OR_NOT==1)
{
    if($TRIAL_LABOR_DAY[1]!=null)
    {
        $REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_RENEW_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
    }
}

$query="UPDATE HR_STAFF_CONTRACT
        SET
        STAFF_NAME='$STAFF_NAME',
        STAFF_USER_NAME='$STAFF_NAME',
        STAFF_CONTRACT_NO='$STAFF_CONTRACT_NO',
        CONTRACT_TYPE='$CONTRACT_TYPE',
        CONTRACT_SPECIALIZATION='$CONTRACT_SPECIALIZATION',
        MAKE_CONTRACT='$MAKE_CONTRACT',
        TRAIL_EFFECTIVE_TIME='$CONTRACT_EFFECTIVE_TIME',
        PROBATIONARY_PERIOD='',
        TRAIL_OVER_TIME='$TRAIL_OVER_TIME',
        PASS_OR_NOT='$PASS_OR_NOT',
        PROBATION_END_DATE='$CONTRACT_END_DATE',
        PROBATION_EFFECTIVE_DATE='$CONTRACT_EFFECTIVE_TIME',
        ACTIVE_PERIOD='',
        CONTRACT_END_TIME='$CONTRACT_END_DATE',
        REMOVE_OR_NOT='$REMOVE_OR_NOT',
        CONTRACT_REMOVE_TIME='$CONTRACT_REMOVE_TIME',
        STATUS='$STATUS',
        SIGN_TIMES='',
        REMARK='$REMARK',
        REMIND_TIME='$REMIND_TIME',
        REMIND_TYPE='$REMIND_TYPE',
        HAS_REMINDED='0',
        ATTACHMENT_ID='$ATTACHMENT_ID',
        ATTACHMENT_NAME='$ATTACHMENT_NAME',
        REMIND_USER='$TO_ID',
        LAST_UPDATE_TIME='$CUR_TIME',
        RENEW_TIME='$REMIND_TIME1',
        CONTRACT_ENTERPRIES='$CONTRACT_ENTERPRIES',
        IS_TRIAL='$TRAIL_PASS_OR_NOT',
        IS_RENEW='$RENEW_OR_NOT'
        WHERE CONTRACT_ID = '$CONTRACT_ID'";
exequery(TD::conn(),$query);

save_field_data("CONTRACT",$CONTRACT_ID,$_POST);

//------- 更新员工角色 --------
if($role!="")
{
$query2 = "SELECT * from  user_priv WHERE USER_PRIV = '$role'";
$cursor2= exequery(TD::conn(),$query2);
if($ROW2=mysql_fetch_array($cursor2))
{
    $PRIV_NO = $ROW2["PRIV_NO"];
    $PRIV_NAME= $ROW2["PRIV_NAME"];
}
$query="UPDATE user SET USER_PRIV='$role',USER_PRIV_NO='$PRIV_NO',USER_PRIV_NAME='$PRIV_NAME' WHERE USER_ID = '$STAFF_NAME'";
exequery(TD::conn(),$query);
}
//------- 事务提醒 --------
//$REMAND_USERS=$TO_ID;
//if($TRIAL_LABOR_DAY[0]!="" && $TRIAL_LABOR_DAY[1]!="")
//{
//$query = "SELECT a.DEPT_HR_MANAGER,b.USER_NAME from HR_MANAGER a left join USER b on a.DEPT_ID=b.DEPT_ID where b.USER_ID='$STAFF_NAME'";
//$cursor= exequery(TD::conn(),$query);
//$DEPT_HR_MANAGER="";
//if($ROW=mysql_fetch_array($cursor))
//{
//	 $USER_NAME = $ROW["USER_NAME"];
//	 $DEPT_HR_MANAGER = $ROW["DEPT_HR_MANAGER"];
//}
//$REMAND_USERS=$DEPT_HR_MANAGER.$REMAND_USERS;
//$REMAND_USERS=str_replace(',,',',',$REMAND_USERS);
//$REMAND_USERS_trim=  trim($REMAND_USERS,',');
//$REMAND_USERS_ARRAY=  explode(',', $REMAND_USERS_trim);
//$REMAND_USERS_NEW="";
//if(in_array($STAFF_NAME, $REMAND_USERS_ARRAY))
//{
//    foreach ($REMAND_USERS_ARRAY as $key => $value)
//    {
//        if($value !=$STAFF_NAME)
//        $REMAND_USERS_NEW.=$value.",";
//    }
//}
//if($TRAIL_OVER_TIME!="" && $REMIND_TIME!="" && $TRAIL_PASS_OR_NOT==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $RENEW_OR_NOT==0)
//{
//  if($REMAND_USERS != "")
//  {
//    $REMIND_URL="hr/manage/staff_contract/modify.php?CONTRACT_ID=".$CONTRACT_ID;
//    $SMS_CONTENT=$USER_NAME._("试用期限已满，请及时办理转正手续！");
//    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS_NEW,63,$SMS_CONTENT,$REMIND_URL);
//  }
//
//  $REMIND_URL="ipanel/hr/contract_detail_info.php?CONTRACT_ID=".$CONTRACT_ID;
//  $SMS_CONTENT=$USER_NAME._("试用期限已满，请及时办理转正手续！");
//  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$STAFF_NAME,63,$SMS_CONTENT,$REMIND_URL);
//}
//else
//{
//    if($REMAND_USERS != "")
//    {
//        $REMIND_URL="hr/manage/staff_contract/modify.php?CONTRACT_ID=".$CONTRACT_ID;
//        $SMS_CONTENT= $USER_NAME._("合同已到期，请及时办理续签手续！");
//        send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS_NEW,63,$SMS_CONTENT,$REMIND_URL);
//    }
//
//    $REMIND_URL="ipanel/hr/contract_detail_info.php?CONTRACT_ID=".$CONTRACT_ID;
//    $SMS_CONTENT= $USER_NAME._("合同已到期，请及时办理续签手续！");
//    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$STAFF_NAME,63,$SMS_CONTENT,$REMIND_URL);
//}

//if($CONTRACT_END_TIME=="" && $TRAIL_OVER_TIME!="" && $REMIND_TIME!="" && $SMS2_REMIND=="on")
//{
//   $SMS_CONTENT=_("OA合同管理:").$USER_NAME._("试用期限已满，请及时办理转正手续！");
//   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,63);
//}
//else if ($CONTRACT_END_TIME!="" && $REMIND_TIME!="" && $SMS_REMIND=="on")
//{
//	$SMS_CONTENT=_("OA合同管理:").$USER_NAME._("合同已到期，请及时办理续签手续！");
//   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,63);
////}
//
//}
header("location:index1.php?CONTRACT_ID=$CONTRACT_ID&connstatus=1")

?>
</body>
</html>
