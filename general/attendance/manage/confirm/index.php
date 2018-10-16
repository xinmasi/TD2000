<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;
$query = "select * from SMS2_PRIV";
$cursor1=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
    $TYPE_PRIV=$ROW["TYPE_PRIV"];

$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PARA_VALUE=$ROW["PARA_VALUE"];
//$SMS2_REMIND=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND=substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));

//修改事务提醒状态--yc
update_sms_status('6',0);

$HTML_PAGE_TITLE = _("考勤管理");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>

<script type="text/javascript" src="/static/js/bootstrap/js/bootstrap.js"></script>
<style>
.pink{
    background-color:#FFDFF9!important;
}
.white{
    background-color:#FFFFFF!important;
}
a:hover{
    text-decoration: none;
    cursor: pointer;
}
a:link{
    text-decoration: none;
}
.accordion{
    margin-bottom: 0;
}
.accordion-heading .accordion-toggle {
    display: block;
    padding: 0px 15px;
}
.table{
    margin-bottom: 0;
}
.accordion-inner {
    padding:0;
    border-top:0;
}
.firstTitle{
    display: inline;
    margin-right: 5px;
    color: #b7b6b6;
}
.secondTitle{
    margin-bottom: 5px;
    height: 30px;
    line-height: 30px;
}
.mobile-phone-alarn{
    display: inline-block;
}
.mobile-phone-wrapper{
    display:block;
    line-height:20px;
    height:20px;
    margin-bottom: 3px;
}
.mobile-phone-checked{
    margin-top: 0;
    margin-bottom: 3px !important;
}
</style>
<script Language=JavaScript>
function show_msg(module, msg, time)
{
    if(msg)
    {
        jQuery("#"+module+"_msg").html(msg);
    }
    if(module=="focus")
    {
        jQuery("#"+module+"_div").css("top",document.documentElement.scrollTop);
    }
    jQuery("#"+module+"_div").fadeIn("slow");
    window.setTimeout(hide_msg,time*1000,module);
}

function hide_msg(module)
{
    jQuery('#'+module+'_div').fadeOut('slow')
}

function LoadWindow2(STATUS,LEAVE_ID,USER_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("LEAVE_SMS2_REMIND"+LEAVE_ID).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;
    URL="back.php?STATUS="+STATUS+"&LEAVE_ID="+LEAVE_ID+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG;
    myleft=(screen.availWidth-650)/2;
    window.open(URL,"formul_edit","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function LoadWindow3(STATUS,OVERTIME_ID,USER_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("LEAVE_SMS2_REMIND"+OVERTIME_ID).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;
    URL="overtime_back_edit.php?STATUS="+STATUS+"&OVERTIME_ID="+OVERTIME_ID+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG;
    myleft=(screen.availWidth-650)/2;
    window.open(URL,"formul_edit","height=350,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function out_confirm(CONFIRM,USER_ID,SUBMIT_TIME,count,OUT_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("OUT_SMS2_REMIND"+count).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;

    if(CONFIRM==2)
    {
        URL="out_reason.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&SUBMIT_TIME="+SUBMIT_TIME+"&MOBILE_FLAG="+MOBILE_FLAG+"&OUT_ID="+OUT_ID;
        window.open(URL,"reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
    }
    else
    {
        URL="out_confirm.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&SUBMIT_TIME="+SUBMIT_TIME+"&MOBILE_FLAG="+MOBILE_FLAG+"&OUT_ID="+OUT_ID;
        window.location=URL;
    }
}
function leave_confirm(CONFIRM,USER_ID,LEAVE_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("LEAVE_SMS2_REMIND"+LEAVE_ID).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;

    if(CONFIRM==2)
    {
        URL="leave_reason.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&LEAVE_ID="+LEAVE_ID;
        window.open(URL,"reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
    }
    else
    {
        URL="leave_confirm.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&LEAVE_ID="+LEAVE_ID;
        window.location=URL;
    }
}

function form_view(RUN_ID)
{
    window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

function evection_confirm(CONFIRM,EVECTION_ID,USER_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("EVECTION_SMS2_REMIND"+EVECTION_ID).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;

    if(CONFIRM==2)
    {
        URL="evection_reason.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&EVECTION_ID="+EVECTION_ID;
        window.open(URL,"reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
    }
    else
    {
        URL="evection_confirm.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&EVECTION_ID="+EVECTION_ID;
        window.location=URL;
    }
}
function overtime_confirm(CONFIRM,OVERTIME_ID,USER_ID)
{
    <?
    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
    {
    ?>
    if(document.all("OVERTIME_SMS2_REMIND"+OVERTIME_ID).checked)
        MOBILE_FLAG=1;
    else
    <?
        }
        ?>
        MOBILE_FLAG=0;

    if(CONFIRM==2)
    {
        URL="overtime_reason.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&OVERTIME_ID="+OVERTIME_ID;
        window.open(URL,"reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
    }
    else
    {
        URL="overtime_confirm.php?CONFIRM="+CONFIRM+"&USER_ID="+USER_ID+"&MOBILE_FLAG="+MOBILE_FLAG+"&OVERTIME_ID="+OVERTIME_ID;
        window.location=URL;
    }
}
function view_myconfirm(att_type)
{
    var iTop = (window.screen.availHeight-30-650)/2; 	 //获得窗口的垂直位置
    var iLeft = (window.screen.availWidth-10-1050)/2;   //获得窗口的水平位置
    URL="my_confirm.php?ATTEND_TYPE="+att_type;
    window.open(URL,'my_confirm','height=650,width=1050,top='+iTop+',left='+iLeft+',status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes');
}
function delete_confirm(TY,TY_ID,ID)
{
    var msg='<?=_("确认要删除该记录吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?DELETE_ID="+ID+"&ATTEND_TYPE="+TY+"&ATTEND_ID="+TY_ID;
        window.location=URL;
    }
}
// var iframe = document.getElementById('tabs_506_iframe');
// iframe.style.height = '99%';
// iframe.scrollWidth;
// iframe.style.height = '100%'
// jQuery('#tabs_506_iframe').css('height','99%');
// jQuery('#tabs_506_iframe').css('height','100%');

</script>
<style>
.all_conf{color:#0066cc; font-weight: bold; font-size: 9pt; LINE-HEIGHT:25px;}
.all_conf a:link, .all_conf a:visited {color: #0066cc; text-decoration: none;}
.all_conf a:hover, .all_conf a:active {color: red; text-decoration: underline}
html,body{
    height:100%;
    overflow:hidden;
}
.wrapper{
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    overflow:auto;
    padding:15px;
}
</style>

<body>
<div class="wrapper">
    <div style="margin:15px 0;">
        <a class="" href="view_status.php" title="<?=_("考勤动态")?>"><?=_("考勤动态")?></a>
        <a class=""  href="javascript:view_myconfirm('all');" title="<?=_("查看所有审批")?>"><?=_("查看所有审批")?></a>
    </div>
    <div class="secondTitle ">
        <h5 class="firstTitle"><?=_("外出审批")?></h5>
        <input type="button" value=<?=_("外出代归来")?> class="btn btn-small" onclick="window.open('out/out_back.php','','left=150,top=60,height=600,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes');" title=<?=_("代外出人归来")?>>
    </div>

    <?
    $CUR_DATE=date("Y-m-d",time());
    $HAS_ANY=0;
    $HAS_OPERATE_COUNT=0;

    //------------------------------------- 今日外出审批 ------------------------------->
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query_get="select OUT_ID from ATTEND_OUT where ALLOW='0'";
    else
        $query_get="select OUT_ID from ATTEND_OUT where LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='0'";
    $cursor_get=exequery(TD::conn(),$query_get, $connstatus);
    while($ROW_GET=mysql_fetch_array($cursor_get))
    {
        $OUT_ID_TEM=$ROW_GET["OUT_ID"];
        $is_run_hook_tem=is_run_hook("OUT_ID",$OUT_ID_TEM);
        if($is_run_hook_tem!=0)
        {
            if(enforce_delete($is_run_hook_tem,"ATTEND_OUT","OUT_ID",$OUT_ID_TEM)=="")
                $query_set="update ATTEND_OUT set ORDER_NO=2 where OUT_ID='$OUT_ID_TEM'";
            else
                $query_set="update ATTEND_OUT set ORDER_NO=1 where OUT_ID='$OUT_ID_TEM'";
            exequery(TD::conn(),$query_set);
        }
    }
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query = "SELECT * from ATTEND_OUT,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_OUT.USER_ID=USER.USER_ID and ALLOW='0' order by ATTEND_OUT.ORDER_NO,ATTEND_OUT.CREATE_DATE,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    else
        $query = "SELECT * from ATTEND_OUT,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_OUT.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='0' order by ATTEND_OUT.ORDER_NO,ATTEND_OUT.CREATE_DATE,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $OUT_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $HAS_ANY=1;
        $OUT_COUNT++;
        $OUT_ID=$ROW["OUT_ID"];
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $OUT_TYPE=$ROW["OUT_TYPE"];
        $OUT_TIME1=$ROW["OUT_TIME1"];
        $OUT_TIME2=$ROW["OUT_TIME2"];
        $ALLOW=$ROW["ALLOW"];
        $CREATE_DATE1=$ROW["CREATE_DATE"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
        $REGISTER_IP=$ROW["REGISTER_IP"];

        $ORDER_NO=$ROW["ORDER_NO"];
        if($ORDER_NO==2)
            $CLASS_VIEW="white";
        else
        {
            $CLASS_VIEW="pink";
            $HAS_OPERATE_COUNT++;
        }
        $SUBMIT_TIME1=substr($SUBMIT_TIME,0,10);
        $DEPT_ID=intval($DEPT_ID);
        $query1="select DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        if($OUT_COUNT==1)
        {
    ?>
    <div align=center>
        <table class="table table-bordered">
            <colgroup>
                <col  style="width:100px;"/>
                <col  style="width:80px;" />
                <col  style="width:50px;" />
                <col  style="width:100px;" />
                <col  style="width:100px;" />
                <col  style="width:200px;"   />
                <col  />
                <col  style="width:100px;"/>
            </colgroup>
            <tr class="">
                <th nowrap align="center"><?=_("操作")?></th>
                <th nowrap align="center"><?=_("部门")?></th>
                <th nowrap align="center"><?=_("姓名")?></th>
                <th nowrap align="center"><?=_("申请时间")?></th>
                <th nowrap align="center"><?=_("开始时间")?></th>
                <th nowrap align="center"><?=_("结束时间")?></th>
                <th nowrap align="center"><?=_("外出原因")?></th>
                <th nowrap align="center"><?=_("登记IP")?></th>
            </tr>

            <?
            }

            if($OUT_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr class="">
             <td nowrap align="center" class="">
                <?
                if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                {
                    ?>
                     <div class="mobile-phone-wrapper">
                    <input type="checkbox" class="mobile-phone-checked" name="OUT_SMS2_REMIND<?=$OUT_COUNT?>" id="OUT_SMS2_REMIND<?=$OUT_COUNT?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="OUT_SMS2_REMIND<?=$OUT_COUNT?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                    </div>
                    <?
                }
                $is_run_hook=is_run_hook("OUT_ID",$OUT_ID);
                if($is_run_hook!=0)
                {
                    ?>
               
                    <a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
                    <?
                        echo enforce_delete("$is_run_hook","ATTEND_OUT","OUT_ID","$OUT_ID");
                    ?>
                    <?
                }
                else
                {
                    ?>
               <input type=button class="btn-small btn  btn-primary" onclick="javascript:out_confirm('1','<?=$USER_ID?>','<?=$SUBMIT_TIME?>','<?=$OUT_COUNT?>','<?=$OUT_ID?>');" value=<?=_("批准")?>>&nbsp;
                    <input type=button class="btn btn-small" onclick="javascript:out_confirm('2','<?=$USER_ID?>','<?=$SUBMIT_TIME?>','<?=$OUT_COUNT?>','<?=$OUT_ID?>');" value=<?=_("不批准")?>>
                
                    <?
                }
                ?>
                </td>
                <td nowrap align="center" class=""><?=$USER_DEPT_NAME?></td>
                <td nowrap align="center" class=""><?=$USER_NAME?></td>
                <td nowrap align="center" class=""><?=$CREATE_DATE1?></td>
                <td nowrap align="center" class=""><?=$SUBMIT_TIME1." ".$OUT_TIME1."(".get_week($SUBMIT_TIME1).")"?></td>
                <td nowrap align="center" class=""><?=$SUBMIT_TIME1." ".$OUT_TIME2."(".get_week($SUBMIT_TIME1).")"?></td>
                <td  nowrap style="word-break:break-all" align="left" class=""><?=$OUT_TYPE?></td>
                <td nowrap align="center" class=""><?=$REGISTER_IP?></td>
            </tr>
            <?
            }
            if($OUT_COUNT>0)
            {
            ?>
        </table>
    </div>
<?
}
else
    message("",_("无外出审批申请"));
?>

    <!-- ------ 请假审批 ----- -->
    <div class="secondTitle ">
        <h5 class="firstTitle"><?=_("请假审批")?></h5>
        <input type="button" value=<?=_("请假代销假")?> class="btn btn-small" onclick="window.open('leave/leave_back.php','','left=150,top=60,height=600,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes');" title=<?=_("代请假人销假并确认销假")?>>
    </div>
    <?
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query_get="select LEAVE_ID from ATTEND_LEAVE where STATUS='1' and ALLOW in('0','3')";
    else
        $query_get="select LEAVE_ID from ATTEND_LEAVE where LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and ALLOW in('0','3')";
    $cursor_get=exequery(TD::conn(),$query_get, $connstatus);
    while($ROW_GET=mysql_fetch_array($cursor_get))
    {
        $LEAVE_ID_TEM=$ROW_GET["LEAVE_ID"];
        $is_run_hook_tem=is_run_hook("LEAVE_ID",$LEAVE_ID_TEM);
        if($is_run_hook_tem!=0)
        {
            if(enforce_delete($is_run_hook_tem,"ATTEND_LEAVE","LEAVE_ID",$LEAVE_ID_TEM)=="")
                $query_set="update ATTEND_LEAVE set ORDER_NO=2 where LEAVE_ID='$LEAVE_ID_TEM'";
            else
                $query_set="update ATTEND_LEAVE set ORDER_NO=1 where LEAVE_ID='$LEAVE_ID_TEM'";
            exequery(TD::conn(),$query_set);
        }
    }
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query = "SELECT * from ATTEND_LEAVE,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_LEAVE.USER_ID=USER.USER_ID and status='1' and ALLOW in('0','3') order by ATTEND_LEAVE.ORDER_NO,ATTEND_LEAVE.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    else
        $query = "SELECT * from ATTEND_LEAVE,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_LEAVE.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and status='1' and allow in('0','3') order by ATTEND_LEAVE.ORDER_NO,ATTEND_LEAVE.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    $cursor= exequery(TD::conn(),$query);
    $LEAVE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_COUNT++;
        $HAS_ANY=1;
        $LEAVE_ID=$ROW["LEAVE_ID"];
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
        $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
        $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
        $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
        $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
        $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
        $REGISTER_IP=$ROW["REGISTER_IP"];
        $RECORD_TIME=$ROW["RECORD_TIME"];
        $DESTROY_TIME=$ROW["DESTROY_TIME"];
        if($DESTROY_TIME=="0000-00-00 00:00:00")
            $DESTROY_TIME="";

        $ORDER_NO=$ROW["ORDER_NO"];
        if($ORDER_NO==2)
            $CLASS_VIEW="white";
        else
        {
            $CLASS_VIEW="pink";
            $HAS_OPERATE_COUNT++;
        }
        $ALLOW=$ROW["ALLOW"];

        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_ID=intval($DEPT_ID);
        $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        if($LEAVE_COUNT==1)
        {
    ?>

    <div align=center>
        <table class="table table-bordered" >
            <colgroup>
                <col  style="width:100px;"/>
                <col  style="width:80px;" />
                <col  style="width:50px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  />
                <col  />
                <col  />
                <col   />
                <col  />
                <col  style="width:100px;"/>
               
            </colgroup>
            <tr class="">
                <th nowrap align="center"><?=_("操作")?></th>
                <th nowrap align="center"><?=_("部门")?></th>
                <th nowrap align="center"><?=_("姓名")?></th>
                <th nowrap align="center"><?=_("申请时间")?></th>
                <th nowrap align="center"><?=_("开始时间")?></th>
                <th nowrap align="center"><?=_("结束时间")?></th>
                <th nowrap align="center"><?=_("请假原因")?></th>
                <th nowrap align="center"><?=_("占年休假")?></th>
                <th nowrap align="center"><?=_("申请类型")?></th>
                <th nowrap align="center"><?=_("请假类型")?></th>
                <th nowrap align="center"><?=_("销假申请时间")?></th>
                <th nowrap align="center"><?=_("登记IP")?></th>
            </tr>
            <?
            }

            if($LEAVE_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr class="">
            <td nowrap align="center" class="">
                <?
                if($ALLOW=="0")
                {
                ?>
                <div>
                    <?
                    $query = "select * from SMS2_PRIV";
                    $cursor1=exequery(TD::conn(),$query);
                    if($ROW=mysql_fetch_array($cursor1))
                        $TYPE_PRIV=$ROW["TYPE_PRIV"];
                    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                    {
                        ?>
                         <div class="mobile-phone-wrapper">
                        <input type="checkbox" class="mobile-phone-checked" name="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" id="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                        </div>
                        <?
                    }
                    $is_run_hook=is_run_hook("LEAVE_ID",$LEAVE_ID);
                    if($is_run_hook!=0)
                    {
                        ?>
                        
                            <a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
                            <?
                                echo enforce_delete($is_run_hook,"ATTEND_LEAVE","LEAVE_ID",$LEAVE_ID);
                            ?>
                        </td>
                        <?
                    }
                    else
                    {
                        ?>
                            <input type=button class="btn-small btn  btn-primary" onclick="javascript:leave_confirm('1','<?=$USER_ID?>','<?=$LEAVE_ID?>');" value=<?=_("批准")?>>&nbsp;
                            <input type=button class="btn btn-small" onclick="javascript:leave_confirm('2','<?=$USER_ID?>','<?=$LEAVE_ID?>');" value=<?=_("不批准")?>>&nbsp;
                        <?
                    }
                }else{
                    ?>
                <div>
                    <?
                    $query = "select * from SMS2_PRIV";
                    $cursor1=exequery(TD::conn(),$query);
                    if($ROW=mysql_fetch_array($cursor1))
                        $TYPE_PRIV=$ROW["TYPE_PRIV"];

                    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                    {
                        ?>
                         <div class="mobile-phone-wrapper">
                        <input type="checkbox" class="mobile-phone-checked" name="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" id="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                        </div>
                        <?
                    }
                    ?>
                   
                        <input type=button class="btn btn-small" onclick="javascript:LoadWindow2('2','<?=$LEAVE_ID?>','<?=$USER_ID?>');" value=<?=_("销假")?>>&nbsp;
                    <?
                    }
                    ?>
                </div>
                </td>
                <td nowrap align="center" class=""><?=$USER_DEPT_NAME?></td>
                <td nowrap align="center" class=""><?=$USER_NAME?></td>
                <td align="center" class=""><?=$RECORD_TIME?></td>
                <td align="center" class=""><?=$LEAVE_DATE1."(".get_week($LEAVE_DATE1).")"?></td>
                <td align="center" class=""><?=$LEAVE_DATE2."(".get_week($LEAVE_DATE2).")"?></td>
                <td  nowrap style="word-break:break-all" align="left" class=""><?=$LEAVE_TYPE?></td>
                <td nowrap align="center" class=""><?=$ANNUAL_LEAVE?></td>
                <?
                if($ALLOW=="0")
                {
                    ?>
                    <td nowrap align="center" class=""><?=_("请假申请")?></td>
                    <?
                }else{
                    ?>
                    <td nowrap align="center" class=""><?=_("销假申请")?></td>
                    <?
                }
                ?>
                <td nowrap align="center" class=""><?=$LEAVE_TYPE2?></td>
                <td nowrap align="center" class=""><?=$DESTROY_TIME?></td>
                <td nowrap align="center" class=""><?=$REGISTER_IP?></td>
            </tr>
            <?
            }
            if($LEAVE_COUNT>0)
            {
            ?>
        </table>
    </div>
<?
}
else
    message("",_("无请假审批申请"));
?>
    <!-- ---------出差审批--------  -->
    <div class="secondTitle ">
        <h5 class="firstTitle"><?=_("出差审批")?></h5>
        <input type="button" value=<?=_("出差代归来")?> class="btn btn-small" onclick="window.open('evection/evection_back.php','','left=150,top=60,height=600,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes');" title=<?=_("代出差人归来")?>>
    </div>
    <?
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query_get="select EVECTION_ID from ATTEND_EVECTION where STATUS='1' and ALLOW='0'";
    else
        $query_get="select EVECTION_ID from ATTEND_EVECTION where LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and ALLOW='0'";
    $cursor_get=exequery(TD::conn(),$query_get, $connstatus);
    while($ROW_GET=mysql_fetch_array($cursor_get))
    {
        $EVECTION_ID_TEM=$ROW_GET["EVECTION_ID"];
        $is_run_hook_tem=is_run_hook("EVECTION_ID",$EVECTION_ID_TEM);
        if($is_run_hook_tem!=0)
        {
            if(enforce_delete($is_run_hook_tem,"ATTEND_EVECTION","EVECTION_ID",$EVECTION_ID_TEM)=="")
                $query_set="update ATTEND_EVECTION set ORDER_NO=2 where EVECTION_ID='$EVECTION_ID_TEM'";
            else
                $query_set="update ATTEND_EVECTION set ORDER_NO=1 where EVECTION_ID='$EVECTION_ID_TEM'";
            exequery(TD::conn(),$query_set);
        }
    }
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query = "SELECT * from ATTEND_EVECTION,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_EVECTION.USER_ID=USER.USER_ID and status='1' and allow='0'order by ATTEND_EVECTION.ORDER_NO,ATTEND_EVECTION.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    else
        $query = "SELECT * from ATTEND_EVECTION,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTEND_EVECTION.USER_ID=USER.USER_ID and LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' and status='1' and allow='0' order by ATTEND_EVECTION.ORDER_NO,ATTEND_EVECTION.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $EVECTION_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $EVECTION_COUNT++;
        $HAS_ANY=1;
        $EVECTION_ID=$ROW["EVECTION_ID"];
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $REASON=$ROW["REASON"];
        $REGISTER_IP=$ROW["REGISTER_IP"];
        $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
        $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
        $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
        $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
        $EVECTION_DEST=$ROW["EVECTION_DEST"];

        $ORDER_NO=$ROW["ORDER_NO"];
        if($ORDER_NO==2)
            $CLASS_VIEW="white";
        else
        {
            $CLASS_VIEW="pink";
            $HAS_OPERATE_COUNT++;
        }

        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_ID=intval($DEPT_ID);
        $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        if($EVECTION_COUNT==1)
        {
    ?>
    <div align=center>
        <table class="table table-bordered" >
            <colgroup>
                <col  style="width:100px;"/>
                <col  style="width:80px;" />
                <col  style="width:50px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  />
                <col  style="width:100px;"/>
                
            </colgroup>
            <tr class="">
                <th nowrap align="center"><?=_("操作")?></th>
                <th nowrap align="center"><?=_("部门")?></th>
                <th nowrap align="center"><?=_("姓名")?></th>
                <th nowrap align="center"><?=_("出差地点")?></th>
                <th nowrap align="center"><?=_("开始日期")?></th>
                <th nowrap align="center"><?=_("结束日期")?></th>
                <th nowrap align="center"><?=_("出差原因")?></th>
                <th nowrap align="center"><?=_("登记IP")?></th>
            </tr>
            <?
            }
            if($EVECTION_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr class="">
             <td nowrap align="center" class="">
                <div>
                    <?
                    $query = "select * from SMS2_PRIV";
                    $cursor1=exequery(TD::conn(),$query);
                    if($ROW=mysql_fetch_array($cursor1))
                        $TYPE_PRIV=$ROW["TYPE_PRIV"];

                    if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                    {
                        ?>
                         <div class="mobile-phone-wrapper">
                        <input type="checkbox" class="mobile-phone-checked" name="EVECTION_SMS2_REMIND<?=$EVECTION_ID?>" id="EVECTION_SMS2_REMIND<?=$EVECTION_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="EVECTION_SMS2_REMIND<?=$EVECTION_ID?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                        </div>
                        <?
                    }
                    $is_run_hook=is_run_hook("EVECTION_ID",$EVECTION_ID);
                    if($is_run_hook!=0)
                    {
                        ?>
                            <a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
                            <?
                                echo enforce_delete($is_run_hook,"ATTEND_EVECTION","EVECTION_ID",$EVECTION_ID);
                            ?>
                        <?
                    }
                    else
                    {
                        ?>
                            <input type=button class="btn-small btn  btn-primary" onclick="javascript:evection_confirm('1','<?=$EVECTION_ID?>','<?=$USER_ID?>');" value=<?=_("批准")?>>&nbsp;
                            <input type=button class="btn btn-small" onclick="javascript:evection_confirm('2','<?=$EVECTION_ID?>','<?=$USER_ID?>');" value=<?=_("不批准")?>>&nbsp;
                        <?
                    }
                    ?>
                </div>
                </td>
                <td nowrap align="center" class=""><?=$USER_DEPT_NAME?></td>
                <td nowrap align="center" class=""><?=$USER_NAME?></td>
                <td align="left" class=""><?=$EVECTION_DEST?></td>
                <td nowrap align="center" class=""><?=$EVECTION_DATE1."(".get_week($EVECTION_DATE1).")"?></td>
                <td nowrap align="center" class=""><?=$EVECTION_DATE2."(".get_week($EVECTION_DATE2).")"?></td>
                <td nowrap style="word-break:break-all" align="left" class=""><?=$REASON?></td>
                <td nowrap align="center" class=""><?=$REGISTER_IP?></td>
            </tr>
            <?
            }
            if($EVECTION_COUNT>0)
            {
            ?>
        </table>
    </div>
<?
}
else
    message("",_("无出差审批申请"));
?>

    <!-- ----------------------------------- 加班审批 ----------------------------- -->
    <div class="secondTitle ">
        <h5 class="firstTitle"><?=_("加班审批")?></h5>
        <input type="button" value=<?=_("加班代确认")?> class="btn btn-small" onclick="window.open('overtime/overtime_back.php','','left=150,top=60,height=600,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes');" title=<?=_("代加班人确认申请并确认")?>>
    </div>

    <?
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query_get="select OVERTIME_ID from ATTENDANCE_OVERTIME where STATUS='0' and ALLOW in('0','3')";
    else
        $query_get="select OVERTIME_ID from ATTENDANCE_OVERTIME where APPROVE_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='0' and ALLOW in('0','3')";
    $cursor_get=exequery(TD::conn(),$query_get, $connstatus);
    while($ROW_GET=mysql_fetch_array($cursor_get))
    {
        $OVERTIME_ID_TEM=$ROW_GET["OVERTIME_ID"];
        $is_run_hook_tem=is_run_hook("OVERTIME_ID",$OVERTIME_ID_TEM);
        if($is_run_hook_tem!=0)
        {
            if(enforce_delete($is_run_hook_tem,"ATTENDANCE_OVERTIME","OVERTIME_ID",$OVERTIME_ID_TEM)=="")
                $query_set="update ATTENDANCE_OVERTIME set ORDER_NO=2 where OVERTIME_ID='$OVERTIME_ID_TEM'";
            else
                $query_set="update ATTENDANCE_OVERTIME set ORDER_NO=1 where OVERTIME_ID='$OVERTIME_ID_TEM'";
            exequery(TD::conn(),$query_set);
        }
    }
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query = "SELECT * from ATTENDANCE_OVERTIME,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and STATUS='0' and ALLOW in('0','3') order by ATTENDANCE_OVERTIME.ORDER_NO,ATTENDANCE_OVERTIME.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    else
        $query = "SELECT * from ATTENDANCE_OVERTIME,USER LEFT JOIN USER_PRIV ON USER.USER_PRIV=USER_PRIV.USER_PRIV where ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and APPROVE_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='0' and ALLOW in('0','3') order by ATTENDANCE_OVERTIME.ORDER_NO,ATTENDANCE_OVERTIME.RECORD_TIME,USER_PRIV.PRIV_NO,USER.USER_NO,USER.USER_NAME ";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $OVERTIME_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $OVERTIME_COUNT++;
        $OVERTIME_ID=$ROW["OVERTIME_ID"];
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $APPROVE_ID=$ROW["APPROVE_ID"];
        $RECORD_TIME=$ROW["RECORD_TIME"];
        $START_TIME=$ROW["START_TIME"];
        $END_TIME=$ROW["END_TIME"];
        $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
        $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
        $ALLOW=$ROW["ALLOW"];
        $STATUS=$ROW["STATUS"];
        $REGISTER_IP=$ROW["REGISTER_IP"];

        $ORDER_NO=$ROW["ORDER_NO"];
        if($ORDER_NO==2)
            $CLASS_VIEW="white";
        else
        {
            $CLASS_VIEW="pink";
            $HAS_OPERATE_COUNT++;
        }
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_ID=intval($DEPT_ID);
        $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        if($OVERTIME_COUNT==1)
        {
    ?>
    <div align=center>
        <table class="table table-bordered">
            <colgroup>
                <col    style="width:100px;"/>
                <col  style="width:80px;" />
                <col  style="width:50px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  style="width:150px;" />
                <col  style="width:100px;"/>
                <col  />
                <col  style="width:100px;"/>
            </colgroup>
            <tr class="">
                <th nowrap align="center"><?=_("操作")?></th>
                <th nowrap align="center"><?=_("部门")?></th>
                <th nowrap align="center"><?=_("姓名")?></th>
                <th nowrap align="center"><?=_("申请时间")?></th>
                <th nowrap align="center"><?=_("开始时间")?></th>
                <th nowrap align="center"><?=_("结束时间")?></th>
                <th nowrap align="center"><?=_("加班时长")?></th>
                <th nowrap align="center"><?=_("加班内容")?></th>
                <th nowrap align="center"><?=_("登记IP")?></th>
            </tr>
            <?
            }
            if($OVERTIME_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr class="">
            <td nowrap align="center" class="">
                <div>
                    <?
                    if($ALLOW=="0")
                    {
                        $query = "select * from SMS2_PRIV";
                        $cursor1=exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor1))
                            $TYPE_PRIV=$ROW["TYPE_PRIV"];

                        if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                        {
                            ?>
                             <div class="mobile-phone-wrapper">
                            <input type="checkbox" class="mobile-phone-checked" name="OVERTIME_SMS2_REMIND<?=$OVERTIME_ID?>" id="OVERTIME_SMS2_REMIND<?=$OVERTIME_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="OVERTIME_SMS2_REMIND<?=$OVERTIME_ID?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                            </div>
                            <?
                        }
                        $is_run_hook=is_run_hook("OVERTIME_ID",$OVERTIME_ID);
                        if($is_run_hook!=0)
                        {
                            ?>
                           
                                <a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a>
                                <?
                                    echo enforce_delete($is_run_hook,"ATTENDANCE_OVERTIME","OVERTIME_ID",$OVERTIME_ID);
                                ?>
                            <?
                        }
                        else
                        {
                            ?>
                                <input type=button class="btn-small btn  btn-primary" onclick="javascript:overtime_confirm('1','<?=$OVERTIME_ID?>','<?=$USER_ID?>');" value=<?=_("批准")?>>&nbsp;
                                <input type=button class="btn btn-small" onclick="javascript:overtime_confirm('2','<?=$OVERTIME_ID?>','<?=$USER_ID?>');" value=<?=_("不批准")?>>&nbsp;
                            <?
                        }
                    }else{
                    ?>
                    <div>
                        <?
                        $query = "select * from SMS2_PRIV";
                        $cursor1=exequery(TD::conn(),$query);
                        if($ROW=mysql_fetch_array($cursor1))
                            $TYPE_PRIV=$ROW["TYPE_PRIV"];

                        if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
                        {
                            ?>
                            <div class="mobile-phone-wrapper">
                            <input type="checkbox" class="mobile-phone-checked" name="LEAVE_SMS2_REMIND<?=$OVERTIME_ID?>" id="LEAVE_SMS2_REMIND<?=$OVERTIME_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$OVERTIME_ID?>" class="mobile-phone-alarn"><?=_("手机提醒")?></label>
                            </div>
                            <?
                        }
                        ?>    
                            <input type=button class="btn btn-small" onclick="javascript:LoadWindow3('1','<?=$OVERTIME_ID?>','<?=$USER_ID?>');" value=<?=_("加班确认")?>>&nbsp;
                        
                        <?
                        }
                        ?>
                    </div>
                    </td>
                <td nowrap align="center" class=""><?=$USER_DEPT_NAME?></td>
                <td nowrap align="center" class=""><?=$USER_NAME?></td>
                <td nowrap align="center" class=""><?=$RECORD_TIME?></td>
                <td nowrap align="center" class=""><?=$START_TIME?></td>
                <td nowrap align="center" class=""><?=$END_TIME?></td>
                <td nowrap align="center" class=""><?=$OVERTIME_HOURS?></td>
                <td style="word-break:break-all" class="" align="left"><?=$OVERTIME_CONTENT?></td>
                <td nowrap align="center" class=""><?=$REGISTER_IP?></td>
            </tr>
            <?
            }
            if($OVERTIME_COUNT>0)
            {
            ?>
        </table>
    </div>
<?
}
else
    message("",_("无加班审批申请"));
?>

    <div id="notice_div" style="text-align:center;position: absolute;width:150px;font-size:11pt;height:25px;line-height:25px;right:20px;top:30px;display:none;z-index: 0;background:#DE7293;">
        <span id="notice_msg" style="color:white;font-weight:bold"></span>
    </div>
</div>
</body>
</html>
<?
function enforce_delete($IS_RUN_HOOK,$TY,$TY_ID,$ID)
{
    $query_flow = "SELECT END_TIME,DEL_FLAG FROM FLOW_RUN WHERE RUN_ID = '$IS_RUN_HOOK'";
    $cursor_flow =exequery(TD::conn(),$query_flow);
    $EXIST_RUN=0;
    if($ROW_FLOW=mysql_fetch_array($cursor_flow))
    {
        $EXIST_RUN=1;
        $END_TIME_FLOW = $ROW_FLOW["END_TIME"];
        $DEL_FLAG_FLOW = $ROW_FLOW["DEL_FLAG"];
    }
    if($DEL_FLAG_FLOW == 1 && $EXIST_RUN==1 || $EXIST_RUN==0 || $END_TIME_FLOW!=NULL)
    {
        return $enforce_button = sprintf("<input type=button class=\"btn btn-small\"  onclick=\"javascript:delete_confirm('".$TY."','".$TY_ID."','".$ID."');\" value='"._("强制删除")."' title='"._("流程已结束或删除的情况下,可以强制删除考勤记录")."'>");
    }
}
?>
