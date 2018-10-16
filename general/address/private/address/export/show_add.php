<?
include_once('inc/auth.inc.php');
include_once("inc/utility_file.php");
include_once('inc/name.php');

$TIME=date("Y-m-d H:i:s",time());

$a_share_group = array();
//============================ 个人和共享分组 =======================================
$query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or (SHARE_GROUP_ID!='' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER_ID)) order by GROUP_ID";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $GROUP_ID       = $ROW["GROUP_ID"];
    $GROUP_NAME     = $ROW["GROUP_NAME"];
    
    $a_share_group[$GROUP_ID]["GROUP_NAME"] = $GROUP_NAME;
    
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
    
    $query_add = "select * from address where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER) and (ADD_START='0000-00-00 00:00:00' or ADD_START<='$TIME') and (ADD_END='0000-00-00 00:00:00' or ADD_END>='$TIME'))) and GROUP_ID='$GROUP_ID'";
    $cursor_add = exequery(TD::conn(), $query_add);
    while($add_row = mysql_fetch_array($cursor_add))
    {
        $s_url_pic = "";
        
        $ADD_ID             = $add_row["ADD_ID"];
        $USER_ID            = $add_row["USER_ID"];
        $PSN_NAME           = $add_row["PSN_NAME"];
        $SEX                = $add_row["SEX"];
        $ATTACHMENT_ID      = $add_row["ATTACHMENT_ID"];
        $ATTACHMENT_NAME    = $add_row["ATTACHMENT_NAME"];
        
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
            $a_show_list[$idx]['add_str'] .= '<div class="lxr">
        <p class="zimu">'.$idx.'</p>
        <ul class="namelist">
            <li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;"><input type="checkbox" style="position:absolute;top:0px;left:20px;"/>'.$PSN_NAME.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
        }
        else
        {
            $a_show_list[$idx]['add_str'] .= '<li><a href="#" style="color:#000;padding-top:10px;"><label class="checkbox" style="height:41px;position:relative;"><input type="checkbox" style="position:absolute;top:0px;left:20px;"/>'.$PSN_NAME.'<span><img src="'.$s_url_pic.'" style="width:41px; height: 41px"></span></a></label></li>';
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
    $a_share_group[$GROUP_ID]["add_str"] = $a_add_str;
}
?>