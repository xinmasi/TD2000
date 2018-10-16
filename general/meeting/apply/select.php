<?
ob_start();
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$meta_str = ob_get_contents();
ob_clean();
$MR_ROOM = $MR_ROOM == '' ? $MR_ID : $MR_ROOM;

if($MR_ID_STR == '')
{
    $query = "SELECT MR_ID FROM meeting_room WHERE find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR) or TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or (TO_ID='' and SECRET_TO_ID='')";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $MR_ID_STR .= $ROW[0].",";
    }
}

$SELECT_STR=$MR_ID_STR;
$WEEKDAYS_ARR=array(_("日"),_("一"),_("二"),_("三"),_("四"),_("五"),_("六"));
if(substr($SELECT_STR,-1)==",")
{
    $SELECT_STR=substr($SELECT_STR,0,-1);
}

$SELECT_STR_ARRAY1=explode(",",$SELECT_STR);
for($i=0;$i < sizeof($SELECT_STR_ARRAY1);$i++)
{
    if($SELECT_STR_ARRAY1[$i]==$MR_ROOM)
    {
        $isSelect = "selected";
    }
    else
    {
        $isSelect = "";
    }
    
    $query = "select MR_NAME  from meeting_room where MR_ID='$SELECT_STR_ARRAY1[$i]'";
    $cursor = exequery(TD::conn(),$query);
    if($ROWS=mysql_fetch_array($cursor))
    {
        $MR_NAME = $ROWS['MR_NAME'];
        $roomList .= "<option value='$SELECT_STR_ARRAY1[$i]' $isSelect>$MR_NAME</option>";
    }
}

function DateCompare($date1, $date2)
{
    $time1 = strtotime(date('Y-m-d',strtotime($date1)));
    $time2 = strtotime(date('Y-m-d',strtotime($date2)));
    
    $t = round(($time1-$time2)/3600/24);
    
    return $t;
}

if($BTIME=="")
{
    $BTIME=date("Y-m-d");
}

if($ETIME=="")
{
    $ETIME=date("Y-m-d",strtotime("+6 day"));
}

$today=$BTIME." 00:00:00";
$week=$ETIME." 23:59:59";
if($MR_ROOM=="")
{
    $SELECT_STR_ROOMS=$SELECT_STR;
}
else
{
    $SELECT_STR_ROOMS=$MR_ROOM;
}

$SELECT_STR_ARRAY=explode(",",$SELECT_STR_ROOMS);
for($i=0;$i < sizeof($SELECT_STR_ARRAY);$i++)
{
    $meeting="";
    $query="select * from meeting where M_ROOM='$SELECT_STR_ARRAY[$i]' and (M_END>='$today' and M_START<='$week') and M_STATUS!=3";
    $cursor = exequery(TD::conn(),$query);
    while($ROWS=mysql_fetch_array($cursor))
    {
        $m_start        = $ROWS["M_START"];
        $m_end          = $ROWS["M_END"];
        $content        = $ROWS["M_NAME"];
        $status         = $ROWS["M_STATUS"];
        $id             = $ROWS["M_ID"];
        
        //申请界面查看现有会议的权限(申请人/查看部门/查看权限/查看人员)
        $s_user_priv = 0;
        $m_proposer     = $ROWS["M_PROPOSER"];
        $m_proposer1    = rtrim(GetUserNameById($m_proposer),",");
        $m_manager      = $ROWS["M_MANAGER"];
        $m_attendee     = $ROWS["M_ATTENDEE"];
        $to_id          = $ROWS["TO_ID"];
        $priv_id        = $ROWS["PRIV_ID"];
        $secret_to_id   = $ROWS["SECRET_TO_ID"];
        
        if($_SESSION["LOGIN_USER_ID"] == 'admin' || $m_proposer == $_SESSION["LOGIN_USER_ID"] || $m_manager == $_SESSION["LOGIN_USER_ID"] || find_id($m_attendee,$_SESSION["LOGIN_USER_ID"]) || $to_id == 'ALL_DEPT' || find_id($to_id,$_SESSION["LOGIN_DEPT_ID"]) || find_id($priv_id,$_SESSION["LOGIN_USER_PRIV"]) || find_id($secret_to_id,$_SESSION["LOGIN_USER_ID"]))
        {
            $s_user_priv = 1;
        }
        
        //会议名称中英文双引号处理
        $content = str_replace('"','\"',$content);
        
        if($status == "4")
        {
            $status = "3";
        }
        
        $m_start = ($m_start <= $today) ? $today : $m_start;
        $m_end = ($m_end >= $week) ? $week : $m_end;
        
        $day = 0;
        $start = 0;
        $limit = 0;
        $cross_day = 0;
        
        $day_start = DateCompare($m_start, $today);
        $day_end = DateCompare($m_end, $today);
        $cross_day = $day_end - $day_start;
        
        $a_m_start_time = explode(":", substr($m_start, 10));
        $a_m_end_time = explode(":", substr($m_end, 10));
        $el_start = $a_m_start_time[0]*60 + $a_m_start_time[1];
        $el_end = $a_m_end_time[0]*60 + $a_m_end_time[1];
        
        if($cross_day > 0)
        {
            for($k=0; $k <= $cross_day; $k++)
            {
                if($k == 0)
                {
                    $start = $el_start;
                    $limit = 24*60 - $start;
                    $meeting .= "{m_proposer:\"".$m_proposer1."\",day:".$day_start.",content:\"".$content."\",start:".$start.",limit:".$limit.",status:\"".$status."\",id:\"".$id."\",s_priv:\"".$s_user_priv."\"},";
                }
                else if($k == $cross_day)
                {
                    $start = 0;
                    $limit = $el_end;
                    $meeting .= "{m_proposer:\"".$m_proposer1."\",day:".$day_end.",content:\"".$content."\",start:".$start.",limit:".$limit.",status:\"".$status."\",id:\"".$id."\",s_priv:\"".$s_user_priv."\"},";
                }
                else
                {
                    $start = 0;
                    $limit = 24*60;
                    $day = $day_start + $k;
                    $meeting .= "{m_proposer:\"".$m_proposer1."\",day:".$day.",content:\"".$content."\",start:".$start.",limit:".$limit.",status:\"".$status."\",id:\"".$id."\",s_priv:\"".$s_user_priv."\"},";
                }
            }
        }
        else
        {
            $count2++;
            $start = $el_start;
            $limit = $el_end - $el_start;
            $meeting .= "{m_proposer:\"".$m_proposer1."\",day:".$day_start.",content:\"".$content."\",start:".$start.",limit:".$limit.",status:\"".$status."\",id:\"".$id."\",s_priv:\"".$s_user_priv."\"},";
        }
    }
    $meeting=rtrim($meeting,",");
    $MR_ID_TEM=$SELECT_STR_ARRAY[$i];
    $query="select MR_NAME,APPLY_WEEKDAYS from meeting_room where MR_ID='$MR_ID_TEM'";
    $cursor = exequery(TD::conn(),$query);
    if($ROWS=mysql_fetch_array($cursor))
    {
        $APPLY_WEEKDAYS = $ROWS["APPLY_WEEKDAYS"];
        $MR_NAME        = $ROWS['MR_NAME'];
        
        $APPLY_WEEKDAYS_ARR=explode(",",$APPLY_WEEKDAYS);
        $WEEKDAYS="";
        for($i=0;$i < count($APPLY_WEEKDAYS_ARR);$i++)
        {
            $WEEKDAYS.=$WEEKDAYS_ARR[$APPLY_WEEKDAYS_ARR[$i]];
        }
        
        $SIGN=1;
        $rooms .= "{name:\"".$MR_NAME."\",id:\"".$MR_ID_TEM."\",blackList:".$SIGN.",meeting:[".$meeting."]},";
    }
}

$rooms=rtrim($rooms,",");

$HTML_PAGE_TITLE = _("会议室预定");
include_once("inc/header.inc.php");

//验证会议室申请权限
$sql = "SELECT * FROM meeting_room WHERE MR_ID = '$MR_ROOM'";
$cur = exequery(TD::conn(),$sql);
if($arr = mysql_fetch_array($cur))
{
    if($arr["TO_ID"]!="ALL_DEPT" && $_SESSION["LOGIN_USER_PRIV"]!=1)
    {
        if(!find_id($arr["TO_ID"],$_SESSION["LOGIN_DEPT_ID"]) && !find_id($arr["SECRET_TO_ID"],$_SESSION["LOGIN_USER_ID"]))
        {
            Message(_("提示"),_("您没有申请该会议室的权限。"));
            exit;
        }
    }
}
echo $meta_str;
?>

<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/images/meeting/inc/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/meeting/book.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.5.1/jquery.ezmark.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.5.1/scroll/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.5.1/scroll/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.5.1/scroll/mwheelIntent.js"></script>
<link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/uicore/jquery.ui.all.css">
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/jquery.jscrollpane.css" rel="stylesheet" type="text/css" />
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/index.css" rel="stylesheet" type="text/css" />
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/global.css" rel="stylesheet" type="text/css" />
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/apply.css" rel="stylesheet" type="text/css" />
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/other.css" rel="stylesheet" type="text/css" />
<link href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/ezmark.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/images/meeting/css/book.css">
<script type="text/javascript">
var weekdays='<?=$WEEKDAYS?>';
Date.prototype.format = function(format){
    var o = {
        "M+" : this.getMonth()+1,
        "d+" : this.getDate(),
        "h+" : this.getHours(),
        "m+" : this.getMinutes(),
        "s+" : this.getSeconds(),
        "q+" : Math.floor((this.getMonth()+3)/3),
        "S" : this.getMilliseconds()
    }
    
    if(/(y+)/.test(format))
    {
        format=format.replace(RegExp.$1,(this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    for(var k in o)if(new RegExp("("+ k +")").test(format))
        format = format.replace(RegExp.$1,RegExp.$1.length==1 ? o[k] :("00"+ o[k]).substr((""+ o[k]).length));
    return format;
}

var bookerDate = initDate("<?=$BTIME?>","<?=$ETIME?>");
function lowerCaseToUpper(num)
{
    var upperWeekNum;
    if (parseInt(num) == 0)
    {
        upperWeekNum = "<?=_("日")?>";
    }
    else if (parseInt(num) == 1)
    {
        upperWeekNum = "<?=_("一")?>";
    }
    else if (parseInt(num) == 2)
    {
        upperWeekNum = "<?=_("二")?>";
    }
    else if (parseInt(num) == 3)
    {
        upperWeekNum = "<?=_("三")?>";
    }
    else if (parseInt(num) == 4)
    {
        upperWeekNum = "<?=_("四")?>";
    }
    else if (parseInt(num) == 5)
    {
        upperWeekNum = "<?=_("五")?>";
    }
    else if (parseInt(num) == 6)
    {
        upperWeekNum = "<?=_("六")?>";
    }
    
    return upperWeekNum;
}

//计算天数差的函数，通用 sDate1和sDate2是2002-12-18格式
function DateDiff(sDate1, sDate2)
{ 
    var aDate, oDate1, oDate2, iDays;
    aDate = sDate1.split("-");
    oDate1 = new Date(aDate[0],aDate[1],aDate[2]);//转换为12-18-2002格式 
    aDate = sDate2.split("-");
    oDate2 = new Date(aDate[0],aDate[1],aDate[2]);
    iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 86400);//把相差的毫秒数转换为天数 
    return iDays;
}
function js_strto_time(str_time){
      var new_str = str_time.replace(/:/g,"-");
      new_str = new_str.replace(/ /g,"-");
      var arr = new_str.split("-");
      var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
      return strtotime = datum.getTime()/1000;
    }
function ifStepMonth(startTimeStr,endTimeStr)
{
    var startTime = startTimeStr + " 00:00:00";
    var endTime = endTimeStr + " 23:59:59";
    var start = js_strto_time(startTime);
    var end   = js_strto_time(endTime);
    var days = (end - start)/86400;
    return Math.abs(days);
}
function initDate(startTimeStr,endTimeStr)
{
    var differ = ifStepMonth(startTimeStr,endTimeStr);
    var nextDate;
    var localDate= new Date(Date.parse(startTimeStr.replace(/-/g,   "/"))); 
    var currentWeek;
    var dateArr=new Array();
    for(var i=0;i<=differ;i++)
    {
        nextDate=localDate.getTime() + i*24*60*60*1000;
        var declareNextDate=new Date(nextDate);
        currentWeek=declareNextDate.getDay();
        var formatDate=declareNextDate.format("yyyy-MM-dd")+"(<?=_('周')?>"+lowerCaseToUpper(currentWeek)+")";
        dateArr[i]=formatDate;
    }
    
    return dateArr;
}

function checkform()
{
    var BTIME=document.form1.BTIME.value;
    var ETIME=document.form1.ETIME.value;
    var datFrom= getDateFromString(BTIME); 
    var datTo = getDateFromString(ETIME); 
    var numDays = (datTo-datFrom)/(1000*60*60*24);
    if(BTIME=="")
    {
        alert("<?=_("请选择开始日期！")?>");
        return (false);
    }
    if(ETIME=="")
    {
        alert("<?=_("请选择结束日期！")?>");
        return (false);
    }
    
    if(BTIME!="" &&  ETIME!="" )
    {
        if(ETIME<BTIME)
        {
            alert("<?=_("结束日期不能小于开始日期！")?>");
            return (false);
        }
        
        if(numDays>=14)
        {
            msg="<?=_("你输入的日期查询跨度超过2周系统加载较慢，确定要继续吗？")?>";
            if(!window.confirm(msg))
            {
                return false;
            }
        }
    }
    form1.submit();
}

function   getDateFromString(strDate)
{
    var  arrYmd = strDate.split("-");
    var  numYear = parseInt(arrYmd[0],10);
    var  numMonth = parseInt(arrYmd[1],10)-1;
    var  numDay= parseInt(arrYmd[2],10);
    var  leavetime=new Date(numYear,numMonth,numDay);
    return leavetime;
}

function display1(t)
{
    var el = document.getElementById("areabox");
    if (t.checked)
    {
        el.className = "areabox hidden";
    }
    else
    {
        el.className = "areabox";
    }
}

$(function() {
    //do active
    $(".meeting:not(.disabled)").live("mousedown", function()
    {
        $(".active.meeting").removeClass("active");
        $(this).addClass("active");
    });
    
    $('html,body').bind('scroll', function()
    {
        fixHaslayout($('.week-booker, .axis-y-name>span'));
    });
    
    $('#show_more').click(function()
    {
        if($('#show_title').css('display') == 'none')
        {
            $('#show_more').css('background','url(/static/theme/10/images/hscroll_arrow.png) -2px center no-repeat');
            $('#show_title').show();
            $('#show_more').attr('title','收缩00:00-07:00');
           // $('#meeting_more').show();
           // $('#meeting').hide();
            $(".axis-x").width('2882px');
            $("#booker_title").width('2883px');
            bookers[0].setStart(0 * 60);
            bookers[0].setLimit(24 * 60 + 1);  
        }
        else
        {
            $('#show_more').css('background','url(/static/theme/10/images/hscroll_arrow.png) -25px center no-repeat');
            $('#show_title').hide();
            $('#show_more').attr('title','展开00:00-07:00');
            //$('#meeting_more').hide();
            //$('#meeting').show();
            $(".axis-x").width('2042px');
            $("#booker_title").width('2043px');
            bookers[0].setStart(7 * 60);
            bookers[0].setLimit(17 * 60 + 1);  
        }
    });
    
    $('input[type=checkbox]').ezMark();
    initMeetings();
    initAxisX();
    document.onselectstart = function()
    {
        return false;    
    }
});

function fixHaslayout(o)
{
    if(o.nodeType)
    {
        o.style.zoom = 1;
        o.style.zoom = 'normal';
    }
    else if(o instanceof jQuery)
    {
        o.each(function(){
            fixHaslayout(this);
        });
    }
}

/**
 * 初始化x轴
 */
function initAxisX()
{
    function number2Time(n)
    {
        var h = Math.floor(n);
        return (h < 10 ? ("0" + h) : h) + ":" + (n - h ? "30" : "00")
    }
    $(".axis-x div").each(function(i, e){
        $(e).html(number2Time(i/2 + 0) + "<br>" + number2Time(i/2 + 0.5));
    });
}

var newMeeting = {};
var bookers = [];
function initMeetings()
{
    var rooms=[<?=$rooms?>];
    $.each(rooms, function(i, e) {
        function handle(index, start, limit){
            newMeeting.id = index;
            newMeeting.day = index;
            //newMeeting.date=bookerDate[index].substr(0,10);
            newMeeting.roomId=e.id;
            newMeeting.start = start;
            newMeeting.limit=limit;
        }
        
        var date = [];
        $.each(bookerDate, function(j, date1) {
            if(weekdays.indexOf(date1.substr(12,1))>=0)
            {
                s=1;
            }
            else
            {
                s=0;
            }
            
            var info = {
                date: date1,
                status: s
            };
            date.push(info);
        });
        
        var wb = new WeekBooker({
            renderTo: "#meeting",
            distance: 30,
            name: e.name,
            date: date,
            update: handle,
            start: 7 * 60,
            limit: 17 * 60 + 1,
            beforecreate: function(day){
                if(e.blackList==0)
                {
                    alert("<?=_("不能申请此会议室，由于您违法了会议室的规章制度！")?>");
                    return false;
                }
                var d = bookerDate[day];
                if(weekdays.indexOf(d.substr(12,1))<0)
                {
                    alert("<?=_("此会议室此时段禁止申请")?>");
                    fixHaslayout($('body > .content'))
                    return false;
                }
            },
            oncreate: handle
        });
        
        bookers.push(wb);
        $.each(e.meeting, function(k, t) {
            var meeting = new Meeting({
            	m_proposer:t.m_proposer,
                content: t.content,
                start: t.start,
                limit: t.limit,
                id: t.id,
                ppm: 2,
                onclick: function(e, meetingId, start, limit){
                    if(t.s_priv == '1')
                    {
                        window.open("../query/meeting_detail.php?M_ID="+t.id,'','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=200,resizable=yes');
                    }
                    else
                    {
                        alert("<?=_("您没有权限查看该会议")?>");
                    }
                },
                status: t.status,
                disabled: true
            });
            
            wb.bookers[t.day].add(meeting);
        });
      
    });
}

function getData()
{
    if(!newMeeting.start && newMeeting.start!=0)
    {
        alert("<?=_("请选择会议室及申请时间！")?>");
        return "";
    }
  //微信申请会议后提示冲突。所以消灭。。
  /*   for (var i in bookers)
    {
        var b = bookers[i];
        if (b.crowd())
        {
            alert("<?=_("会议时间冲突！")?>");
            return false;
        }
    }
     */
    var newMeetingDate=bookerDate[newMeeting.day].substr(0,10);
    var newMeetingStart=newMeeting.start;
    var newMeetingLimit=newMeeting.limit;
    var hourStart = parseInt(newMeetingStart/60);
    if(hourStart < 10)
    {
        hourStart="0"+hourStart;
    }
    var minStart=parseInt(newMeetingStart%60);
    if(minStart==0)
    {
        minStart="00";
    }
    else
    {
        minStart="30";
    }
    var startTime=newMeetingDate+" "+hourStart+":"+minStart+":00";//拼接出开始时间
    var newMeetingEnd=newMeetingStart+newMeetingLimit;
    var hourEnd=parseInt(newMeetingEnd/60);
    if(hourEnd < 10)
    {
        hourEnd="0"+hourEnd;
    }
    
    var minEnd=parseInt(newMeetingEnd%60);
    if(minEnd==0)
    {
        minEnd="00";
    }
    else
    {
        minEnd="30";
    }
    var endTime=newMeetingDate+" "+hourEnd+":"+minEnd+":00";//拼接出结束时间
    var meetingId=newMeeting.roomId;
    url="new.php?NEW=1&MR_ID="+meetingId+"&M_START="+startTime+"&M_END="+endTime;
    location=url;
}
</script>

<body class="bodycolor" style="overflow:auto;position:relative;">
<div id="query" style="align:center;top:0px;">
<form name="form1" action="">
    <table class="TableList" align="center" width="100%">
        <tr>
            <td class="TableContent"  nowrap align="left"><?=_("会议室选择：")?>
                <select name="MR_ROOM"  class="bigSelect" onChange="checkform();">
                    <?=$roomList?>
                </select>&nbsp;&nbsp;
                <?=_("开始时间：")?>
                <input size="15" type="text" class="bigInput" name="BTIME" value="<?=$BTIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">&nbsp;&nbsp;
                <?=_("结束时间：")?>
                <input size="15" type="text" class="bigInput" name="ETIME" value="<?=$ETIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">&nbsp;&nbsp;&nbsp;
                <input  type="button" class="SmallButton" value="<?=_("查询")?>" onClick="return checkform();">
            </td>
            <td class="TableContent"></td>
        </tr>
    </table>
    
    <input type="hidden" name="MR_ID_STR" value="<?=$MR_ID_STR?>">
    <input type="hidden" name="MR_ID" value="<?=$MR_ID?>">
</form>
</div>

<div class="content" style="width:98%;min-width: 1000px;_display:inline-block;position:relative;">
    <div class="legend">
        <b><?=_("图例说明：")?></b>
        <label for=""><img src="<?=MYOA_STATIC_SERVER?>/static/images/meeting/images/book/explain-a.jpg"><span><?=_("未预约")?></span></label>
        <label for=""><img src="<?=MYOA_STATIC_SERVER?>/static/images/meeting/images/book/explain-b.jpg"><span><?=_("待批准")?></span></label>
        <label for=""><img src="<?=MYOA_STATIC_SERVER?>/static/images/meeting/images/book/explain-c.jpg"><span><?=_("已批准")?></span></label>
        <label for=""><img src="<?=MYOA_STATIC_SERVER?>/static/images/meeting/images/book/explain-d.jpg"><span><?=_("进行中")?></span></label>
        <label for=""><img src="<?=MYOA_STATIC_SERVER?>/static/images/meeting/images/book/explain-e.jpg"><span><?=_("已结束")?></span></label>
    </div>

    <div class="contentwrapper" style="padding-left: 160px;">
        <div class="content_left">
            <div class="booker-title" style="width: 100%;">
                <div class="table-title" style="width:152px;">
                    <ul>
                        <li style="width: 149px"><span><?=_("日期")?></span></li>
                    </ul>
                </div>
            </div>
            <div>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div class="axis-y-date" id="meeting_date">
<?
//结束时间-开始时间
$s_from_date=strtotime($BTIME);
$s_to_date=strtotime($ETIME);
$i_num_days = 0;
$s_show_date = "";
$s_week_name = "";
if($s_to_date >= $s_from_date)
{
    for($i = $s_from_date;$i <= $s_to_date;$i = $i + 60*60*24)
    {
        $s_show_date = date("Y-m-d",$s_from_date +$i_num_days*60*60*24);
        
        $s_week_name = date('w',$s_from_date +$i_num_days*60*60*24);
        if($s_week_name == '0')
        {
            $s_week_name = _("日");
        }
        else if($s_week_name == '1')
        {
            $s_week_name = _("一");
        }
        else if($s_week_name == '2')
        {
            $s_week_name = _("二");
        }
        else if($s_week_name == '3')
        {
            $s_week_name = _("三");
        }
        else if($s_week_name == '4')
        {
            $s_week_name = _("四");
        }
        else if($s_week_name == '5')
        {
            $s_week_name = _("五");
        }
        else if($s_week_name == '6')
        {
            $s_week_name = _("六");
        }
        
        echo "<div class='item ".($i_num_days % 2 ? "odd" : "even")."'>".$s_show_date."(周".$s_week_name.")"."</div>";
        $i_num_days++;
    }
}
?>
                                </div>
                            </td>
                            <td id="show_more" style="cursor:pointer;background: url('/static/theme/10/images/hscroll_arrow.png') -25px center no-repeat;" title="<?=_("展开00:00-07:00")?>">
                                <div style="width:8px;">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div style="width:1200px;overflow-x:auto;overflow-y:hidden;position:relative;">
            <div class="booker-title" id="booker_title" style="width: 2043px;">
                <div class="axis-x" style="width:2042px;">
                    <span id="show_title" style="display:none;">
                        <div class="first"></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </span>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="last"></div>
                </div>
                
                <div style="clear:left;"></div>
                <div id="meeting_more" style="display:none;"></div>
                <div id="meeting"></div>
            </div>
        </div>

        <div align="center" style="clear:both;">
        <br />
<?
if(!$_GET["ACTION"]=="SEE")
{
?>
            <input type="button" class="BigButton" value="<?=_("提交")?>" onClick="getData()" />
            <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="window.location='room_manage.php'" />
<?
}
else
{
?>
            <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close()" />
<?
}
?>
        </div>
    </div>
</div>

</body>
</html>