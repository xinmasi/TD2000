<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
  Message(_("错误"),_("只能导入xls文件!"));
  Button_Back();
  exit;
}

if(MYOA_IS_UN == 1)
{
  $title=array("NAME"=>"BY_EVALU_STAFFS","ID"=>"USER_ID","APPROVE_PERSON"=>"APPROVE_PERSON","POST_NAME"=>"POST_NAME","GET_METHOD"=>"GET_METHOD","REPORT_TIME"=>"REPORT_TIME","RECEIVE_TIME"=>"RECEIVE_TIME","APPROVE_NEXT"=>"APPROVE_NEXT","APPROVE_NEXT_TIME"=>"APPROVE_NEXT_TIME","EMPLOY_POST"=>"EMPLOY_POST","EMPLOY_COMPANY"=>"EMPLOY_COMPANY","START_DATE"=>"START_DATE","END_DATE"=>"END_DATE","EVALUATION_DETAILS"=>"REMARK");
	$fieldAttr=array("REPORT_TIME"=>"date","RECEIVE_TIME"=>"date","APPROVE_NEXT_TIME"=>"date","START_DATE"=>"date","END_DATE"=>"date");
}
else
{
  $title=array(_("评定对象")=>"BY_EVALU_STAFFS",_("评定对象用户名")=>"USER_ID",_("批准人")=>"APPROVE_PERSON",_("获取职称")=>"POST_NAME",_("获取方式")=>"GET_METHOD",_("申报时间")=>"REPORT_TIME",_("获取时间")=>"RECEIVE_TIME",_("下次申报职称")=>"APPROVE_NEXT",_("下次申报时间")=>"APPROVE_NEXT_TIME",_("聘用职务")=>"EMPLOY_POST",_("聘用单位")=>"EMPLOY_COMPANY",_("聘用开始时间")=>"START_DATE",_("聘用结束时间")=>"END_DATE",_("评定详情")=>"REMARK");
	$fieldAttr=array(_("申报时间")=>"date",_("获取时间")=>"date",_("下次申报时间")=>"date",_("聘用开始时间")=>"date",_("聘用结束时间")=>"date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title,$fieldAttr);

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}

$MSG_ERROR = "";
$rows=0;
$UPDATE_COUNT=0;
$INSERT_COUNT=0;
while($DATA = $objExcel->getNextRow())
{
  $success = 1;
	$rows++;
	
  if($DATA['USER_ID']==""&&$DATA['BY_EVALU_STAFFS']=="")
	{
   	$MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为评定对象与评定对象用户名两项必须填写一项！"), $rows)."</font><br/>";
    $success = 0;
    continue;
	}
	else if(!$DATA['USER_ID']=="")
	{
		if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
			$success = 0;
			continue;	
		}
		else
		{
			$DATA['BY_EVALU_STAFFS'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
		}
	}
	else if($DATA['USER_ID']=="" && $DATA['BY_EVALU_STAFFS']!="")
	{
		$query="select USER_ID from USER where USER_NAME='".$DATA['BY_EVALU_STAFFS']."' limit 1";
		$cur= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cur))
		{
			$DATA['BY_EVALU_STAFFS']=$ROW['USER_ID'];
		}else
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
			$success = 0;
			continue;
		}
	}
         
  if($DATA['APPROVE_PERSON']=="")
	{
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为批准人不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
         
  if($DATA['POST_NAME']=="")
	{
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为获取职称不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }

  
  if($DATA['APPROVE_PERSON']!="")
  {
		$sql="select USER_ID from USER where USER_NAME='".$DATA['APPROVE_PERSON']."' limit 1";
		$cr=exequery(TD::conn(),$sql);
		if($ROW1=mysql_fetch_array($cr))
		{
			$DATA['APPROVE_PERSON']=$ROW1['USER_ID'];
		}
	}
	
	if($DATA['GET_METHOD']!="")
  {
  	$sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_TITLE_EVALUATION'";
  	$cure=exequery(TD::conn(),$sql);
		while($ROW1=mysql_fetch_array($cure))
		{
			if($DATA['GET_METHOD']==$ROW1['CODE_NAME'])
				$DATA['GET_METHOD']=$ROW1['CODE_NO'];
		}
	}
	
	
  reset($title);
  $STR_KEY="";
  $STR_VALUE="";
  $UPDATE_STR="";
  foreach($title as $key)
	{
 		if(find_id($ID_STR, $key))
       continue;
    $value=ltrim($DATA[$key]);
    if($key!="USER_ID")
    {
    	$STR_KEY .= $key . ",";
    	$STR_VALUE .= "'$value'" . ",";
    	$UPDATE_STR .= $key."='$value',";
    }
	}

  if (substr($STR_KEY,-1)==",")
    $STR_KEY=substr($STR_KEY,0,-1);
  if (substr($STR_VALUE,-1)==",")
    $STR_VALUE=substr($STR_VALUE,0,-1);
  if (substr($UPDATE_STR, - 1) == ",")
    $UPDATE_STR = substr($UPDATE_STR, 0, - 1);

  $array = explode(",",$STR_VALUE);
  if($array[0]=="''") continue;
  
  if ($success==1)
  {
  	$sql="select * from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='".$DATA["BY_EVALU_STAFFS"]."' and POST_NAME='".$DATA["POST_NAME"]."' and GET_METHOD='".$DATA["GET_METHOD"]."' and REPORT_TIME='".$DATA["REPORT_TIME"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$W_EXPERIENCE_ID=$ROW["EVALUATION_ID"];
    	$query = "update HR_STAFF_TITLE_EVALUATION set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',LAST_UPDATE_TIME='$CUR_TIME',".$UPDATE_STR." where EVALUATION_ID='$EVALUATION_ID'";
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_STAFF_TITLE_EVALUATION (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
    	$INSERT_COUNT++;
    }
    exequery(TD::conn(), $query);
  }

}
if(file_exists($EXCEL_FILE))
  @unlink($EXCEL_FILE);

if($INSERT_COUNT>0)  
	$MESSAGE=sprintf(_("共%s条数据导入！"), $INSERT_COUNT);
if($UPDATE_COUNT>0)
	$MESSAGE=sprintf(_("共%s条数据更新！"), $UPDATE_COUNT);
if($INSERT_COUNT<=0 && $UPDATE_COUNT<=0)
	$MESSAGE=_("导入失败！");
Message("", $MESSAGE);
echo $MSG_ERROR; 

?>
<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='pre_import.php';" title="<?=_("返回")?>">
</div>
</body>
</html>
