<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");

$show_title = ($send_type == 'tel') ? _("΢��") : _("�ʼ�");
$HTML_PAGE_TITLE = _("Ⱥ��").$show_title;
include_once("inc/header.inc.php");

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<?
if($send_str != '')
{
    $s_mobile_no_str = "";
    $s_email_str     = "";
	$uid       = "";
	$user_id   = "";
	$user_name = "";
    
    $query = "select * from user where 1=1 and find_in_set(UID,'$send_str')";
    $cursor = exequery(TD::conn(),$query);
    while($row = mysql_fetch_array($cursor))
    {
		//������΢��Ȩ��
		$query_oa="SELECT * FROM module_priv WHERE UID = '".$row['UID']."' AND MODULE_ID = 0";
		$cursor_oa= exequery(TD::conn(),$query_oa);
		if($arr=mysql_fetch_array($cursor_oa))
		{
			$DEPT_ID = $arr['DEPT_ID'];//����
			$PRIV_ID = $arr['PRIV_ID'];//��ɫ
			$USER_ID = $arr['USER_ID'];//��Ա
			$PRIV    = $DEPT_ID."|".$PRIV_ID."|".$USER_ID;
			if(!check_priv($PRIV))
			{
				continue;
			}
		}
        $MOBIL_NO       = $row["MOBIL_NO"];
        $EMAIL          = $row["EMAIL"];
		$UID            = $row["UID"];
		$USER_ID        = $row["USER_ID"];
		$USER_NAME      = $row["USER_NAME"];
		
		
		$uid       .= $UID.",";
		$user_name .= $USER_NAME.",";
		$user_id   .= $USER_ID.",";
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
    if($uid == '')
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
		header("location: /general/status_bar/sms_back.php?TO_UID=".$uid."&TO_NAME=".$user_name);
        //header("location: /general/mobile_sms/new/index.php?TO_ID1=".$s_mobile_no_str);
    }
}

if($send_type == 'email')
{
    if($user_id == '')
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
		header("location: /general/email/new/?TO_ID=".$user_id);
        //header("location: /general/email/new/?TO_WEBMAIL=".$s_email_str);
    }
}
?>