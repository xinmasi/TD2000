<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("导入结果");
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
  $title+=array(_("姓名")=>"USER_NAME",_("用户名")=>"USER_ID",_("备注")=>"MEMO",_("保险基数")=>"ALL_BASE", _("养老保险")=>"PENSION_BASE", _("单位养老")=>"PENSION_U", _("个人养老")=>"PENSION_P", _("医疗保险")=>"MEDICAL_BASE", _("单位医疗")=>"MEDICAL_U", _("个人医疗")=>"MEDICAL_P", _("生育保险")=>"FERTILITY_BASE", _("单位生育")=>"FERTILITY_U", _("失业保险")=>"UNEMPLOYMENT_BASE", _("单位失业")=>"UNEMPLOYMENT_U", _("个人失业")=>"UNEMPLOYMENT_P", _("工伤保险")=>"INJURIES_BASE", _("单位工伤")=>"INJURIES_U", _("住房公积金")=>"HOUSING_BASE", _("单位住房")=>"HOUSING_U", _("个人住房")=>"HOUSING_P");

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
		$MSG_ERROR[$ROW_COUNT]=_("<font color=red><b>用户名与姓名两项必须填写一项</b></font><br/>");
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
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("系统中有姓名%s的重名员工，请指定用户名！"), $USER_NAME)."</b></font><br>\n";
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
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("员工%s尚未在OA系统中注册！！"), $USER_NAME)."</b></font><br>\n";
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
			 $MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("系统中不存在用户名为 %s 的用户！"), $USER_ID)."</b></font><br>\n";
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
	}//内层foreach

	if(substr($STR_KEY,-1)==",")
		$STR_KEY=substr($STR_KEY,0,-1);
	if(substr($STR_VALUE,-1)==",")
		$STR_VALUE=substr($STR_VALUE,0,-1);
	if(substr($STR_UPDATE,-1)==",")
		$STR_UPDATE=substr($STR_UPDATE,0,-1);


	if($USEFUL_COLUMN>0)
	{
		/*
		//保险
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
		     		$BASE_ARR["ALL_BASE"]=$ROW2["ALL_BASE"]; //保险基数
		     		$BASE_ARR["PENSION_BASE"]=$ROW2["PENSION_BASE"]; //养老保险
		     		$BASE_ARR["PENSION_U"]=$ROW2["PENSION_U"]; //单位养老
		     		$BASE_ARR["PENSION_P"]=$ROW2["PENSION_P"]; //个人养老
		     		$BASE_ARR["MEDICAL_BASE"]=$ROW2["MEDICAL_BASE"]; //医疗保险
		     		$BASE_ARR["MEDICAL_U"]=$ROW2["MEDICAL_U"]; //单位医疗
		     		$BASE_ARR["MEDICAL_P"]=$ROW2["MEDICAL_P"]; //个人医疗
		     		$BASE_ARR["FERTILITY_BASE"]=$ROW2["FERTILITY_BASE"]; //生育保险
		     		$BASE_ARR["FERTILITY_U"]=$ROW2["FERTILITY_U"]; //单位生育
		     		$BASE_ARR["UNEMPLOYMENT_BASE"]=$ROW2["UNEMPLOYMENT_BASE"]; //失业保险
		     		$BASE_ARR["UNEMPLOYMENT_U"]=$ROW2["UNEMPLOYMENT_U"]; //单位失业
		     		$BASE_ARR["UNEMPLOYMENT_P"]=$ROW2["UNEMPLOYMENT_P"]; //个人失业
		     		$BASE_ARR["INJURIES_BASE"]=$ROW2["INJURIES_BASE"]; //工伤保险
		     		$BASE_ARR["INJURIES_U"]=$ROW2["INJURIES_U"]; //单位工伤
		     		$BASE_ARR["HOUSING_BASE"]=$ROW2["HOUSING_BASE"]; //住房公积金
		     		$BASE_ARR["HOUSING_U"]=$ROW2["HOUSING_U"]; //单位住房
		     		$BASE_ARR["HOUSING_P"]=$ROW2["HOUSING_P"]; //个人住房
		
		     		//$BASE_ARR["INSURANCE_DATE"]=$ROW2["INSURANCE_DATE"]; //投保时间
		     		$BASE_ARR["INSURANCE_OTHER"]=1; //是否投保
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
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("员工%s的工资修改完成！")."<br>", $USER_NAME);
		  		$UPDATE_COUNT++;
		  	}
		  	else
		  	{
		  		$query="insert into SAL_DATA(FLOW_ID,USER_ID,IS_DEPT_INPUT,IS_FINA_INPUT,";
		  		$query.=$STR_KEY.") values ('$FLOW_ID','$USER_ID','1','1',".$STR_VALUE.")";
		  		exequery(TD::conn(),$query);
		  		$MSG_ERROR[$ROW_COUNT]=sprintf(_("员工%s的工资导入完成！")."<br>", $USER_NAME);
		  		$INSERT_COUNT++;
		  	}
	  	}
	}
	else
	{
		$MSG_ERROR[$ROW_COUNT]="<font color=red><b>"._("工资项目没有定义!")."</b></font><br>";
		break;
	}
	$ROW_COUNT++;
}//外层foreach

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
  	<td nowrap align="center"><?=_("编号")?></td>
    <td nowrap align="center"><?=_("用户名")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
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
	$MESSAGE=sprintf(_("共%s条数据导入！"), $INSERT_COUNT);
	Message("", $MESSAGE);
}
if($UPDATE_COUNT>0)
{
	$MESSAGE=sprintf(_("共%s条数据更新！"), $UPDATE_COUNT);
	Message("", $MESSAGE);
}
if($INSERT_COUNT<=0 && $UPDATE_COUNT<=0)
{
	$MESSAGE=_("导入失败！");
	Message("", $MESSAGE);
}
Button_Back();
?>
