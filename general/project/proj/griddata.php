<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/sys_code_field.php");
include_once("../setting/cust/sys/list/proj_list_config.php");
ob_clean();
$OUTPUT_ARRAY = array();
$STATUS_ARR=array("0"=>_("立项中"),"1"=>_("审批中"),"2"=>_("进行中"),"3"=>_("已结束"),"4"=>_("挂起中"));
$STATUS_COLOR=array("0"=>"#947BD1","1"=>"blue","2"=>"green","3"=>"red","4"=>"#6d9dd5");
$CUR_DATE=date("Y-m-d",time());

//$filter = json_decode(stripslashes($filter),true);
//if(is_array($filter))
//{
//   foreach($filter as $key => $array)
//   {
//      if($array['property'] && $array['value'])
//         $$array['property'] = $array['value'];
//   }
//}
$PROJ_NAME = td_iconv($PROJ_NAME, "utf-8", MYOA_CHARSET);
$PROJ_OWNER = td_iconv($PROJ_OWNER, "utf-8", MYOA_CHARSET);

   $query_count = "select count(*) FROM PROJ_PROJECT WHERE 1=1";
   $query = "select * FROM PROJ_PROJECT WHERE 1=1";

if($RANGE==0)
  $query_str .= " AND (".$_SESSION["LOGIN_USER_PRIV"]."=1 OR PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_MANAGER))";
elseif($RANGE==1)
  $query_str .= " AND (".$_SESSION["LOGIN_USER_PRIV"]."=1 OR PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_MANAGER))";
elseif($RANGE==2)
  $query_str .= " AND (FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) OR FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER))";

if($PROJ_START_TIME)
  $query_str .= " and PROJ_START_TIME>'$PROJ_START_TIME'";
if($PROJ_END_TIME)
  $query_str .= " and PROJ_END_TIME<'$PROJ_END_TIME'";

if($NO_HIDE_FINISHED == 'false')//显示已结束
{
  $query_str .= " and PROJ_STATUS!=3 ";
}

if($STATUS != "ALL")
{
   $query_str .= " and PROJ_STATUS='".intval($STATUS)."' ";
}
  
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
  
$query_str .=" ORDER BY PROJ_START_TIME DESC,PROJ_ID DESC";
$limit = $limit ? $limit : 10;
$start = $start ? $start :  0;

$query_str .= " limit $start,$limit";
$query_data = $query.$query_str;
$COUNT = 0;
$USER_NAME_ARRAY=array();
$cursor = exequery(TD::conn(),$query_data);
while($ROW=mysql_fetch_array($cursor))
{
   $PROJ_ARRAY = array();
	$COUNT++;
	$PROJ_ID = $ROW["PROJ_ID"];
	$PROJ_NUM = $ROW["PROJ_NUM"];
	$PROJ_NAME = $ROW["PROJ_NAME"];
	$PROJ_TYPE = $ROW["PROJ_TYPE"];
	$PROJ_OWNER = $ROW["PROJ_OWNER"];
	$PROJ_MANAGER = $ROW["PROJ_MANAGER"];
	$PROJ_START_TIME = $ROW["PROJ_START_TIME"];
	$PROJ_END_TIME = $ROW["PROJ_END_TIME"];
	$PROJ_ACT_END_TIME = $ROW["PROJ_ACT_END_TIME"];
	$PROJ_STATUS = $ROW["PROJ_STATUS"];
	//9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均吧状态9作为状态1
	if($PROJ_STATUS == 9){
		$PROJ_STATUS = 1;
		$ROW["PROJ_STATUS"] = 1;
	}
	if(!array_key_exists($PROJ_OWNER,$USER_NAME_ARRAY))
	{
	   $query1 = "SELECT USER_NAME FROM USER WHERE USER_ID='$ROW[PROJ_OWNER]'";
     $cursor1 = exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME_ARRAY["$PROJ_OWNER"] = $ROW["USER_NAME"];
  }
  $PROJ_OWNER_NAME =  $USER_NAME_ARRAY["$PROJ_OWNER"];
  
  if($PROJ_ACT_END_TIME=="0000-00-00" || $PROJ_ACT_END_TIME=="")
     $PROJ_ACT_END_TIME="-";
  
	$STATUS='<font color="'.$STATUS_COLOR[$PROJ_STATUS].'">'.$STATUS_ARR[$PROJ_STATUS].'</font>';
	if($PROJ_STATUS==2 && compare_date($CUR_DATE,$PROJ_END_TIME)==1)
	   $STATUS = '<font color="red">'._("已超时").'</font>';

  // if($_SESSION["LOGIN_USER_PRIV"]=="1")
   //   $OUTPUT_ARRAY[] = array('text'=>'<input type="checkbox" class="op_check" name="proj_select" value="'.$PROJ_ID.'">','dataIndex'=>'proj_select','width'=>'85','sortable'=>'false','hideable'=>'false');
      
   $PROJ_ARRAY['PROJ_ID'] = $PROJ_ID;
	if($LIST_ARRAY['PROJ_NUM']==1) {
      $PROJ_ARRAY['PROJ_NUM'] = $PROJ_NUM;
   }
   //$OUTPUT_ARRAY[] = array('text'=>'<a href="#" title="' . $PROJ_NAME . '" onclick=show_proj("'.$PROJ_ID.'")>'.(strlen($PROJ_NAME) > 50 ? csubstr($PROJ_NAME,0,50)."..." : $PROJ_NAME).'</a>','dataIndex'=>'PROJ_NAME','width'=>'85','sortable'=>'false','hideable'=>'false');
   //$PROJ_ARRAY['PROJ_NAME'] = $PROJ_NAME;
   $PROJ_ARRAY['PROJ_NAME'] = '<a href="#" title="'.$PROJ_NAME.'" onclick="show_proj(\''.$PROJ_ID.'\',\''.$PROJ_NAME.'\')">'.(strlen($PROJ_NAME) > 50 ? csubstr($PROJ_NAME,0,50)."..." : $PROJ_NAME).'</a>';
   if($LIST_ARRAY['PROJ_OWNER_NAME']==1) {
      $PROJ_ARRAY['PROJ_OWNER_NAME'] = $PROJ_OWNER_NAME;
   }
   if($LIST_ARRAY['PROJ_START_TIME']==1) {
      $PROJ_ARRAY['PROJ_START_TIME'] = $PROJ_START_TIME;
   }
   if($LIST_ARRAY['PROJ_END_TIME']==1) {
      $PROJ_ARRAY['PROJ_END_TIME'] = $PROJ_END_TIME;
   }
   if($LIST_ARRAY['PROJ_ACT_END_TIME']==1) {
      $PROJ_ARRAY['PROJ_ACT_END_TIME'] =$PROJ_ACT_END_TIME;
   }
	
	if($LIST_ARRAY['PROJ_GLOBAL_VAL']==1){
   	$ARRAY_GDATA =  proj_get_data_array($PROJ_TYPE,$PROJ_ID);
   	$ARRAY_SETTINGS = get_settings();
   	if(is_array($ARRAY_GDATA)) {
   		foreach($ARRAY_SETTINGS as $key => $value){
            $PROJ_ARRAY['extra_'.$key] = $ARRAY_GDATA[$value['FIELDNO']];
   		}	
   	}
   }
   $PROJ_ARRAY['PROJ_STATUS'] = $STATUS;
	if($_SESSION["LOGIN_USER_PRIV"]==1 || $PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER==$_SESSION["LOGIN_USER_ID"])
	{
		if($PROJ_STATUS==0){
			$OUTPUT='<a href="#" onclick="edit_proj(\''.$PROJ_ID.'\',\'0\',\''.$PROJ_NAME.'\')">'._("编辑").'</a>&nbsp;<a href="#" class="op_del" onclick="delete_proj(\''.$PROJ_ID.'\')" id="proj_'.$PROJ_ID.'">'._("删除").'</a>&nbsp;';
		}
		else if($PROJ_STATUS==2 || $PROJ_STATUS==1)
		{
			$OUTPUT='<a href="#" onclick="edit_proj(\''.$PROJ_ID.'\',\'1\',\''.$PROJ_NAME.'\')">'._("项目变更").'</a>&nbsp;<a href="#" onclick="check_proj_finished(\''.$PROJ_ID.'\')">'._("结束").'</a>&nbsp;';
		}
		else{
			$OUTPUT='<a href="#" onclick="resume_proj(\''.$PROJ_ID.'\')">'._("恢复执行").'</a>&nbsp;';
		}
		$PROJ_ARRAY['action'] = $OUTPUT;
	}
	else{
		$PROJ_ARRAY['action'] = '-';
	}
      $PROJ_ARRAY['PROJ_TYPE'] = $PROJ_TYPE;
      $PROJ_ARRAY['id'] = 'PROJ_'.$PROJ_ID;
      $OUTPUT_ARRAY[] = $PROJ_ARRAY;
}

$O_ARRAY = array('results'=> $PROJ_COUNT,'datastr'=> $OUTPUT_ARRAY);

echo array_to_json($O_ARRAY);