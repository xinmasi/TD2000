<?
/**
 * �칫��Ʒ�黹����ҳ��
 */
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");


//�����黹
if(!empty($TRANS_ID))
{
    $sql = "SELECT b.PRO_NAME,c.MANAGER,a.BORROWER FROM office_transhistory as a left join  office_products as b on a.PRO_ID = b.PRO_ID left join office_depository as c on FIND_IN_SET(b.OFFICE_PROTYPE,c.OFFICE_TYPE_ID) WHERE TRANS_ID = '$TRANS_ID'";
    $res = exequery(TD::conn(),$sql);
    $arr = mysql_fetch_array($res);

    $this_time = date("Y-m-d",time());

    $query = "UPDATE office_transhistory SET RETURN_DATE='$this_time',RETURN_STATUS=0 WHERE TRANS_ID = '$TRANS_ID'";
    $cursor = exequery(TD::conn(),$query);

    $REMIND_URL="1:office_product/dept_approval/pending_list.php";
    $SMS_CONTENT=sprintf(_("������%s�İ칫��Ʒ%s�黹���롣"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']);
    send_sms("",$arr['BORROWER'],$arr['MANAGER'],43,$SMS_CONTENT,$REMIND_URL);

    Message(_("��ʾ"),_("���Ĺ黹�������ύ��"));
    Button_Back();
}
//�����黹
if(!empty($TRANS_STR_ID))
{
    if(substr($TRANS_STR_ID,-1)==",")
    {
        $TRANS_STR_ID = substr($TRANS_STR_ID,0,-1);
    }

    $trans_str_array = explode(",",$TRANS_STR_ID);
    $this_time = date("Y-m-d",time());

    for($i=0;$i<count($trans_str_array);$i++)
    {
        $sql = "SELECT b.PRO_NAME,c.MANAGER,a.BORROWER FROM office_transhistory as a left join  office_products as b on a.PRO_ID = b.PRO_ID left join office_depository as c on FIND_IN_SET(b.OFFICE_PROTYPE,c.OFFICE_TYPE_ID) WHERE TRANS_ID = '{$trans_str_array[$i]}'";
        $res = exequery(TD::conn(),$sql);
        $arr = mysql_fetch_array($res);

        $query = "UPDATE office_transhistory SET RETURN_DATE='$this_time',RETURN_STATUS=0 WHERE TRANS_ID = '{$trans_str_array[$i]}'";
        $cursor = exequery(TD::conn(),$query);

        $REMIND_URL="1:office_product/dept_approval/pending_list.php";
        $SMS_CONTENT=sprintf(_("������%s�İ칫��Ʒ%s�黹���롣"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']);
        send_sms("",$arr['BORROWER'],$arr['MANAGER'],43,$SMS_CONTENT,$REMIND_URL);
    }
    Message(_("��ʾ"),_("���������黹�������ύ��"));
    Button_Back();
}

?>