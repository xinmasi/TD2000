<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");

$PRO_AUDITER1="";
$MANAGER1="";


if($TRANS_FLAG=="1")
    $TRANS_FLAG_NAME=_("领用");

if($TRANS_FLAG=="2")
    $TRANS_FLAG_NAME=_("借用");

if($TRANS_FLAG=="3")
    $TRANS_FLAG_NAME=_("归还");


$query="update OFFICE_TRANSHISTORY set DEPT_STATUS='$DEPT_STATUS' where CYCLE_NO='$CYCLE_NO'";
exequery(TD::conn(),$query);


$query="select TRANS_ID,PRO_ID,TRANS_STATE,BORROWER from OFFICE_TRANSHISTORY where CYCLE_NO='$CYCLE_NO'";
//echo $query;exit;
$cursor=exequery(TD::conn(), $query);

while($ROW=mysql_fetch_array($cursor))
{
    $TRANS_ID=$ROW["TRANS_ID"];
    $PRO_ID=$ROW["PRO_ID"];
    $TRANS_STATE=$ROW["TRANS_STATE"];
    $BORROWER=$ROW["BORROWER"];

    $query1= "select a.PRO_AUDITER,a.PRO_NAME,c.MANAGER,c.PRO_KEEPER from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY= c.ID where a.PRO_ID='$PRO_ID'";
    //echo $query1."<br>";//exit;
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        if (!find_id($MANAGER1, td_trim($ROW1["MANAGER"])))
            $MANAGER1.=$ROW1["MANAGER"];
        if (!find_id($PRO_AUDITER1, td_trim($ROW1["PRO_AUDITER"])))
            $PRO_AUDITER1.=$ROW1["PRO_AUDITER"];
        //$PRO_AUDITER=$ROW["PRO_AUDITER"];
        //$MANAGER=$ROW["MANAGER"];
    }
    //echo $MANAGER1."<br>";
}

$BORROWER_NAME=td_trim(GetUserNameById($BORROWER)) ;

if ($DEPT_STATUS=="1")
{
    if($TRANS_STATE==0)
    {
        if($PRO_AUDITER1!="")
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("部门领导%s已批准%s的办公用品%s申请，请批示!"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_FLAG_NAME);//_("请审批%s的办公用品%s申请。")
            send_sms("",$BORROWER,$PRO_AUDITER1,43,$SMS_CONTENT,$REMIND_URL);
        }

        if($PRO_AUDITER1=="" && $MANAGER1!="")
        {
            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
            $SMS_CONTENT=sprintf(_("部门领导%s已批准%s的办公用品%s申请，请批示!"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_FLAG_NAME);

            send_sms("",$BORROWER,$MANAGER1,43,$SMS_CONTENT,$REMIND_URL);
        }
    }
}else{
    $SMS_CONTENT=sprintf(_("%s没有批准您的办公用品%s申请。"),$_SESSION["LOGIN_USER_NAME"],$TRANS_FLAG_NAME);
    send_sms("", $_SESSION["LOGIN_USER_ID"],$BORROWER,43,$SMS_CONTENT,"office_product/apply/apply_list.php");
}
header("location: query.php?DEPT_STATUS=0");
?>