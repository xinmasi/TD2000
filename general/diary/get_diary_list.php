<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("get_diary_data.func.php");

ob_end_clean();
$per_page=10;
if(!isset($curpage) || $curpage=="")
    $curpage=1;
$start=($curpage-1)*$per_page;
$end=$per_page;
$where_str="";
$where_user_str="";//user表查询条件判断
$para_array=get_sys_para("LOCK_TIME,LOCK_SHARE,IS_COMMENTS");
$login_user_id=$_SESSION["LOGIN_USER_ID"];

if(isset($startdate) && $startdate!="")
{
    $where_str = " and DIA_DATE >= '$startdate' ";
}
if(isset($enddate) && $enddate!="")
{
    $where_str .= " and DIA_DATE <= '$enddate' ";
}
if($type==0 || $type==2 || $type==4)
{
    if(isset($dept) && $dept!="" && $dept!="ALL_DEPT")
    {
        $dept .= "," . GetChildDeptId($dept);
        if(substr($dept,-1,1)==",")
        {
            $dept=substr($dept,0,-1);
        }
        $dept = str_replace(",,", ",", $dept);
        $where_str .= " and b.DEPT_ID in ($dept)";
        $where_user_str .= " and b.DEPT_ID in ($dept)";
    }
    if(isset($role) && $role!="")
    {
        if(substr($role,-1,1)==",")
        {
            $role=substr($role,0,-1);
        }   
        $where_str .= " AND  b.USER_PRIV in ($role)";
        $where_user_str .= " AND  b.USER_PRIV in ($role)";
    }
}
if($db=="")
{
    if(isset($keyword) && $keyword!="")
    {
        $keyword = td_iconv($keyword, "utf-8", MYOA_CHARSET);
        $where_str .= " and (SUBJECT like '%$keyword%'  or CONTENT like '%$keyword%' or DIARY.USER_ID like '%$keyword%')";
    }    
    if(isset($user) && $user!="")
    {
        $user = td_iconv($user, "utf-8", MYOA_CHARSET);
        if(substr($user,-1,1)==",")
        {
            $user=substr($user,0,-1); 
        } 
        $where_str .= " AND  find_in_set(DIARY.USER_ID ,'$user')";
        $where_user_str .= " AND  find_in_set(b.USER_ID ,'$user')";
    }
	
	include_once("check_priv.inc.php");
    $WHERE_STRS = substr($WHERE_STRS, 4);
    
    if($type==1)//查看自己的日志
    {
        $where_str.= " and DIARY.USER_ID='$login_user_id' ";
        $diary_array=get_diary_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,'',$where_user_str,$searchnodiary,$WHERE_STRS);
    }
    else if($type==3) //查看共享的
    {
        $where_str.=" and DIARY.USER_ID!='$login_user_id' and (find_in_set('".$login_user_id."',TO_ID) || TO_ALL = '1') ";	
        $diary_array=get_diary_data($_SESSION["LOGIN_USER_ID"],3,$start,$end,$para_array,$where_str,$ismain,'',$where_user_str,$searchnodiary,$WHERE_STRS);
    }
    else
    {
        if($type==2)//查看其他人的(共享的+有权限的)
        {
            if($WHERE_STRS=="")
            {
                $where_str.=  " and DIARY.USER_ID!='$login_user_id' and (DIA_TYPE!=2 or find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1')";
            }
            else 
            {
                $where_str.= " and DIARY.USER_ID!='$login_user_id' and (".$WHERE_STRS." and DIA_TYPE!=2 or (find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1'))";
            }
            $diary_array=get_diary_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,'',$where_user_str,$searchnodiary,$WHERE_STRS);
        }        
        else if($type==4) //查看有权限的
        {
            if ($WHERE_STRS=="")
            {
                $where_str .= " and DIARY.USER_ID!='$login_user_id' and DIA_TYPE!=2 ";            
            }
            else
            {
                $where_str .= " and DIARY.USER_ID!='$login_user_id' and DIA_TYPE!=2 and ".$WHERE_STRS;
            }
            $diary_array=get_diary_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,'',$where_user_str,$searchnodiary,$WHERE_STRS);
        }
        else //查看所有的
        {	
            if($WHERE_STRS=="")
            {
                $where_str .= " and (DIA_TYPE!=2 or DIARY.USER_ID='$login_user_id' or (find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1'))";
            }
            else 
            {
                $where_str .= " and ((".$WHERE_STRS." and DIA_TYPE!=2) or DIARY.USER_ID='$login_user_id' or (find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1'))";
            }
            $diary_array=get_diary_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,'',$where_user_str,$searchnodiary,$WHERE_STRS);
        }
    }
}
else
{
    if(isset($keyword) && $keyword!="")
    {
        $keyword = td_iconv($keyword, "utf-8", MYOA_CHARSET);
    }
    if(isset($user) && $user!="")
    {
        $user = td_iconv($user, "utf-8", MYOA_CHARSET);
    }
    if($type==1)
    {
        $DIARY_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY".$db;
        $where_str.= " and ".$DIARY_TABLE_NAME.".USER_ID='$login_user_id' ";
        $diary_array=get_search_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,$db,$keyword,$user);				
    }
    else if($type==3)
    {
        $DIARY_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY".$db;
        $where_str.=" and ".$DIARY_TABLE_NAME.".USER_ID!='$login_user_id' and (find_in_set('".$login_user_id."',TO_ID) || TO_ALL = '1') ";
        $diary_array=get_search_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,$db,$keyword,$user);
    }
    else
    {
        include_once("check_priv.inc.php");
        $WHERE_STRS = substr($WHERE_STRS, 4);
        if($type==2)//查看其他人的(共享的+有权限的)
        {
            if($WHERE_STRS=="")
            {
                $where_str .= " and (DIA_TYPE!=2 or find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1')";
            }
            else 
            {
                $where_str.= " and (".$WHERE_STRS." and DIA_TYPE!=2 or (find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1'))";
            }
            $diary_array=get_search_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,$db,$keyword,$user);			
        }
        else if($type==4)//查看有权限的
        {
            if($WHERE_STRS=="")
            {
                $where_str .= " and DIA_TYPE!=2 ";
            }
            else 
            {
                $where_str .= " and DIA_TYPE!=2 and ".$WHERE_STRS;
            }
            $diary_array=get_search_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,$db,$keyword,$user);	
        }
        else //查看所有的
        {
            if ($WHERE_STRS!="")
            {
                $WHERE_STRS = " ( DIA_TYPE!=2 and ".$WHERE_STRS.") ";
            }
            else 
            {
                $WHERE_STRS = "  DIA_TYPE!=2 ";
            }
            $diary_array=get_search_data($login_user_id,$type,$start,$end,$para_array,$where_str,$ismain,$db,$keyword,$user,$WHERE_STRS);
        }		
    }	
}
echo retJson($diary_array);

?>