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
  $title=array("TYPE"=>"CARE_TYPE","FEES"=>"CARE_FEES","ID"=>"USER_ID","NAME"=>"BY_CARE_STAFFS","DATE"=>"CARE_DATE","EFFECTS"=>"CARE_EFFECTS","PARTICIPANTS"=>"PARTICIPANTS","CONTENT"=>"CARE_CONTENT");
  $fieldAttr=array("CARE_DATE"=>"date");
}
else
{
  $title=array(_("关怀类型")=>"CARE_TYPE",_("关怀开支费用/人")=>"CARE_FEES",_("被关怀员工用户名")=>"USER_ID",_("被关怀员工")=>"BY_CARE_STAFFS",_("关怀日期")=>"CARE_DATE",_("关怀效果")=>"CARE_EFFECTS",_("参与人（中文逗号隔开）")=>"PARTICIPANTS",_("关怀内容")=>"CARE_CONTENT");
	$fieldAttr=array(_("关怀日期")=>"date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}


require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title,$fieldAttr);

$MSG_ERROR = "";
$rows=0;
$UPDATE_COUNT=0;
$INSERT_COUNT=0;
while($DATA = $objExcel->getNextRow())
{
  $success = 1;
	$rows++;

  if($DATA['USER_ID']==""&&$DATA['BY_CARE_STAFFS']=="")
	{
   	$MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为被关怀用户名与被关怀员工两项必须填写一项！"), $rows)."</font><br/>";
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
			$DATA['BY_CARE_STAFFS'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
		}	
	}
	else if($DATA['USER_ID']=="" && $DATA['BY_CARE_STAFFS']!="")
	{
		$query="select USER_ID from USER where USER_NAME='".$DATA['BY_CARE_STAFFS']."' limit 1";
		$cur= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cur))
		{
			$DATA['BY_CARE_STAFFS']=$ROW['USER_ID'];
		}else
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
			$success = 0;
			continue;
		}
	}
	if($DATA['CARE_DATE']=="")
	{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为关怀日期不能为空！"), $rows)."</font><br/>";
		$success = 0;
		continue;
  }
  
  if($DATA['CARE_CONTENT']=="")
  {
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为关怀内容不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }

   $POSTFIX = _("，");
	if($DATA['PARTICIPANTS']!="")
	{
		if(substr($DATA['PARTICIPANTS'],-strlen($POSTFIX))==$POSTFIX) 
			$DATA['PARTICIPANTS']=substr($DATA['PARTICIPANTS'],0,-strlen($POSTFIX));
		$users=explode('，',$DATA['PARTICIPANTS']);
		$user_str="";
		foreach($users as $value)
		{
			$sql="select USER_ID from USER where USER_NAME='$value' limit 1";
			$cr=exequery(TD::conn(),$sql);
			if($ROW1=mysql_fetch_array($cr))
				$user_str.=$ROW1['USER_ID'].',';
		}
		$DATA['PARTICIPANTS']=$user_str;
	}
	
  if($DATA['CARE_TYPE']!="")
  {
  	$sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_CARE'";
  	$cure=exequery(TD::conn(),$sql);
		while($ROW1=mysql_fetch_array($cure))
		{
			if($DATA['CARE_TYPE']==$ROW1['CODE_NAME'])
				$DATA['CARE_TYPE']=$ROW1['CODE_NO'];
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
  	$sql="select * from HR_STAFF_CARE where CARE_TYPE='".$DATA["CARE_TYPE"]."' and BY_CARE_STAFFS='".$DATA["BY_CARE_STAFFS"]."' and CARE_DATE='".$DATA["CARE_DATE"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$CARE_ID=$ROW["CARE_ID"];
    	$query = "update HR_STAFF_CARE set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',LAST_UPDATE_TIME='$CUR_TIME',".$UPDATE_STR." where CARE_ID='$CARE_ID'";
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_STAFF_CARE (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
    	$INSERT_COUNT++;
    }
    exequery(TD::conn(), $query);
  }

}
if(file_exists($EXCEL_FILE))
  @unlink($EXCEL_FILE);

if($INSERT_COUNT>0)  
	$MESSAGE=sprintf(_("共%d条数据导入！"), $INSERT_COUNT);
if($UPDATE_COUNT>0)
	$MESSAGE=sprintf(_("共%d条数据更新！！"), $UPDATE_COUNT);
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
