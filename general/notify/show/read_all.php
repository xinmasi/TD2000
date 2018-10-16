<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$CUR_TIME=date("Y-m-d H:i:s",time());
$WHERE_CLAUSE 	= " WHERE PUBLISH='1' ";
$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
$WHERE_CLAUSE  .= " AND NOT find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS)" ;
$WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT' 
													 OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) 
													 OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
													 OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
									. priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";
	
$query = "SELECT NOTIFY_ID FROM NOTIFY ".$WHERE_CLAUSE;
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $NOTIFY_ID=$ROW["NOTIFY_ID"];
   $Tquery = "insert into APP_LOG(USER_ID,TIME,MODULE,OPP_ID,TYPE) values ('".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','4','$NOTIFY_ID','1')";  
   exequery(TD::conn(),$Tquery);
}

$query = "update NOTIFY set READERS=concat(READERS,'".$_SESSION["LOGIN_USER_ID"].",')".$WHERE_CLAUSE;
exequery(TD::conn(),$query);
header("location:notify.php?IS_MAIN=1");
?>
