<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

//print_r($_GET);
//
//echo "<br>".GetDeptNameById($dept_id_str);
//exit;


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
        $OUTPUT_HEAD =  "DEPT,NAME,NUMBER";
    }else if($dept_type==1 && $select_user!="")
    {
        $OUTPUT_HEAD =  "FROM_ID,TYPE_ID,TO_NAME_STR,SUBJECT,CREATE_DATE,BEGIN_DATE,END_DATE,STATUS";
    }else if($dept_type==0 || $dept_type==2)
    {
        $OUTPUT_HEAD =  "DEPT,NUMBER";
    }
}else{
    if($dept_type==1 && $select_user=="")
    {
        $OUTPUT_HEAD =  _("部门").","._("姓名").","._("发布数量");
    }else if($dept_type==1 && $select_user!="")
    {
        $OUTPUT_HEAD =  _("发布人").","._("类型").","._("发布范围").","._("标题").","._("创建时间").","._("生效日期").","._("终止日期").","._("状态");
    }else if($dept_type==0 || $dept_type==2)
    {
        $OUTPUT_HEAD =  _("部门").","._("发布数量");
    }
}
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("公告统计信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$count = 0;
$show_arr = array();
if($dept_type==1 && $select_user=="")
{
    $query = "SELECT FROM_ID,count(FROM_ID),USER_NAME FROM notify,user WHERE NOT_LOGIN=0 AND user.DEPT_ID='$dept_id_str' AND user.USER_ID=FROM_ID AND FROM_ID<>'' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' group by FROM_ID ORDER BY FROM_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[1][$row[0]]['count'] = $row[1];
        $show_arr[1][$row[0]]['user_name'] = $row[2];
    }
}
else if($dept_type==1 && $select_user!="")
{
    $cur_date = date("Y-m-d", time());
    $type_id_str = "";
    $query = "SELECT * FROM notify WHERE FROM_ID='$select_user' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' order by TOP desc,SEND_TIME desc";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        
        $subject         = $row['SUBJECT'];
        $notify_id       = $row['NOTIFY_ID'];
        $type_id         = $row['TYPE_ID'];
        $publish         = $row['PUBLISH'];
        $send_time       = $row['SEND_TIME'];
        $begin_date      = $row['BEGIN_DATE'];
        $end_date        = $row['END_DATE'];
        $to_id           = $row['TO_ID'];
        $priv_id         = $row['PRIV_ID'];
        $user_id         = td_trim($row['USER_ID']);
        $auditer         = $row['AUDITER'];
        $reason          = $row['REASON'];
        
        //公告类型
        if($type_id)
        {
            $type_id_str .= "'".$type_id."',";
        }
        
        //状态处理
        if($publish == "1") //发布的
        {
            if(compare_date($cur_date, $begin_date)<0)
            {
                $notify_status = _("待生效");
            }
            else
            {
                $notify_status = _("生效");
            }
            if($end_date != "")
            {
                if(compare_date($cur_date, $end_date)>0)
                {
                    $notify_status = _("终止");
                }
            }
        }
        else if($publish == "2")//待审批
        {
            $notify_status = _("待审批");
        }
        else if($publish=="3")//审批未通过
        {
            $notify_status = _("未通过"); 
        }
        else if($publish=="0")
        {
            $notify_status = _("未发布");
        }
        
        //发布范围
        $to_name = ($to_id=="ALL_DEPT" ? _("全体部门") : td_trim(GetDeptNameById($to_id)));
        $priv_name = td_trim(GetPrivNameById($priv_id));
        $user_name = "";
        
        if($user_id != "")
        {
            $user_id_arr = explode(',', $user_id);
            $user_id_arr = array_unique($user_id_arr);
            $user_id_arr = array_filter($user_id_arr);
            $user_id = td_trim(implode(',', $user_id_arr));
            
            $user_name = ($user_id!="" ? GetUserNameById($user_id) : "");
        }
        
        $to_name_title = "";
        $to_name_str = "";
        if($to_name != "")
        {
            $to_name_title .= _("部门：").$to_name;
            $to_name_str .= strip_tags($to_name);
        }
        if($priv_name!="")
        {
            if($to_name_title != "")
            {
                $to_name_title .= "\n\n";
            }
            
            $to_name_title .= _("角色：").$priv_name;
            $to_name_str .= strip_tags($priv_name);
        }
        if($user_name!="")
        {
            if($to_name_title != "")
            {
                $to_name_title .= "\n\n";
            }
            
            $to_name_title .= _("人员：").$user_name;
            $to_name_str .= strip_tags($user_name);
        }
        
        $show_arr[2][$count]['subject']         = $subject;
        $show_arr[2][$count]['notify_id']       = $notify_id;
        $show_arr[2][$count]['title']           = $subject;
        $show_arr[2][$count]['type_id']         = $type_id;
        $show_arr[2][$count]['to_name_title']   = $to_name_title;
        $show_arr[2][$count]['to_name_str']     = $to_name_str;
        $show_arr[2][$count]['create_time']     = $send_time;
        $show_arr[2][$count]['begin_date']      = date("Y-m-d", $begin_date);
        $show_arr[2][$count]['end_date']        = ($end_date==0 ? "-" : date("Y-m-d", $end_date));
        $show_arr[2][$count]['notify_status']   = $notify_status;
        $show_arr[2][$count]['reason']          = td_trim(GetUserNameById($auditer))._("：").$reason;
        $show_arr[2][$count]['publish']         = $publish;
    }
    
    $type_id_str = td_trim($type_id_str);
    $type_name_array = array();
    $type_name = "";
    if($type_id_str)
    {
        $query1 = "select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($type_id_str)";
        $cursor1= exequery(TD::conn(),$query1);
        while($row_code=mysql_fetch_array($cursor1))
        {
            $type_id_key    =$row_code["CODE_NO"];
            $type_name      =$row_code["CODE_NAME"];
            $code_ext       =unserialize($row_code["CODE_EXT"]);
            
            if(is_array($code_ext) && $code_ext[MYOA_LANG_COOKIE] != "")
            {
                $type_name = $code_ext[MYOA_LANG_COOKIE];
            }
            
            $type_name_array[$type_id_key]= $type_name;
        }
    }
}
else if($dept_type==0 || $dept_type==2)
{
    $sql_str = '';
    if($dept_type == 2)
    {
        $sql_str = " AND find_in_set(user.DEPT_ID, '$dept_id_str') ";
    }
    
    $query = "SELECT DEPT_ID,count(DEPT_ID) FROM notify,user WHERE NOT_LOGIN=0 AND DEPT_ID<>0 AND user.USER_ID=FROM_ID AND FROM_ID<>'' AND SEND_TIME >= '$date_begin 00:00:00' AND SEND_TIME <= '$date_end 23:59:59' ".$sql_str." group by DEPT_ID ORDER BY DEPT_ID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $count++;
        $show_arr[0][$row[0]]['count'] = $row[1];
        $show_arr[0][$row[0]]['dept_name'] = td_trim(GetDeptNameById($row[0]));
        $show_arr[0][$row[0]]['dept_id'] = $row[0];
    }
}
//exit;

if($count == 0)
{
    Message("",_("无发布的公告信息"));
    exit;
}

if($dept_type==1 && $select_user=="")
{
    //单部门公告通知信息
    $dept_name = td_trim(GetDeptNameById($dept_id_str));
    $dept_name=format_cvs($dept_name);
    foreach($show_arr[1] as $val)
    {        
        $val_count=format_cvs($val['count']);
        $user_name=format_cvs($val['user_name']);
        $OUTPUT = $dept_name.",".$user_name.",".$val_count;
        $objExcel->addRow($OUTPUT);
    }
}
else if($dept_type==1 && $select_user!="")
{
    //具体人员公告通知信息
    $dept_name = td_trim(GetDeptNameById($dept_id_str));
    $select_user = td_trim(GetUserNameById($select_user));
    foreach($show_arr[2] as $val)
    {
        $select_user=format_cvs($select_user);
        $type=format_cvs($type_name_array[$val['type_id']]);
        $to_name_title=format_cvs(td_trim($val['to_name_title']));
        $subject=format_cvs($val['subject']);
        $create_time=format_cvs($val['create_time']);
        $begin_date=format_cvs($val['begin_date']);
        $end_date=format_cvs($val['end_date']);
        $notify_status=format_cvs($val['notify_status']);
        $OUTPUT = $select_user.",".$type.",".$to_name_title.",".$subject.",".$create_time.",".$begin_date.",".$end_date.",".$notify_status;
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
        $OUTPUT = $dept_name.",".$val_count;
        $objExcel->addRow($OUTPUT);
    }
}
ob_end_clean();
$objExcel->Save();
?>