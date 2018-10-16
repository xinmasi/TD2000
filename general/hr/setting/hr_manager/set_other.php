<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("其他设置");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
    function check(id){
        var daycontent=$("#ANNUAL_BEGIN_TIME_DAY").val();
        if(id==04 || id == 06 || id == 09 || id== 11)
        {
            if(daycontent>30)
            {
                var str="";
                for(var i =1;i<31;i++)
                {
                    str+="<option value='" + i + "' > " + i + "</option>\r\n";
                }
            }
            else
            {
                var str="";
                for(var i =1;i<31;i++)
                {
                    if(i==daycontent)
                        str+="<option value='" + i + "' selected> " + i + "</option>\r\n";
                    else
                        str+="<option value='" + i + "'> " + i + "</option>\r\n";
                }
            }

        }
        if(id==02)
        {
            if(daycontent>28)
            {
                var str="";
                for(var i =1;i<29;i++)
                {
                    str+="<option value='" + i + "' > " + i + "</option>\r\n";
                }
            }
            else
            {
                var str="";
                for(var i =1;i<29;i++)
                {
                    if(i==daycontent)
                        str+="<option value='" + i + "' selected> " + i + "</option>\r\n";
                    else
                        str+="<option value='" + i + "'> " + i + "</option>\r\n";
                }
            }
        }
        if(id==01 || id ==03 || id == 05 || id ==07 || id ==08 || id ==10 || id ==12)
        {
            for(var i =1;i<32;i++)
            {
                if(i==daycontent)
                    str+="<option value='" + i + "' selected> " + i + "</option>\r\n";
                else
                    str+="<option value='" + i + "'> " + i + "</option>\r\n";
            }
        }
        $("#ANNUAL_BEGIN_TIME_DAY").html(str);

    }
</script>

<body class="bodycolor" topmargin="5">
<?
$SYS_PARA_ARRAY = get_sys_para("HR_SET_USER_LOGIN,RETIRE_AGE,ANNUAL_BEGIN_TIME,ANNUAL_END_TIME,HR_SET_SPECIALIST,KEEP_WATCH");

$YES_OTHER  = $SYS_PARA_ARRAY["HR_SET_USER_LOGIN"];
$PARA_VALUE = $SYS_PARA_ARRAY["RETIRE_AGE"];
$SPECIALIST = $SYS_PARA_ARRAY["HR_SET_SPECIALIST"];
$KEEP_WATCH = $SYS_PARA_ARRAY["KEEP_WATCH"];


$AGE_ARRAY=explode(",",$PARA_VALUE);
$SYS_PARA_ARRAY1 = get_sys_para("ANNUAL_BEGIN_TIME,ANNUAL_END_TIME", FALSE);
$ANNUAL_BEGIN_TIME=$SYS_PARA_ARRAY1["ANNUAL_BEGIN_TIME"];
$ANNUAL_BEGIN_TIME_DAY      = substr($ANNUAL_BEGIN_TIME, 4, 2);
$ANNUAL_BEGIN_TIME_MONTH  = substr($ANNUAL_BEGIN_TIME, 1, 2);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/training.gif" align="absmiddle"><span class="big3"> <?=_("其他设置")?></span>
        </td>
    </tr>
</table>

<div align="center">

    <table class="TableList" width="90%">
        <form action="set_submit.php"  method="post" name="form1">
            <tr class="TableHeader" align="center">
                <td width="120"><?=_("选项")?></td>
                <td><?=_("参数")?></td>
                <td width="250"><?=_("备注")?></td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("允许人力资源管理员设置OA登录权限：")?></b></td>
                <td align="left">
                    <input type="radio" name="YES_OTHER" id="YES_OTHER1" value="1" <?if($YES_OTHER==1) echo "checked";?>><label for="YES_OTHER1"><?=_("是")?></label>
                    <input type="radio" name="YES_OTHER" id="YES_OTHER2" value="0" <?if($YES_OTHER==0) echo "checked";?>><label for="YES_OTHER2"><?=_("否")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("选择是，则人力资源管理员在新建用户档案时有授予该用户是否能够登录")?>OA<?=_("系统的权限；选择否，则无。")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("允许人事专员设置OA登录权限：")?></b></td>
                <td align="left">
                    <input type="radio" name="SPECIALIST" id="SPECIALIST1" value="1" <?if($SPECIALIST==1) echo "checked";?>><label for="SPECIALIST1"><?=_("是")?></label>
                    <input type="radio" name="SPECIALIST" id="SPECIALIST2" value="0" <?if($SPECIALIST==0) echo "checked";?>><label for="SPECIALIST2"><?=_("否")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("选择是，则人力资源管理员在新建用户档案时有授予该用户是否能够登录")?>OA<?=_("系统的权限；选择否，则无。")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("允许用户查看所有人的值班信息：")?></b></td>
                <td align="left">
                    <input type="radio" name="KEEP_WATCH" id="KEEP_WATCH1" value="1" <?if($KEEP_WATCH==1) echo "checked";?>><label for="KEEP_WATCH1"><?=_("是")?></label>
                    <input type="radio" name="KEEP_WATCH" id="KEEP_WATCH2" value="0" <?if($KEEP_WATCH==0) echo "checked";?>><label for="KEEP_WATCH2"><?=_("否")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("选择是，用户可在个人考勤->我的值班->月视图查看所有人的值班信息")?><?=_("选择否，则无。")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("设置退休年龄：")?></b></td>
                <td align="left" colspan="3">
                    <?=_("男：")?><input type="text" name="MAN_AGE" class="BigInput" size="4" maxlength="100" value="<?=$AGE_ARRAY[0]?>"><?=_("岁")?> &nbsp;
                    <?=_("女：")?><input type="text" name="WOMEN_AGE" class="BigInput" size="4" maxlength="100" value="<?=$AGE_ARRAY[1]?>"><?=_("岁")?> &nbsp;
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("设置年休假时间：")?></b></td>
                <td align="left" colspan="1">
                    <?=_("开始时间：")?>
                    <select name="ANNUAL_BEGIN_TIME_MONTH" class="BigSelect" onChange="check(this.value)">
                        <?
                        for($i=1;$i<=12;$i++){
                            ?>
                            <option value='<?=sprintf("%02d", $i);?>' <?php if($ANNUAL_BEGIN_TIME_MONTH == $i){echo 'selected';}?>><?=_("$i")?></option>
                        <? }?>
                    </select>
                    <?=_("月")?>
                    <select name="ANNUAL_BEGIN_TIME_DAY" class="BigSelect" id="ANNUAL_BEGIN_TIME_DAY">
                        <?
                        $m="";
                        if($ANNUAL_BEGIN_TIME_MONTH==01 || $ANNUAL_BEGIN_TIME_MONTH ==03 || $ANNUAL_BEGIN_TIME_MONTH == 05 || $ANNUAL_BEGIN_TIME_MONTH ==07 || $ANNUAL_BEGIN_TIME_MONTH ==08 || $ANNUAL_BEGIN_TIME_MONTH ==10 || $ANNUAL_BEGIN_TIME_MONTH ==12)
                            $m=31;
                        if($ANNUAL_BEGIN_TIME_MONTH==04 || $ANNUAL_BEGIN_TIME_MONTH ==06 || $ANNUAL_BEGIN_TIME_MONTH == 09 || $ANNUAL_BEGIN_TIME_MONTH ==11)
                            $m=30;
                        if($ANNUAL_BEGIN_TIME_MONTH==02)
                            $m=28;
                        for($i=1;$i<=$m;$i++){
                            ?>
                            <option value='<?=sprintf("%02d", $i);?>' <?php if($ANNUAL_BEGIN_TIME_DAY == $i){echo 'selected';}?>><?=_("$i")?></option>
                        <? }?>
                    </select>
                    <?=_("日 00:00:01")?> &nbsp;
                </td>
                <td width="300" align="left">
                    <?=_("默认年休假时间格式：开始时间：1月1日0时0分1秒，结束时间：12月30日23时59分59秒,结束时间应是开始时间的前一天")?>
                </td>
            </tr>
            <tr>
                <td nowrap  class="TableControl" colspan="3" align="center">
                    <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
                </td>
            </tr>
        </form>
    </table>
</div>
</body>
</html>