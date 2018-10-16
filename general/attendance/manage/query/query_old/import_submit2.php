<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
  
if(strtolower(substr($FILE_NAME2,-3))!="xls")
{
   Message(_("错误"),_("只能导入Excel文件!"));
   Button_Back();
   exit;
}

if(MYOA_IS_UN == 1){
   $title=array("NAME"=>"USER_NAME","ID"=>"USER_ID","REGISTRATION_TIME"=>"REGISTER_TIME","REGISTER_IP"=>"REGISTER_IP","MEMO"=>"REMARK");
   $fieldAttr = array("REGISTRATION_TIME" => "datetime");
}else{
   $title=array(_("姓名")=>"USER_NAME",_("用户名")=>"USER_ID",_("登记时间")=>"REGISTER_TIME",_("登记IP")=>"REGISTER_IP",_("备注")=>"REMARK");
   $fieldAttr = array(_("登记时间") => "datetime");
}
$ROW_COUNT = 0;
$SUCC_COUNT =0;

$EXCEL_FILE2 = $_FILES['EXCEL_FILE']['tmp_name'];

$data=file_get_contents($EXCEL_FILE2);

if(!$data)
{
   Message(_("错误"),_("打开文件错误!"));
   Button_Back();
   exit;
}

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID,USER_NAME from user where NOT_LOGIN='0' AND DEPT_ID!='0'";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
	$user_info_arr[$row['BYNAME']] = $row;
}

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE2, $title, $fieldAttr);
$MSG_ERROR = array();
while($line = $objExcel->getNextRow())
{
   $STR_VALUE="";
   $STR_KEY="";
   $MSG_ERROR[$ROW_COUNT]=_("成功");
   $success=1;
   $lines[$ROW_COUNT]=$line;
	if($line['USER_ID']==""&&$line['USER_NAME']=="")
	{
		$MSG_ERROR[$ROW_COUNT]=_("<font color=red><b>用户名与姓名两项必须填写一项</b></font><br/>");
        $success = 0;
        $ROW_COUNT++;
        continue;
	}
	else if($line['USER_ID']==""&&$line['USER_NAME']!="")
	{
		$USER_NAME=$line['USER_NAME'];
		$query="select USER_ID from USER where USER_NAME='".$line['USER_NAME']."'";
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
			$USER_ID=$lines[$ROW_COUNT]['USER_ID']=$ROW['USER_ID'];
		}
		else
		{
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("员工%s尚未在OA系统中注册！！"), $USER_NAME)."</b></font><br>\n";
			$success=0;
			$ROW_COUNT++;
			continue;
		}
	}
	else if($line['USER_ID']!="")
	{
		$USER_ID=$line["USER_ID"];
		//$USER_NAME=trim(GetUserNameById($USER_ID),",");
		if(!$user_info_arr[$USER_ID]['USER_NAME'])
		{
			 $MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("系统中不存在用户名为 %s 的用户！"), $USER_ID)."</b></font><br>\n";
			 $success=0;
			 $ROW_COUNT++;
			 continue;
		}else
		{
			$USER_NAME = $user_info_arr[$USER_ID]['USER_NAME'];
			$lines[$ROW_COUNT]["USER_NAME"]=$USER_NAME;
		}	
	}
	
  foreach ($line as $key => $value) 
  {
     $value=ltrim($value);	  
     if ($key!="USER_ID"&&$key!="USER_NAME")
 		 	$STR_KEY.=$key.",";
    
     if ($key=="REGISTER_TIME")
 	 {
        $REGISTER_TIME=$value; 
        $value=$REGISTER_TYPE."','".$value;
        $line_tem["REGISTER_TYPE"]=$REGISTER_TYPE;   
     }
 	if ($key!="USER_ID"&&$key!="USER_NAME")
    	$STR_VALUE.="'$value',";
   }//end foreach 
   
	  
   if (substr($STR_KEY,-1)==",")
      $STR_KEY=substr($STR_KEY,0,-1);
   if (substr($STR_VALUE,-1)==",")
      $STR_VALUE=substr($STR_VALUE,0,-1);    
   $ROW_COUNT++;
    if($USER_ID!="")
    	$STR_VALUE="'".$USER_ID."',".$STR_VALUE;
    else
    	continue;
    	

		
		if($success) 
		{
			$SUCC_COUNT++;
			$query="insert into ATTEND_DUTY_SHIFT(USER_ID,REGISTER_TYPE,".$STR_KEY.") values (".$STR_VALUE.")";
			exequery(TD::conn(),$query);    
		}
}
if(file_exists($EXCEL_FILE2))
   @unlink($EXCEL_FILE2);
   
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
  	<td nowrap align="center"><?=_("编号")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("登记次序")?></td>
    <td nowrap align="center"><?=_("登记时间")?></td>
    <td nowrap align="center"><?=_("登记IP")?></td>
    <td nowrap align="center"><?=_("备注")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
  </thead>
<?
for($I=0;$I< count($lines);$I++)
{
?>
  <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
  	<td><?=($I+1)?></td>
    <td><?=($lines[$I]["USER_ID"]==""?($lines[$I]["USER_NAME"]):substr((GetUserNameById($lines[$I]["USER_ID"])),0,-1))?></td>
    <td><?=$lines[$I]["REGISTER_TYPE"]?></td>
    <td><?=$lines[$I]["REGISTER_TIME"]?></td>     
    <td><?=$lines[$I]["REGISTER_IP"]?></td>
    <td><?=$lines[$I]["REMARK"]?></td>
    <td align="left"><?=$MSG_ERROR[$I]?></td>
  </tr>
<?
}
?>
</table>
<?
$MSG2 = sprintf(_("共%d条数据导入成功!"), $SUCC_COUNT);
Message("",$MSG2);
Button_Back();
?>