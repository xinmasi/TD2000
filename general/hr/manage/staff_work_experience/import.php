<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���xls�ļ�!"));
   Button_Back();
   exit;
}
if(MYOA_IS_UN == 1)
{
   $title=array("ID"=>"USER_ID","NAME"=>"STAFF_NAME","POST_OF_JOB"=>"POST_OF_JOB","WORK_BRANCH"=>"WORK_BRANCH","WITNESS"=>"WITNESS","START_DATE"=>"START_DATE","END_DATE"=>"END_DATE","MOBILE"=>"MOBILE","WORK_UNIT"=>"WORK_UNIT","WORK_CONTENT"=>"WORK_CONTENT","REASON_FOR_LEAVING"=>"REASON_FOR_LEAVING","MEMO"=>"REMARK","KEY_PERFORMANCE"=>"KEY_PERFORMANCE");
   $fieldAttr=array("START_DATE"=>"date","END_DATE"=>"date");
}
else
{
   $title=array(_("�û���")=>"USER_ID",_("��λԱ��")=>"STAFF_NAME",_("����ְ��")=>"POST_OF_JOB",_("���ڲ���")=>"WORK_BRANCH",_("֤����")=>"WITNESS",_("��ʼ����")=>"START_DATE",_("��������")=>"END_DATE",_("��ҵ���")=>"MOBILE",_("������λ")=>"WORK_UNIT",_("��������")=>"WORK_CONTENT",_("��ְԭ��")=>"REASON_FOR_LEAVING",_("��ע")=>"REMARK",_("��Ҫҵ��")=>"KEY_PERFORMANCE");
	 $fieldAttr=array(_("��ʼ����")=>"date",_("��������")=>"date");
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
		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ�û����뵥λԱ�����������дһ��"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	else if(!$DATA['USER_ID']=="")
	{
		if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��û������ڣ�"), $rows)."</font><br/>";
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
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��û������ڣ�"), $rows)."</font><br/>";
			$success = 0;
			continue;
		}	
	}

	if(strtotime($DATA['START_DATE'])>=strtotime($DATA['END_DATE']))
	{
    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ����ʱ�䲻��С�ڿ�ʼʱ�䣡"), $rows)."</font><br/>";
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
  	$sql="select * from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='".$DATA["STAFF_NAME"]."' and POST_OF_JOB='".$DATA["POST_OF_JOB"]."' and START_DATE='".$DATA["START_DATE"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$W_EXPERIENCE_ID=$ROW["W_EXPERIENCE_ID"];
    	$query = "update HR_STAFF_WORK_EXPERIENCE set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',LAST_UPDATE_TIME='$CUR_TIME',".$UPDATE_STR." where W_EXPERIENCE_ID='$W_EXPERIENCE_ID'";
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_STAFF_WORK_EXPERIENCE (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
    	$INSERT_COUNT++;
    }
    exequery(TD::conn(), $query);
  }

}
if(file_exists($EXCEL_FILE))
  @unlink($EXCEL_FILE);

if($INSERT_COUNT>0)  
	$MESSAGE=sprintf(_("��%d�����ݵ��룡"), $INSERT_COUNT);
if($UPDATE_COUNT>0)
	$MESSAGE=sprintf(_("��%d�����ݸ��£�"), $UPDATE_COUNT);
if($INSERT_COUNT<=0 && $UPDATE_COUNT<=0)
	$MESSAGE=_("����ʧ�ܣ�");
Message("", $MESSAGE);
echo $MSG_ERROR;
?>
<div align="center">
<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='pre_import.php';" title="<?=_("����")?>">
</div>
</body>
</html>
