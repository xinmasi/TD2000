<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");

$PARA_ARRAY     = get_sys_para("SMS_REMIND");
$PARA_VALUE        = $PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY     = explode("|", $PARA_VALUE);
$SMS_REMIND     = $REMIND_ARRAY[0];  //事物提醒默认
$SMS2_REMIND     = $REMIND_ARRAY[1];  //手机短信提醒默认
$SMS3_REMIND     = $REMIND_ARRAY[2];   //事务提醒权限

function check_car($VU_ID,$V_ID,$VU_START,$VU_END)
{
    $CUR_TIME=date("Y-m-d H:i:s",time());
    $query="SELECT VU_START,VU_END,VU_ID,VU_STATUS,VU_FINAL_END FROM vehicle_usage WHERE VU_ID!='$VU_ID' and V_ID='$V_ID' and (VU_STATUS in('1','2') and DMER_STATUS='1' or VU_STATUS in('1','2') and DMER_STATUS='0') and SHOW_FLAG='1'";
    $cursor=exequery(TD::conn(),$query);
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $VU_START1        = $ROW["VU_START"];
        $VU_END1          = $ROW["VU_END"];
        $VU_STATUS1       = $ROW["VU_STATUS"];
        $VU_FINAL_END1    = $ROW["VU_FINAL_END"];

        if($VU_END1<=$CUR_TIME)
            $VU_END1=$CUR_TIME;

        if(($VU_START1 >= $VU_START and $VU_END1 <= $VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_START) or ($VU_START1 < $VU_END and $VU_END1>$VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_END))
        {
            $COUNT++;
            $VU_IDD     = $ROW["VU_ID"];
            break;
        }
    }
    $VU_ID = $VU_IDD;
    if($COUNT >= 1)
        return $VU_ID;
    else
        return "#";
}

$HTML_PAGE_TITLE = _("车辆使用管理");
include_once("inc/header.inc.php");
?>
<style type="text/css">
.shade{
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(226, 222, 222, 0.65);
    top: 0;
    left:0px;
    z-index: 5;
    display:none;
    position:absolute;
}

.terrace{
    display:none;
    margin-left:auto;
    margin-right:auto;
    position: absolute;
    z-index: 11;
    background: white;
    height:200px;
    width:700px;
    padding-left:20px;
    padding-right:20px;
    padding-top:20px;
    overflow:hidden;
}

.close{
    float:right;
    background-image:url();
}

.form{
    margin-left:5px;
    margin-right:5px;
    margin-top:15px;
}
</style>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script>
function delete_usage(VU_ID,VU_STATUS)
{
    msg='<?=_("确认要删除该车辆使用申请吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?VU_ID=" + VU_ID + "&VU_STATUS=" + VU_STATUS;
        window.location=URL;
    }
}

function receive_usage(VU_ID,V_ID)
{
    msg='<?=_("确认要收回使用中的车辆吗？")?>';
    if(window.confirm(msg))
    {
        URL="checkup.php?VU_ID=" + VU_ID + "&VU_STATUS=" + 4 + "&V_ID=" + V_ID;
        window.location=URL;
    }
}

function order_by(field,asc_desc,vu_status)
{
    window.location="query.php?FIELD="+field+"&ASC_DESC="+asc_desc+"&VU_STATUS="+vu_status;
}

function operator_reason(VU_ID)
{
    URL="operator_reason.php?VU_ID=" + VU_ID + "&VU_STATUS=" + 3;
    window.open(URL,"operator_reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
}

function form_view(RUN_ID)
{
    window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

function time_status(str,VU_ID)
{
    if(str=="1")
        document.getElementById("receive_"+VU_ID).style.display='';
    if(str=="0")
        document.getElementById("receive_"+VU_ID).style.display='none';
}
function remind_cont(vu_id)
{
    showPanel('terrace');
    document.getElementById('shade').style.display='block';
    document.getElementById('terrace').style.display='block';
    $("input[name='VU_ID']").val(vu_id);
    $.post('car_option.php',{vu_id:vu_id},function(data){
        $("#VU_DRIVER").html(data);
    });

    // $.ajax({
    // type: "GET",
    // url: "car_option.php",
    // data: "vu_id="+vu_id,
    // success: function(data){
    // $("#VU_DRIVER").html(data);
    // }
    // });
}

function showBG()
{
    var scrollHeight = document.documentElement.scrollHeight!=0?document.documentElement.scrollHeight:document.body.scrollHeight;
    var sTop = document.documentElement.scrollTop==0?document.body.scrollTop:document.documentElement.scrollTop;
    $("#shade").css({
        "top":sTop,
        'height'    : scrollHeight
    }).fadeIn('normal');
    station=true;
}
function showPanel(str)
{
    current    = str;
    obj = $('#'+str);
    showBG();
    var clientWidth = document.documentElement.clientWidth==0?document.body.clientWidth:document.documentElement.clientWidth;
    var clientHeight = document.documentElement.clientHeight==0?document.body.clientHeight:document.documentElement.clientHeight;
    var sTop = document.documentElement.scrollTop==0?document.body.scrollTop:document.documentElement.scrollTop;
    var divWidth = obj.width();
    var divHeight = obj.height();
    var divLeft = clientWidth / 2 - divWidth / 2;
    var divTop = clientHeight / 2 - divHeight / 2;
    if(divTop <= 0){
        divTop = 0;
    }
    obj.css({
        "z-index": 99,
        "top": divTop+sTop,
        "left": divLeft
    }).fadeIn('normal');
}
function clean_cont()
{
    document.getElementById('shade').style.display='none';
    document.getElementById('terrace').style.display='none';
}
</script>

<body class="bodycolor">

<?
//修改事务提醒状态--yc
update_sms_status('9',0);

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动使用----------
$query = "SELECT VU_ID,V_ID,VU_START FROM vehicle_usage WHERE VU_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $VU_ID3       = $ROW["VU_ID"];
    $V_ID3        = $ROW["V_ID"];
    $VU_START3    = $ROW["VU_START"];

    $VU_ID3       = intval($VU_ID3);
    $query2="SELECT USEING_FLAG FROM vehicle WHERE V_ID='$V_ID3'";

    $cursor3=exequery(TD::conn(),$query2);
    if ($ROW3=mysql_fetch_array($cursor3))
    {
        $USEING_FLAG2    = $ROW3["USEING_FLAG"];
    }

    if($CUR_TIME>=$VU_START3 && $USEING_FLAG2=='0')
    {
        exequery(TD::conn(),"UPDATE vehicle_usage SET VU_STATUS= '2' WHERE VU_ID='$VU_ID3'");
        exequery(TD::conn(),"UPDATE vehicle SET USEING_FLAG= '1' WHERE V_ID='$V_ID3'");
    }
}

if (!$PAGE_SIZE)
    $PAGE_SIZE=15;
if (!isset($start) || $start=="")
    $start=0;

$query="SELECT * FROM vehicle_usage WHERE VU_STATUS='1' OR VU_STATUS='2'";
$cursor=exequery(TD::conn(), $query);
while ($ROW=mysql_fetch_array($cursor))
{
    $VU_ID3          = $ROW["VU_ID"];
    $V_ID3           = $ROW["V_ID"];
    $IS_BACK         = $ROW["IS_BACK"];
    $VU_END_TIME     = $ROW["VU_END"];
    $VU_STATUS1      = $ROW["VU_STATUS"];
    if ($IS_BACK==0 && $VU_END_TIME<=$CUR_TIME && $VU_STATUS1=='2')
    {
        exequery(TD::conn(),"UPDATE vehicle_usage SET VU_STATUS= '4', IS_BACK=2 WHERE VU_ID='$VU_ID3'");
        exequery(TD::conn(),"UPDATE vehicle SET USEING_FLAG= '0' WHERE V_ID='$V_ID3'");
    }

    $sql1="SELECT distinct(V_ID) FROM vehicle_usage WHERE V_ID='$V_ID3' and VU_STATUS='2' ";
    $cursor_sql=exequery(TD::conn(), $sql1);

    if (mysql_num_rows($cursor_sql)==0)
    {
        $sql2="UPDATE vehicle SET USEING_FLAG=0 WHERE V_ID='$V_ID3'";
        exequery(TD::conn(), $sql2);
    }
}

//-----自动回收----------
/*$query = "SELECT * FROM VEHICLE_USAGE WHERE VU_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VU_ID3=$ROW["VU_ID"];
   $V_ID3=$ROW["V_ID"];
   $VU_END3=$ROW["VU_END"];
   if($CUR_TIME>=$VU_END3)
   {
         exequery(TD::conn(),"UPDATE VEHICLE_USAGE SET VU_STATUS= '4' WHERE VU_ID='$VU_ID3'");
         exequery(TD::conn(),"UPDATE VEHICLE SET USEING_FLAG= '0' WHERE V_ID='$V_ID3'");
   }
}*/


if($VU_STATUS==0)
    $VU_STATUS_DESC=_("待批申请");
elseif($VU_STATUS==1)
    $VU_STATUS_DESC=_("已准申请");
elseif($VU_STATUS==2)
    $VU_STATUS_DESC=_("使用中车辆");
elseif($VU_STATUS==3)
    $VU_STATUS_DESC=_("未准申请");
elseif($VU_STATUS==4)
    $VU_STATUS_DESC=_("使用结束车辆");

if($_SESSION["LOGIN_USER_PRIV"]!="1" && $VU_STATUS != 2)
    $query = "SELECT count(VU_ID) FROM vehicle_usage WHERE (VU_OPERATOR='".$_SESSION["LOGIN_USER_ID"]."' or VU_OPERATOR1='".$_SESSION["LOGIN_USER_ID"]."') and VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";
else if($_SESSION["LOGIN_USER_PRIV"]!="1" && $VU_STATUS == 2)
    $query = "SELECT count(VU_ID) FROM vehicle_usage WHERE (VU_OPERATOR='".$_SESSION["LOGIN_USER_ID"]."' or VU_OPERATOR1='".$_SESSION["LOGIN_USER_ID"]."') and VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";
else
    $query = "SELECT count(VU_ID) FROM vehicle_usage WHERE VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";

$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;

if($ROW=mysql_fetch_array($cursor))
    $VU_COUNT=$ROW[0];

$TOTAL_ITEMS=$VU_COUNT;

if($VU_COUNT==0)
{

    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?>
    </span>
            </td>
        </tr>
    </table>

    <?
    Message("",_("无").$VU_STATUS_DESC);
    exit;
}
?>
<div>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?></span>
            </td>
            <?
            $MSG = sprintf(_("共%s条车辆记录"),"<span class='big4'>&nbsp;".$VU_COUNT."</span>&nbsp;");
            ?>
            <td valign="bottom" class="small1"><?=$MSG?>
            </td>
            <td align="right" valign="bottom" class="small1"><?=page_bar($start,$VU_COUNT,$PAGE_SIZE)?></td>
        </tr>
    </table>

    <?
    //============================ 显示记录 =======================================
    if($ASC_DESC=="")
        $ASC_DESC="1";
    if($FIELD=="")
        $FIELD="VU_START";

    if($_SESSION["LOGIN_USER_PRIV"]!="1" && $VU_STATUS != 2)
        $query = "SELECT VU_ID,V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_START,VU_END,VU_FINAL_END,VU_MILEAGE,VU_DEPT,VU_STATUS,DEPT_MANAGER FROM vehicle_usage WHERE (VU_OPERATOR='".$_SESSION["LOGIN_USER_ID"]."' or VU_OPERATOR1='".$_SESSION["LOGIN_USER_ID"]."') and VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";
    else if($_SESSION["LOGIN_USER_PRIV"]!="1" && $VU_STATUS == 2)
        $query = "SELECT VU_ID,V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_START,VU_END,VU_FINAL_END,VU_MILEAGE,VU_DEPT,VU_STATUS,DEPT_MANAGER,VU_OPERATOR1 FROM vehicle_usage WHERE (VU_OPERATOR='".$_SESSION["LOGIN_USER_ID"]."' or VU_OPERATOR1='".$_SESSION["LOGIN_USER_ID"]."') and VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";
    else
        $query = "SELECT VU_ID,V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_START,VU_END,VU_FINAL_END,VU_MILEAGE,VU_DEPT,VU_STATUS,DEPT_MANAGER FROM vehicle_usage WHERE VU_STATUS='$VU_STATUS' and SHOW_FLAG='1'";
    $query .= " order by $FIELD";
    if($ASC_DESC=="1")
        $query .= " desc";
    else
        $query .= " asc";

    $query.=" limit $start,$PAGE_SIZE";
    if($ASC_DESC=="0")
        $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
    else
        $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
    $cursor= exequery(TD::conn(),$query);
    $VU_COUNT=0;
    $VU_OPERATOR1 = '';
    while($ROW=mysql_fetch_array($cursor))
    {
    $VU_COUNT++;

    $VU_ID              = $ROW["VU_ID"];
    $V_ID               = $ROW["V_ID"];
    $VU_PROPOSER        = $ROW["VU_PROPOSER"];
    $VU_REQUEST_DATE    = $ROW["VU_REQUEST_DATE"];
    $VU_USER            = $ROW["VU_USER"];
    $VU_START           = $ROW["VU_START"];
    $VU_END             = $ROW["VU_END"];
    $VU_FINAL_END       = $ROW["VU_FINAL_END"];
    $VU_MILEAGE         = $ROW["VU_MILEAGE"];
    $VU_DEPT            = $ROW["VU_DEPT"];
    $VU_STATUS          = $ROW["VU_STATUS"];
    $DEPT_MANAGER       = $ROW["DEPT_MANAGER"];
    if($_SESSION["LOGIN_USER_PRIV"]!="1" && $VU_STATUS == 2)
    {
        $VU_OPERATOR1 = $ROW["VU_OPERATOR1"];
    }

    $query_name = "SELECT USER_NAME FROM user WHERE USER_ID = '$VU_USER'";
    $cursor_name= exequery(TD::conn(),$query_name);
    if($ROW_NAME=mysql_fetch_array($cursor_name)){
        $VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
    }

    $query1 = "SELECT USER_NAME FROM user WHERE USER_ID='$DEPT_MANAGER'";
    $cursor1= exequery(TD::conn(),$query1);
    $DEPT_MANAGER_NAME="";
    if($ROW1=mysql_fetch_array($cursor1))
        $DEPT_MANAGER_NAME=$ROW1["USER_NAME"];

    if($VU_START=="0000-00-00 00:00:00")
        $VU_START="";
    if($VU_END=="0000-00-00 00:00:00")
        $VU_END="";

    if($VU_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";

    $query = "SELECT V_NUM FROM vehicle WHERE V_ID='$V_ID'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
        $V_NUM=$ROW2["V_NUM"];

    if($VU_COUNT==1)
    {
    ?>
    <table class="TableList" width="95%" align="center">
        <tr class="TableHeader">
            <td nowrap align="center"><?=_("车牌号")?></td>
            <td nowrap align="center"><?=_("用车人")?></td>
            <td nowrap align="center"><?=_("部门审批人")?></td>
            <td nowrap align="center" onClick="order_by('VU_START','<?if($FIELD=="VU_START"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>','<?=$VU_STATUS?>');" style="cursor:hand;" width="30%"><u><?=_("开始时间")?></u><?if($FIELD=="VU_START"||$FIELD=="") echo $ORDER_IMG;?></td>
            <td nowrap align="center" onClick="order_by('VU_END','<?if($FIELD=="VU_END") echo 1-$ASC_DESC;else echo "1";?>','<?=$VU_STATUS?>');" style="cursor:hand;" width="20%"><u><?=_("结束时间")?></u><?if($FIELD=="VU_END") echo $ORDER_IMG;?></td>
            <td nowrap align="center" onClick="order_by('VU_FINAL_END','<?if($FIELD=="VU_FINAL_END") echo 1-$ASC_DESC;else echo "1";?>','<?=$VU_STATUS?>');" style="cursor:hand;" width="20%"><u><?=_("实际结束")?></u><?if($FIELD=="VU_FINAL_END") echo $ORDER_IMG;?></td>
            <?if ($VU_STATUS!=4) {?><td nowrap align="center" width="120"><?=_("预约状态")?></td><?}?>

            <td nowrap align="center"><?=_("操作")?></td>
        </tr>
        <?
        }
        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap align="center"><a href="javascript:;" onClick="window.open('../vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=160,resizable=yes');"><?=$V_NUM?></a></td>
            <td nowrap align="center"><?=$VU_USER?></td>
            <td nowrap align="center"><?=$DEPT_MANAGER_NAME?></td>
            <td nowrap align="center"><?=$VU_START?></td>
            <td nowrap align="center"><?=$VU_END?></td>
            <td nowrap align="center">
                <?
                if($VU_STATUS!=4){
                    echo "-";
                }else{
                    echo $VU_FINAL_END;
                }
                ?>
            </td>
            <?
            if($VU_STATUS!=4)
            {
                ?>
                <td nowrap align="center">
                    <?
                    $SS=substr(check_car($VU_ID,$V_ID,$VU_START,$VU_END), 0, 1);
                    //echo $SS;
                    if(!is_number($SS))
                        echo _("无冲突");
                    else
                    {
                        ?>
                        <a href="javascript:;" onClick="window.open('../usage_detail.php?VU_ID=<?=check_car($VU_ID,$V_ID,$VU_START,$VU_END)?>','','height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=yes');"><font color="red"><?=_("预约冲突")?></font></a>
                        <?
                    }
                    ?>
                </td>
            <?}?>
            <td nowrap align="center">
                <?if($VU_OPERATOR1 == ''){?>
                    <a href="javascript:;" onClick="window.open('../usage_detail.php?VU_ID=<?=$VU_ID?>','','height=380,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=200,left=280,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                    <a href="javascript:;" onClick="window.open('../prearrange.php','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=100,resizable=yes');"><?=_("预约情况")?></a><br>
                <?}?>
                <?
                if($VU_STATUS==0)
                {
                    $is_run_hook=is_run_hook("VU_ID",$VU_ID);
                    if($is_run_hook!=0)
                    {
                        ?>

                        <a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
                        <?
                    }
                    else
                    {
                        if(!is_number($SS))
                            //echo "<a href='checkup.php?VU_ID=$VU_ID&VU_STATUS=1'> "._("批准")."</a>&nbsp;";
                            echo "<a href='#' onclick='remind_cont($VU_ID)'>"._("批准")."</a>&nbsp;";
                        ?>
                        <a href="javascript:operator_reason('<?=$VU_ID?>');"> <?=_("不准")?></a>&nbsp;
                        <a href="edit.php?VU_ID=<?=$VU_ID?>&VU_STATUS=<?=$VU_STATUS?>"><?=_("修改")?></a>&nbsp;
                        <?
                    }
                }
                elseif($VU_STATUS==1)
                {
                    echo _("<a href='checkup.php?VU_ID=$VU_ID&VU_STATUS=0'>撤销</a>&nbsp;");
                    echo _("<a href='edit.php?VU_ID=$VU_ID&VU_STATUS=$VU_STATUS'>修改</a>&nbsp;");
                }
                elseif($VU_STATUS==2)
                {
                    ?>
                    <span id="receive_<?=$VU_ID?>" ><a href="javascript:receive_usage('<?=$VU_ID?>','<?=$V_ID?>');"> <?=_("收回")?></a></span>&nbsp;
                    <?if($VU_OPERATOR1 == ''){?>
                    <a href="edit.php?VU_ID=<?=$VU_ID?>&VU_STATUS=<?=$VU_STATUS?>"><?=_("修改")?></a>&nbsp;
                <?}?>
                    <?
                }
                elseif($VU_STATUS==3)
                {
                    echo _("<a href='checkup.php?VU_ID=$VU_ID&VU_STATUS=1'> 批准</a>&nbsp;");
                    echo _("<a href='edit.php?VU_ID=$VU_ID&VU_STATUS=$VU_STATUS'>修改</a>&nbsp;");
                }

                elseif($VU_STATUS==4)
                {
                    ?>
                    <a href="javascript:;" onClick = "window.open('notes.php?VU_ID=<?=$VU_ID?>&VU_STATUS=4','','height=180,width=520,status=on,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=250,resizable=yes,directories=no');"><?=_("出车情况")?></a>
                    <a href="edit.php?VU_ID=<?=$VU_ID?>&VU_STATUS=<?=$VU_STATUS?>"><?=_("修改")?></a>&nbsp;
                    <?
                }
                if($VU_STATUS!=2)
                {
                    ?>
                    <a href="javascript:delete_usage('<?=$VU_ID?>','<?=$VU_STATUS?>');"><?=_("删除")?></a>
                    <?
                }
                ?>
            </td>
        </tr>
        <?
        }

        if($VU_COUNT > 0)
            echo "</table>";
        ?>
</div>

<div class="shade" id="shade"></div>
<div class="terrace" id="terrace">
    <a href="#" style="font-size:13px;" onClick="clean_cont()" class="close"><?=_("×")?></a>
    <span style="font-size:15px;"><?=_("信息提醒")?></span>
    <hr style="margin-top:3px;">
    <form class="form"action="checkup.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td class="TableData" nowrap><?=_("提醒类型：")?></td>
                <td  align="left" class="TableData"><?=sms_remind(9);?></td>
            </tr>
            <tr>
                <td class="TableData" nowrap><?=_("提醒内容：")?></td>
                <td><textarea name="REMIND_CONTENT" class="BigInput" id="REMIND_CONTENT" cols="76" rows="2"><?=$REMIND_CONTENT?></textarea></td>
            </tr>
            <tr>
                <td class="TableData" nowrap><?=_("被提醒人：")?></td>
                <td style="padding-top:2px;"  align="left"><select name="VU_DRIVER" id="VU_DRIVER" style="min-width: 80px;"></select></td>
            </tr>
            <tr>
                <td style="padding-top:2px;" colspan="2" align="center" style="margin-top:15px;">
                    <input type="submit" class="BigButtonA" style="font-size:14px;" value="<?=_('发送')?>" />
                    <input type="button" class="BigButtonA" style="font-size:14px;" onClick="clean_cont()" value="<?=_('取消')?>" />
                </td>
            </tr>
            <input type="hidden" name="VU_ID" value=""/>
            <input type="hidden" name="VU_STATUS" value="1"/>
    </form>
</div>
</body>
</html>