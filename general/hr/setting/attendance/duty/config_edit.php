<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$HTML_PAGE_TITLE = _("编辑排班类型");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<style>
.color_wrapper{
	position:relative;
}
.color_menu{
	display:none;
}
.color_menu.active{
	display:inline-block;
	margin-left: 10px;
}

.color_menu .color{
	cursor:pointer;
	display:inline-block;
	width:22px;
	height:20px;
	float:left;
}
.color_menu .color:hover{
    opacity: 0.7;
}

#color{
    border: 2px solid #ddd;
}
.color{
	display:inline-block;
	width:22px;
	height:20px;
	margin-top:5px;
	background-color:#fff;
	cursor: pointer;
	float: left;
	border: 1px solid #ddd;
	
}
.color1{
	background-color:rgb(255, 136, 124);
}
.color2{
	background-color:rgb(252, 226, 89);
}
.color3{
	background-color:rgb(105, 240, 164);
}
.color4{
	background-color:rgb(245, 181, 46);
}
.color5{
	background-color:rgb(70, 214, 219);
}
.color6{
	background-color:rgb(219, 173, 255);
}
</style>
<script type="text/javascript" src="/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
    jQuery(document).ready(function(){
        jQuery('#color').click(function(){
            jQuery('#color_menu').toggleClass('active')
        })    
        jQuery('#color_menu a').click(function(){
            var $this = $(this)
            var index = $this.attr('index')
            jQuery('#color').attr('class','color color'+index)
            jQuery('#color-hidden').val(index)
        })
    })
</script>
<script Language="JavaScript">
    function CheckForm()
    {
        var DUTY_NAME = document.form1.DUTY_NAME.value;
        if(DUTY_NAME=="")
        {
            alert("<?=_("排班类型不能为空")?>");
            return (false);
        }

        /* ---第一次登记时间----  */
        var time1 = document.form1.DUTY_TIME1.value;//签到时间
        var time_late1 = document.form1.TIME_LATE1.value; // 迟到时间
        var duty_before1 = document.form1.DUTY_BEFORE1.value; // 提前多长时间打卡
        var duty_after1 = document.form1.DUTY_AFTER1.value; //延后多长时间打卡

        if(time1 == "")
        {
            alert("<?=_("第一次登记时间不能为空")?>");
            return false;
        }
        if(time1 != "" && (duty_before1=="" || duty_before1==0 || duty_before1== 00 || duty_after1=="" || duty_after1==0 || duty_after1==00))
        {
            alert("<?=_("第一次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);

        }

        if(time1 != "" && parseInt(duty_after1) <= parseInt(time_late1))
        {
            alert("<?=_("第一次登记时间,迟到时间不能大于等于延后时间")?>");
            return (false);
        }

        var time1_sec = parseInt(time1.substr(0, 2))*3600 + parseInt(time1.substr(3,2))*60 + parseInt(time1.substr(6,2));
        var duty_after1_sec = parseInt(duty_after1*60);
        var sec1_after = time1_sec + duty_after1_sec;

        /* ---第二次登记时间---  */
        var time2 = document.form1.DUTY_TIME2.value;//签到时间
        var time_early2 = document.form1.TIME_EARLY2.value; // 早退时间
        var duty_before2 = document.form1.DUTY_BEFORE2.value; // 提前多长时间打卡
        var duty_after2 = document.form1.DUTY_AFTER2.value; //延后多长时间打卡


        if(time2 == "")
        {
            alert("<?=_("第二次登记时间不能为空")?>");
            return false;
        }

        if(duty_before2=="" || duty_before2==0 || duty_before2== 00 || duty_after2=="" || duty_after2==0 || duty_after2==00)
        {
            alert("<?=_("第二次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);

        }
        if(parseInt(duty_before2) <= parseInt(time_early2))
        {
            alert("<?=_("第二次登记时间,早退时间不能大于等于提前时间")?>");
            return (false);
        }

        var time2_sec = parseInt(time2.substr(0, 2))*3600 + parseInt(time2.substr(3,2))*60 + parseInt(time2.substr(6,2));
        var duty_before2_sec = parseInt(duty_before2*60);
        var sec2_before = time2_sec - duty_before2_sec;

        var duty_after2_sec = parseInt(duty_after2*60);
        var sec2_after = time2_sec + duty_after2_sec;


        if(sec2_before <= sec1_after)
        {
            alert("<?=_("第二次登记时间不能小于等于第一次登记时间")?>");
            return false;
        }

        /* ---第三次登记时间---  */
        var time3 = document.form1.DUTY_TIME3.value;//签到时间
        var time_late3 = document.form1.TIME_LATE3.value; // 迟到时间
        var duty_before3 = document.form1.DUTY_BEFORE3.value; // 提前多长时间打卡
        var duty_after3 = document.form1.DUTY_AFTER3.value; //延后多长时间打卡

        if(time3 != "" && (duty_before3=="" || duty_before3==0 || duty_before3== 00 || duty_after3=="" || duty_after3==0 || duty_after3==00))
        {
            alert("<?=_("第三次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);

        }

        if(time3 != "" && parseInt(duty_after3) <= parseInt(time_late3))
        {
            alert("<?=_("第三次登记时间,迟到时间不能大于等于延后时间")?>");
            return (false);
        }

        var time3_sec = parseInt(time3.substr(0, 2))*3600 + parseInt(time3.substr(3,2))*60 + parseInt(time3.substr(6,2));
        var duty_before3_sec = parseInt(duty_before3*60);
        var sec3_before = time3_sec - duty_before3_sec;

        var duty_after3_sec = parseInt(duty_after3*60);
        var sec3_after = time3_sec + duty_after3_sec;


        if(sec3_before <= sec2_after)
        {
            alert("<?=_("第三次登记时间不能小于等于第二次登记时间")?>");
            return false;
        }

        /* ---第四次登记时间---  */
        var time4 = document.form1.DUTY_TIME4.value;//签到时间
        var time_early4 = document.form1.TIME_EARLY4.value; // 早退
        var duty_before4 = document.form1.DUTY_BEFORE4.value; // 提前多长时间打卡
        var duty_after4 = document.form1.DUTY_AFTER4.value; //延后多长时间打卡


        if(time3 == "" && time4!='')
        {
            alert("<?=_("第三次登记时间不能为空")?>");
            return false;
        }
        if(time4 == "" && time3!='')
        {
            alert("<?=_("第四次登记时间不能为空")?>");
            return false;
        }

        if(time4 != "" && (duty_before4 == "" || duty_before4 == 0 || duty_before4 == 00 || duty_after4 == "" || duty_after4 == 0 || duty_after4 == 00))
        {
            alert("<?=_("第四次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);
        }

        if(time4 != "" && parseInt(duty_before4) <= parseInt(time_early4))
        {
            alert("<?=_("第四次登记时间,早退时间不能大于等于提前时间")?>");
            return (false);
        }

        var time4_sec = parseInt(time4.substr(0, 2))*3600 + parseInt(time4.substr(3,2))*60 + parseInt(time4.substr(6,2));
        var duty_before4_sec = parseInt(duty_before4*60);
        var sec4_before = time4_sec - duty_before4_sec;

        var duty_after4_sec = parseInt(duty_after4*60);
        var sec4_after = time4_sec + duty_after4_sec;

        if(sec4_before <= sec3_after)
        {
            alert("<?=_("第四次登记时间不能小于等于第三次登记时间")?>");
            return false;
        }

        /* ---第五次登记时间---  */
        var time5 = document.form1.DUTY_TIME5.value;//签到时间
        var time_late5 = document.form1.TIME_LATE5.value; // 迟到
        var duty_before5 = document.form1.DUTY_BEFORE5.value; // 提前多长时间打卡
        var duty_after5 = document.form1.DUTY_AFTER5.value; //延后多长时间打卡

        if(time5 != "" && (duty_before5=="" || duty_before5==0 || duty_before5== 00 || duty_after5=="" || duty_after5==0 || duty_after5==00))
        {
            alert("<?=_("第五次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);

        }

        if(time5 != "" && parseInt(duty_after5) <= parseInt(time_late5))
        {
            alert("<?=_("第五次登记时间时间,迟到时间不能大于等于延后时间")?>");
            return (false);
        }

        var time5_sec = parseInt(time5.substr(0, 2))*3600 + parseInt(time5.substr(3,2))*60 + parseInt(time5.substr(6,2));
        var duty_before5_sec = parseInt(duty_before5*60);
        var sec5_before5 = time5_sec - duty_before5_sec;

        var duty_after5_sec = parseInt(duty_after5*60);
        var sec5_after = time5_sec + duty_after5_sec;

        if(sec5_before5 <= sec4_after)
        {
            alert("<?=_("第五次登记时间不能小于等于第四次登记时间")?>");
            return false;
        }

        /* ---第六次登记时间---  */
        var time6 = document.form1.DUTY_TIME6.value;//签到时间
        var time_early6 = document.form1.TIME_EARLY6.value; // 早退
        var duty_before6 = document.form1.DUTY_BEFORE6.value; // 提前多长时间打卡
        var duty_after6 = document.form1.DUTY_AFTER6.value; //延后多长时间打卡

        if(time5 == "" && time6!='')
        {
            alert("<?=_("第五次登记时间不能为空！")?>");
            return false;
        }
        if(time6 == "" && time5!='')
        {
            alert("<?=_("第六次登记时间不能为空")?>");
            return false;
        }

        if(time6 != "" && (duty_before6 == "" || duty_before6 == 0 || duty_before6 == 00 || duty_after6 == "" || duty_after6 == 0 || duty_after6 == 00))
        {
            alert("<?=_("第六次登记时间,提前或延后时间不能为空或是零")?>");
            return (false);
        }

        if(time6 != "" && parseInt(duty_before6) <= parseInt(time_early6))
        {
            alert("<?=_("第六次登记时间,早退时间不能大于等于提前时间")?>");
            return (false);
        }

        var time6_sec = parseInt(time6.substr(0, 2))*3600 + parseInt(time6.substr(3,2))*60 + parseInt(time6.substr(6,2));
        var duty_before6_sec = parseInt(duty_before6*60);
        var sec6_before = time6_sec - duty_before6_sec;

        if(sec6_before <= sec5_after)
        {
            alert("<?=_("第六次登记时间不能小于等于第五次登记时间")?>");
            return false;
        }

        return true;
    }
</script>


<body class="attendance" onload="document.form1.DUTY_NAME.focus();">

<?
if($DUTY_TYPE!="")
    $TITLE = _("编辑排班类型");
else
    $TITLE = _("新建排班类型");
?>
<h5 class="attendance-title"><?=$TITLE?></h5>
<?
if($DUTY_TYPE!="")
{
    $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
    $cursor= exequery(TD::conn(),$query);

    if($ROW=mysql_fetch_array($cursor))
    {
        $DUTY_NAME=$ROW["DUTY_NAME"];
        $COLOR=$ROW["COLOR"];
        $DUTY_TIME1=$ROW["DUTY_TIME1"];
        $DUTY_TIME2=$ROW["DUTY_TIME2"];
        $DUTY_TIME3=$ROW["DUTY_TIME3"];
        $DUTY_TIME4=$ROW["DUTY_TIME4"];
        $DUTY_TIME5=$ROW["DUTY_TIME5"];
        $DUTY_TIME6=$ROW["DUTY_TIME6"];

        $DUTY_BEFORE1 = $ROW['DUTY_BEFORE1'];
        $DUTY_AFTER1  = $ROW['DUTY_AFTER1'];
        $DUTY_BEFORE2 = $ROW['DUTY_BEFORE2'];
        $DUTY_AFTER2  = $ROW['DUTY_AFTER2'];
        $DUTY_BEFORE3 = $ROW['DUTY_BEFORE3'];
        $DUTY_AFTER3  = $ROW['DUTY_AFTER3'];
        $DUTY_BEFORE4 = $ROW['DUTY_BEFORE4'];
        $DUTY_AFTER4  = $ROW['DUTY_AFTER4'];
        $DUTY_BEFORE5 = $ROW['DUTY_BEFORE5'];
        $DUTY_AFTER5  = $ROW['DUTY_AFTER5'];
        $DUTY_BEFORE6 = $ROW['DUTY_BEFORE6'];
        $DUTY_AFTER6  = $ROW['DUTY_AFTER6'];
        $TIME_LATE1   = $ROW['TIME_LATE1'];
        $TIME_EARLY2  = $ROW['TIME_EARLY2'];
        $TIME_LATE3   = $ROW['TIME_LATE3'];
        $TIME_EARLY4  = $ROW['TIME_EARLY4'];
        $TIME_LATE5   = $ROW['TIME_LATE5'];
        $TIME_EARLY6  = $ROW['TIME_EARLY6'];
    }
}
?>

<br>
<form action="<? if($DUTY_TYPE!="") echo"config_update"; else echo "config_insert";?>.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="table table-bordered"  align="center">
        <thead class="">
        <tr>
            <td nowrap class="TableData" width="25%"> <?=_("班次说明：")?></td>
            <td class="TableData">
                <input type="text" name="DUTY_NAME" size="35" maxlength="15" class="input" value="<?=$DUTY_NAME?>">&nbsp;<span style="font-size:13px;color:#9a9898;">最多15个字符（中英文或数字），首两个字符会显示在考勤月历中</span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="25%"> <?=_("班次标识：")?></td>
            <td class="TableData">
                <div class="color_wrapper">
                    <a id="color" class="pull-left <? if($COLOR) echo "color color".$COLOR; else echo "color"; ?>" hidefocus="true" index="<? if($COLOR) echo $COLOR; else echo '0';?>"></a>
                    <div id="color_menu" class="pull-left color_menu">
                        <a id="calcolor" href="javascript:;" class="color" index="0"></a>
                        <a id="calcolor1" href="javascript:;" class="color color1" index="1"></a>
                        <a id="calcolor2" href="javascript:;" class="color color2" index="2"></a>
                        <a id="calcolor3" href="javascript:;" class="color color3" index="3"></a>
                        <a id="calcolor4" href="javascript:;" class="color color4" index="4"></a>
                        <a id="calcolor5" href="javascript:;" class="color color5" index="5"></a>
                        <a id="calcolor6" href="javascript:;" class="color color6" index="6"></a>
                    </div>
                    <input type="hidden" id="color-hidden" name="color" value="<? if($COLOR) echo $COLOR; else echo '0';?>" />
             </div>
            </td>
        </tr>
        </thead>
    </table>
    <table class="table table-bordered"  align="center">
        <thead class="">
        <tr>
            <td>
                <?=_("签到时间: ")?>
                <input type="text" name="DUTY_TIME1" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME1?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;
                <?=_("迟到: ")?>
                <input type="text" name="TIME_LATE1" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_LATE1?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;
                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE1" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE1?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;
                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER1" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER1?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        <tr>
            <td>
                <?=_("签退时间: ")?>
                <input type="text" name="DUTY_TIME2" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME2?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;

                <?=_("早退: ")?>
                <input type="text" name="TIME_EARLY2" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_EARLY2?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;

                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE2" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE2?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;

                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER2" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER2?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        </thead>
    </table>
    <table class="table table-bordered"  align="center">
        <thead class="">
        <tr>
            <td>
                <?=_("签到时间: ")?>
                <input type="text" name="DUTY_TIME3" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME3?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;
                <?=_("迟到: ")?>
                <input type="text" name="TIME_LATE3" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_LATE3?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;
                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE3" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE3?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;
                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER3" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER3?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        <tr>
            <td>
                <?=_("签退时间: ")?>
                <input type="text" name="DUTY_TIME4" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME4?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;

                <?=_("早退: ")?>
                <input type="text" name="TIME_EARLY4" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_EARLY4?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;

                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE4" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE4?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;

                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER4" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER4?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        </thead>
    </table>
    <table class="table table-bordered"  align="center">
        <thead class="">
        <tr>
            <td>
                <?=_("签到时间: ")?>
                <input type="text" name="DUTY_TIME5" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME5?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;
                <?=_("迟到: ")?>
                <input type="text" name="TIME_LATE5" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_LATE5?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;
                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE5" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE5?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;
                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER5" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER5?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        <tr>
            <td>
                <?=_("签退时间: ")?>
                <input type="text" name="DUTY_TIME6" class="input-small" size="8" maxlength="8" value="<?=$DUTY_TIME6?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">&nbsp;&nbsp;

                <?=_("早退: ")?>
                <input type="text" name="TIME_EARLY6" class="input-small" size="8" maxlength="2" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$TIME_EARLY6?>">
                <?=_("分钟正常")?>&nbsp;&nbsp;

                <?=_("提前: ")?>
                <input type="text" name="DUTY_BEFORE6" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_BEFORE6?>">
                <?=_("分钟准许登记")?>&nbsp;&nbsp;

                <?=_("延后: ")?>
                <input type="text" name="DUTY_AFTER6" class="input-small" size="8" maxlength="3" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="<?=$DUTY_AFTER6?>">
                <?=_("分钟准许登记")?>
            </td>
        </tr>
        </thead>
    </table>
    <br>
    <div align="center">
        <input type="hidden" value="<?=$DUTY_TYPE?>" name="DUTY_TYPE">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn " onClick="location='index.php'">
    </div>
</form>

</body>
</html>