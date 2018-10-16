<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if (strtolower(substr($_FILES["EXCEL_FILE"]["name"], - 3)) != "xls")
{
    Message(_("����"), _("ֻ�ܵ���.xls�ļ�!"));
    Button_Back();
    exit();
}
if(MYOA_IS_UN == 1)
{
	$title = array("ID" => "USER_ID", "NAME" => "STAFF_NAME", "LICENSE_TYPE" => "LICENSE_TYPE","LICENSE_NO" => "LICENSE_NO", "LICENSE_NAME" => "LICENSE_NAME", "GET_LICENSE_DATE" => "GET_LICENSE_DATE","EFFECTIVE_DATE" => "EFFECTIVE_DATE", "STATUS" => "STATUS", "EXPIRATION_PERIOD" => "EXPIRATION_PERIOD","EXPIRE_DATE" => "EXPIRE_DATE","NOTIFIED_BODY" => "NOTIFIED_BODY","LICENSE_DEPT" => "LICENSE_DEPT" ,"MEMO" => "REMARK");
	$fieldAttr = array("GET_LICENSE_DATE"=>"date","EFFECTIVE_DATE"=>"date","EXPIRE_DATE"=>"date");
}
else
{
   
	$title = array(_("�û���") => "USER_ID", _("��λԱ��") => "STAFF_NAME", _("֤������") => "LICENSE_TYPE",_("֤�ձ��") => "LICENSE_NO", _("֤������") => "LICENSE_NAME", _("ȡ֤����") => "GET_LICENSE_DATE",_("��Ч����") => "EFFECTIVE_DATE", _("״̬") => "STATUS", _("�Ƿ���������") => "EXPIRATION_PERIOD", _("��������") => "EXPIRE_DATE",_("��֤����") => "NOTIFIED_BODY",_("����") => "LICENSE_DEPT", _("��ע") => "REMARK");
	$fieldAttr = array(_("ȡ֤����")=>"date",_("��Ч����")=>"date",_("��������")=>"date");
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
$rows = 0;
$UPDATE_COUNT = 0;
$INSERT_COUNT = 0;
while($DATA = $objExcel->getNextRow())
{
	$success=1;
	$rows++;
	if($DATA['USER_ID'] == "" && $DATA['STAFF_NAME'] == "")
	{
		$MSG_ERROR.="<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ�û����뵥λԱ�����������дһ��"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	else if($DATA['USER_ID']!= "")
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
	else if($DATA['USER_ID']== "" && $DATA['STAFF_NAME']!= "")
	{
		$query = "select USER_ID from USER where USER_NAME='" .$DATA['STAFF_NAME'] . "' limit 1";
		$cur = exequery(TD::conn(), $query);
		if($ROW = mysql_fetch_array($cur))
		{
			$DATA['STAFF_NAME'] = $ROW['USER_ID'];
		}else
		{
			$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ��û������ڣ�"), $rows)."</font><br/>";
			$success = 0;
			continue;
		}
	}
	if($DATA['LICENSE_TYPE']=="")
	{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ֤�����Ͳ���Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['LICENSE_NO']=="")
	{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ֤�ձ�Ų���Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['LICENSE_NAME']=="")
	{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ֤�����Ʋ���Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['NOTIFIED_BODY']=="")
	{
		$MSG_ERROR .= "<font color=red size=3px>".sprintf(_("��%s�е���ʧ�ܣ���Ϊ��֤��������Ϊ��!"), $rows)."</font><br/>";
		$success = 0;
		continue;
	}
	if($DATA['EXPIRATION_PERIOD']!="")
	{
		if($DATA['EXPIRATION_PERIOD']==_("��"))
		{
			$DATA['EXPIRATION_PERIOD']='1';
		}
		else if($DATA['EXPIRATION_PERIOD']==_("��"))
		{
			$DATA['EXPIRATION_PERIOD']='0';
		}
	}
	if($DATA['LICENSE_TYPE']!="")
	{
		$sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_LICENSE1'";
		$cure=exequery(TD::conn(),$sql);
		while($ROW1=mysql_fetch_array($cure))
		{
			if($DATA['LICENSE_TYPE']==$ROW1['CODE_NAME'])
			{
				$DATA['LICENSE_TYPE']=$ROW1['CODE_NO'];
			}
		}
	}
	if($DATA['STATUS']!="")
	{
		$sql="select CODE_NO,CODE_NAME from HR_CODE where PARENT_NO='HR_STAFF_LICENSE2'";
		$cure=exequery(TD::conn(),$sql);
		while($ROW1=mysql_fetch_array($cure))
		{
			if($DATA['STATUS']==$ROW1['CODE_NAME'])
			{
				$DATA['STATUS']=$ROW1['CODE_NO'];
			}
		}
	}
	reset($title);
	$STR_KEY="";
	$STR_VALUE="";
	$UPDATE_STR="";
	foreach ($title as $key)
	{
		if(find_id($ID_STR, $key))
		{
			continue;
		}
		$value=ltrim($DATA[$key]);
		if($key!="USER_ID")
		{
			$STR_KEY .= $key . ",";
			$STR_VALUE .= "'$value'" . ",";
			$UPDATE_STR .= $key."='$value',";
		}
	}
	if(substr($STR_KEY, - 1) == ",")
	{
		$STR_KEY = substr($STR_KEY, 0, - 1);
	} 
	if(substr($STR_VALUE, - 1) == ",")
	{
		$STR_VALUE = substr($STR_VALUE, 0, - 1);
	}
	if(substr($UPDATE_STR, - 1) == ",")
	{
		$UPDATE_STR = substr($UPDATE_STR, 0, - 1);
	}
	$array = explode(",",$STR_VALUE);
	if($array[0]=="''")
	{
		continue;
	}
    $ARR_DEPARTMENT = explode(',', $STR_VALUE);
    $LICENSE_DEPTS = $ARR_DEPARTMENT['10'];
    $sql = "SELECT DEPT_ID FROM DEPARTMENT WHERE DEPT_NAME = ".$LICENSE_DEPTS."";
    $cursor=exequery(TD::conn(),$sql);
    $ROW=mysql_fetch_array($cursor);
    $LICENSE_DEPT_ID = (string)$ROW['DEPT_ID']; 
    
    if($ARR_DEPARTMENT['10'] !=  "''")
    {
        $STR_VALUE = str_replace($LICENSE_DEPTS,$LICENSE_DEPT_ID,$STR_VALUE);
        $UPDATE_STR = str_replace($LICENSE_DEPTS,$LICENSE_DEPT_ID,$UPDATE_STR);
        
    }
	if ($success==1)
	{
		$sql="select * from HR_STAFF_LICENSE where LICENSE_NO='".$DATA["LICENSE_NO"]."'";
		$cursor=exequery(TD::conn(),$sql);
		$CUR_TIME = date("Y-m-d H:i:s", time());
		if($ROW=mysql_fetch_array($cursor))
		{
			$LICENSE_ID=$ROW["LICENSE_ID"];
			$query = "update HR_STAFF_LICENSE set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',ADD_TIME='$CUR_TIME',".$UPDATE_STR." where LICENSE_ID='$LICENSE_ID'";
			$UPDATE_COUNT++;
		}
		else
		{
			$query = "insert into HR_STAFF_LICENSE (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME')";
			$INSERT_COUNT++;
		}
		exequery(TD::conn(), $query);
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
<div align="center"><input type="button" value="<?=_("����")?>" class="BigButton"
onClick="location='pre_import.php';" title="<?=_("����")?>"></div>
</body>
</html>
