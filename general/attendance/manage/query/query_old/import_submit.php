<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

//ȡ����Ǽ�ʱ���----��ʼ----
$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_BEFORE1=$ROW["PARA_VALUE"];
   
$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_AFTER1=$ROW["PARA_VALUE"];
   
$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_BEFORE2=$ROW["PARA_VALUE"];
   
$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_AFTER2=$ROW["PARA_VALUE"]; 
//ȡ����Ǽ�ʱ���-----����---   
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
   Button_Back();
   exit;
}

if(MYOA_IS_UN == 1){
   $title=array("NAME"=>"USER_NAME","ID"=>"USER_ID","REGISTRATION_TIME"=>"REGISTER_TIME","REGISTER_IP"=>"REGISTER_IP","MEMO"=>"REMARK");
   $fieldAttr = array("REGISTRATION_TIME" => "datetime");
}else{
   $title=array(_("����")=>"USER_NAME",_("�û���")=>"USER_ID",_("�Ǽ�ʱ��")=>"REGISTER_TIME",_("�Ǽ�IP")=>"REGISTER_IP",_("��ע")=>"REMARK");
   $fieldAttr = array(_("�Ǽ�ʱ��") => "datetime");
}

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

$ROW_COUNT = 0;
$SUCC_COUNT =0;
$data=file_get_contents($EXCEL_FILE);

if(!$data)
{
   Message(_("����"),_("���ļ�����!"));
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
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
//if(strpos($data,"\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1")!==FALSE)
//{
//   Message(_("����"),_("��Ҫ���޸��ļ���չ���ķ�ʽ�����EXCEL��ʽ���ļ�!��ʹ��Excel�ļ��˵��µ�\"���Ϊ\"��ѡ�񱣴��ʽ��"));
//   Button_Back();
//   exit;
//}
//
//$lines=CSV2Array($data, $title);
$MSG_ERROR = array();
while($line = $objExcel->getNextRow())
{
   $STR_VALUE="";
   $STR_KEY="";
   $MSG_ERROR[$ROW_COUNT]=_("�ɹ�");
   $success=1;
   $lines[$ROW_COUNT]=$line;
	if($line['USER_ID']==""&&$line['USER_NAME']=="")
	{
		$MSG_ERROR[$ROW_COUNT]=_("<font color=red><b>�û������������������дһ��</b></font><br/>");
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
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("ϵͳ��������%s������Ա������ָ���û�����"), $USER_NAME)."</b></font><br>\n";
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
			$MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("Ա��%s��δ��OAϵͳ��ע�ᣡ��"), $USER_NAME)."</b></font><br>\n";
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
			 $MSG_ERROR[$ROW_COUNT]="<font color=red><b>".sprintf(_("ϵͳ�в������û���Ϊ %s ���û���"), $USER_ID)."</b></font><br>\n";
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
      //ȡ�Ű����ͼ����°�Ǽ�ʱ��----��ʼ---
 	  $query = "SELECT c.DUTY_TYPE,b.DUTY_TIME1, b.DUTY_TIME2, b.DUTY_TIME3, b.DUTY_TIME4, b.DUTY_TIME5, b.DUTY_TIME6,b.DUTY_NAME, b.GENERAL FROM USER a LEFT JOIN USER_EXT c ON a.UID = c.UID LEFT JOIN ATTEND_CONFIG b on c.DUTY_TYPE=b.DUTY_TYPE where a.USER_ID='{$user_info_arr[$USER_ID]['USER_ID']}'";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
      {
         $DUTY_TYPE=$ROW["DUTY_TYPE"];
         $DUTY_NAME =$ROW["DUTY_NAME"];
         $GENERAL=$ROW["GENERAL"];
         $DUTY_TIME1=$ROW["DUTY_TIME1"];
         $DUTY_TIME2=$ROW["DUTY_TIME2"];
         $DUTY_TIME3=$ROW["DUTY_TIME3"];
         $DUTY_TIME4=$ROW["DUTY_TIME4"];
         $DUTY_TIME5=$ROW["DUTY_TIME5"];
         $DUTY_TIME6=$ROW["DUTY_TIME6"];  
      } 
      //ȡ�Ű����ͼ����°�Ǽ�ʱ��----����---
      if($value!="")
      {
          $timearray = explode(" ",$value);
          $time=$timearray[0];
          $DUTY_TIME11 =$DUTY_TIME1; 
          $DUTY_TIME1=$time." ".$DUTY_TIME1;
          $DUTY_TIME12 =$DUTY_TIME2;
          $DUTY_TIME2=$time." ".$DUTY_TIME2;
          $DUTY_TIME13 =$DUTY_TIME3;
          $DUTY_TIME3=$time." ".$DUTY_TIME3;
          $DUTY_TIME14 =$DUTY_TIME4;          
          $DUTY_TIME4=$time." ".$DUTY_TIME4;
          $DUTY_TIME15 =$DUTY_TIME5;  
          $DUTY_TIME5=$time." ".$DUTY_TIME5;
          $DUTY_TIME16 =$DUTY_TIME6;  
          $DUTY_TIME6=$time." ".$DUTY_TIME6;
          $REGISTER_TYPE = 0;
          if($DUTY_TIME11!=""&&strtotime($value)>=(strtotime($DUTY_TIME1)-$DUTY_INTERVAL_BEFORE1*60) && strtotime($value)<=(strtotime($DUTY_TIME1)+$DUTY_INTERVAL_AFTER1*60))
             $REGISTER_TYPE=1;
          if($DUTY_TIME12!=""&&strtotime($value)>=(strtotime($DUTY_TIME2)-$DUTY_INTERVAL_BEFORE2*60) && strtotime($value)<=(strtotime($DUTY_TIME2)+$DUTY_INTERVAL_AFTER2*60))
          	 $REGISTER_TYPE=2;
          if($DUTY_TIME13!=""&&strtotime($value)>=(strtotime($DUTY_TIME3)-$DUTY_INTERVAL_BEFORE1*60) && strtotime($value)<=(strtotime($DUTY_TIME3)+$DUTY_INTERVAL_AFTER2*60))
          	 $REGISTER_TYPE=3;
          if($DUTY_TIME14!=""&&strtotime($value)>=(strtotime($DUTY_TIME4)-$DUTY_INTERVAL_BEFORE2*60) && strtotime($value)<=(strtotime($DUTY_TIME4)+$DUTY_INTERVAL_AFTER2*60))
          	 $REGISTER_TYPE=4;
          if($DUTY_TIME15!=""&&strtotime($value)>=(strtotime($DUTY_TIME5)-$DUTY_INTERVAL_BEFORE1*60) && strtotime($value)<=(strtotime($DUTY_TIME5)+$DUTY_INTERVAL_AFTER2*60))
          	 $REGISTER_TYPE=5;
          if($DUTY_TIME16!=""&&strtotime($value)>=(strtotime($DUTY_TIME6)-$DUTY_INTERVAL_BEFORE2*60) && strtotime($value)<=(strtotime($DUTY_TIME6)+$DUTY_INTERVAL_AFTER2*60))
          	 $REGISTER_TYPE=6;
      }
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
    	$STR_VALUE="'".$user_info_arr[$USER_ID]['USER_ID']."',".$STR_VALUE;
    else
    	continue;

		if($success) 
		{
			$query_tmp = "SELECT REGISTER_TIME from ATTEND_DUTY where USER_ID='{$user_info_arr[$USER_ID]['USER_ID']}' and REGISTER_TYPE='$REGISTER_TYPE' and REGISTER_TIME='$REGISTER_TIME'";
			$cursor_tmp = exequery(TD::conn(),$query_tmp);
			if(!$ROW_tmp=mysql_fetch_array($cursor_tmp))
			{
				$query_tmp="insert into ATTEND_DUTY (USER_ID,REGISTER_TYPE,".$STR_KEY.",DUTY_TYPE) values (".$STR_VALUE.",'$DUTY_TYPE')";
				exequery(TD::conn(),$query_tmp);
				$SUCC_COUNT++;
			}
			else
			{
				$lines[$ROW_COUNT-1]="";
				$tmp_REGISTER_TIME = $ROW_tmp['REGISTER_TIME'];
				if(($REGISTER_TYPE%2==0) && strtotime($tmp_REGISTER_TIME) < strtotime($REGISTER_TIME))
				{
					$query_tmp="update ATTEND_DUTY set REGISTER_TIME='$REGISTER_TIME' where USER_ID='{$user_info_arr[$USER_ID]['USER_ID']}' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME') ";
					exequery(TD::conn(),$query_tmp);
				}
				elseif(($REGISTER_TYPE%2!=0) && strtotime($tmp_REGISTER_TIME) > strtotime($REGISTER_TIME))
				{
					$query_tmp="update ATTEND_DUTY set REGISTER_TIME='$REGISTER_TIME' where USER_ID='{$user_info_arr[$USER_ID]['USER_ID']}' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$REGISTER_TIME') ";
					exequery(TD::conn(),$query_tmp);
				}
			}
		}
}
if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
  	<td nowrap align="center"><?=_("���")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("�ǼǴ���")?></td>
    <td nowrap align="center"><?=_("�Ǽ�ʱ��")?></td>
    <td nowrap align="center"><?=_("�Ǽ�IP")?></td>
    <td nowrap align="center"><?=_("��ע")?></td>
    <td nowrap align="center"><?=_("״̬")?></td>
  </thead>
<?

for($I=0;$I< count($lines);$I++)
{
	if($lines[$I]=="") continue;
	
?>
  <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
  	<td><?=($I+1)?></td>
    <td><?=($lines[$I]["USER_ID"]==""?($lines[$I]["USER_NAME"]):substr((GetUserNameById($user_info_arr[$lines[$I]["USER_ID"]]['USER_ID'])),0,-1))?></td>
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
$MSG2 = sprintf(_("��%d�����ݵ���ɹ�!"), $SUCC_COUNT);
Message("",$MSG2);
Button_Back();
?>