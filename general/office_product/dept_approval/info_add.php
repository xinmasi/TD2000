<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("../function_type.php");
include_once("inc/utility_sms1.php");

$query="update office_transhistory set REASON='{$REASON}'";

if($dept_status==1)
{
    $query.=",TRANS_STATE=2";
    $approval = _("办公用品管理员");
}
else
{
    $query.=",DEPT_STATUS=2";
    $approval = _("部门审批人");
}

if($RETURN_DATE =="0000-00-00")
{
    if(!empty($TRANS_ID))
    {
        $query.=" where trans_id='{$TRANS_ID}'";
        $cursor = exequery(TD::conn(),$query);
        
        $SMS_CONTENT = sprintf(_("%s:%s没有批准您的办公用品:%s的申请。理由:%s"),$approval,$_SESSION["LOGIN_USER_NAME"],$pro_name,$REASON);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$borrower,43,$SMS_CONTENT,"office_product/apply/apply_list.php");
        
        //验证是否是复审批的
        if(isset($repeat) && $repeat == "repeat")
        {
            $sql = "SELECT FACT_QTY,PRO_ID FROM office_transhistory WHERE TRANS_ID = '$TRANS_ID'";
            $cursor = exequery(TD::conn(),$sql);
            $arr = mysql_fetch_array($cursor);
            
            $FACT_QTY = abs($arr['FACT_QTY']);
            $upsql = "UPDATE office_products SET PRO_STOCK=PRO_STOCK+'$FACT_QTY' WHERE PRO_ID = '{$arr[PRO_ID]}'";
            exequery(TD::conn(),$upsql);
        }        
    }
    else
    {
        $pro_id_str   = substr($pro_id_str,0,-1);
        $trans_id_str = substr($trans_id_str,0,-1);
        $pro_name_str = substr($pro_name_str,0,-1);
        
        $trans_id_array = explode(",",$trans_id_str);
        
        for($i=0;$i<count($trans_id_array);$i++)
        {
            $query1 = $query." where CYCLE_NO='{$CYCLE_NO}' and TRANS_ID = '{$trans_id_array[$i]}'";
            $cursor = exequery(TD::conn(),$query1);
        }
        
        $SMS_CONTENT = sprintf(_("%s:%s没有批准您的办公用品:%s的申请。"),$approval,$_SESSION["LOGIN_USER_NAME"],$pro_name_str);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$borrower,43,$SMS_CONTENT,"office_product/apply/apply_list.php");
        
    }
}else
{
    if(!empty($TRANS_ID))
    {
        $sql = "UPDATE office_transhistory SET RETURN_STATUS=2,RETURN_REASON='{$REASON}' WHERE TRANS_ID = '$TRANS_ID'";
        $cursor = exequery(TD::conn(),$sql);
        
        $SMS_CONTENT = sprintf(_("%s没有批准您的办公用品:%s的归还申请。"),$_SESSION["LOGIN_USER_NAME"],$pro_name);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$borrower,43,$SMS_CONTENT,"office_product/apply/apply_list.php");
    }
    else
    {
        $trans_id_str = substr($trans_id_str,0,-1);
        $pro_name_str = substr($pro_name_str,0,-1);
        
        $trans_id_array = explode(",",$trans_id_str);
        for($i=0;$i<count($trans_id_array);$i++)
        {
            $sql = "UPDATE office_transhistory SET RETURN_STATUS=2,RETURN_REASON='{$REASON}' WHERE TRANS_ID = '{$trans_id_array[$i]}'";
            $cursor = exequery(TD::conn(),$sql);
        }
        $SMS_CONTENT = sprintf(_("%s没有批准您的办公用品:%s的归还申请。"),$_SESSION["LOGIN_USER_NAME"],$pro_name_str);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$borrower,43,$SMS_CONTENT,"office_product/apply/apply_list.php");
    }
}


if($cursor)
{
    header("Location: pending_list.php");
}
?>
