<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��������");
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
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/training.gif" align="absmiddle"><span class="big3"> <?=_("��������")?></span>
        </td>
    </tr>
</table>

<div align="center">

    <table class="TableList" width="90%">
        <form action="set_submit.php"  method="post" name="form1">
            <tr class="TableHeader" align="center">
                <td width="120"><?=_("ѡ��")?></td>
                <td><?=_("����")?></td>
                <td width="250"><?=_("��ע")?></td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("����������Դ����Ա����OA��¼Ȩ�ޣ�")?></b></td>
                <td align="left">
                    <input type="radio" name="YES_OTHER" id="YES_OTHER1" value="1" <?if($YES_OTHER==1) echo "checked";?>><label for="YES_OTHER1"><?=_("��")?></label>
                    <input type="radio" name="YES_OTHER" id="YES_OTHER2" value="0" <?if($YES_OTHER==0) echo "checked";?>><label for="YES_OTHER2"><?=_("��")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("ѡ���ǣ���������Դ����Ա���½��û�����ʱ��������û��Ƿ��ܹ���¼")?>OA<?=_("ϵͳ��Ȩ�ޣ�ѡ������ޡ�")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("��������רԱ����OA��¼Ȩ�ޣ�")?></b></td>
                <td align="left">
                    <input type="radio" name="SPECIALIST" id="SPECIALIST1" value="1" <?if($SPECIALIST==1) echo "checked";?>><label for="SPECIALIST1"><?=_("��")?></label>
                    <input type="radio" name="SPECIALIST" id="SPECIALIST2" value="0" <?if($SPECIALIST==0) echo "checked";?>><label for="SPECIALIST2"><?=_("��")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("ѡ���ǣ���������Դ����Ա���½��û�����ʱ��������û��Ƿ��ܹ���¼")?>OA<?=_("ϵͳ��Ȩ�ޣ�ѡ������ޡ�")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("�����û��鿴�����˵�ֵ����Ϣ��")?></b></td>
                <td align="left">
                    <input type="radio" name="KEEP_WATCH" id="KEEP_WATCH1" value="1" <?if($KEEP_WATCH==1) echo "checked";?>><label for="KEEP_WATCH1"><?=_("��")?></label>
                    <input type="radio" name="KEEP_WATCH" id="KEEP_WATCH2" value="0" <?if($KEEP_WATCH==0) echo "checked";?>><label for="KEEP_WATCH2"><?=_("��")?></label>
                </td>
                <td width="300" align="left">
                    <?=_("ѡ���ǣ��û����ڸ��˿���->�ҵ�ֵ��->����ͼ�鿴�����˵�ֵ����Ϣ")?><?=_("ѡ������ޡ�")?>
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("�����������䣺")?></b></td>
                <td align="left" colspan="3">
                    <?=_("�У�")?><input type="text" name="MAN_AGE" class="BigInput" size="4" maxlength="100" value="<?=$AGE_ARRAY[0]?>"><?=_("��")?> &nbsp;
                    <?=_("Ů��")?><input type="text" name="WOMEN_AGE" class="BigInput" size="4" maxlength="100" value="<?=$AGE_ARRAY[1]?>"><?=_("��")?> &nbsp;
                </td>
            </tr>
            <tr class="TableData" align="center" height="30">
                <td width="250"><b><?=_("�������ݼ�ʱ�䣺")?></b></td>
                <td align="left" colspan="1">
                    <?=_("��ʼʱ�䣺")?>
                    <select name="ANNUAL_BEGIN_TIME_MONTH" class="BigSelect" onChange="check(this.value)">
                        <?
                        for($i=1;$i<=12;$i++){
                            ?>
                            <option value='<?=sprintf("%02d", $i);?>' <?php if($ANNUAL_BEGIN_TIME_MONTH == $i){echo 'selected';}?>><?=_("$i")?></option>
                        <? }?>
                    </select>
                    <?=_("��")?>
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
                    <?=_("�� 00:00:01")?> &nbsp;
                </td>
                <td width="300" align="left">
                    <?=_("Ĭ�����ݼ�ʱ���ʽ����ʼʱ�䣺1��1��0ʱ0��1�룬����ʱ�䣺12��30��23ʱ59��59��,����ʱ��Ӧ�ǿ�ʼʱ���ǰһ��")?>
                </td>
            </tr>
            <tr>
                <td nowrap  class="TableControl" colspan="3" align="center">
                    <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
                </td>
            </tr>
        </form>
    </table>
</div>
</body>
</html>