<?
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
//获取用户信息
function get_user_infos($USER_ID)
{
    if($USER_ID=="")
	{
		exit;
	}   
    $USER_UID=UserId2Uid($USER_ID);
    if($USER_UID!="")
    {
        $ROW = GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID,PRIV_NAME,USER_PRIV_NO,DEPT_ID_OTHER,USER_PRIV,USER_PRIV_OTHER,POST_PRIV,POST_DEPT");
        $DEPT_ID         = $ROW["DEPT_ID"];
        $USER_PRIV_NO    = $ROW["USER_PRIV_NO"];       //角色排序号
        $DEPT_ID_OTHER   = $ROW["DEPT_ID_OTHER"];     //辅助部门
        $USER_PRIV_OTHER = $ROW["USER_PRIV_OTHER"]; //辅助角色
        $POST_PRIV       = $ROW["POST_PRIV"];             //管理范围
        $POST_DEPT       = $ROW["POST_DEPT"];             //管理范围指定部门
        $USER_PRIV       = $ROW["USER_PRIV"];
        if($DEPT_ID==0)
        {
            $DEPT_NAME       = _("离职人员/外部人员");
            $SHORT_DEPT_NAME = _("离职人员/外部人员");
        }
        else
        {
            $DEPT_NAME       = dept_long_name($DEPT_ID);
            $SHORT_DEPT_NAME = dept_name($DEPT_ID);
        }
	    $USER_NAME      = $ROW["USER_NAME"];
	    $USER_PRIV_NAME = $ROW["PRIV_NAME"];
	}
    else //如果从缓存中获取不到用户信息从user表中查
    {
        $querys  = "SELECT USER_NAME,DEPT_ID,USER_PRIV,USER_PRIV_NO,DEPT_ID_OTHER,USER_PRIV_OTHER,POST_PRIV,POST_DEPT from USER where USER_ID='$USER_ID'";
        $cursors = exequery(TD::conn(),$querys);
        if($ROW=mysql_fetch_array($cursors))
        {
            $DEPT_ID         = $ROW["DEPT_ID"];
            $USER_PRIV_NO    = $ROW["USER_PRIV_NO"];       //角色排序号
            $DEPT_ID_OTHER   = $ROW["DEPT_ID_OTHER"];     //辅助部门
            $USER_PRIV_OTHER = $ROW["USER_PRIV_OTHER"]; //辅助角色
            $POST_PRIV       = $ROW["POST_PRIV"];             //管理范围
            $POST_DEPT       = $ROW["POST_DEPT"];             //管理范围指定部门
            $USER_PRIV       = $ROW["USER_PRIV"];
            if($DEPT_ID==0)
            {
                $DEPT_NAME       = _("离职人员/外部人员");
                $SHORT_DEPT_NAME = _("离职人员/外部人员");
            }
            else
            {
                $DEPT_NAME       = dept_long_name($DEPT_ID);
                $SHORT_DEPT_NAME = dept_name($DEPT_ID);
            }
            $USER_NAME      = $ROW["USER_NAME"];
            $USER_PRIV_NAME = td_trim(GetPrivNameById($ROW["USER_PRIV"]));
        }
        else //如果从数据库中已经查询不到
        {
            $USER_NAME       = $USER_ID;
            $DEPT_NAME       = _("用户已删除"); 
            $SHORT_DEPT_NAME = _("用户已删除");
            $USER_PRIV_NAME  = _("用户已删除"); 
        }  
    }
    $USER_ARRAY=array("USER_NAME"=> $USER_NAME,"DEPT_NAME"=> $DEPT_NAME,"USER_PRIV_NAME"=> $USER_PRIV_NAME,"SHORT_DEPT_NAME"=> $SHORT_DEPT_NAME,"DEPT_ID" => $DEPT_ID,"USER_PRIV_NO" => $USER_PRIV_NO,"DEPT_ID_OTHER" => $DEPT_ID_OTHER,"USER_PRIV_OTHER" => $USER_PRIV_OTHER,"POST_DEPT" => $POST_DEPT,"POST_PRIV" => $POST_PRIV,"USER_PRIV" => $USER_PRIV);
    return	$USER_ARRAY;	
}
//获取用户的查询管理范围
function get_user_priv($USER_ID)
{
    if($USER_ID=="")
	{
		exit;
	}
    $UID = UserId2Uid($USER_ID);
    $USER_PRIV = array();
    $query   = "select * from MODULE_PRIV where UID='$UID' and MODULE_ID='4'";
    $cursors = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursors))
    {
        $DEPT_PRIV = $ROW["DEPT_PRIV"];
        $ROLE_PRIV = $ROW["ROLE_PRIV"];
        $DEPT_ID   = $ROW["DEPT_ID"];
        $PRIV_ID   = $ROW["PRIV_ID"];
        $USER_IDS  = $ROW["USER_ID"];
        $USER_PRIV = array("DEPT_PRIV" => $DEPT_PRIV,"ROLE_PRIV" => $ROLE_PRIV,"DEPT_ID" => $DEPT_ID,"PRIV_ID" => $PRIV_ID,"USER_ID" => $USER_IDS);
    }
    
    return $USER_PRIV;    
}
function is_share($LOGIN_UID,$USER_ID)
{        
    $USER_ARRAY      = get_user_infos($USER_ID);
    $DEPT_ID         = $USER_ARRAY["DEPT_ID"];
    $USER_PRIV_NO    = $USER_ARRAY["USER_PRIV_NO"];
    $DEPT_ID_OTHER   = $USER_ARRAY["DEPT_ID_OTHER"];
    $USER_PRIV_OTHER = $USER_ARRAY["USER_PRIV_OTHER"];
    $POST_DEPT       = $USER_ARRAY["POST_DEPT"];
    $POST_PRIV       = $USER_ARRAY["POST_PRIV"];
    $USER_PRIV       = $USER_ARRAY["USER_PRIV"];
    
    $USER_PRIV_LOGIN    = get_user_priv($LOGIN_UID);
    $LOGIN_USERS        = GetUserInfoByUID($LOGIN_UID);
    if(is_array($LOGIN_USERS) && count($LOGIN_USERS)>0)
    {
        $LOGIN_POST_DEPT    = isset($LOGIN_USERS["POST_DEPT"]) ? $LOGIN_USERS["POST_DEPT"] : '';
        $LOGIN_POST_PRIV    = isset($LOGIN_USERS["POST_PRIV"]) ? $LOGIN_USERS["POST_PRIV"] : '';
        $LOGIN_USER_PRIV_NO = isset($LOGIN_USERS["USER_PRIV_NO"]) ? $LOGIN_USERS["USER_PRIV_NO"] : '';
    }
    else
    {
        return 0;
    }
    
    $SHARE_FLAG = "";
    if(count($USER_PRIV_LOGIN) <=0) //未按模块设置管理范围
    {
        $SHARE_FLAG = 0;
    }
    else
    {
        $DEPT_PRIV_MODULE = $USER_PRIV_LOGIN["DEPT_PRIV"];
        $ROLE_PRIV_MODULE = $USER_PRIV_LOGIN["ROLE_PRIV"];
        $DEPT_ID_MODULE   = $USER_PRIV_LOGIN["DEPT_ID"];
        $PRIV_ID_MODULE   = $USER_PRIV_LOGIN["PRIV_ID"];
        $USER_ID_MODULE   = $USER_PRIV_LOGIN["USER_ID"];
        if($DEPT_PRIV_MODULE==0)    //管理范围是本部门
        {
            if($DEPT_ID!=$_SESSION["LOGIN_DEPT_ID"])
            {
                $SHARE_FLAG = 0;
            }
            else
            {
                if($ROLE_PRIV_MODULE==0)  //低角色用户
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO < $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==1)   //同角色和低角色用户
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO <= $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==3)    //指定角色用户
                {
                    if(find_id($PRIV_ID_MODULE,$USER_PRIV) || find_id($PRIV_ID_MODULE,$USER_PRIV_OTHER))
                    {
                        $SHARE_FLAG = 1;
                    } 
                    else
                    {
                        $SHARE_FLAG = 0;
                    }
                }
                else      //所有角色用户
                {
                    $SHARE_FLAG = 1;
                }                                    
            }                                
        }
        else if($DEPT_PRIV_MODULE==2)   //管理范围是指定部门
        {   
            if(!find_id($DEPT_ID_MODULE,$DEPT_ID) && !find_id($DEPT_ID_MODULE,$DEPT_ID_OTHER))
            {
                $SHARE_FLAG = 0;
            }
            else
            {
                if($ROLE_PRIV_MODULE==0)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO < $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==1)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO <= $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==3)
                {
                    if(find_id($PRIV_ID_MODULE,$USER_PRIV) || find_id($PRIV_ID_MODULE,$USER_PRIV_OTHER))
                    {
                        $SHARE_FLAG = 1;
                    } 
                    else
                    {
                        $SHARE_FLAG = 0;
                    }
                }
                else
                {
                    $SHARE_FLAG = 1;
                }       
            }
            
        }
        else if($DEPT_PRIV_MODULE==3)
        {
            if(!find_id($USER_ID_MODULE,$AUTHOR_USER_ID))
            {
                $SHARE_FLAG = 0;
            }
            else 
            {
                if($ROLE_PRIV_MODULE==0)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO < $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==1)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO <= $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==3)
                {
                    if(find_id($PRIV_ID_MODULE,$USER_PRIV) || find_id($PRIV_ID_MODULE,$USER_PRIV_OTHER))
                    {
                        $SHARE_FLAG = 1;
                    } 
                    else
                    {
                        $SHARE_FLAG = 0;
                    }
                }
                else
                {
                    $SHARE_FLAG = 1;
                } 
                
            }                                
        }
        else if($DEPT_PRIV_MODULE==1)
        {
            if($ROLE_PRIV_MODULE==0)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO < $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==1)
                {
                    $SHARE_FLAG = $LOGIN_USER_PRIV_NO <= $USER_PRIV_NO ? 1 : 0;
                }
                else if($ROLE_PRIV_MODULE==3)
                {
                    if(find_id($PRIV_ID_MODULE,$USER_PRIV) || find_id($PRIV_ID_MODULE,$USER_PRIV_OTHER))
                    {
                        $SHARE_FLAG = 1;
                    } 
                    else
                    {
                        $SHARE_FLAG = 0;
                    }
                }
                else
                {
                    $SHARE_FLAG = 1;
                } 
        }
           
    }
    return $SHARE_FLAG;
}
 
//获取总数
function  get_diary_count($USER_ID,$TYPE,$WHERE_STR="",$IS_MAIN="",$DIARY_COPY_TIME="")
{
    $num_count=0;
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
        $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME         = "DIARY"; 
        $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT"; 
    }
    if($TYPE==1)
    {
        if($WHERE_STR=="")
        {
            $WHERE_STR = " and  USER_ID = '".$USER_ID."'";
        }
        $query_count  = "SELECT count(*) from ".$DIARY_TABLE_NAME." where 1=1 ".$WHERE_STR;
        $cursor_count = exequery(TD::conn(),$query_count,$QUERY_MASTER);
        if($ROW_COUNT=mysql_fetch_array($cursor_count))
        {
            $num_count=$ROW_COUNT[0];
        }        
    }
    if($TYPE==2)
    {
        if($WHERE_STR!="")
        {
            $WHERE_STR = substr($WHERE_STR,4);
            $WHERE_STR = " and ".$DIARY_TABLE_NAME.".USER_ID!='$USER_ID' and (".$WHERE_STR." and DIA_TYPE!=2 or (find_in_set('".$USER_ID."',TO_ID) || TO_ALL= '1'))";            
        }
        else
        {
            $WHERE_STR = " and ".$DIARY_TABLE_NAME.".USER_ID!='$USER_ID' and (DIA_TYPE!=2 or find_in_set('".$USER_ID."',TO_ID) || TO_ALL= '1')";
        }	
                
        $query_count = "SELECT count(*)
            FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1".$WHERE_STR." ORDER BY DIA_TIME DESC ";
        $cursor_count= exequery(TD::conn(),$query_count,$QUERY_MASTER);
        if($ROW_COUNT=mysql_fetch_array($cursor_count))
        {
            $num_count=$ROW_COUNT[0];
        }	
    }
    return $num_count;

}
//获取列表日志信息
function get_diary_data($USER_ID,$TYPE,$start,$end,$PARA_ARRAY,$WHERE_STR="",$IS_MAIN="",$DIARY_COPY_TIME="",$WHERE_USER_STR="",$SEARCHNODIARY,$power)
{
    $dataBack  = array(); 
    $dataBacks = array(); 
    $PER_PAGE  = 10;
    $num_count = 0;
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
        $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$DIARY_COPY_TIME;
        $DIARY_TOP_TABLE_NAME     = TD::$_arr_db_master['db_archive'].".DIARY_TOP".$DIARY_COPY_TIME;

    }
    else
    {
        $DIARY_TABLE_NAME         = "DIARY"; 
        $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT";
        $DIARY_TOP_TABLE_NAME     = "DIARY_TOP";

    }
	
    if($USER_ID!="")//查询某用户日志 1:自己，3:共享给自己，2:查看其他人的(共享的+有权限的)，4:查看有权限的,0:所有
    {
        if($TYPE==1 || $TYPE==3)  
        {
            $query = "SELECT DIARY.DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,CONTENT,DIARY.USER_ID,t.TOP_ID as TOP_ID from ".$DIARY_TABLE_NAME." LEFT JOIN ".$DIARY_TOP_TABLE_NAME." as t on t.DIA_ID = DIARY.DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1 ".$WHERE_STR." order by TOP_ID DESC,DIA_TIME DESC limit ".$start.",".$end;
            $query_count = "SELECT count(*) from ".$DIARY_TABLE_NAME." where 1=1 ".$WHERE_STR." order by DIA_TIME desc";
        }
        else if($TYPE==0 || $TYPE==2 || $TYPE==4)
        {
            $query = "SELECT DIARY.DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".USER_ID AS USER_ID,t.TOP_ID as TOP_ID
                 FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV LEFT JOIN ".$DIARY_TOP_TABLE_NAME." as t on t.DIA_ID = DIARY.DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1".$WHERE_STR." ORDER BY TOP_ID DESC,DIA_TIME DESC LIMIT ".$start.",".$end;
            $query_count = "SELECT count(*)
                 FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1".$WHERE_STR." ORDER BY DIA_TIME DESC ";	
        }
        $cursor       = exequery(TD::conn(),$query,$QUERY_MASTER);
        $cursor_count = exequery(TD::conn(),$query_count,$QUERY_MASTER);
    
        if($ROW_COUNT=mysql_fetch_array($cursor_count))
        {
            $num_count = $ROW_COUNT[0];
        }
        $curpage   = ($start/$PER_PAGE) +1;
        $totalpage = ceil($num_count/$PER_PAGE);
		//模块管理员权限
		$module_priv ="";
		if(is_module_manager(2))
		{
			$module_priv = 1;
		}
        
        $code_arr = array();
        $query0 = "SELECT CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='DIARY_TYPE'";
        $cursor0= exequery(TD::conn(),$query0);
        while($row0 = mysql_fetch_array($cursor0))
        {
            $CODE_NO        = $row0["CODE_NO"];
            $CODE_NAME      = $row0["CODE_NAME"];
            $CODE_EXT       = unserialize($row0["CODE_EXT"]);
            if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
                $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

            $code_arr[$CODE_NO] = $CODE_NAME;
        }
        
        while($ROW=mysql_fetch_array($cursor))
        {         		
            $DIA_ID           = $ROW["DIA_ID"];
            $DIA_DATE         = $ROW["DIA_DATE"];
            $DIA_DATE         = strtok($DIA_DATE," ");
            $DIA_TYPE         = $ROW["DIA_TYPE"];
            $SUBJECT          = $ROW["SUBJECT"];
            $AUTHOR_USER_ID   = $ROW["USER_ID"];
            $DIA_TIME         = strtotime($ROW["DIA_TIME"]);
            $COMPRESS_CONTENT = $ROW["COMPRESS_CONTENT"];
            $NOTAGS_CONTENT   = $ROW['CONTENT'];
            $TOP_ID           = $ROW['TOP_ID'];//yc置顶排序ID
            $query_user  = "SELECT AVATAR  FROM USER WHERE USER_ID='$AUTHOR_USER_ID'";
            $cursor_user = exequery(TD::conn(),$query_user,$QUERY_MASTER);
            if($ROW_USER=mysql_fetch_array($cursor_user))
            {
                $AVATAR = $ROW_USER["AVATAR"];
            }
            
            if($AUTHOR_USER_ID=="" || $DIA_TYPE=="")
            {
                continue;
            }
            
            if($DIA_TYPE!="")
            {
                $DIA_TYPE_NAME = $code_arr[$DIA_TYPE];
            }
            else
            {
                $DIA_TYPE_NAME = "";
            }
        
            if($COMPRESS_CONTENT == "")
            {
                $CONTENT = $NOTAGS_CONTENT;
            }
            else
            {
                $CONTENT = @gzuncompress($COMPRESS_CONTENT);
                if($CONTENT===FALSE)
                {
                    $CONTENT = $NOTAGS_CONTENT;
                }
            }
            $CONTENT_LEN = strlen($CONTENT);
            
            if($CONTENT_LEN>'500')
            {    
                $CONTENT_S    = csubstr($CONTENT,0,500)."...";
                $flagReadMore = 1;
                
            }
            else
            {
                $CONTENT_S    = $CONTENT;
                $flagReadMore = 0;    
            }
            
            $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
            $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"]; 
            if(strlen($SUBJECT)>'100')
            {
                $SUBJECT = csubstr($SUBJECT,0,100);
            }
            if($ATTACHMENT_ID && $ATTACHMENT_NAME!="")
            {
                $HAS_ATTACHMENT = 1;
            }
            else
            {
                $HAS_ATTACHMENT = 0;
            }            
            //附件信息
            $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
            if($ATTACH_ARRAY["NAME"]!="")
            {
                $ATTACHMENTS = str_replace("<br>","",attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1));
                $ATTACHMENTS = str_replace("</br>","",$ATTACHMENTS);
            }
            else
            {
                $ATTACHMENTS = "";
            }
            //判断是否有点评及点评数量
            $querys  = "SELECT count(DIA_ID) from ".$DIARY_COMMENT_TABLE_NAME." where DIA_ID='$DIA_ID'";
            $cursors = exequery(TD::conn(),$querys,TRUE);
            if($ROWS=mysql_fetch_array($cursors))
            {
                $COMMENT_COUNT = $ROWS[0];
            }
            else
            {
                $COMMENT_COUNT = 0;
            }
            if($AUTHOR_USER_ID!="")
            {
            //作者信息
                if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID)
                {
                    $AUTHOR_USER_NAME  = $_SESSION["LOGIN_USER_NAME"];
                    $AUTHOR_DEPT_ID    = $_SESSION["LOGIN_DEPT_ID"];
                    
                    if($AUTHOR_DEPT_ID==0)
                    {
                        $AUTHOR_DEPT_NAME       = _("离职人员/外部人员");
                        $AUTHOR_SHORT_DEPT_NAME = _("离职人员/外部人员");
                    }
                    else
                    {
                        $AUTHOR_DEPT_NAME       = dept_long_name($AUTHOR_DEPT_ID);
                        $AUTHOR_SHORT_DEPT_NAME = dept_name($AUTHOR_DEPT_ID);
                    }
                    $AUTHOR_PRIV_NAME = td_trim(GetPrivNameById($_SESSION["LOGIN_USER_PRIV"]));		
                }
                else
                {
                    $USER_ARRAY             = get_user_infos($AUTHOR_USER_ID);
                    $AUTHOR_USER_NAME       = $USER_ARRAY["USER_NAME"];
                    $AUTHOR_DEPT_NAME       = $USER_ARRAY["DEPT_NAME"];
                    $AUTHOR_PRIV_NAME       = $USER_ARRAY["USER_PRIV_NAME"];
                    $AUTHOR_SHORT_DEPT_NAME = $USER_ARRAY["SHORT_DEPT_NAME"];
                } 
            }
            
            if($AVATAR=="0" || $AVATAR=="1")
            {
                $AUTHOR_PHOTO = photo_path($AUTHOR_USER_ID);
            }
            else
            {
        	    $AUTHOR_PHOTO = avatar_path($AVATAR);	
        	}		
        	//操作权限
            if(is_array($PARA_ARRAY))
            {
                while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
				{
					$$PARA_NAME = $PARA_VALUE;
				}  
                if($LOCK_TIME!="")
                {
                   /* $LOCK_TIME = explode(",",$LOCK_TIME);
                    $W_START   = $LOCK_TIME[0];
                    $W_END     = $LOCK_TIME[1];
                    $DAYS      = intval($LOCK_TIME[2]);*/
					$PARA_ARRAY = get_sys_para("LOCK_TIME");
					$PARA_VALUE = $PARA_ARRAY["LOCK_TIME"];
					$PARA_VALUE = explode(",",$PARA_VALUE);
					
					$W_START = $PARA_VALUE[0];
					$W_END   = $PARA_VALUE[1];
					$DAYS    = $PARA_VALUE[2];	
                }
                $EDIT_FLAG  = 0;
                $SHARE_FLAG = 0;
                $DEL_FLAG   = 0;
                $REPLY_FLAG = 0;
				$TOP_FLAG   = 0;//yc置顶状态
            }
			if($TOP_ID!="")
			{
				$TOP_FLAG=1;
			}
            if($LOCK_SHARE==1 && $DIARY_COPY_TIME=="")
            {
                $SHARE_FLAG=1;
            }
            if($LOCK_SHARE==0 && $DIARY_COPY_TIME=="")
            {
            	//不在锁定日期内
                if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $_SESSION["LOGIN_USER_PRIV"]!=1 && $module_priv !=1)
                    {
						
                        $SHARE_FLAG = is_share($_SESSION["LOGIN_UID"],$AUTHOR_USER_ID);                        
                    }
                    else
                    {
                        if($DIA_TYPE!=2)
                        {
                            $SHARE_FLAG = 1;//共享权限
                        }
                    }
                }
            }
            if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID || $module_priv==1) //自己的日志
            {
                //不在锁定日期内
                if($DIARY_COPY_TIME=="" && ($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    $EDIT_FLAG  = 1; //编辑
                    $REPLY_FLAG = 1; //点评
                }
                $DEL_FLAG = 1;
            	
            }
            if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $DIARY_COPY_TIME=="" && $module_priv!=1) //其它人的日志
            {
                //不在锁定日期内
                if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    if($IS_COMMENTS==1)
                    {
                        $REPLY_FLAG = 1; //点评
                    }
                }	
            }
            if($AUTHOR_USER_ID==$_SESSION["LOGIN_USER_ID"])
            {
                $flagPrivate = 1;
            }
            else
            {
                $flagPrivate = 0;
            }
        	//系统管理员的特殊权限
            if($_SESSION["LOGIN_USER_PRIV"]==1 || $module_priv==1)
            {
                $DEL_FLAG = 1;
                //$SHARE_FLAG=1;
            }
            $MORE_URL = "show_diary.php?dia_id=".$DIA_ID."&diary_copy_time=".$DIARY_COPY_TIME;    
            
            $CONTENT  = htmlFilter($CONTENT);
            
        	$dataBack[] = array(
            "dia_id"                 => $DIA_ID,     //日志ID
            "dia_date"               => $DIA_DATE, //创建日期
            "dia_time"               => $DIA_TIME, //创建时间
            "subject"                => $SUBJECT,   //主题
            "dia_type"               => $DIA_TYPE_NAME, //日志类型	   
            "has_attachment"         => $HAS_ATTACHMENT,//是否有附件
            "attachments"            => $ATTACHMENTS,//附件内容
            "comment_count"          => $COMMENT_COUNT,  //点评数量
            "author_user_id"         => $AUTHOR_USER_ID, //作者
            "author_user_name"       => $AUTHOR_USER_NAME,//作者名称
            "author_dept_name"       => $AUTHOR_DEPT_NAME,//作者部门名称
            "author_short_dept_name" => $AUTHOR_SHORT_DEPT_NAME,//作者短部门名称
            "author_priv_name"       => $AUTHOR_PRIV_NAME,//作者角色名称
            "author_photo"           => $AUTHOR_PHOTO,//作者头像
            "edit_flag"              => $EDIT_FLAG,//编辑权限
            "del_flag"               => $DEL_FLAG,//删除权限
            "share_flag"             => $SHARE_FLAG,//设置共享范围权限
            "reply_flag"             => $REPLY_FLAG,//点评权限
            "flagReadMore"           => $flagReadMore,//内容是否缩写
            "flagPrivate"            => $flagPrivate, //是否个人
            "more_url"               => $MORE_URL,
            "top_flag"               => $TOP_FLAG,//置顶状态yc
            "content_all"            => $CONTENT
        	);
        }
        
        include_once("check_priv.inc.php");
        $WHERE_STRS = substr($WHERE_STRS, 4);
        if($WHERE_STRS!="")
        {
            $WHERE_STR_DEPT .= "and ".$WHERE_STRS;
        }
		
		//将数组按条件重新排序yc
		/*foreach($dataBack as $key=>$val){
    		$top_flag[] = $val['top_flag'];
			$dia_id[]   = $val['dia_id'];
   			$sort_type  = SORT_DESC;
 		 }
		 array_multisort($top_flag,$sort_type,$dia_id,$sort_type,$dataBack);*/
		 //对二维数组进行分页处理yc
		 //$dataBack=array_slice($dataBack, $start, $end);
		
        $dataBacks=array("curpage" => $curpage,"totalpage" => $totalpage,"datalist" => $dataBack);
        //未写日志的人员
        if($SEARCHNODIARY!=0){
            $query2 = "select distinct(DIARY.USER_ID) as USER_ID from DIARY left outer join USER b on DIARY.USER_ID=b.USER_ID left outer join USER_PRIV g on g.USER_PRIV=b.USER_PRIV  where 1=1 ".$WHERE_STR.$WHERE_STR_DEPT;
            $cursor2 = exequery(TD::conn(),$query2);
            while($ROW2=mysql_fetch_array($cursor2))
            {
                $diary_user_id.= $ROW2["USER_ID"].",";                        
            }
			if($power)
			{
				$power=" and".$power;
			}
			//增加动态权限判断yc
            $query2 = "select distinct(b.USER_ID) from USER b left outer join USER_PRIV g on g.USER_PRIV=b.USER_PRIV where 1=1 ".$power.$WHERE_USER_STR.$WHERE_STR_DEPT;
            $cursor2 = exequery(TD::conn(),$query2);
            $nodiary_dept_id = "";
            $count = 0;
            while($ROW2=mysql_fetch_array($cursor2))
            {
                $user_ids .= $ROW2["USER_ID"].",";
                $user_id   = $ROW2["USER_ID"];
                if($diary_user_id != "")
                {
                    if(!find_id($diary_user_id,$user_id))
                    {
                        $count++;
                        $nodiary_uid .= UserId2Uid($user_id).",";
                        $nodiary_user_name .= GetUserInfoByUID(UserId2Uid($user_id),"USER_NAME").",";
                        if($count <= 10)
                        {
                            $nodiary_short_user_name .= GetUserInfoByUID(UserId2Uid($user_id),"USER_NAME").",";
                        }
                        $nodiary_dept_nme = GetUserInfoByUID(UserId2Uid($user_id),"DEPT_ID");
                        if(!find_id($nodiary_dept_id,$nodiary_dept_nme))
                        {
                            $nodiary_dept_name .= dept_name($nodiary_dept_nme)." ";
                            $nodiary_dept_id .= $nodiary_dept_nme.",";
                        }
                        $nodiary_user_id .= $user_id.",";                
                    }
                }
                else
                {
                    $count++;
                    $nodiary_uid .= UserId2Uid($user_id).",";
                    $nodiary_user_name .= GetUserInfoByUID(UserId2Uid($user_id),"USER_NAME").",";
                    $nodiary_dept_nme = GetUserInfoByUID(UserId2Uid($user_id),"DEPT_ID");
                    if($count <= 10)
                    {
                        $nodiary_short_user_name .= GetUserInfoByUID(UserId2Uid($user_id),"USER_NAME").",";
                    }
                    if(!find_id($nodiary_dept_id,$nodiary_dept_nme))
                    {
                        $nodiary_dept_name .= dept_name($nodiary_dept_nme)." ";
                        $nodiary_dept_id .= $nodiary_dept_nme.",";
                    }
                    $nodiary_user_id .= $user_id.",";
                    
                }
            }
            
            $nodiary_user = array(
            "user_name"       => rtrim($nodiary_user_name,","),
            "short_user_name" => rtrim($nodiary_short_user_name,","),
            "dept_name"       => $nodiary_dept_name,
            "count"           => $count,
            "user_id"         => rtrim($nodiary_user_id,","),
            "uid"             => $nodiary_uid
            );
            $dataBacks=array("curpage" => $curpage,"totalpage" => $totalpage,"datalist" => $dataBack,"nodiary_user" => $nodiary_user);
        }
        
        //$dataBacks=array("curpage" => $curpage,"totalpage" => $totalpage,"datalist" => $dataBack,"nodiary_user" => $nodiary_user);
    }
    return $dataBacks;
}
//获取日志详细信息
function get_diary_detaildata($DIA_ID,$PARA_ARRAY,$IS_MAIN="",$DIARY_COPY_TIME="")
{   
    if(!isset($DIA_ID))
	{
		exit;
	}  
    $dataBack = array(); 
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
        $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$DIARY_COPY_TIME;
        $DIARY_TOP_TABLE_NAME     = TD::$_arr_db_master['db_archive'].".DIARY_TOP".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME         = "DIARY"; 
        $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT";
        $DIARY_TOP_TABLE_NAME     = "DIARY_TOP";
    }
	$module_priv ="";
	if(is_module_manager(2))
	{
		$module_priv = 1;
	}
	//查询日志详细信息
    $query  = "SELECT DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT,d.USER_ID,COMPRESS_CONTENT,READERS,ATTACHMENT_ID,ATTACHMENT_NAME,t.TOP_ID as TOP_ID from ".$DIARY_TABLE_NAME." AS d LEFT JOIN ".$DIARY_TOP_TABLE_NAME." AS t ON t.DIA_ID = d.DIA_ID AND FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',t.USER_ID) AND t.DIA_CATE = 1 where d.DIA_ID='$DIA_ID' ";
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    if($ROW=mysql_fetch_array($cursor))
    {
        $DIA_DATE       = $ROW["DIA_DATE"];
        $DIA_TIME       = $ROW["DIA_TIME"];
        $DIA_DATE       = strtok($DIA_DATE," ");
        $DIA_TYPE       = $ROW["DIA_TYPE"];
        $SUBJECT        = $ROW["SUBJECT"];
        $AUTHOR_USER_ID = $ROW["USER_ID"];
        $NOTAGS_CONTENT = $ROW["CONTENT"];
        $READERS        = $ROW["READERS"];
		$TOP_ID         = $ROW["TOP_ID"];
        if($ROW["COMPRESS_CONTENT"] == "")
        {
            $CONTENT = $NOTAGS_CONTENT;
        }
        else
        {
            $CONTENT = @gzuncompress($ROW["COMPRESS_CONTENT"]);
            if($CONTENT===FALSE)
                $CONTENT = $NOTAGS_CONTENT;
        }
        $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
        //附件信息
        $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
        if($ATTACH_ARRAY["NAME"]!="")
        {
            $ATTACHMENTS = str_replace("<br>","",attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1));
            $ATTACHMENTS = str_replace("</br>","",$ATTACHMENTS);
        }
        $DIA_TYPE_DESC = get_code_name($DIA_TYPE,"DIARY_TYPE");
        //作者信息
        if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID)
        {
            $AUTHOR_USER_NAME = $_SESSION["LOGIN_USER_NAME"];
            $AUTHOR_DEPT_ID   = $_SESSION["LOGIN_DEPT_ID"];
            if($AUTHOR_DEPT_ID==0)
            {
                $AUTHOR_DEPT_NAME       = _("离职人员/外部人员");
                $AUTHOR_SHORT_DEPT_NAME = _("离职人员/外部人员");
            }
            else
            {
                $AUTHOR_DEPT_NAME       = dept_long_name($AUTHOR_DEPT_ID);
                $AUTHOR_SHORT_DEPT_NAME = dept_name($AUTHOR_DEPT_ID);
            }
            $AUTHOR_PRIV_NAME = td_trim(GetPrivNameById($_SESSION["LOGIN_USER_PRIV"]));		
        }
        else
        {
            $USER_ARRAY             = get_user_infos($AUTHOR_USER_ID);
            $AUTHOR_USER_NAME       = $USER_ARRAY["USER_NAME"];
            $AUTHOR_DEPT_NAME       = $USER_ARRAY["DEPT_NAME"];
            $AUTHOR_PRIV_NAME       = $USER_ARRAY["USER_PRIV_NAME"];
            $AUTHOR_SHORT_DEPT_NAME = $USER_ARRAY["SHORT_DEPT_NAME"];
        } 
        $AUTHOR_PHOTO = photo_path($AUTHOR_USER_ID);			
        //操作权限
        if(is_array($PARA_ARRAY))
        {
            while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
			{
				$$PARA_NAME = $PARA_VALUE;
			}    
            if($LOCK_TIME!="")
            {
                $LOCK_TIME = explode(",",$LOCK_TIME);
                $W_START   = $LOCK_TIME[0];
                $W_END     = $LOCK_TIME[1];
                $DAYS      = intval($LOCK_TIME[2]);
            }
            $EDIT_FLAG  = 0;
            $SHARE_FLAG = 0;
            $DEL_FLAG   = 0;
            $REPLY_FLAG = 0;
			$TOP_FLAG   = 0;//置顶状态yc
        }
		if($TOP_ID!="")
		{
			$TOP_FLAG = 1;
		}
        if($LOCK_SHARE==1 && $DIARY_COPY_TIME=="")
        {
            $SHARE_FLAG = 1;
        }
        if($LOCK_SHARE==0 && $DIARY_COPY_TIME=="")
        {
        //不再锁定日期内
            if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
            {
                if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $_SESSION["LOGIN_USER_PRIV"]!=1 && $module_priv!=1)
                {
                    $SHARE_FLAG = is_share($_SESSION["LOGIN_UID"],$AUTHOR_USER_ID);
                }
                else
                {
                    if($DIA_TYPE!=2)
                    {
                        $SHARE_FLAG = 1;
                    }                    
                }
            }
        }
        if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID && $DIARY_COPY_TIME=="" || $module_priv==1) //自己的日志
        {
            //不再锁定日期内
            if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
            {
                $EDIT_FLAG  = 1; //编辑
                $REPLY_FLAG = 1; //点评
            }
            $DEL_FLAG = 1;        
        }
        if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $DIARY_COPY_TIME=="" && $module_priv!=1) //其它人的日志
        {
            //不再锁定日期内
            if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
            {
                if($IS_COMMENTS==1 )
                    $REPLY_FLAG = 1; //点评
            }	
        }
        if($_SESSION["LOGIN_USER_PRIV"]==1 && $DIARY_COPY_TIME=="" || $module_priv==1)
        {
            $DEL_FLAG = 1;
        }
        //判断是否有点评及点评数量
        $querys  = "SELECT count(DIA_ID) from ".$DIARY_COMMENT_TABLE_NAME." where DIA_ID='$DIA_ID'";
        $cursors = exequery(TD::conn(),$querys,TRUE);
        if($ROWS=mysql_fetch_array($cursors))
        {
            $COMMENT_COUNT = $ROWS[0];
        }	
        else
        {
            $COMMENT_COUNT = 0;
        }
        
        $CONTENT = htmlFilter($CONTENT);
        
        $dataBack = array(
        "dia_id"                 => $DIA_ID,     //日志主ID
        "dia_date"               => $DIA_DATE, //创建日期
        "dia_time"               => $DIA_TIME, //创建时间
        "subject"                => $SUBJECT,   //主题
        "dia_type_desc"          => $DIA_TYPE_DESC, //日志类型
        "content"                => $CONTENT,//内容
        "attachments"            => $ATTACHMENTS,//附件名称
        "comment_count"          => $COMMENT_COUNT,  //点评数量
        "author_user_id"         => $AUTHOR_USER_ID, //作者
        "author_user_name"       => $AUTHOR_USER_NAME,//作者名称
        "author_dept_name"       => $AUTHOR_DEPT_NAME,//作者部门名称
        "author_short_dept_name" => $AUTHOR_SHORT_DEPT_NAME,//作者短部门名称
        "author_priv_name"       => $AUTHOR_PRIV_NAME,//作者角色名称
        "author_photo"           => $AUTHOR_PHOTO,//作者头像
        "edit_flag"              => $EDIT_FLAG,//编辑权限
        "del_flag"               => $DEL_FLAG,//删除权限
        "share_flag"             => $SHARE_FLAG,//设置共享范围权限
        "reply_flag"             => $REPLY_FLAG,//点评权限
	    "top_flag"               => $TOP_FLAG,//置顶状态yc
        "readers"                => $READERS
        );
    }
    return $dataBack;
}
//获取某日志的评论信息
function get_diary_commentdate($DIA_ID,$IS_MAIN=1,$PARA_ARRAY,$DIARY_COPY_TIME="")
{
    if(!isset($DIA_ID))
	{
		exit;
	}  
    $dataBack = array(); 
    $COUNT    = 0;
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
        $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$DIARY_COPY_TIME;
        $DIARY_COMMENT_REPLY      = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT_REPLY".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME         = "DIARY"; 
        $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT"; 
        $DIARY_COMMENT_REPLY      = "DIARY_COMMENT_REPLY"; 
    }
	$module_priv ="";
	if(is_module_manager(2))
	{
		$module_priv = 1;
	}
    $query  = "SELECT a.COMMENT_ID,a.USER_ID,a.SEND_TIME,a.CONTENT,a.COMMENT_FLAG,a.ATTACHMENT_ID,a.ATTACHMENT_NAME,b.USER_ID as AU_ID from ".$DIARY_COMMENT_TABLE_NAME." as a,".$DIARY_TABLE_NAME." as b where a.DIA_ID='$DIA_ID' and a.DIA_ID=b.DIA_ID order by SEND_TIME asc";
    $cursor = exequery(TD::conn(), $query,TRUE);
    while($ROW = mysql_fetch_array($cursor))
    {
        $COUNT++;
        $COMMENT_ID      = $ROW["COMMENT_ID"];
        $USER_ID         = $ROW["USER_ID"];
        $SEND_TIME       = $ROW["SEND_TIME"];
        $CONTENT         = td_trim($ROW["CONTENT"]);
        $COMMENT_FLAG    = $ROW["COMMENT_FLAG"];
        $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
        //附件信息
        $ATTACH_ARRAY    = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
        if($ATTACH_ARRAY["NAME"]!="")
        {
            $ATTACHMENTS = str_replace("<br>","",attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1));
            $ATTACHMENTS = str_replace("</br>","",$ATTACHMENTS);
        }
        else
        {
            $ATTACHMENTS = "";
        }
        $CONTENT = str_replace("\"","'",$CONTENT);
        
        if(strpos($CONTENT,"\n")!==false && strpos($CONTENT,"<br />")===false)
        {
            $CONTENT = str_replace("\n","<br />",$CONTENT);
            $CONTENT = str_replace("<br /><br />","",$CONTENT);
        }
        $AU_ID     = $ROW["AU_ID"];
        $USER_NAME = td_trim(GetUserNameById($USER_ID));
        //操作权限
        if(is_array($PARA_ARRAY))
        {
            while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
			{
				$$PARA_NAME = $PARA_VALUE;
			} 
            if($LOCK_TIME!="")
            {
                $LOCK_TIME = explode(",",$LOCK_TIME);
                $W_START   = $LOCK_TIME[0];
                $W_END     = $LOCK_TIME[1];
                $DAYS      = intval($LOCK_TIME[2]);
            }
            $DEL_FLAG   = 0;
            $REPLY_FLAG = 0;
        }	
        //删除权限
        if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_ID"]==$AU_ID || $_SESSION["LOGIN_USER_PRIV"]==1 || $module_priv==1) //为回复人或日志作者可删除
        {
            $DEL_FLAG=1;
        }
        //回复权限
        if($DIARY_COPY_TIME=="") //不是归档日志且日志有评论权限
        {
            //不再锁定日期内
            if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
            {
                $REPLY_FLAG= 1; //点评
            }
        }
            
        $dataBack[] = array(
        "type"           => "",
        "id"             => $COMMENT_ID, //评论主ID
        "comment_id"     => "",
        "from"           => $USER_NAME, //回复人
        "from_id"        => $USER_ID,//回复人ID
        "to_id"          =>  '',//日志作者ID
        "to"             => '',//日志作者
        "count"          => $COUNT, //点评楼层
        "send_time"      => $SEND_TIME,   //点评时间
        "send_timestamp" => strtotime($SEND_TIME),   //点评时间戳
        "comment_flag"   => $COMMENT_FLAG, //阅读标记0作者未读、1已读
        "content"        => $CONTENT,//点评内容
        "attachments"    => $ATTACHMENTS, //附件
        "del_flag"       => $DEL_FLAG,
        "reply_flag"     => $REPLY_FLAG
        );
        $querys  = "SELECT * from ".$DIARY_COMMENT_REPLY." where COMMENT_ID='$COMMENT_ID' order by REPLY_TIME asc";
        $cursors = exequery(TD::conn(),$querys,TRUE);
        while($ROWS=mysql_fetch_array($cursors))
        {
            $REPLYER       = $ROWS["REPLYER"];
            $REPLY_ID      = $ROWS["REPLY_ID"];
            $COMMENT_ID    = $ROWS["COMMENT_ID"];
            $REPLY_TIME    = $ROWS["REPLY_TIME"];
            $REPLY_COMMENT = td_trim($ROWS["REPLY_COMMENT"]);
            //$REPLY_COMMENT=str_replace("\n","<br>",$REPLY_COMMENT);
            $USER_NAME     = td_trim(GetUserNameById($REPLYER));
            $TO_ID         = $ROWS["TO_ID"];        //增加字段
            $TO_NAME       = td_trim(GetUserNameById($TO_ID));
            //删除权限
            if($REPLY_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_ID"]==$AU_ID || $_SESSION["LOGIN_USER_PRIV"]==1 || $module_priv==1) //当前用户为回复人或日志作者、系统管理员可删除
            {
                $DEL_FLAG = 1;
            }
            else
            {
                $DEL_FLAG = 0;
            }
            //回复权限
            if($DIARY_COPY_TIME=="" ) //不是归档日志
            {
                $REPLY_FLAG = 1;
            }
            else
            {
                $REPLY_FLAG = 0;
            }
            $dataBack[] = array(
            "type"           => "sub",
            "id"             => $REPLY_ID, //回复主ID
            "comment_id"     => $COMMENT_ID,
            "from"           => $USER_NAME, //回复人
            "from_id"        => $REPLYER, //回复人ID
            "to_id"          => $TO_ID,
            "to"             => $TO_NAME,
            "count"          => "" , //点评楼层
            "send_time"      => $REPLY_TIME,   //回复时间
            "send_timestamp" => strtotime($REPLY_TIME),   //回复时间戳
            "comment_flag"   => "" , //阅读标记0作者未读、1已读
            "content"        => $REPLY_COMMENT,//回复内容
            "attachments"    => "", //附件
            "del_flag"       => $DEL_FLAG,
            "reply_flag"     => $REPLY_FLAG
            );
        }
    }
    return $dataBack;	
}

//获取有权限查看的日志留where_str
function get_diary_privdata($WHERE_STR,$IS_MAIN="",$DIARY_COPY_TIME="")
{
    $dataBack = array(); 
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME = "DIARY"; 
    }
    //查询日志详细信息
    $query  = "SELECT DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,USER_ID from ".$DIARY_TABLE_NAME." where 1=1 and ".$WHERE_STR;
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);	
    while($ROW=mysql_fetch_array($cursor))
    {
        $DIA_ID           = $ROW["DIA_ID"];
        $DIA_DATE         = $ROW["DIA_DATE"];
        $DIA_DATE         = strtok($DIA_DATE," ");
        $DIA_TYPE         = $ROW["DIA_TYPE"];
        $SUBJECT          = $ROW["SUBJECT"];
        $AUTHOR_USER_ID   = $ROW["USER_ID"];
        $DIA_TIME         = $ROW["DIA_TIME"];
        $COMPRESS_CONTENT = $ROW["COMPRESS_CONTENT"];
        $NOTAGS_CONTENT   = $ROW['CONTENT'];
        if($COMPRESS_CONTENT == "")
        {
            $CONTENT = $NOTAGS_CONTENT;
        }
        else
        {
            $CONTENT = @gzuncompress($COMPRESS_CONTENT);
            if($CONTENT===FALSE)
			{
				$CONTENT = $NOTAGS_CONTENT;
			}      
        } 
        $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"]; 
        if($SUBJECT=="")
            $SUBJECT = _("无标题");        
        $dataBack[] = array(
        "dia_id"          => $DIA_ID,     //日志主ID
        "dia_date"        => $DIA_DATE, //创建日期
        "dia_time"        => $DIA_TIME, //创建时间
        "subject"         => $SUBJECT,   //主题
        "dia_type"        => $DIA_TYPE, //日志类型
        "content"         => htmlFilter($CONTENT),//内容
        "attachment_id"   => $ATTACHMENT_ID, //附件ID
        "attachment_name" => $ATTACHMENT_NAME,//附件名称
        "author_user_id"  => $AUTHOR_USER_ID //作者
        );    
    }
    return $dataBack;		
}
// 关键词查询 只获取标题
function get_diaryselect_data($USER_ID,$TYPE,$WHERE_STR="",$IS_MAIN="",$DIA_DATE_DIARY="")
{
    $dataBack = array(); 
    $PER_PAGE=10;
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    
    if($TYPE==1 || $TYPE==3)
    {
        $query = "SELECT SUBJECT from DIARY where 1=1 ".$WHERE_STR." order by DIA_TIME desc limit 0,10";
    }
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    while($ROW=mysql_fetch_array($cursor))
    {  
        $SUBJECT    = $ROW["SUBJECT"];
        $dataBack[] = $SUBJECT;  //主题    
    }
    return $dataBack;
}
//删除日志
function del_diary($DIA_ID)
{
    $query_attach  = "SELECT ATTACHMENT_ID,ATTACHMENT_NAME FROM DIARY WHERE DIA_ID = '$DIA_ID'";
    $cursor_attach = exequery(TD::conn(),$query_attach);
    while($ROW=mysql_fetch_array($cursor_attach))
    {
        $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
        
        if($ATTACHMENT_ID!="")
        {
            delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
        }
    }
    $query_comment  = "SELECT COMMENT_ID FROM DIARY_COMMENT WHERE DIA_ID='$DIA_ID'";
    $cursor_comment = exequery(TD::conn(),$query_comment);
    while($ROW_COMMENT=mysql_fetch_array($cursor_comment))
    {	
        $comment_str .= $ROW_COMMENT["COMMENT_ID"].",";
    }
    $comment_str = td_trim($comment_str);    
    $query       = "delete from DIARY where DIA_ID = '$DIA_ID' ";
    exequery(TD::conn(),$query);
    if(mysql_affected_rows() > 0)
    {
        $STATE  = "ok";
        $querys = "delete from DIARY_COMMENT where DIA_ID = '$DIA_ID'";
        exequery(TD::conn(),$querys);	
        if($comment_str!="")
        { 
            $query_reply = "delete from DIARY_COMMENT_REPLY where COMMENT_ID in ($comment_str) ";	    
            exequery(TD::conn(),$query_reply);
        }
    }
    else
    {
        $STATE = "error";	
    }
    return $STATE;
}

//点评
function add_comment($DIA_ID,$SMS_REMIND,$SMS2_REMIND,$CONTENT)
{
    $query  = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $SUBJECT1       = $ROW["SUBJECT"];
        $NOTAGS_CONTENT = $ROW["CONTENT"];    
        $CONTENT1       = @gzuncompress($ROW["COMPRESS_CONTENT"]);
        if($CONTENT1=="")
		{
			$CONTENT1 = $NOTAGS_CONTENT; 
		}      
        $DIA_DATE = $ROW["DIA_DATE"];
        $DIA_DATE = strtok($DIA_DATE," ");
        if($SUBJECT1=="")
		{
			$SUBJECT1=csubstr(strip_tags($CONTENT1),0,50).(strlen($CONTENT1)>50?"...":"");
		}    
        $DIA_USER = $ROW["USER_ID"];
    }
    $SEND_TIME = date("Y-m-d H:i:s",time());
    $query     = "insert into DIARY_COMMENT (DIA_ID,USER_ID,CONTENT,SEND_TIME) values($DIA_ID,'".$_SESSION["LOGIN_USER_ID"]."','$CONTENT','$SEND_TIME')";
    exequery(TD::conn(),$query);
    $com_id=mysql_insert_id();
    if(mysql_affected_rows() > 0) 
    {
        $STATE = array(
        'flag' => 'ok',
        'data' => array(
            'id' => $com_id
            )
        );
    }
    else
    {
        $STATE = array(
        'flag' => "error"	
        );
    }
    $query = "update DIARY set LAST_COMMENT_TIME='$SEND_TIME' where DIA_ID='$DIA_ID'";
    exequery(TD::conn(),$query);
    $REMIND_URL  = "diary/show_diary.php?dia_id=".$DIA_ID;
    $CONTENT     = strip_tags($CONTENT);
    $MSG         = sprintf(_("%s对您 %s 的工作日志“%s”进行了评论，评论内容：%s"), $_SESSION["LOGIN_USER_NAME"],$DIA_DATE,$SUBJECT1,$CONTENT);
    $SMS_CONTENT = $MSG;
    if($SMS_REMIND=="on")
	{
		send_sms("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,13,$SMS_CONTENT,$REMIND_URL,$DIA_ID);
	}
	  
    if($SMS2_REMIND=="on")
	{
		$sql="SELECT MOBIL_NO FROM user,diary WHERE diary.USER_ID = user.USER_ID AND diary.DIA_ID = '$DIA_ID' ";
		$cursor   = exequery(TD::conn(),$sql);
		if($ROW=mysql_fetch_array($cursor))
		{
			$MOBIL_NO = $ROW[0];
		}
		if($MOBIL_NO)
		{
			send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,$SMS_CONTENT,13);
		}	
	}
    return  $STATE;
}

//回复评论
function add_comment_reply($DIA_ID,$AU_ID,$COMMENT_ID,$TO_ID,$SMS_REMIND,$SMS2_REMIND,$REPLY_COMMENT)
{
    $CUR_TIME = date("Y-m-d H:i:s",time());
    $query    = "insert into DIARY_COMMENT_REPLY(REPLY_COMMENT,REPLY_TIME,REPLYER,COMMENT_ID,TO_ID) values ('$REPLY_COMMENT','$CUR_TIME','".$_SESSION["LOGIN_USER_ID"]."','$COMMENT_ID','$TO_ID')";
    exequery(TD::conn(),$query);
    $reply_id=mysql_insert_id();
    if(mysql_affected_rows() > 0) 
    {
        $STATE = array(
        'flag' => 'ok',
        'data' => array(
            'id' => $reply_id
            )
        );
    }
    else
    {
        $STATE = array(
        'flag' => "error"	
        );	
    }
    $REMIND_URL       = "diary/show_diary.php?dia_id=".$DIA_ID;
    $SMS_CONTENT      = $_SESSION["LOGIN_USER_NAME"]._("对您的日志进行了回复，回复内容：").$REPLY_COMMENT;
    $SMS_CONTENT_TOAU = $_SESSION["LOGIN_USER_NAME"]._("对您的日志进行了回复，回复内容：").$REPLY_COMMENT;	
    $SMS_CONTENT_TO   = $_SESSION["LOGIN_USER_NAME"]._("对您的评论进行了回复，回复内容：").$REPLY_COMMENT;
	
    if($SMS_REMIND=="on")
    {
        if($TO_ID==$AU_ID)
        {    
            send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID,13,$SMS_CONTENT,$REMIND_URL,$DIA_ID); //发给回复人
        }
        else
        {
			send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID,13,$SMS_CONTENT_TO,$REMIND_URL,$DIA_ID); //发给回复人
			send_sms("",$_SESSION["LOGIN_USER_ID"],$AU_ID,13,$SMS_CONTENT_TOAU,$REMIND_URL,$DIA_ID); //发给日志作者
        }
    }
    if($SMS2_REMIND=="on")
    {
		$sql="SELECT MOBIL_NO FROM user WHERE USER_ID = '$TO_ID'";
		$cursor   = exequery(TD::conn(),$sql);
		if($ROW=mysql_fetch_array($cursor))
		{
			$TO_MOBIL_NO = $ROW[0];
		}
		
		$sql1="SELECT MOBIL_NO FROM user WHERE USER_ID = '$AU_ID'";
		$cursor1   = exequery(TD::conn(),$sql1);
		if($arr=mysql_fetch_array($cursor1))
		{
			$AU_MOBIL_NO = $arr[0];
		}
		
        if($TO_ID==$AU_ID && $TO_MOBIL_NO!="" && $AU_MOBIL_NO != "")
        {    
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$TO_ID,$SMS_CONTENT,13);
        }
        else
        {
			if($TO_MOBIL_NO)
            	send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$TO_ID,$SMS_CONTENT_TO,13); //发给回复人
			if($AU_MOBIL_NO)
            	send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$AU_ID,$SMS_CONTENT_TOAU,13);  //发给日志作者
        }    
    }
    return  $STATE;
}
// 删除评论
function del_comment($TYPE,$ID)
{
    if($TYPE=="sub")//删除回复
    {
        $query = "delete from DIARY_COMMENT_REPLY where REPLY_ID='$ID'";
    }
    else
    {
        $query = "delete from DIARY_COMMENT where COMMENT_ID='$ID'";
    }
    exequery(TD::conn(),$query);
    if(mysql_affected_rows() > 0) 
    {
        $STATE  = "ok";
        $querys = "delete from DIARY_COMMENT_REPLY where COMMENT_ID='$ID'";
        exequery(TD::conn(),$querys);
    }
    else
    {
        $STATE = "error";	
    }	
    return  $STATE;
}
//设置共享
function set_share($DIA_ID,$TO_ID)
{
    $query  ="update DIARY set TO_ID='$TO_ID' where DIA_ID='$DIA_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($cursor === false) 
    {
        //$STATE= "ok";
        $STATE = "error";	
    }
    else
    {
        //$STATE= "error";
        $STATE = "ok";	
    }	
    return  $STATE;	
} 

//获取共享人员
function get_share($DIA_ID)
{
    $dataBack = array();
    $query    = "SELECT TO_ID FROM DIARY WHERE DIA_ID='$DIA_ID'";	
    $cursor   = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TO_ID = $ROW["TO_ID"];
    }
    $TO_ID = td_trim($TO_ID);
    if($TO_ID!="")
    {	
        $toArry = explode(",",$TO_ID);
        
        for($i=0;$i<count($toArry);$i++)
        {
            $value = "";
            $text  = "";
            if($toArry[$i]=="")
			{
				continue;
			} 
            $value  = $toArry[$i];
            $querys = "SELECT USER_NAME FROM USER WHERE USER_ID='$value'";
            $cursor = exequery(TD::conn(),$querys);
            if($ROWS=mysql_fetch_array($cursor))
            {
                $text =	$ROWS["USER_NAME"];
            }
            if($text!= ""){
            	$dataBack[] = array(
	            "value" => $value,
	            "text"  => $text
	            );   
            }      
        }		
    }
    return $dataBack;
}
//获取阅读人员
function get_readers($DIA_ID)
{
    $dataBack = array();
    $query    = "SELECT READERS FROM DIARY WHERE DIA_ID='$DIA_ID'";	
    $cursor   = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $READERS = $ROW["READERS"];
    }
    $READERS = td_trim($READERS);
    if($READERS!="")
    {	
        $toArry = explode(",",$READERS);
        
        for($i=0;$i<count($toArry);$i++)
        {
            $value = "";
            $text  = "";
            if($toArry[$i]=="")
			{
				continue;
			}  
            $value  = $toArry[$i];
            $querys = "SELECT USER_NAME FROM USER WHERE USER_ID='$value'";
            $cursor = exequery(TD::conn(),$querys);
            if($ROWS=mysql_fetch_array($cursor))
            {
                $text =	$ROWS["USER_NAME"];
            }
            if($text!= ""){
            	$dataBack[] = array(
	            "value" => $value,
	            "text"  => $text
	            );   
            }       
        }		
    }
    return $dataBack;
}
//获取最新点评
function get_new_comment($USER_ID)
{
    $dataBack = array();
    $query    = "select a.DIA_ID,a.SUBJECT,a.DIA_DATE,b.USER_ID as COMMENTER from DIARY as a,DIARY_COMMENT as b where a.DIA_ID=b.DIA_ID and a.USER_ID ='".$USER_ID."' and a.LAST_COMMENT_TIME!='0000-00-00 00:00:00'  group by a.DIA_ID order by a.LAST_COMMENT_TIME desc  limit 0,5";
    $cursor = exequery(TD::conn(),$query);	
    $COUNT  = 0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $DIA_ID         = $ROW["DIA_ID"];
        $SUBJECT        = $ROW["SUBJECT"];
        $COMMENTER      = $ROW["COMMENTER"];
        $COMMENTER_NAME = td_trim(GetUserNameById($COMMENTER));
        $MORE_URL       = "show_diary.php?dia_id=".$DIA_ID;    
        $dataBack[]     = array(
        "dia_id"         => $DIA_ID,     //日志主ID
        "subject"        => $SUBJECT,   //主题
        "commenter"      => $COMMENTER, //评论
        "commenter_name" => $COMMENTER_NAME,//评论作者
        "url"            => $MORE_URL
        );
    }
    return $dataBack;
}

//判断用户是否有查看日志的权限
function is_read($UID,$DIA_ID,$DIARY_COPY_TIME)
{
    $STATE = 0;
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME = "DIARY"; 
    }
    $query  = "SELECT USER_ID,TO_ID,DIA_TYPE,TO_ALL FROM ".$DIARY_TABLE_NAME." WHERE DIA_ID='$DIA_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $USER_ID  = $ROW["USER_ID"];
        $TO_ID    = $ROW["TO_ID"];
        $DIA_TYPE = $ROW["DIA_TYPE"];
        $TO_ALL   = $ROW["TO_ALL"];
    }
    if($UID==$USER_ID)
    {
        $STATE = 1;
    }
    else if((find_id($TO_ID,$UID) || $TO_ALL=="1") )
    {
        $STATE = 1;
    }
    else
    {
        include_once("check_priv.inc.php");
        $querys = "SELECT DIA_ID
            FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where DIA_ID='$DIA_ID' and DIA_TYPE!=2 ".$WHERE_STRS;
        $cursors = exequery(TD::conn(),$querys);
        if(mysql_num_rows($cursors)>0)
        {
            $STATE = 1;
        }		
    }
    return $STATE;
}
//获取日志查询信息
function get_search_data($USER_ID,$TYPE,$start,$end,$PARA_ARRAY,$WHERE_STR="",$IS_MAIN="",$DIARY_COPY_TIME="",$keyword="",$user="",$WHERE_STRS="")
{
    $dataBack  = array(); 
    $dataBacks = array(); 
    $PER_PAGE  = 10;
    $num_count = 0;
    if($IS_MAIN==1)//主从服务器查询
    {
        $QUERY_MASTER = TRUE;	
    }
    else
    {
        $QUERY_MASTER = FALSE;	
    }
    if($DIARY_COPY_TIME!="")//是否归档库查询
    {
        $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$DIARY_COPY_TIME;
        $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$DIARY_COPY_TIME;
        $DIARY_TOP_TABLE_NAME     = TD::$_arr_db_master['db_archive'].".DIARY_TOP".$DIARY_COPY_TIME;
    }
    else
    {
        $DIARY_TABLE_NAME         = "DIARY"; 
        $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT";
        $DIARY_TOP_TABLE_NAME     = "DIARY_TOP";

    }
    
    if($keyword!="")
    {
        $WHERE_STR .= " AND (SUBJECT like '%$keyword%'  or CONTENT like '%$keyword%' or ".$DIARY_TABLE_NAME.".USER_ID like '%$keyword%')";	
    }
    if($user!="" && ($TYPE==2 || $TYPE==4 || $TYPE==0))
    {
        if(substr($user,-1,1)==",")
        {
            $user = substr($user,0,-1); 
        } 
        $WHERE_STR .= " AND  find_in_set(".$DIARY_TABLE_NAME.".USER_ID ,'$user')";
    }
    
    
    if($USER_ID!="")//查询某用户日志
    {
        if($TYPE==1 || $TYPE==3)
        {
            $query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,CONTENT,".$DIARY_TABLE_NAME.".USER_ID,t.TOP_ID as TOP_ID from ".$DIARY_TABLE_NAME." LEFT JOIN ".$DIARY_TOP_TABLE_NAME." as t on t.DIA_ID =  ".$DIARY_TABLE_NAME.".DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1 ".$WHERE_STR." order by TOP_ID DESC,DIA_TIME DESC limit ".$start.",".$end;
			
            $query_count = "SELECT count(*) from ".$DIARY_TABLE_NAME." where 1=1 ".$WHERE_STR." order by DIA_TIME desc";
			
        }
        else if($TYPE==2 || $TYPE==4)
        {
            
            $WHERE_STR .= " AND ".$DIARY_TABLE_NAME.".USER_ID!='$USER_ID' ";
			
            $query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".USER_ID,t.TOP_ID as TOP_ID
                FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV LEFT JOIN ".$DIARY_TOP_TABLE_NAME." as t on t.DIA_ID =  ".$DIARY_TABLE_NAME.".DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1".$WHERE_STR." ORDER BY TOP_ID DESC,DIA_TIME DESC LIMIT ".$start.",".$end;
				
            $query_count = "SELECT count(*)
                FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1".$WHERE_STR." ORDER BY DIA_TIME DESC ";
				
        }
        else if($TYPE==0)
        {
            $WHERE_STR .= " and (".$WHERE_STRS."  or ".$DIARY_TABLE_NAME.".USER_ID='".$USER_ID."' or (find_in_set('".$USER_ID."',TO_ID) || TO_ALL= '1'))";
            $query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".USER_ID,t.TOP_ID as TOP_ID              FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV LEFT JOIN ".$DIARY_TOP_TABLE_NAME." as t on t.DIA_ID =  ".$DIARY_TABLE_NAME.".DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1".$WHERE_STR." ORDER BY TOP_ID DESC,DIA_TIME DESC LIMIT ".$start.",".$end;
				
            $query_count = "SELECT count(*)
                FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1".$WHERE_STR." ORDER BY DIA_TIME DESC ";
					
        }
        $cursor       = exequery(TD::conn(),$query,$QUERY_MASTER);
        $cursor_count = exequery(TD::conn(),$query_count,$QUERY_MASTER);
        if($ROW_COUNT = mysql_fetch_array($cursor_count))
        {
            $num_count = $ROW_COUNT[0];
        }
        $curpage   = ($start/$PER_PAGE) +1;
        $totalpage = ceil($num_count/$PER_PAGE);
        
        $code_arr = array();
        $query0 = "SELECT CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='DIARY_TYPE'";
        $cursor0= exequery(TD::conn(),$query0);
        while($row0 = mysql_fetch_array($cursor0))
        {
            $CODE_NO        = $row0["CODE_NO"];
            $CODE_NAME      = $row0["CODE_NAME"];
            $CODE_EXT       = unserialize($row0["CODE_EXT"]);
            if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
                $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

            $code_arr[$CODE_NO] = $CODE_NAME;
        }
        
        while($ROW = mysql_fetch_array($cursor))
        {                  
            $DIA_ID           = $ROW["DIA_ID"];            
            $DIA_DATE         = $ROW["DIA_DATE"];
            $DIA_DATE         = strtok($DIA_DATE," ");
            $DIA_TYPE         = $ROW["DIA_TYPE"];
            $SUBJECT          = $ROW["SUBJECT"];
            $AUTHOR_USER_ID   = $ROW["USER_ID"];
            $DIA_TIME         = strtotime($ROW["DIA_TIME"]);
            $COMPRESS_CONTENT = $ROW["COMPRESS_CONTENT"];
            $NOTAGS_CONTENT   = $ROW['CONTENT'];
			$TOP_ID           = $ROW['TOP_ID'];
            
            $query_user  = "SELECT AVATAR FROM USER WHERE USER_ID='$AUTHOR_USER_ID'";
            $cursor_user = exequery(TD::conn(),$query_user);
            if($ROW_USER = mysql_fetch_array($cursor_user))
            {
                $AVATAR = $ROW_USER["AVATAR"];
            }
            
            if($DIA_TYPE!="")
            {
                $DIA_TYPE_NAME = $code_arr[$DIA_TYPE];
            }
            else
            {
                $DIA_TYPE_NAME = "";
            }
            
            if($COMPRESS_CONTENT == "")
            {
                $CONTENT = $NOTAGS_CONTENT;
            }
            else
            {
                $CONTENT = @gzuncompress($COMPRESS_CONTENT);
                if($CONTENT===FALSE)
				{
					$CONTENT = $NOTAGS_CONTENT;
				}       
            }
            $CONTENT_LEN = strlen($CONTENT);
            if($CONTENT_LEN>'500')
            {    
                $CONTENT_S    = csubstr($CONTENT,0,500)."...";
                $flagReadMore = 1;
            }
            else
            {
                $CONTENT_S    = $CONTENT;
                $flagReadMore = 0;	
            }
            $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
            $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"]; 
            if(strlen($SUBJECT)>'100')
			{
				$SUBJECT = csubstr($SUBJECT,0,100);
			}    
            if($ATTACHMENT_ID && $ATTACHMENT_NAME!="")
			{
				$HAS_ATTACHMENT = 1;
			}    
            else
			{
				$HAS_ATTACHMENT = 0;
			}
            //附件信息
            $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
            if($ATTACH_ARRAY["NAME"]!="")
            {
                $ATTACHMENTS = str_replace("<br>","",attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1));
                $ATTACHMENTS = str_replace("</br>","",$ATTACHMENTS);
            }
            else
            {
                $ATTACHMENTS = "";
            }
            //判断是否有点评及点评数量
            $querys  = "SELECT count(DIA_ID) from ".$DIARY_COMMENT_TABLE_NAME." where DIA_ID='$DIA_ID'";
            $cursors = exequery(TD::conn(),$querys,TRUE);
            if($ROWS = mysql_fetch_array($cursors))
            {
                $COMMENT_COUNT = $ROWS[0];
            }	
            else
            {
                $COMMENT_COUNT = 0;
            }
            if($AUTHOR_USER_ID!="")
            {
                //作者信息
                if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID)
                {
                    $AUTHOR_USER_NAME = $_SESSION["LOGIN_USER_NAME"];
                    $AUTHOR_DEPT_ID   = $_SESSION["LOGIN_DEPT_ID"];
                    if($AUTHOR_DEPT_ID==0)
                    {
                        $AUTHOR_DEPT_NAME       = _("离职人员/外部人员");
                        $AUTHOR_SHORT_DEPT_NAME = _("离职人员/外部人员");
                    }
                    else
                    {
                        $AUTHOR_DEPT_NAME       = dept_long_name($AUTHOR_DEPT_ID);
                        $AUTHOR_SHORT_DEPT_NAME = dept_name($AUTHOR_DEPT_ID);
                    }
                    $AUTHOR_PRIV_NAME = td_trim(GetPrivNameById($_SESSION["LOGIN_USER_PRIV"]));		
                }
                else
                {
                    $USER_ARRAY             = get_user_infos($AUTHOR_USER_ID);
                    $AUTHOR_USER_NAME       = $USER_ARRAY["USER_NAME"];
                    $AUTHOR_DEPT_NAME       = $USER_ARRAY["DEPT_NAME"];
                    $AUTHOR_PRIV_NAME       = $USER_ARRAY["USER_PRIV_NAME"];
                    $AUTHOR_SHORT_DEPT_NAME = $USER_ARRAY["SHORT_DEPT_NAME"];
                } 
            }
            
            if($AVATAR=="0" || $AVATAR=="1")
            {		    
                $AUTHOR_PHOTO = photo_path($AUTHOR_USER_ID);	
            }
            else
            {
                $AUTHOR_PHOTO = avatar_path($AVATAR);
            }		
            //操作权限
            if(is_array($PARA_ARRAY))
            {
                while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
				{
					$$PARA_NAME = $PARA_VALUE;
				}  
                if(is_string($LOCK_TIME) && $LOCK_TIME!="")
                {
                    $LOCK_TIME = explode(",",$LOCK_TIME);
                    $W_START   = $LOCK_TIME[0];
                    $W_END     = $LOCK_TIME[1];
                    $DAYS      = intval($LOCK_TIME[2]);
                }
                $EDIT_FLAG  = 0;
                $SHARE_FLAG = 0;
                $DEL_FLAG   = 0;
                $REPLY_FLAG = 0;
				$TOP_FLAG   = 0;//置顶状态yc
            }
			if($TOP_ID!="")
			{
				$TOP_FLAG=1;
			}
            if($LOCK_SHARE==1 && $DIARY_COPY_TIME=="")
            {
                $SHARE_FLAG=1;
            }
            if($LOCK_SHARE==0 && $DIARY_COPY_TIME=="")
            {
                //不再锁定日期内
                if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $_SESSION["LOGIN_USER_PRIV"]!=1)
                    {
                        $SHARE_FLAG = is_share($_SESSION["LOGIN_UID"],$AUTHOR_USER_ID);
                    }
                    else
                    {
                        if($DIA_TYPE!=2)
                        {
                            $SHARE_FLAG = 1;
                        }
                    }
                    
                }
            }
            if($_SESSION["LOGIN_USER_ID"]==$AUTHOR_USER_ID && $DIARY_COPY_TIME=="") //自己的日志
            {
                //不再锁定日期内
                if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    $EDIT_FLAG  = 1; //编辑
                    $REPLY_FLAG = 1; //点评
                }
                $DEL_FLAG = 1;            
            }
            if($_SESSION["LOGIN_USER_ID"]!=$AUTHOR_USER_ID && $DIARY_COPY_TIME=="") //其它人的日志
            {
                //不再锁定日期内
                if(($DAYS==0 || date("Y-m-d",strtotime("+".$DAYS."day",strtotime($DIA_DATE)))>date("Y-m-d",time())) && ($W_START=="" && $W_END=="" || $W_START=="" && $W_END!="" && compare_date($DIA_DATE,$W_END)==1 || $W_START!="" && $W_END=="" && compare_date($W_START,$DIA_DATE)==1 || $W_START!="" && $W_END!="" && (compare_date($DIA_DATE,$W_END)==1 || compare_date($W_START,$DIA_DATE)==1)))
                {
                    if($IS_COMMENTS==1 )
                        $REPLY_FLAG= 1; //点评
                }	
            }
            
            if($AUTHOR_USER_ID==$_SESSION["LOGIN_USER_ID"])
			{
				$flagPrivate = 1;
			}   
            else
			{
				$flagPrivate = 0;
			}   
            //系统管理员的特殊权限
            /*if($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                $DEL_FLAG=1;
            }*/
            $MORE_URL   ="show_diary.php?dia_id=".$DIA_ID."&diary_copy_time=".$DIARY_COPY_TIME;    
            $dataBack[] = array(
            "dia_id"                 => $DIA_ID,     //日志ID
            "dia_date"               => $DIA_DATE, //创建日期
            "dia_time"               => $DIA_TIME, //创建时间
            "subject"                => $SUBJECT,   //主题
            "dia_type"               => $DIA_TYPE_NAME, //日志类型
            "has_attachment"         => $HAS_ATTACHMENT,//是否有附件
            "attachments"            => $ATTACHMENTS,//附件内容
            "comment_count"          => $COMMENT_COUNT,  //点评数量
            "author_user_id"         => $AUTHOR_USER_ID, //作者
            "author_user_name"       => $AUTHOR_USER_NAME,//作者名称
            "author_dept_name"       => $AUTHOR_DEPT_NAME,//作者部门名称
            "author_short_dept_name" => $AUTHOR_SHORT_DEPT_NAME,//作者短部门名称
            "author_priv_name"       => $AUTHOR_PRIV_NAME,//作者角色名称
            "author_photo"           => $AUTHOR_PHOTO,//作者头像
            "edit_flag"              => $EDIT_FLAG,//编辑权限
            "del_flag"               => $DEL_FLAG,//删除权限
			"top_flag"               => $TOP_FLAG, //是否置顶
            "share_flag"             => $SHARE_FLAG,//设置共享范围权限
            "reply_flag"             => $REPLY_FLAG,//点评权限
            "flagReadMore"           => $flagReadMore,//内容是否缩写
            "flagPrivate"            => $flagPrivate, //是否个人
            "more_url"               => $MORE_URL,
            "content_all"            => htmlFilter($CONTENT)
            );
            
        }
        $dataBacks=array("curpage" => $curpage,"totalpage" => $totalpage,"datalist" => $dataBack);
    }    
    return $dataBacks;
}
//时间转换
function get_time($SEND_TIME)
{
    $time        = time();
    $send_time_u = strtotime($SEND_TIME);
    $time_com    = $time-$send_time_u;
    $time_str    = "";
    if($time_com < 0)
    {
        $time_str = "";
    }
    else
    {
        if($time_com < (60))
        {
            $time_str = round($time_com)._("秒前");	
        }
        else
        {
            if($time_com < (60 * 60 ))
            {
                $time_str = round($time_com / 60 ). _("分钟前");	
            }
            else
            {
                if($time_com < (24 * 60 * 60 ))
                {
                    if(date("Y-m-d",$send_time_u) == date("Y-m-d"))
                    {
                        $time_str = _("今天 ").date("H:i",$send_time_u);                                 
                    }
                    else
                    {
                        $time_str = date("n",$send_time_u)._("月").date("d",$send_time_u)._("日")." ".date("H:i",$send_time_u);          
                    }
                }
                else
                {
                    if($time_com < (365 * 24 * 60 * 60 ))
                    {
                        if(date("Y",$send_time_u) ==date("Y"))
                        {
                            $time_str = date("n",$send_time_u)._("月").date("d",$send_time_u)._("日")." ".date("H:i",$send_time_u); 
                        }
                        else
                        {
                           $time_str = date("Y-n-j H:i",$send_time_u);
                        }
                    }
                    else
                    {
                        $time_str = date("Y-n-j H:i",$send_time_u);
                    }	
                }
            }
        }
    }	
	return $time_str;	
}

//根据日志信息获取所有有权限的人员uid，array(uid/dept_id_other/priv_id_str)不包含本人
function GetUidsByDiaryId($s_uid)
{
    $uid_str = "";
    $s_dept_id_str = "";
    $s_priv_id_str = "";
    
    //获取用户数据
    $user_info_arr = array();
    
    $query = "select UID,USER_ID,DEPT_ID,DEPT_ID_OTHER,USER_PRIV,USER_PRIV_OTHER,USER_PRIV_NO from user ORDER BY UID";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
    {
        $user_info_arr[$row['UID']] = $row;
        
        if($row['UID'] == $s_uid)
        {
            $s_dept_id_str = $row['DEPT_ID'].",".$row['DEPT_ID_OTHER'].",";
            $s_priv_id_str = $row['USER_PRIV'].",".$row['USER_PRIV_OTHER'].",";
        }
    }
    
    if(td_trim($s_dept_id_str)=="" || td_trim($s_priv_id_str)=="")
    {
        return "";
    }
    
    $owner_dept_arr = explode(',', $s_dept_id_str);
    $owner_priv_arr = explode(',', $s_priv_id_str);
    
    //获取角色序号
    $priv_info_arr = array();
    $query = "select USER_PRIV,PRIV_NO from user_priv ORDER BY USER_PRIV";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
    {
        $priv_info_arr[$row['USER_PRIV']] = $row['PRIV_NO'];
    }
    
    //按模块设置管理范围-工作日志查询
    $user_module_priv_arr = array();
    $query_p = "SELECT * from MODULE_PRIV where MODULE_ID='4'";   
    $cursor_p = exequery(TD::conn(), $query_p);
    while($row_p = mysql_fetch_array($cursor_p))
    {
        $uid            =$row_p["UID"];
        $dept_priv      =$row_p["DEPT_PRIV"];
        $role_priv      =$row_p["ROLE_PRIV"];
        $dept_id_str    =$row_p["DEPT_ID"];
        $priv_id_str    =$row_p["PRIV_ID"];
        $user_id_str    =$row_p["USER_ID"];
        
        $user_module_priv_arr[$uid]['uid']   = $uid;
        $user_module_priv_arr[$uid]['dept_priv']   = $dept_priv;
        $user_module_priv_arr[$uid]['role_priv']   = $role_priv;
        $user_module_priv_arr[$uid]['dept_id_str'] = $dept_id_str;
        $user_module_priv_arr[$uid]['priv_id_str'] = $priv_id_str;
        $user_module_priv_arr[$uid]['user_id_str'] = $user_id_str;
    }
    
    foreach($user_module_priv_arr as $k1 => $v1)
    {
        //获取低角色或同级角色id串
        $low_priv_str = "";
        $mid_priv_str = "";
        foreach($priv_info_arr as $k2 => $v2)
        {
            if($priv_info_arr[$user_info_arr[$v1['uid']]['USER_PRIV']]<$v2)
            {
                $low_priv_str .= $k2.",";
                continue;
            }
            else if($priv_info_arr[$user_info_arr[$v1['uid']]['USER_PRIV']]==$v2)
            {
                $mid_priv_str .= $k2.",";
            }
            
            if($user_info_arr[$v1['uid']]['USER_PRIV_OTHER'])
            {
                $priv_other_arr = explode(',', $user_info_arr[$v1['uid']]['USER_PRIV_OTHER']);
                
                foreach($priv_other_arr as $k3 => $v3)
                {
                    if($priv_info_arr[$v3]<$v2)
                    {
                        $low_priv_str .= $k2.",";
                        break;
                    }
                    else if($priv_info_arr[$v3]==$v2)
                    {
                        $mid_priv_str .= $k2.",";
                    }
                }
            }
        }
        $mid_priv_str .= $low_priv_str;
        
        $user_dept_arr = explode(',', $user_info_arr[$v1['uid']]['DEPT_ID'].",".$user_info_arr[$v1['uid']]['DEPT_ID_OTHER']);
        $low_priv_arr = explode(',', $low_priv_str);
        $mid_priv_arr = explode(',', $mid_priv_str);
        $is_dept_in = td_trim(implode(',', array_intersect($user_dept_arr, $owner_dept_arr)));
        $is_low_priv_in = td_trim(implode(',', array_intersect($low_priv_arr, $owner_priv_arr)));
        $is_mid_priv_in = td_trim(implode(',', array_intersect($mid_priv_arr, $owner_priv_arr)));
        
        if($v1['dept_priv']=='0' && $is_dept_in && !find_id($uid_str, $v1['uid']))
        {
            //根据人员角色
            if($v1['role_priv']=='0' && $is_low_priv_in)
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='1' && $is_mid_priv_in)
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='2')
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='3')
            {
                $app_priv_arr = explode(',', $v1['priv_id_str']);
                $is_app_priv_in = td_trim(implode(',', array_intersect($app_priv_arr, $owner_priv_arr)));
                
                if($is_app_priv_in)
                {
                    $uid_str .= $v1['uid'].",";
                    continue;
                }
            }
        }
        else if($v1['dept_priv']=='1' && !find_id($uid_str, $v1['uid']))
        {
            //根据人员角色
            if($v1['role_priv']=='0' && $is_low_priv_in)
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='1' && $is_mid_priv_in)
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='2')
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
            else if($v1['role_priv']=='3')
            {
                $app_priv_arr = explode(',', $v1['priv_id_str']);
                $is_app_priv_in = td_trim(implode(',', array_intersect($app_priv_arr, $owner_priv_arr)));
                
                if($is_app_priv_in)
                {
                    $uid_str .= $v1['uid'].",";
                    continue;
                }
            }
        }
        else if($v1['dept_priv']=='2' && !find_id($uid_str, $v1['uid']))
        {
            $app_dept_arr = explode(',', $v1['dept_id_str']);
            $is_app_dept_in = td_trim(implode(',', array_intersect($app_dept_arr, $owner_dept_arr)));
            
            if($is_app_dept_in)
            {
                //根据人员角色
                if($v1['role_priv']=='0' && $is_low_priv_in)
                {
                    $uid_str .= $v1['uid'].",";
                    continue;
                }
                else if($v1['role_priv']=='1' && $is_mid_priv_in)
                {
                    $uid_str .= $v1['uid'].",";
                    continue;
                }
                else if($v1['role_priv']=='2')
                {
                    $uid_str .= $v1['uid'].",";
                    continue;
                }
                else if($v1['role_priv']=='3')
                {
                    $app_priv_arr = explode(',', $v1['priv_id_str']);
                    $is_app_priv_in = td_trim(implode(',', array_intersect($app_priv_arr, $owner_priv_arr)));
                    
                    if($is_app_priv_in)
                    {
                        $uid_str .= $v1['uid'].",";
                        continue;
                    }
                }
            }
        }
        else if($v1['dept_priv']=='3' && !find_id($uid_str, $v1['uid']))
        {
            $app_uid_str = GetUidByUserID($v1['user_id_str']);
            if(find_id($app_uid_str, $s_uid))
            {
                $uid_str .= $v1['uid'].",";
                continue;
            }
        }
    }
    
    return $uid_str;
}
?>