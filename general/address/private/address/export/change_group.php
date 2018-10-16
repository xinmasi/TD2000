<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_email.php");
include_once('inc/name.php');
ob_end_clean();

$TIME=date("Y-m-d H:i:s",time());

$s_my_str = "";//个人分组显示串
$s_share_str = "";//共享分组显示串
$i_my_count = 0;//个人分组个数
$i_share_count = 0;//共享分组个数
$s_check = "";//是否已经选中

$a_show_list = array();
for($i=ord('A'); $i<=ord('Z'); ++$i)
{
    $name[chr($i)] = array();
    $nidx[chr($i)] = array();
    
    $a_show_list[chr($i)] = array();
}
$a_show_list["#"] = array();
$name['#']=array();
$nidx['#']=array();
$s_where_str = '';
if($share_type == '1')
{
    $s_where_str .= " and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER) and (ADD_START='0000-00-00 00:00:00' or ADD_START<='$TIME') and (ADD_END='0000-00-00 00:00:00' or ADD_END>='$TIME'))";
}
else
{
    $s_where_str .= " and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
}

$query_add = "select * from address where GROUP_ID='$group_id' ".$s_where_str;
$cursor_add = exequery(TD::conn(), $query_add);
while($add_row = mysql_fetch_array($cursor_add))
{
    $s_url_pic = "";
    $s_short_name = "";
    
    $ADD_ID             = $add_row["ADD_ID"];
    $USER_ID            = $add_row["USER_ID"];
    $PSN_NAME           = $add_row["PSN_NAME"];
    $SEX                = $add_row["SEX"];
    $ATTACHMENT_ID      = $add_row["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $add_row["ATTACHMENT_NAME"];
    
    $s_short_name = (strlen($PSN_NAME) > 8) ? csubstr($PSN_NAME,0,8).".." : $PSN_NAME;
    
    if(find_id($show_add_str,$ADD_ID))
    {
        $s_check = 'checked';
    }
    else
    {
        $s_check = '';
    }
    if($ATTACHMENT_NAME=="" && $SEX==0)
    {
        $s_url_pic = MYOA_JS_SERVER."/static/modules/address/images/man_s.png";
    }
    else if($ATTACHMENT_NAME=="" && $SEX==1)
    {
        $s_url_pic = MYOA_JS_SERVER."/static/modules/address/images/w_s.png";
    }
    else
    {
        $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
        $s_url_pic = $URL_ARRAY["view"];
    }
    
    $s = $PSN_NAME;
    $idx = '#';
    if(ord($s[0]) >= 128)
    {
        $FirstName=substr($s, 0, MYOA_MB_CHAR_LEN);
        foreach($mb as $key => $s)
        {
            if(strpos($s, $FirstName))
            {
                $idx=strtoupper($key);
                break;
            }
        }
    }
    else
    {
        $FirstName=strtoupper($s[0]);
        if($FirstName>='A' && $FirstName<='Z')
        {
            $idx=$FirstName;
        }
        else
        {
            $idx='#';
        }
    }
    
    if(count($name[$idx]) == 0)
    {
        $a_show_list[$idx]['add_str'] .= '<div class="lxr" style="height: auto;"><p class="zimu">'.$idx.'</p><ul class="namelist"><li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;" title="'.$PSN_NAME.'"><input type="checkbox" style="position:absolute;top:0px;left:20px;" value="'.$ADD_ID.'" onclick="check_one(this.value)" '.$s_check.'/>'.$s_short_name.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
    }
    else
    {
        $a_show_list[$idx]['add_str'] .= '<li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;" title="'.$PSN_NAME.'"><input type="checkbox" style="position:absolute;top:0px;left:20px;" value="'.$ADD_ID.'" onclick="check_one(this.value)" '.$s_check.'/>'.$s_short_name.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
    }
    
    if(!in_array($FirstName, $nidx[$idx]))
    {
        array_push($nidx[$idx], $FirstName);
    }
    
    array_push($name[$idx], $row);
}

$a_add_str = "";
foreach($a_show_list as $key => $str)
{
    if($str['add_str'] != "")
    {
        $a_add_str .= $str['add_str']. '</ul></div>';
    }
}

echo $a_add_str;
?>

