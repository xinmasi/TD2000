<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");


//----------- 合法性校验 ---------
if(!is_date($date_begin))
{
    Message(_("错误"),sprintf(_("起始日期格式不正确，应如：%s"),date("Y-m-d")));
    exit;
}
if(!is_date($date_end))
{
    Message(_("错误"),sprintf(_("结束日期格式不正确，应如：%s"),date("Y-m-d")));
    exit;
}
if($date_begin > $date_end)
{
    Message(_("错误"),_("起始日期不能大于结束日期"));
    exit;
}

if(MYOA_IS_UN == 1){
    if($dept_type==1 && $select_user=="")
    {
        $OUTPUT_HEAD =  "DEPT,NAME,NUMBER,AVERAGE DAILY SEND";
    }else if($dept_type==0 || $dept_type==2)
    {
        $OUTPUT_HEAD =  "DEPT,NUMBER,SEND,NOT SEND,PER CAPITA SEND";
    }
}else{
    if($dept_type==1 && $select_user=="")
    {
        $OUTPUT_HEAD =  _("部门").","._("姓名").","._("发布数量").",日均发布数";
    }else if($dept_type==0 || $dept_type==2)
    {
        $OUTPUT_HEAD =  _("部门").","._("发布数量").",发布人数,未发布人数,人均发布数";
    }
}
ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("日志统计信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$count = 0;
$show_arr = array();
if($dept_type==1 && $select_user=="")
{
    $query = "SELECT user.USER_ID,USER_NAME FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID='$dept_id_str' AND user.USER_ID<>'' GROUP BY user.USER_ID ORDER BY user.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $show_arr[1][$row[0]]['count'] = 0;
        $show_arr[1][$row[0]]['user_name'] = $row[1];
    }
    
	$query = "SELECT diary.USER_ID,count(diary.USER_ID) FROM user,diary WHERE NOT_LOGIN=0 AND user.USER_ID=diary.USER_ID AND DEPT_ID='$dept_id_str' AND diary.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end' GROUP BY diary.USER_ID ORDER BY diary.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[1][$row[0]]['count'] = $row[1];
    }
}
else if($dept_type==0 || $dept_type==2)
{
    $sql_str = '';
    if($dept_type == 2)
    {
    	if('ALL_DEPT' != $dept_id_str)
    	{
        	$sql_str = " AND FIND_IN_SET(DEPT_ID, '$dept_id_str')";
        }
    }
    $dept_list = '';
    $send_list_str = '';
    $send_list_arr = array();
    $send_count = 0;
    $notsend_list = array();
    $notsend_count = 0;
    
    $query = "SELECT DEPT_ID,COUNT(DEPT_ID) FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID = diary.USER_ID AND diary.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end'".$sql_str." GROUP BY DEPT_ID ORDER BY DEPT_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[0][$row[0]]['count'] = $row[1];
        $show_arr[0][$row[0]]['dept_name'] = td_trim(GetDeptNameById($row[0]));
        $show_arr[0][$row[0]]['dept_id'] = $row[0];
        $dept_list .= $row[0].',';
        $show_arr[0][$row[0]]['send_count'] = 0;
        $show_arr[0][$row[0]]['notsend_count'] = 0;
    }
    
    $query = "SELECT DISTINCT DEPT_ID,user.USER_ID FROM user,diary WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID = diary.USER_ID AND user.USER_ID<>'' AND DIA_DATE >= '$date_begin' AND DIA_DATE <= '$date_end' AND FIND_IN_SET(DEPT_ID, '$dept_list') ORDER BY DEPT_ID,user.USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $send_list_str .= $row[1].',';
        $send_list_arr[$row[0]][$send_count] = $row[1];
        ++$send_count;
    }
    foreach($send_list_arr as $key => $val)
    {
    	$show_arr[0][$key]['send_count'] = count($val);
    }
    
    $query = "SELECT DEPT_ID,USER_NAME FROM user WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND USER_ID<>'' AND FIND_IN_SET(DEPT_ID, '$dept_list') AND NOT FIND_IN_SET(USER_ID, '$send_list_str') ORDER BY DEPT_ID,USER_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
    	$show_arr[0][$row[0]]['notsend_list'] .= $row[1].',';
    	$notsend_list[$row[0]][$notsend_count] = $row[1];
    	++$notsend_count;
    }
    foreach($notsend_list as $key => $val)
    {
    	$show_arr[0][$key]['notsend_count'] = count($val);
    }
}

if(0 == $count)
{
    Message("",_("无发布的日志信息"));
    exit;
}

if($dept_type==1 && $select_user=="")
{
    //单部门日志信息
    $dept_name = td_trim(GetDeptNameById($dept_id_str));
    $dept_name=format_cvs($dept_name);
    $date_count = (strtotime($date_end) - strtotime($date_begin)) / 86400 + 1;
    $aver = 0;
    foreach($show_arr[1] as $val)
    {
    	$aver = $val['count'] / $date_count;
    	if(0 != $aver)
    	{
    		$aver = number_format($aver, 1, '.', '');
    	}        
        $val_count=format_cvs($val['count']);
        $user_name=format_cvs($val['user_name']);
        $val_aver = format_cvs($aver);
        $OUTPUT = $dept_name.",".$user_name.",".$val_count.",".$val_aver;
        $objExcel->addRow($OUTPUT);
    }
}
else if($dept_type==0 || $dept_type==2)
{
    foreach($show_arr[0] as $val)
    {
        $dept_name=td_trim(dept_long_name($val['dept_id']));
        $dept_name=format_cvs($dept_name);
        $val_count=format_cvs($val['count']);
        $val_send_count = format_cvs($val['send_count']);
        $val_notsend_count = format_cvs($val['notsend_count']);
        $val_aver = format_cvs(number_format($val['count'] / ($val['send_count'] + $val['notsend_count']), 1, '.', ''));
        $OUTPUT = $dept_name.",".$val_count.",".$val_send_count.",".$val_notsend_count.",".$val_aver;
        $objExcel->addRow($OUTPUT);
    }
}
$objExcel->Save();
?>