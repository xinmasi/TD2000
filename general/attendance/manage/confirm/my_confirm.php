<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');
$CUR_TIME=date("Y-m-d H:i:s",time());

if($ATTEND_TYPE=="")
{
    $ATTEND_TYPE="all";
}

if($BTN_OP!="")
{
    $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-".$DAY));
    if(stristr($BTN_OP, "month"))
    {
        $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-01"));
    }

    $YEAR=date("Y",$DATE);
    $MONTH=date("m",$DATE);

    if(!stristr($BTN_OP, "month"))
    {
        $DAY=date("d",$DATE);
    }
}

$ALL_ATTEND=array("all" => _("所有审批"), "ATTEND_OUT" => _("外出审批"), "ATTEND_LEAVE" => _("请假审批"), "ATTEND_EVECTION" => _("出差审批"), "ATTENDANCE_OVERTIME" => _("加班审批"));
if(!$YEAR)
{
    $YEAR = $CUR_YEAR;
}
if(!$MONTH)
{
    $MONTH = $CUR_MON;
}
if(!$DAY)
{
    $DAY = $CUR_DAY;
}

if(!checkdate($MONTH,$DAY,$YEAR))
{
    $DAY=date("t", strtotime($YEAR."-".$MONTH."-01"));
}
$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$MONTH_BEGIN=strtotime($YEAR."-".$MONTH."-01");
$MONTH_END=strtotime($YEAR."-".$MONTH."-".date("t",$DATE));

$CUR_TIME=date("Y-m-d H:i:s",time());
$CONDITION_STR="";

$HTML_PAGE_TITLE = _("查看所有审批");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script>
function set_date(id)
{
    var td_cur =$("td_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);
    var div_cur=$("div_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);
    var td=$(id);
    var div=$("div_"+id.substr(3));
    if(!td || !td_cur || !div || !div_cur) return;
    td_cur.className="";
    div_cur.className="TableContent";
    td.className="TableRed";
    div.className="TableRed";
    document.form1.YEAR.value=id.substr(3,4);
    document.form1.MONTH.value=id.substr(7,2);
    document.form1.DAY.value=id.substr(9,2);
}

function init()
{
    var tbl = $("cal_table");
    if(!tbl) return;
    for(i=0;i<tbl.rows.length;i++)
    {
        for(j=0;j<tbl.rows[i].cells.length;j++)
        {
            var td=tbl.rows[i].cells[j];
            if(td.id.substr(0,3)=="td_")
            {
                td.onmouseover=function ()
                {
                    var td=$(this.id);
                    var div=$("div_"+this.id.substr(3));
                    td.className="TableRed";
                    div.className="TableRed";
                }
                td.onmouseout=function ()
                {
                    var td=$(this.id);
                    var div=$("div_"+this.id.substr(3));
                    td.className="";
                    div.className="TableContent";
                }
            }
        }
    }
}

function set_status2(status)
{
    document.form1.ATTEND_TYPE.value=status;
    My_Submit();
}

function set_view2(view, cname)
{
    if(cname=="" || typeof(cname)=='undefined') cname="cal_view";
    var exp = new Date();
    exp.setTime(exp.getTime() + 24*60*60*1000);
    document.cookie = cname+"="+ escape (view) + ";expires=" + exp.toGMTString()+";path=/";

    var url=view+'.php?PAIBAN_TYPE='+document.form1.PAIBAN_TYPE.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
    if(document.form1.DEPT_ID) url+='&DEPT_ID='+document.form1.DEPT_ID.value;
    location=url;
}

function my_note2(ATTEND_TYPE,ATTEND_ID)
{
    myleft=(screen.availWidth-600)/2;
    mytop=(screen.availHeight-500)/2;
    URL="attend_detail.php?ATTEND_ID="+ATTEND_ID+"&ATTEND_TYPE="+ATTEND_TYPE;
    window.open(URL,"<?=$ALL_ATTEND["ATTEND_TYPE"]?>","height=500,width=600,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}
</script>

<br>
<body class="bodycolor" onload="init();">
<table border="0" topmargin="5" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> 
        <?
            if($ATTEND_TYPE=="all")
            {
                echo _("查看所有审批");
            }else if($ATTEND_TYPE=="ATTEND_OUT")
            {
                echo _("查看外出审批");
            }else if($ATTEND_TYPE=="ATTEND_LEAVE")
            {
                echo _("查看请假审批");
            }else if($ATTEND_TYPE=="ATTEND_EVECTION")
            {
                echo _("查看出差审批");
            }else if($ATTEND_TYPE=="ATTENDANCE_OVERTIME")
            {
                echo _("查看加班审批");
            }
        ?>
        </span></td>
        <td>
            <div class="small" style="clear:both;">
                <div style="float:right;">
                    <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
                        <input type="hidden" value="" name="BTN_OP">
                        <input type="hidden" value="<?=$_GET['ATTEND_TYPE']?>" name="ATTEND_TYPE">
                        <input type="hidden" value="<?=$DAY?>" name="DAY">
                        <input type="button" value="<?=_("本月")?>" class="SmallButton" title="<?=_("今天")?>" onclick="location='<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>'">
                        <!-------------- 年 ------------>
                        <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
                        <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a>
                        <select name="YEAR" class="SmallSelect" onchange="My_Submit();">
<?
for($I=2000;$I<=2030;$I++)
{
?>
    <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
}
?>
                        </select>
                        <!-------------- 月 ------------>
                        <select name="MONTH" class="SmallSelect" onchange="My_Submit();">
<?
for($I=1;$I<=12;$I++)
{
    if($I<10)
    {
        $I="0".$I;
    }
?>
    <option value="<?=$I?>" <? if($I==$MONTH) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
}
?>
                        </select>
                        <a href="javascript:set_mon(1);" class="ArrowButtonR" title="<?=_("下一月")?>"></a>
                        <a href="javascript:set_year(1);" class="ArrowButtonRR" title="<?=_("下一年")?>"></a>&nbsp;
                        <a id="status" href="javascript:;" class="dropdown" onclick="showMenu(this.id,'1');" hidefocus="true"><span><?=$ALL_ATTEND["$ATTEND_TYPE"]?><?=menu_arrow("DOWN")?></span></a>&nbsp;
                        <div id="status_menu" class="attach_div">
                            <a href="javascript:set_status2('all');" style="color:#0000FF;"><?=_("所有审批")?></a>
                            <a href="javascript:set_status2('ATTEND_OUT');" style="color:#0000FF;"><?=_("外出审批")?></a>
                            <a href="javascript:set_status2('ATTEND_LEAVE');" style="color:#0000FF;"><?=_("请假审批")?></a>
                            <a href="javascript:set_status2('ATTEND_EVECTION');" style="color:#0000FF;"><?=_("出差审批")?></a>
                            <a href="javascript:set_status2('ATTENDANCE_OVERTIME');" style="color:#0000FF;"><?=_("加班审批")?></a>
                        </div>
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>

<?
if($ATTEND_TYPE!="all")
{
    $arr=get_attend($ATTEND_TYPE,$MONTH_BEGIN,$MONTH_END,$CONDITION_STR);
    $CAL_ARRAY=$arr["CAL_ARRAY"];
    include("show_cal.inc.php");
}
else if($ATTEND_TYPE=="all")
{
    $CAL_ARRAY=array();
    $arr_out=get_attend("ATTEND_OUT",$MONTH_BEGIN,$MONTH_END,$CONDITION_STR);
    $arr_leave=get_attend("ATTEND_LEAVE",$MONTH_BEGIN,$MONTH_END,$CONDITION_STR);
    $arr_evection=get_attend("ATTEND_EVECTION",$MONTH_BEGIN,$MONTH_END,$CONDITION_STR);
    $arr_overtime=get_attend("ATTENDANCE_OVERTIME",$MONTH_BEGIN,$MONTH_END,$CONDITION_STR);

    for($k=1;$k<=31;$k++)
    {
        $CAL_ARRAY[$k]="";
        if($arr_out["CAL_ARRAY"][$k]!="")
        {
            $CAL_ARRAY[$k].="<b>"._("外出")."</b>"._("：")."<ul>".$arr_out["CAL_ARRAY"][$k]."</ul>";
        }
        if($arr_leave["CAL_ARRAY"][$k]!="")
        {
            $CAL_ARRAY[$k].="<b>"._("请假")."</b>"._("：")."<ul>".$arr_leave["CAL_ARRAY"][$k]."</ul>";
        }
        if($arr_evection["CAL_ARRAY"][$k]!="")
        {
            $CAL_ARRAY[$k].="<b>"._("出差")."</b>"._("：")."<ul>".$arr_evection["CAL_ARRAY"][$k]."</ul>";
        }
        if($arr_overtime["CAL_ARRAY"][$k]!="")
        {
            $CAL_ARRAY[$k].="<b>"._("加班")."</b>"._("：")."<ul>".$arr_overtime["CAL_ARRAY"][$k]."</ul>";
        }
    }
    include("show_cal.inc.php");
}
?>
<br>
<div align="center">
     <input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close()" />
</div>
</body>
</html>

<?
function get_attend($ATTEND_TYPE,$MONTH_BEGIN,$MONTH_END,$CONDITION_STR)
{
    if($_SESSION["LOGIN_USER_PRIV"]!="1")
    {
        if($ATTEND_TYPE=="ATTENDANCE_OVERTIME")
        {
            $LEADER_STR=" and ATTENDANCE_OVERTIME.APPROVE_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
        }
        else
        {
            $LEADER_STR=" and $ATTEND_TYPE.LEADER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
        }
    }
    if($ATTEND_TYPE=="ATTEND_OUT")
    {
        $query= "SELECT * from $ATTEND_TYPE,USER where $ATTEND_TYPE.USER_ID=USER.USER_ID ".$LEADER_STR.$CONDITION_STR." and ($ATTEND_TYPE.CREATE_DATE>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and $ATTEND_TYPE.CREATE_DATE<='".date("Y-m-d",$MONTH_END)." 23:59:59') order by $ATTEND_TYPE.CREATE_DATE";
    }
    else if($ATTEND_TYPE=="ATTEND_EVECTION")
    {
        $query = "SELECT * from $ATTEND_TYPE,USER where $ATTEND_TYPE.USER_ID=USER.USER_ID ".$LEADER_STR.$CONDITION_STR." and ($ATTEND_TYPE.RECORD_TIME>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and $ATTEND_TYPE.RECORD_TIME<='".date("Y-m-d",$MONTH_END)." 23:59:59') order by $ATTEND_TYPE.RECORD_TIME";
    }
    else
    {
        $query = "SELECT * from $ATTEND_TYPE,USER where $ATTEND_TYPE.USER_ID=USER.USER_ID ".$LEADER_STR.$CONDITION_STR." and ($ATTEND_TYPE.RECORD_TIME>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and $ATTEND_TYPE.RECORD_TIME<='".date("Y-m-d",$MONTH_END)." 23:59:59') order by $ATTEND_TYPE.RECORD_TIME";
    }
    //echo $query;echo "<br>";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_ID        = $ROW["USER_ID"];
        $USER_NAME      = $ROW["USER_NAME"];
        $ALLOW          = $ROW["ALLOW"];
        $DEPT_ID        = $ROW["DEPT_ID"];

        if($ATTEND_TYPE=="ATTEND_OUT")
        {
            $RECORD_TIME=$ROW["CREATE_DATE"];
        }
        //在它申请的时间
        else if($ATTEND_TYPE=="ATTEND_EVECTION")
        {
            $RECORD_TIME=$ROW["RECORD_TIME"];
        }
        else
        {
            $RECORD_TIME=$ROW["RECORD_TIME"];
        }

        $USER_DEPT_NAME = substr(GetDeptNameById($DEPT_ID),0,-1);
        $DESC_STR="";
        if($USER_NAME!="")
        {
            $DESC_STR.=$USER_NAME;
        }
        if($USER_DEPT_NAME!="")
        {
            $DESC_STR.="(".$USER_DEPT_NAME.")";
        }

        if($ATTEND_TYPE=="ATTEND_OUT")
        {
            $ATTEND_ID=$ROW["OUT_ID"];
            $STATUS=$ROW["STATUS"];
            if($ALLOW=="0" && $STATUS=="0")
            {
                $ALLOW_DESC=_("待审批");
            }
            else if($ALLOW=="1" && $STATUS=="0")
            {
                $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
            }
            else if($ALLOW=="2" && $STATUS=="0")
            {
                $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
            }
            else if($ALLOW=="1" && $STATUS=="1")
            {
                $ALLOW_DESC=_("已归来");
            }
        }

        if($ATTEND_TYPE=="ATTEND_LEAVE")
        {
            $ATTEND_ID=$ROW["LEAVE_ID"];
            $STATUS=$ROW["STATUS"];

            if($ALLOW=="0" && $STATUS=="1")
            {
                $ALLOW_DESC=_("待审批");
            }
            else if($ALLOW=="1" && $STATUS=="1")
            {
                $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
            }
            else if($ALLOW=="2" && $STATUS=="1")
            {
                $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
            }
            else if($ALLOW=="3" && $STATUS=="1")
            {
                $ALLOW_DESC=_("申请销假");
            }
            else if($ALLOW=="3" && $STATUS=="2")
            {
                $ALLOW_DESC=_("已销假");
            }
        }

        if($ATTEND_TYPE=="ATTEND_EVECTION")
        {
            $ATTEND_ID=$ROW["EVECTION_ID"];
            $ALLOW=$ROW["ALLOW"];
            $STATUS=$ROW["STATUS"];
            if($ALLOW=="0" && $STATUS=="1")
            {
                $ALLOW_DESC=_("待审批");
            }
            else if($ALLOW=="1" && $STATUS=="1")
            {
                $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
            }
            else if($ALLOW=="2" && $STATUS=="1")
            {
                $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
            }
            else if($ALLOW=="1" && $STATUS=="2")
            {
                $ALLOW_DESC=_("已归来");
            }
        }

        if($ATTEND_TYPE=="ATTENDANCE_OVERTIME")
        {
            $ATTEND_ID=$ROW["OVERTIME_ID"];
            $STATUS=$ROW["STATUS"];
            if($ALLOW=="0" && $STATUS=="0")
            {
                $ALLOW_DESC=_("待审批");
            }
            else if($ALLOW=="1" && $STATUS=="0")
            {
                $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
            }
            else if($ALLOW=="2" && $STATUS=="0")
            {
                $ALLOW_DESC= "<font color=\"red\">"._("未批准")."</font>";
            }
            else if($ALLOW=="3" && $STATUS=="0")
            {
                $ALLOW_DESC=_("申请确认");
            }
            else if($ALLOW=="3" && $STATUS=="1")
            {
                $ALLOW_DESC=_("已确认");
            }
        }

        $CAL_ARRAY[date("j",strtotime($RECORD_TIME))].="
            <li style='white-space: nowrap;'>
            <a id=\"cal_".$ATTEND_TYPE."_".$ATTEND_ID."\" href='javascript:my_note2(\"$ATTEND_TYPE\",$ATTEND_ID);'>
            ".$DESC_STR."</a>".$ALLOW_DESC."</li>";
    }

    $return_arr=array();
    $return_arr["DESC_STR"]=$DESC_STR;
    $return_arr["CAL_ARRAY"]=$CAL_ARRAY;
    return $return_arr;
}
?>
