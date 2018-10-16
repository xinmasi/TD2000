<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//lp 2014/7/10 4:27:02 退出的时候增加只退出当前的session
$query="delete from USER_ONLINE where SID = '".session_id()."'";
exequery(TD::conn(), $query);

add_log(22,"",$_SESSION["LOGIN_USER_ID"]);
clear_online_status();

session_start();
session_unset();
session_destroy();

$params = session_get_cookie_params();
setcookie(session_name(), session_id(), 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

//与UCenter集成
$SYS_PARA_ARRAY = get_sys_para('USE_DISCUZ');
$USE_DISCUZ = $SYS_PARA_ARRAY["USE_DISCUZ"];

if($USE_DISCUZ > 0)
{
    include_once("inc/uc_client/config.inc.php"); 
        
    if(defined("UC_APPID"))
    { 
        include_once("inc/uc_client/client.php"); 
        switch($USE_DISCUZ)
        {
            case 1:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_NAME"];
                break;
            case 2:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_ID"];
                break; 
            case 3:
                $UC_USER_NAME = $_SESSION["LOGIN_BYNAME"];
                break; 
            default:
                $UC_USER_NAME = $_SESSION["LOGIN_USER_NAME"]; 
        }
        
        if($uc_data = uc_get_user($UC_USER_NAME)) 
        {
            $uc_synclogout_script = uc_user_synlogout($uc_data[0]);
        }
    }
}

$HTML_PAGE_TITLE = _("正在注销系统...");
$HTML_PAGE_BASE_STYLE = 0;
include_once("inc/header.inc.php");
?>



<?=$uc_synclogout_script?>
<script>
location="../"
</script>


</html>
