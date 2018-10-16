<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("联系人列表");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/middle.css" />
<script>
    function show_add(add_id)
    {
        parent.document.getElementById("show_add").src="address/show_add.php?ADD_ID="+add_id+"&SHARE_TYPE=<?=$SHARE_TYPE?>&PUBLIC_FLAG=<?=$PUBLIC_FLAG?>";
    }
</script>
<body style="overflow-x: hidden;background:#fafafa" id="show_talklist">
<?
include_once('inc/name.php');

$a_show_list = array();
for($i=ord('A'); $i<=ord('Z'); ++$i)
{
    $name[chr($i)] = array();
    $nidx[chr($i)] = array();

    $a_show_list[chr($i)] = array();
}
$a_show_list["#"] = array();
$name['#']        = array();
$nidx['#']        = array();

$TIME=date("Y-m-d H:i:s",time());

$query_str = '';
if($keyword!="")
{
//	if((ord($keyword)>=65 && ord($keyword)<=90) || (ord($keyword)>=97 && ord($keyword)<=122))
//	{
//		$keyword_A=strtoupper($keyword);
//  	$query_str .= " AND find_in_set(ADD_ID,'$ADD_ARRAY[$keyword_A]')";
//  }
//  else
    if($SHARE_TYPE ==2)
    {
        $str=" and (USER_NAME like '%$keyword%' or MOBIL_NO like '%$keyword%' or TEL_NO_HOME like '%$keyword%' or TEL_NO_DEPT like '%$keyword%' or OICQ_NO like '%$keyword%' or EMAIL like '%$keyword%')";
    }
    else
    {
        $query_str .= " and (PSN_NAME like '%$keyword%' or MOBIL_NO like '%$keyword%' or TEL_NO_HOME like '%$keyword%' or TEL_NO_DEPT like '%$keyword%' or OICQ_NO like '%$keyword%' or EMAIL like '%$keyword%')";
    }
}
$query_str .= " order by PSN_NAME";



//点击我的分组时查询有权限的所有联系人
if($TYPE==1 && $SHARE_TYPE==0)
{
    if($POWER=="")
    {
        $POWER=$arr;
    }
    $sql = "SELECT * FROM address WHERE (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or USER_ID= '') and GROUP_ID in($POWER"."0)  ".$query_str;
}//点击共享时查询有权限的所有联系人
elseif($TYPE==1 && $SHARE_TYPE==1)
{
    if($SHARING=="")
    {
        $SHARING=0;
    }
    $sql = "SELECT * FROM address WHERE (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER) AND (ADD_START='0000-00-00 00:00:00' or ADD_START<='$TIME') AND (ADD_END='0000-00-00 00:00:00' or ADD_END>='$TIME')) ".$query_str;//AND GROUP_ID in ($SHARING)
}
elseif($TYPE==2 && $SHARE_TYPE==2)//查询内部OA所有联系人
{
    $sql = "SELECT UID as ADD_ID,USER_ID,USER_NAME as PSN_NAME,SEX,PHOTO FROM user WHERE 1=1 AND (NOT_LOGIN=0 OR NOT_MOBILE_LOGIN=0) and DEPT_ID!=0 ".$str;
}
elseif($SHARE_TYPE==2)//OA自定义分组人员
{
    $query="SELECT USER_STR FROM user_group WHERE GROUP_ID = '$GROUP_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $USER_STR = $ROW['USER_STR'];
    }
    $sql = "SELECT UID as ADD_ID,USER_ID,USER_NAME as PSN_NAME,SEX,PHOTO FROM user WHERE 1=1 and (NOT_LOGIN=0 OR NOT_MOBILE_LOGIN=0) and DEPT_ID!=0 and find_in_set(USER_ID,'".$USER_STR."')".$str;
}
elseif($SHARE_TYPE==1)
{
    $sql = "SELECT * FROM address WHERE (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER) AND (ADD_START='0000-00-00 00:00:00' or ADD_START<='$TIME') AND (ADD_END='0000-00-00 00:00:00' or ADD_END>='$TIME')) AND GROUP_ID='$GROUP_ID' ".$query_str;
}
elseif($GROUP_ID==0 && $SHARE_TYPE!=1)
{
    $sql = "SELECT * FROM address WHERE (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or USER_ID='' ) AND GROUP_ID='$GROUP_ID' ".$query_str;
}
else
{
    $sql = "SELECT * FROM address WHERE ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SHARE_USER) AND (ADD_START='0000-00-00 00:00:00' or ADD_START<='$TIME') AND (ADD_END='0000-00-00 00:00:00' or ADD_END>='$TIME')) or USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or USER_ID='' ) AND GROUP_ID='$GROUP_ID' ".$query_str;

}
$i_add_count = 0;
$s_show_str  = "";
$result = exequery(TD::conn(), $sql);
while($row = mysql_fetch_array($result))
{
    $s_url_pic = "";
    $s_short_name = "";

    $ADD_ID             = $row["ADD_ID"];
    $USER_ID            = $row["USER_ID"];
    $PSN_NAME           = $row["PSN_NAME"];
    $SEX                = $row["SEX"];
    $ATTACHMENT_ID      = $row["ATTACHMENT_ID"];
    $ATTACHMENT_NAME    = $row["ATTACHMENT_NAME"];


    if($SHARE_TYPE==2)
    {
        $PHOTO = $row['PHOTO'];
        if($PHOTO!="")
        {
            $URL_ARRAY = attach_url_old('photo', $PHOTO);
            $s_url_pic   = $URL_ARRAY['view'];
            $AVATAR_FILE = attach_real_path('photo', $PHOTO);
        }else
        {
            $HRMS_PHOTO = "";
            $query="select PHOTO_NAME,JOB_POSITION  from HR_STAFF_INFO where USER_ID='$USER_ID'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $HRMS_PHOTO=$ROW["PHOTO_NAME"];
            }
            if($HRMS_PHOTO!="")
            {
                $URL_ARRAY = attach_url_old('hrms_pic', $HRMS_PHOTO);
                $s_url_pic   = $URL_ARRAY['view'];
                $AVATAR_FILE = MYOA_ATTACH_PATH."hrms_pic/".$HRMS_PHOTO;
            }
        }
        if($s_url_pic=="")
        {
            $iamge=($SEX==0)?"man_s":"w_s";
            $s_url_pic = MYOA_STATIC_SERVER."/static/modules/address/images/".$iamge.".png";
        }
    }
    else
    {
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
    $s_short_name = (strlen($PSN_NAME) > 10) ? csubstr($PSN_NAME,0,10) : $PSN_NAME;

    if(count($name[$idx]) == 0)
    {
        $a_show_list[$idx]["show_add_id"] = $ADD_ID;
        $a_show_list[$idx]["show_list"] .= '
                <div class="zm">
                    <p id="'.$idx.'" class="zimu" name="'.$idx.'">'.$idx.'</p>
                    <ul class="namelist">
                        <li onclick="show_add('.$ADD_ID.');" title="'.$PSN_NAME.'">
                            <a>'.$s_short_name.'</a>
                            <span style="cursor: pointer;">
                                <img src="'.$s_url_pic.'" height="41px" width="41px">
                            </span>
                        </li>';
    }
    else
    {
        $a_show_list[$idx]["show_list"] .= '<li onclick="show_add('.$ADD_ID.');" title="'.$PSN_NAME.'"><a>'.$s_short_name.'</a><span style="cursor: pointer;"><img src="'.$s_url_pic.'" height="41px" width="41px"></span></li>';
    }

    if(!in_array($FirstName, $nidx[$idx]))
    {
        array_push($nidx[$idx], $FirstName);
    }

    array_push($name[$idx], $row);
}

if($i_add_count==1)
{
    $ADD_ID_ST = $ADD_ID;
}

foreach($a_show_list as $key => $str)
{
    if($key1 != "" || $key2 != "")
    {
        if($key == $key1 || $key == $key2)
        {
            if($str["show_list"] != "")
            {
                $i_add_count++;
                if($i_add_count == '1')
                {
                    $ADD_ID_ST = $str["show_add_id"];
                }
                $s_show_str .= $str["show_list"]. '</ul></div>';
            }
        }
    }
    else
    {
        if($str["show_list"] != "")
        {
            $i_add_count++;
            if($i_add_count == '1')
            {
                $ADD_ID_ST = $str["show_add_id"];
            }
            $s_show_str .= $str["show_list"]. '</ul></div>';
        }
    }
}

if($_GET['GETUPID'])
{
    $ADD_ID_ST=$_GET['GETUPID'];
}
echo $s_show_str;
?>
<script>
    parent.document.getElementById("show_add").src="address/show_add.php?ADD_ID=<?=$ADD_ID_ST?>&SHARE_TYPE=<?=$SHARE_TYPE?>&PUBLIC_FLAG=<?=$PUBLIC_FLAG?>";
</script>
</body>