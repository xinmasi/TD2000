<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("����"),_("ֻ�ܵ���Excel�ļ�!"));
   Button_Back();
   exit;
}

if(MYOA_IS_UN == 1){
 $title=array("PLAN_NAME"=>"PLAN_NAME","POSITION"=>"POSITION","NAME"=>"EMPLOYEE_NAME","SEX"=>"EMPLOYEE_SEX","BIRTH"=>"EMPLOYEE_BIRTH","AGE"=>"EMPLOYEE_AGE","NATIONALITY"=>"EMPLOYEE_NATIONALITY","PHONE"=>"EMPLOYEE_PHONE","E_mail"=>"EMPLOYEE_EMAIL","MAJOR"=>"EMPLOYEE_MAJOR","HIGHEST_SCHOOL"=>"EMPLOYEE_HIGHEST_SCHOOL","HIGHEST_DEGREE"=>"EMPLOYEE_HIGHEST_DEGREE","GRADUATION_DATE"=>"GRADUATION_DATE","GRADUATION_SCHOOL"=>"GRADUATION_SCHOOL","EXPECTED_SALARY"=>"EXPECTED_SALARY","RESIDENCE_PLACE"=>"RESIDENCE_PLACE","NATIVE_PLACE"=>"EMPLOYEE_NATIVE_PLACE","DOMICILE_PLACE"=>"EMPLOYEE_DOMICILE_PLACE","MARITAL_STATUS"=>"EMPLOYEE_MARITAL_STATUS","POLITICAL_STATUS"=>"EMPLOYEE_POLITICAL_STATUS","HEALTH"=>"EMPLOYEE_HEALTH","JOB_BEGINNING"=>"JOB_BEGINNING","JOB_CATEGORY"=>"JOB_CATEGORY","JOB_INDUSTRY"=>"JOB_INDUSTRY","JOB_INTENSION"=>"JOB_INTENSION","WORK_CITY"=>"WORK_CITY","START_WORKING"=>"START_WORKING","FOREIGN_LANGUAGE1"=>"FOREIGN_LANGUAGE1","FOREIGN_LANGUAGE2"=>"FOREIGN_LANGUAGE2","FOREIGN_LANGUAGE3"=>"FOREIGN_LANGUAGE3","FOREIGN_LEVEL1"=>"FOREIGN_LEVEL1","FOREIGN_LEVEL2"=>"FOREIGN_LEVEL2","FOREIGN_LEVEL3"=>"FOREIGN_LEVEL3","COMPUTER_LEVEL"=>"COMPUTER_LEVEL","RECRU_CHANNEL"=>"RECRU_CHANNEL","SKILLS"=>"EMPLOYEE_SKILLS","CAREER_SKILLS"=>"CAREER_SKILLS","WORK_EXPERIENCE"=>"WORK_EXPERIENCE","PROJECT_EXPERIENCE"=>"PROJECT_EXPERIENCE","MEMO"=>"REMARK");
 $fieldAttr = array("BIRTH" => "date","GRADUATION_DATE" => "date","JOB_BEGINNING" => "date");
}else{
 $title=array(_("�ƻ�����")=>"PLAN_NAME",_("��λ")=>"POSITION",_("ӦƸ������")=>"EMPLOYEE_NAME",_("�Ա�")=>"EMPLOYEE_SEX",_("��������")=>"EMPLOYEE_BIRTH",_("����")=>"EMPLOYEE_AGE",_("����")=>"EMPLOYEE_NATIONALITY",_("��ϵ�绰")=>"EMPLOYEE_PHONE","E_mail"=>"EMPLOYEE_EMAIL",_("��ѧרҵ")=>"EMPLOYEE_MAJOR",_("ѧ��")=>"EMPLOYEE_HIGHEST_SCHOOL",_("ѧλ")=>"EMPLOYEE_HIGHEST_DEGREE",_("��ҵʱ��")=>"GRADUATION_DATE",_("��ҵѧУ")=>"GRADUATION_SCHOOL",_("����нˮ(˰ǰ)")=>"EXPECTED_SALARY",_("�־�ס����")=>"RESIDENCE_PLACE",_("����")=>"EMPLOYEE_NATIVE_PLACE",_("�������ڵ�")=>"EMPLOYEE_DOMICILE_PLACE",_("����״��")=>"EMPLOYEE_MARITAL_STATUS",_("������ò")=>"EMPLOYEE_POLITICAL_STATUS",_("����״��")=>"EMPLOYEE_HEALTH",_("�μӹ���ʱ��")=>"JOB_BEGINNING",_("������������")=>"JOB_CATEGORY",_("����������ҵ")=>"JOB_INDUSTRY",_("��������ְҵ")=>"JOB_INTENSION",_("������������")=>"WORK_CITY",_("����ʱ��")=>"START_WORKING",_("��������1")=>"FOREIGN_LANGUAGE1",_("��������2")=>"FOREIGN_LANGUAGE2",_("��������3")=>"FOREIGN_LANGUAGE3",_("����ˮƽ1")=>"FOREIGN_LEVEL1",_("����ˮƽ2")=>"FOREIGN_LEVEL2",_("����ˮƽ3")=>"FOREIGN_LEVEL3",_("�����ˮƽ")=>"COMPUTER_LEVEL",_("��Ƹ����")=>"RECRU_CHANNEL",_("�س�")=>"EMPLOYEE_SKILLS",_("ְҵ����")=>"CAREER_SKILLS",_("��������")=>"WORK_EXPERIENCE",_("��Ŀ����")=>"PROJECT_EXPERIENCE",_("��ע")=>"REMARK");
 $fieldAttr = array(_("��������") => "date",_("��ҵʱ��") => "date",_("�μӹ���ʱ��") => "date");
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
//
//$lines=CSV2Array($data,$title);
require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, $title, $fieldAttr);
$MSG_ERROR = array();
while($DATA = $objExcel->getNextRow())
{
	$lines[$ROW_COUNT]=$DATA;
   //$line = $DATA;
   $DATA_NUM = count ($line);
   $STR_VALUE="";
   $STR_UPDATE="";
   $STR_KEY="";
   $STR_UPDATEKEY="";
   $MSG_ERROR[$ROW_COUNT]=_("�ɹ�");
   $success=1;

   foreach ($title as $key) 
   {

     $value=ltrim($DATA[$key]);	  
     if (($key!="PLAN_NAME")&&($key!="EMPLOYEE_NAME"))
     {
 		 $STR_KEY.=$key.",";
 		 $STR_UPDATEKEY.=$key.",";
     }
     else
     {
   		if ($key=="PLAN_NAME")
   		{
   		  $PLAN_NAME=$value;
   		  if($value=="")  //��Ƹ�ƻ���Ϊ�ղ�����
   		  {
   		   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("��Ƹ�ƻ���Ϊ�գ�δ����")."</font>";
   		   $success=0;
   		   continue;
   		  }
   		}
   		if ($key=="EMPLOYEE_NAME")
   		{
   		  $EMPLOYEE_NAME=$value;
   		  if($value=="")  //ӦƸ������Ϊ�ղ�����
   		  {
   		   $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("ӦƸ������Ϊ�գ�δ����")."</font>";
   		   $success=0;
   		   continue;
   		  }
   		}
     }//end else
     if ($key=="EMPLOYEE_SEX")
     {
  			if ($value==_("��"))
  		   $EMPLOYEE_SEX="0";
  			else
  		   $EMPLOYEE_SEX="1";
     }
     switch ($value)
 		 {
  			case _("δ��"):    {$value="0";break;}
  			case _("�ѻ�"):    {$value="1";break;}
  			case _("����"):    {$value="2";break;}
  			case _("ɥż"):    {$value="3";break;}
  			case _("��"):      {$value="0";break;}
  			case _("Ů"):      {$value="1";break;}
    	}	
 
 	   if (($key!="EMPLOYEE_NATIVE_PLACE")&&($key!="EMPLOYEE_POLITICAL_STATUS")&&($key!="JOB_CATEGORY")&&($key!="POSITION")&&($key!="EMPLOYEE_MAJOR")&&($key!="EMPLOYEE_HIGHEST_SCHOOL")&&($key!="EMPLOYEE_HIGHEST_DEGREE")&&($key!="PLAN_NAME")&&($key!="EMPLOYEE_NAME")&&($key!="RECRU_CHANNEL"))
     { 
           $STR_VALUE.="'$value',";
           $STR_UPDATE.="$key='$value',";
     }
 	   else
 	   {
 	     if (($key!="PLAN_NAME")&&($key!="EMPLOYEE_NAME"))
 		   {  
	       $query1="select CODE_NO from HR_CODE where PARENT_NO='$key' and CODE_NAME='$value'";
         if ($key=="EMPLOYEE_NATIVE_PLACE")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='AREA' and CODE_NAME='$value'";
	    if ($key=="EMPLOYEE_POLITICAL_STATUS")
	       	  $query1="select CODE_NO from HR_CODE where PARENT_NO='STAFF_POLITICAL_STATUS' and CODE_NAME='$value'";
         if ($key=="JOB_CATEGORY")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='JOB_CATEGORY' and CODE_NAME='$value'";
	    if ($key=="POSITION")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='POOL_POSITION' and CODE_NAME='$value'";
         if ($key=="EMPLOYEE_MAJOR")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='POOL_EMPLOYEE_MAJOR' and CODE_NAME='$value'";
	    if ($key=="EMPLOYEE_HIGHEST_SCHOOL")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='STAFF_HIGHEST_SCHOOL' and CODE_NAME='$value'";
         if ($key=="EMPLOYEE_HIGHEST_DEGREE")
		       $query1="select CODE_NO from HR_CODE where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NAME='$value'";
	   if($key=="RECRU_CHANNEL")
	   		 $query1="select CODE_NO from HR_CODE where PARENT_NO='PLAN_DITCH' and CODE_NAME='$value'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW=mysql_fetch_array($cursor1))
           {
              $STR_VALUE.="'$ROW[0]',";
              $STR_UPDATE.="$key='$ROW[0]',";
           }
 		     else
 			   {
 			      $STR_VALUE.="'$value'".",";
 			      $STR_UPDATE.="$key='$value',";
 			   }
 		   }
 	   }
   }//end foreach
  
   if (substr($STR_KEY,-1)==",")
   {
      $STR_KEY=substr($STR_KEY,0,-1);
      $STR_UPDATEKEY=substr($STR_UPDATEKEY,0,-1);
   }
   if (substr($STR_VALUE,-1)==",")
   {
      $STR_VALUE=substr($STR_VALUE,0,-1);
      $STR_UPDATE=substr($STR_UPDATE,0,-1);
   } 
   $query1 = "SELECT a.EXPERT_ID,b.PLAN_NO from HR_RECRUIT_POOL a left outer join HR_RECRUIT_PLAN b on a.PLAN_NO=b.PLAN_NO where b.PLAN_NAME='$PLAN_NAME' and a.EMPLOYEE_NAME='$EMPLOYEE_NAME'";
   $cursor1= exequery(TD::conn(),$query1);
   //����oa�û�
   if($ROW=mysql_fetch_array($cursor1))
   {   
   	 $EXPERT_ID=$ROW["EXPERT_ID"];
   	 $PLAN_NO=$ROW["PLAN_NO"];
     $MSG_ERROR[$ROW_COUNT]="<font color=green>"._("�����Ѹ���")."</font>";
  
     $query1="update HR_RECRUIT_POOL SET ".$STR_UPDATE.",ADD_TIME='$CUR_TIME',PLAN_NO='$PLAN_NO' where PLAN_NAME='".$PLAN_NAME."' and  EMPLOYEE_NAME='".$EMPLOYEE_NAME."'";
     $cursor1=exequery(TD::conn(),$query1); 	
   }
   else   //����oa�û�
   {
     $query1="select PLAN_ID,PLAN_NO from HR_RECRUIT_PLAN where PLAN_NAME='$PLAN_NAME'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $PLAN_ID=$ROW["PLAN_ID"];
        $PLAN_NO=$ROW["PLAN_NO"];
     }
     else
     {
        $MSG_ERROR[$ROW_COUNT]="<font color=red>"._("��Ƹ�ƻ����Ʋ����ڣ�δ����")."</font>";
        $ROW_COUNT++;
        $success=0;
        continue;
     }
     if($PLAN_NAME!="" && $EMPLOYEE_NAME!="")
     {
       $query="insert into HR_RECRUIT_POOL (CREATE_USER_ID,CREATE_DEPT_ID,PLAN_NO,PLAN_NAME,EMPLOYEE_NAME,ADD_TIME,".$STR_KEY.") values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$PLAN_NO','$PLAN_NAME','$EMPLOYEE_NAME','$CUR_TIME',".$STR_VALUE.")";
       exequery(TD::conn(),$query);
    }
   }
   
   $ROW_COUNT++;
   if($success) $SUCC_COUNT++;
}

if(file_exists($EXCEL_FILE))
   @unlink($EXCEL_FILE);
?>
<br>
<table class="TableList" width="100%" align="center">
  <thead class="TableHeader">
    <td nowrap align="center"><?=_("�ƻ�����")?></td>
    <td nowrap align="center"><?=_("ӦƸ������")?></td>
    <td nowrap align="center"><?=_("�Ա�")?></td>
    <td nowrap align="center"><?=_("ӦƸ��λ")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("ѧ��")?></td>
    <td nowrap align="center"><?=_("רҵ")?></td>
    <td nowrap align="center"><?=_("��ϵ�绰")?></td>
    <td nowrap align="center"><?=_("״̬")?></td>
  </thead>
<?
for($I=0;$I< count($lines);$I++)
{
?>
  <tr align="center" style="<?=$TR_STYLE?>" class="TableData">
    <td><?=$lines[$I]["PLAN_NAME"]?></td>
    <td><?=$lines[$I]["EMPLOYEE_NAME"]?></td>
    <td><?=$lines[$I]["EMPLOYEE_SEX"]?></td>
    <td><?=$lines[$I]["POSITION"]?></td>     
    <td><?=$lines[$I]["EMPLOYEE_BIRTH"]?></td>
    <td><?=$lines[$I]["EMPLOYEE_HIGHEST_SCHOOL"]?></td>
    <td><?=$lines[$I]["EMPLOYEE_MAJOR"]?></td>
    <td><?=$lines[$I]["EMPLOYEE_PHONE"]?></td>
    <td align="left"><?=$MSG_ERROR[$I]?></td>
  </tr>
<?
}
?>
</table>
<?
Message("",sprintf(_("��%s�����ݵ���ɹ�!"), $SUCC_COUNT));
Button_Back();
?>