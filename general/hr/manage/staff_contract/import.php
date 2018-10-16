<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("分组管理");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
if(strtolower(substr($_FILES["EXCEL_FILE"]["name"],-3))!="xls")
{
   Message(_("错误"),_("只能导入Excel文件!"));
   Button_Back();
   exit;
}
if(MYOA_IS_UN == 1)
{
   $title=array("USER_ID"=>"USER_ID","STAFF_NAME"=>"STAFF_NAME","STAFF_CONTRACT_NO"=>"STAFF_CONTRACT_NO","CONTRACT_TYPE"=>"CONTRACT_TYPE","STATUS"=>"STATUS","CONTRACT_SPECIALIZATION"=>"CONTRACT_SPECIALIZATION","CONTRACT_ENTERPRIES"=>"CONTRACT_ENTERPRIES","MAKE_CONTRACT"=>"MAKE_CONTRACT","PROBATION_EFFECTIVE_DATE"=>"PROBATION_EFFECTIVE_DATE","CONTRACT_END_TIME"=>"CONTRACT_END_TIME","IS_TRIAL"=>"IS_TRIAL","TRAIL_OVER_TIME"=>"TRAIL_OVER_TIME","PASS_OR_NOT"=>"PASS_OR_NOT","REMOVE_OR_NOT"=>"REMOVE_OR_NOT","CONTRACT_REMOVE_TIME"=>"CONTRACT_REMOVE_TIME","IS_RENEW"=>"IS_RENEW","RENEW_TIME"=>"RENEW_TIME","REMARK"=>"REMARK");
   $fieldAttr = array("MAKE_CONTRACT"=>"date","PROBATION_EFFECTIVE_DATE"=>"date","CONTRACT_END_TIME"=>"date","TRAIL_OVER_TIME"=>"date","CONTRACT_REMOVE_TIME"=>"date");
}
else
{
   $title=array(_("用户名")=>"USER_ID",_("姓名")=>"STAFF_NAME",_("合同编号")=>"STAFF_CONTRACT_NO",_("合同类型")=>"CONTRACT_TYPE",_("合同状态")=>"STATUS",_("合同期限属性")=>"CONTRACT_SPECIALIZATION",_("合同签约公司")=>"CONTRACT_ENTERPRIES",_("合同签订日期")=>"MAKE_CONTRACT",_("合同生效日期")=>"PROBATION_EFFECTIVE_DATE",_("合同终止日期")=>"CONTRACT_END_TIME",_("是否含试用期")=>"IS_TRIAL",_("试用截止日期")=>"TRAIL_OVER_TIME",_("雇员是否转正")=>"PASS_OR_NOT",_("合同是否已解除")=>"REMOVE_OR_NOT",_("合同解除日期")=>"CONTRACT_REMOVE_TIME",_("合同是否续签")=>"IS_RENEW",_("续签到期日期")=>"RENEW_TIME",_("备注")=>"REMARK");
   $fieldAttr = array(_("合同签订日期")=>"date",_("合同生效日期")=>"date",_("合同终止日期")=>"date",_("试用截止日期")=>"date",_("合同解除日期")=>"date");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user where DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}

//------------------- 信息提示日期 -----------------------
$query = "SELECT PARA_VALUE from  sys_para where PARA_NAME='TRIAL_LABOR_DAY'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $TRIAL_LABOR_DAY=explode(",",$ROW['PARA_VALUE']);
}


require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
$SUCC_COUNT=0;
$ROW_COUNT = 0;
$DATAS=array();
while($DATA = $objExcel->getNextRow())
{
	//$DATAS[$ROW_COUNT]=$DATA;
	$DATAS [] = $DATA;
	
	$ID_STR="";
	$VALUE_STR="";
	$STR_UPDATE="";
	$success=1;
	
	
	//初始化参数
	$STAFF_CONTRACT_NO    = "";
	$CONTRACT_END_TIME    = "";
	$REMIND_TIME          = "";
	$MAKE_CONTRACT        = "";
	$IS_TRIAL             = "";
	$TRAIL_OVER_TIME      = "";
	$PASS_OR_NOT          = "";
	$REMOVE_OR_NOT        = "";
	$CONTRACT_REMOVE_TIME = "";
	$IS_RENEW             = "";
	$RENEW_TIME           = "";
	$USER_IDS             = "";
	$CONTRACT_TYPE        = "";
	
	//reset($title);
	foreach($DATA as $key => $value)
	{
		/*if(find_id($ID_STR, $key))
		{
			$ROW_COUNT++;
			continue 2;
		}
		$value=$DATA[$key];*/
		
		if($key=="CONTRACT_TYPE")
		{
			$CONTRACT_TYPE = "";
			$query1="select CODE_NO from HR_CODE where PARENT_NO='HR_STAFF_CONTRACT1' and CODE_NAME='$value'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $value="$ROW[0]";	
            }
            else
            {
                $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("合同类型未匹配")."</font>";
				$ROW_COUNT++;
				$success=0;
				continue 2;
            }
			$CONTRACT_TYPE=$value;	
        }
		if($key=="CONTRACT_END_TIME")
		{
			$CONTRACT_END_TIME = $value;
		}
		if($key=="CONTRACT_REMOVE_TIME")
		{
			$CONTRACT_REMOVE_TIME = $value;
	    }
        if($key=="RENEW_TIME")
        {
            if(!stripos($value,"|") && $value!="")
            {
                $n = intval(($value - 25569) * 3600 * 24); //转换成1970年以来的秒数
                $value = gmdate('Y-m-d', $n);//格式化时间
				$RENEW_TIME = $value;
				
				$ta = strtotime($CONTRACT_END_TIME);
				$tb = strtotime($value);
				if($ta>$tb)
				{
					$MSG_ERROR[$ROW_COUNT]="<font color=red>"._("续签到期日期不能小于合同终止日期")."</font>";
					$ROW_COUNT++;
					$success=0;
					continue 2;
				}
            }else
			{
				 if(substr($value,-1)=="|")
				 	$values=substr($value,0,-1);
					
				$RENEW_TIME_ARRAY = explode("|", $values);				
				$RENEW_TIME       = end($RENEW_TIME_ARRAY);
			}
        }
        if($key=="CONTRACT_ENTERPRIES")
        {
            $query1="select CODE_NO from HR_CODE where PARENT_NO='HR_ENTERPRISE' and CODE_NAME='$value'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $value="$ROW[0]";
            }
            else
            {
                $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("合同签约公司未匹配")."</font>";
				$ROW_COUNT++;
				$success=0;
				continue 2;
            }
		}
		if($key=="STATUS")
        {
            $query1="select CODE_NO from HR_CODE where PARENT_NO='HR_STAFF_CONTRACT2' and CODE_NAME='$value'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                    $value="$ROW[0]";
            }
            else
            {
                $value="";
            }
		}
		if($key=="CONTRACT_SPECIALIZATION")
		{
			if($value==_("有固定期限"))
				$value=1;
			else if($value==_("无固定期限"))
				$value=2;
			else if($value==_("以完成一定工作任务为期限"))
				$value=3;
		}
		if($key=="PASS_OR_NOT")//是否转正
		{
			if($value==_("是"))
				$value=1;
			else if($value==_("否"))
				$value=0;
				
			$PASS_OR_NOT = $value;
		}
		if($key=="IS_RENEW")//是否续签
		{
			if($value==_("是"))
				$value=1;
			else if($value==_("否"))
				$value=0;
				
			$IS_RENEW = $value;
		}
		if($key=="REMOVE_OR_NOT")//合同是否解除
		{
			if($value==_("是"))
				$value=1;
			else if($value==_("否"))
				$value=0;
				
			$REMOVE_OR_NOT = $value;
		}
		if($key=="IS_TRIAL")//试用期
		{
			if($value==_("是"))
				$value=1;
			else if($value==_("否"))
				$value=0;
				
			$IS_TRIAL = $value;
		}
		if($key=="TRAIL_OVER_TIME")
		{
			$TRAIL_OVER_TIME = $value;
		}
		
		if($key=="USER_ID")
			$USER_IDS=$value;
		if($key=="STAFF_NAME")
			$STAFF_NAME=$value;
		
		if($key=="STAFF_CONTRACT_NO")
		{
			if($value=="")
			{
				$MSG_ERROR[$ROW_COUNT]="<font color=red>"._("合同编号为空，未导入")."</font>";
				$ROW_COUNT++;
				$success=0;
				continue 2;
				
			}else
			{
				$STAFF_CONTRACT_NO=$value;
				$query3="select * from HR_STAFF_CONTRACT where STAFF_CONTRACT_NO='$STAFF_CONTRACT_NO'";	
				$cursor3=exequery(TD::conn(),$query3);
				$count=mysql_num_rows($cursor3);
				if($count > 0)
				{
					$MSG_ERROR[$ROW_COUNT]="<font color=red>"._("合同编号重复，未导入")."</font>";
					$ROW_COUNT++;
					$success=0;
					continue 2;
				 }
			}
			
		}		 
		if($USER_IDS=="")
		{
			$MSG_ERROR[$ROW_COUNT]="<font color=red>"._("用户名不能为空")."</font>";
			$ROW_COUNT++;
			$success=0;
			continue 2;
		}
		 if($USER_IDS!="")
		 {
			 if(!$user_info_arr[$USER_IDS]['USER_ID'])
			 {
				 $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("用户不存在或已离职")."</font>";
				 $ROW_COUNT++;
				 $success=0;
				 continue 2;
			 }
			 else
			 {
				 $THIS_USER_ID = $user_info_arr[$USER_IDS]['USER_ID'];
				 $THIS_BYNAME  = $user_info_arr[$USER_IDS]['BYNAME'];
			 }
		 }
		 if($THIS_USER_ID!="" && $STAFF_NAME!="" && $key=="STAFF_NAME")
		 {
			 $sql = "SELECT USER_ID FROM user WHERE USER_ID = '$THIS_USER_ID' AND USER_NAME = '$STAFF_NAME'";
			 $cursor= exequery(TD::conn(),$sql);
			 if(!mysql_affected_rows()>0)
			 {
				 $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("用户名与姓名不匹配")."</font>";
				 $ROW_COUNT++;
				 $success=0;
				 continue 2;
			 }
			 
		 }
		 /*if($USER_IDS=="")
		 {
			 if($STAFF_NAME!="")
			 {
				 $USER_ID="";
				 $query2="select USER_ID FROM user where USER_NAME='$STAFF_NAME'";
				 $cursor2=exequery(TD::conn(),$query2);
				 if($ROW2=mysql_fetch_array($cursor2))
					$THIS_USER_ID=$ROW2["USER_ID"];
				 if($THIS_USER_ID=="")
				 {
					 $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("用户不存在")."</font>";
					 $ROW_COUNT++;
					 $success=0;
					 continue 2;
				 }
			 }
		}*/
		if($THIS_USER_ID!="" && $CONTRACT_TYPE!="")
		{
			$query4 = "SELECT CONTRACT_END_TIME from  hr_staff_contract where STAFF_NAME='$THIS_USER_ID' and CONTRACT_TYPE='$CONTRACT_TYPE'";
			$cursor4= exequery(TD::conn(),$query4);
			if($ROW4=mysql_fetch_array($cursor4))
			{
				$MSG_ERROR[$ROW_COUNT]="<font color=red>"._("合同信息已存在")."</font>";
				$ROW_COUNT++;
				$success=0;
				continue 2;
			}
		}
		//计算出提醒时间
		if($IS_TRIAL==1 && $PASS_OR_NOT==0 && $REMOVE_OR_NOT==0 && $IS_RENEW==0)
		{
			if($TRIAL_LABOR_DAY[0]!="")
			{
				$REMIND_TIME=date('Y-m-d   H:i:s',strtotime($TRAIL_OVER_TIME."-".$TRIAL_LABOR_DAY[0]." days +9 hours"));
			}
		}
		if($PASS_OR_NOT==1 && $REMOVE_OR_NOT==0 && $IS_RENEW==0)
		{
			if($TRIAL_LABOR_DAY[1]!="")
			{
				$REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_END_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
			}
		}
		if($REMOVE_OR_NOT==1 && $IS_RENEW==0)
		{
			if($TRIAL_LABOR_DAY[1]!="")
			{
				$REMIND_TIME=date('Y-m-d   H:i:s',strtotime($CONTRACT_REMOVE_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
			}
		}
		if($IS_RENEW==1)
		{
			if($TRIAL_LABOR_DAY[1]!="")
			{
				$REMIND_TIME=date('Y-m-d   H:i:s',strtotime($RENEW_TIME."-".$TRIAL_LABOR_DAY[1]." days +9 hours"));
			}
		}

		if($key!="USER_ID"&&$key!="STAFF_NAME"&&$key!="STAFF_CONTRACT_NO")
		{
			$ID_STR.=$key.",";
			$VALUE_STR.="'".$value."',";
		} 
   } 
   if($success==1)
   {
     $ID_STR=trim($ID_STR,",");
     $VALUE_STR=trim($VALUE_STR,",");
     $query="insert into HR_STAFF_CONTRACT (CREATE_USER_ID,STAFF_NAME,CREATE_DEPT_ID,STAFF_CONTRACT_NO,ADD_TIME,LAST_UPDATE_TIME,".$ID_STR.",REMIND_TIME,REMIND_TYPE,USER_NAME) values ('".$_SESSION["LOGIN_USER_ID"]."','$THIS_USER_ID','".$_SESSION["LOGIN_DEPT_ID"]."','$STAFF_CONTRACT_NO','$CUR_TIME','$CUR_TIME',".$VALUE_STR.",'$REMIND_TIME','1','THIS_BYNAME')";
     exequery(TD::conn(),$query);
     $CONTRACT_ID=mysql_insert_id();
     $SUCC_COUNT++;
     $ROW_COUNT++;
   }
   else
   {
	   $ROW_COUNT++;
	   continue;
   }
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("合同编号")?></td>
    <td nowrap align="center"><?=_("签署公司")?></td>
    <td nowrap align="center"><?=_("合同类型")?></td>
    <td nowrap align="center"><?=_("合同签订日期")?></td>
    <td nowrap align="center"><?=_("合同状态")?></td>    
    <td nowrap align="center"><?=_("状态")?></td>
  </thead>
<?
for($I=0;$I< count($DATAS);$I++)
{
?>
  <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
    <td><?=$DATAS[$I]["STAFF_NAME"]?>(<?=$DATAS[$I]["USER_ID"]?>)</td>
    <td><?=$DATAS[$I]["STAFF_CONTRACT_NO"]?></td>    
    <td><?=$DATAS[$I]["CONTRACT_ENTERPRIES"]?></td>
    <td><?=$DATAS[$I]["CONTRACT_TYPE"]?></td>
    <td><?=$DATAS[$I]["MAKE_CONTRACT"]?></td>
    <td><?=$DATAS[$I]["STATUS"]?></td>    
    <td align="center"><? echo (!isset($MSG_ERROR[$I]))?_("成功"):$MSG_ERROR[$I];?></td>
  </tr>
<?
}
?>
</table>
<?
Message("",sprintf(_("共%s条数据导入成功!"), $SUCC_COUNT));
Button_Back();
?>
</body>
</html>
