<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��ͬ��ϸ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("��ͬ��ϸ��Ϣ")?></span><br>
        </td>
    </tr>
</table>
<br>
<?
//�޸���������״̬--yc
update_sms_status('63',$CONTRACT_ID);

$query = "SELECT * from HR_STAFF_CONTRACT where CONTRACT_ID='$CONTRACT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $STAFF_NAME=$ROW["STAFF_NAME"];
    $STAFF_CONTRACT_NO=$ROW["STAFF_CONTRACT_NO"];
    $CONTRACT_TYPE=$ROW["CONTRACT_TYPE"];
    $CONTRACT_SPECIALIZATION=$ROW["CONTRACT_SPECIALIZATION"];
    $MAKE_CONTRACT=$ROW["MAKE_CONTRACT"];
    $TRAIL_EFFECTIVE_TIME=$ROW["TRAIL_EFFECTIVE_TIME"];
    $PROBATIONARY_PERIOD=$ROW["PROBATIONARY_PERIOD"];
    $TRAIL_OVER_TIME=$ROW["TRAIL_OVER_TIME"];
    $PASS_OR_NOT=$ROW["PASS_OR_NOT"];
    $PROBATION_END_DATE=$ROW["PROBATION_END_DATE"];
    $PROBATION_EFFECTIVE_DATE=$ROW["PROBATION_EFFECTIVE_DATE"];
    $ACTIVE_PERIOD=$ROW["ACTIVE_PERIOD"];
    $CONTRACT_END_TIME=$ROW["CONTRACT_END_TIME"];
    $REMOVE_OR_NOT=$ROW["REMOVE_OR_NOT"];
    $CONTRACT_REMOVE_TIME=$ROW["CONTRACT_REMOVE_TIME"];
    $STATUS=$ROW["STATUS"];
    $SIGN_TIMES=$ROW["SIGN_TIMES"];
    $REMARK=$ROW["REMARK"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
    $ADD_TIME =$ROW["ADD_TIME"];
    $LAST_UPDATE_TIME =$ROW["LAST_UPDATE_TIME"];
    $REMIND_TIME =$ROW["REMIND_TIME"];
    $REMIND_USER =$ROW["REMIND_USER"];
    $CONTRACT_ENTERPRIES=$ROW['CONTRACT_ENTERPRIES'];
    $RENEW_TIME=$ROW['RENEW_TIME'];
    $IS_TRIAL=$ROW["IS_TRIAL"];
    $IS_RENEW=$ROW["IS_RENEW"];

    $RENEW_TIME1 = trim($RENEW_TIME,"|");
    $A_RENEW_TIME = explode("|",$RENEW_TIME1);
    $COUNT_RENEW_TIME= count($A_RENEW_TIME);
    if($A_RENEW_TIME[0]=="" || $A_RENEW_TIME[0]=="0000-00-00")
    {
        $COUNT_RENEW_TIME=0;
    }
    $query = "SELECT CODE_NAME from hr_code where CODE_NO='$CONTRACT_ENTERPRIES' && PARENT_NO='HR_ENTERPRISE'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $CONTRACT_ENTERPRIES=$ROW['CODE_NAME'];
    }
    //��ɫ��ȡ
    $query = "SELECT USER_PRIV_NAME from user where USER_ID='".$STAFF_NAME."';";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $HR_USER_PRIV_NAME=$ROW['USER_PRIV_NAME'];
    }

    $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    $CONTRACT_DEPT="";
    if($STAFF_NAME1!="")
    {
        $query1 = "SELECT STAFF_NAME,WORK_JOB from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $CONTRACT_JOB="";
            $query4 = "SELECT DEPT_ID from user where USER_ID='$STAFF_NAME'";
            $cursor4= exequery(TD::conn(),$query4);
            $ROW4=mysql_fetch_array($cursor4);
            if($ROW4["DEPT_ID"]==0)
            {
                $STAFF_NAME1=$ROW1["STAFF_NAME"];
                $HR_WORK_JOB=$ROW1['WORK_JOB'];
                $STAFF_NAME1=$STAFF_NAME1."(<font color='red'>"._("�û�����ְ")."</font>)";
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB=$ROW5["CODE_NAME"];
            }
            else
            {
                $STAFF_NAME1=$ROW1["STAFF_NAME"];
                $HR_WORK_JOB=$ROW1['WORK_JOB'];
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB=$ROW5["CODE_NAME"];
            }
        }
    }
    else
    {
        $query7 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor7= exequery(TD::conn(),$query7);
        if($ROW7=mysql_fetch_array($cursor7))
        {
            $STAFF_NAME1=$ROW7["STAFF_NAME"]."(<font color='red'>"._("�û���ɾ��")."</font>)";
        }
    }
    $query = "SELECT STAFF_E_NAME,BEFORE_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $BEFORE_NAME=$ROW["BEFORE_NAME"];
        $STAFF_E_NAME=$ROW["STAFF_E_NAME"];
    }
    $REMIND_USER_NAME=substr(GetUserNameById($REMIND_USER),0,-1);

    $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
    $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");

    if($CONTRACT_SPECIALIZATION==1)
        $CONTRACT_SPECIALIZATION=_("�̶�����");
    if($CONTRACT_SPECIALIZATION==2)
        $CONTRACT_SPECIALIZATION=_("�޹̶�����");
    if($CONTRACT_SPECIALIZATION==3)
        $CONTRACT_SPECIALIZATION=_("�����һ����������Ϊ����");

    if($REMIND_TIME=="0000-00-00 00:00:00")
        $REMIND_TIME="";
    if($TRAIL_EFFECTIVE_TIME=="0000-00-00")
        $TRAIL_EFFECTIVE_TIME="";
    if($TRAIL_OVER_TIME=="0000-00-00")
        $TRAIL_OVER_TIME="";
    if($PROBATION_END_DATE=="0000-00-00")
        $PROBATION_END_DATE="";
    if($PROBATION_EFFECTIVE_DATE=="0000-00-00")
        $PROBATION_EFFECTIVE_DATE="";
    if($CONTRACT_END_TIME=="0000-00-00")
        $CONTRACT_END_TIME="";
    if($CONTRACT_REMOVE_TIME=="0000-00-00")
        $CONTRACT_REMOVE_TIME="";
    if($ADD_TIME=="0000-00-00 00:00:00")
        $ADD_TIME="";
    if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
        $LAST_UPDATE_TIME="";
    if($MAKE_CONTRACT=="0000-00-00")
        $MAKE_CONTRACT="";
    if ($A_RENEW_TIME[$COUNT_RENEW_TIME-1] == "0000-00-00")
        $A_RENEW_TIME[$COUNT_RENEW_TIME-1]="";
    $query2 = "SELECT department.DEPT_NAME from department,user where user.USER_ID='$STAFF_NAME' and department.DEPT_ID=user.DEPT_ID;";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $CONTRACT_DEPT=$ROW2['DEPT_NAME'];
    }
    function DiffDate($date1,$date2)
    {
        if(strtotime($date1)>strtotime($date2))
        {
            $tmp   = $date2;
            $date2 = $date1;
            $date1 = $tmp;
        }
        list($y1,$m1,$d1)=explode('-',$date1);

        list($y2,$m2,$d2)=explode('-',$date2);

        $y = $y2-$y1;
        $m = $m2-$m1;
        $d = $d2-$d1;

        if($d<0)
        {
            $d+=(int)date('t',strtotime("-1 month $date2"));
            $m--;
        }
        if($m<0)
        {
            $m+=12;
            $y--;
        }
        return array($y, $m, $d);
    }
    ?>
    <table class="TableBlock" width="90%" align="center">
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ɫ��")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$HR_USER_PRIV_NAME?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("Ӣ������")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$STAFF_E_NAME?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ��ţ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$STAFF_CONTRACT_NO?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬǩԼ��˾��")?></td>
            <td align="left" class="TableData" width="180"><?=$CONTRACT_ENTERPRIES?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("�������ţ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$CONTRACT_DEPT?></td>
        </tr>

        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ���ͣ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$CONTRACT_TYPE?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ�������ԣ�")?></td>
            <td align="left" class="TableData" width="180"><?=$CONTRACT_SPECIALIZATION?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ״̬��")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$STATUS?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬǩ�����ڣ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$MAKE_CONTRACT?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ��Ч���ڣ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$PROBATION_EFFECTIVE_DATE?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ��ֹ���ڣ�")?></td>
            <td align="left" class="TableData" width="180"><?=$CONTRACT_END_TIME?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("�Ƿ������ڣ�")?></td>
            <td align="left" class="TableData" width="180"><?if($IS_TRIAL==1) echo _("��");if($IS_TRIAL==0) echo _("��");?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("���ý�ֹ���ڣ�")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$IS_TRIAL==1?$TRAIL_OVER_TIME:""?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��Ա�Ƿ�ת����")?></td>
            <td align="left" colspan="3" class="TableData" width="180"><?if($PASS_OR_NOT==1) echo _("��");if($PASS_OR_NOT==0) echo _("��");?></td>
        </tr>

        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ�Ƿ��ѽ����")?></td>
            <td align="left" class="TableData" width="180"><?if($REMOVE_OR_NOT==1) echo _("��");if($REMOVE_OR_NOT==0) echo _("��");?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ͬ������ڣ�")?></td>
            <td align="left" class="TableData" width="180"><?=$CONTRACT_REMOVE_TIME?></td>
        </tr>

        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("�Ǽ�ʱ�䣺")?></td>
            <td nowrap align="left" class="TableData" width="180" ><?=$ADD_TIME?></td>
            <td nowrap align="left" width="120" class="TableContent"><?=_("����޸�ʱ�䣺")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$LAST_UPDATE_TIME?></td>
        </tr>
        <?
        if($COUNT_RENEW_TIME!=0)
        {
            ?>
            <tr>
            <td class="TableContent" rowspan="<?=($COUNT_RENEW_TIME+1)?>"><?= _("��ͬ��ǩ��ʷ��Ϣ��") ?></td>
            <?
            if($RENEW_TIME != "0000-00-00" && $RENEW_TIME !="")
            {
                for($i=0;$i<$COUNT_RENEW_TIME;$i++)
                {
                    $time1 = $A_RENEW_TIME[$i];
                    if($i==0)
                    {
                        $time2 = $CONTRACT_END_TIME;
                    }
                    else
                    {
                        $time2 = $A_RENEW_TIME[$i-1];
                    }
                    $thistime = DiffDate($time1,$time2);
                    $start = 0;
                    $string = _("��ͬ��ǩ���ޣ�");
                    $string .= $thistime[0]._("��");
                    $string .= $thistime[1]._("��");
                    $string .= abs($thistime[2])._("��");
                    ?>
                    <tr id="lastinfo">
                        <td nowrap class="TableData"><?= _("��".($i+1)."����ǩ���ڣ�") ?></td>
                        <?
                        if($i==0 && $CONTRACT_END_TIME=="")
                        {
                            ?>
                            <td class="TableData" colspan="2"><? echo _("��ǩ�������ڣ�").$A_RENEW_TIME[$i]."&nbsp;&nbsp;&nbsp;"; ?></td>
                            <?
                        }
                        else
                        {
                            ?>
                            <td class="TableData"  colspan="2"><? echo $string."&nbsp;&nbsp;&nbsp;".$time2._("&nbsp;��&nbsp;").$A_RENEW_TIME[$i]."&nbsp;&nbsp;&nbsp;"; ?></td>
                            <?
                        }
                        ?>
                    </tr>
                    <?
                }
            }
        }
        ?>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("������Ա��")?></td>
            <td nowrap align="left" colspan="3" class="TableData"><?=$REMIND_USER_NAME?></td>
        </tr>

        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
            <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
        </tr>
        <tr>
            <td nowrap align="left" width="120" class="TableContent"><?=_("�����ĵ���")?></td>
            <td nowrap align="left" class="TableData" colspan="3">
                <?
                if($ATTACHMENT_ID=="")
                    echo _("�޸���");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);

                ?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="4">
                <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
            </td>
        </tr>
    </table>
    <?
}
else
    Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>
</html>
