<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");

if($WORK_TYPE=="")
    $WORK_TYPE=0;

if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("WORK_PLAN", 10);
if(!isset($start) || $start=="")
    $start=0;

$HTML_PAGE_TITLE = _("工作计划列表");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function plan_detail(PLAN_ID)
{
    URL="plan_detail.php?PLAN_ID="+PLAN_ID;
    window.open(URL,"plan_detail","height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=60,left=110,resizable=yes");
}

function change_type(TPYE,WORK_TYPE,SELECT_STATUS)
{
    window.location="work_plan.php?TPYE="+TPYE+"&WORK_TYPE="+WORK_TYPE+"&SELECT_STATUS="+SELECT_STATUS+"&start="+<?=$start?>;
}

function order_by(field,asc_desc)
{
    window.location="work_plan.php?TPYE=<?=$TPYE?>&WORK_TYPE=<?=$WORK_TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc+"&start="+<?=$start?>;
}

function open_diary(PLAN_ID,HINT_FLAG)
{
    if(HINT_FLAG==1)
    {
        alert("<?=_("您不是该工作的参与人，不能写进度日志。")?>");
        return (false);
    }
    else
    {
        window.open('add_diary.php?PLAN_ID='+PLAN_ID,'','height=550,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=110,top=60,resizable=yes');
    }
}
</script>


<body class="bodycolor">
<?

//进度没有达到100%，就到了结束日期的，会发事务提醒相应的参与人(至少写了一次工作日志)
$CUR_DATE=date("Y-m-d",time());
setcookie("remind_flag",1,time()+60*60*24*3000);
if($_COOKIE["remind_flag"]!=1)
{
    $query = "SELECT MAX( a.PERCENT ) AS MAX_PERCENT, a.WRITER,b.NAME,b.PLAN_ID
    FROM WORK_DETAIL AS a, WORK_PLAN AS b
    WHERE b.END_DATE <= '$CUR_DATE'
    AND b.END_DATE != '0000-00-00'
    AND b.SMS_FLAG = '0'
    AND a.PLAN_ID = b.PLAN_ID
    GROUP BY a.PLAN_ID, a.WRITER";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $MAX_PERCENT=$ROW["MAX_PERCENT"];
        $PLAN_ID=$ROW["PLAN_ID"];
        $NAME=$ROW["NAME"];
        $WRITER=$ROW["WRITER"];
        $SMS_CONTENT="";
        $SMS_CONTENT = $NAME._("工作计划到了结束时间，请尽快完成。");

        if($MAX_PERCENT < 100)
            send_sms("","admin",$WRITER,12,$SMS_CONTENT);

        $query = "update WORK_PLAN set SMS_FLAG = '1' where PLAN_ID = '$PLAN_ID'";
        exequery(TD::conn(),$query);
    }
}

if($TPYE==1)
    $TPYE_DESC=_("今日工作计划");
else if($TPYE==2)
    $TPYE_DESC=_("本周工作计划");
else if($TPYE==3)
    $TPYE_DESC=_("本月工作计划");

//范围条件
if($_SESSION["LOGIN_USER_PRIV"]!=1)
    $RANGE_STR="(TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_PERSON_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPATOR)or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPINION_LEADER))";
else
    $RANGE_STR="1=1";

if($WORK_TYPE!=0)
    $RANGE_STR.=" and TYPE='$WORK_TYPE'";

//状态转换
if($SELECT_STATUS=="")
    $SELECT_STATUS=0;

if($SELECT_STATUS==1)
    $RANGE_STR.=" and (END_DATE <= '$CUR_DATE' and END_DATE!='0000-00-00')";

if($SELECT_STATUS==2)
    $RANGE_STR.=" and (END_DATE > '$CUR_DATE' or END_DATE='0000-00-00')";

//时间条件
$WEEK_BEGIN=date("Y-m-d",(strtotime($CUR_DATE)-(date("w",strtotime($CUR_DATE)))*24*3600));
$WEEK_END=date("Y-m-d",(strtotime($CUR_DATE)+(6-date("w",strtotime($CUR_DATE)))*24*3600));
$MONTH_BEGIN=date("Y-m-d",(strtotime($CUR_DATE)-(date("j",strtotime($CUR_DATE))-1)*24*3600));
$MONTH_END=date("Y-m-d",(strtotime($CUR_DATE)+(date("t",strtotime($CUR_DATE))-date("j",strtotime($CUR_DATE)))*24*3600));

if($TPYE==1)
    $DATE_STR="BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE='0000-00-00')";
elseif($TPYE==2)
    $DATE_STR="((BEGIN_DATE<='$WEEK_END' and BEGIN_DATE>='$WEEK_BEGIN') or (END_DATE<='$WEEK_END' and END_DATE>='$WEEK_BEGIN') or (BEGIN_DATE<='$WEEK_BEGIN' and END_DATE>='$WEEK_END') or (BEGIN_DATE<='$WEEK_BEGIN' and END_DATE='0000-00-00'))";
elseif($TPYE==3)
    $DATE_STR="((BEGIN_DATE<='$MONTH_END' and BEGIN_DATE>='$MONTH_BEGIN') or (END_DATE<='$MONTH_END' and END_DATE>='$MONTH_BEGIN') or (BEGIN_DATE<='$MONTH_BEGIN' and END_DATE>='$MONTH_END') or (BEGIN_DATE<='$MONTH_BEGIN' and END_DATE='0000-00-00'))";

$query = "SELECT count(*) from WORK_PLAN where ".$RANGE_STR." and ".$DATE_STR." and PUBLISH='1'";
$cursor= exequery(TD::conn(),$query);
$WORK_PLAN_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
    $WORK_PLAN_COUNT=$ROW[0];
$TOTAL_ITEMS = $WORK_PLAN_COUNT;
if($WORK_PLAN_COUNT==0)
{
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="20" align="absmiddle"><span class="big3"> <?=$TPYE_DESC?> </span>&nbsp;
                <select name="WORK_TYPE" class="BigSelect" onChange="change_type('<?=$TPYE?>',this.value,'<?=$SELECT_STATUS?>');">
                    <option value="0"<?if($WORK_TYPE=="0") echo " selected";?>><?=_("所有类别")?></option>
                    <?
                    $query = "SELECT TYPE_ID,TYPE_NAME from PLAN_TYPE order by TYPE_NO";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $TYPE_ID=$ROW["TYPE_ID"];
                        $TYPE_NAME=$ROW["TYPE_NAME"];
                        ?>
                        <option value="<?=$TYPE_ID?>" <?if($WORK_TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
                        <?
                    }
                    ?>
                </select>
                <select name="SELECT_STATUS" class="BigSelect" onChange="change_type('<?=$TPYE?>','<?=$WORK_TYPE?>',this.value);">
                    <option value="0"<?if($SELECT_STATUS=="0") echo " selected";?>><?=_("所有计划")?></option>
                    <option value="1"<?if($SELECT_STATUS=="1") echo "selected";?>><?=_("结束计划")?></option>
                    <option value="2"<?if($SELECT_STATUS=="2") echo "selected";?>><?=_("未结束计划")?></option>
                </select>
            </td>
        </tr>
    </table>
    <?
    Message("",_("无工作计划"));
    exit;
}
else
{
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" >
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$TPYE_DESC?> </span>&nbsp;
                <select name="WORK_TYPE" class="BigSelect" onChange="change_type('<?=$TPYE?>',this.value,'<?=$SELECT_STATUS?>');">
                    <option value="0"<?if($WORK_TYPE=="0") echo " selected";?>><?=_("所有类别")?></option>
                    <?
                    $query = "SELECT TYPE_ID,TYPE_NAME from PLAN_TYPE order by TYPE_NO";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $TYPE_ID=$ROW["TYPE_ID"];
                        $TYPE_NAME=$ROW["TYPE_NAME"];
                        ?>
                        <option value="<?=$TYPE_ID?>" <?if($WORK_TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
                        <?
                    }
                    ?>
                </select>
                <select name="SELECT_STATUS" class="BigSelect" onChange="change_type('<?=$TPYE?>','<?=$WORK_TYPE?>',this.value);">
                    <option value="0"<?if($SELECT_STATUS=="0") echo " selected";?>><?=_("所有计划")?></option>
                    <option value="1"<?if($SELECT_STATUS=="1") echo "selected";?>><?=_("结束计划")?></option>
                    <option value="2"<?if($SELECT_STATUS=="2") echo "selected";?>><?=_("未结束计划")?></option>
                </select>
            </td>
            <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>    </td>
        </tr>
    </table>
    <?
}

if($ASC_DESC=="")
    $ASC_DESC="1";
if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>
<table  class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("序号")?></td>
        <td nowrap align="center" onClick="order_by('NAME','<?if($FIELD=="NAME") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("计划名称")?></u><?if($FIELD=="NAME") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("进度")?></td>
        <td nowrap align="center" onClick="order_by('BEGIN_DATE','<?if($FIELD=="BEGIN_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("开始时间")?></u><?if($FIELD=="BEGIN_DATE"||$FIELD=="") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("结束时间")?></td>
        <td nowrap align="center" onClick="order_by('TYPE','<?if($FIELD=="TYPE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("计划类别")?></u><?if($FIELD=="TYPE") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("负责人")?></td>
        <td nowrap align="center"><?=_("参与人")?></td>
        <td nowrap align="center"><?=_("附件")?></td>
        <td nowrap align="center"><?=_("状态")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
    <?
    //============================ 显示 =======================================
    $POSTFIX = _("，");
    $PLAN_COUNT=0;
    $query = "SELECT * from WORK_PLAN where ".$RANGE_STR." and ".$DATE_STR." and PUBLISH='1'";
    if($FIELD=="")
        $query .= " order by PLAN_ID desc,BEGIN_DATE desc";
    else
    {
        $query .= " order by ".$FIELD;
        if($ASC_DESC=="1")
            $query .= " desc";
        else
            $query .= " asc";
    }
    $query .= " limit $start,$PAGE_SIZE";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $PLAN_COUNT++;

        $PLAN_ID=$ROW["PLAN_ID"];
        $NAME=$ROW["NAME"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $TYPE1=$ROW["TYPE"];
        $TO_ID=$ROW["TO_ID"];
        $MANAGER=$ROW["MANAGER"];
        $PARTICIPATOR=$ROW["PARTICIPATOR"];

        $DIARY_WRITER=$MANAGER.$PARTICIPATOR;  //负责人和参与人可以写工作日志
        if(find_id($DIARY_WRITER,$_SESSION["LOGIN_USER_ID"]))
            $HINT_FLAG=0;
        else
            $HINT_FLAG=1;

        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
        $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];

        $query1 = "SELECT * from PLAN_TYPE where TYPE_ID='$TYPE1'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $TYPE_DESC=$ROW1["TYPE_NAME"];
        else
            $TYPE_DESC="";

        $MANAGE_NAME="";
        $TOK=strtok($MANAGER,",");
        while($TOK!="")
        {
            $query1="select * from USER where USER_ID='$TOK'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW1=mysql_fetch_array($cursor1))
            {
                $DEPT_ID=$ROW1["DEPT_ID"];
                $DEPT_NAME=dept_long_name($DEPT_ID);
                $MANAGE_NAME.="<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u>".$POSTFIX;
            }
            $TOK=strtok(",");
        }
        $MANAGE_NAME=substr($MANAGE_NAME,0,-strlen($POSTFIX));

        $PARTICIPATOR_NAME_TITLE="";
        $PARTICIPATOR_NAME=td_trim(GetUserNameById($PARTICIPATOR));

        if(strlen($PARTICIPATOR_NAME) > 50)
        {
            $PARTICIPATOR_NAME_TITLE=$PARTICIPATOR_NAME;
            $PARTICIPATOR_NAME=csubstr($PARTICIPATOR_NAME, 0, 50)."...";
        }
        //参与人和负责人都可以填写日志
        $TOK1=strtok($DIARY_WRITER,",");

        $SUM_PERCENT=0;
        $TOTAL_PERCENT=0;
        $PERSON=0;
        while($TOK1!="")
        {
            $query1="select * from USER where USER_ID='$TOK1'";//有用户
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW1=mysql_fetch_array($cursor1))
            {
                $query2 = "SELECT MAX(PERCENT) as PERCENT from WORK_DETAIL where PLAN_ID='$PLAN_ID' and WRITER='$TOK1'";
                $cursor2=exequery(TD::conn(),$query2);
                if($ROW2=mysql_fetch_array($cursor2))
                {
                    $PERCENT=$ROW2["PERCENT"];
                    $SUM_PERCENT=$SUM_PERCENT + $PERCENT;
                    $PERSON++;
                }
            }
            $TOK1=strtok(",");

        }
        if($PERSON!=0)
            $TOTAL_PERCENT=round($SUM_PERCENT/($PERSON*100)*100);
        $MY_FLAG=0;
        if($SUSPEND_FLAG==1)
        {
            if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
            {
                $STATUS=1;
                $STATUS_DESC=_("未开始");
            }
            else
            {
                $STATUS=2;
                $STATUS_DESC="<font color='#00AA00'><b>"._("进行中")."</b></font>";
            }

            if($END_DATE!="0000-00-00")
            {
                if(compare_date($CUR_DATE,$END_DATE)>=0)
                {
                    $STATUS=3;
                    $STATUS_DESC="<font color='#FF0000'><b>"._("已结束")."</b></font>";
                    $MY_FLAG=1;
                }
            }
        }
        else
        {
            $STATUS=2;
            $STATUS_DESC="<font color='#FF0000'><b>"._("暂停")."</b></font>";
            $MY_FLAG=1;
        }

        if($PLAN_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";

        if($END_DATE=="0000-00-00")
            $END_DATE="";
        ?>
        <tr class="<?=$TableLine?>">
            <td align="center"><?=$PLAN_ID?></td>
            <td align="left"><a href="javascript:plan_detail('<?=$PLAN_ID?>');"><?=$NAME?></a>
                <input type="button"  value="<?=_("进度图")?>" class="SmallButton" onClick="window.open('progress_map.php?PLAN_ID=<?=$PLAN_ID?>&HINT_FLAG=<?=$HINT_FLAG?>&STATUS=<?=$STATUS?>','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes');" title="<?=_("查看进度图")?>">
            </td>
            <td nowrap align="center"><?=$TOTAL_PERCENT?>%</td>
            <td nowrap align="center"><?=$BEGIN_DATE?></td>
            <td nowrap align="center"><?=$END_DATE?></td>
            <td nowrap align="center"><?=$TYPE_DESC?></td>
            <td align="center"><?=$MANAGE_NAME?></td>
            <td style="cursor:hand" title="<?=$PARTICIPATOR_NAME_TITLE?>"><?=$PARTICIPATOR_NAME?></td>
            <td align="left">
                <?
                if($ATTACHMENT_NAME=="")
                    echo _("无");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,0,0,1,0,0);
                ?>
            </td>
            <td nowrap align="center"><?=$STATUS_DESC?></td>
            <td nowrap align="center">
                <?
                if($MY_FLAG!=1 && $HINT_FLAG!=1)
                {
                    ?>
                    <a href="javascript:open_diary('<?=$PLAN_ID?>','<?=$HINT_FLAG?>');"><?=_("进度日志")?></a>&nbsp;
                    <?
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>

</body>
</html>