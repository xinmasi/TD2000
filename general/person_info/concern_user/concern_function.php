<?
include_once("inc/session.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("./concern_config.php");
require_once('general/sns/classes/TFeedLoader.php');

session_start();
ob_start();

$dataBack  = array();
$dataBacks = array();
$PER_PAGE  = 10;
$num_count = 0;
$thisPage  = 0;
$curPage   = ($curPage-1)*$pageLimit;
$user_id = $uid ? td_trim(GetUserIdByUid($uid)) : $user_id;
$user_id = iconv('utf-8', MYOA_CHARSET, $user_id);
//默认
if($_GET['load']=='list')
{
    $where = "";
    $concern_arr = array();
    if($group_id || $group_id=='0')
    {
        $where = " and FIND_IN_SET('$group_id',group_id) ";
    }
    else
    {
        $where .=" and ((group_id = 0 and concern_user = '".$_SESSION["LOGIN_USER_ID"]."')";
        $count = 1;
        $sql="SELECT GROUP_ID FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor= exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($cursor))
        {
            $group_id = $row['GROUP_ID'];
            $where   .=" or FIND_IN_SET('$group_id',group_id)";
        }
        $where .=") ";
    }
    
    $count = 0;
    $query = "SELECT count(*) FROM concern_user,user WHERE user.USER_ID=concern_user.USER_ID and concern_user='".$_SESSION["LOGIN_USER_ID"]."'".$where;
    $cursor= exequery(TD::conn(),$query);
    if($row=mysql_fetch_array($cursor))
    {
        $count = $row[0];
    }
    $pageLimit  = $pageLimit == 0 ? 1 : $pageLimit;
    $thisPage   = ($curPage/$pageLimit) +1;
    $totalpage  = ceil($count/$pageLimit);
    
    
    $num_count = 0;
    $query = "SELECT concern_id,group_id,concern_content,UID,user.USER_ID,USER_NAME,SEX,MY_STATUS,PHOTO,USER_PRIV,DEPT_ID,NOT_LOGIN,NOT_MOBILE_LOGIN FROM concern_user,user WHERE user.USER_ID=concern_user.USER_ID and concern_user='".$_SESSION["LOGIN_USER_ID"]."' ".$where." limit ".$curPage.",".$pageLimit;
    $cursor= exequery(TD::conn(),$query);
    if($curPage>0 && !mysql_affected_rows()>0)
    {
        $curPage1 = $curPage-10;
        $thisPage = $thisPage-1;
        
        $query  = "SELECT concern_id,group_id,concern_content,UID,user.USER_ID,USER_NAME,SEX,MY_STATUS,PHOTO,USER_PRIV,DEPT_ID,NOT_LOGIN,NOT_MOBILE_LOGIN FROM concern_user,user WHERE user.USER_ID=concern_user.USER_ID and concern_user='".$_SESSION["LOGIN_USER_ID"]."' ".$where." limit ".$curPage1.",".$pageLimit;
        $cursor = exequery(TD::conn(),$query);
    }
    
    while($row=mysql_fetch_array($cursor))
    {
        $concern_id       = $row['concern_id'];
        $group_id         = $row['group_id'];
        $concern_content  = $row['concern_content'];
        $user_id_str      = (find_id($user_id_str, $row['user_id']) ?  $user_id_str : $user_id_str.$row['user_id'].",");
        $uid              = $row['UID'];
        $user_name        = $row['USER_NAME'];
        $user_id          = $row['USER_ID'];
        $my_status        = $row['MY_STATUS'];
        $user_priv_name   = td_trim(($row['USER_PRIV'] ? GetPrivNameById($row['USER_PRIV']) : ""));
        $dept_name        = td_trim(($row['DEPT_ID'] ? GetDeptNameById($row['DEPT_ID']) : _("离职人员/外部人员")));
        $path             = get_head($row['PHOTO'], $row['SEX'], $user_id);
        $NOT_LOGIN        = $row['NOT_LOGIN'];
        $NOT_MOBILE_LOGIN = $row['NOT_MOBILE_LOGIN'];

        $con_array = "";
        if($concern_content != "")
        {
            $con_array = explode(",",$concern_content);
            foreach($con_array as $i=>$k)
            {
                if($k)
                {
                    $con_array[$i] = $CLEAR_ARRAY[$k];
                }
            }
        }
        
        $pc_login_priv = 0;
        $mobile_login_priv = 0;
        
        if($NOT_LOGIN!='0')
        {
            $pc_login_priv = 1;
        }
        if($NOT_MOBILE_LOGIN!='0')
        {
            $mobile_login_priv = 1;
        }
        
        $concern_arr[]=array(
            'concern_id'      => $concern_id,
            'user_id'         => td_iconv($user_id, MYOA_CHARSET, 'utf-8'),
            'group_id'        => $group_id,
            'group_name'      => td_iconv(get_groupname($group_id), MYOA_CHARSET, 'utf-8'),
            'concern_content' => td_iconv($con_array, MYOA_CHARSET, 'utf-8'),
            'concern_key'     => td_iconv($concern_content, MYOA_CHARSET, 'utf-8'),
            'user_name'       => td_iconv($user_name, MYOA_CHARSET, 'utf-8'),
            'my_status'       => td_iconv($my_status, MYOA_CHARSET, 'utf-8'),
            'user_priv'       => td_iconv($user_priv_name, MYOA_CHARSET, 'utf-8'),
            'dept_name'       => td_iconv($dept_name, MYOA_CHARSET, 'utf-8'),
            'pc_login_priv'   => $pc_login_priv,
            'mobile_login_priv' => $mobile_login_priv,
            'path'            => $path,
        );
        $num_count++;
    }
    
    //echo "<pre>";print_r($concern_arr);exit;
    $dataBacks=array("curpage" => $thisPage,"totalpage" => $totalpage,"numcount"=>$num_count,"datalist" => $concern_arr);
    echo json_encode($dataBacks);
    exit;
}

//分组
if($_GET['load']=='group')
{
    $group_arr = array();
    $query = "SELECT * from user_group WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
         $GROUP_ID   = $ROW["GROUP_ID"];
         $GROUP_NAME = $ROW["GROUP_NAME"];
         $ORDER_NO   = $ROW["ORDER_NO"];
         
         $group_arr[]=array(
             'group_id'   => $GROUP_ID,
            'group_name' => td_iconv($GROUP_NAME, MYOA_CHARSET, 'utf-8'),
            'order_no'   => $ORDER_NO    
         );     
    }
    echo json_encode($group_arr);
    exit;
}

//获取关注数
if($_GET['load']=='count_concern')
{
    $num_count = 0;
    $where = "";
    if($group_id || $group_id=='0')
    {
        $where = " and FIND_IN_SET('$group_id',group_id) ";
    }
    else
    {
        $where .=" and ((group_id = 0 and concern_user = '".$_SESSION["LOGIN_USER_ID"]."')";
        $count = 1;
        $sql="SELECT GROUP_ID FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor= exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($cursor))
        {
            $group_id = $row['GROUP_ID'];
            $where   .= " or FIND_IN_SET('$group_id',group_id) ";
        }
        $where .=") ";
    }
    $num_count = 0;
    $query = "SELECT count(*) FROM concern_user,user WHERE user.USER_ID=concern_user.USER_ID and concern_user='".$_SESSION["LOGIN_USER_ID"]."'".$where;
    $cursor= exequery(TD::conn(),$query);
    if($row=mysql_fetch_array($cursor))
    {
        $num_count = $row[0];
    }
    echo $num_count;
    exit;
}
//编辑权限
if($_GET['load']=='edit_priv')
{
    $userNode = new TUserNode($_SESSION['LOGIN_UID']);
    $uid = UserId2Uid($user_id);
    if(strstr($concern_content,"COMMUNITY"))
    {
        $userNode->follow($uid);
    }else
    {
        //取消
        $userNode->unfollow($uid);
    }
    
    $query = "UPDATE concern_user SET concern_content = '$concern_content' WHERE user_id = '$user_id' AND concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor = exequery(TD::conn(),$query);
    if($cursor)
    {
        echo "ok";
    }
    exit;
    
}
//设置权限
if($_GET['load']=='get_priv')
{
    echo json_encode(td_iconv($CLEAR_ARRAY, MYOA_CHARSET, 'utf-8'));
    exit;
}

//新建获取组成员
if($_GET['load']=='get_user')
{
    $query = "SELECT * FROM user_group WHERE group_id='$group_id'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TO_ID = $ROW["USER_STR"];
    }
    $query1 = "SELECT USER_ID,USER_NAME FROM user where find_in_set(USER_ID,'$TO_ID')";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
        $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];
    }
    $TOK=strtok($TO_ID,",");
    while($TOK!="")
    {
        if($TO_ARRAY[$TOK]["USER_NAME"]=="")
        {
            $TOK=strtok(",");
            continue;
        }
        $TO_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
        $TOK=strtok(",");
    }
    $user_arr[]=array(
        'user_id'      => $TO_ID,
        'user_name'    => td_iconv($TO_NAME, MYOA_CHARSET, 'utf-8'),
    );
    echo json_encode($user_arr);
    exit;
}
if($_GET['load']=='new_user')
{
    $user_id_all = "";
    $sql1 = "SELECT user_id FROM concern_user WHERE concern_user = '".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor1= exequery(TD::conn(),$sql1);
    while($arr=mysql_fetch_array($cursor1))
    {
        $user_id_all .= $arr['user_id'].",";
    }
    $sql    = "SELECT USER_STR FROM user_group WHERE GROUP_ID='$group_id' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor = exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($cursor))
    {
        $USER_STR = $row['USER_STR'];
    }
    $to_id    = check_id($_SESSION["LOGIN_USER_ID"], $to_id, false);
    $group = $group_id.",";
    
    $added_uid = check_id($USER_STR,$to_id,false);
    
    if(substr($added_uid,-1)==",")
    {
        $added_uid = substr($added_uid,0,-1);
    }
    if($added_uid!="")
    {
        if($added_uid1 = check_id($user_id_all,$added_uid,true))
        {
            $added_uid1 = substr($added_uid1,0,-1);
            $added_uid1_array = explode(",",$added_uid1);
            for($i=0;$i<count($added_uid1_array);$i++)
            {
                $query = "UPDATE concern_user SET group_id= CONCAT(group_id,'$group') WHERE user_id = '$added_uid1_array[$i]' AND concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
                exequery(TD::conn(),$query);
            }    
        }
        if($added_uid2 = check_id($user_id_all,$added_uid,false))
        {
            $added_uid2 = substr($added_uid2,0,-1);
            $added_uid2_array = explode(",",$added_uid2);
            for($i=0;$i<count($added_uid2_array);$i++)
            {
                $query = "INSERT INTO concern_user (user_id,group_id,concern_user,concern_content)VALUES('$added_uid2_array[$i]','$group','".$_SESSION["LOGIN_USER_ID"]."','')";
                exequery(TD::conn(),$query);
                
                
                //判断是否开启关注提醒
                $SYS_PARA_ARRAY  = get_sys_para("SMS_REMIND");
                $PARA_VALUE      = $SYS_PARA_ARRAY["SMS_REMIND"];
                $PARA_VALUE_ARRAY = explode("|",$PARA_VALUE);
                if(find_id($PARA_VALUE_ARRAY[2],"86")){
                    //发送事务提醒
                    $SMS_CONTENT  = $_SESSION["LOGIN_USER_NAME"]._("关注了您");
                    send_sms("",$_SESSION["LOGIN_USER_ID"],$added_uid2_array[$i],86,$SMS_CONTENT,""); 
                }  
            }
        }    
    }    
    //对比删除的人员
    $out_add = check_id($to_id,$USER_STR,false);    
    if(substr($out_add,-1)==",")
    {
        $out_add = substr($out_add,0,-1);
    }
    if($out_add!="")
    {
        $out_array = explode(",",$out_add);
        for($i=0;$i<count($out_array);$i++)
        {
            $query = "UPDATE concern_user set group_id = REPLACE(group_id,'$group','') WHERE user_id = '".$out_array[$i]."' AND concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
            exequery(TD::conn(),$query);
        }
    }
    
    $query="UPDATE user_group SET USER_STR='$to_id' WHERE GROUP_ID='$group_id' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    exequery(TD::conn(), $query);
    
    updateUserCache($_SESSION["LOGIN_UID"]);
    exit;
    
}
//管理分组
if($_GET['load']=='select_group')
{
    $userid = $user_id.",";
    $sql="SELECT group_id FROM concern_user WHERE user_id = '$user_id' and concern_user='".$_SESSION['LOGIN_USER_ID']."'";
    $cursor= exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($cursor))
    {
        $groupid = $row['group_id'];
    }
    
    if($groupid != $group_id_str)
    {
        //对比添加的分组
        $added_uid = check_id($groupid,$group_id_str,false);
        if(substr($added_uid,-1)==",")
        {
            $added_uid = substr($added_uid,0,-1);
        }
        $added_uid_array = explode(",",$added_uid);
        for($i=0;$i<count($added_uid_array);$i++)
        {
            $query = "UPDATE user_group SET USER_STR= CONCAT(USER_STR,'$userid') WHERE GROUP_ID = '".$added_uid_array[$i]."'";
            exequery(TD::conn(),$query);
        }
        //对比删除的分组
        $out_uid = check_id($group_id_str,$groupid,false);
        if(substr($out_uid,-1)==",")
        {
            $out_uid = substr($out_uid,0,-1);
        }
        $out_uid_array = explode(",",$out_uid);
        for($i=0;$i<count($out_uid_array);$i++)
        {
            $query = "UPDATE user_group set USER_STR = REPLACE(USER_STR,'$userid','') WHERE GROUP_ID = '".$out_uid_array[$i]."'";
            exequery(TD::conn(),$query);
        }
        $query="UPDATE concern_user SET group_id='$group_id_str' WHERE user_id = '$user_id' and concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
        exequery(TD::conn(), $query);
    } 
    echo "ok";
    exit;  
}
//批量管理分组
if($_GET['load']=='batch_group')
{
    $str_array = array();
    $sel_user  = "SELECT GROUP_ID,USER_STR FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
    $cur = exequery(TD::conn(),$sel_user);
    while($arr1=mysql_fetch_array($cur))
    {
        $str_array[$arr1['GROUP_ID']] = $arr1['USER_STR'];
    }
    //中文解码
    $userid_str = $user_id_str = iconv('UTF-8','gbk',urldecode($_GET['user_id_str']));

    if(substr($user_id_str,-1)==",")
    {
        $user_id_str = substr($user_id_str,0,-1);
    }
    $user_array = explode(",",$user_id_str);    
    for($i=0;$i<count($user_array);$i++)
    {
        $query1 = "UPDATE concern_user SET group_id = '$group_id_str' WHERE user_id = '".$user_array[$i]."' AND concern_user = '".$_SESSION["LOGIN_USER_ID"]."'";
        exequery(TD::conn(),$query1);
        $sql = "SELECT GROUP_ID FROM user_group WHERE FIND_IN_SET('".$user_array[$i]."',USER_STR) AND USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor= exequery(TD::conn(),$sql);
        while($row=mysql_fetch_array($cursor))
        {
            $GROUP_ID .= $row['GROUP_ID'].",";
        }
        //对比添加的分组
        $added_uid = check_id($GROUP_ID,$group_id_str,false);
        if(substr($added_uid,-1)==",")
        {
            $added_uid = substr($added_uid,0,-1);
        }
        $added_uid_array = explode(",",$added_uid);
        for($j=0;$j<count($added_uid_array);$j++)
        {
            $user_str_add = check_id($str_array[$added_uid_array[$j]],$userid_str,false);
            if($user_str_add )
            {
                $query2 = "UPDATE user_group SET USER_STR= CONCAT(USER_STR,'$user_str_add') WHERE GROUP_ID = '".$added_uid_array[$j]."'";
                exequery(TD::conn(),$query2);
            }    
            
        }
        //对比删除的分组
        $out_uid = check_id($group_id_str,$GROUP_ID,false);
        if(substr($out_uid,-1)==",")
        {
            $out_uid = substr($out_uid,0,-1);
        }
        $out_uid_array = explode(",",$out_uid);
        for($k=0;$k<count($out_uid_array);$k++)
        {
            $user_str_out = check_id($userid_str,$str_array[$out_uid_array[$k]],true);
            if($user_str_out)
            {
                $query3 = "UPDATE user_group set USER_STR = REPLACE(USER_STR,'$user_str_out','') WHERE GROUP_ID = '".$out_uid_array[$k]."'";
                exequery(TD::conn(),$query3);
            }
        }
    }
    echo "ok";
    exit;
}

//批量设置权限
if($_GET['load']=='batch_priv')
{
    if(substr($user_str,-1)==",")
    {
        $user_str = substr($user_str,0,-1);
    }
    
    $userNode = new TUserNode($_SESSION['LOGIN_UID']);
    
    $user_array = explode(",",$user_str);
    for($i=0;$i<count($user_array);$i++)
    {
        $uid = UserId2Uid($user_array[$i]);
        if(strstr($concern_str,"COMMUNITY"))
        {
            $userNode->follow($uid);
        }else
        {
            //取消
            $userNode->unfollow($uid);
        }
        $query = "UPDATE concern_user SET concern_content = '$concern_str' WHERE user_id = '".$user_array[$i]."' AND concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor = exequery(TD::conn(),$query);
    }
    echo "ok";        
    exit;
}
//批量取消关注
if($_GET['load']=='batch_cancel')
{
    $userNode = new TUserNode($_SESSION['LOGIN_UID']);  
    if($group_id)
    {
        $group = $group_id.",";
        
    }
    if(substr($user_str,-1)==",")
    {
        $user_str = substr($user_str,0,-1);
    }
    $user_array = explode(",",$user_str);
    for($i=0;$i<count($user_array);$i++)
    {
        if($group_id)
        {
            $sql = "UPDATE concern_user SET group_id = REPLACE(group_id,'$group','') WHERE  concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='".$user_array[$i]."'";
            
            $query = "UPDATE user_group SET USER_STR = REPLACE(USER_STR,'$_GET[user_str]','') WHERE  USER_ID='".$_SESSION["LOGIN_USER_ID"]."' AND GROUP_ID = '$group_id'";
            exequery(TD::conn(),$query);
            
            $sql_user = "SELECT group_id FROM concern_user WHERE concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='{$user_array[$i]}'";
            $cursor3  = exequery(TD::conn(),$sql_user);
            $ROW3     = mysql_fetch_array($cursor3);
            if(td_trim($ROW3[0])=="")
            {
                $sql_del = "DELETE FROM concern_user WHERE concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='{$user_array[$i]}'";
                exequery(TD::conn(),$sql_del);
            }  
        }
        else
        {
            $query = "UPDATE user_group SET USER_STR = REPLACE(USER_STR,'$_GET[user_str]','') WHERE  USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
            exequery(TD::conn(),$query);
            $sql = "DELETE FROM concern_user WHERE concern_user = '".$_SESSION["LOGIN_USER_ID"]."' AND user_id = '".$user_array[$i]."'";
        }
        exequery(TD::conn(),$sql); 
        
        $uid = UserId2Uid($user_array[$i]);
        $userNode->unfollow($uid);           
    }
    echo "ok";
    exit;
}

//私人自定义分组
if($_GET['load']=='detail')
{
    $concern_arr = array();
    $children    = array();
    $sql="SELECT GROUP_ID,GROUP_NAME FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor= exequery(TD::conn(),$sql);
    while($row=mysql_fetch_array($cursor))
    {
        $group_id   = $row['GROUP_ID'];
        $group_name = $row['GROUP_NAME'];
        
        $query1 = "SELECT * FROM concern_user WHERE  FIND_IN_SET('$group_id',group_id) AND concern_user = '".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor1= exequery(TD::conn(),$query1);
        $i=0;
        while($ress=mysql_fetch_array($cursor1))
        {
            $query_user = "SELECT UID,USER_NAME,SEX,MY_STATUS,PHOTO,USER_PRIV_NAME,DEPT_NAME FROM user,department WHERE department.DEPT_ID = user.DEPT_ID AND user.USER_ID = '".$ress['user_id']."'";
            $cursor2 = exequery(TD::conn(),$query_user);
            if($arr = mysql_fetch_array($cursor2))
            {
                $children[$i]['uid']       = $arr['UID'];
                $children[$i]['user_name'] = td_iconv($arr['USER_NAME'], MYOA_CHARSET, 'utf-8');
                $children[$i]['my_status'] = td_iconv($arr['MY_STATUS'], MYOA_CHARSET, 'utf-8');
                $children[$i]['user_priv'] = td_iconv($arr['USER_PRIV_NAME'], MYOA_CHARSET, 'utf-8');
                $children[$i]['dept_name'] = td_iconv($arr['DEPT_NAME'], MYOA_CHARSET, 'utf-8');
                $children[$i]['path']      = get_head($arr['PHOTO'],$arr['SEX'],$arr['USER_ID']);
                //短信与微讯权限
                $oa_priv = 1;
                $query_oa="SELECT * FROM module_priv WHERE UID = '".$arr['UID']."' AND MODULE_ID = 0";
                $cursor_oa= exequery(TD::conn(),$query_oa);
                if($arr=mysql_fetch_array($cursor_oa))
                {
                    $DEPT_ID = $arr['DEPT_ID'];//部门
                    $PRIV_ID = $arr['PRIV_ID'];//角色
                    $USER_ID = $arr['USER_ID'];//人员
                    $PRIV    = $DEPT_ID."|".$PRIV_ID."|".$USER_ID;
                    if(check_priv($PRIV))
                    {
                        $oa_priv = 0;
                    }
                }
                else
                {
                    $oa_priv = 0;
                }
                $children[$i]['oa_priv']   = $oa_priv;
            }
            $i++;
        }    
        $concern_arr[]=array(
            'group_id'     => $group_id,
            'group_name'   => td_iconv($group_name, MYOA_CHARSET, 'utf-8'),
            'item'         => $children,    
        );
    }
    echo json_encode($concern_arr);
    exit;
}
//新建分组
if($_GET['load']=='add_group')
{
    $groupname   = td_iconv($groupname, "utf-8", MYOA_CHARSET);
    
    $sql = "SELECT * FROM user_group WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
    $arr = exequery(TD::conn(),$sql);
    $group_count = 1;
    while($ROW = mysql_fetch_array($arr))
    {
        if($ROW['GROUP_NAME'] == $groupname)
        {
            echo "same";
            exit;
        }
        if($group_count>=20)
        {
            echo "count";
            exit;
        }
        $group_count++;
    }
    $query = "INSERT INTO user_group (GROUP_NAME, USER_ID, ORDER_NO) VALUES ('$groupname', '".$_SESSION["LOGIN_USER_ID"]."','99')";
    exequery(TD::conn(),$query);
    echo "ok";
    exit;
}
//编辑分组
if($_GET['load']=='edit_group')
{
    $groupname   = td_iconv($groupname, "utf-8", MYOA_CHARSET);
    
    $sql = "SELECT * FROM user_group WHERE GROUP_NAME = '$groupname' AND USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' AND GROUP_ID!='$groupid'";
    $arr = exequery(TD::conn(),$sql);
    if(mysql_affected_rows()>0)
    {
        echo "same";
        exit;
    }
    
    $query="UPDATE user_group SET GROUP_NAME='$groupname' where GROUP_ID = '$groupid'";
    exequery(TD::conn(),$query);
    echo "ok";
    exit;
}
//删除分组
if($_GET['load']=='del_group')
{
    $group = $group_id.",";
    $query = "UPDATE concern_user set group_id = REPLACE(group_id,'$group','') WHERE  concern_user='".$_SESSION["LOGIN_USER_ID"]."'";
    exequery(TD::conn(),$query);
 
    $sql_user = "SELECT concern_id FROM concern_user WHERE concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND group_id =''";
    $cursor3  = exequery(TD::conn(),$sql_user);
    while($arr = mysql_fetch_array($cursor3))
    {
        $sql_del = "UPDATE concern_user SET group_id = '0' WHERE concern_id='{$arr[0]}'";
        exequery(TD::conn(),$sql_del);
    }
    
    $sql="DELETE FROM user_group WHERE GROUP_ID = '$group_id'";
    exequery(TD::conn(),$sql);
    exit;
}

//移动分组
if($_GET['load']=='updatesort')
{
    if(substr($group_id_str,-1)==",")
    {
        $group_id_str = substr($group_id_str,0,-1);
    }
    $group_array = explode(",",$group_id_str);
    for($i=0;$i<count($group_array);$i++)
    {
        $sql="UPDATE user_group SET ORDER_NO = '$i' WHERE GROUP_ID = '".$group_array[$i]."' ";
        exequery(TD::conn(),$sql);
    }
    exit;
}
//获取所有所关注的user_id和user_name
if($_GET['load']=='getalluser')
{
    $user_id_str = "";
    $user_name_str = "";
    $user_arr = array();
    
    $where = "";
    if($group_id!="" && $group_id!= 0)
    {
        $where = " and (group_id='$group_id') OR FIND_IN_SET('$group_id',group_id)";
    }elseif($group_id == 0 && $group_id!="")
    {
        $where = " and group_id='$group_id'";
    }
    
    $query = "SELECT user_id FROM concern_user WHERE concern_user = '".$_SESSION["LOGIN_USER_ID"]."'".$where;
    $cursor  = exequery(TD::conn(),$query);
    while($arr = mysql_fetch_array($cursor))
    {
        $user_id_str.= $arr[0].",";
    }
    $user_name_str = GetUserNameById($user_id_str);
    
    $user_arr[]=array(
            'useridstr'     => td_iconv($user_id_str, MYOA_CHARSET, 'utf-8'),
            'usernamestr'   => td_iconv($user_name_str, MYOA_CHARSET, 'utf-8'),
        );
  
    echo json_encode($user_arr);
    exit;
}



//返回头像地址
function get_head($PHOTO,$SEX,$USER_ID)
{
    if($PHOTO!="")
    {
        $URL_ARRAY   = attach_url_old('photo', $PHOTO);
        $AVATAR_PATH = $URL_ARRAY['view'];
        $AVATAR_FILE = attach_real_path('photo', $PHOTO);
   }else
   {
       $HRMS_PHOTO = "";
       $query  = "SELECT PHOTO_NAME,JOB_POSITION  FROM hr_staff_info WHERE USER_ID = '$USER_ID'";
       $cursor = exequery(TD::conn(),$query);
       if($ROW = mysql_fetch_array($cursor))
       {
           $HRMS_PHOTO = $ROW["PHOTO_NAME"];
        }
        if($HRMS_PHOTO!="")
        {
            $URL_ARRAY   = attach_url_old('hrms_pic', $HRMS_PHOTO);
            $AVATAR_PATH = $URL_ARRAY['view'];
            $AVATAR_FILE = MYOA_ATTACH_PATH."hrms_pic/".$HRMS_PHOTO;
        }
    }
    if(!file_exists($AVATAR_FILE))
    {
        $AVATAR_PATH = MYOA_STATIC_SERVER."/static/images/avatar/".$SEX.".png";
        $AVATAR_FILE = MYOA_ROOT_PATH."static/images/avatar/".$SEX.".png";
    }
    
    return $AVATAR_PATH;
}

//返回组名
function get_groupname($group_id)
{
    if($group_id=='0')
    {
        $group_name = "";
    }
    else
    {
        if(substr($group_id,-1)==",")
        {
            $group_id = substr($group_id,0,-1);
        }
        $newstr = explode(",",$group_id);
        $conn   = count($newstr);
        for($i=0;$i<$conn;$i++)
        {
            $query_user="SELECT GROUP_NAME FROM user_group WHERE GROUP_ID = '".$newstr[$i]."'";
            $cursor_user= exequery(TD::conn(),$query_user);
            if($ROW = mysql_fetch_array($cursor_user))
            {
                $group_name .= $ROW['GROUP_NAME'].",";
            }
        }
        $group_name = substr($group_name,0,-1);
    }
    
    return $group_name;
}

//添加关注
if($_GET['load']=='concern')
{
    $concern = concern($load,$user_id,$group_id,$concern_content);
    echo $concern;
    exit;
}
//取消关注
if($_GET['load']=='cancel_concern')
{
    $concern = concern($load,$user_id,$group_id,'');
    echo $concern;
    exit;
}

$concern = concern($load,$user_id,$group_id,$concern_content);

function concern($load,$user_id,$group_id,$concern_content)
{
    $uid = UserId2Uid($user_id);
    $userNode = new TUserNode($_SESSION['LOGIN_UID']);
    if($load =='cancel_concern')
    {
        $userid = $user_id.",";
        if(isset($group_id) && $group_id!='0')
        {
            $group = $group_id.",";
        }
        if(isset($group_id) && $group_id!='0')
        {
            $sql = "UPDATE concern_user set group_id = REPLACE(group_id,'$group','') WHERE  concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='$user_id'";
            exequery(TD::conn(),$sql);
            $query = "UPDATE user_group set USER_STR = REPLACE(USER_STR,'$userid','') WHERE  GROUP_ID = '$group_id' ";
            exequery(TD::conn(),$query);
            
            $sql_user = "SELECT group_id FROM concern_user WHERE concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='$user_id'";
            $cursor3  = exequery(TD::conn(),$sql_user);
            $ROW3     = mysql_fetch_array($cursor3);
            
            if(td_trim($ROW3[0])=="")
            {
                $sql_del = "DELETE FROM concern_user WHERE concern_user='".$_SESSION["LOGIN_USER_ID"]."' AND user_id ='$user_id'";
                exequery(TD::conn(),$sql_del);
            }    
        }
        else
        {
            $sql = "DELETE FROM concern_user WHERE concern_user = '".$_SESSION["LOGIN_USER_ID"]."' AND user_id = '$user_id'";
            exequery(TD::conn(),$sql);
            $query = "UPDATE user_group set USER_STR = REPLACE(USER_STR,'$userid','') WHERE  USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
            exequery(TD::conn(),$query);
        }
        $userNode->unfollow($uid);
    }
    elseif($load =='concern')
    {
        $query   = "SELECT * FROM concern_user WHERE user_id = '$user_id' and concern_user = '{$_SESSION["LOGIN_USER_ID"]}'";
        $cursor1 = exequery(TD::conn(),$query);
        $ROW     = mysql_fetch_array($cursor1);
        if(!mysql_affected_rows()>0 && $user_id!=$_SESSION["LOGIN_USER_ID"])
        {
            $group = $group_id!=0?$group_id.",":$group_id;
            
            $sql = "INSERT INTO concern_user(user_id,group_id,concern_user,concern_content)VALUES('$user_id','$group','".$_SESSION["LOGIN_USER_ID"]."','$concern_content')";
            exequery(TD::conn(),$sql);
            $userNode->follow($uid);
            
            if($group_id!=0)
            {
                $user_id_new = $user_id.",";
                $query1 = "UPDATE user_group SET USER_STR= CONCAT(USER_STR,'$user_id_new') WHERE GROUP_ID = '$group_id'";
                exequery(TD::conn(),$query1);
            }
            
            //判断是否开启关注提醒
            $SYS_PARA_ARRAY  = get_sys_para("SMS_REMIND");
            $PARA_VALUE      = $SYS_PARA_ARRAY["SMS_REMIND"];
            $PARA_VALUE_ARRAY = explode("|",$PARA_VALUE);
            if(find_id($PARA_VALUE_ARRAY[2],"86")){
                $SMS_CONTENT  = $_SESSION["LOGIN_USER_NAME"]._("关注了您");
                send_sms("",$_SESSION["LOGIN_USER_ID"],$user_id,86,$SMS_CONTENT,"");
            }
           
 
        }elseif($user_id!=$_SESSION["LOGIN_USER_ID"] && $group_id != 0)
        {
            $group = $group_id.",";
            $user_id_new = $user_id.",";
            
            if($ROW['group_id']==0)
            {
                $sql1 = "UPDATE concern_user SET group_id = '$group' WHERE concern_id = '{$ROW['concern_id']}'";
                exequery(TD::conn(),$sql1);
                
                $sql3 = "UPDATE user_group SET USER_STR= CONCAT(USER_STR,'$user_id_new') WHERE GROUP_ID = '$group_id'";
                exequery(TD::conn(),$sql3);
                
            }
            else
            {
                $groupid_array = explode(",",$ROW['group_id']);
                
                if(!in_array($group_id,$groupid_array))
                {
                    $sql2 = "UPDATE concern_user SET group_id= CONCAT(group_id,'$group') WHERE concern_id = '{$ROW['concern_id']}'";
                    exequery(TD::conn(),$sql2);
                    
                    $sql3 = "UPDATE user_group SET USER_STR= CONCAT(USER_STR,'$user_id_new') WHERE GROUP_ID = '$group_id'";
                    exequery(TD::conn(),$sql3);
                }    
            }
        }  
    }
    return "ok";    
}

//echo json_encode($concern);




?>