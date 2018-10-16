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
	$title=array("ID"=>"USER_ID","NAME"=>"STAFF_NAME","ABILITY_NAME"=>"ABILITY_NAME","SPECIAL_WORK"=>"SPECIAL_WORK","SKILLS_LEVEL"=>"SKILLS_LEVEL","SKILLS_CERTIFICATE"=>"SKILLS_CERTIFICATE","ISSUE_DATE"=>"ISSUE_DATE","EXPIRES"=>"EXPIRES","EXPIRE_DATE"=>"EXPIRE_DATE","ISSUING_AUTHORITY"=>"ISSUING_AUTHORITY","MEMO"=>"REMARK");
	$fieldAttr=array("ISSUE_DATE"=>"date","EXPIRE_DATE"=>"date");
}
else
{
  $title=array(_("用户名")=>"USER_ID",_("单位员工")=>"STAFF_NAME",_("技能名称")=>"ABILITY_NAME",_("是否特种作业")=>"SPECIAL_WORK",_("级别")=>"SKILLS_LEVEL",_("是否有技能证")=>"SKILLS_CERTIFICATE",_("发证日期")=>"ISSUE_DATE",_("有效期")=>"EXPIRES",_("到期日期")=>"EXPIRE_DATE",_("发证机关/单位")=>"ISSUING_AUTHORITY",_("备注")=>"REMARK");
	$fieldAttr=array(_("发证日期")=>"date",_("到期日期")=>"date");
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
  if($DATA['USER_ID']==""&&$DATA['STAFF_NAME']=="")
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
  if($DATA['ABILITY_NAME']=="")
  {
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为技能名称不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
  if($DATA['ISSUE_DATE']=="")
  {
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为发证日期不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }
   
  if($DATA['EXPIRES']=="")
  {
  	$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("第%s行导入失败，因为有效期不能为空!"), $rows)."</font><br/>";
    $success = 0;
    continue;
  }

  if($DATA['SPECIAL_WORK']!="")
  {
		if($DATA['SPECIAL_WORK']==_("是"))
		{
			$DATA['SPECIAL_WORK']='1';
		}
		else if ($DATA['SPECIAL_WORK']==_("否"))
		{
			$DATA['SPECIAL_WORK']='0';
		}
	}
	if($DATA['SKILLS_CERTIFICATE']!="")
  {
		if($DATA['SKILLS_CERTIFICATE']==_("是"))
		{
			$DATA['SKILLS_CERTIFICATE']='1';
		}
		else if ($DATA['SKILLS_CERTIFICATE']==_("否"))
		{
			$DATA['SKILLS_CERTIFICATE']='0';
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
  	$sql="select * from HR_STAFF_LABOR_SKILLS where STAFF_NAME='".$DATA["STAFF_NAME"]."' and ABILITY_NAME='".$DATA["ABILITY_NAME"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$SKILLS_ID=$ROW["SKILLS_ID"];
    	$query = "update HR_STAFF_LABOR_SKILLS set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',LAST_UPDATE_TIME='$CUR_TIME',".$UPDATE_STR." where SKILLS_ID='$SKILLS_ID'";
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_STAFF_LABOR_SKILLS (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
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
