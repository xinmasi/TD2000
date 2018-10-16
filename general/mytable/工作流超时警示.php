<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("inc/utility_flow.php");
include_once("general/workflow/prcs_role.php");
include_once("inc/workflow/inc/common.inc.php");
include_once("inc/workflow/monitor/workmonitor.func.php");
$MODULE_FUNC_ID="";
$MODULE_DESC=_("超时工作警示");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'workflow';
$MODULE_BODY.= "<ul>";
$COUNT=0;
$CUR_TIME = date("Y-m-d H:i:s",time());

$attend_cfg = array();
$query = "select
                  A.RUN_ID,
                  A.PRCS_ID,
                  A.FLOW_PRCS,
                  A.CREATE_TIME,
                  A.PRCS_TIME,
                  A.DELIVER_TIME,
                  A.PRCS_FLAG,
                  A.USER_ID,
                  A.TIME_OUT as TIME_OUT_RUN,
				  A.ID AS PRCS_KEY_ID,
                  A.WORKINGDAYS_TYPE,
                  B.FLOW_ID,
                  B.RUN_NAME,
                  B.END_TIME
			from
                  FLOW_RUN_PRCS A
				  inner join FLOW_RUN B on A.RUN_ID=B.RUN_ID 
			where A.TIME_OUT_FLAG = '1' and B.DEL_FLAG != '1'	and A.PRCS_FLAG in('1','2') and  A.USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'
			ORDER BY A.RUN_ID DESC limit 0,$MAX_COUNT";
$cursor = exequery(TD::conn(),$query);
$attend_cfg_arr = array();
$flowProcessArr = array();
//免签人员和免签节假日
$no_duty_user = '';
$festval = array();
$SYS_PARA_ARRAY = get_sys_para("NO_DUTY_USER");
$no_duty_user = $SYS_PARA_ARRAY["NO_DUTY_USER"];
$festval_query = "SELECT * from ATTEND_HOLIDAY order by BEGIN_DATE desc";
$festval_cursor = exequery(TD::conn(), $festval_query);
while($festval_row = mysql_fetch_array($festval_cursor))
{
    $festval[] = array(
        'BEGIN_DATE' => $festval_row["BEGIN_DATE"],
        'END_DATE' => $festval_row['END_DATE']
    );
}
// echo '<pre>';
while($ROW=mysql_fetch_array($cursor)){
	$RUN_ID			= $ROW['RUN_ID'];
	$flow_id = $FLOW_ID	= $ROW['FLOW_ID'];
	$CREATE_TIME	= $ROW['CREATE_TIME'];
	$PRCS_TIME		= $ROW['PRCS_TIME'];
	$DELIVER_TIME	= $ROW['DELIVER_TIME'];
	$RUN_NAME		= $ROW['RUN_NAME'];
	$USER_ID		= $ROW['USER_ID'];
	$USER_NAME		= rtrim(GetUserNameById($USER_ID),",");
	$PRCS_ID		= $ROW["PRCS_ID"];
	$flow_prcs = $FLOW_PRCS = $ROW["FLOW_PRCS"];
	$prcs_key_id	= $ROW["PRCS_KEY_ID"];
    $WORKINGDAYS_TYPE = $ROW["WORKINGDAYS_TYPE"];
    $END_TIME       = $ROW["END_TIME"];
	
	// if(!array_key_exists($FLOW_ID,$flowProcessArr)){
		// $WORKFLOW_PRCS_ARRAY = TD::get_cache('workflow/flow/'.$FLOW_ID);
	    // $flowProcessArr[$FLOW_ID] = $WORKFLOW_PRCS_ARRAY;
	// }
    $getFlowPrcsInfo =	getFlowPrcsInfo($FLOW_ID, $FLOW_PRCS);
    // echo '<pre>';
    // print_r($flowProcessArr);
    // exit;
    $config = Array ();
    $config ["TIME_OUT"] = $getFlowPrcsInfo["TIME_OUT"];
    $config ["TIME_OUT_MODIFY"] = $getFlowPrcsInfo["TIME_OUT_MODIFY"];
    $config ["TIME_OUT_TYPE"] = $getFlowPrcsInfo["TIME_OUT_TYPE"];
    $config ["TIME_OUT_ATTEND"] = $getFlowPrcsInfo["TIME_OUT_ATTEND"];
    $config ["NO_DUTY_USER"] = $no_duty_user;
    $config ["FESTVAL"] = $festval;
    $workingdays_type = $getFlowPrcsInfo['WORKINGDAYS_TYPE'];
    
    // echo $RUN_ID;
    // echo '-----';
    // echo $USER_ID;
    // echo '-----';
    // echo $CREATE_TIME;
    // echo '-----';
    // echo $PRCS_TIME;
    // echo '-----';
    // echo $DELIVER_TIME;
    // echo '-----';
    // print_r($config);
    // exit;
    $work_time_array = getWorkPrcTime ( $USER_ID , $CREATE_TIME , $PRCS_TIME , $DELIVER_TIME , $config );
    $work_time = $work_time_array['work_time'];
    $time_base = $work_time_array['time_base'];
    
    if ($ROW ['TIME_OUT_RUN'] != '')
    {
        $time_out = $ROW ['TIME_OUT_RUN'];
    }
    else
    {
        $time_out = $config ["TIME_OUT"];
    }
    if($END_TIME)
    {
        $work_time = $work_time - $time_out*3600;//修改已办结工作超时时间不显示问题王瑞杰20140428
    }
    else
    {
        $work_time = $work_time - $time_out*3600;
    }
    // echo $work_time;
    // echo '---';
    // echo $time_base;
    // echo '---';
    // echo $workingdays_type;
    // exit;
    $TIME_OUT_DESC = format_interval_to_king_str($work_time, $time_base, $workingdays_type);
	// $TIME_OUT = $flowProcessArr[$FLOW_ID][$FLOW_PRCS]["TIME_OUT"];
	// $TIME_OUT_TYPE = $flowProcessArr[$FLOW_ID][$FLOW_PRCS]["TIME_OUT_TYPE"];
	// $TIME_OUT_ATTEND = $flowProcessArr[$FLOW_ID][$FLOW_PRCS]["TIME_OUT_ATTEND"];
	// if($TIME_OUT_ATTEND == 1){
		// if(array_key_exists($USER_ID, $attend_cfg_arr)){
			// $attend_cfg = $attend_cfg_arr[$USER_ID];
		// }else{
			// $attend_cfg = get_attend_cfg($USER_ID);
			// $attend_cfg_arr[$USER_ID] = $attend_cfg;
		// }
	// }else {
		// $attend_cfg = "";
	// }
	// if($TIME_OUT_TYPE == 0){	 //按照工作步骤的PRCS_TIME来计算
		// if(empty($PRCS_TIME) || $PRCS_TIME == "0000-00-00 00:00:00"){
			// $PRCS_BEGIN_TIME = strtotime($CREATE_TIME);
		// }else {
			// $PRCS_BEGIN_TIME = strtotime($PRCS_TIME);
		// }
	// }else{  //按照工作步骤的CREATE_TIME来计算
		// $PRCS_BEGIN_TIME = strtotime($CREATE_TIME);
	// }
	// if(empty($DELIVER_TIME) || $DELIVER_TIME == "0000-00-00 00:00:00"){
		// $PRCS_END_TIME = strtotime($CUR_TIME);
	// }else {
		// $PRCS_END_TIME = strtotime($DELIVER_TIME);
	// }		
	// if($TIME_OUT_RUN != ""){
		// $TIME_OUT = $TIME_OUT_RUN;
	// }
	// $TIME_OUT_DESC = getTimeOutDesc($TIME_OUT,$PRCS_BEGIN_TIME,$PRCS_END_TIME,$attend_cfg);
	
	$COUNT++;  
	$MODULE_BODY.='<li>'._("流水号:").'<font color=red>'.$RUN_ID.'</font>&nbsp;'._("工作名称/文号:").	"<font color=red><a href=\"javascript:handle_work('', '".$RUN_ID."', '".$prcs_key_id."', '".$flow_id."', '".$PRCS_ID."', '".$flow_prcs."');\" >".$RUN_NAME."</a></font>&nbsp;"._("责任人:").'<font color=red>'.$USER_NAME.'</font>&nbsp;'._("超时：").'<font color=red>'.$TIME_OUT_DESC.'</font></li>';
	if($COUNT > $MAX_COUNT){
		break;
	}
}//while
	
// exit;
if($COUNT==0){
	$MODULE_BODY.= "<li>"._("暂无超时工作")."</li>";
}
$MODULE_BODY.= "<ul>";

$MODULE_BODY .='
<script>
function handle_work(menu_flag, run_id, prcs_key_id, flow_id, prcs_id, flow_prcs){ //办理工作
	var url = "/general/workflow/list/input_form/?actionType=handle";
	url += "&MENU_FLAG="+menu_flag;
	url += "&RUN_ID="+run_id;
	url += "&PRCS_KEY_ID="+prcs_key_id;
	url += "&FLOW_ID="+flow_id;
	url += "&PRCS_ID="+prcs_id;
	url += "&FLOW_PRCS="+flow_prcs;
	window.open(url);
}
</script>
';
?>