<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
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
   $title=array("NAME"=>"STAFF_NAME","ID"=>"USER_ID","WELFARE_ITEM"=>"WELFARE_ITEM","PAYMENT_DATE"=>"PAYMENT_DATE","WELFARE_MONTH"=>"WELFARE_MONTH","WELFARE_PAYMENT"=>"WELFARE_PAYMENT","TAX_AFFAIRS"=>"TAX_AFFAIRS","FREE_GIFT"=>"FREE_GIFT","MEMO"=>"REMARK");
   $fieldAttr=array("PAYMENT_DATE"=>"date","WELFARE_MONTH"=>"date");
}
else
{
   $title=array(_("����")=>"STAFF_NAME",_("�û���")=>"USER_ID",_("������Ŀ")=>"WELFARE_ITEM",_("��������")=>"PAYMENT_DATE",_("�����·�")=>"WELFARE_MONTH",_("�������")=>"WELFARE_PAYMENT",_("�Ƿ���˰")=>"TAX_AFFAIRS",_("������Ʒ")=>"FREE_GIFT",_("��ע")=>"REMARK");
   $fieldAttr=array(_("��������")=>"date",_("�����·�")=>"date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title,$fieldAttr);

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID,USER_NAME from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}
$MSG_ERROR= array();
$ROW_COUNT=0;
$UPDATE_COUNT=0;
$INSERT_COUNT=0;
while($DATA = $objExcel->getNextRow())
{
	$success=1;
	reset($title);
	$USER_ID="";
	$DATAS[$ROW_COUNT]=array("USER_ID" => $DATA["USER_ID"],"STAFF_NAME" => $DATA["STAFF_NAME"]);
	$STR_VALUE="";
	$STR_KEY="";
	$STR_UPDATE="";

	if($DATA['USER_ID']==""&&$DATA['STAFF_NAME']=="")
	{
		$MSG_ERROR[$ROW_COUNT]=_("<font color=red><b>�û������������������дһ��</b></font><br/>");
    $success = 0;
    $ROW_COUNT++;
    continue;
	}
	else if($DATA['USER_ID']==""&&$DATA['STAFF_NAME']!="")
	{
		$STAFF_NAME=$DATA['STAFF_NAME'];
		$query="select USER_ID from USER where USER_NAME='".$DATA['STAFF_NAME']."'";
		$cur= exequery(TD::conn(),$query);
		$sum=mysql_num_rows($cur);
		if($sum>1)
		{
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("ϵͳ��������%s������Ա������ָ���û�����"), $STAFF_NAME)."</b></font><br>\n";
			$success=0;
			$ROW_COUNT++;
			continue;
		}
		else if($sum==1)
		{
			$ROW=mysql_fetch_array($cur);
			$USER_ID=$DATAS[$ROW_COUNT]['USER_ID']=$ROW['USER_ID'];
		}
		else
		{
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("Ա��%s��δ��OAϵͳ��ע�ᣡ��"), $STAFF_NAME)."</b></font><br>\n";
			$success=0;
			$ROW_COUNT++;
			continue;
		}
	}
	else if($DATA['USER_ID']!="")
	{
		$USER_ID=$DATA["USER_ID"];
		if(!$user_info_arr[$USER_ID]['USER_NAME'])
		{
			 $MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("ϵͳ�в������û���Ϊ %s ���û���"), $USER_ID)."</b></font><br>\n";
			 $success=0;
			 $ROW_COUNT++;
			 continue;
		}else
		{
			$USER_NAME = $user_info_arr[$USER_ID]['USER_NAME'];
			$USER_ID   = $user_info_arr[$USER_ID]['USER_ID'];
			$DATAS[$ROW_COUNT]["STAFF_NAME"]=$USER_NAME;
		}	
	}
	
	$USEFUL_COLUMN=0;  
  foreach ($title as $key)
  {
   	if(find_id($ID_STR, $key))
       continue;
    $value=ltrim($DATA[$key]);
    if(($key!="WELFARE_ITEM")&&($key!="TAX_AFFAIRS")&&($key!="STAFF_NAME")&&($key!="WELFARE_MONTH")&&($key!="USER_ID"))
    {
    	$STR_KEY.=$key.",";
      $STR_VALUE.="'$value'".",";
      $STR_UPDATE.="$key="."'$value',";
    }
    else
    {
      if($key=="TAX_AFFAIRS")
      {
        if($value==_("��"))
        {
        	$STR_KEY.=$key.",";
          $STR_VALUE.="'1'".",";
          $STR_UPDATE.="TAX_AFFAIRS='1',";
        }
        elseif($value==_("��"))
        {
        	$STR_KEY.=$key.",";
          $STR_VALUE.="'0'".",";
          $STR_UPDATE.="TAX_AFFAIRS='0',";
        }
        else
        {
        	$STR_KEY.=$key.",";
          $STR_VALUE.="''".",";
          $STR_UPDATE.="TAX_AFFAIRS='',";
        }
      }
      if ($key=="WELFARE_ITEM")
      {
        $Tquery="SELECT CODE_NO FROM HR_CODE WHERE CODE_NAME='$value' and PARENT_NO = 'HR_WELFARE_MANAGE'";
	      $Tcursor= exequery(TD::conn(),$Tquery);
	      if($TROW=mysql_fetch_array($Tcursor))
	      {
	      	$STR_KEY.=$key.",";
	        $CODE_NO = $TROW["CODE_NO"];
	        $WELFARE_ITEM=$CODE_NO;
	        $STR_VALUE.="'$CODE_NO'".",";
	        $STR_UPDATE.="WELFARE_ITEM='$CODE_NO',";
	        $CODE_NO = "";
        }
        else
        {
        	$STR_KEY.=$key.",";
	        $STR_VALUE.="''".",";
	       	$STR_UPDATE.="WELFARE_ITEM='',";
	      }
      }
      if ($key=="WELFARE_MONTH")
      {
      	$STR_KEY.=$key.",";
        $time = strtotime($value);
        $value=substr(date("Y-m-d",$time),0,-3);
	      $STR_VALUE.="'$value'".",";
	      $STR_UPDATE.="WELFARE_MONTH='$value',";
      }
    }
  }
  if(substr($STR_KEY,-1)==",")
     $STR_KEY=substr($STR_KEY,0,-1);
  if(substr($STR_VALUE,-1)==",")
     $STR_VALUE=substr($STR_VALUE,0,-1);
	if(substr($STR_UPDATE,-1)==",")
		 $STR_UPDATE=substr($STR_UPDATE,0,-1);
		 
		 
  $array = explode(",",$STR_VALUE);
  if($array[0]=="''") continue;
  
		
  if($success==1)
  {
  	$sql="select * from HR_WELFARE_MANAGE where STAFF_NAME='".$USER_ID."' and WELFARE_ITEM='".$WELFARE_ITEM."' and PAYMENT_DATE='".$DATA["PAYMENT_DATE"]."'";
  	$cursor=exequery(TD::conn(),$sql);
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if($ROW=mysql_fetch_array($cursor))
    {
    	$WELFARE_ID=$ROW["WELFARE_ID"];
    	$query = "update HR_WELFARE_MANAGE set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',CREATE_DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."',ADD_TIME='$CUR_TIME',STAFF_NAME='$USER_ID',".$STR_UPDATE." where WELFARE_ID='$WELFARE_ID'";
    	$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s�ĸ���������ɣ���")."<br>", $STAFF_NAME);
    	$UPDATE_COUNT++;
    }
    else
    {
    	$query = "insert into HR_WELFARE_MANAGE (" . $STR_KEY .",CREATE_USER_ID,CREATE_DEPT_ID,ADD_TIME,STAFF_NAME) values (" . $STR_VALUE .",'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$CUR_TIME','$USER_ID')";
    	$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s�ĸ���������ɣ���")."<br>", $STAFF_NAME);
    	$INSERT_COUNT++;
    }
    exequery(TD::conn(), $query);
  }
  $ROW_COUNT++;
}
if(file_exists($EXCEL_FILE))
  @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
  	<td nowrap align="center"><?=_("���")?></td>
    <td nowrap align="center"><?=_("�û���")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("״̬")?></td>
  </thead>
<?
for($I=0;$I< count($DATAS);$I++)
{
	if($I%2==1)
		$TR_STYLE="TableLine1";
	else
		$TR_STYLE="TableLine2";
?>
  <tr align="center" class="<?=$TR_STYLE?>" class="TableData">
  	<td><?=($I+1)?></td>
    <td><?=$DATAS[$I]["USER_ID"]?></td>
    <td><?=$DATAS[$I]["STAFF_NAME"]?></td>
    <td align="left"><?=$MSG_ERROR[$I]?></td>
  </tr>
<?
}
?>
</table>
<br>
<?
if($INSERT_COUNT>0)  
{
	$MESSAGE=sprintf(_("��%s�����ݵ��룡"), $INSERT_COUNT);
	Message("", $MESSAGE);
}
if($UPDATE_COUNT>0)
{
	$MESSAGE=sprintf(_("��%s�����ݸ��£�"), $UPDATE_COUNT);
	Message("", $MESSAGE);
}
if($INSERT_COUNT<=0 && $UPDATE_COUNT<=0)
{
	$MESSAGE=_("����ʧ�ܣ�");
	Message("", $MESSAGE);
}
Button_Back();
?>