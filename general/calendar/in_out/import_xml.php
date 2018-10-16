<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("导入xml数据");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))=="csv")
{
   Message(_("提示"),_("您选择的文件格式不正确，请选择文件来自Outlook"));
?>
<center>
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
</center>   
<?
   exit;
}

if(strtolower(substr($FILE_NAME,-3))!="xml")
{
   Message(_("提示"),_("只能导入xml文件!"));
?>
<center>
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
</center>   
<?
   exit;
}
if($TO_ID3_IN!=""||$TO_ID_IN!=""||$PRIV_ID_IN!="")
{
	$query1 = "SELECT USER_ID from USER where find_in_set(DEPT_ID,'$TO_ID_IN') or find_in_set(USER_PRIV,'$PRIV_ID_IN')";
	$cursor1= exequery(TD::conn(),$query1);
	while($ROW=mysql_fetch_array($cursor1))
	{
		if(!find_id($TO_ID3_IN,$ROW["USER_ID"]))
		{
			$USER_ID.=$ROW["USER_ID"].",";
		}   
	}
	$TO_ID3_IN .= $USER_ID;
}else
{
	$TO_ID3_IN = $_SESSION["LOGIN_USER_ID"].",";
}
$GET_USER = strtok($TO_ID3_IN,",");

$XML_FILE = $_FILES['XML_FILE']['tmp_name'];

$lists = simplexml_load_file($XML_FILE); 

if($lists->AFFAIR=='')
{
	Message(_("错误"),_("导入的xml文件错误!"));
	Button_Back();
	exit;
}

if($TO_ID3_IN == $_SESSION["LOGIN_USER_ID"])
{
	$conn = 1;
	$newstr = explode(",",$TO_ID3_IN);
	
}else
{
	$newstr = substr($TO_ID3_IN,0,strlen($TO_ID3_IN)-1); 
	$newstr = explode(",",$newstr);
	$conn   = count($newstr);
}

for($i=0;$i<$conn;$i++)
{
	foreach($lists->AFFAIR as $AFFAIR)
	{
		$CONTENT = iconv("utf-8",MYOA_CHARSET,$AFFAIR->CONTENT);		
		if($AFFAIR->FLAG=="AFFAIR")
		{
			if($_SESSION["LOGIN_USER_ID"]!=$newstr[$i])
			{
				$MANAGER_ID = $_SESSION["LOGIN_USER_ID"];
			}
			else
			{
				$MANAGER_ID = "";
			}
			$query = "INSERT INTO AFFAIR(USER_ID,BEGIN_TIME,END_TIME,TYPE,REMIND_DATE,REMIND_TIME,CONTENT,SMS2_REMIND,MANAGER_ID,BEGIN_TIME_TIME,END_TIME_TIME)values('".$newstr[$i]."','".strtotime($AFFAIR->BEGIN_DATE)."','".strtotime($AFFAIR->END_DATE)."','".$AFFAIR->TYPE."','".$AFFAIR->REMIND_DATE."','".$AFFAIR->REMIND_TIME."','".$CONTENT."','".$AFFAIR->SMS2_REMIND."','$MANAGER_ID','".$AFFAIR->BEGIN_TIME."','".$AFFAIR->END_TIME."')";
		}else if($AFFAIR->FLAG=="CALENDAR")
		{
			if($_SESSION["LOGIN_USER_ID"]!=$newstr[$i])
			{
				$AFFAIR->CAL_TYPE = "1";	
				$MANAGER_ID = $_SESSION["LOGIN_USER_ID"];
			}else
			{
				$MANAGER_ID = "";
			}
			$query = "INSERT INTO CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,MANAGER_ID) values ('".$newstr[$i]."','".strtotime($AFFAIR->CAL_TIME)."','".strtotime($AFFAIR->END_TIME)."','".$AFFAIR->CAL_TYPE."','".$AFFAIR->CAL_LEVEL."','".$CONTENT."','0','$MANAGER_ID')";
			 
		}else if($AFFAIR->FLAG=="TASK")
		{
			if($_SESSION["LOGIN_USER_ID"]!=$newstr[$i])
			{
				$AFFAIR->TASK_TYPE = "1";
				$MANAGER_ID = $_SESSION["LOGIN_USER_ID"];
			}else
			{
				$MANAGER_ID="";
			}
			$SUBJECT = iconv("utf-8",MYOA_CHARSET,$AFFAIR->SUBJECT);
			
			$query="INSERT INTO `TASK`(`USER_ID` , `TASK_NO` , `TASK_TYPE` , `TASK_STATUS` , `COLOR` , `IMPORTANT` , `SUBJECT` , `EDIT_TIME` , `BEGIN_DATE` , `END_DATE` , `CONTENT` , `RATE` , `FINISH_TIME` , `TOTAL_TIME` , `USE_TIME` , `CAL_ID`  , `MANAGER_ID` )
			 VALUES ('".$newstr[$i]."', '".$AFFAIR->TASK_NO."', '".$AFFAIR->TASK_TYPE."', '".$AFFAIR->TASK_STATUS."', '".$AFFAIR->COLOR."', '".$AFFAIR->IMPORTANT."', '".$SUBJECT."', '".$AFFAIR->EDIT_TIME."', '".$AFFAIR->BEGIN_DATE."','".$AFFAIR->END_DATE."','".$CONTENT."', '".$affair['RATE']."', '".$AFFAIR->FINISH_TIME."', '".$AFFAIR->TOTAL_TIME."', '".$AFFAIR->USE_TIME."', '".$AFFAIR->CAL_ID."','$MANAGER_ID')";    
		}
		exequery(TD::conn(),$query);
	}	
}
Message(_("提示"),_("导入成功"));
?>
<center>
	<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
</center>

</body>
</html>