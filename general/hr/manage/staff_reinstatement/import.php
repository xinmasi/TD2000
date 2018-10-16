<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))!="xls"){
	Message(_("����"),_("ֻ�ܵ���xls�ļ�!"));
	Button_Back();
	//exit;
}
if(MYOA_IS_UN == 1){
	$title = Array("USER_ID" => "USER_ID","REINSTATEMENT_PERSON" => "REINSTATEMENT_PERSON","REAPPOINTMENT_TYPE" => "REAPPOINTMENT_TYPE","APPLICATION_DATE" => "APPLICATION_DATE","NOW_POSITION" => "NOW_POSITION","REAPPOINTMENT_TIME_PLAN" => "REAPPOINTMENT_TIME_PLAN","REAPPOINTMENT_TIME_FACT" => "REAPPOINTMENT_TIME_FACT","FIRST_SALARY_TIME" => "FIRST_SALARY_TIME","REAPPOINTMENT_DEPT" => "REAPPOINTMENT_DEPT","MATERIALS_CONDITION" => "MATERIALS_CONDITION","REMARK" => "REMARK","REAPPOINTMENT_STATE" => "REAPPOINTMENT_STATE");
	$fieldAttr=array(_("APPLICATION_DATE")=>"date",_("REAPPOINTMENT_TIME_PLAN")=>"date",_("REAPPOINTMENT_TIME_FACT")=>"date",_("FIRST_SALARY_TIME")=>"date");
}else{
	$title=array(_("�û���")=>"USER_ID",_("��ְ��Ա")=>"REINSTATEMENT_PERSON",_("��ְ����")=>"REAPPOINTMENT_TYPE",_("��������")=>"APPLICATION_DATE",_("����ְ��")=>"NOW_POSITION",_("�⸴ְ����")=>"REAPPOINTMENT_TIME_PLAN",_("ʵ�ʸ�ְ����")=>"REAPPOINTMENT_TIME_FACT",_("���ʻָ�����")=>"FIRST_SALARY_TIME",_("��ְ����")=>"REAPPOINTMENT_DEPT",_("��ְ��������")=>"MATERIALS_CONDITION",_("��ע")=>"REMARK",_("��ְ˵��")=>"REAPPOINTMENT_STATE");
	$fieldAttr=array(_("��������")=>"date",_("�⸴ְ����")=>"date",_("ʵ�ʸ�ְ����")=>"date",_("���ʻָ�����")=>"date");
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
while($DATA = $objExcel->getNextRow()){
	$success = 1;
	$rows++;
	if($DATA['USER_ID']==""&&$DATA['REINSTATEMENT_PERSON']==""){
   		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ�û����븴ְ��Ա���������дһ��"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}else if(!$DATA['USER_ID']=="")
	{
		if(!$user_info_arr[$DATA['USER_ID']]['USER_ID'])
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��û������ڣ�"), $rows)."</font><br/>";
			$success = 0;
			continue;	
		}
		else
		{
			$DATA['REINSTATEMENT_PERSON'] = $user_info_arr[$DATA['USER_ID']]['USER_ID'];
		}	
	}else if($DATA['USER_ID']=="" && $DATA['REINSTATEMENT_PERSON']!="")
	{
		$query="select USER_ID from USER where USER_NAME='".$DATA['REINSTATEMENT_PERSON']."' limit 1";
		$cur= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cur)){
			$DATA['REINSTATEMENT_PERSON']=$ROW['USER_ID'];
		}else
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��û������ڣ�"), $rows)."</font><br/>";
			$success = 0;
			continue;
		}
	}
	if($DATA['APPLICATION_DATE'] !="" && !is_date($DATA['APPLICATION_DATE'])){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ����븴ְ����ӦΪ�����ͣ��磺1999-01-01"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['REAPPOINTMENT_TIME_PLAN'] !="" && !is_date($DATA['REAPPOINTMENT_TIME_PLAN'])){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��⸴ְ����ӦΪ�����ͣ��磺1999-01-01"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}

	if($DATA['REAPPOINTMENT_TIME_FACT']!="" && !is_date($DATA['REAPPOINTMENT_TIME_FACT'])){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ�ʵ�ʸ�ְ����ӦΪ�����ͣ��磺1999-01-01"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}

	/*if($DATA['REAPPOINTMENT_TIME_PLAN'] < $DATA['APPLICATION_DATE']){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��⸴ְ���ڲ���С�����븴ְ����"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}

	if($DATA['REAPPOINTMENT_TIME_FACT'] < $DATA['APPLICATION_DATE']){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ�ʵ�ʸ�ְ���ڲ���С�����븴ְ����"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}*/
	if($DATA['FIRST_SALARY_TIME'] < $DATA['REAPPOINTMENT_TIME_FACT']&&$DATA['FIRST_SALARY_TIME']!=""&&$DATA['REAPPOINTMENT_TIME_FACT']!=""){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ����ʻָ����ڲ���С��ʵ�ʸ�ְ����"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}

	if($DATA['FIRST_SALARY_TIME'] !="" && !is_date($DATA['FIRST_SALARY_TIME'])){
	    $MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ����ʻָ�����ӦΪ�����ͣ��磺1999-01-01"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	
	if($DATA['REAPPOINTMENT_DEPT']==""){
  		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ��ְ���Ų���Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['REAPPOINTMENT_STATE']==""){
  		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ��ְ˵������Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
 
	$LEAVE_DEPT_NAME = $DATA['REAPPOINTMENT_DEPT'];
	$sql="select DEPT_ID,DEPT_NAME from department where DEPT_NAME='".$DATA['REAPPOINTMENT_DEPT']."'";
	$cure=exequery(TD::conn(),$sql);
	if($ROW1=mysql_fetch_array($cure)){
		$DATA['REAPPOINTMENT_DEPT'] = $ROW1['DEPT_ID'];
	}else{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊϵͳ�в�������������ĸ�ְ����!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	
	$GET_REAPPOINTMENT_TYPE_FLAG = false;
	if($DATA['REAPPOINTMENT_TYPE']!=""){
  		$sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_REINSTATEMENT'";
  		$cure=exequery(TD::conn(),$sql);
		while($ROW1=mysql_fetch_array($cure)){
			if(trim($DATA['REAPPOINTMENT_TYPE'])==trim($ROW1['CODE_NAME'])){
				$DATA['REAPPOINTMENT_TYPE']=$ROW1['CODE_NO'];
				$GET_REAPPOINTMENT_TYPE_FLAG = true;
				break;
			}
		}
		if(!$GET_REAPPOINTMENT_TYPE_FLAG){
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊϵͳ�в��������ύ�ĸ�ְ����!"), $rows)."</font><br/>";
			$success = 0;
			continue;			
		}
	}else{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���ְ���Ͳ���Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;			
	}


	reset($title);
	$STR_KEY="";
	$STR_VALUE="";
	$UPDATE_STR="";
	foreach($title as $key){
		if(find_id($ID_STR, $key))
		continue;
		$value=ltrim($DATA[$key]);
		if($key!="USER_ID"){
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
  
	if($success==1){
		$CUR_TIME = date("Y-m-d H:i:s", time());
		$query = "insert into HR_STAFF_REINSTATEMENT (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,LAST_UPDATE_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$CUR_TIME')";
		$INSERT_COUNT++;
		exequery(TD::conn(), $query);

		$query="update USER set DEPT_ID='".$DATA['REAPPOINTMENT_DEPT']."',NOT_LOGIN='0' where USER_ID='".$DATA['REINSTATEMENT_PERSON']."'";
		exequery(TD::conn(),$query);
		  
		$query="update HR_STAFF_INFO  set DEPT_ID='".$DATA['REAPPOINTMENT_DEPT']."', WORK_STATUS='01' where USER_ID='".$DATA['REINSTATEMENT_PERSON']."'";
		exequery(TD::conn(),$query);

		$query="update HR_STAFF_LEAVE set IS_REINSTATEMENT='1' where LEAVE_PERSON='".$DATA['REINSTATEMENT_PERSON']."'";
		exequery(TD::conn(),$query);
	}
}
if(file_exists($EXCEL_FILE))
  @unlink($EXCEL_FILE);

if($INSERT_COUNT>0)  
	$MESSAGE=sprintf(_("��%s�����ݵ��룡"), $INSERT_COUNT);
if($UPDATE_COUNT>0)
	$MESSAGE=sprintf(_("��%s�����ݸ��£�"), $UPDATE_COUNT);
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
