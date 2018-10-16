<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if (strtolower(substr($FILE_NAME, - 3)) != "xls")
{
    Message(_("错误"), _("只能导入xls文件!"));
    Button_Back();
    exit();
}
if(MYOA_IS_UN == 1)
{
   $title = array("ID" => "USER_ID", "NAME" => "STAFF_NAME", "MAJOR" => "MAJOR","ACADEMY_DEGREE" => "ACADEMY_DEGREE", "DEGREE" => "DEGREE", "POSITION" => "POSITION","WITNESS" => "WITNESS", "SCHOOL" => "SCHOOL", "SCHOOL_ADDRESS" => "SCHOOL_ADDRESS","START_DATE" => "START_DATE", "END_DATE" => "END_DATE", "AWARDING" => "AWARDING","CERTIFICATES" => "CERTIFICATES", "MEMO" => "REMARK");
	$fieldAttr=array("START_DATE"=>"date","END_DATE"=>"date");
}
else
{
  $title = array(_("用户名") => "USER_ID", _("单位员工") => "STAFF_NAME", _("所学专业") => "MAJOR",_("所获学历") => "ACADEMY_DEGREE", _("所获学位") => "DEGREE", _("曾任班干") => "POSITION",_("证明人") => "WITNESS", _("所在院校") => "SCHOOL", _("院校所在地") => "SCHOOL_ADDRESS",_("开始日期") => "START_DATE", _("结束日期") => "END_DATE", _("获奖情况") => "AWARDING",_("所获证书") => "CERTIFICATES", _("备注") => "REMARK");
	$fieldAttr=array(_("开始日期") => "date", _("结束日期") => "date");
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
  if ($DATA['USER_ID'] == "" && $DATA['STAFF_NAME'] == "")
  {
		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为用户名与单位员工两项必须填写一项"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
  else if($DATA['USER_ID']!="")
  {
	  if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
	  {
		  $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
		  $success = 0;
		  continue;	
	  }
	  else
	  {
		  $DATA['STAFF_NAME'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
	  }	
  }
  else if($DATA['USER_ID']=="" && $DATA['STAFF_NAME']!="")
  {
	  $query="select USER_ID from USER where USER_NAME='".$DATA['STAFF_NAME']."' limit 1";
	  $cur= exequery(TD::conn(),$query);
	  if($ROW=mysql_fetch_array($cur))
	  {
		  $DATA['STAFF_NAME']=$ROW['USER_ID'];
	  }else
	  {
		  $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，用户不存在！"), $rows)."</font><br/>";
		  $success = 0;
		  continue;
	  }
  }
  if($DATA['ACADEMY_DEGREE']=="")
  {
    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为学历不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
  if($DATA['DEGREE']=="")
  {
    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为学位不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
  if (strtotime($DATA['START_DATE']) >= strtotime($DATA['END_DATE']))
  {
		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("第%s行导入失败，因为结束时间不能小于开始时间"), $rows)."</font><br/>";
    $success = 0;
    continue;
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
		if($key=="ACADEMY_DEGREE")
		{
			$sql="SELECT CODE_NO FROM hr_code WHERE PARENT_NO ='STAFF_HIGHEST_SCHOOL' AND CODE_NAME = '$value'";
			$Tcursor1= exequery(TD::conn(),$sql);
			if($TROW1=mysql_fetch_array($Tcursor1))
			{
				$value = $TROW1[0];
			}
		}
		if($key=="DEGREE")
		{
			$sql1="SELECT CODE_NO FROM hr_code WHERE PARENT_NO ='EMPLOYEE_HIGHEST_DEGREE' AND CODE_NAME = '$value'";
			$Tcursor2= exequery(TD::conn(),$sql1);
			if($TROW2=mysql_fetch_array($Tcursor2))
			{
				$value = $TROW2[0];
			}
		}
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
  	$sql="select * from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='".$DATA["STAFF_NAME"]."' and MAJOR='".$DATA["MAJOR"]."' and START_DATE='".$DATA["START_DATE"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$L_EXPERIENCE_ID=$ROW["L_EXPERIENCE_ID"];
    	$query = "update HR_STAFF_LEARN_EXPERIENCE set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',LAST_UPDATE_TIME='$CUR_TIME',".$UPDATE_STR." where L_EXPERIENCE_ID='$L_EXPERIENCE_ID'";
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_STAFF_LEARN_EXPERIENCE (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
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
<div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton"
	onClick="location='pre_import.php';" title="<?=_("返回")?>"></div>
</body>
</html>
