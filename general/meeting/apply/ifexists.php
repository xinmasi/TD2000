<?php 
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$m_start = $_POST['m_start'];
$m_end   = $_POST['m_end'];
$user_id = $_POST['user_id'];
$m_id = $_POST['m_id'];
$cur_date = $_POST['cur_date'];//获取实际的天数

$WEEKS = iconv("UTF-8","gbk",$_POST["weeks"]); 
$MONTH_HEAD = substr($m_start,5,2);
$MONTH_END = substr($m_end,5,2);
$str = ''; //出席人员制空
if($MONTH_HEAD != $MONTH_END)
{
    for($i=$MONTH_HEAD;$i<=$MONTH_END;$i++)
    {
        $j = '0'.$i;
        if(strlen($j) == '3')
        {
            $j = substr($j,1,2);
        }
        $SPAN_MONTH[] = $j;
    }
}
if($m_id != "")
{    
    $sql1 = "select M_ATTENDEE,M_START,M_END from meeting where M_ID =".$m_id;
    $cursor = exequery(TD::conn(),$sql1);
    $ROW=mysql_fetch_array($cursor);
    $user_old = explode(",",rtrim($ROW['M_ATTENDEE'],","));//数据库原有此会议的出勤人员
    $m_start_old = $ROW['M_START'];
    $m_end_old = $ROW['M_END'];
}
$users = explode(",",rtrim($user_id,","));
foreach ($users as $value)
{
    if($user_old != "" AND $m_id != "")
    {
        $sql = "select count(*) as ifint from meeting where (('".$m_start."'< M_START and '".$m_end."'> M_START) or ('".$m_start."'>=M_START and '".$m_end."'<= M_END ) or ( '".$m_start."' < M_END and '".$m_end."'> M_END )) and M_ATTENDEE like '%".$value."%' AND M_ID not in ($m_id) and (M_STATUS <> 3 and M_STATUS <> 4)";
        $cursor = exequery(TD::conn(),$sql);
        $ROW=mysql_fetch_array($cursor);
        if($ROW['ifint'] > 0)
        {
            $str .= td_trim(GetUserNameById($value)).',';
             
        }
    }
    
    if($m_id == "" AND $weeks == "" AND $cur_date == null)
    {
        $sql = "select count(*) as ifint from meeting where (('".$m_start."'< M_START and '".$m_end."'> M_START) or ('".$m_start."'>=M_START and '".$m_end."'<= M_END ) or ( '".$m_start."' < M_END and '".$m_end."'> M_END ) OR ('".$m_start."'= M_START AND '".$m_end."'= M_END)) and (M_ATTENDEE like '%".$value."%' or RECORDER like '%".$value."%') and (M_STATUS <> 3 and M_STATUS <> 4)";
        $cursor = exequery(TD::conn(),$sql);
        $ROW=mysql_fetch_array($cursor);
        if($ROW['ifint'] > 0)
        {
            $str .= td_trim(GetUserNameById($value)).",";
        }
    }
    

    if($cur_date != "")
    { 
        $cur_date_arr = explode(',',$cur_date);
        array_pop($cur_date_arr);
        for($i=0;strtotime($m_start.'+'.$i.' days')<=strtotime($m_end)&&$i<365;$i++)
        {
            $time = strtotime($m_start.'+'.$i.' days');
            $m_start1 = date('H:i:s',$time);
            $ONLY_END = date('H:i:s',strtotime($m_end));
            $m_end_time = date('Y-m-d',$time);
            $m_end1 = $m_end_time.' '.$ONLY_END; 
        }
        if($MONTH_HEAD != $MONTH_END)
        {
            foreach($SPAN_MONTH as $M)
            {
                foreach(array_reverse($cur_date_arr) as $k => $v)
                {
                    $m_start2 = date('Y',$time).'-'.$M.'-'.$v.' '.$m_start1.',';
                    $m_end2 = date('Y',$time).'-'.$M.'-'.$v.' '.$ONLY_END.','; 
                
                    $sql2 = "select count(*) as ifint from meeting where ((('".$m_start2."'<= M_START and '".$m_end2."'>= M_END) or ('".$m_start2."'>= M_START and ('".$m_end2."'<= M_END))) and (find_in_set('$value',M_ATTENDEE) || RECORDER='".$value."')) and (M_STATUS <> 3 and M_STATUS <> 4)";
                    $cursor2 = exequery(TD::conn(),$sql2);
                    $ROW2=mysql_fetch_array($cursor2);
                    if($ROW2['ifint'] > 0)
                    {   
                        $str[]=GetUserNameById($value);   
                    }    
                }
            }
        }
        else
        {
            foreach(array_reverse($cur_date_arr) as $k => $v)
            {
                $m_start2 = date('Y-m',$time).'-'.$v.' '.$m_start1.',';
                $m_end2 = date('Y-m',$time).'-'.$v.' '.$ONLY_END.','; 
            
                $sql2 = "select count(*) as ifint from meeting where ((('".$m_start2."'<= M_START and '".$m_end2."'>= M_END) or ('".$m_start2."'>= M_START and ('".$m_end2."'<= M_END))) and (find_in_set('$value',M_ATTENDEE) || RECORDER='".$value."')) and (M_STATUS <> 3 and M_STATUS <> 4)";
                $cursor2 = exequery(TD::conn(),$sql2);
                $ROW2=mysql_fetch_array($cursor2);
                if($ROW2['ifint'] > 0)
                {   
                    $str[]=GetUserNameById($value);   
                }    
            }
        }
    }
    
    if($weeks != "")
    {  
        $week = "0,1,2,3,4,5,6";
        $weeke = explode(",",mb_convert_encoding($week,"gb2312","utf-8")); 
        $APPLY_WEEK = explode(",",$weeks);
        array_pop($APPLY_WEEK);
        for($i=0;strtotime($m_start.'+'.$i.' days')<=strtotime($m_end)&&$i<365;$i++)
        {	
            $TIME = strtotime($m_start.'+'.$i.' days');
            $ONLY_BEGIN = date('H:i:s',$TIME);
            $ONLY_END = date('H:i:s',strtotime($m_end));
            $GET_WEEK.= $weeke[date('w',$TIME)].',';
            $DATES = date('Y-m-d',$TIME);
            $GET_DATE.= $DATES.',';//年月 
        }
        $GET_WEEKS = explode(",",$GET_WEEK);
        array_pop($GET_WEEKS);
        $GET_DATES = explode(",",$GET_DATE);
        array_pop($GET_DATES);
        $DATES_REVERSE = array_flip($GET_DATES);
        foreach($APPLY_WEEK as $k => $v)
        {             
            $GET_WEEKS_KEYS[] = array_keys($GET_WEEKS, $v);                
        }
        foreach($GET_WEEKS_KEYS as $key => $val)
        { 
            foreach($val as $valu) 
            { 
                $new_arr[] = $valu; 
            } 
        }
        foreach($new_arr as $key => $valus)
        {
            $DATES_REVERSE_VALUE = array_keys($DATES_REVERSE, $valus);
            
            foreach($DATES_REVERSE_VALUE as $k => $y)
            {
               $m_start2 = $y.' '.$ONLY_BEGIN;
               $m_end2 = $y.' '.$ONLY_END;
               
               $sql = "select count(*) as ifint from meeting where (('".$m_start2."'<= M_START and '".$m_end2."'>= M_END) or ('".$m_start2."'>= M_START and ('".$m_end2."'<= M_END))) and (RECORDER='".$value."' || find_in_set('$value',M_ATTENDEE)) and (M_STATUS <> 3 and M_STATUS <> 4)";
                $cursor = exequery(TD::conn(),$sql);
                $ROW=mysql_fetch_array($cursor);
                if($ROW['ifint'] > 0)
                {
                    $str[]= td_trim(GetUserNameById($value)).',';   
                }
            }                
        }        
       // $sql = "select count(*) as ifint from meeting where (('".$m_start2."'<= M_START and '".$m_end2."'>= M_END) or ('".$m_start2."'>= M_START and ('".$m_end2."'<= M_END))) and find_in_set('$value',M_ATTENDEE)";
    }
}
if(is_array($str))
{
    $strs = array_unique($str);
    $str=implode("",$strs);
}

$return_str_arr = explode(',', $str);
$return_str_arr = array_unique($return_str_arr);
$return_str_arr = array_filter($return_str_arr);
$str  = implode(',', $return_str_arr);

echo $str;
exit;
?>