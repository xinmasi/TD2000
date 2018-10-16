<?
/**
*   get_proj_num.php文件
*
*   文件内容描述：
*   1、返回对应状态下的对应项目数量
*
*   @author  zfc
*
*   @edit_time  2014/01/06
*
*/
include_once("inc/auth.inc.php");
include_once("inc/utility_project.php");
$QUERT_AUTHOR = project_auth_sql();



//返回处理过的字符串   如: a%b%c%1 a,b,c,1,3 你,好
function splitStr($str,$c)
{
	$str=addslashes($str);
	preg_match_all("/[".chr(0xa1)."-".chr(0xff)."]+/",$str,$arr);
	$arr1=$arr[0];
	$strcn=join($c,str_split(implode('',$arr1),2));
	
	preg_match_all("/[a-zA-Z0-9]+/",$str,$arren);
	$arr2=$arren[0];
	$stren=join($c,str_split(implode('',$arr2),1));
	$str=$strcn.$stren;
	return $str;
}



$QUERY = "SELECT PROJ_STATUS FROM PROJ_PROJECT WHERE 1=1 " . $QUERT_AUTHOR ;

if(isset($find)){
	if(!isset($PROJ_NUM_M)){
		$PROJ_NUM = isset($PROJ_NUM)?" AND PROJ_NUM = '" . $PROJ_NUM ."'": "";
	}else{
		$PROJ_NUM = isset($PROJ_NUM)?" AND PROJ_NUM like '%" . splitStr($PROJ_NUM,'%') . "%'": "";
	}
	if(!isset($PROJ_NAME_M)){
		$PROJ_NAME = isset($PROJ_NAME)?" AND PROJ_NAME = '" . $PROJ_NAME ."'": "";
	}else{
		$PROJ_NAME = isset($PROJ_NAME)?" AND PROJ_NAME like '%" . splitStr($PROJ_NAME,'%') . "%'": "";
    }
	$PROJ_TYPE = isset($PROJ_TYPE)?" AND PROJ_TYPE = '" . $PROJ_TYPE ."'": "";
    $PROJ_START_TIME = isset($PROJ_START_TIME) ? " AND PROJ_START_TIME >= '" . $PROJ_START_TIME . "'":"";
    $PROJ_END_TIME = isset($PROJ_END_TIME)?" AND PROJ_END_TIME <= '" . $PROJ_END_TIME . "'":"";
    //$PROJ_LEVEL = isset($PROJ_LEVEL)?" AND PROJ_LEVEL = '" . $PROJ_LEVEL ."'": "";

    if(isset($PROJ_LEVEL)){
        $PROJ_LEVEL = str_split($PROJ_LEVEL);
        $PROJ_LEVELS = "";
        //and (FIND_IN_SET('b',PROJ_LEVEL) || FIND_IN_SET('a',PROJ_LEVEL));
    foreach($PROJ_LEVEL as $KEY => $VAL){
        $PROJ_LEVELS .= " FIND_IN_SET('$VAL',PROJ_LEVEL) ||";
    }
    $PROJ_LEVELS = rtrim($PROJ_LEVELS,'||');
    $PROJ_LEVEL = " AND ( " . $PROJ_LEVELS . " ) ";
    }else{
        $PROJ_LEVEL = "";
    }
    
    $QUERY .= $PROJ_NUM . $PROJ_NAME . $PROJ_TYPE . $PROJ_LEVEL .$PROJ_START_TIME . $PROJ_END_TIME;
}
    

$CUR = exequery(TD::conn(),$QUERY);
$STATUS = ARRAY();

WHILE($ROW = mysql_fetch_array($CUR)){
        $S = $ROW['PROJ_STATUS'];
        IF(!array_key_exists($S,$STATUS))
            $STATUS[$S] = 0;
		//9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均吧状态9作为状态1
		if($S == 9)
			$STATUS[1] = $STATUS[1] + 1;
        $STATUS[$S] = $STATUS[$S] + 1;    
}
echo json_encode($STATUS);
?>
