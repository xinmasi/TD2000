<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("���°�Ǽǲ�ѯ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="">
<?
$USER_ID = $_SESSION['LOGIN_USER_ID'];
$UID     = $_SESSION['LOGIN_UID'];

$duty_type = "";
//��ȡ�����Ű�
$sql = "SELECT * FROM user_duty WHERE uid = '$UID' and duty_date = '$SOME_DATE'";
$cur = exequery(TD::conn(),$sql);
if($arr=mysql_fetch_array($cur))
{
    $duty_type = $arr['duty_type'];
}

//---- ȡ�涨���°�ʱ�� -----
$DUTY_TYPE = intval($duty_type);
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


}else
{
    $WEEK    = date("w",strtotime($SOME_DATE));
    $HOLIDAY = "";
    $query  = "select * from ATTEND_HOLIDAY where BEGIN_DATE <='$SOME_DATE' and END_DATE>='$SOME_DATE'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
        $HOLIDAY = "�ڼ���";
    else
    {
        if(find_id($GENERAL,$WEEK))
            $HOLIDAY = "������";
        else
            $HOLIDAY = "δ���Ű��";
    }
}

if($HOLIDAY=="")
{
    $query="SELECT * FROM attend_evection WHERE USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$SOME_DATE') and to_days(EVECTION_DATE2)>=to_days('$SOME_DATE')";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $HOLIDAY = "����";
}
?>

<!----  ���°�Ǽ� ---->
<h5 class="attendance-title"><?=_("���°�Ǽǲ�ѯ")?> (<?=$DUTY_NAME?>)  <?=$SOME_DATE?></h5>

<br>

<table class="table table-bordered" align="center" style="width: 80%">
    <tr class="">
        <th nowrap align="center"><?=_("�ǼǴ���")?></th>
        <th nowrap align="center"><?=_("�Ǽ�����")?></th>
        <th nowrap align="center"><?=_("�涨ʱ��")?></th>
        <th nowrap align="center"><?=_("�Ǽ�ʱ��")?></th>
        <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
    </tr>
    <?
    if($HOLIDAY!="")
    {
        Message(_("��ʾ"),_($HOLIDAY));
    }
    else
    {
        for($I=1;$I<=6;$I++)
        {
            $DUTY_TIME_I="DUTY_TIME".$I;
            $DUTY_TIME_I=$$DUTY_TIME_I;
            if($I%2==0)
            {
                $DUTY_TYPE_I = 2;
            }else
            {
                $DUTY_TYPE_I = 1;
            }

            if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
                continue;

            $HOLIDAY1="";
            if($HOLIDAY=="")
            {
                $query="select leave_type2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$SOME_DATE $DUTY_TIME_I' and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME_I'";
                $cursor= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor)){
                    $LEAVE_TYPE2 = $ROW["LEAVE_TYPE2"];
                    $LEAVE_TYPE2 = get_hrms_code_name($LEAVE_TYPE2, "ATTEND_LEAVE");
                    $HOLIDAY1="<font color='#008000'>"._("���").  "-$LEAVE_TYPE2</font>";
                }
            }
            else
                $HOLIDAY1=$HOLIDAY;

            if($HOLIDAY==""&&$HOLIDAY1=="")
            {
                $DUTY_TIME_I = date("H:s:i",strtotime($DUTY_TIME_I));
                $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$SOME_DATE') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
                $cursor= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                    $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
            }

            $REGISTER_TIME="";
            $REGISTER_IP="";
            $REMARK="";
            $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $REGISTER_TIME  = $ROW["REGISTER_TIME"];
                $REGISTER_TIME2 = $ROW["REGISTER_TIME"];
                $REGISTER_IP    = $ROW["REGISTER_IP"];
                $REMARK         = $ROW["REMARK"];
                $REMARK         = str_replace("\n","<br>",$REMARK);
                $REGISTER_TIME  = strtok($REGISTER_TIME," ");
                $REGISTER_TIME  = strtok(" ");

                $duty_times   = $row['DUTY_TIME'];
                $time_late    = $row['TIME_LATE'];
                $time_early   = $row['TIME_EARLY'];

                if($duty_times!="")
                {
                    $DUTY_TIME_I = $duty_times;
                }
                if($I%2==0)
                {
                    $str = this_compare_time($REGISTER_TIME,$DUTY_TIME_I,$time_early,1);
                }
                else
                {
                    $str = this_compare_time($REGISTER_TIME,$DUTY_TIME_I,$time_late,0);
                }

                if($DUTY_TYPE_I == "1" && $str == 1)
                {
                    if($HOLIDAY1!='')
                    {
                        $REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>(".$HOLIDAY1.')';
                    }
                    else
                    {
                        $REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
                    }
                }
                if($DUTY_TYPE_I == "2" && $str == - 1)
                {
                    if($HOLIDAY1!='')
                    {
                        $REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>(".$HOLIDAY1.')';
                    }else
                    {
                        $REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
                    }
                }
                if($REMARK!="")
                    $REMARK="<br>"._("��ע��").$REMARK;
            }

            if($DUTY_TYPE_I=="1")
                $DUTY_TYPE_DESC=_("�ϰ�Ǽ�");
            else
                $DUTY_TYPE_DESC=_("�°�Ǽ�");

            $MSG = sprintf(_("��%d�εǼ�"), $I);
            ?>
            <tr class="">
                <td nowrap align="center"><?=$MSG?></td>
                <td nowrap align="center"><?=$DUTY_TYPE_DESC?></td>
                <td nowrap align="center"><?=$DUTY_TIME_I?></td>
                <td nowrap align="center">
                    <?
                    if($REGISTER_TIME=="")
                    {
                        if($HOLIDAY1=="")
                            echo _("δ�Ǽ�");
                        else
                            echo $HOLIDAY1;

                    }
                    else
                        echo $REGISTER_TIME.$REMARK;
                    ?>
                </td>
                <td nowrap align="center"><?=$REGISTER_IP?></td>
            </tr>
            <?
        }
    }
    ?>
</table>

<div align="center">
  <input type="button"  value="<?=_("����")?>" class="btn" onClick="location='attendance_list.php?USER_ID=<?=$USER_ID?>'">
</div>

</body>
</html>