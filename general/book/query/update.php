<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����Ǽ�");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
if($BORROW_ID!="")
{
    $query="SELECT RETURN_DATE,RENEW_FLAG,BOOK_NO FROM BOOK_MANAGE WHERE BORROW_ID='$BORROW_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $return_date =$ROW["RETURN_DATE"]; //��ʼ�黹ʱ��
        $renew_flag =$ROW["RENEW_FLAG"];
        $book_no =$ROW["BOOK_NO"];
    }
    if(strtotime($return_date) < time())
    {
        Message(_("����"),_("��ǰ�ѳ����������ڣ����ȹ黹���顣"));
        Button_Close();
        exit;
    }
    if($renew_flag == 1)
    {
        Message(_("����"),_("����������顣"));
        Button_Close();
        exit;
    }
    if($BORROW_REMARK!= "")
    {//���½������ڼ���ע
        $query="UPDATE BOOK_MANAGE SET RETURN_DATE='$RETURN_DATE', BORROW_REMARK='$BORROW_REMARK',RENEW_FLAG='1' WHERE BORROW_ID='$BORROW_ID' ";
    }
    else{
        $query="UPDATE BOOK_MANAGE SET RETURN_DATE='$RETURN_DATE', RENEW_FLAG='1' WHERE BORROW_ID='$BORROW_ID' ";
    }
    exequery(TD::conn(),$query);
    //ȡ��ͼ���������ŵ�ͼ�����Ա
    $MANAGER_ID = "";
    $query = "SELECT MANAGER_ID from BOOK_MANAGER where find_in_set('$BELONG_DEPT',MANAGE_DEPT_ID) or MANAGE_DEPT_ID='ALL_DEPT'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
        $MANAGER_ID .= $ROW["MANAGER_ID"];

    //�ڲ���������
    if($MANAGER_ID!="")
    {
        //��������
        $MSG = sprintf(_("�����ͼ��(��ţ�%s)����%s����!"), $book_no,$RETURN_DATE);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER_ID,73,$_SESSION["LOGIN_USER_NAME"].$MSG,"/book/query/query.php?STATUS=1");
    }

    Message(_("��ʾ"),_("����ɹ�"));
}else{
    Message(_("����"),_("ϵͳ��æ"));
    Button_Close();
    exit;
}
?>
<script>window.opener.document.location.reload();</script>
<br>
<div align="center">
    <input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close();">
</div>
</body>
</html>