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
		/*
		//����
 		$BASE_STR="";
 		$BASE_KEY="";
 		$BASE_VALUE="";
		if($YES_OTHER==1)
		{
		      $query2="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
		      $cursor2= exequery(TD::conn(),$query2);
		      if($ROW2=mysql_fetch_array($cursor2))
		      {
		     		$BASE_ARR=array();
		     		$BASE_ARR["ALL_BASE"]=$ROW2["ALL_BASE"]; //���ջ���
		     		$BASE_ARR["PENSION_BASE"]=$ROW2["PENSION_BASE"]; //���ϱ���
		     		$BASE_ARR["PENSION_U"]=$ROW2["PENSION_U"]; //��λ����
		     		$BASE_ARR["PENSION_P"]=$ROW2["PENSION_P"]; //��������
		     		$BASE_ARR["MEDICAL_BASE"]=$ROW2["MEDICAL_BASE"]; //ҽ�Ʊ���
		     		$BASE_ARR["MEDICAL_U"]=$ROW2["MEDICAL_U"]; //��λҽ��
		     		$BASE_ARR["MEDICAL_P"]=$ROW2["MEDICAL_P"]; //����ҽ��
		     		$BASE_ARR["FERTILITY_BASE"]=$ROW2["FERTILITY_BASE"]; //��������
		     		$BASE_ARR["FERTILITY_U"]=$ROW2["FERTILITY_U"]; //��λ����
		     		$BASE_ARR["UNEMPLOYMENT_BASE"]=$ROW2["UNEMPLOYMENT_BASE"]; //ʧҵ����
		     		$BASE_ARR["UNEMPLOYMENT_U"]=$ROW2["UNEMPLOYMENT_U"]; //��λʧҵ
		     		$BASE_ARR["UNEMPLOYMENT_P"]=$ROW2["UNEMPLOYMENT_P"]; //����ʧҵ
		     		$BASE_ARR["INJURIES_BASE"]=$ROW2["INJURIES_BASE"]; //���˱���
		     		$BASE_ARR["INJURIES_U"]=$ROW2["INJURIES_U"]; //��λ����
		     		$BASE_ARR["HOUSING_BASE"]=$ROW2["HOUSING_BASE"]; //ס��������
		     		$BASE_ARR["HOUSING_U"]=$ROW2["HOUSING_U"]; //��λס��
		     		$BASE_ARR["HOUSING_P"]=$ROW2["HOUSING_P"]; //����ס��
		
		     		//$BASE_ARR["INSURANCE_DATE"]=$ROW2["INSURANCE_DATE"]; //Ͷ��ʱ��
		     		$BASE_ARR["INSURANCE_OTHER"]=1; //�Ƿ�Ͷ��
		     		foreach($BASE_ARR as $base_key => $base_value)
		     		{
		     			$BASE_STR.=$base_key."='".$base_value."',";
		     			$BASE_KEY.=$base_key.",";
		     			$BASE_VALUE.=$base_value.",";
		     		}
		       	if(substr($BASE_STR,-1)==",")
		       		$BASE_STR=substr($BASE_STR,0,-1);
		       	if(substr($BASE_KEY,-1)==",")
		       		$BASE_KEY=substr($BASE_KEY,0,-1);
		       	if(substr($BASE_VALUE,-1)==",")
		       		$BASE_VALUE=substr($BASE_VALUE,0,-1);
		       	$BASE_VALUE=','.$BASE_VALUE;
		       	$BASE_KEY=','.$BASE_KEY;
		       	$BASE_STR=','.$BASE_STR;
		    	  }
    		}
    		*/
			$USER_ID = $user_info_arr[$DATA["USER_ID"]]["USER_ID"];
	    if($success==1)
	    {
		  	$query2 = "SELECT * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
		  	$cursor2=exequery(TD::conn(),$query2);
		  	if(mysql_num_rows($cursor2)>0)
		  	{
		  		$query="update SAL_DATA set IS_DEPT_INPUT='1',IS_FINA_INPUT='1',";
		  		$query.=$STR_UPDATE;
		  		$query.=" where FLOW_ID=$FLOW_ID and USER_ID='$USER_ID'";
		  		exequery(TD::conn(),$query);
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s�Ĺ����޸���ɣ�")."<br>", $USER_NAME);
		  		$UPDATE_COUNT++;
		  	}
		  	else
		  	{
		  		$query="insert into SAL_DATA(FLOW_ID,USER_ID,IS_DEPT_INPUT,IS_FINA_INPUT,";
		  		$query.=$STR_KEY.") values ('$FLOW_ID','$USER_ID','1','1',".$STR_VALUE.")";
		  		exequery(TD::conn(),$query);
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("Ա��%s�Ĺ��ʵ�����ɣ�")."<br>", $USER_NAME);
		  		$INSERT_COUNT++;
		  	}
	  	}
	}
	else
	{
		$MSG_ERROR[$ROW_COUNT]="<font color=red><b>"._("������Ŀû�ж���!")."</b></font><br>";
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
