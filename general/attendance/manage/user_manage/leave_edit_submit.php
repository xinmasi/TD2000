<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�޸���ټ�¼");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

<?
$sql="select ALLOW from attend_leave where LEAVE_ID='$LEAVE_ID'";
$result= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($result))
{
    $allow = $row["ALLOW"];
}
//----------- �Ϸ���У�� ---------
if($LEAVE_DATE1!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("����"),_("��ٿ�ʼʱ���ʽ���ԣ�Ӧ���� 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($LEAVE_DATE2!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("����"),_("��ٽ���ʱ���ʽ���ԣ�Ӧ���� 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($DESTROY_TIME!="")
{
    $TIME_OK=is_date_time($DESTROY_TIME);
    if(!$TIME_OK && (($allow!="3" && $DESTROY_TIME!="0000-00-00 00:00:00") || $allow=="3"))
    {
        Message(_("����"),_("����ʱ���ʽ���ԣ�Ӧ���� 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
    if(compare_date_time($LEAVE_DATE1,$DESTROY_TIME)>=0 && ($allow=="3" || $DESTROY_TIME!="0000-00-00 00:00:00"))
    {
        Message(_("����"),_("����ʱ��Ҫ������ٿ�ʼʱ��"));
        Button_Back();
        exit;
    }
}

if(compare_date_time($LEAVE_DATE1,$LEAVE_DATE2)>=0)
{
    Message(_("����"),_("��ٽ���ʱ��Ӧ������ٿ�ʼʱ��"));
    Button_Back();
    exit;
}
if($allow=="3" && $DESTROY_TIME=="")
{
    Message(_("����"),_("����ʱ�䲻��Ϊ��"));
    Button_Back();
    exit;
}
$query="update ATTEND_LEAVE set DESTROY_TIME='$DESTROY_TIME',LEAVE_TYPE='$LEAVE_TYPE',LEAVE_DATE1='$LEAVE_DATE1',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE',LEAVE_TYPE2='$LEAVE_TYPE2' where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("�޸ĳɹ�"));
?>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
