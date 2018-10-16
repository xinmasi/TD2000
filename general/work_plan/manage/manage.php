<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");
?>




<body class="bodycolor">

<?

$END_DATE=$CUR_DATE=date("Y-m-d",time());
$END_DATE=date("Y-m-d",time());

if($OPERATION==1)
   $query="update WORK_PLAN set BEGIN_DATE='$CUR_DATE',PUBLISH='1' where PLAN_ID='$PLAN_ID'";
else if($OPERATION==2)
   $query="update WORK_PLAN set END_DATE='$END_DATE',SUSPEND_FLAG='1' where PLAN_ID='$PLAN_ID'";
else if($OPERATION==4)
   $query="update WORK_PLAN set SUSPEND_FLAG='0' where PLAN_ID='$PLAN_ID'";
else if($OPERATION==5)
   $query="update WORK_PLAN set SUSPEND_FLAG='1' where PLAN_ID='$PLAN_ID'";
else
   $query="update WORK_PLAN set END_DATE='0000-00-00' where PLAN_ID='$PLAN_ID'";  
  
exequery(TD::conn(),$query);

if($OPERATION==1)
{   
    $CONTENT = iconv("UTF-8","gbk","发布新的工作计划，请注意查看，计划名称："); 
    $query1="SELECT * FROM WORK_PLAN WHERE PLAN_ID='$PLAN_ID'";
    $cursor = exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor))
    {
        $CREATOR = $ROW['CREATOR'];
        $NAME = $ROW['NAME'];
        $WORK_PLAN_ID = $ROW['PLAN_ID'];
        $TO_ID = $ROW['TO_ID'];
        $TO_PERSON_ID = $ROW['TO_PERSON_ID'];
    }
    if($TO_ID == 'ALL_DEPT')
    { 
        $query="SELECT DEPT_ID FROM DEPARTMENT";
        $cursor = exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $DEPT_ID[] = $ROW['DEPT_ID'];
        }
        
        foreach($DEPT_ID as $v)
        {
            $query="SELECT USER_ID FROM USER WHERE DEPT_ID = '$v'";
            $cursor = exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
            {
               
                $USER_ID[] = $ROW['USER_ID'];
            }
        }
        $TO_ID_STR=implode(",",$USER_ID);
        $LOGIN_USER_ID = $_SESSION['LOGIN_USER_ID'];
        $query="SELECT USER_NAME FROM USER WHERE USER_ID = '$LOGIN_USER_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $USER_NAME = $ROW['USER_NAME'];
        }
        $CONTENT = iconv("UTF-8","gbk","发布新的工作计划，请注意查看，计划名称："); 
        $REMIND_URL="1:work_plan/show/plan_detail.php?PLAN_ID=".$WORK_PLAN_ID;
        $SMS_CONTENT = sprintf(_("%s $CONTENT%s"),$USER_NAME,$NAME);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,12,$SMS_CONTENT,$REMIND_URL,$WORK_PLAN_ID);
    }
    
    if($TO_ID != 'ALL_DEPT' and $TO_ID != "")
    {
        $TO_ID = explode(",",$TO_ID);
        array_pop($TO_ID);
        foreach($TO_ID as $v)
        {
            $query="SELECT USER_ID FROM USER WHERE DEPT_ID = $v";
            $cursor = exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $USER_ID[] = $ROW['USER_ID'];
            }
        }
        $TO_ID_STR=implode(",",$USER_ID);
        $LOGIN_USER_ID = $_SESSION['LOGIN_USER_ID'];
        $query="SELECT USER_NAME FROM USER WHERE USER_ID = '$LOGIN_USER_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $USER_NAME = $ROW['USER_NAME'];
        }
        $REMIND_URL="1:work_plan/show/plan_detail.php?PLAN_ID=".$WORK_PLAN_ID;
        $SMS_CONTENT=sprintf(_("%s$CONTENT%s"),$USER_NAME,$NAME);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,12,$SMS_CONTENT,$REMIND_URL,$WORK_PLAN_ID);
    }
    if($TO_PERSON_ID != " ")
    {   
        $LOGIN_USER_ID = $_SESSION['LOGIN_USER_ID'];
        $query="SELECT USER_NAME FROM USER WHERE USER_ID = '$LOGIN_USER_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $USER_NAME = $ROW['USER_NAME'];
        }
        $REMIND_URL="1:work_plan/show/plan_detail.php?PLAN_ID=".$WORK_PLAN_ID;
        $SMS_CONTENT=sprintf(_("%s$CONTENT%s"),$USER_NAME,$NAME);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_PERSON_ID,12,$SMS_CONTENT,$REMIND_URL,$WORK_PLAN_ID);
    }
    
}

if($SEARCH_FLAG==1)
   header("location: search.php");
else
   header("location: index1.php?WORK_TYPE=$WORK_TYPE");
?>

</body>
</html>
