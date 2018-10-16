<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(strtolower(substr($FILE_NAME,-3))!="xls"){
    Message(_("错误"),_("只能导入xls文件!"));
    Button_Back();
    exit;
}
if(MYOA_IS_UN == 1){
    $title = array("USER_ID"=>"USER_ID","LEAVE_PERSON"=>"LEAVE_PERSON","POSITION"=>"POSITION","QUIT_TYPE"=>"QUIT_TYPE","APPLICATION_DATE"=>"APPLICATION_DATE","QUIT_TIME_PLAN"=>"QUIT_TIME_PLAN","QUIT_TIME_FACT"=>"QUIT_TIME_FACT","LAST_SALARY_TIME"=>"LAST_SALARY_TIME","LEAVE_DEPT"=>"LEAVE_DEPT","TRACE"=>"TRACE","MATERIALS_CONDITION"=>"MATERIALS_CONDITION","REMARK"=>"REMARK","TO_NAME"=>"TO_NAME","QUIT_REASON"=>"QUIT_REASON","SALARY"=>"SALARY");
    $fieldAttr=array("APPLICATION_DATE"=>"date","QUIT_TIME_PLAN"=>"date","QUIT_TIME_FACT"=>"date","LAST_SALARY_TIME"=>"date");
}else{
    $title=array(_("用户名")=>"USER_ID",_("离职人员")=>"LEAVE_PERSON",_("担任职务")=>"POSITION",_("离职类型")=>"QUIT_TYPE",_("申请日期")=>"APPLICATION_DATE",_("拟离职日期")=>"QUIT_TIME_PLAN",_("实际离职日期")=>"QUIT_TIME_FACT",_("工资截止日期")=>"LAST_SALARY_TIME",_("离职部门")=>"LEAVE_DEPT",_("去向")=>"TRACE",_("离职手续办理")=>"MATERIALS_CONDITION",_("备注")=>"REMARK",_("提醒人员")=>"TO_NAME",_("离职原因")=>"QUIT_REASON",_("离职当月薪资")=>"SALARY");
    $fieldAttr=array(_("申请日期")=>"date",_("拟离职日期")=>"date",_("实际离职日期")=>"date",_("工资截止日期")=>"date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title,$fieldAttr);


$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
    $user_info_arr[$row['BYNAME']] = $row;
}

$MSG_ERROR = "";
$rows=0;
$UPDATE_COUNT=0;
$INSERT_COUNT=0;
while($DATA = $objExcel->getNextRow()){
    $success = 1;
    $rows++;
    if($DATA['USER_ID']==""&&$DATA['LEAVE_PERSON']=="")
    {
        $MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名与离职人员两项必须填写一项"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }else if(!$DATA['USER_ID']=="")
    {
        if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
        {
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
        else
        {
            $DATA['LEAVE_PERSON'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
        }
    }else if($DATA['USER_ID']=="" && $DATA['LEAVE_PERSON']!="")
    {
        $query="select USER_ID from USER where USER_NAME='".$DATA['LEAVE_PERSON']."' limit 1";
        $cur= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cur))
        {
            $DATA['LEAVE_PERSON']=$ROW['USER_ID'];
        }else
        {
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
    }

    $sql="select DEPT_ID from user where USER_ID='".$DATA['LEAVE_PERSON']."'";
    $cure=exequery(TD::conn(),$sql);
    if($ROW1=mysql_fetch_array($cure)){
        if($ROW1['DEPT_ID'] == 0){
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，该用户不在职！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }else{
            $DATA["LEAVE_DEPT"] = $ROW1['DEPT_ID'];
            $sql="select DEPT_NAME from department where DEPT_ID=".$DATA["LEAVE_DEPT"];
            $cure=exequery(TD::conn(),$sql);
            if($ROW1=mysql_fetch_array($cure)){
                $LEAVE_DEPT_NAME = $ROW1['DEPT_NAME'];
            }
        }
    }

    if($DATA['APPLICATION_DATE'] !="" && !is_date($DATA['APPLICATION_DATE'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，申请离职日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }
    if($DATA['QUIT_TIME_PLAN'] !="" && !is_date($DATA['QUIT_TIME_PLAN'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，拟离职日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    if($DATA['QUIT_TIME_FACT']!="" && !is_date($DATA['QUIT_TIME_FACT'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，实际离职日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    if($DATA['LAST_SALARY_TIME'] !="" && !is_date($DATA['LAST_SALARY_TIME'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，工资截止日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }
    if($DATA['APPLICATION_DATE'] > $DATA['QUIT_TIME_FACT']&&$DATA['APPLICATION_DATE'] > $DATA['QUIT_TIME_PLAN']&&$DATA['APPLICATION_DATE']!=""&&$DATA['QUIT_TIME_PLAN']!=""&&$DATA['QUIT_TIME_FACT']!=""){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，实际离职日期和拟离职日期都不能小于申请离职日期"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }
    if($DATA['APPLICATION_DATE'] > $DATA['QUIT_TIME_PLAN']&&$DATA['QUIT_TIME_PLAN']!=""&&$DATA['APPLICATION_DATE']!=""){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，拟离职日期不能小于申请离职日期"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }
    if($DATA['APPLICATION_DATE'] > $DATA['QUIT_TIME_FACT']&&$DATA['QUIT_TIME_FACT']!=""&&$DATA['APPLICATION_DATE']!=""){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，实际离职日期不能小于申请离职日期"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }


    $GET_QUIT_TYPE_FLAG = FALSE;
    if($DATA['QUIT_TYPE']!=""){
        $sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_LEAVE'";
        $cure=exequery(TD::conn(),$sql);
        while($ROW1=mysql_fetch_array($cure)){
            if($DATA['QUIT_TYPE']==$ROW1['CODE_NAME']){
                $DATA['QUIT_TYPE']=$ROW1['CODE_NO'];
                $GET_QUIT_TYPE_FLAG = TRUE;
                break;
            }
        }
        if(!$GET_QUIT_TYPE_FLAG){
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为系统中不存在您提交的离职类型!"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
    }else{
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，离职类型不能为空!"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    $SMS_REMIND = $DATA['TO_NAME'];
    unset($DATA['TO_NAME']);
    unset($title['提醒人员']);
    unset($title['TO_NAME']);
    reset($title);
    $STR_KEY="";
    $STR_VALUE="";
    $UPDATE_STR="";
    foreach($title as $key){
        if(find_id($ID_STR, $key))
            continue;
        $value=ltrim($DATA[$key]);
        if($key!="USER_ID"){
            $STR_KEY .= $key . ",";
            $STR_VALUE .= "'$value'" . ",";
            $UPDATE_STR .= $key."='$value',";
        }
    }

    if (substr($STR_KEY,-1)==",")
        $STR_KEY=substr($STR_KEY,0,-1);
    if (substr($STR_VALUE,-1)==",")
        $STR_VALUE=substr($STR_VALUE,0,-1);
    if (substr($UPDATE_STR, - 1) == ",")
        $UPDATE_STR = substr($UPDATE_STR, 0, - 1);

    $array = explode(",",$STR_VALUE);
    if($array[0]=="''") continue;

    if($success==1)
    {
        $query="select DEPT_ID,USER_NAME from USER where USER_ID='".$DATA['LEAVE_PERSON']."'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $DEPT_ID    = $ROW["DEPT_ID"];
            $USER_NAME  = $ROW["USER_NAME"];
        }

        $CUR_TIME = date("Y-m-d H:i:s", time());
        $query = "insert into hr_staff_leave (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
        $INSERT_COUNT++;
        exequery(TD::conn(), $query);
        $TRANSFER_ID = mysql_insert_id();

        $query="update USER set DEPT_ID='0',NOT_LOGIN='1',NOT_MOBILE_LOGIN='1' where USER_ID='".$DATA['LEAVE_PERSON']."'";
        exequery(TD::conn(),$query);

        //set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

        //cache_users();
        $WORK_STATUS=$DATA["QUIT_TYPE"]==""?"":'0'.($DATA["$QUIT_TYPE"]+1);
        $query="select * from HR_STAFF_INFO where USER_ID='".$DATA["LEAVE_PERSON"]."'";
        $cursor= exequery(TD::conn(),$query);
        if(!$ROW=mysql_fetch_array($cursor))
            $query="insert into HR_STAFF_INFO(CREATE_USER_ID,CREATE_DEPT_ID,USER_ID,DEPT_ID,STAFF_NAME,WORK_STATUS) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','".$DATA["LEAVE_PERSON"]."','".$DATA["LEAVE_DEPT"]."','".$USER_NAME."','$WORK_STATUS')";
        else
            $query="update HR_STAFF_INFO  set DEPT_ID='".$DATA["LEAVE_DEPT"]."', WORK_STATUS='$WORK_STATUS' where USER_ID='".$DATA["LEAVE_PERSON"]."'";
        exequery(TD::conn(),$query);

        //记录系统日志
        add_log(23,$USER_NAME._("办理离职"),$_SESSION["LOGIN_USER_ID"]);
        //事务提醒相关用户
        if(!empty($SMS_REMIND)){
            $TO_NAME_ARR = explode(",",$SMS_REMIND);
            $TO_NAME_STR = "'".implode("','",$TO_NAME_ARR)."'";
            $query="select USER_ID from USER where USER_NAME in(".$TO_NAME_STR.")";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor)){
                $TO_ID_ARR[] = $ROW["USER_ID"];
            }
            $TO_ID_STR = implode(",",$TO_ID_ARR);
            $SMS_CONTENT=_("员工").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")已办理离职手续!");
            send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,64,$SMS_CONTENT,"ipanel/user/user_info.php?USER_ID=".$DATA['LEAVE_PERSON'],$DATA['LEAVE_PERSON']);
        }
    }
}
if(file_exists($EXCEL_FILE))
    @unlink($EXCEL_FILE);

if($INSERT_COUNT>0)
    $MESSAGE=sprintf(_("共%s条数据导入！"), $INSERT_COUNT);
if($UPDATE_COUNT>0)
    $MESSAGE=sprintf(_("共%s条数据更新！"), $UPDATE_COUNT);
if($INSERT_COUNT<=0 && $UPDATE_COUNT<=0)
    $MESSAGE=_("导入失败！");
Message("", $MESSAGE);
echo $MSG_ERROR;
?>
<div align="center">
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='pre_import.php';" title="<?=_("返回")?>">
</div>
</body>
</html>
