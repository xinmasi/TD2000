<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("������");
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

$query = "SELECT ITEM_ID ,ITEM_NAME from SAL_ITEM";
$cursor= exequery(TD::conn(),$query);
$title=array();
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ITEM_ID="S".$ROW["ITEM_ID"];
   $title[$ITEM_NAME]=$ITEM_ID;
}
$query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
 $YES_OTHER=$ROW["YES_OTHER"];
}
if(MYOA_IS_UN == 1)
  $title+=array("NAME"=>"USER_NAME","ID"=>"USER_ID","MEMO"=>"MEMO","ALL_BASE"=>"ALL_BASE", "PENSION_BASE"=>"PENSION_BASE", "PENSION_U"=>"PENSION_U", "PENSION_P"=>"PENSION_P", "MEDICAL_BASE"=>"MEDICAL_BASE", "MEDICAL_U"=>"MEDICAL_U", "MEDICAL_P"=>"MEDICAL_P", "FERTILITY_BASE"=>"FERTILITY_BASE", "FERTILITY_U"=>"FERTILITY_U","UNEMPLOYMENT_BASE"=>"UNEMPLOYMENT_BASE", "UNEMPLOYMENT_U"=>"UNEMPLOYMENT_U", "UNEMPLOYMENT_P"=>"UNEMPLOYMENT_P", "INJURIES_BASE"=>"INJURIES_BASE", "INJURIES_U"=>"INJURIES_U", "HOUSING_BASE"=>"HOUSING_BASE", "HOUSING_U"=>"HOUSING_U","HOUSING_P"=>"HOUSING_P");
else
  $title+=array(_("����")=>"USER_NAME",_("�û���")=>"USER_ID",_("��ע")=>"MEMO",_("���ջ���")=>"ALL_BASE", _("���ϱ���")=>"PENSION_BASE", _("��λ����")=>"PENSION_U", _("��������")=>"PENSION_P", _("ҽ�Ʊ���")=>"MEDICAL_BASE", _("��λҽ��")=>"MEDICAL_U", _("����ҽ��")=>"MEDICAL_P", _("��������")=>"FERTILITY_BASE", _("��λ����")=>"FERTILITY_U", _("ʧҵ����")=>"UNEMPLOYMENT_BASE", _("��λʧҵ")=>"UNEMPLOYMENT_U", _("����ʧҵ")=>"UNEMPLOYMENT_P", _("���˱���")=>"INJURIES_BASE", _("��λ����")=>"INJURIES_U", _("ס��������")=>"HOUSING_BASE", _("��λס��")=>"HOUSING_U", _("����ס��")=>"HOUSING_P");

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];
require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE,$title);


$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID,USER_NAME from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}

$MSG_ERROR = array();
$ROW_COUNT=0;
$UPDATE_COUNT=0;
$INSERT_COUNT=0;
while($DATA = $objExcel->getNextRow())
{	
	$success=1;
	reset($title);
	$USER_ID="";
	$DATAS[$ROW_COUNT]=array("USER_ID" => $DATA["USER_ID"],"USER_NAME" => $DATA["USER_NAME"]);
	$STR_VALUE="";
	$STR_KEY="";
	$STR_UPDATE="";
	
	if($DATA['USER_ID']==""&&$DATA['USER_NAME']=="")
	{
		$MSG_ERROR[$ROW_COUNT]=_("<font color=red><b>�û������������������дһ��</b></font><br/>");
	    $success = 0;
	    $ROW_COUNT++;
	    continue;
	}
	else if($DATA['USER_ID']==""&&$DATA['USER_NAME']!="")
	{
		$USER_NAME=$DATA['USER_NAME'];
		$query="select USER_ID from USER where USER_NAME='".$DATA['USER_NAME']."'";
		$cur= exequery(TD::conn(),$query);
		$sum=mysql_num_rows($cur);
		if($sum>1)
		{
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("ϵͳ��������%s������Ա������ָ���û�����"), $USER_NAME)."</b></font><br>\n";
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
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("Ա��%s��δ��OAϵͳ��ע�ᣡ��"), $USER_NAME)."</b></font><br>\n";
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
			$DATAS[$ROW_COUNT]["USER_NAME"]=$USER_NAME;
		}
	}

	$USEFUL_COLUMN=0;
	foreach ($title as $key)
	{
	   $value=$DATA[$key];
	    if($value=="")
        continue;
		if($key!="USER_NAME"&&$key!="USER_ID")
		{
				$STR_KEY.=$key.",";
				$STR_VALUE.="'$value'".",";
				$STR_UPDATE.=$key."='".$value."',";
				$USEFUL_COLUMN++;
		}
	}//�ڲ�foreach

	if(substr($STR_KEY,-1)==",")
		$STR_KEY=substr($STR_KEY,0,-1);
	if(substr($STR_VALUE,-1)==",")
		$STR_VALUE=substr($STR_VALUE,0,-1);
	if(substr($STR_UPDATE,-1)==",")
		$STR_UPDATE=substr($STR_UPDATE,0,-1);


	if($USEFUL_COLUMN>0)
	{
			$USER_ID = $user_info_arr[$DATA["USER_ID"]]["USER_ID"];
	    if($success==1)
	    {
		  	$query2 = "SELECT * from HR_SAL_DATA where USER_ID='$USER_ID'";
            //echo $query2;
		  	$cursor2=exequery(TD::conn(),$query2);
		  	if(mysql_num_rows($cursor2)>0)
		  	{
		  		$query="update HR_SAL_DATA set ";
		  		$query.=$STR_UPDATE;
		  		$query.=" where USER_ID='$USER_ID'";
		  		exequery(TD::conn(),$query);
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s��н�ʻ����޸���ɣ�")."<br>", $USER_NAME);
		  		$UPDATE_COUNT++;
		  	}
		  	else
		  	{
		  		$query="insert into HR_SAL_DATA(USER_ID,";
		  		$query.=$STR_KEY.") values ('$USER_ID',".$STR_VALUE.")";
		  		exequery(TD::conn(),$query);
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s��н�ʻ���������ɣ�")."<br>", $USER_NAME);
		  		$INSERT_COUNT++;
		  	}
	  	}
	}
	else
	{
		$MSG_ERROR[$ROW_COUNT]="<font color=red><b>"._("н�ʻ���û�ж���!")."</b></font><br>";
		break;
	}
	$ROW_COUNT++;
}//���foreach

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
    <td><?=$DATAS[$I]["USER_NAME"]?></td>
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
