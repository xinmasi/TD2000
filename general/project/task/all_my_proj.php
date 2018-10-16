<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
//列出我参与的项目
   $SETS = array();
if($queryStr)
{
   $query_str = " and PROJ_NAME like '%".unescape(iconv("UTF-8", MYOA_CHARSET,$queryStr))."%'";
}else{
   $array['PROJ_ID']=0;
   $array['PROJ_NAME']="---"._("所有项目")."---";
   $SETS[] = $array;
}
$count = 0;
$limit = $limit ? $limit : 10;
$start = $start ? $start : 0;

$query = "select PROJ_ID,PROJ_NAME FROM PROJ_PROJECT WHERE PROJ_STATUS IN (2,3) AND FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|',''))";
$query_count = "select count(PROJ_NAME) FROM PROJ_PROJECT WHERE PROJ_STATUS IN (2,3) AND FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|',''))";
$query .= $query_str;
$query_count .= $query_str;
$cursor = exequery(TD::conn(),$query_count);
if($ROW=mysql_fetch_array($cursor)){
   $count = $ROW[0];
}
$query .= " limit $start,$limit";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $array = array();
   $array['PROJ_ID']=$ROW["PROJ_ID"];
   $array['PROJ_NAME']=$ROW["PROJ_NAME"];
   $SETS[] = $array;
}
ob_clean();
echo array_to_json(array("count"=>$count,'datastr'=>$SETS));
?>