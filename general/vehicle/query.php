<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("车辆使用申请");
include_once("inc/header.inc.php");
?>
<script>
function delete_usage(VU_ID,DMER_STATUS,VU_STATUS)
{
    msg='<?=_("确认要删除该记录吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?VU_ID=" + VU_ID + "&DMER_STATUS=" + DMER_STATUS + "&VU_STATUS=" + VU_STATUS;
        window.location=URL;
    }
}

function form_view(RUN_ID)
{
    window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
</script>

<body class="bodycolor">

<?
//修改事务提醒状态--yc
update_sms_status('9',0);

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动使用----------
$query = "SELECT * from VEHICLE_USAGE where VU_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $VU_ID3=$ROW["VU_ID"];
    $V_ID3=$ROW["V_ID"];

    $query2="SELECT USEING_FLAG from VEHICLE where V_ID='$V_ID3'";
    $cursor3=exequery(TD::conn(),$query2);
    if ($ROW3=mysql_fetch_array($cursor3))
    {
        $USEING_FLAG2=$ROW3["USEING_FLAG"];
    }

    $VU_START3=$ROW["VU_START"];
    if($CUR_TIME>=$VU_START3 && $USEING_FLAG2=='0')
    {
        exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '2' where VU_ID='$VU_ID3'");
        exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '1' where V_ID='$V_ID3'");
    }
}

$query="SELECT * FROM VEHICLE_USAGE WHERE VU_STATUS='1' OR VU_STATUS='2'";
$cursor=exequery(TD::conn(), $query);
while ($ROW=mysql_fetch_array($cursor))
{
    $VU_ID3=$ROW["VU_ID"];
    $V_ID3=$ROW["V_ID"];
    $IS_BACK=$ROW["IS_BACK"];
    $VU_END_TIME=$ROW["VU_END"];
    $VU_STATUS1=$ROW["VU_STATUS"];
    if ($IS_BACK==0 && $VU_END_TIME<=$CUR_TIME && $VU_STATUS1=='2')
    {
        exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '4', IS_BACK=2 where VU_ID='$VU_ID3'");
        exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '0' where V_ID='$V_ID3'");
    }

    $sql1="select distinct(V_ID) from VEHICLE_USAGE where V_ID='$V_ID3' and VU_STATUS='2' ";
    $cursor_sql=exequery(TD::conn(), $sql1);
    if (mysql_num_rows($cursor_sql)==0)
    {
        $sql2="update VEHICLE set USEING_FLAG=0 where V_ID='$V_ID3'";
        exequery(TD::conn(), $sql2);
    }

}


//-----自动回收----------
/*$query = "SELECT * from VEHICLE_USAGE where VU_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VU_ID3=$ROW["VU_ID"];
   $V_ID3=$ROW["V_ID"];
   $VU_END3=$ROW["VU_END"];
   if($CUR_TIME>=$VU_END3)
   {
     	exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '4' where VU_ID='$VU_ID3'");
     	exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '0' where V_ID='$V_ID3'");
   }
}*/

if($VU_STATUS==0)
    $VU_STATUS_DESC=_("待批申请");
elseif($VU_STATUS==1)
    $VU_STATUS_DESC=_("已准申请");
elseif($VU_STATUS==2)
    $VU_STATUS_DESC=_("使用中车辆");
elseif($VU_STATUS==3 || $DMER_STATUS==3)
    $VU_STATUS_DESC=_("未准申请");

if($DMER_STATUS==3)
    $WHERE_STR=" and (VU_STATUS='$VU_STATUS' or DMER_STATUS='$DMER_STATUS')";
else if($VU_STATUS==0)
    $WHERE_STR=" and (VU_STATUS='$VU_STATUS' and DMER_STATUS!='3')";
else
    $WHERE_STR=" and VU_STATUS='$VU_STATUS'";

$query = "SELECT count(*) from VEHICLE_USAGE where VU_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."'".$WHERE_STR;

$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
    $VU_COUNT=$ROW[0];

if($VU_COUNT==0)
{
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?></span>
            </td>
        </tr>
    </table>
    <br />
    <?
    Message("",_("无").$VU_STATUS_DESC);
    exit;
}

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?></span>
        </td>
        <?
        $MSG = sprintf(_("共%s条车辆记录"),"<span class='big4'>&nbsp;".$VU_COUNT."</span>&nbsp;");
        ?>
        <td valign="bottom" class="small1"><?=$MSG?>
        </td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("车牌号")?></td>
        <td nowrap align="center"><?=_("用车人")?></td>
        <td nowrap align="center"><?=_("随行人员")?></td>
        <td align="center"><?=_("事由")?></td>
        <td nowrap align="center"><?=_("开始时间")?></td>
        <td nowrap align="center"><?=_("结束时间")?></td>
        <td align="center"><?=_("备注")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>

    <?
    //============================ 显示已发布公告 =======================================
    $query = "SELECT * from VEHICLE_USAGE where VU_PROPOSER='".$_SESSION["LOGIN_USER_ID"]."'".$WHERE_STR."order by VU_START";
    $cursor= exequery(TD::conn(),$query);
    $VU_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $VU_COUNT++;

        $VU_ID=$ROW["VU_ID"];
        $V_ID=$ROW["V_ID"];
        $VU_PROPOSER=$ROW["VU_PROPOSER"];
        $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
        $VU_USER=$ROW["VU_USER"];
        $VU_SUITE=$ROW["VU_SUITE"];
        $VU_START =$ROW["VU_START"];
        $VU_REASON=$ROW["VU_REASON"];
        $VU_START =$ROW["VU_START"];
        $VU_END=$ROW["VU_END"];
        $VU_MILEAGE=$ROW["VU_MILEAGE"];
        $VU_DEPT=$ROW["VU_DEPT"];
        $VU_STATUS=$ROW["VU_STATUS"];
        $VU_REMARK=$ROW["VU_REMARK"];
        $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
        $cursor_name= exequery(TD::conn(),$query_name);
        if($ROW_NAME=mysql_fetch_array($cursor_name)){
            //$VU_USER_ID = $ROW_NAME["USER_NAME"] != ""?$VU_USER:"";
            $VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
        }

        if($VU_START=="0000-00-00 00:00:00")
            $VU_START="";
        if($VU_END=="0000-00-00 00:00:00")
            $VU_END="";

        if($VU_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        $query = "SELECT V_NUM from VEHICLE where V_ID='$V_ID'";
        $cursor2= exequery(TD::conn(),$query);
        if($ROW2=mysql_fetch_array($cursor2))
            $V_NUM=$ROW2["V_NUM"];
        ?>
    <tr class="<?=$TableLine?>">
        <td nowrap align="center"><a href="javascript:;" onClick="window.open('vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=160,resizable=yes');"><?=$V_NUM?></a></td>
        <td nowrap align="center"><?=$VU_USER?></td>
        <td nowrap align="center"><?=$VU_SUITE?></td>
        <td align="center"><?=$VU_REASON?></td>
        <td nowrap align="center"><?=$VU_START?></a></td>
        <td nowrap align="center"><?=$VU_END?></a></td>
        <td align="center"><?=$VU_REMARK?></td>
        <td nowrap align="center">
        <a href="javascript:;" onClick="window.open('usage_detail.php?VU_ID=<?=$VU_ID?>','','height=420,width=520,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=150,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <?
        /*if($VU_STATUS==2)
        {
           echo _("<a href='javascript:;' onclick = \"window.open('notes.php?VU_ID=$VU_ID&VU_STATUS=4','','height=180,width=520,status=on,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=250,resizable=yes,directories=no');\">结束</a>&nbsp;");
        }*/

        $is_run_hook=is_run_hook("VU_ID",$VU_ID);
        if($is_run_hook!=0)
        {
            ?>

            <a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
            <?
        }
        else
        {
            if($VU_STATUS==0||$DMER_STATUS==3)
            {
                echo "<a href=\"new_no.php?VU_ID=$VU_ID&DMER_STATUS=$DMER_STATUS\">"._("修改")."</a>&nbsp;";
                echo "<a href=\"javascript:delete_usage('$VU_ID','$DMER_STATUS','$VU_STATUS');\">"._("删除")."</a>";
            }
            ?>
            </td>
            </tr>
            <?
        }
    }
    ?>

</table>
</body>

</html>
