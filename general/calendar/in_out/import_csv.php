<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����csv����");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
   Message(_("��ʾ"),_("ֻ�ܵ���xls�ļ�!"));
?>
<center>
   <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</center>
<?
   exit;
}

if($TO_ID3_IN!=""||$TO_ID_IN!=""||$PRIV_ID_IN!="")
{
   $query1 = "SELECT USER_ID from USER where find_in_set(DEPT_ID,'$TO_ID_IN') or find_in_set(USER_PRIV,'$PRIV_ID_IN')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      if(!find_id($TO_ID3_IN,$ROW["USER_ID"]))
   	    $USER_ID.=$ROW["USER_ID"].",";

   $TO_ID3_IN .= $USER_ID;
}
else
   $TO_ID3_IN = $_SESSION["LOGIN_USER_ID"];

if(MYOA_IS_UN==1)
{
if($CAL_AFF_TASK==1)
{   $title=array("BEGIN_DATE**"=>"CAL_TIME1",
                "BEGIN_TIME"=>"CAL_TIME2",
                "END_DATE"=>"END_TIME1",
                "END_TIME"=>"END_TIME2",
                "PRIORITY"=>"CAL_LEVEL",
                "PRIVATE"=>"CAL_TYPE",
                "REMARK"=>"CONTENT"
               );
	$fieldAttr = array("BEGIN_DATE**" => "date","BEGIN_TIME"=>"time","END_DATE"=>"date","END_TIME"=>"time");	
}
else if($CAL_AFF_TASK==2)
{   $title=array("BEGIN_DATE**"=>"BEGIN_TIME1",
                "BEGIN_TIME"=>"BEGIN_TIME2",
                "END_DATE"=>"END_TIME1",
                "END_TIME"=>"END_TIME2",
                "NOTIFY_DATE"=>"REMIND_DATE",
                "NOTIFY_TIME"=>"REMIND_TIME",
                "NOTIFY(Y/N)"=>"SMS2_REMIND",
                "REMARK"=>"CONTENT"
               );
$fieldAttr = array("BEGIN_DATE**" => "date","BEGIN_TIME"=>"time","END_DATE"=>"date","END_TIME"=>"time","NOTIFY_DATE"=>"date","NOTIFY_TIME"=>"time");	
}
else
{   $title=array("BEGIN_DATE"=>"BEGIN_DATE",
             "CLOSING_DATE"=>"END_DATE",
             "FINISH_DATE"=>"FINISH_TIME",
             "ALL_WORK"=>"TOTAL_TIME",
             "REAL_WORK"=>"USE_TIME",
             "MEMO"=>"CONTENT",
             "FINISH_PER"=>"RATE",
             "CLASS"=>"COLOR",
             "STATUS"=>"TASK_STATUS",
             "PRIORITY"=>"IMPORTANT",
             "PRIVATE"=>"TASK_TYPE",
             "SUBJECT"=>"SUBJECT"
         );
	$fieldAttr = array("BEGIN_DATE" => "date","CLOSING_DATE"=>"date","FINISH_DATE"=>"date");
}
}
else
{
	if($CAL_AFF_TASK==1)
{
   $title=array(_("��ʼ����**")=>"CAL_TIME1",
                _("��ʼʱ��")=>"CAL_TIME2",
                _("��������")=>"END_TIME1",
                _("����ʱ��")=>"END_TIME2",
                _("���ȼ�")=>"CAL_LEVEL",
                _("˽��")=>"CAL_TYPE",
                _("˵��")=>"CONTENT"
               );
	$fieldAttr = array(_("��ʼ����**") => "date",_("��ʼʱ��")=>"time",_("��������")=>"date",_("����ʱ��")=>"time");			   
}
else if($CAL_AFF_TASK==2)
{   $title=array(_("��ʼ����**")=>"BEGIN_TIME1",
                _("��ʼʱ��")=>"BEGIN_TIME2",
                _("��������")=>"END_TIME1",
                _("����ʱ��")=>"END_TIME2",
                _("��������")=>"REMIND_DATE",
                _("����ʱ��")=>"REMIND_TIME",
                _("���ѿ�/��")=>"SMS2_REMIND",
                _("˵��")=>"CONTENT"
               );
	 $fieldAttr = array(_("��ʼ����**") => "date",_("��ʼʱ��")=>"time",_("��������")=>"date",_("����ʱ��")=>"time",_("��������")=>"date",_("����ʱ��")=>"time");
}
else
{   $title=array(_("��ʼ����")=>"BEGIN_DATE",
             _("��ֹ����")=>"END_DATE",
             _("�������")=>"FINISH_TIME",
             _("ȫ������")=>"TOTAL_TIME",
             _("ʵ�ʹ���")=>"USE_TIME",
             _("��ע")=>"CONTENT",
             _("��ɰٷֱ�")=>"RATE",
             _("���")=>"COLOR",
             _("״̬")=>"TASK_STATUS",
             _("���ȼ�")=>"IMPORTANT",
             _("˽��")=>"TASK_TYPE",
             _("����")=>"SUBJECT"
         );
	$fieldAttr = array(_("��ʼ����") => "date",_("��ֹ����")=>"date",_("�������")=>"date");
}
}

$XML_FILE = $_FILES['XML_FILE']['tmp_name'];

$data=file_get_contents($XML_FILE);

//$lines=CSV2Array($data, $title);
require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($XML_FILE, $title, $fieldAttr);
$GET_USER=strtok($TO_ID3_IN,",");
while($GET_USER!="")
{
    while($lines_array = $objExcel->getNextRow()) 
   //foreach($lines as $lines_array)
   {
   	 if($CAL_AFF_TASK==1)
   	 {
        if($_SESSION["LOGIN_USER_ID"]!=$GET_USER)
        {
           $MANAGER_ID=$_SESSION["LOGIN_USER_ID"];
           $lines_array['CAL_TYPE']="1";
        }
        else
        {
           $MANAGER_ID="";
     	 	   if($lines_array['CAL_TYPE']=="TRUE")
     	 	      $lines_array['CAL_TYPE']="2";
     	 	   else
     	 	      $lines_array['CAL_TYPE']="1";           
        }
   	 	  if($lines_array['CAL_LEVEL']==_("��"))
   	 	     $lines_array['CAL_LEVEL']="1";
   	 	  else if($lines_array['CAL_LEVEL']==_("��")) 
   	 	     $lines_array['CAL_LEVEL']="2";
   	 	  else
   	 	     $lines_array['CAL_LEVEL']=""; 
   	 	     
   	 	     
   	    $query="INSERT INTO CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS) values ('$GET_USER','"
   	    .strtotime($lines_array['CAL_TIME1']." ".$lines_array['CAL_TIME2'])."','".strtotime($lines_array['END_TIME1']." ".$lines_array['END_TIME2'])."',
   	    '".$lines_array['CAL_TYPE']."','".$lines_array['CAL_LEVEL']."','".addslashes($lines_array['CONTENT'])."','0')"; 
   	 }
   	 else if($CAL_AFF_TASK==2)
   	 {
         if($lines_array['SMS2_REMIND']=="FALSE")
            $lines_array['SMS2_REMIND']=0;
         else
            $lines_array['SMS2_REMIND']=1;
         
        if($_SESSION["LOGIN_USER_ID"]!=$GET_USER)
           $MANAGER_ID=$_SESSION["LOGIN_USER_ID"];
        else
           $MANAGER_ID="";
           
        //$CONTENT=addslashes($lines_array['CONTENT']);   
         $query="INSERT into AFFAIR(MANAGER_ID,USER_ID,BEGIN_TIME,END_TIME,TYPE,REMIND_DATE,REMIND_TIME,CONTENT,SMS2_REMIND,BEGIN_TIME_TIME,END_TIME_TIME)
         values('$MANAGER_ID','$GET_USER','".strtotime($lines_array['BEGIN_TIME1'])."','".strtotime($lines_array['END_TIME1'])."','2',
         '','".$lines_array['REMIND_TIME']."','".addslashes($lines_array['CONTENT'])."','".$lines_array['SMS2_REMIND']."','".$lines_array['BEGIN_TIME2']."','".$lines_array['END_TIME2']."');"; //echo "<br>";
     }
   	 else
   	 {            
         if($lines_array['TASK_STATUS']==_("δ��ʼ"))
            $lines_array['TASK_STATUS']=1;
         else if($lines_array['TASK_STATUS']==_("������"))
            $lines_array['TASK_STATUS']=2;               
         else if($lines_array['TASK_STATUS']==_("�����"))
            $lines_array['TASK_STATUS']=3;  
         else if($lines_array['TASK_STATUS']==_("���ڵȴ�������"))
            $lines_array['TASK_STATUS']=4;  
         else if($lines_array['TASK_STATUS']==_("���Ƴ�"))
            $lines_array['TASK_STATUS']=5;
         else
            $lines_array['TASK_STATUS']=1;  
           //������ɫ����
         $PARA_ARRAY=get_sys_para("CALENDAR_TASK_COLOR");
         $PARA_VALUE=$PARA_ARRAY["CALENDAR_TASK_COLOR"];
         $PARA_VALUE=explode(",",$PARA_VALUE);
         $CALENDAR_TASK_COLOR_0=$PARA_VALUE[0]; //��ɫ
         $CALENDAR_TASK_COLOR_1=$PARA_VALUE[1]; //��ɫ
         $CALENDAR_TASK_COLOR_2=$PARA_VALUE[2]; //��ɫ
         $CALENDAR_TASK_COLOR_3=$PARA_VALUE[3]; //��ɫ
         $CALENDAR_TASK_COLOR_4=$PARA_VALUE[4]; //��ɫ
         $CALENDAR_TASK_COLOR_5=$PARA_VALUE[5]; //��ɫ
         if($CALENDAR_TASK_COLOR_0=="")
            $CALENDAR_TASK_COLOR_0=_("��ɫ���");
         if($CALENDAR_TASK_COLOR_1=="")
            $CALENDAR_TASK_COLOR_1=_("��ɫ���");
         if($CALENDAR_TASK_COLOR_2=="")
            $CALENDAR_TASK_COLOR_2=_("��ɫ���");
         if($CALENDAR_TASK_COLOR_3=="")
           $CALENDAR_TASK_COLOR_3=_("��ɫ���");
         if($CALENDAR_TASK_COLOR_4=="")
           $CALENDAR_TASK_COLOR_4=_("��ɫ���");
         if($CALENDAR_TASK_COLOR_5=="")
           $CALENDAR_TASK_COLOR_5=_("��ɫ���");  
                  	 	
         if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_0)
            $lines_array['COLOR']=1;
         else if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_1)
            $lines_array['COLOR']=2;               
         else if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_2)
            $lines_array['COLOR']=3;  
         else if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_3)
            $lines_array['COLOR']=4;  
         else if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_4)
            $lines_array['COLOR']=5;                 
         else if($lines_array['COLOR']==$CALENDAR_TASK_COLOR_5)
            $lines_array['COLOR']=6; 
         else
            $lines_array['COLOR']="";                     	 	
                  	 	
   	 	  if($lines_array['IMPORTANT']==_("��"))
   	 	     $lines_array['IMPORTANT']="1";
   	 	  else if($lines_array['IMPORTANT']==_("��")) 
   	 	     $lines_array['IMPORTANT']="2";
   	 	  else
   	 	     $lines_array['IMPORTANT']=""; 
        if($_SESSION["LOGIN_USER_ID"]!=$GET_USER)
        {
           $lines_array['TASK_TYPE']=1;
           $MANAGER_ID=$_SESSION["LOGIN_USER_ID"];
        }else{
           $MANAGER_ID="";
           if($lines_array['TASK_TYPE']=="FALSE")
              $lines_array['TASK_TYPE']=1;
           else
              $lines_array['TASK_TYPE']=2;             
        }      	 	                          	 	
   	 	  $lines_array['RATE'] = $lines_array['RATE']*100;                       	 	
        $query="INSERT INTO `TASK`(`MANAGER_ID` , `USER_ID` , `TASK_NO` , `TASK_TYPE` , `TASK_STATUS` , `COLOR` , `IMPORTANT` , `SUBJECT` , `EDIT_TIME` , `BEGIN_DATE` , `END_DATE` , `CONTENT` , `RATE` , `FINISH_TIME` , `TOTAL_TIME` , `USE_TIME` , `CAL_ID` )
         VALUES ('$MANAGER_ID', '$GET_USER', '1', '".$lines_array['TASK_TYPE']."', '".$lines_array['TASK_STATUS']."', '".$lines_array['COLOR']."', '".$lines_array['IMPORTANT']."', '".$lines_array['SUBJECT']."', 
         '".$lines_array['EDIT_TIME']."', '".$lines_array['BEGIN_DATE']."','".$lines_array['END_DATE']."','".$lines_array['CONTENT']."', '".$lines_array['RATE']."', '".$lines_array['FINISH_TIME']."', '".$lines_array['TOTAL_TIME']."', '".$lines_array['USE_TIME']."', '".$lines_array['CAL_ID']."');";// echo "<br>";
   	 }
   	 exequery(TD::conn(),$query);
   }
   $GET_USER=strtok(",");
}
if(file_exists($XML_FILE))
   @unlink($XML_FILE);
Message(_("��ʾ"),_("����ɹ�"));
?>
<center>
	<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</center>
</body>
</html>