<?
include_once 'inc/auth.inc.php';
include_once 'inc/utility_all.php';
include_once 'inc/utility_org.php';
include_once 'inc/utility_sms1.php';

if(!isset($user_id) || !isset($subject) || !isset($vote_id))
{
    $HTML_PAGE_TITLE = '非法访问';
    include_once("inc/header.inc.php");
    echo '<br/><br/>';
    Message('禁止','非法访问！');
    Button_Close();
    exit;
}
$user_id_login = $_SESSION['LOGIN_USER_ID'];
$user_id = urldecode($user_id);
$user_id_array = explode(',', $user_id);
$user_id_array_num = count($user_id_array);
for($i=0; $i < $user_id_array_num; ++$i)
{
    if('' == $user_id_array[$i])
    {
        continue;
    }
    $func_id_str = GetfunmenuByuserID($user_id_array[$i]);
    if(!find_id($func_id_str, 148))
    {
        $user_id = str_replace($user_id_array[$i], '', $user_id);
    }
}
$sms_type = 11;
$content = "请查看投票！\n标题：".csubstr(urldecode($subject), 0, 100);
$url = "1:vote/show/read_vote.php?VOTE_ID=$vote_id";
send_sms('', $user_id_login, $user_id, $sms_type, $content, $url,$vote_id);
echo 'complete';