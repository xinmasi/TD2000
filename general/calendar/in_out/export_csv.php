<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$WHERE_STR = "";
$WHERE_STR2 = "";
$POST_CAL_TYPE = $CAL_TYPE;

$CUR_DATE=date("Y-m-d",time());
$BEGIN_DATE1=$BEGIN_DATE;
$END_DATE1=$END_DATE;
if($BEGIN_DATE=="")
    $BEGIN_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' -3 month')),0,7)."-01";
if($END_DATE=="")
    $END_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' +12 month')),0,7)."-01";
$BEGIN_DATE_U=strtotime($BEGIN_DATE);
$END_DATE_U=strtotime($END_DATE);
$WHERE_STR2 .= "and   (
   (END_TIME!='0' and BEGIN_TIME <='$BEGIN_DATE_U' and END_TIME>='$BEGIN_DATE_U') or
   (END_TIME!='0' and BEGIN_TIME >='$BEGIN_DATE_U' and  END_TIME<='$END_DATE_U') or
   (END_TIME!='0' and BEGIN_TIME <='$END_DATE_U' and  END_TIME>='$END_DATE_U') or
   (END_TIME='0' and  BEGIN_TIME <='$BEGIN_DATE_U') or
   (END_TIME='0' and  BEGIN_TIME >='$BEGIN_DATE_U' and BEGIN_TIME<='$END_DATE_U')
  ) ";

$BEGIN_DATE2 = $BEGIN_DATE = $BEGIN_DATE." 00:00:01";
$END_DATE2 = $END_DATE = $END_DATE." 23:59:59";
$BEGIN_DATE_U=strtotime($BEGIN_DATE);
$END_DATE_U=strtotime($END_DATE);
$WHERE_STR .= "and  (
   (END_TIME!='0' and BEGIN_TIME <='$BEGIN_DATE_U' and END_TIME>='$BEGIN_DATE_U') or
   (END_TIME!='0' and BEGIN_TIME >='$BEGIN_DATE_U' and  END_TIME<='$END_DATE_U') or
   (END_TIME!='0' and BEGIN_TIME <='$END_DATE_U' and  END_TIME>='$END_DATE_U') or
   (END_TIME='0' and  BEGIN_TIME <='$BEGIN_DATE_U') or
   (END_TIME='0' and  BEGIN_TIME >='$BEGIN_DATE_U' and BEGIN_TIME<='$END_DATE_U')
  ) ";
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
    if($TO_ID==""&&$PRIV_ID==""&&$TO_ID2=="")
        $ADMIN_WHERE_STR = "USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
    else
    {
        if($TO_ID!="ALL_DEPT")
        {
            $query = "SELECT USER_ID from USER where (find_in_set(DEPT_ID,'$TO_ID') or find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$TO_ID2')) and (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0')";
            $cursor= exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
                $USER_ID_STR.=$ROW["USER_ID"].",";
        }else{
            $query = "SELECT USER_ID from USER where NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0'";
            $cursor= exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
                $USER_ID_STR.=$ROW["USER_ID"].",";
        }
        $ADMIN_WHERE_STR = "find_in_set(USER_ID,'$USER_ID_STR') ";
    }
}else{
    $ADMIN_WHERE_STR = "USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
}
if(MYOA_IS_UN == 1)
{
    $CSV_OUT ="SUBJECT,BEGIN_DATE**,BEGIN_TIME,END_DATE,END_TIME,WHOLE_DAY_AFFAIR,NOTIFY(Y/N),NOTIFY_DATE,NOTIFY_TIME,SENSITIVITY,REMARK,PRIVATE,PRIORITY,BEGIN_DATE,CLOSING_DATE,FINISH_DATE,FINISH_PER,ALL_WORK,REAL_WORK,MEMO,CLASS,STATUS";
}
else
{
    $CSV_OUT = _("����").","._("��ʼ����**").","._("��ʼʱ��").","._("��������").","._("����ʱ��").","._("ȫ���¼�").","._("���ѿ�/��").","._("��������").","._("����ʱ��").","._("���ж�").","._("˵��").","._("��������").","._("���ȼ�").","._("��ʼ����").","._("��ֹ����").","._("�������").","._("��ɰٷֱ�").","._("ȫ������").","._("ʵ�ʹ���").","._("��ע").","._("���").","._("״̬");
}
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($CONTENT_NAME);
$objExcel->addHead($CSV_OUT);

//�ճ�
if($CALENDAR_CONTENT=="on")
{
    $CALENDAR_WHERE_STR = str_replace("BEGIN_TIME","CAL_TIME",$WHERE_STR);
    if($POST_CAL_TYPE!="")
        $CALENDAR_WHERE_STR .= " and CAL_TYPE ='$POST_CAL_TYPE'";

    $query = "SELECT * from CALENDAR where ".$ADMIN_WHERE_STR.$CALENDAR_WHERE_STR."order by CAL_TIME";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $CAL_ID1        = $ROW["CAL_ID"];
        $USER_ID1       = $ROW["USER_ID"];
        $CAL_TIME1      = $ROW["CAL_TIME"];
        $CAL_TIME1      = date("Y-m-d H:i:s",$CAL_TIME1);
        $END_TIME1      = $ROW["END_TIME"];
        $END_TIME1      = date("Y-m-d H:i:s",$END_TIME1);
        $CAL_TYPE       = $ROW["CAL_TYPE"];
        $CAL_LEVEL      = $ROW["CAL_LEVEL"];
        $CONTENT        = format_cvs($ROW["CONTENT"]);
        $MANAGER_ID     = $ROW["MANAGER_ID"];
        $OVER_STATUS    = $ROW["OVER_STATUS"];
        $BEFORE_REMAIND = $ROW["BEFORE_REMAIND"];
        $ALLDAY		  = $ROW["ALLDAY"];
        if($ALLDAY == '1'){
            $ALLDAY = "��";
        }else{
            $ALLDAY = "��";
        }

        if($OVER_STATUS==0)
            $OVER_STATUS_STR = _("δ��ʼ");
        else
            $OVER_STATUS_STR = _("�����");
        if($CAL_LEVEL=="1")
            $CAL_LEVEL_STR = _("��");
        else if($CAL_LEVEL=="2" || $CAL_LEVEL=="3" || $CAL_LEVEL=="")
            $CAL_LEVEL_STR = _("��");
        else
            $CAL_LEVEL_STR = _("��");

        if($CAL_TYPE=="2")
            //$CAL_TYPE_FLAG = "TRUE";
            $CAL_TYPE_FLAG = "��������";
        else
            //$CAL_TYPE_FLAG = "FALSE";
            $CAL_TYPE_FLAG = "��������";

        $TMP_SUBJECT = csubstr($CONTENT,0,20);
        $TMP_CAL_DATE1 = substr($CAL_TIME1,0,10);
        $TMP_CAL_TIME1 = substr($CAL_TIME1,11);
        $TMP_END_DATE1 = substr($END_TIME1,0,10);
        $TMP_END_TIME1 = substr($END_TIME1,11);

        //��ǰ����ʱ��
        $REMAIND_ARRAY = explode("|",$BEFORE_REMAIND);

        $BEFORE_DAY  = $REMAIND_ARRAY[0];
        $BEFORE_HOUR = $REMAIND_ARRAY[1];
        $BEFORE_MIN  = $REMAIND_ARRAY[2];


        $REMIND_TIME = date("Y-m-d H:i:s",$ROW["CAL_TIME"]-$BEFORE_DAY*24*3600-$BEFORE_HOUR*3600-$BEFORE_MIN*60);

        $CSV_OUT = "$TMP_SUBJECT,$TMP_CAL_DATE1,$TMP_CAL_TIME1,$TMP_END_DATE1,$TMP_END_TIME1,$ALLDAY,"._("��").",$TMP_CAL_DATE1,$REMIND_TIME,"._("��ͨ").",$CONTENT,$CAL_TYPE_FLAG,$CAL_LEVEL_STR,$TMP_CAL_DATE1,$TMP_END_DATE1,,,,,,,$OVER_STATUS_STR";
        $objExcel->addRow($CSV_OUT);
    }
}
//����������

if($AFFAIR_CONTENT=="on")
{
    $query = "SELECT * from AFFAIR where ".$ADMIN_WHERE_STR.$WHERE_STR."order by BEGIN_TIME";
    $cursor= exequery(TD::conn(),$query);

    while($ROW=mysql_fetch_array($cursor))
    {
        $AFF_ID=$ROW["AFF_ID"];
        $USER_ID=$ROW["USER_ID"];
        $BEGIN_TIME=$ROW["BEGIN_TIME"];
        $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
        $END_TIME=$ROW["END_TIME"];
        if($END_TIME!=0)
            $END_TIME=date("Y-m-d H:i:s",$END_TIME);
        $TYPE=$ROW["TYPE"];
        $REMIND_DATE=$ROW["REMIND_DATE"];
        $REMIND_TIME=$ROW["REMIND_TIME"];
        $CONTENT=format_cvs($ROW["CONTENT"]);
        $TMP_SUBJECT = csubstr($CONTENT,0,20);
        $LAST_REMIND=$ROW["LAST_REMIND"];
        $SMS2_REMIND=$ROW["SMS2_REMIND"];
        $LAST_SMS2_REMIND=$ROW["LAST_SMS2_REMIND"];
        $MANAGER_ID=$ROW["MANAGER_ID"];
        $TMP_BEGIN_TIME = $ROW["BEGIN_TIME_TIME"];
        $TMP_END_TIME = $ROW["END_TIME_TIME"];
        //$TMP_BEGIN_TIME = substr($BEGIN_TIME,11);
        //$TMP_END_TIME = substr($END_TIME,11);
        /*
           if ��������
              ���Ϊÿ��һ����¼ �������ں�ʱ��ֱ�Ϊ��Ϊ�������ں����õ�ʱ��
           if ��������
              ���Ϊÿ����1����¼ �������ں�ʱ��ֱ�Ϊ�����õ��������ں�ʱ��
           if ��������
              ���Ϊÿ��1����¼ �������ں�ʱ��ֱ�Ϊ�����õ����ں�ʱ��
           if ��������
              ���Ϊÿ��1����¼ �������ں�ʱ��ֱ�Ϊ�����õ����ں�ʱ��
        */
//compare_date($DATE1,$DATE2) //-- DATE1=DATE2 ����0,DATE1>DATE2 ����1,DATE1<DATE2 ����-1

        if($END_TIME=="0" || compare_time($END_TIME,$END_DATE2)>=0)
            $END_TIME=$END_DATE2;
        else
            $END_TIME=$END_TIME;
        if(compare_time($BEGIN_DATE2,$BEGIN_TIME)>=0)
            $BEGIN_TIME=$BEGIN_DATE2;
        else
            $BEGIN_TIME=$BEGIN_TIME;

        $TMP_BEGIN_DATE = substr($BEGIN_TIME,0,10);
        $TMP_END_DATE = substr($END_TIME,0,10);

        if($TYPE==2)//��������
        {
            while(compare_date($TMP_END_DATE,$TMP_BEGIN_DATE)>=0)
            {
                $TMP_DATE=$TMP_BEGIN_DATE;
                $CSV_OUT = "$TMP_SUBJECT,$TMP_DATE,$TMP_BEGIN_TIME,$TMP_DATE,$TMP_END_TIME,"._("��").","._("��").",$TMP_DATE,$REMIND_TIME,"._("��ͨ").",$CONTENT,"._("��������").",,$TMP_DATE,$TMP_DATE";
                $objExcel->addRow($CSV_OUT);
                $TMP_BEGIN_DATE = date('Y-m-d',strtotime($TMP_BEGIN_DATE.' +1 day'));
            }
        }
        if($TYPE==3)//��������
        {
            while(compare_date($TMP_END_DATE,$TMP_BEGIN_DATE)>=0)
            {
                $TMP_DATE=$TMP_BEGIN_DATE;
                if(date("w",strtotime($TMP_BEGIN_DATE))==$REMIND_DATE)
                {
                    $CSV_OUT = "$TMP_SUBJECT,$TMP_DATE,$TMP_BEGIN_TIME,$TMP_DATE,$TMP_END_TIME,"._("��").","._("��").",$TMP_DATE,$REMIND_TIME,"._("��ͨ").",$CONTENT,"._("��������").",,$TMP_DATE,$TMP_DATE";
                    $objExcel->addRow($CSV_OUT);
                }
                $TMP_BEGIN_DATE = date('Y-m-d',strtotime($TMP_BEGIN_DATE.' +1 day'));
            }
        }
        if($TYPE==4)//��������
        {
            while(compare_date($TMP_END_DATE,$TMP_BEGIN_DATE)>=0)
            {
                $TMP_DATE=$TMP_BEGIN_DATE;
                if(date("j",strtotime($TMP_BEGIN_DATE))==$REMIND_DATE)
                {
                    $CSV_OUT = "$TMP_SUBJECT,$TMP_DATE,$TMP_BEGIN_TIME,$TMP_DATE,$TMP_END_TIME,"._("��").","._("��").",$TMP_DATE,$REMIND_TIME,"._("��ͨ").",$CONTENT,"._("��������").",,$TMP_DATE,$TMP_DATE";
                    $objExcel->addRow($CSV_OUT);
                }
                $TMP_BEGIN_DATE = date('Y-m-d',strtotime($TMP_BEGIN_DATE.' +1 day'));
            }
        }
        if($TYPE==5)//��������
        {
            while(compare_date($TMP_END_DATE,$TMP_BEGIN_DATE)>=0)
            {
                $TMP_DATE=$TMP_BEGIN_DATE;
                if(date("n-j",strtotime($TMP_BEGIN_DATE))==$REMIND_DATE)
                {
                    $CSV_OUT = "$TMP_SUBJECT,$TMP_DATE,$TMP_BEGIN_TIME,$TMP_DATE,$TMP_END_TIME,"._("��").","._("��").",$TMP_DATE,$REMIND_TIME,"._("��ͨ").",$CONTENT,"._("��������").",,$TMP_DATE,$TMP_DATE";
                    $objExcel->addRow($CSV_OUT);
                }
                $TMP_BEGIN_DATE = date('Y-m-d',strtotime($TMP_BEGIN_DATE.' +1 day'));
            }
        }

    }
}

//����
if($TASK_CONTENT=="on")
{
    //$TMP_STR = str_replace("BEGIN_TIME","BEGIN_DATE",$WHERE_STR2);
    //$TASK_WHERE_STR = str_replace("END_TIME","END_DATE",$TMP_STR);
    //$TASK_WHERE_STR=" AND BEGIN_DATE>='$BEGIN_DATE1' and (END_DATE<='$END_DATE1' or END_DATE='0000-00-00')";
    $TASK_WHERE_STR = " AND ((END_DATE!='0000-00-00' and BEGIN_DATE <='$BEGIN_DATE1' and END_DATE>='$BEGIN_DATE1') or (END_DATE!='0000-00-00' and BEGIN_DATE >='$BEGIN_DATE1' and  END_DATE<='$END_DATE1') or (END_DATE!='0000-00-00' and BEGIN_DATE <='$END_DATE1' and  END_DATE>='$END_DATE1') or (END_DATE='0000-00-00' and  BEGIN_DATE <='$BEGIN_DATE1') or  (END_DATE='0000-00-00' and  BEGIN_DATE >='$BEGIN_DATE1' and BEGIN_DATE<='$END_DATE1'))";
    if($POST_CAL_TYPE!="")
        $TASK_WHERE_STR .= " and TASK_TYPE ='$POST_CAL_TYPE'";
    $query = "SELECT * from TASK where ".$ADMIN_WHERE_STR.$TASK_WHERE_STR."order by BEGIN_DATE";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $TASK_ID=$ROW["TASK_ID"];
        $USER_ID=$ROW["USER_ID"];
        $TASK_NO=$ROW["TASK_NO"];
        $TASK_TYPE=$ROW["TASK_TYPE"];
        $TASK_STATUS=$ROW["TASK_STATUS"];
        $COLOR=$ROW["COLOR"];
        $IMPORTANT=$ROW["IMPORTANT"];
        $SUBJECT=$ROW["SUBJECT"];
        $EDIT_TIME=$ROW["EDIT_TIME"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $CONTENT=format_cvs($ROW["CONTENT"]);
        $RATE=$ROW["RATE"]/100;
        $FINISH_TIME=$ROW["FINISH_TIME"];
        $TOTAL_TIME=$ROW["TOTAL_TIME"];
        $USE_TIME=$ROW["USE_TIME"];
        $CAL_ID=$ROW["CAL_ID"];
        $MANAGER_ID=$ROW["MANAGER_ID"];

        if($FINISH_TIME=="0000-00-00 00:00:00")
            $FINISH_TIME="";
        if($BEGIN_DATE=="0000-00-00")
            $BEGIN_DATE="";
        if($END_DATE=="0000-00-00")
            $END_DATE="";

        if($IMPORTANT=="1")
            $IMPORTANT_STR = _("��");
        else if($IMPORTANT=="2" || $IMPORTANT=="3" || $IMPORTANT=="")
            $IMPORTANT_STR = _("��");
        else
            $IMPORTANT_STR = _("��");
        $FINISH_DATE = substr($FINISH_TIME,0,10);
        //������ɫ����
        $PARA_ARRAY=get_sys_para("CALENDAR_TASK_COLOR");
        $PARA_VALUE=$PARA_ARRAY["CALENDAR_TASK_COLOR"];
        $PARA_VALUE=explode(",",$PARA_VALUE);
        $CALENDAR_TASK_COLOR_0=$PARA_VALUE[0]; //��ɫ
        $CALENDAR_TASK_COLOR_1=$PARA_VALUE[1]; //��ɫ
        $CALENDAR_TASK_COLOR_2=$PARA_VALUE[2]; //��ɫ
        $CALENDAR_TASK_COLOR_3=$PARA_VALUE[3]; //��ɫ
        $CALENDAR_TASK_COLOR_4=$PARA_VALUE[4]; //��ɫ
        $CALENDAR_TASK_COLOR_5=$PARA_VALUE[5]; //��ɫ
        if($CALENDAR_TASK_COLOR_0=="")
            $CALENDAR_TASK_COLOR_0=_("��ɫ���");
        if($CALENDAR_TASK_COLOR_1=="")
            $CALENDAR_TASK_COLOR_1=_("��ɫ���");
        if($CALENDAR_TASK_COLOR_2=="")
            $CALENDAR_TASK_COLOR_2=_("��ɫ���");
        if($CALENDAR_TASK_COLOR_3=="")
            $CALENDAR_TASK_COLOR_3=_("��ɫ���");
        if($CALENDAR_TASK_COLOR_4=="")
            $CALENDAR_TASK_COLOR_4=_("��ɫ���");
        if($CALENDAR_TASK_COLOR_5=="")
            $CALENDAR_TASK_COLOR_5=_("��ɫ���");
        if($COLOR=="1")
            $COLOR_STR = $CALENDAR_TASK_COLOR_0;
        if($COLOR=="2")
            $COLOR_STR = $CALENDAR_TASK_COLOR_1;
        if($COLOR=="3")
            $COLOR_STR = $CALENDAR_TASK_COLOR_2;
        if($COLOR=="4")
            $COLOR_STR = $CALENDAR_TASK_COLOR_3;
        if($COLOR=="5")
            $COLOR_STR = $CALENDAR_TASK_COLOR_4;
        if($COLOR=="6")
            $COLOR_STR = $CALENDAR_TASK_COLOR_5;

        switch($TASK_STATUS)
        {
            case "1": $STATUS_DESC=_("δ��ʼ");break;
            case "2": $STATUS_DESC=_("������");break;
            case "3": $STATUS_DESC=_("�����");break;
            case "4": $STATUS_DESC=_("�ȴ�������");break;
            case "5": $STATUS_DESC=_("���Ƴ�");break;
        }

        if($TASK_TYPE=="2")
            //$TASK_TYPE_FLAG = "TRUE";
            $TASK_TYPE_FLAG = "��������";
        else
            //$TASK_TYPE_FLAG = "FALSE";
            $TASK_TYPE_FLAG = "��������";

        $CSV_OUT = "$SUBJECT,$BEGIN_DATE,8:0:0,$END_DATE,17:0:0,"._("��").","._("��").",$BEGIN_DATE,8:0:0,"._("��ͨ").",$CONTENT,$TASK_TYPE_FLAG,$IMPORTANT_STR,$BEGIN_DATE,$END_DATE,,$RATE,$TOTAL_TIME,$USE_TIME,$CONTENT,$COLOR_STR,$STATUS_DESC";
        $objExcel->addRow($CSV_OUT);
    }
}

$objExcel->Save();
?>