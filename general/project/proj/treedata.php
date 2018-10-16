<?
/*
{
    "success": true,
     expanded: true,
     "PROJ_NAME": "My Root",
     
    "children":[
        { "PROJ_NAME": "User 1" ,leaf:true},
        { "PROJ_NAME": "User 2" ,expanded: true, children: [
            { PROJ_NAME: "GrandChild", leaf: true }
        ]}
    ]
}
*/
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/sys_code_field.php");
include_once("../setting/cust/sys/list/proj_list_config.php");
ob_end_clean();
$OUTPUT_ARRAY = array();
$CUR_DATE=date("Y-m-d",time());

$filter = json_decode(stripslashes($filter),true);
if(is_array($filter))
{
   foreach($filter as $key => $array)
   {
      if($array['property'])
         $$array['property'] = $array['value'];
   }
}
$PROJ_NAME = td_iconv($PROJ_NAME, "utf-8", MYOA_CHARSET);
$PROJ_OWNER = td_iconv($PROJ_OWNER, "utf-8", MYOA_CHARSET);

   $query_count = "select count(*) FROM PROJ_PROJECT WHERE 1=1";

   $query = "select * FROM PROJ_PROJECT WHERE 1=1";
 
//添加过时项目至进行中 
if(intval($STATUS) == 2)
    $query = "select * FROM PROJ_PROJECT WHERE 1=1 AND PROJ_STATUS = '2'";
	//$query = "select * FROM PROJ_PROJECT WHERE 1=1 AND PROJ_END_TIME > '" . $CUR_DATE . "'";

if($RANGE==0){
  $query_str .= " AND (".$_SESSION["LOGIN_USER_PRIV"]."=1 OR PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_MANAGER))";
  $query_z = $query_str;
}elseif($RANGE==1){
  $query_str .= " AND (".$_SESSION["LOGIN_USER_PRIV"]."=1 OR PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_MANAGER))";
  $query_z = $query_str;
}elseif($RANGE==2){
  $query_str .= " AND (FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER))";
  $query_z = $query_str;
}
if($PROJ_START_TIME)
  $query_str .= " and PROJ_START_TIME>='$PROJ_START_TIME'";
if($PROJ_END_TIME)
  $query_str .= " and PROJ_END_TIME<='$PROJ_END_TIME'";

if($STATUS == "ALL" || !isset($STATUS)){//所有未结束状态 by dq 090628
   $P_STATUS = "0,1,2,3,4,9";
}
else{
   if(!intval($STATUS))
      $P_STATUS = 0;
   else if($STATUS == 1)//9为临时位
	  $P_STATUS = "1,9";
   else
      $P_STATUS = $STATUS;
}
   
$P_STATUS = rtrim($P_STATUS,',');
$query_str .= " AND PROJ_STATUS in($P_STATUS)";


if($PROJ_TYPE!="ALL" && $PROJ_TYPE!="")
  $query_str .= " AND PROJ_TYPE='$PROJ_TYPE'";
  
if($PROJ_NUM!="")
  $query_str .= " AND PROJ_NUM LIKE '%".unescape($PROJ_NUM)."%'";
  
if($PROJ_NAME!="")
  $query_str .= " AND PROJ_NAME LIKE '%".unescape($PROJ_NAME)."%'";
  
if($PROJ_OWNER!="")
  $query_str .= " AND PROJ_OWNER='".unescape($PROJ_OWNER)."'";


$query_count = $query_count.$query_str;
 $cursor = exequery(TD::conn(),$query_count);
 if($ROW=mysql_fetch_array($cursor))
    $PROJ_COUNT=$ROW[0];

$query_str .=" ORDER BY PROJ_START_TIME DESC,PROJ_STATUS,PROJ_ID DESC";
$limit = $limit ? $limit : 10;
$start = $start ? $start :  0;
$query_data = $query.$query_str;

if(intval($STATUS) == 5)
    $query_data = "select * FROM PROJ_PROJECT WHERE 1=1 " . $query_z ." AND PROJ_END_TIME < '".date("Y-m-d" , time())."' AND PROJ_STATUS = 2";
    

    
$COUNT = 0;
$USER_NAME_ARRAY=array();
$cursor = exequery(TD::conn(),$query_data);
while($ROW=mysql_fetch_array($cursor))
{
   $PROJ_ARRAY = array();
	$COUNT++;
    $PROJ_ID=$ROW["PROJ_ID"];
    $PROJ_TYPE=$ROW["PROJ_TYPE"];
    $PROJ_NAME=$ROW["PROJ_NAME"];
    $PROJ_USER=$ROW["PROJ_USER"];
    $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
    $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
    $PROJ_OWNER=$ROW["PROJ_OWNER"];
    $PROJ_STATUS=$ROW["PROJ_STATUS"];
	//9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均吧状态9作为状态1
	if($PROJ_STATUS == 9){
		$PROJ_STATUS = 1;
		$ROW["PROJ_STATUS"] = 1;
	}	
    $PROJ_END_TIME=$ROW["PROJ_END_TIME"];
    //项目权限检测
    $PROJ_USER=str_replace("|","",$PROJ_USER);
    if(!find_id($PROJ_USER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"] && !find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"])
       continue;
   
    //判断项目成员的目录权限
    if(!find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"] && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"])
    {
    	$query1 = "select 1 from PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGE_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',NEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MODIFY_USER) )";
      $cursor1= exequery(TD::conn(),$query1);
      if(!mysql_fetch_array($cursor1))
         continue;
    }
    
    $PROJ_NAME=td_htmlspecialchars($PROJ_NAME);
    $PROJ_NAME=str_replace("\"","&quot;",$PROJ_NAME);
    $url = "detail.php?PROJ_ID=".$PROJ_ID;
//    if(!is_array($PROJ_ARRAY[$PROJ_TYPE])){
//       $PROJ_ARRAY[$PROJ_TYPE] = array(//分类
//           "node" => $PROJ_TYPE,
//           "PROJ_NAME" => $PROJ_TYPE,
//           "isFolder" => true,
//           "isLazy" => true,
//           "expanded" => true,
//           "icon" => $FOLDER_IMG,
//           "children" => array(),
//           "target" => "page"
//       );
//   }
$PROJ_ARRAY = array(
//   $PROJ_ARRAY[$PROJ_TYPE]['children'][] = array(
          // "PROJ_ID" => $PROJ_ID,
           "id" => $PROJ_ID,
           "PROJ_NAME" => $PROJ_NAME,
         //  "PROJ_TYPE"=> $PROJ_TYPE,
           "PROJ_STATUS" => $PROJ_STATUS,
          // "isFolder" => false,
           "leaf" => true,
           //"isLazy" => false,
          // "icon" => $FOLDER_IMG,
          // "url" => $url,
         //  "target" => "page"
   );
	if($PROJ_STATUS==2 && compare_date($CUR_DATE,$PROJ_END_TIME)==1)
	   $PROJ_ARRAY['TIME_OUT'] = 1;
   $OUTPUT_ARRAY[] = $PROJ_ARRAY;
}

$O_ARRAY = array("expanded"=> true,'children'=> $OUTPUT_ARRAY);

echo array_to_json($O_ARRAY);
