<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/chinese_date.php");
$connstatus = ($connstatus) ? true : false;
$PARA_ARRAY = get_sys_para("DUTY_MACHINE");
$DUTY_MACHINE=$PARA_ARRAY["DUTY_MACHINE"];

$HTML_PAGE_TITLE = _("���°�Ǽ�");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script language="JavaScript">
    function remark(REGISTER_TYPE,REGISTER_TIME)
    {
        URL="remark.php?REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
        myleft=(screen.availWidth-650)/2;
        window.open(URL,"formul_edit","height=400,width=650,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top=150,left="+myleft+",resizable=yes");
    }
</script>
<body class="bodycolor attendance ">

<?
//---- IP���鿪ʼ ---
$USER_IP=get_client_ip();
if(!check_ip($USER_IP,"1",$_SESSION["LOGIN_USER_ID"]))
{
    echo "<br/>";
    Message(_("����"),sprintf(_("����Ȩ�޴Ӹ�IP(%s)����!"), $USER_IP));
    exit;
}
//---- IP������� ---

$SYS_PARA_ARRAY = get_sys_para("NO_DUTY_USER");
$USER_IDS       = td_trim($SYS_PARA_ARRAY["NO_DUTY_USER"]);

if(find_id($USER_IDS,$_SESSION["LOGIN_USER_ID"]))
{
    echo "<br/>";
    Message("",_("������ǩ��Ա!"));
    exit;
}

$DUTY_TYPE = "";
$thisday   = date("Y-m-d",time());
//��ѯ�����Ű���Ϣ
$sql = "SELECT duty_type FROM user_duty WHERE uid = '".$_SESSION["LOGIN_UID"]."' AND duty_date = '$thisday'";
$cursor= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($cursor))
{
    $DUTY_TYPE = $row[0];
}

if($DUTY_TYPE=="99")
{
    ?>
    <!----  �ְ������°�Ǽ� ---->
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small table table-bordered" style="text-align: center;">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����ְ����°�Ǽ�")?></td>
        </tr>
    </table>
    <div style="text-align: center;">
        <input type="button"  value="<?=_("�ϰ�")?>" name="SB" class="BigButton" onClick="javascript:location='submit1.php?SXB=1'" title="<?=_("�ϰ�Ǽ�")?>">&nbsp;&nbsp;
        <input type="button"  value="<?=_("�°�")?>" name="XB" class="BigButton" onClick="javascript:location='submit1.php?SXB=2'" title="<?=_("�°�Ǽ�")?>">
    </div>
    <?
}
else
{
if($DUTY_TYPE=="")
{
    $query="SELECT * FROM attend_holiday WHERE BEGIN_DATE <='$thisday' AND END_DATE>='$thisday'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $STR = _("��ǩ�ڼ���");
    }
    else
    {
        $STR = _("��ǩ��Ϣ��");
    }

    message("",$STR);
    exit;
}
//---- ȡ�涨���°�ʱ�� -----
$DUTY_TYPE=intval($DUTY_TYPE);
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DUTY_NAME  = $ROW["DUTY_NAME"];
    $GENERAL    = $ROW["GENERAL"];

    $DUTY_TIME1 = $ROW["DUTY_TIME1"];
    $DUTY_TIME2 = $ROW["DUTY_TIME2"];
    $DUTY_TIME3 = $ROW["DUTY_TIME3"];
    $DUTY_TIME4 = $ROW["DUTY_TIME4"];
    $DUTY_TIME5 = $ROW["DUTY_TIME5"];
    $DUTY_TIME6 = $ROW["DUTY_TIME6"];

    $DUTY_TYPE1 = $ROW["DUTY_TYPE1"];
    $DUTY_TYPE2 = $ROW["DUTY_TYPE2"];
    $DUTY_TYPE3 = $ROW["DUTY_TYPE3"];
    $DUTY_TYPE4 = $ROW["DUTY_TYPE4"];
    $DUTY_TYPE5 = $ROW["DUTY_TYPE5"];
    $DUTY_TYPE6 = $ROW["DUTY_TYPE6"];

    $DUTY_BEFORE1 = $ROW["DUTY_BEFORE1"];
    $DUTY_AFTER1  = $ROW["DUTY_AFTER1"];
    $DUTY_BEFORE2 = $ROW["DUTY_BEFORE2"];
    $DUTY_AFTER2  = $ROW["DUTY_AFTER2"];
    $DUTY_BEFORE3 = $ROW["DUTY_BEFORE3"];
    $DUTY_AFTER3  = $ROW["DUTY_AFTER3"];
    $DUTY_BEFORE4 = $ROW["DUTY_BEFORE4"];
    $DUTY_AFTER4  = $ROW["DUTY_AFTER4"];
    $DUTY_BEFORE5 = $ROW["DUTY_BEFORE5"];
    $DUTY_AFTER5  = $ROW["DUTY_AFTER5"];
    $DUTY_BEFORE6 = $ROW["DUTY_BEFORE6"];
    $DUTY_AFTER6  = $ROW["DUTY_AFTER6"];

    $TIME_LATE1   = $ROW["TIME_LATE1"];
    $TIME_EARLY2  = $ROW["TIME_EARLY2"];
    $TIME_LATE3   = $ROW["TIME_LATE3 "];
    $TIME_EARLY4  = $ROW["TIME_EARLY4"];
    $TIME_LATE5   = $ROW["TIME_LATE5"];
    $TIME_EARLY6  = $ROW["TIME_EARLY6"];
}
?>

<!----  ���������°�Ǽ� ---->
<h5 class="attendance-title">
    <span class="big3"><?=_("�������°�Ǽ�")?>(<?=$DUTY_NAME?><?=_("��ǰʱ�䣺")?><span id="timetable"></span>)</span><br>
    <SCRIPT LANGUAGE="JavaScript">
        <?
        list($CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND) = DateTimeEx(hexdec(dechex(time()+1)));
        $CUR_MON--;
        $TIME_STR="$CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND";
        ?>
        var OA_TIME1 = new Date(<?=$TIME_STR?>);
        function get_time()
        {
            window.setTimeout( "get_time()", 1000 );

            timestr=OA_TIME1.toLocaleString();
            document.getElementById("timetable").innerHTML=timestr;
            OA_TIME1.setSeconds(OA_TIME1.getSeconds()+1);
        }
        get_time();

    </SCRIPT>
</h5>
<?

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$FLAG=0;
$STR="";
//������������������°�Ǽ�
//1  �ڼ���
/*$query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$CUR_DATE' and END_DATE>='$CUR_DATE'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
    $FLAG=1;
    $STR=_("�ڼ��ղ�����Ǽ�");
}*/

//2  �����ڼ�
$query="select * from ATTEND_EVECTION where USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' and ALLOW = '1' and to_days(EVECTION_DATE1)<=to_days('$CUR_DATE') and to_days(EVECTION_DATE2)>=to_days('$CUR_DATE')";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
    $FLAG = 1;
    $STR  = _("�����ڼ䲻����Ǽ�");
}

//3  ���
$query="select * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='1' and LEAVE_DATE1<='$CUR_TIME' and LEAVE_DATE2>='$CUR_TIME'";
$cursor1= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor1))
{
    $FLAG = 1;
    $STR  = _("��������ǰ������Ǽ�");
}

//4  �����
/*$query="select count(*) from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' and STATUS = 0) and to_days(SUBMIT_TIME)=to_days('$CUR_DATE') and OUT_TIME1<='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."' and OUT_TIME2>='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
    $FLAG = 1;
    $STR  = _("�������ǰ������Ǽ�");
}*/

if($FLAG==1)
{
    message("","$STR");
    exit;
}

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DUTY_INTERVAL_BEFORE1=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DUTY_INTERVAL_AFTER1=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DUTY_INTERVAL_BEFORE2=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DUTY_INTERVAL_AFTER2=$ROW["PARA_VALUE"];

//Message("", sprintf(_("�涨ʱ��֮ǰ %s ���ӵ�֮�� %s �������ʱ��ɽ����ϰ�Ǽǣ��涨ʱ��֮ǰ %s ���ӵ�֮�� %s �������ʱ��ɽ����°�Ǽ�"), $DUTY_INTERVAL_BEFORE1, $DUTY_INTERVAL_AFTER1, $DUTY_INTERVAL_BEFORE2, $DUTY_INTERVAL_AFTER2));

$SOME_DATE=date("Y-m-d");
$WEEK=date("w",strtotime($SOME_DATE));

$HOLIDAY="";
$query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$SOME_DATE' and END_DATE>='$SOME_DATE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $HOLIDAY="<font color='#008000'>"._("�ڼ���")."</font>";
else
{
    if(find_id($GENERAL,$WEEK))
        $HOLIDAY="<font color='#008000'>"._("������")."</font>";
}

//---- �鿴�������°���� -----
?>
<table class="table table-bordered" align="center" width="95%">
    <tr class="TableHeader">
        <th nowrap style="text-align: center;"><?=_("�ǼǴ���")?></th>
        <th nowrap style="text-align: center;"><?=_("�Ǽ�����")?></th>
        <th nowrap style="text-align: center;"><?=_("�涨ʱ��")?></th>
        <th nowrap style="text-align: center;"><?=_("�涨��Χ")?></th>
        <th nowrap style="text-align: center;"><?=_("�Ǽ�ʱ��")?></th>
        <th nowrap style="text-align: center;"><?=_("����")?></th>
    </tr>
    <?
    for($I=1;$I<=6;$I++)
    {
        $STR="DUTY_TIME".$I;
        $DUTY_TIME=$$STR;

        if($DUTY_TIME=="")
            continue;

        $STR="DUTY_TYPE".$I;
        $DUTY_TYPE=$$STR;

        $STR = "DUTY_BEFORE".$I;
        $DUTY_BEFORE = $$STR;

        $STR = "DUTY_AFTER".$I;
        $DUTY_AFTER = $$STR;

        $REGISTER_TIME="";
        $SIGN="0";

        $IS_MOBILE_DUTY = 0;
        //--- ѭ��6�β�ѯ���°�ǼǼ�¼ ---
        $query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and to_days(REGISTER_TIME)=to_days('$CUR_DATE') and REGISTER_TYPE='$I'";
        $cursor= exequery(TD::conn(),$query,true);
        if($ROW=mysql_fetch_array($cursor))
        {
            $REGISTER_TIME=$ROW["REGISTER_TIME"];
            $REGISTER_TIME_BAK=$ROW["REGISTER_TIME"];
            $REGISTER_TIME=strtok($REGISTER_TIME," ");
            $REGISTER_TIME=strtok(" ");
            $IS_MOBILE_DUTY = $ROW['IS_MOBILE_DUTY'];

            if($HOLIDAY==""&&$DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
            {$REGISTER_TIME.=" <span class=big4>"._("�ٵ�")."</span>";$SIGN="1";}

            if($HOLIDAY==""&&$DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
            {$REGISTER_TIME.=" <span class=big4>"._("����")."</span>";$SIGN="1";}
        }

        $DUTY_INTERVAL_BEFORE="DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
        $DUTY_INTERVAL_AFTER="DUTY_INTERVAL_AFTER".$DUTY_TYPE;

        $DUTY_TYPE_NAME = $I%2==0?"�°�ǩ��":"�ϰ�ǩ��";

        $s_time = strtotime($DUTY_TIME)-$DUTY_BEFORE*60;
        $e_time = strtotime($DUTY_TIME)+$DUTY_AFTER*60;
        $DUTY_TIME_STR  = date("H:i:s",$s_time)."��".date("H:i:s",$e_time);

        if($REGISTER_TIME=="")
            $REGISTER_TIME_DESC=_("δ�Ǽ�");
        else
            $REGISTER_TIME_DESC=$REGISTER_TIME;

        $MSG = sprintf(_("��%d�εǼ�"), $I);
        ?>
        <tr class="TableData">
            <td nowrap style="text-align: center;"><?=$MSG?></td>
            <td nowrap style="text-align: center;"><?=$DUTY_TYPE_NAME?></td>
            <td nowrap style="text-align: center;"><?=$DUTY_TIME?></td>
            <td nowrap style="text-align: center;"><?=$DUTY_TIME_STR?></td>
            <td nowrap style="text-align: center;"><?=$REGISTER_TIME_DESC?></td>
            <td nowrap style="text-align: center;">
                <?
                if($REGISTER_TIME=="") //δ�Ǽ�
                {
                    if($DUTY_MACHINE==1)
                        echo _("��ʹ�ÿ��ڻ�����");
                    else
                    {
                        if($s_time <= strtotime($CUR_TIME) && strtotime($CUR_TIME) <= $e_time)
                        {
                            ?>
                            <a href="submit.php?REGISTER_TYPE=<?=$I?>"><?=$DUTY_TYPE_NAME?></a>
                            <?
                        }
                        else
                            echo _("���ڵǼ�ʱ���");
                    }//no DUTY_MACHINE
                }
                else //�ѵǼ�
                {
                    ?>
                    <?
                    if($IS_MOBILE_DUTY == 1)
                        echo _("���ֻ�����");
                    else
                        echo _("�ѿ���");
                    ?>
                    <a href="javascript:remark('<?=$I?>','<?=$REGISTER_TIME_BAK?>');"><?=_("��ע")?></a>
                    <?
                    //�°�������30�������ٴδ򿨣��������ʧ�󣬵�������
                    $A=strtotime($CUR_TIME)-strtotime($REGISTER_TIME_BAK);
                    if($DUTY_TYPE==2 && $DUTY_MACHINE!=1 && strtotime($CUR_TIME)-strtotime($REGISTER_TIME_BAK)<=1800)
                    {
                        ?>
                        <a href="submit.php?REGISTER_TYPE=<?=$I?>"><?=_("���½����°�Ǽ�")?></a>
                        <?
                    }
                }
                ?>
            </td>
        </tr>

        <?
    }//for
    }
    ?>

</table>

</body>
</html>