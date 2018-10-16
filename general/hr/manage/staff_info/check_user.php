<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$BYNAME = td_trim($BYNAME);
if(mb_detect_encoding($BYNAME,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "UTF-8"){
    $BYNAME = td_iconv($BYNAME,"UTF-8",MYOA_CHARSET);
}else{
    $BYNAME = stripslashes($BYNAME);
}
$check_str="";
$query = "SELECT * from USER where BYNAME='$BYNAME'";

$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $check_str="-ERR";
else
    $check_str="+OK";

if($check_str=="+OK")
{
    $query = "SELECT * from HR_STAFF_INFO where USER_ID='$BYNAME'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $check_str="-ERR1";
    else
        $check_str="+OK";
}
ob_end_clean();
if($check_str=="+OK")
    $check_str = $check_str.$BYNAME;
echo $check_str;
?>