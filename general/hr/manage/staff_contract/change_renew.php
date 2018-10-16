<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");;

$HTML_PAGE_TITLE = _("合同信息修改");
include_once("inc/header.inc.php");
ob_end_clean();

$CONTRACT_ID=$_POST["hetongid"];
$CONTRACT_RENEW_TIME=$_POST["chuanzhi"];
if($CONTRACT_RENEW_TIME=="")
{
    echo _("请输入续签日期");
    exit();
}

$query = "SELECT RENEW_TIME from  hr_staff_contract where CONTRACT_ID = '$CONTRACT_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{    
    $RENEW_TIME_QUERY=$ROW['RENEW_TIME'];    
    if($RENEW_TIME_QUERY != "0000-00-00")
    {        
        $RENEW_TIME_QUERY1 = trim($RENEW_TIME_QUERY,"|");
        $A_RENEW_TIME_QUERY = explode("|",$RENEW_TIME_QUERY1);
        $COUNT_RENEW_TIME_QUERY= count($A_RENEW_TIME_QUERY);
        $query = "select * from HR_STAFF_CONTRACT where CONTRACT_ID='$CONTRACT_ID'";
        $cursor = exequery(TD::conn(), $query);
        if ($ROW = mysql_fetch_array($cursor)) 
            $CONTRACT_END_TIME = $ROW["CONTRACT_END_TIME"];
        if($COUNT_RENEW_TIME_QUERY==1)
        {
            $time2 = $CONTRACT_END_TIME;
        }
        else
        {
            $time2 = $A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-2];
        }
        if($A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-1] != "0000-00-00" && $time2 != $CONTRACT_RENEW_TIME && $time2 < $CONTRACT_RENEW_TIME)
        {            
            $RENEW_TIME_INFO=str_replace($A_RENEW_TIME_QUERY[$COUNT_RENEW_TIME_QUERY-1], "", $RENEW_TIME_QUERY1);
            $REMIND_TIME1=$RENEW_TIME_INFO.$CONTRACT_RENEW_TIME."|";
            
        }
        else
        {
            if($time2 >= $CONTRACT_RENEW_TIME)
            {
                echo _("续签日期不能小于上次续签日期或者小于合同终止日期");
                exit();
            }
            else
            {
                echo _("续签日期不能等于上次续签日期");
                exit();
            }
        }
         
    }
    else
    {        
        $REMIND_TIME1=$CONTRACT_RENEW_TIME."|";
    }
}

function DiffDate($date1,$date2)
{
	if(strtotime($date1)>strtotime($date2))
	{
		$tmp   = $date2;
		$date2 = $date1;
		$date1 = $tmp;
	}
	list($y1,$m1,$d1)=explode('-',$date1);
	
	list($y2,$m2,$d2)=explode('-',$date2);
	
	$y = $y2-$y1;
	$m = $m2-$m1;
	$d = $d2-$d1;
	
	if($d<0)
	{
		$d+=(int)date('t',strtotime("-1 month $date2"));
        $m--;
    }
    if($m<0)
	{
		$m+=12;
		$y--;
    }
    return array($y, $m, $d);
}
    
$query="UPDATE HR_STAFF_CONTRACT SET  RENEW_TIME='$REMIND_TIME1' WHERE CONTRACT_ID = '$CONTRACT_ID'";
exequery(TD::conn(),$query);

$time1 = $CONTRACT_RENEW_TIME;
$thistime = DiffDate($time1,$time2);	
$start = 0;
$string = _("合同续签期限：");
$string .= $thistime[0]._("年");
$string .= $thistime[1]._("月");
$string .= abs($thistime[2])._("天");
if($COUNT_RENEW_TIME_QUERY==1 && $CONTRACT_END_TIME=="0000-00-00")
{
    echo '<td nowrap class="TableData">'._("第").$COUNT_RENEW_TIME_QUERY._("次续签日期：").'</td>
                        <td class="TableData" id="neirongtiaozheng" hetongid="'.$CONTRACT_ID.'" name="'.$CONTRACT_RENEW_TIME.'" colspan="2">'._("续签到期日期：").$CONTRACT_RENEW_TIME."&nbsp;&nbsp;&nbsp;".'<a href="javascript:void(0);" onclick="XUQIAN()">'._("续签日期修改").'</a></td>
                   ';
}
else
{
    echo '<td nowrap class="TableData">'._("第").$COUNT_RENEW_TIME_QUERY._("次续签日期：").'</td>
                        <td class="TableData" id="neirongtiaozheng" hetongid="'.$CONTRACT_ID.'" name="'.$CONTRACT_RENEW_TIME.'" colspan="2">'.$string."&nbsp;&nbsp;&nbsp;".$time2._("&nbsp;到&nbsp;").$CONTRACT_RENEW_TIME."&nbsp;&nbsp;&nbsp;".'<a href="javascript:void(0);" onclick="XUQIAN()">'._("续签日期修改").'</a></td>
                   ';
}
?>