<?
/**
 * 办公用品申领处理页面
 */
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/flow_hook.php");
include_once("inc/header.inc.php");
include_once("../function_type.php");


$TO_ID = $_SESSION["LOGIN_USER_ID"];
$TRANS_QTY_FLOW = $TRANS_QTY; 

//办公用品引擎
$office_hmodule = "office_product_draw";

$USER_HOOK = 0; 
$query  = "SELECT * FROM flow_hook WHERE hmodule='$office_hmodule' and status > 0";
$cursor = exequery(TD::conn(),$query);
if($row = mysql_fetch_array($cursor))
{
    $USER_HOOK = 1;
}
if($USER_HOOK==1)
{
    $DEPT_MANAGER = "";
}

if($TRANS_FLAG=="1" or $TRANS_FLAG=="3")
{
    $TRANS_QTY1 = $TRANS_QTY*(-1);
}
//echo $TRANS_QTY;exit;
$TRANS_DATE=date("Y-m-d",time());

$query= "SELECT a.PRO_AUDITER, a.PRO_NAME,a.PRO_PRICE, c.MANAGER, c.PRO_KEEPER FROM office_products a left outer join office_type b on a.OFFICE_PROTYPE = b.ID left outer join office_depository c on b.TYPE_DEPOSITORY = c.ID WHERE a.PRO_ID='$PRO_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PRO_AUDITER = $ROW["PRO_AUDITER"];//审批权限用户
    $PRO_NAME    = $ROW["PRO_NAME"];   //办公用品名称
    $MANAGER     = $ROW["MANAGER"];    //仓库管理员
    $PRO_KEEPER  = $ROW["PRO_KEEPER"]; //物品调度员
    $PRO_PRICE   = $ROW["PRO_PRICE"]; //物品单价
}
if($DEPT_MANAGER!="")
{
    $DEPT_STATUS=0;
}
else
{
    $DEPT_STATUS=1;
}
   
if(empty($DEPT_MANAGER))
{
    $a=1;
}else
{
    $a=0;
}
if($PRO_ID=='' && isset($_POST['project-id']))
{
    $PRO_ID = $_POST['project-id'];
}
if($TRANS_FLAG==1)//获取办公用品类型 1是领用2是借用
{
    $query="SELECT OFFICE_PRODUCT_TYPE FROM office_products WHERE PRO_ID = '{$PRO_ID}'";
    $cursor = exequery ( TD::conn (), $query );
    while ( $ROW = mysql_fetch_array ( $cursor ) )
    {
        $OFFICE_PRODUCT_TYPE=$ROW['OFFICE_PRODUCT_TYPE'];
    }
    //$TRANS_QTY=-$TRANS_QTY;
}
if($OFFICE_PRODUCT_TYPE!="1" && $OFFICE_PRODUCT_TYPE!="2")
{
    Message(_("错误"),_("办公用品类别信息有误！"));
    Button_Back();
    exit;
}


$arr=array();

$arr=get_depository_id($PRO_ID);
if(!empty($office_type)&&$office_type==1)//修改个人待审批申请记录
{
    
    $query="UPDATE office_transhistory SET REMARK='{$REMARK}',FACT_QTY='{$TRANS_QTY}',TRANS_QTY='{$TRANS_QTY1}' ,pro_id='{$PRO_ID}' ,borrower='{$_SESSION["LOGIN_USER_ID"]}',DEPT_MANAGER='{$DEPT_MANAGER}',dept_status='$a',pro_keeper='{$arr['pro_keeper']}' where trans_id='{$office_id}'";
    
}
else
{
    //借用
    if($TRANS_FLAG==1 && $OFFICE_PRODUCT_TYPE==2)
    {
        $sql2="select * from office_transhistory where PRO_ID='$PRO_ID' and BORROWER='$TO_ID' and TRANS_FLAG='$OFFICE_PRODUCT_TYPE' and (TRANS_STATE='0' or DEPT_STATUS='0')";
        $re1=exequery(TD::conn(),$sql2);
        if(mysql_affected_rows()>0)
        {
             Message(_("错误"),_("借用申请正在处理中,无法重复登记"));
             Button_Back();
             exit;
        }else
        {
            $query="INSERT INTO office_transhistory(pro_id,borrower,trans_flag,remark,trans_date,trans_state,trans_qty,dept_id,dept_manager,dept_status,pro_keeper,fact_qty,price)
    VALUES('{$PRO_ID}','{$_SESSION['LOGIN_USER_ID']}','{$OFFICE_PRODUCT_TYPE}','{$REMARK}',current_date(),0,'{$TRANS_QTY1}','{$_SESSION["LOGIN_DEPT_ID"]}','{$DEPT_MANAGER}',$a,'{$arr['pro_keeper']}','{$TRANS_QTY}','$PRO_PRICE')";
        }    
    }
    elseif($TRANS_FLAG==1 && $OFFICE_PRODUCT_TYPE==1)//申领
    {
        $query= "SELECT PRO_PRICE,PRO_NAME FROM office_products WHERE PRO_ID='$PRO_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW = mysql_fetch_array($cursor))
        {
            $PRO_PRICE = $ROW['PRO_PRICE'];
            $PRO_NAME = $ROW['PRO_NAME'];
           
        }
        $query="INSERT INTO office_transhistory(pro_id,borrower,trans_flag,remark,trans_date,trans_state,trans_qty,dept_id,dept_manager,dept_status,pro_keeper,OPERATOR,fact_qty,price)
    VALUES ('{$PRO_ID}','{$_SESSION['LOGIN_USER_ID']}','{$OFFICE_PRODUCT_TYPE}','{$REMARK}',current_date(),0,'{$TRANS_QTY1}','{$_SESSION['LOGIN_DEPT_ID']}','{$DEPT_MANAGER}',$a,'{$arr['pro_keeper']}','{$_SESSION['LOGIN_USER_ID']}','{$TRANS_QTY}','$PRO_PRICE')";
    }
} 
$cursor = exequery(TD::conn(), $query);
$status=0;
$office_transhistory_id = mysql_insert_id();


$module="office_product_draw";

$DEPT_MANAGER_NAME = td_trim(GetUserNameById($DEPT_MANAGER));

$data_array=array("KEY"=>$office_transhistory_id,"field"=>"TRANS_ID","PRO_ID"=>$PRO_ID,"PRO_NAME"=>$PRO_NAME,"BORROWER"=>$_SESSION["LOGIN_USER_NAME"],"BORROWER_ID"=>$_SESSION["LOGIN_USER_ID"],"REMARK"=>$REMARK,"TRANS_QTY"=>$TRANS_QTY_FLOW,"DEPT_MANAGER"=>$DEPT_MANAGER,"DEPT_MANAGER_NAME"=>$DEPT_MANAGER_NAME);
$config= array("module"=>"$module");

run_hook($data_array,$config);

if($status==0)
{
    //部门审批人
    if($DEPT_MANAGER!="")
    {
         $REMIND_URL="1:office_product/dept_approval/pending_list.php";
         $SMS_CONTENT=sprintf(_("请审批%s的办公用品%s申请。"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME);
         send_sms("",$_SESSION["LOGIN_USER_ID"],$DEPT_MANAGER,43,$SMS_CONTENT,$REMIND_URL);  
    }else
    {
        if($PRO_AUDITER!="")//审批权限用户
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("请审批%s的办公用品%s申请。"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$PRO_AUDITER,43,$SMS_CONTENT,$REMIND_URL);
        }   
        if($PRO_AUDITER=="" && $MANAGER!="")//仓库管理员
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("请审批%s的办公用品%s申请。"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER,43,$SMS_CONTENT,$REMIND_URL);   
        }
        if($PRO_AUDITER=="" && $MANAGER=="")
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("请审批%s的办公用品%s申请。"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME);
            send_sms("",$_SESSION["LOGIN_USER_ID"], $arr['pro_keeper'],43,$SMS_CONTENT,$REMIND_URL);
        }
    }
    Message(_("提示"), _("您的申请登记已提交"));
    
}
if($office_type!=1){
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="javascrtpt:window.location.href='apply_one.php'"></center>
<?
}else
{
?>
<br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="javascrtpt:window.location.href='apply_list.php?curpage=<?=$curpage?>'"></center>
<?
}
?>