<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人力资源信息中心");
include_once("inc/header.inc.php");
?>
<style type="text/css">
    .hrmsmokuai{width: 470px; border:1px #83ACCF solid; float: left; margin: 5px 0px 0px 5px; height:250px;overflow:auto;}
    .hrmsbananner{width: 100%; height: 30px; background: #c4de83}
    .hrmstittle{font-weight: bold; font-size: 12px; line-height: 30px; padding-left: 10px;}
    .hrmsulcontent{margin: 0px; padding: 0px 25px; list-style-type: disc; margin-top: 10px;}
    .hrmsulcontent li{ line-height: 20px;}
</style>
<script>
function open_staff_detail(USER_ID)
{
 URL="/general/hr/manage/query/staff_detail.php?USER_ID="+USER_ID;
 myleft=(screen.availWidth-900)/2;
 mytop=50;
 mywidth=900;
 myheight=600;
 window.open(URL,"open_staff_detail","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function open_trail_over(CONTRACT_ID)
{
 URL="/general/hr/manage/staff_contract/modify.php?CONTRACT_ID="+CONTRACT_ID;

 myleft=(screen.availWidth-900)/2;
 mytop=50;
 mywidth=900;
 myheight=600;
 window.open(URL,"open_staff_detail","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function open_contract_over(CONTRACT_ID)
{
 URL="/general/hr/manage/staff_contract/modify.php?CONTRACT_ID="+CONTRACT_ID;

 myleft=(screen.availWidth-900)/2;
 mytop=50;
 mywidth=900;
 myheight=600;
 window.open(URL,"open_staff_detail","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function open_staff_leave(LEAVE_ID)
{
 URL="/general/hr/manage/staff_leave/leave_detail.php?LEAVE_ID="+LEAVE_ID;

 myleft=(screen.availWidth-900)/2;
 mytop=50;
 mywidth=900;
 myheight=600;
 window.open(URL,"open_staff_detail","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
</script>
    <body  style="width: 960px; margin: 0 auto; background-color: white;">
        <div style="clear: both; height: 10px; width: 900px;"></div>
        <div class="hrmsmokuai">
            <div class="hrmsbananner">
                <span class="hrmstittle">近期新建档案</span>
            </div>
            <ul class="hrmsulcontent">
                <?
                $TODAY=date("Y-m-d");
                $tomorrow =date('Y-m-d',strtotime('+1 day'));
                $CUR_MONTH = Date("Y-m");
                $query = "SELECT * from  hr_staff_info where ADD_TIME like '".$CUR_MONTH."%'  order by STAFF_ID desc";
                $cursor= exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $HR_USER_ID=$ROW["USER_ID"];
                    $USER_NAME=substr(GetUserNameById($HR_USER_ID),0,-1);
                    if($USER_NAME=="")
                    {
                        $USER_NAME=$ROW["USER_ID"];
                    }
                    $ADD_TIME=$ROW["ADD_TIME"];
                ?>
                <li><a href="javascript:open_staff_detail(<?="'$HR_USER_ID'"?>);"><?=$USER_NAME?></a>&nbsp;&nbsp;&nbsp;（建档日期：<?=$ADD_TIME?>）</li>
                <?
                }
                ?>
            </ul>
        </div>

        <div class="hrmsmokuai">
            <div class="hrmsbananner">
                <span class="hrmstittle">本月试用到期</span>
            </div>
            <ul class="hrmsulcontent">
                <?
                $query = "SELECT * from  HR_STAFF_CONTRACT where TRAIL_OVER_TIME like '".$CUR_MONTH."%'  and STATUS = '1'  order by TRAIL_OVER_TIME desc";
                $cursor= exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $HR_USER_ID=$ROW["STAFF_NAME"];
                    $CONTRACT_ID=$ROW["CONTRACT_ID"];
                    $USER_NAME=substr(GetUserNameById($HR_USER_ID),0,-1);
                    if($USER_NAME=="")
                    {
                        $USER_NAME=$ROW["STAFF_NAME"];
                    }
                    $TRAIL_OVER_TIME=$ROW["TRAIL_OVER_TIME"];
                ?>
                <li><a href="javascript:open_trail_over(<?="'$CONTRACT_ID'"?>);"><?=$USER_NAME?></a>&nbsp;&nbsp;&nbsp;（试用到期日期：<?=$TRAIL_OVER_TIME?>）</li>
                <?
                }
                ?>
            </ul>
        </div>

        <div class="hrmsmokuai">
            <div class="hrmsbananner">
                <span class="hrmstittle">本月合同到期</span>
            </div>
            <ul class="hrmsulcontent">
               <?
                $CUR_MONTH_FIRST=$CUR_MONTH."-01";
                $query = "SELECT * from  HR_STAFF_CONTRACT where CONTRACT_END_TIME like '".$CUR_MONTH."%' and CONTRACT_END_TIME !='0000-00-00' and IS_RENEW !='1' and STATUS = '2' order by CONTRACT_END_TIME desc";
                $cursor= exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    

                        $CONTRACT_END_TIME=$ROW["CONTRACT_END_TIME"];                        

                            $HR_USER_ID=$ROW["STAFF_NAME"];
                            $CONTRACT_ID=$ROW["CONTRACT_ID"];
                            $USER_NAME=substr(GetUserNameById($HR_USER_ID),0,-1);
                            if($USER_NAME=="")
                            {
                                $USER_NAME=$ROW["STAFF_NAME"];
                            }
                 ?>
                            <li><a href="javascript:open_contract_over(<?="'$CONTRACT_ID'"?>);"><?=$USER_NAME?></a>&nbsp;&nbsp;&nbsp;（本月合同到期日期：<?=$CONTRACT_END_TIME?>）</li>
                
                <?
                }
                ?>
            </ul>
        </div>

        <div class="hrmsmokuai">
            <div class="hrmsbananner">
                <span class="hrmstittle">近期离职人员</span>
            </div>
            <ul class="hrmsulcontent">
                <?
                $TODAY=date("Y-m-d");
                $CUR_MONTH = Date("Y-m");
                $query = "SELECT * from  hr_staff_leave where QUIT_TIME_FACT like '".$CUR_MONTH."%'  order by QUIT_TIME_FACT desc";
                $cursor= exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $HR_USER_ID=$ROW["LEAVE_PERSON"];
                    $LEAVE_ID=$ROW["LEAVE_ID"];
                    $USER_NAME=substr(GetUserNameById($HR_USER_ID),0,-1);
                    if($USER_NAME=="")
                    {
                        $USER_NAME=$ROW["LEAVE_PERSON"];
                    }
                    $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"];
                ?>
                <li><a href="javascript:open_staff_leave(<?="'$LEAVE_ID'"?>);"><?=$USER_NAME?></a>&nbsp;&nbsp;&nbsp;（实际离职日期：<?=$QUIT_TIME_FACT?>）</li>
                <?
                }
                ?>
            </ul>
        </div>
        <div style="clear: both; height: 10px; width: 900px;"></div>
    </body>
</html>
