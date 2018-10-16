<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");

$show_title = ($send_type == 'tel') ? _("微信") : _("邮件");
$HTML_PAGE_TITLE = _("群发").$show_title;
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
		//短信与微信权限
		$query_oa="SELECT * FROM module_priv WHERE UID = '".$row['UID']."' AND MODULE_ID = 0";
		$cursor_oa= exequery(TD::conn(),$query_oa);
		if($arr=mysql_fetch_array($cursor_oa))
		{
			$DEPT_ID = $arr['DEPT_ID'];//部门
			$PRIV_ID = $arr['PRIV_ID'];//角色
			$USER_ID = $arr['USER_ID'];//人员
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
        Message("",_("所选联系人未设置手机号码"));
?>
        <center>
            <button type="button" class="btn" onClick="window.close();"><?=_("关闭")?></button>
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
        Message("",_("所选联系人未设置电子邮件"));
?>
        <center>
            <button type="button" class="btn" onClick="window.close();"><?=_("关闭")?></button>
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