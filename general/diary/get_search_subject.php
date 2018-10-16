<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("get_diary_data.func.php");
ob_end_clean();
$keyword = td_iconv($keyword, "utf-8", MYOA_CHARSET);

$WHERE_STR="";
if(isset($startdate) && $startdate!="")
{	
    $WHERE_STR=" and DIA_DATE >= '$startdate' ";		
}
if(isset($enddate) && $enddate!="")
{	
    $WHERE_STR.=" and DIA_DATE <= '$enddate' ";		
}
if(isset($keyword) && $keyword!="")
{	
    $WHERE_STR.=" and  SUBJECT like '%$keyword%'";		
}
$TYPE=1;
$data=get_diaryselect_data($_SESSION["LOGIN_USER_ID"],$TYPE,$WHERE_STR);
echo retJson($data);
?>