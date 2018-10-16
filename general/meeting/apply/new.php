<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$M_NAME          = $_COOKIE['MEETING_M_NAME'];
$M_TOPIC         = $_COOKIE['MEETING_M_TOPIC'];
$M_ATTENDEE_OUT  = $_COOKIE['MEETING_M_ATTENDEE_OUT'];
$M_ATTENDEE      = $_COOKIE['MEETING_M_ATTENDEE'];
$M_ROOM          = $_COOKIE['M_ROOM'];
$M_DESC          = $_COOKIE['MEETING_M_DESC'];
$SECRET_TO_ID    = $_COOKIE['MEETING_SECRET_TO_ID'];
$PRIV_ID         = $_COOKIE['MEETING_PRIV_ID'];
$TO_ID           = $_COOKIE['MEETING_TO_ID'];
$SYS_PARA_ARRAY  = get_sys_para("SMS_REMIND");
$PARA_VALUE      = $SYS_PARA_ARRAY["SMS_REMIND"];
$SMS_REMIND      = substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND_TMP = substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND     = substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));
if($NEW == "")
    $NEW = $_GET['NEW'];
if($M_ROOM=="")
    $M_ROOM_TEM=$_GET["MR_ID"];

$HTML_PAGE_TITLE = _("会议申请");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/meeting/book.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var $ = function(id) {return document.getElementById(id);};
function Data_Show(){
    var Showtype = document.form1.RD;
    var flag = false;
    var str = '';
    for( i=0;i<Showtype.length;i++ )
    {
        if( Showtype[i].checked == true )
        {
            str = Showtype[i].value;
            flag = true;
            break;
        }
    }
    return str;
}
function CheckForm()
{
    if(document.form1.M_NAME.value=="")
    {
        alert("<?=_("请指定会议名称！")?>");
        return false;
    }
    if(document.form1.RESEND_SEVERAL.value>4)
    {
        alert("<?=_("提醒次数不可大于4次")?>");
        document.getElementById("RESEND_SEVERAL").focus();
        return false;
    }

// 周期性会议申请:否
    if(Data_Show() == '0')
    {
        if(document.form1.M_START.value=="")
        {
            alert("<?=_("开始时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_END.value=="")
        {
            alert("<?=_("结束时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_START.value==document.form1.M_END.value)
        {
            alert("<?=_("开始时间与结束时间不能相等！")?>");
            return false;
        }

        if(document.form1.M_START.value!=""&&document.form1.M_END.value!="")
        {
            var M_START = document.form1.M_START.value.replace("-","/");
            var M_END   = document.form1.M_END.value.replace("-","/");
            M_START = new Date(Date.parse(M_START));
            M_END   = new Date(Date.parse(M_END));
            if(M_START>M_END)
            {
                alert("<?=_("开始时间不能大于结束时间")?>");
                return false;
            }
        }
        document.form1.action="add.php";
    }
//周期性会议申请:按周
    if(Data_Show() == "1")
    {
        if(document.form1.M_START_DATE.value=="")
        {
            alert("<?=_("开始日期不能为空！")?>");
            return false;
        }

        if(document.form1.M_END_DATE.value=="")
        {
            alert("<?=_("结束日期不能为空！")?>");
            return false;
        }

        if(document.form1.M_START_TIME.value=="")
        {
            alert("<?=_("会议开始时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_END_TIME.value=="")
        {
            alert("<?=_("会议结束时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_START_DATE.value!=""&&document.form1.M_END_DATE.value!="")
        {
            var arr=document.form1.M_START_DATE.value.split("-");
            var starttime=new Date(arr[0],arr[1],arr[2]);
            var starttimes=starttime.getTime();

            var arrs=document.form1.M_END_DATE.value.split("-");
            var lktime=new Date(arrs[0],arrs[1],arrs[2]);
            var lktimes=lktime.getTime();

            if(starttimes>lktimes)
            {
                alert('<?=_("结束日期不能小于开始日期！")?>');
                return false;
            }
        }

        if(document.form1.M_START_TIME.value!=""&&document.form1.M_END_TIME.value!="")
        {
            var endTime   = document.form1.M_END_TIME.value;
            var beginTime = document.form1.M_START_TIME.value;
            endTime   = "2000/01/01 "+endTime;
            beginTime = "2000/01/01 "+beginTime;
            if(beginTime>endTime)
            {
                alert('<?=_("会议结束时间不能小于开始时间")?>');
                return false;
            }
            if(beginTime==endTime)
            {
                alert('<?=_("会议结束时间不能等于开始时间")?>');
                return false;
            }
        }
        document.form1.action="add_cycle.php";
    }
//周期性会议：按月
    if(Data_Show() == "2")
    {
        if(document.form1.M_START_DATE1.value=="")
        {
            alert("<?=_("开始日期不能为空！")?>");
            return false;
        }

        if(document.form1.M_END_DATE1.value=="")
        {
            alert("<?=_("结束日期不能为空！")?>");
            return false;
        }

        if(document.form1.M_START_TIME1.value=="")
        {
            alert("<?=_("会议开始时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_END_TIME1.value=="")
        {
            alert("<?=_("会议结束时间不能为空！")?>");
            return false;
        }

        if(document.form1.M_START_DATE1.value!=""&&document.form1.M_END_DATE1.value!="")
        {
            var arr=document.form1.M_START_DATE1.value.split("-");
            var starttime=new Date(arr[0],arr[1],arr[2]);
            var starttimes=starttime.getTime();

            var arrs=document.form1.M_END_DATE1.value.split("-");
            var lktime=new Date(arrs[0],arrs[1],arrs[2]);
            var lktimes=lktime.getTime();

            if(starttimes>lktimes)
            {
                alert('<?=_("结束日期不能小于开始日期！")?>');
                return false;
            }
        }

        if(document.form1.M_START_TIME1.value!=""&&document.form1.M_END_TIME1.value!="")
        {
            var endTime = document.form1.M_END_TIME1.value;
            var beginTime = document.form1.M_START_TIME1.value;
            endTime = "2000/01/01 "+endTime;
            beginTime = "2000/01/01 "+beginTime;
            if(beginTime>endTime)
            {
                alert('<?=_("会议结束时间不能小于开始时间")?>');
                return false;
            }
            if(beginTime==endTime)
            {
                alert('<?=_("会议结束时间不能等于开始时间")?>');
                return false;
            }
        }
        document.form1.action="add_month.php";
    }

    if(document.form1.M_TOPIC.value=="")
    {
        alert("<?=_("请指定会议主题！")?>");
        return false;
    }

    if(document.form1.M_MANAGER != undefined)
    {
        if(document.form1.M_MANAGER.value=="")
        {
            alert("<?=_("请指定会议室管理员！")?>");
            return false;
        }
    }
    if(document.form1.COPY_TO_ID.value =="" && document.form1.M_ATTENDEE_OUT.value=="")
    {
        msg='<?=_("没有设置会议的出席人员，确定提交吗？")?>';
        if(!window.confirm(msg))
        {
            document.form1.COPY_TO_NAME.focus();
            returnfalse;
        }
    }else
    {
        if(Data_Show() == '0')
        {
            var start_m = document.form1.M_START.value;
            var end_m   = document.form1.M_END.value;
        }else if(Data_Show() == '1')
        {
            var start_m = document.form1.M_START_DATE.value + ' ' + document.form1.M_START_TIME.value;
            var end_m   = document.form1.M_END_DATE.value + ' ' + document.form1.M_END_TIME.value;
        }else if(Data_Show() == '2')
        {
            var start_m = document.form1.M_START_DATE1.value + ' ' + document.form1.M_START_TIME1.value;
            var end_m   = document.form1.M_END_DATE1.value + ' ' + document.form1.M_END_TIME1.value;
        }
        var user_id = document.form1.COPY_TO_ID.value;
        var cur_date = jQuery('[name="selectdates"]').val();
        var cur_week = jQuery('[name="selectdates"]').val();

        var weeks = '';
        jQuery('.weeks').find('[type="checkbox"]:checked').each(function(k, v){
            var $this = jQuery(this);
            weeks += $this.attr('data-value')+',';
        });
        //weeks = encodeURIComponent(weeks);
        var is = 1;
        jQuery.ajax({
            url:"ifexists.php",
            type:"POST",
            async:false,
            data:"m_start="+start_m+"&m_end="+end_m+"&user_id="+user_id+"&cur_date="+cur_date+"&weeks="+weeks+"&m_id="+"<?=$M_ID?>",
            success:function(data)
            {
                if($.trim(data) != "")
                {
                    alert(data+"在这个时间段有其他会议参加！");
                    is = 0;
                }
            }
        });
        if(is == 0)
        {
            return false;
        }
    }
    var testInput = document.form1.elements;
    for (var i = 0; i < testInput.length; i++)
    {
        if(testInput[i].className.indexOf("checkUp")!=-1)
        {
            if(testInput[i].value!=testInput[i].defaultValue)
            {

                document.form1.OP_M_STATUS.value="0";
            }

        }
    }
    document.form1.OP.value="1";
    return (true);
}

function InsertImage(src)
{
    AddImage2Editor('M_DESC', src);
}
function sendForm()
{
    if(CheckForm() && ifuse() && ifjiyao())
    {
        document.form1.submit();
    }
}

function upload_attach()
{
    if(CheckForm())
    {
        document.form1.OP.value="0";
        document.form1.submit();
    }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?M_ID=<?=$M_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

function time_status(str)
{
    if(str=='1')
    {
        document.getElementById("time_status").style.display='';
        document.getElementById("time_status2").style.display='';
        document.getElementById("time_status0").style.display='none';
        document.getElementById("time_status_month").style.display='none';
        document.getElementById("time_status_month2").style.display='none';
    }
    if(str=='2')
    {
        document.getElementById("time_status").style.display='none';
        document.getElementById("time_status_month").style.display='';
        document.getElementById("time_status_month2").style.display='';
        document.getElementById("time_status2").style.display='none';
        document.getElementById("time_status0").style.display='none';
    }
    if(str=='0')
    {
        document.getElementById("time_status").style.display='none';
        document.getElementById("time_status_month").style.display='none';
        document.getElementById("time_status2").style.display='none';
        document.getElementById("time_status_month2").style.display='none';
        document.getElementById("time_status0").style.display='';
    }
}

function  DateNextDay(d2)
{
//slice返回一个数组
    var   str = d2.slice(5) + "-" + d2.slice(0,4);
    var d = new Date(str);
    var d3 = new Date(d.getFullYear(),d.getMonth(),d.getDate()+1);
    var month=returnMonth(d3.getMonth());
    var day=d3.getDate();
    day=day < 10? "0"+day:day;
    var str2=d3.getFullYear()+"-"+month+"-"+day;
    return str2;
}

function returnMonth(num)
{
    var str= "";
    switch(num)
    {
        case 0: str= "01"; break;
        case 1: str= "02"; break;
        case 2: str= "03"; break;
        case 3: str= "04"; break;
        case 4: str= "05"; break;
        case 5: str= "06"; break;
        case 6: str= "07"; break;
        case 7: str= "08"; break;
        case 8: str= "09"; break;
        case 9: str= "10"; break;
        case 10: str= "11"; break;
        case 11: str= "12"; break;
    }
    return str;
}
function replaceEmptyItem(arr){
    for(var i=0,len=arr.length;i<len;i++){
        if(!arr[i]|| arr[i]==''){
            arr.splice(i,1);
            len--;
            i--;
        }
    }
}
function selectdate()
{
    var str=document.getElementsByName("dateselects");
    var seldates = document.form1.selectdates;
    var objarray=str.length;
    var chestr="";
    for (i=0;i<objarray;i++)
    {
        if(str[i].checked == true)
        {
            chestr+=str[i].value+",";
        }
    }
    seldates.value = chestr;
}
function selectdate1(obj)
{
    var seldates = document.form1.selectdates;
    var objcheck = obj.checked;
    var objval = obj.value;
    if(objcheck)
    {
        seldates.value += objval+',';
    }else
    {
        var sel = seldates.value.split(',');
        var sels= new Array();
        for(var i=0;i<sel.length;i++)
        {
            sels[i] = sel[i];
            if(objval == sel[i])
            {
                sels[i] = '';
            }
        }
        replaceEmptyItem(sels);
        seldates.value = sels.join(',');
        seldates.value = seldates.value + ',';
        if(seldates.value == ',')
        {
            seldates.value = '';
        }
    }
}
function createElv(obj,x,y)
{
    var span = document.createElement("span");
    var option = document.createElement("input");
    option.setAttribute("value", eval(x+'+'+y));
    option.setAttribute("type", 'checkbox');
    option.setAttribute("name", 'dateselects');
    option.setAttribute("onclick", 'selectdate()');

    var str = ('<label for=""><input type="checkbox" value="'+ eval(x+'+'+y) +'" name="dateselects" onclick="selectdate()" />'+ eval(x+'+'+y)+'号' +'</label>');
    jQuery(obj).append(str);
    /*
     var str = document.createElement("label");
     strg.innerHTML =  eval(x+'+'+y)+'号';
     str.setAttribute("for", '');
     obj.appendChild(span.appendChild(option));
     obj.appendChild(span.appendChild(str));
     */
}
function date_select1()
{
    //document.form1.selectdates.value = '';
    var x = document.form1.M_START_DATE.value;
    // var datex = x.substr(x.length-2,x.length);
    //var monthx= x.substr(5,x.length-8);
    var y = document.form1.M_END_DATE.value;
    //var datey = y.substr(y.length-2,y.length);
    //var monthy= y.substr(5,y.length-8);
    //var monthselect = document.getElementById('monthselect');
    monthselect.innerHTML = '';
    if( x == '' && y != '')
    {
        alert('请选择会议开始时间！');
        document.form1.M_END_DATE.value="";
        return false;
    }
    if( x != '' && y != '')
    {
        if(x > y)
        {
            alert('会议日期选择不正确！');
        }
    }

}
function date_select()
{
    document.form1.selectdates.value = '';
    var x = document.form1.M_START_DATE1.value;
    var datex = x.substr(x.length-2,x.length);
    var monthx= x.substr(5,x.length-8);
    var y = document.form1.M_END_DATE1.value;
    var datey = y.substr(y.length-2,y.length);
    var monthy= y.substr(5,y.length-8);
    var monthselect = document.getElementById('monthselect');
    monthselect.innerHTML = '';
    if( x != '' && y != '')
    {
        if(x > y)
        {
            alert('会议日期选择不正确！');
        }
    }
    if( x == '' && y != '')
    {
        alert('请选择会议开始时间！');
        document.form1.M_END_DATE1.value="";
        return false;
    }
    //判断是否跨越
    if(monthy > monthx)
    {
        for(var i=0;i<=(31-datex);i++)
        {
            createElv(monthselect,datex,i);
        }
        if((monthy-monthx) == 1)
        {
            if(datey >= datex)
            {
                for(var i=1;i<=datex-1;i++)
                {
                    createElv(monthselect,0,i);
                }
            }else
            {
                for(var i=1;i<=datey;i++)
                {
                    createElv(monthselect,0,i);
                }
            }
        }else{
            for(var i=1;i<=datex-1;i++)
            {
                createElv(monthselect,0,i);
            }
        }

    }else if(monthy == monthx)
    {
        for(var i=0;i<=(datey-datex);i++)
        {
            createElv(monthselect,datex,i);
        }
    }
}
function week_select()
{
    document.getElementById("WEEKEND1").style.display="none";
    document.getElementById("WEEKEND2").style.display="none";
    document.getElementById("WEEKEND3").style.display="none";
    document.getElementById("WEEKEND4").style.display="none";
    document.getElementById("WEEKEND5").style.display="none";
    document.getElementById("WEEKEND6").style.display="none";
    document.getElementById("WEEKEND0").style.display="none";

    var x = document.form1.M_START_DATE.value;
    y = x.replace(/-/, ",");
    y = y.replace(/-/, "/");
    y = new Date(y);

    var z = document.form1.M_END_DATE.value;
    r = z.replace(/-/, ",");
    r = r.replace(/-/, "/");
    r = new Date(r);

    a = r - y;
    a= a/24/60/60/1000+1;

    if(a>6)
    {
        document.getElementById("WEEKEND1").style.display="";
        document.getElementById("WEEKEND2").style.display="";
        document.getElementById("WEEKEND3").style.display="";
        document.getElementById("WEEKEND4").style.display="";
        document.getElementById("WEEKEND5").style.display="";
        document.getElementById("WEEKEND6").style.display="";
        document.getElementById("WEEKEND0").style.display="";
    }
    else
    {
        for(var i=x;i<=z;i=DateNextDay(i))
        {
            i = i.replace(/-/, ",");
            i = i.replace(/-/, "/");
            v = new Date(i);
            s = v.getDay();
            if(/^[-]?\d+$/.test(s))
            {
                w = "WEEKEND"+s;
                document.getElementById(w).style.display="";
            }
        }
    }
}
function set_att()
{
    if($("att").style.display=='none')
    {
        $("att").style.display='';
        $("att_show").innerHTML='<?=_("隐藏添加外部人员")?>';
    }
    else
    {
        $("att").style.display='none';
        $("att_show").innerHTML='<?=_("添加外部人员")?>';
    }
}
function set_fw()
{
    if($("fw_1").style.display=='none')
    {
        $("fw_1").style.display='';
        $("fw_2").style.display='';
        $("fw_show").innerHTML='<?=_("隐藏按人员或角色选取")?>';
    }
    else
    {
        $("fw_1").style.display='none';
        $("fw_2").style.display='none';
        $("fw_show").innerHTML='<?=_("按人员或角色选取")?>';
    }
}
function ifuse(){
    if($("MR_ROOM") != undefined)
    {
        if(Data_Show() == '0')
        {
            var start_m = document.form1.M_START.value;
            var end_m   = document.form1.M_END.value;
        }else if(Data_Show() == '1')
        {
            var start_m = document.form1.M_START_DATE.value + ' ' + document.form1.M_START_TIME.value;
            var end_m   = document.form1.M_END_DATE.value + ' ' + document.form1.M_END_TIME.value;
        }else if(Data_Show() == '2')
        {
            var start_m = document.form1.M_START_DATE1.value + ' ' + document.form1.M_START_TIME1.value;
            var end_m   = document.form1.M_END_DATE1.value + ' ' + document.form1.M_END_TIME1.value;
        }
        var MR_ROOM = $("MR_ROOM").value;
        var is= 1;
        jQuery.ajax({
            url:"ifuse.php",
            type:"POST",
            async:false,
            data:"m_start="+start_m+"&m_end="+end_m+"&MR_ROOM="+MR_ROOM+"&m_id="+"<?=$M_ID?>",
            success:function(data)
            {
                if(data == 1)
                {
                    alert("此会议室已被占用！");
                    is = 0;
                }
            }
        })
        if(is == 0)
        {
            return false;
        }
    }
    return true;
}
function ifjiyao()
{
    var jiyao = $('RECORDER_ID').value;
    if(jiyao != "")
    {
        if(Data_Show() == '0')
        {
            var start_m = document.form1.M_START.value;
            var end_m   = document.form1.M_END.value;
        }else if(Data_Show() == '1')
        {
            var start_m = document.form1.M_START_DATE.value + ' ' + document.form1.M_START_TIME.value;
            var end_m   = document.form1.M_END_DATE.value + ' ' + document.form1.M_END_TIME.value;
        }else if(Data_Show() == '2')
        {
            var start_m = document.form1.M_START_DATE1.value + ' ' + document.form1.M_START_TIME1.value;
            var end_m   = document.form1.M_END_DATE1.value + ' ' + document.form1.M_END_TIME1.value;
        }
        var user_id = document.form1.COPY_TO_ID.value;
        var cur_date = jQuery('[name="selectdates"]').val();
        var is = 1;
        var weeks = '';
        jQuery('.weeks').find('[type="checkbox"]:checked').each(function(k, v){
            var $this = jQuery(this);
            weeks += $this.attr('data-value')+',';
        });
        jQuery.ajax({
            url:"ifexists1.php",
            type:"POST",
            async:false,
            //data:"m_start="+start_m+"&m_end="+end_m+"&user_id="+user_id+"&jiyao="+jiyao+"&weeks="+weeks+"&M_ID="+"<?=$M_ID?>",
            data:"m_start="+start_m+"&m_end="+end_m+"&user_id="+user_id+"&jiyao="+jiyao+"&cur_date="+cur_date+"&weeks="+weeks+"&M_ID="+"<?=$M_ID?>",
            success:function(data)
            {
                if(data != "")
                {
                    alert("会议纪要员"+data+"在这个时间段有其他会议参加！");
                    is = 0;
                }

            }
        })
        if(is == 0)
        {
            return false;
        }
    }
    return true;
}
</script>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动开始----------
$query = "SELECT * from MEETING  where M_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $M_ID3=$ROW["M_ID"];
    $M_START3=$ROW["M_START"];
    if($CUR_TIME>=$M_START3)
    {
        exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '2' WHERE M_ID='$M_ID3'");
    }
}

//-----自动结束----------
$query = "SELECT * from MEETING  where M_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $M_ID3=$ROW["M_ID"];
    $M_END3=$ROW["M_END"];
    if($CUR_TIME>=$M_END3)
    {
        exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '4' WHERE M_ID='$M_ID3'");
    }
}
/*$query = "SELECT * from MEETING  where M_STATUS=3";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$M_ID3=$ROW["M_ID"];
	$M_END3=$ROW["M_END"];
	if($CUR_TIME>=$M_END3)
	{
		exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '0' WHERE M_ID='$M_ID3'");
	}
}*/
if($M_ID!="")
{
    $query = "SELECT * from MEETING  where M_ID='$M_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_NAME             = $ROW["M_NAME"];
        $M_TOPIC            = $ROW["M_TOPIC"];
        $M_DESC             = $ROW["M_DESC"];
        $M_PROPOSER         = $ROW["M_PROPOSER"];
        $M_REQUEST_TIME     = $ROW["M_REQUEST_TIME"];
        $M_ATTENDEE         = $ROW["M_ATTENDEE"];
        $M_START            = $ROW["M_START"];
        $M_END              = $ROW["M_END"];
        $M_ROOM             = $ROW["M_ROOM"];
        $M_MANAGER          = $ROW["M_MANAGER"];
        $M_ATTENDEE_OUT     = $ROW["M_ATTENDEE_OUT"];
        $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];
        $SECRET_TO_ID       = $ROW["SECRET_TO_ID"];
        $PRIV_ID            = $ROW["PRIV_ID"];
        $TO_ID              = $ROW["TO_ID"];
        $RESEND_LONG        = $ROW["RESEND_LONG"]; //小时
        $RESEND_LONG_FEN    = $ROW["RESEND_LONG_FEN"]; //分钟 王瑞杰
        $RESEND_SEVERAL     = $ROW["RESEND_SEVERAL"];
        $RECORDER           = $ROW["RECORDER"];
        $OP_M_STATUS        = $ROW["M_STATUS"];
        $TO_EMAIL           = $ROW["TO_EMAIL"];
        $CALENDAR           = $ROW["CALENDAR"];


        $TOK=strtok($RECORDER,",");
        $RECORDER_ID = $TOK;
        $RECORDER_NAME=GetUserNameById($TOK);
        if($RECORDER_NAME=="")
            $RECORDER_NAME=$TOK;

        if($M_START=="0000-00-00 00:00:00")
            $M_START="";
        if($M_END=="0000-00-00 00:00:00")
            $M_END="";
    }
}
else
{
    $M_START=$_GET["M_START"]==""? $CUR_TIME : $_GET["M_START"];
    $M_END=$_GET["M_END"]==""? $CUR_TIME : $_GET["M_END"];
}

if($M_REQUEST_TIME=="0000-00-00 00:00:00" || $M_REQUEST_TIME=="")
    $M_REQUEST_TIME=$CUR_TIME;
if($M_PROPOSER=="")
    $M_PROPOSER=$_SESSION["LOGIN_USER_ID"];

$TOK=strtok($M_ATTENDEE,",");
while($TOK!="")
{
    $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $USER_NAME2.=$ROW["USER_NAME"].",";
    $TOK=strtok(",");
}

$TOK=strtok($SECRET_TO_ID,",");
while($TOK!="")
{
    $query2 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $USER_NAME3.=$ROW["USER_NAME"].",";
    $TOK=strtok(",");
}

$TOK=strtok($PRIV_ID,",");
while($TOK!="")
{
    $query2 = "SELECT PRIV_NAME from USER_PRIV where USER_PRIV='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $PRIV_NAME.=$ROW["PRIV_NAME"].",";
    $TOK=strtok(",");
}

$TOK=strtok($TO_ID,",");
while($TOK!="")
{
    $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
        $DEPT_NAME.=$ROW["DEPT_NAME"].",";
    $TOK=strtok(",");
}

if($TO_ID=="ALL_DEPT")
    $DEPT_NAME=_("全体部门");
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">

    <tr>

        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("会议申请")?>
                &nbsp;&nbsp;<input type="button" value="<?=_("会议室管理制度")?>" class="BigButton" onClick="window.open('meetingrule.php','','height=300,width=492,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=200,resizable=yes');"></span>
            <input type="button" value="<?=_("会议室预约情况")?>" class="BigButton" onClick="window.open('select.php?MR_ID=<?=$MR_ID?>&ACTION=SEE','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=200,resizable=yes');"></span>
        </td>

    </tr>
</table>
<form enctype="multipart/form-data" method="post" name="form1">
    <table align="center" width="90%" class="TableBlock">
        <tr>
            <td nowrap class="TableData"> <?=_("出席人员（内部）：")?><br /><a href="JavaScript:;" onClick="set_att()"><span id="att_show"><?=_("添加外部人员")?></span></a></td>
            <td class="TableData" colspan="3">
                <input type="hidden" id="COPY_TO_ID" name="COPY_TO_ID" value="<?=$M_ATTENDEE?>">
                <input type="hidden" name="FLAG" value="<?=$FLAG?>">
                <textarea name="COPY_TO_NAME" class="BigStatic checkUp" cols="50" rows="2" wrap="yes" readonly><?=$USER_NAME2?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('86','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="att" style="display:none;">
            <td nowrap class="TableData"> <?=_("出席人员（外部）：")?></td>
            <td class="TableData" colspan="3">
                <textarea name="M_ATTENDEE_OUT" class="BigInput checkUp" cols="50" rows="2"><?=$M_ATTENDEE_OUT?></textarea>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("查看范围（部门）：")?><br /><a href="JavaScript:;" onClick="set_fw()"><span id="fw_show"><?=_("添加查看范围")?></span></a></td>
            <td class="TableData" colspan="3">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=50 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="fw_1" style="display:none;">
            <td nowrap class="TableData"><?=_("查看范围（角色）：")?></td>
            <td class="TableData" colspan="3">
                <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
                <textarea cols=50 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="fw_2" style="display:none;">
            <td nowrap class="TableData"><?=_("查看范围（人员）：")?></td>
            <td class="TableData" colspan="3">
                <input type="hidden" name="SECRET_TO_ID" value="<?=$SECRET_TO_ID?>">
                <textarea cols=50 name="SECRET_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME3?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('86','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" ><?=_("名称：")?></td>
            <td class="TableData">
                <input type="text" name="M_NAME" size="40" maxlength="100" class="BigInput" value="<?=$M_NAME?>"><font color=red>(*)</font>
            </td>
            <td nowrap class="TableData"> <?=_("主题：")?></td>
            <td class="TableData">
                <input type="text" name="M_TOPIC" size="40" maxlength="100" class="BigInput" value="<?=$M_TOPIC?>"><font color=red>(*)</font>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="90"><?=_("会议室：")?></td>
            <td class="TableData">
                <?
                if(isset($_GET["MR_ID"]))
                    $query = "SELECT MR_ID,MR_NAME,MR_DESC,APPLY_WEEKDAYS,OPERATOR from MEETING_ROOM where MR_ID='".$_GET["MR_ID"]."'";
                else
                    $query = "SELECT MR_ID,MR_NAME,MR_DESC,APPLY_WEEKDAYS,OPERATOR from MEETING_ROOM where MR_ID='$M_ROOM '";
                $cursor= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                {
                $MR_ID=$ROW["MR_ID"];
                $MR_DESC=$ROW["MR_DESC"];
                $APPLY_WEEKDAYS=$ROW["APPLY_WEEKDAYS"];
                $MR_NAME=$ROW["MR_NAME"];
                if($NEW == 1)
                {
                    echo $MR_NAME;
                }else
                {
                $query_1 = "select MR_NAME,MR_ID  from meeting_room";
                $cursor_1 = exequery(TD::conn(),$query_1);
                while($ROWS_1=mysql_fetch_array($cursor_1))
                {
                    $MR_NAME_1 = $ROWS_1['MR_NAME'];
                    $MR_ID_1   = $ROWS_1['MR_ID'];
                    if($MR_ID_1 == $MR_ID)
                    {
                        $roomList .= "<option value='$MR_ID_1' selected>$MR_NAME_1</option>";
                    }else
                    {
                        $roomList .= "<option value='$MR_ID_1'>$MR_NAME_1</option>";
                    }
                }
                ?>
                <select name="MR_ROOM" id="MR_ROOM"  class="bigSelect" onChange="ifuse()">
                    <?=$roomList?>
                </select>&nbsp;&nbsp;
            </td>
            <?
            }}
            ?>
            <td nowrap class="TableData" width="90"> <?=_("会议室管理员：")?></td>
            <td class="TableData" id="select_manager">
                <?
                $OPERATOR=$ROW["OPERATOR"];
                if($OPERATOR!="")
                {
                    $OPERATOR_ARRAY = explode(",",$OPERATOR);
                    $OPERATOR_ARRAY_COUNT = count($OPERATOR_ARRAY);
                    if($OPERATOR_ARRAY[$OPERATOR_ARRAY_COUNT-1]=="")$OPERATOR_ARRAY_COUNT--;
                    echo "<select name=\"M_MANAGER\" class=\"BigSelect\">";
                    for($I=0; $I < $OPERATOR_ARRAY_COUNT;$I++)
                    {
                        ?>
                        <option value="<?=$OPERATOR_ARRAY[$I]?>" <? if($OPERATOR_ARRAY[$I]==$M_MANAGER){?> selected <?}?>><?=substr(getUserNameByID($OPERATOR_ARRAY[$I]),0,-1)?></option>
                        <?
                    }
                }
                else
                {

                    $SYS_PARA_ARRAY=get_sys_para("MEETING_OPERATOR");
                    $OPERATOR=$SYS_PARA_ARRAY["MEETING_OPERATOR"];
                    if($OPERATOR!="")
                    {
                        $OPERATOR_ARRAY = explode(",",$OPERATOR);
                        $OPERATOR_ARRAY_COUNT = count($OPERATOR_ARRAY);
                        if($OPERATOR_ARRAY[$OPERATOR_ARRAY_COUNT-1]=="")$OPERATOR_ARRAY_COUNT--;
                        echo "<select name=\"M_MANAGER\" class=\"BigSelect\">";
                        for($I=0; $I < $OPERATOR_ARRAY_COUNT;$I++)
                        {
                            ?>
                            <option value="<?=$OPERATOR_ARRAY[$I]?>"><?=substr(getUserNameByID($OPERATOR_ARRAY[$I]),0,-1)?></option>
                            <?
                        }
                    }
                }
                echo "</select>";
                ?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("周期性会议申请")?>:</td>
            <td nowrap class="TableData">
                <input type="radio" name="RD" value="0" onClick="time_status('0')" checked="true"><?=_("否")?>
                <input type="radio" name="RD" value="1" onClick="time_status('1')"><?=_("按周")?>
                <input type="radio" name="RD" value="2" onClick="time_status('2')"><?=_("按月")?>
            </td>
            <td nowrap class="TableData"> <?=_("当前在线会议室管理员：")?></td>
            <td class="TableData" id="online_user">
                <?
                $POSTFIX = _("，");
                $ONLINE_USER_NAME="";
                $query = "SELECT USER_ID,USER_NAME from USER,USER_ONLINE where USER.UID=USER_ONLINE.UID and USER_ID!='' and find_in_set(USER_ID,'$OPERATOR') group by USER_ONLINE.UID order by USER_NO,USER_NAME";
                $cursor= exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USER_ID=$ROW["USER_ID"];
                    $USER_NAME=$ROW["USER_NAME"];
                    $ONLINE_USER_NAME.=$USER_NAME.$POSTFIX;
                }
                echo substr($ONLINE_USER_NAME,0,-strlen($POSTFIX));
                ?>
            </td>
        </tr>
        <tr id="time_status0">
            <td nowrap class="TableData" width="90"> <?=_("开始时间：")?></td>
            <td class="TableData">
                <input type="text" id="start_time" name="M_START" size="20" maxlength="19" class="BigInput checkUp" value="<?=$M_START?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
            </td>
            <td nowrap class="TableData" width="90"> <?=_("结束时间：")?></td>
            <td class="TableData">
                <input type="text" name="M_END" size="20" maxlength="19" class="BigInput checkUp" value="<?=$M_END?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})">

            </td>
        </tr>
        <tr id="time_status" style="display:none;">
            <td nowrap class="TableData" width="90"> <?=_("会议日期：")?></td>
            <td class="TableData">
                <input type="text" name="M_START_DATE" size="10" maxlength="19" class="BigInput checkUp" value="" onClick="WdatePicker();week_select();">

                <?=_("至")?>
                <input type="text" name="M_END_DATE" size="10" maxlength="19" class="BigInput checkUp" value=""  onchange ="date_select1()" onClick="WdatePicker();week_select();">
            </td>
            <td nowrap class="TableData" width="90"> <?=_("会议时间：")?></td>
            <td class="TableData">
                <input type="text" name="M_START_TIME" size="10" maxlength="19" class="BigInput checkUp" value="<?=$M_START == "" ? "" : substr($M_START,-8)?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif"   border="0" align="absMiddle" style="cursor:hand" onClick="td_clock('form1.M_START_TIME');"><?=_("至")?>
                <input type="text" name="M_END_TIME" size="10" maxlength="19" class="BigInput checkUp" value="<?=$M_END == "" ? "" : substr($M_END,-8)?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0"  style="cursor:hand" onClick="td_clock('form1.M_END_TIME');">

            </td>
        </tr>
        <tr id="time_status2" class="weeks" style="display:none;">
            <td nowrap class="TableData" width="100"> <?=_("申请星期：")?></td>
            <td class="TableData" colspan="3">
                <label id="WEEKEND0" for="W7" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'0')?"":"disabled"?> name="W7" data-value="0"><?=_("星期日")?></label>
                <label id="WEEKEND1" for="W1" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'1')?"":"disabled"?> name="W1" data-value="1"><?=_("星期一")?></label>
                <label id="WEEKEND2" for="W2" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'2')?"":"disabled"?> name="W2" data-value="2"><?=_("星期二")?></label>
                <label id="WEEKEND3" for="W3" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'3')?"":"disabled"?> name="W3" data-value="3"><?=_("星期三")?></label>
                <label id="WEEKEND4" for="W4" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'4')?"":"disabled"?> name="W4" data-value="4"><?=_("星期四")?></label>
                <label id="WEEKEND5" for="W5" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'5')?"":"disabled"?> name="W5" data-value="5"><?=_("星期五")?></label>
                <label id="WEEKEND6" for="W6" style="display:none"><input type="checkbox" <?=find_id($APPLY_WEEKDAYS,'6')?"":"disabled"?> name="W6" data-value="6"><?=_("星期六")?></label>
            </td>
        </tr>
        <tr id="time_status_month" style="display:none;">
            <td nowrap class="TableData" width="90"> <?=_("会议日期：")?></td>
            <td class="TableData">
                <input type="text" name="M_START_DATE1" size="10" maxlength="19" class="BigInput checkUp" value="" onClick="WdatePicker();">
                <?=_("至")?>
                <input type="text" name="M_END_DATE1" size="10" maxlength="19" class="BigInput checkUp" value="" onchange ="date_select()" onClick="WdatePicker();">
            </td>
            <td nowrap class="TableData" width="90"> <?=_("会议时间：")?></td>
            <td class="TableData">
                <input type="text" name="M_START_TIME1" size="10" maxlength="19" class="BigInput checkUp" value="<?=$M_START == "" ? "" : substr($M_START,-8)?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif"   border="0" align="absMiddle" style="cursor:hand" onClick="td_clock('form1.M_START_TIME1');"><?=_("至")?>
                <input type="text" name="M_END_TIME1" size="10" maxlength="19" class="BigInput checkUp" value="<?=$M_END == "" ? "" : substr($M_END,-8)?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0"  style="cursor:hand" onClick="td_clock('form1.M_END_TIME1');">
            </td>
        </tr>
        <tr id="time_status_month2" style="display:none;">
            <td nowrap class="TableData" width="100"> <?=_("申请日期：")?></td>
            <td class="TableData" colspan="2" id='monthselect'>
            </td>
            <td>
                当前选择的日期：<input type='text' name='selectdates' class='BigInput' value=''>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("会议室设备：")?></td>
            <td nowrap class="TableData" colspan="3">
                <div id="equipment">
                    <?
                    $query = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='0' and EQUIPMENT_STATUS='1' and MR_ID='$MR_ID' order by EQUIPMENT_NO";
                    $cursor= exequery(TD::conn(),$query);
                    $EQUIPMENT_COUNT=0;
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $EQUIPMENT_COUNT++;
                        $EQUIPMENT_ID=$ROW["EQUIPMENT_ID"];
                        $EQUIPMENT_NO=$ROW["EQUIPMENT_NO"];
                        $EQUIPMENT_NAME=$ROW["EQUIPMENT_NAME"];
                        $REMARK=strip_tags($ROW["REMARK"]);


                        ?>
                        <input type="checkbox" name="SB_<?=$EQUIPMENT_ID?>" id="SB_<?=$EQUIPMENT_ID?>" value="<?=$EQUIPMENT_ID?>"><label title="<?=$REMARK?>" for="SB_<?=$EQUIPMENT_ID?>"><?=$EQUIPMENT_NAME?></label>
                        <?
                    }

                    $query = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='1' and EQUIPMENT_STATUS='1' and MR_ID='$MR_ID' group by GROUP_NO";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $EQUIPMENT_COUNT++;
                        $GROUP_NO1=$ROW["GROUP_NO"];
                        $query1 = "SELECT * from MEETING_EQUIPMENT where GROUP_YN='1' and EQUIPMENT_STATUS='1' and MR_ID='$MR_ID' and GROUP_NO='$GROUP_NO1'";
                        $cursor1= exequery(TD::conn(),$query1);
                        $COUNT[$GROUP_NO1]=0;
                        while($ROW1=mysql_fetch_array($cursor1))
                        {
                            $COUNT[$GROUP_NO1]++;
                            $EQUIPMENT_NAME1=$ROW1["EQUIPMENT_NAME"];
                            $EQUIPMENT_ID1=$ROW1["EQUIPMENT_ID"];
                            $REMARK1=$ROW1["REMARK"];
                            if($COUNT[$GROUP_NO1]==1)
                            {
                                echo "&nbsp;&nbsp;<select name=\"SB_".$EQUIPMENT_ID1."\" class=\"BigSelect\">";
                                echo "  <option value=\"\">"._("选择").get_code_name($GROUP_NO1,"MEETING_EQUIPMENT")."</option>";
                            }
                            ?>
                            <option value="<?=$EQUIPMENT_ID1?>" title="<?=$REMARK1?>"><?=$EQUIPMENT_NAME1?></option>
                            <?
                        }
                        echo "</select>";
                    }
                    if($EQUIPMENT_COUNT==0)
                        echo _("无记录");
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("会议纪要员：")?></td>
            <td  class="TableData">
                <input type="hidden" name="RECORDER_ID" id="RECORDER_ID" value="<?=$RECORDER_ID?>">
                <input type="text" name="RECORDER_NAME" id="RECORDER_NAME" size="20" class="BigStatic" maxlength="20"  value="<?=$RECORDER_NAME?>"  readonly>
                &nbsp;<input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="SelectUserSingle('85','','RECORDER_ID','RECORDER_NAME')" title="<?=_("选择")?>" name="button">
                &nbsp;<input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="ClearUser('RECORDER_ID','RECORDER_NAME')" title="<?=_("清空")?>" name="button">
            </td>
            <td nowrap class="TableData"> <?=_("是否电子邮件提醒：")?></td>
            <td class="TableData">
                <input type="checkbox" name="TO_EMAIL" id="TO_EMAIL" <? if(isset($TO_EMAIL) && $TO_EMAIL=="0"){?><?}else{?> checked="true" <?}?>><label for="TO_EMAIL"><?=_("是")?></label>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("提醒设置：")?></td>
            <td class="TableData">
                <?
                $msg=sprintf(_("提前%s小时%s分提醒，提醒%s次"),"<input type='text' name='RESEND_LONG' size='4' class='BigInput' onkeyup='this.value=this.value.replace(/\D|^0/g,\"\")' style='text-align:right' value='".$RESEND_LONG."' keyup=''>","<input type='text' name='RESEND_LONG_FEN' class='BigInput' size='4' style='text-align:right'  onkeyup='this.value=this.value.replace(/\D|^0/g,\"\")' value='".$RESEND_LONG_FEN."'>","<input type='text' name='RESEND_SEVERAL' id='RESEND_SEVERAL' class='BigInput' size='4' style='text-align:right'  onkeyup='this.value=this.value.replace(/\D|^0/g,\"\")' value='".$RESEND_SEVERAL."'>");
                ?>
                <?=$msg ?>
            </td>
            <td nowrap class="TableData"> <?=_("写入日程安排：")?></td>
            <td class="TableData">
                <input type="checkbox" name="CALENDAR" id="CALENDAR" <? if(isset($CALENDAR) && $CALENDAR==""){?><?}else{?>checked="true"<?}?>>
                <label for="CALENDAR"><?=_("是")?></label>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData" width="110"> <?=_("提醒会议室管理员：")?></td>
            <td class="TableData">
                <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"8")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息 ")?></label>&nbsp;
                <?
                $query = "select * from SMS2_PRIV";
                $cursor=exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                    $TYPE_PRIV=$ROW["TYPE_PRIV"];

                if(find_id($TYPE_PRIV,8)) //检查该模块是否允许手机提醒
                {
                    ?>
                    <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"8")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("发送手机短信提醒")?></label>
                    <?
                }
                ?>
            </td>
            <td nowrap class="TableData" width="90"><?=_("通知出席人员：")?></td>
            <td class="TableData">
                <?=sms_remind("8_1");?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("附件文档：")?></td>
            <td nowrap class="TableData">
                <?
                if($ATTACHMENT_ID=="")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);

                ?>
            </td>
            <td nowrap class="TableData"><?=_("是否提醒，查看范围：")?></td>
            <td class="TableData">
                <input type="checkbox" name="TO_SCOPE" id="TO_SCOPE" checked="true">
                <label for="TO_SCOPE"><?=_("是")?></label>
            </td>
        </tr>
        <tr height="25">
            <td nowrap class="TableData"><?=_("附件选择：")?></td>
            <td class="TableData" colspan="3">
                <script>ShowAddFile();ShowAddImage();</script>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" colspan="4"><span id="ATTACH_LABEL"><?=_("会议描述：")?></span></td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan="4">
                <?
                $editor = new Editor('M_DESC') ;
                $editor->Height = '370';
                $editor->Config = array('model_type' => '08');
                $editor->Value = $M_DESC ;
                $editor->Create() ;

                ?>
            </td>
        </tr>
        <tr class="TableControl">
            <td nowrap colspan="4" align="center">
                <input type="hidden" name="OP" value="">
                <input type="hidden" name="OP_M_STATUS" value="<?=$OP_M_STATUS?>">
                <input type="hidden" value="<?=$M_ID?>" name="M_ID">
                <input type="hidden" value="<?=$MR_ID?>" name="M_ROOM">
                <input type="hidden" value="<?=$NEW?>" name="NEW">
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
                <input type="hidden" value="<?=$CUR_PAGE?>" name="CUR_PAGE">
                <input type="hidden" value="<?=$CUR_TIME?>" name="M_REQUEST_TIME">
                <input type="hidden" value="<?=$M_PROPOSER?>" name="M_PROPOSER">
                <input type="hidden" name="MUSA" value="<?=$M_ID?>">
                <input type="button" value="<?=_("确定")?>" class="BigButton" onClick="sendForm();">&nbsp;&nbsp;
                <? if($FROM_TASK_CENTER=='1')
                {?>
                    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
                <?}else
                {?>
                    <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="history.back();">
                <?} ?>
            </td>
        </tr>
    </table>
</form>
</body>
</html>