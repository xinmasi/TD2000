<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");


$keyword   = td_iconv($keyword, "utf-8", MYOA_CHARSET);
$dataBack  = array();
$dataBacks = array();
$PER_PAGE  = 10;
$num_count = 0;
$thisPage  = 0;
$curPage   = ($curPage-1)*$pageLimit;
//用户查询
if($type =='user')
{
	$query       = "SELECT * FROM user,user_priv,department WHERE  department.DEPT_ID = user.DEPT_ID and user.USER_PRIV = user_priv.USER_PRIV and user.NOT_LOGIN = 0";
	$query_count = "SELECT count(*) FROM user,user_priv,department WHERE  department.DEPT_ID = user.DEPT_ID and user.USER_PRIV = user_priv.USER_PRIV and user.NOT_LOGIN = 0";
	if($keyword!="")
	{
		$query       .= " and (USER_NAME like '%$keyword%' or BYNAME like '%$keyword%' or USER_ID like '%$keyword%')";
		$query_count .= " and (USER_NAME like '%$keyword%' or BYNAME like '%$keyword%' or USER_ID like '%$keyword%')";
	}
	$query .= " ORDER BY DEPT_NO,PRIV_NO,USER_NO,USER_NAME limit ".$curPage.",".$pageLimit;
	$cursor       = exequery(TD::conn(),$query);
	$cursor_count = exequery(TD::conn(),$query_count);
	if($ROW_COUNT=mysql_fetch_array($cursor_count))
	{
		$num_count = $ROW_COUNT[0];
	}
	$thisPage   = ($curPage/$pageLimit) +1;
	$totalpage = ceil($num_count/$pageLimit);
	while($ROW=mysql_fetch_array($cursor))
	{
		$UID            = $ROW['UID'];
		$USER_ID        = $ROW['USER_ID'];
		$USER_NAME      = $ROW['USER_NAME'];
		$USER_PRIV_NAME = $ROW['USER_PRIV_NAME'];
		$MY_STATUS      = $ROW['MY_STATUS'];
		$PRIV_NAME      = $ROW['PRIV_NAME'];
		$DEPT_ID        = $ROW['DEPT_ID'];
		$PHOTO          = $ROW['PHOTO'];
		$SEX            = $ROW["SEX"];
		$DEPT_NAME      = $ROW["DEPT_NAME"];
		$OICQ_NO        = $ROW['$OICQ_NO'];
		$ADD_HOME       = $ROW['ADD_HOME'];
		$EMAIL          = $ROW['EMAIL'];
		
		$ROW['NOT_MOBILE_LOGIN'] == 0?$MOBIL_NO=$ROW["MOBIL_NO"]:$MOBIL_NO="";
		$AVATAR_PATH=get_head($PHOTO,$SEX,$USER_ID,"user");
		
		//短信与微讯权限
		$oa_priv = 1;
		$query_oa="SELECT * FROM module_priv WHERE UID = '$UID' AND MODULE_ID = 0";
		$cursor_oa= exequery(TD::conn(),$query_oa);
		if($arr=mysql_fetch_array($cursor_oa))
		{
			$DEPT_ID1 = $arr['DEPT_ID'];//部门
			$PRIV_ID1 = $arr['PRIV_ID'];//角色
			$USER_ID1 = $arr['USER_ID'];//人员
			$PRIV1    = $DEPT_ID1."|".$PRIV_ID1."|".$USER_ID1;
			if(check_priv($PRIV1))
			{
				$oa_priv = 0;
			}
		}
		else
		{
			$oa_priv = 0;
		}
		//关注过滤
		$sql = "SELECT concern_id FROM concern_user WHERE concern_user = '".$_SESSION["LOGIN_USER_ID"]."' AND user_id = '$USER_ID'";
		$cur = exequery(TD::conn(),$sql);
		if(mysql_affected_rows()>0)
		{
			$is_concern = 1;
		}else
		{
			$is_concern = 0;
		}
		$dataBack[] = array(
			'uid'         => $UID,
			'userid'      => td_iconv($USER_ID, MYOA_CHARSET, 'utf-8'),
			'userName'    => td_iconv($USER_NAME, MYOA_CHARSET, 'utf-8'),
			'company'     => '',
			'department'  => td_iconv($DEPT_NAME, MYOA_CHARSET, 'utf-8'),
			'signature'   => td_iconv($MY_STATUS, MYOA_CHARSET, 'utf-8'),
			'qq'          => $OICQ_NO,
			'phoneNum'    => $MOBIL_NO,
			'email'       => $EMAIL,
			'address'     => td_iconv($ADD_HOME, MYOA_CHARSET, 'utf-8'),
			'portraitUrl' => td_iconv($AVATAR_PATH, MYOA_CHARSET, 'utf-8'),
			'isPriv'      => $oa_priv,
			'is_concern'  => $is_concern	
		);	
	}
	$dataBacks = array("curPage" => $thisPage,"totalpage" => $totalpage,"numCount"=>$num_count,"datalist" => $dataBack);
	echo json_encode($dataBacks);
}
//工作流
if($type =='workflow')
{
	$query = "SELECT a.PRCS_ID,a.ID,b.RUN_ID,a.CREATE_TIME,b.FLOW_ID,RUN_PRCS_NAME,PRCS_FLAG,FLOW_PRCS,FLOW_NAME,RUN_NAME,FLOW_TYPE,LIST_FLDS_STR,FORM_ID from FLOW_RUN_PRCS AS a,FLOW_RUN AS b,FLOW_TYPE WHERE a.RUN_ID=b.RUN_ID and b.FLOW_ID=FLOW_TYPE.FLOW_ID and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DEL_FLAG='0' and PRCS_FLAG<>'3' and PRCS_FLAG<>'4' and PRCS_FLAG<>'6' and CHILD_RUN='0' and b.BEGIN_USER= '".$_SESSION["LOGIN_USER_ID"]."'";
	$query_count = "SELECT count(*) from FLOW_RUN_PRCS AS a,FLOW_RUN AS b,FLOW_TYPE WHERE a.RUN_ID=b.RUN_ID and b.FLOW_ID=FLOW_TYPE.FLOW_ID and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DEL_FLAG='0' and PRCS_FLAG<>'3' and PRCS_FLAG<>'4' and PRCS_FLAG<>'6' and CHILD_RUN='0' and b.BEGIN_USER= '".$_SESSION["LOGIN_USER_ID"]."'";
	if($keyword!="")
	{
		$query       .= " and (b.RUN_NAME like '%$keyword%' or b.RUN_ID like '%$keyword%') ";
		$query_count .= " and (b.RUN_NAME like '%$keyword%' or b.RUN_ID like '%$keyword%') ";
	}
	$query .= "GROUP BY a.ID ORDER BY a.CREATE_TIME desc LIMIT ".$curPage.",".$pageLimit;
	$cursor       = exequery(TD::conn(),$query);
	$cursor_count = exequery(TD::conn(),$query_count);
	if($ROW_COUNT=mysql_fetch_array($cursor_count))
	{
		$num_count = $ROW_COUNT[0];
	}
	$thisPage   = ($curPage/$pageLimit) +1;
	$totalpage = ceil($num_count/$pageLimit);
	while($ROW=mysql_fetch_array($cursor))
	{
		$ID          = $ROW['ID'];
		$RUN_ID      = $ROW['RUN_ID'];
		$CREATE_TIME = $ROW['CREATE_TIME'];
		$FLOW_NAME   = $ROW['FLOW_NAME'];
		$RUN_NAME    = $ROW['RUN_NAME'];
		$FLOW_ID     = $ROW['FLOW_ID'];
		$PRCS_ID     = $ROW['PRCS_ID'];
		$FLOW_PRCS   = $ROW['FLOW_PRCS'];

		$url="general/workflow/list/input_form/?menu_flag=&RUN_ID=$RUN_ID&PRCS_KEY_ID=$ID&FLOW_ID=$FLOW_ID&PRCS_ID=$PRCS_ID&FLOW_PRCS=$FLOW_PRCS";
		
		$dataBack[] = array(
			'runId'       => $RUN_ID,
			'flowId'      => $FLOW_ID,
			'runName'     => td_iconv($RUN_NAME, MYOA_CHARSET, 'utf-8'),
			'prcsId'      => $PRCS_ID,
			'url'         => $url		
		);		
	}
	$dataBacks=array("curPage" => $thisPage,"totalpage" => $totalpage,"numCount"=>$num_count,"datalist" => $dataBack);
	echo json_encode($dataBacks);	
}
//通讯簿
if($type =="contacts")
{
	//通讯簿表数据
	$query = "SELECT * FROM address_group WHERE USER_ID='' or USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ORDER BY GROUP_NAME asc";
	$cursor= exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cursor))
	{
		$PRIV_DEPT      = $ROW["PRIV_DEPT"];
		$PRIV_ROLE      = $ROW["PRIV_ROLE"];
		$PRIV_USER      = $ROW["PRIV_USER"];
		$USER_ID        = $ROW["USER_ID"];
		if($PRIV_DEPT!="ALL_DEPT" && $USER_ID=="")
		{
			if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) and !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" and !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
			{
				continue;
			}
		}
		
		$GROUP_ID1     = $ROW["GROUP_ID"];
		
		$GROUP_ID_STR .= $GROUP_ID1.",";
	}//while
	
	$GROUP_ID_STR = $GROUP_ID_STR."0,";
	
	$query       = "SELECT * FROM address WHERE (USER_ID='' or USER_ID='".$_SESSION["LOGIN_USER_ID"]."') AND find_in_set(GROUP_ID,'$GROUP_ID_STR')";
	$query_count = "SELECT count(*) FROM address WHERE (USER_ID='' or USER_ID='".$_SESSION["LOGIN_USER_ID"]."') AND find_in_set(GROUP_ID,'$GROUP_ID_STR')";
	
	if($keyword!="")
	{
		$query        .= " and PSN_NAME like '%$keyword%'";
		$query_count  .= " and PSN_NAME like '%$keyword%'";
	}
	//$query       .= " ORDER BY GROUP_ID,PSN_NAME asc LIMIT ".$curPage.",".$pageLimit;
	$cursor       = exequery(TD::conn(),$query);
	$cursor_count = exequery(TD::conn(),$query_count);
	if($ROW_COUNT=mysql_fetch_array($cursor_count))
	{
		$num_count = $ROW_COUNT[0];
	}
	while($ROW=mysql_fetch_array($cursor))
	{
		$ADD_ID         = $ROW["ADD_ID"];
		$PSN_NAME       = $ROW["PSN_NAME"];
		$SEX            = $ROW["SEX"];
		$DEPT_NAME      = $ROW["DEPT_NAME"];
		$ADD_DEPT       = $ROW['ADD_DEPT'];
		$TEL_NO_DEPT    = $ROW["TEL_NO_DEPT"];
		$MOBIL_NO       = $ROW["MOBIL_NO"];
		$EMAIL          = $ROW["EMAIL"];
		$USER_ID        = $ROW["USER_ID"];
		$OICQ_NO        = $ROW['OICQ_NO'];
		
		if($ATTACHMENT_NAME=="" && $SEX==0)
		{
			$URL_PIC = MYOA_STATIC_SERVER."/static/modules/address/images/man_big.png";
		}
		else if($ATTACHMENT_NAME=="" && $SEX==1)
		{
			$URL_PIC = MYOA_STATIC_SERVER."/static/modules/address/images/w_big.png";
		}
		else
		{
			$URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
			$URL_PIC   = $URL_ARRAY["view"];
		}
		
		
		$query1  = "SELECT * FROM ADDRESS_GROUP WHERE GROUP_ID='$GROUP_ID'";
		$cursor1 = exequery(TD::conn(),$query1);
		if($ROW1=mysql_fetch_array($cursor1))
		{
			$GROUP_NAME = $ROW1["GROUP_NAME"];
		}
		if($GROUP_ID==0)
		{
			$GROUP_NAME = _("默认");
		}
		if($USER_ID=="")
		{
			$GROUP_NAME.=_("(公共)");
		}
		
		$dataBack[] = array(
			'uid'          => $ADD_ID,
			'userid'       => '',
			'uName'        => td_iconv($PSN_NAME, MYOA_CHARSET, 'utf-8'),
			'portraitUrl'  => $URL_PIC,
			'company'      => td_iconv($DEPT_NAME, MYOA_CHARSET, 'utf-8'),
			'department'   => '',
			'qq'           => $OICQ_NO,
			'phoneNum'     => $MOBIL_NO,
			'email'        => $EMAIL,
			'address'      => td_iconv($ADD_DEPT, MYOA_CHARSET, 'utf-8'),
			'groupName'    => td_iconv($GROUP_NAME, MYOA_CHARSET, 'utf-8')
		);	
	}
	//用户表数据
	$sql       = "SELECT * FROM user,user_priv,department WHERE  department.DEPT_ID = user.DEPT_ID and user.USER_PRIV = user_priv.USER_PRIV and user.NOT_LOGIN = 0";
	$sql_count = "SELECT count(*) FROM user,user_priv,department WHERE  department.DEPT_ID = user.DEPT_ID and user.USER_PRIV = user_priv.USER_PRIV and user.NOT_LOGIN = 0";
	if($keyword!="")
	{
		$sql       .= " and USER_NAME like '%$keyword%'";
		$sql_count .= " and USER_NAME like '%$keyword%'";
	}
	//$query .= " ORDER BY DEPT_NO,PRIV_NO,USER_NO,USER_NAME ";
	$cursor_sql       = exequery(TD::conn(),$sql);
	$cursor_sql_count = exequery(TD::conn(),$sql_count);
	if($count=mysql_fetch_array($cursor_sql_count))
	{
		$user_count = $count[0];
	}
	while($res=mysql_fetch_array($cursor_sql))
	{
		$UID            = $res['UID'];
		$USER_ID        = $res['USER_ID'];
		$USER_NAME      = $res['USER_NAME'];
		$USER_PRIV_NAME = $res['USER_PRIV_NAME'];
		$MY_STATUS      = $res['MY_STATUS'];
		$PRIV_NAME      = $res['PRIV_NAME'];
		$DEPT_ID        = $res['DEPT_ID'];
		$PHOTO          = $res['PHOTO'];
		$SEX            = $res["SEX"];
		$DEPT_NAME      = $res["DEPT_NAME"];
		$OICQ_NO        = $res['$OICQ_NO'];
		$ADD_HOME       = $res['ADD_HOME'];
		$EMAIL          = $res['EMAIL'];
		
		$res['NOT_MOBILE_LOGIN'] == 0?$MOBIL_NO=$res["MOBIL_NO"]:$MOBIL_NO="";
		$AVATAR_PATH=get_head($PHOTO,$SEX,$USER_ID,"contacts");
		
		//短信与微讯权限
		$oa_priv = 1;
		$query_oa="SELECT * FROM module_priv WHERE UID = '$UID' AND MODULE_ID = 0";
		$cursor_oa= exequery(TD::conn(),$query_oa);
		if($arr=mysql_fetch_array($cursor_oa))
		{
			$DEPT_ID1 = $arr['DEPT_ID'];//部门
			$PRIV_ID1 = $arr['PRIV_ID'];//角色
			$USER_ID1 = $arr['USER_ID'];//人员
			$PRIV1    = $DEPT_ID1."|".$PRIV_ID1."|".$USER_ID1;
			if(check_priv($PRIV1))
			{
				$oa_priv = 0;
			}
		}
		else
		{
			$oa_priv = 0;
		}
		$dataBack[] = array(
			'uid'         => $UID,
			'userid'      => td_iconv($USER_ID, MYOA_CHARSET, 'utf-8'),
			'uName'       => td_iconv($USER_NAME, MYOA_CHARSET, 'utf-8'),
			'portraitUrl' => td_iconv($AVATAR_PATH, MYOA_CHARSET, 'utf-8'),
			'company'     => '',
			'department'  => td_iconv($DEPT_NAME, MYOA_CHARSET, 'utf-8'),
			'qq'          => $OICQ_NO,
			'phoneNum'    => $MOBIL_NO,
			'email'       => $EMAIL,
			'address'     => td_iconv($ADD_HOME, MYOA_CHARSET, 'utf-8'),
			'groupName'   => $oa_priv		
		);
	}
	
	
	
	$dongtai1 = array_msort($dataBack, array('uName'=>SORT_ASC));
	
	
	$total     = $num_count+$user_count;
	$thisPage  = ($curPage/$pageLimit) +1;
	$totalpage = ceil($total/$pageLimit);
	
	
	$dongtai1=array_slice($dongtai1, $curPage,$pageLimit);

	$dataBacks=array("curPage" => $thisPage,"totalpage" => $totalpage,"numCount"=>$total,"datalist" => $dongtai1);
	echo json_encode($dataBacks);	
}
//日程安排
if($type =="calendar")
{
	$CUR_TIME    = date("Y-m-d H:i:s",time());
	$query       = "SELECT CAL_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS FROM calendar WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' and( find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER))";
	$query_count = "SELECT count(*) FROM calendar WHERE USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' and( find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER))";
	if($keyword!="")
	{
		$query        .= " and CONTENT like '%$keyword%'";
		$query_count  .= " and CONTENT like '%$keyword%'";
	}
	$query .=" order by CAL_TIME DESC LIMIT ".$curPage.",".$pageLimit;
	$cursor       = exequery(TD::conn(),$query);
	$cursor_count = exequery(TD::conn(),$query_count);
	
	if($ROW_COUNT=mysql_fetch_array($cursor_count))
	{
		$num_count = $ROW_COUNT[0];
	}
	$thisPage   = ($curPage/$pageLimit) +1;
	$totalpage  = ceil($num_count/$pageLimit);
	while($ROW=mysql_fetch_array($cursor))
	{
		$CAL_ID          = $ROW["CAL_ID"];
		$CAL_TIME        = $ROW["CAL_TIME"];
		$END_TIME        = $ROW["END_TIME"];
		$TITLE           = $ROW["CAL_TYPE"]==1?"工作事务":"个人事务";
		$ADD_TIME        = $ROW['ADD_TIME'];
		$CONTENT         = $ROW["CONTENT"];
		$OVER_STATUS     = $ROW["OVER_STATUS"];
		 if($OVER_STATUS=="0")
		 {
			 if(compare_time($CUR_TIME,date("Y-m-d H:i:s",$END_TIME))>0)
			 {
				 $CAL_TITLE=_("状态：已超时");
			 }
			 else if(compare_time($CUR_TIME,date("Y-m-d H:i:s",$CAL_TIME))<0)
			 {
				 $CAL_TITLE=_("状态：未开始");
			 }
			 else
			 {
				 $CAL_TITLE=_("状态：进行中");
			 }
		}else
		{
			$CAL_TITLE=_("状态：已完成");
		}
		$dataBack[] = array(
			'calId'       => $CAL_ID,
			'content'     => td_iconv($CONTENT, MYOA_CHARSET, 'utf-8'),
			'calTime'     => date("Y-m-d H:i",$CAL_TIME),
			'endTime'     => date("Y-m-d H:i",$END_TIME),
			'type'        => $OVER_STATUS,
			'category'    => td_iconv($TITLE, MYOA_CHARSET, 'utf-8'),
			'state'       => td_iconv($CAL_TITLE, MYOA_CHARSET, 'utf-8')
			
		);
	}
	$dataBacks=array("curPage" => $thisPage,"totalpage" => $totalpage,"numCount"=>$num_count,"datalist" => $dataBack);
	echo json_encode($dataBacks);	
}



//返回头像地址
function get_head($PHOTO,$SEX,$USER_ID,$module)
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
		if($module=="contacts")
		{
			$SEX==0?$image="man_big":$image="w_big";
			$AVATAR_PATH = MYOA_STATIC_SERVER."/static/modules/address/images/".$image.".png";
			
		}
		else
		{
			$AVATAR_PATH = MYOA_STATIC_SERVER."/static/images/avatar/".$SEX.".png";
		}
		
		$AVATAR_FILE = MYOA_ROOT_PATH."static/images/avatar/".$SEX.".png";
	}
	
	return $AVATAR_PATH;
}

function array_msort($array, $cols)
{
	$colarr = array();
	foreach ($cols as $col => $order)
	{
		$colarr[$col] = array();
		foreach ($array as $k => $row)
		{
			$colarr[$col]['_'.$k] = strtolower($row[$col]);
		}
	}
	$eval = 'array_multisort(';
	foreach ($cols as $col => $order)
	{
		$eval .= '$colarr[\''.$col.'\'],'.$order.',';
	}
	$eval = substr($eval,0,-1).');';
	eval($eval);
	$ret = array();
	foreach ($colarr as $col => $arr)
	{
		foreach ($arr as $k => $v)
		{
			$k = substr($k,1);
			if (!isset($ret[$k]))
			{
				$ret[$k] = $array[$k];
			}
			$ret[$k][$col] = $array[$k][$col];
		 }
	}
	return $ret; 
}

?>