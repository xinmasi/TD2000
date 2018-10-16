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
    
$query="UPDATE HR_STAFF_CONTRACT SET  RENEW_TIME='$REMIND_TIME1' WHERE CONTRACT_ID = '$CONTRACT_ID'";
exequery(TD::conn(),$query);

$time1 = $CONTRACT_RENEW_TIME;
$t1 = strtotime($time1);
$t2 = strtotime($time2);
$t12 = abs($t1-$t2);
$start = 0;
$string = _("合同续签期限：");
$y = floor($t12/(3600*24*365));                    
$t12 -= $y*3600*24*365;
$string .= $y._("年");

$m = floor($t12/(3600*24*30));                   
$t12 -= $m*3600*24*31;
$string .= $m._("月");

$d = floor($t12/(3600*24));                    
$t12 -= $d*3600*24;
$string .= $d._("天");
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