<?
include_once("inc/auth.inc.php");

$show_title = ($send_type == 'tel') ? _("�ֻ�����") : _("�ʼ�");
$HTML_PAGE_TITLE = _("Ⱥ��").$show_title;
include_once("inc/header.inc.php");

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<?
if($send_str != '')
{
    $s_mobile_no_str = "";
    $s_email_str = "";
    
    $query = "select * from ADDRESS where 1=1 and find_in_set(ADD_ID,'$send_str') order by GROUP_ID,PSN_NAME asc";
    $cursor = exequery(TD::conn(),$query);
    while($row = mysql_fetch_array($cursor))
    {
        $MOBIL_NO       = $row["MOBIL_NO"];
        $EMAIL          = $row["EMAIL"];
        
        if($MOBIL_NO!="")
        {
            $s_mobile_no_str .= $MOBIL_NO.",";
        }
        
        if($EMAIL!="")
        {
            $s_email_str .= $EMAIL.",";
        }
    }
}

if($send_type == 'tel')
{
    if($s_mobile_no_str == '')
    {
        Message("",_("��ѡ��ϵ��δ�����ֻ�����"));
?>
        <center>
            <button type="button" class="btn" onClick="window.close();"><?=_("�ر�")?></button>
        </center>
<?
    }
    else
    {
        header("location: /general/mobile_sms/new/index.php?TO_ID1=".$s_mobile_no_str);
    }
}

if($send_type == 'email')
{
    if($s_email_str == '')
    {
        Message("",_("��ѡ��ϵ��δ���õ����ʼ�"));
?>
        <center>
            <button type="button" class="btn" onClick="window.close();"><?=_("�ر�")?></button>
        </center>
<?
    }
    else
    {
        header("location: /general/email/new/?TO_WEBMAIL=".$s_email_str);
    }
}
?>