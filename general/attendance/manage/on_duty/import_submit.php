<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
   Button_Back();
   exit;
}

if(MYOA_IS_UN == 1){
   $title=array("NAME"=>"ZHIBANREN","SCHEDULE_TYPE"=>"PAIBAN_TYPE","DUTY_TYPE"=>"ZHIBAN_TYPE","SCHEDULE_START_TIME"=>"ZBSJ_B","SCHEDULE_END_TIME"=>"ZBSJ_E","DUTY_REQUIREMENT"=>"ZBYQ","MEMO"=>"BEIZHU");
   $fieldAttr = array("SCHEDULE_START_TIME" => "datetime","SCHEDULE_END_TIME" => "datetime");
}else{
   $title=array(_("ֵ����")=>"ZHIBANREN",_("�Ű�����")=>"PAIBAN_TYPE",_("ֵ������")=>"ZHIBAN_TYPE",_("ֵ�࿪ʼʱ��")=>"ZBSJ_B",_("ֵ�����ʱ��")=>"ZBSJ_E",_("ֵ��Ҫ��")=>"ZBYQ",_("��ע")=>"BEIZHU");
   $fieldAttr = array(_("ֵ�࿪ʼʱ��") => "datetime",_("ֵ�����ʱ��") => "datetime");
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
//if(strpos($data,"\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1")!==FALSE)
//{
//   Message(_("����"),_("��Ҫ���޸��ļ���չ���ķ�ʽ�����EXCEL��ʽ���ļ�!��ʹ��Excel�ļ��˵��µ�\"���Ϊ\"��ѡ�񱣴��ʽ��"));
//   Button_Back();
//   exit;
//}
$CUR_TIME=date("Y-m-d H:i:s",time());
require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
$MSG_ERROR = array();
while($line = $objExcel->getNextRow())
{
   $lines[] = $line;
   $STR_VALUE="";
   $STR_KEY="";
   $MSG_ERROR[$ROW_COUNT]=_("�ɹ�");
   $success=1;

   $query = "SELECT USER_ID FROM USER WHERE USER_NAME ='".$line["ZHIBANREN"]."'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
      $USER_ID = $ROW["USER_ID"];
   else
   {
     $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ���˲����ڣ�δ����")."</font>";
     $success=0;
     $ROW_COUNT++;
     continue;
   }
		$query="select PAIBAN_ID from ZBAP_PAIBAN where ZHIBANREN='$USER_ID' and ZBSJ_B='".$line["ZBSJ_B"]."'";
		$cursor=exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
			 $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("�ظ����ţ�δ����")."</font>";
			 $ROW_COUNT++;
			 $success=0;
			 continue;
		}
		
    foreach ($line as $key => $value)
    {
      $value=ltrim($value);
   	  $STR_KEY.=$key.",";
   	  if($key!="ZHIBANREN" && $key!="PAIBAN_TYPE" && $key!="ZHIBAN_TYPE" && $key!="ZBSJ_B" && $key!="ZBSJ_E")
   	  {
   	     $STR_VALUE.= "'$value',";
   	  }
   	  else
   	  {
   	     if($key=="ZHIBANREN")
   	     {
   	        if($value=="")  //�û���Ϊ�ղ�����
   	        {
   	           $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ����Ϊ�գ�δ����")."</font>";
   	           $success=0;
   	           continue;
   	        }
   	        else
   	        {
                $query = "SELECT USER_ID,DEPT_ID FROM USER WHERE USER_NAME ='$value'";
                $cursor = exequery(TD::conn(),$query);
                if($ROW = mysql_fetch_array($cursor))
                {
                   $USER_ID = $ROW["USER_ID"]; 
                   $STR_VALUE.="'$USER_ID',";           
                   $DEPT_ID = $ROW["DEPT_ID"];                 
                }
                else
                {
   	              $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ���˲����ڣ�δ����")."</font>";
   	              $success=0;
   	              continue;
                }
   	        }
   	     }
         if ($key=="PAIBAN_TYPE")
   	     {
             $PAIBAN_TYPE=$value;
             $query = "SELECT CODE_NO FROM SYS_CODE WHERE PARENT_NO ='PAIBAN_TYPE' AND CODE_NAME ='$value'";
             $cursor = exequery(TD::conn(),$query);
             if($ROW = mysql_fetch_array($cursor))
             {
                $CODE_NO = $ROW['CODE_NO'];
                $STR_VALUE.="'$CODE_NO',";
             }
             else
             {
   	            $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("�Ű����Ͳ����ڣ�δ����")."</font>";
   	            $success=0;
   	            continue;
   	        }
         }
         if ($key=="ZHIBAN_TYPE")
   	     {
             $PAIBAN_TYPE=$value;
             $query = "SELECT CODE_NO FROM SYS_CODE WHERE PARENT_NO ='ZHIBAN_TYPE' AND CODE_NAME ='$value'";
             $cursor = exequery(TD::conn(),$query);
             if($ROW = mysql_fetch_array($cursor))
             {
                $CODE_NO = $ROW['CODE_NO'];
                $STR_VALUE.="'$CODE_NO',";
             }
             else
             {
   	            $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ�����Ͳ����ڣ�δ����")."</font>";
   	            $success=0;
   	            continue;
   	        } 
         }
         if ($key=="ZBSJ_B")
   	     {

            if($value=="")
            {
   	           $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ�࿪ʼʱ�䲻��Ϊ�գ�δ����")."</font>";
   	           $success=0;
   	           continue;
            }

            $format="Y-m-d H:i:s";//ʱ���ʽ����
            $unixTime=strtotime($value);
            $checkDate= date($format,$unixTime);
            if($checkDate!="1970-01-01 08:00:00")
            {
               $STR_VALUE.="'$checkDate',";
            }
            else
            {
   	           $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ�࿪ʼʱ���ʽ����ȷ��δ����")."</font>";
   	           $success=0;
   	           continue;
            }
         }
         if ($key=="ZBSJ_E")
   	     {

            if($value=="")
            {
   	           $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ�����ʱ�䲻��Ϊ�գ�δ����")."</font>";
   	           $success=0;
   	           continue;
            }

            $checkDate= date("Y-m-d H:i:s",strtotime($value));
            if($checkDate!="1970-01-01 08:00:00")
            {
               $STR_VALUE.="'$checkDate',";
            }
            else
            {
   	           $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ֵ�����ʱ���ʽ����ȷ��δ����")."</font>";
   	           $success=0;
   	           continue;
            }
         }
      }
   }//end foreach
    if (substr($STR_KEY,-1)==",")
       $STR_KEY=substr($STR_KEY,0,-1);
    if (substr($STR_VALUE,-1)==",")
       $STR_VALUE=substr($STR_VALUE,0,-1);
   $ROW_COUNT++;
   if($success==1)
   {
      $query="insert into ZBAP_PAIBAN (ZHIBANREN_DEPT,PAIBAN_APR,ANPAI_TIME,REMIND_TYPE,HAS_REMINDED,".$STR_KEY.") values ('$DEPT_ID','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','3','0',".$STR_VALUE.")";
      exequery(TD::conn(),$query);
      $PAIBAN_ID=mysql_insert_id();
	 if($SMS_REMIND=="on")
	 {
	    $REMIND_URL="1:attendance/personal/on_duty/note.php?PAIBAN_ID=".$PAIBAN_ID;
	    $SMS_CONTENT=_("����ֵ�ల��,��鿴");     
	    if($USER_ID!="")
	       send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,55,$SMS_CONTENT,$REMIND_URL,$PAIBAN_ID);
	 }   
	 if($SMS2_REMIND=="on")
	 {
	    $SMS_CONTENT=_("����ֵ�ల��,��鿴");
	    if($USER_ID!="")
	       send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,55);
	 } 
	 $SUCC_COUNT++;
   }
}
if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
    <td nowrap align="center"><?=_("ֵ����")?></td>
    <td nowrap align="center"><?=_("�Ű�����")?></td>
    <td nowrap align="center"><?=_("ֵ������")?></td>
    <td nowrap align="center"><?=_("ֵ�࿪ʼʱ��")?></td>
    <td nowrap align="center"><?=_("ֵ�����ʱ��")?></td>
    <td nowrap align="center"><?=_("ֵ��Ҫ��")?></td>
    <td nowrap align="center"><?=_("��ע")?></td>
    <td nowrap align="center"><?=_("״̬")?></td>
  </thead>
<?
for($I=0;$I< count($lines);$I++)
{
	if($I%2==1)
		$TR_STYLE="TableLine1";
	else
		$TR_STYLE="TableLine2";
?>
  <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
    <td><?=$lines[$I]["ZHIBANREN"]?></td>
    <td><?=$lines[$I]["PAIBAN_TYPE"]?></td>
    <td><?=$lines[$I]["ZHIBAN_TYPE"]?></td>
    <td><?=$lines[$I]["ZBSJ_B"]?></td>
    <td><?=$lines[$I]["ZBSJ_E"]?></td>
    <td><?=$lines[$I]["ZBYQ"]?></td>
    <td><?=$lines[$I]["BEIZHU"]?></td>
    <td align="left"><?=$MSG_ERROR[$I]?></td>
  </tr>
<?
}
?>
</table>
<?
$MSG = sprintf(_("��%d�����ݵ���ɹ�!"), $SUCC_COUNT);
Message("",$MSG);
Button_Back();
?>