<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");


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
    $title=array("USER_ID"=>"USER_ID","TRANSFER_PERSON"=>"TRANSFER_PERSON","TRANSFER_TYPE"=>"TRANSFER_TYPE","TRANSFER_DATE"=>"TRANSFER_DATE","TRANSFER_EFFECTIVE_DATE"=>"TRANSFER_EFFECTIVE_DATE","TRAN_COMPANY_BEFORE"=>"TRAN_COMPANY_BEFORE","TRAN_COMPANY_AFTER"=>"TRAN_COMPANY_AFTER","TRAN_POSITION_BEFORE"=>"TRAN_POSITION_BEFORE","TRAN_POSITION_AFTER"=>"TRAN_POSITION_AFTER","TRAN_DEPT_BEFORE"=>"TRAN_DEPT_BEFORE","TRAN_DEPT_AFTER"=>"TRAN_DEPT_AFTER","MATERIALS_CONDITION"=>"MATERIALS_CONDITION","REMARK"=>"REMARK","SMS_REMIND"=>"SMS_REMIND","TRAN_REASON"=>"TRAN_REASON");
    $fieldAttr=array("TRANSFER_DATE"=>"date","TRANSFER_EFFECTIVE_DATE"=>"date");
}else{
    $title=array(_("用户名")=>"USER_ID",_("调动人员")=>"TRANSFER_PERSON",_("调动类型")=>"TRANSFER_TYPE",_("调动日期")=>"TRANSFER_DATE",_("调动生效日期")=>"TRANSFER_EFFECTIVE_DATE",_("调动前单位")=>"TRAN_COMPANY_BEFORE",_("调动后单位")=>"TRAN_COMPANY_AFTER",_("调动前职务")=>"TRAN_POSITION_BEFORE",_("调动后职务")=>"TRAN_POSITION_AFTER",_("调动前部门")=>"TRAN_DEPT_BEFORE",_("调动后部门")=>"TRAN_DEPT_AFTER",_("调动手续办理")=>"MATERIALS_CONDITION",_("备注")=>"REMARK",_("发送事务提醒")=>"SMS_REMIND",_("调动原因")=>"TRAN_REASON");
    $fieldAttr=array(_("调动日期")=>"date",_("调动生效日期")=>"date");
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
    if($DATA['USER_ID']==""&&$DATA['TRANSFER_PERSON']==""){
        $MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名与调动人员两项必须填写一项"), $rows)."</font><br/>";
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
            $DATA['TRANSFER_PERSON'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
        }
    }
    else if($DATA['USER_ID']=="" && $DATA['TRANSFER_PERSON']!="")
    {
        $query="select USER_ID from USER where USER_NAME='".$DATA['TRANSFER_PERSON']."' limit 1";
        $cur= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cur))
        {
            $DATA['TRANSFER_PERSON']=$ROW['USER_ID'];
        }else
        {
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
    }

    if($DATA['TRAN_DEPT_AFTER']==""){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为调动后部门不能为空!"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    if($DATA['TRANSFER_DATE']!="" && !is_date($DATA['TRANSFER_DATE'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，调动日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    if($DATA['TRANSFER_EFFECTIVE_DATE'] !="" && !is_date($DATA['TRANSFER_EFFECTIVE_DATE'])){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，调动生效日期应为日期型，如：1999-01-01"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    if($DATA['TRANSFER_DATE'] > $DATA['TRANSFER_EFFECTIVE_DATE']){
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，调动生效日期不能小于调动日期"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    $TRANSFER_TYPE_FLAG = false;
    if($DATA['TRANSFER_TYPE']!=""){
        $sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_TRANSFER'";
        $cure=exequery(TD::conn(),$sql);
        while($ROW1=mysql_fetch_array($cure)){
            if($DATA['TRANSFER_TYPE']==$ROW1['CODE_NAME']){
                $DATA['TRANSFER_TYPE']=$ROW1['CODE_NO'];
                $TRANSFER_TYPE_FLAG = true;
                break;
            }
        }
        if(!$TRANSFER_TYPE_FLAG){
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为系统中不存在您提交的调动类型!"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
    }else{
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，调动类型不能为空!"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    $sql="select DEPT_ID from user where USER_ID='".$DATA['TRANSFER_PERSON']."'";
    $cure=exequery(TD::conn(),$sql);
    if($ROW1=mysql_fetch_array($cure)){
        $DATA['TRAN_DEPT_BEFORE'] = $ROW1['DEPT_ID'];
    }else{
        $DATA['TRAN_DEPT_BEFORE'] = '';
    }

    $sql="select DEPT_ID from user where USER_ID='".$DATA['TRANSFER_PERSON']."'";
    $cure=exequery(TD::conn(),$sql);
    if($ROW1=mysql_fetch_array($cure)){
        if($ROW1['DEPT_ID'] == 0){
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，该用户不在职！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }else{
            $DATA['TRAN_DEPT_BEFORE'] = $ROW1['DEPT_ID'];
        }
    }

    $sql="select DEPT_ID,DEPT_NAME from department where DEPT_NAME='".$DATA['TRAN_DEPT_AFTER']."'";
    $cure=exequery(TD::conn(),$sql);
    if($ROW1=mysql_fetch_array($cure)){
        if($ROW1['DEPT_ID'] == $DATA['TRAN_DEPT_BEFORE']){
            $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，调动前部门与调动后部门不能相同！"), $rows)."</font><br/>";
            $success = 0;
            continue;
        }
        $DATA['TRAN_DEPT_AFTER'] = $ROW1['DEPT_ID'];
    }else{
        $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为系统中不存在您所输入的调动后部门!"), $rows)."</font><br/>";
        $success = 0;
        continue;
    }

    $SMS_REMIND = $DATA['SMS_REMIND'];
    unset($DATA['SMS_REMIND']);
    unset($title['发送事务提醒']);
    unset($title['SMS_REMIND']);
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

    if ($success==1){
        $CUR_TIME = date("Y-m-d H:i:s", time());
        $query = "insert into HR_STAFF_TRANSFER (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
        $INSERT_COUNT++;
        exequery(TD::conn(), $query);
        $TRANSFER_ID = mysql_insert_id();

        $query = "update USER SET DEPT_ID='".$DATA['TRAN_DEPT_AFTER']."' where USER_ID = '".$DATA['TRANSFER_PERSON']."'";
        exequery(TD::conn(),$query);

        //set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

        //cache_users();

        $query = "update  HR_STAFF_INFO SET DEPT_ID='".$DATA['TRAN_DEPT_AFTER']."',JOB_POSITION='".$DATA['TRAN_POSITION_AFTER']."' where USER_ID = '".$DATA['TRANSFER_PERSON']."'";
        exequery(TD::conn(),$query);

        //------------事务提醒-----------
        $query = "SELECT DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='".$DATA['TRAN_DEPT_BEFORE']."'";
        $cursor= exequery(TD::conn(),$query);
        $DEPT_HR_MANAGER1="";
        if($ROW=mysql_fetch_array($cursor))
            $DEPT_HR_MANAGER1 = $ROW["DEPT_HR_MANAGER"];
        $query = "SELECT DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='".$DATA['TRAN_DEPT_AFTER']."'";
        $cursor= exequery(TD::conn(),$query);
        $DEPT_HR_MANAGER2="";
        if($ROW=mysql_fetch_array($cursor))
            $DEPT_HR_MANAGER2 = $ROW["DEPT_HR_MANAGER"];
        $TMP_ARRAY = explode(",",$DEPT_HR_MANAGER1);
        for($I=0;$I<=count($TMP_ARRAY);$I++)
        {
            if($TMP_ARRAY[$I]!=""&&!find_id($DEPT_HR_MANAGER2,$TMP_ARRAY[$I]))
                $DEPT_HR_MANAGER2.=$TMP_ARRAY[$I].',';
        }

        if($SMS_REMIND=="是" && $DEPT_HR_MANAGER2!=""){
            $REMIND_URL="ipanel/hr/transfer_detail.php?TRANSFER_ID=".$TRANSFER_ID;
            $SMS_CONTENT=_("请查看人事调动信息");
            send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER2,56,$SMS_CONTENT,$REMIND_URL,$TRANSFER_ID);
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
