<?php
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

//print_r($_POST);
$CUR_DATE=date("Y-m-d");

//����ְ����ģ��
if($TO_ID!="ALL_DEPT")  //δѡ��ȫ�岿��
{
   if ($TO_ID!="")  //ѡ�����ɲ���
   {
      $DEPT_ID=$TO_ID;
      if (substr($DEPT_ID,-1)==",")
         $DEPT_ID=substr($DEPT_ID,0,-1);
   }
   else  //δѡ����
   {
      $DEPT_ID="";
      if($query=="")
         $query.=" where b.DEPT_ID !='0'";
      else
         $query.=" and b.DEPT_ID !='0'";
   }
   if($DEPT_ID!="")
   {
      $DEPT_ID="(".$DEPT_ID.")";
      if($query=="")
         $query.=" where b.DEPT_ID in $DEPT_ID";
      else
         $query.=" and b.DEPT_ID in $DEPT_ID";
   }
}
else  //ѡ��ȫ�岿��
{
   if($query=="")
      $query.=" where b.DEPT_ID !='0'";
   else
      $query.=" and b.DEPT_ID !='0'";
}
//��ְ����ģ��
if($TO_ID!="ALL_DEPT")  //δѡ��ȫ�岿��
{
   if ($TO_ID!="")  //ѡ�����ɲ���
   {
       $DEPT_ID=$TO_ID;
       if (substr($DEPT_ID,-1)==",")
         $DEPT_ID=substr($DEPT_ID,0,-1);
   }
   else  //δѡ����
   {
      $DEPT_ID="";
      if($condition_str=="")
          $condition_str.=" where b.DEPT_ID ='0'";
      else
          $condition_str.=" and b.DEPT_ID ='0'";
   }
   if($DEPT_ID!="")
   {
      $DEPT_ID="(".$DEPT_ID.")";
      if($condition_str=="")
         $condition_str.=" where HR_STAFF_INFO.DEPT_ID in $DEPT_ID and b.DEPT_ID ='0'";
      else
         $condition_str.=" and HR_STAFF_INFO.DEPT_ID in $DEPT_ID and b.DEPT_ID ='0'";
   }
}
else  //ѡ��ȫ�岿��
{
   if($condition_str=="")
      $condition_str.=" where b.DEPT_ID ='0'";
   else
      $condition_str.=" and b.DEPT_ID ='0'";
}


if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
  {
  	$agearray = explode("-",$DATES_EMPLOYED);
  	$cur = explode("-",$CUR_DATE);
  	$year=$agearray[0];
  	$JOB_AGE=date("Y")-$year;
  	if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
  	{
  		$JOB_AGE++;
  	}
  }
else
  {
  	$JOB_AGE="";
  }
if($CHOSE=="")
{

$HTML_PAGE_TITLE = _("����״ͼ����");
include_once("inc/header.inc.php");
?>
<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<center>
<?
if($MODULE=="HR_INFO")
{   //ѧ��
   $query1="select STAFF_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_HIGHEST_SCHOOL");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("��ѧ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18' canvasBorderColor='#1687cb'>";
}
if($MODULE=="HR_HT")
{
	//��ͬ����
   $query1="select CONTRACT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT1");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����ͬ����ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_JC")
{
   	//������Ŀ
   $query1="select INCENTIVE_ITEM  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_INCENTIVE1");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("��������Ŀͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_ZZ")
{
	 	//֤������
   $query1="select LICENSE_TYPE   as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE1");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("��֤������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_XX")
{
	 //����ѧλ
   $query1="select DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("δ����ѧϰ����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("������ѧλͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_JL")
{
	 //���ڲ���
   $query1="select WORK_BRANCH  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("�����ڲ���ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_JN")
{
   	//��������
   $query1="select ABILITY_NAME as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_GX")
{
   	//�뱾�˹�ϵ
   $query1="select RELATIONSHIP  as SUMNAME,COUNT(DISTINCT b.USER_ID  ) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_RELATIVES");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("���뱾�˹�ϵͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_DD")
{
	 //��������
   $query1="select TRANSFER_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_TRANSFER.TRANSFER_ID) as TOTALCOUNT from  HR_STAFF_TRANSFER,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TRANSFER.TRANSFER_PERSON =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TRANSFER");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_LZ")
{
   $query1="select QUIT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_LEAVE.LEAVE_ID ) as TOTALCOUNT from  HR_STAFF_LEAVE,HR_STAFF_INFO,USER as b  ".$condition_str."and HR_STAFF_LEAVE.LEAVE_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LEAVE");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_FZ")
{
	 //��ְ����
   $query1="select REAPPOINTMENT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_REINSTATEMENT.REINSTATEMENT_ID) as TOTALCOUNT from  HR_STAFF_REINSTATEMENT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_REINSTATEMENT.REINSTATEMENT_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_REINSTATEMENT");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_ZC")
{
	 //��ȡ��ʽ
   $query1="select GET_METHOD  as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TITLE_EVALUATION");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("����ȡ��ʽͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}
if($MODULE=="HR_GH")
{
	 //�ػ�����
   $query1="select CARE_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CARE.CARE_ID ) as TOTALCOUNT from  HR_STAFF_CARE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CARE.BY_CARE_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
   $cursor= exequery(TD::conn(),$query1);
   $i=0;
   while($ROW=mysql_fetch_array($cursor))
   {
      $HRMS_COUNT++;
      $arrData[$i][1]=$ROW["SUMNAME"];
      $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CARE");
      if($arrData[$i][1]=="")
      		$arrData[$i][1]=_("����");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
   }
   $strXML = "<chart caption='"._("���ػ�����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
}

if(empty($arrData))
   Message("",_("�޼�¼"));
else
{
   foreach ($arrData as $arSubData)
   $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "'/>";
   $strXML .= "</chart>";
   echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Pie3D.swf", "", $strXML, "FactorySum", "80%", "500", false, false);
}
?>
</body>
</html>
<?
}
if($CHOSE==1){

$HTML_PAGE_TITLE = _("����״ͼ����");
include_once("inc/header.inc.php");
?>
<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<center>
<?
if($MODULE=="HR_INFO")
{   //ѧ��
   if($SUMFIELD==1)
   {
      $query1="select STAFF_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_HIGHEST_SCHOOL");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
      }
      $strXML = "<chart caption='"._("��ѧ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //����//������ͳ�ƣ���֯sql���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE);
      $ARRAY_COUNT=sizeof($ARRAY_STATIS);
      for($i=0;$i<$ARRAY_COUNT;$i++)
      {
         $AGERANG=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($AGERANG[1]!="")
         {
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= STAFF_BIRTH";
            else
               $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<=STAFF_BIRTH";
         }
         if($AGERANG[0]!="")
         {
            $AGERANG[0]--;
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
            else
               $WHEN_STR.="  and STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
         }

          $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
          $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
              COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
              from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
              //echo $query1;
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	 $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //�Ա�
   if($SUMFIELD==3)
   {
      $query1="select  STAFF_SEX as SUMNAME,
             COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
             from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query." GROUP BY SUMNAME";

      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="0")
            $arrData[$i][1]=_("��");
         if($arrData[$i][1]=="1")
            $arrData[$i][1]=_("Ů");
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("δ��д");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	$strXML = "<chart caption='"._("���Ա�ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //������ò
   if($SUMFIELD==4)
   {
      $query1="select STAFF_POLITICAL_STATUS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
      $strXML = "<chart caption='"._("��������òͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //��ְ״̬
   if($SUMFIELD==5)
   {
      $query1="select WORK_STATUS  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"WORK_STATUS");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
      $strXML = "<chart caption='"._("����ְ״̬ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //����
   if($SUMFIELD==6)
   {
      $query1="select STAFF_NATIVE_PLACE  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"AREA");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
   	  $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //ְ��
   if($SUMFIELD==7)
   {
      $query1="select PRESENT_POSITION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"PRESENT_POSITION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
   		$strXML = "<chart caption='"._("��ְ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //Ա������
   if($SUMFIELD==8)
   {
      $query1="select STAFF_OCCUPATION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_OCCUPATION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
   		$strXML = "<chart caption='"._("��Ա������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //���뱾��λʱ��
   if($SUMFIELD==9)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
      {
         $YEAR[$i]=$j;
         $Y_M_D[$i]=$YEAR[$i]."-01-01";
         $j++;
      }
   	  for($i=0;$i<51;$i++)
   	  {
   	     $WHEN_STR="";
   	     if($Y_M_D[$i]!=="")
   	        $WHEN_STR.=" WHEN DATES_EMPLOYED between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
   	     $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
         $STRCASE=$STRCASE.$WHEN_STR;
   	  }

   	  $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
    	}
      $strXML = "<chart caption='"._("�����뵥λʱ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //����//������λ����ͳ�ƣ���֯sql���
   if($SUMFIELD==10)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE1);
      $ARRAY_COUNT=sizeof($ARRAY_STATIS);

      for($i=0;$i<$ARRAY_COUNT;$i++)
      {
         $AGERANG=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($AGERANG[1]!="")
         {
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
            else
               $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
         }
         if($AGERANG[0]!="")
         {
            $AGERANG[0]--;
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
            else
               $WHEN_STR.="  and DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
         }

          $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
          $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
              COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
              from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
    	//echo $query1;
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         else
            $arrData[$i][1]=$ROW["SUMNAME"]._("��");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	 $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //ѧλ
   if($SUMFIELD==11)
   {
    	$query1="select STAFF_HIGHEST_DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("δ����ѧϰ����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
      }
      $strXML = "<chart caption='"._("������ѧλͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}

if($MODULE=="HR_HT")
{
	//��ͬ����
   if($SUMFIELD==1)
   {
      $query1="select CONTRACT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ͬ����ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ͬ״̬
   if($SUMFIELD==2)
   {
      $query1="select STATUS as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
       $strXML = "<chart caption='"._("����ͬ״̬ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ͬ����
   if($SUMFIELD==3)
   {
      $query1="select CONTRACT_SPECIALIZATION  as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]==1)
            $arrData[$i][1]=_("�й̶�����");
         if($arrData[$i][1]==2)
            $arrData[$i][1]=_("�޹̶�����");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ͬ����ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
    //���뱾��λʱ��
   if($SUMFIELD==4)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
      {
         $YEAR[$i]=$j;
         $Y_M_D[$i]=$YEAR[$i]."-01-01";
         $j++;
      }
      for($i=0;$i<51;$i++)
      {
         $WHEN_STR="";
         if($Y_M_D[$i]!=="")
            $WHEN_STR.=" WHEN MAKE_CONTRACT  between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
         $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
         $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];
          if($arrData[$i][1]=="")
       		   $arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
      }
      $strXML = "<chart caption='"._("����ͬǩ������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JC")
{
   	//������Ŀ
   if($SUMFIELD==1)
   {
      $query1="select INCENTIVE_ITEM  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_INCENTIVE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
       $strXML = "<chart caption='"._("��������Ŀͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��������
   if($SUMFIELD==2)
   {
      $query1="select INCENTIVE_TYPE  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp=$arrData[$i][1];
         $query2="select CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE' and CODE_NO='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         if($ROW2=mysql_fetch_array($cursor2))
             $arrData[$i][1]=$ROW2["CODE_NAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;

    	}
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_ZZ")
{
	 	//֤������
   if($SUMFIELD==1)
   {
      $query1="select LICENSE_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("��֤������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //֤��״̬
   if($SUMFIELD==2)
   {
      $query1="select STATUS    as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("��֤��״̬ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_XX")
{
	 //����ѧλ
   if($SUMFIELD==1)
   {
    	$query1="select DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
      	 $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("������ѧλͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JL")
{
	 //���ڲ���
   if($SUMFIELD==1)
   {
    	$query1="select WORK_BRANCH as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("�����ڲ���ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //����ְ��
   if($SUMFIELD==2)
   {
    	$query1="select POST_OF_JOB  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("������ְ��ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JN")
{
   	//��������
   if($SUMFIELD==1)
   {
    	$query1="select ABILITY_NAME as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //���ܼ���
   if($SUMFIELD==2)
   {
    	$query1="select SKILLS_LEVEL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("�����ܼ���ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_GX")
{
   	//�뱾�˹�ϵ
   if($SUMFIELD==1)
   {
    	$query1="select RELATIONSHIP  as SUMNAME,COUNT(DISTINCT b.USER_ID  ) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_RELATIVES");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("���뱾�˹�ϵͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //������ò
   if($SUMFIELD==2)
   {
    	$query1="select POLITICS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("��������òͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_DD")
{
	 //��������
   if($SUMFIELD==1)
   {
    	$query1="select TRANSFER_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_TRANSFER.TRANSFER_ID) as TOTALCOUNT from  HR_STAFF_TRANSFER,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TRANSFER.TRANSFER_PERSON =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TRANSFER");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_LZ")
{
   		//��ְ����
   if($SUMFIELD==1)
   {
    	$query1="select QUIT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_LEAVE.LEAVE_ID) as TOTALCOUNT from  HR_STAFF_LEAVE,HR_STAFF_INFO,USER as b  ".$condition_str."and HR_STAFF_LEAVE.LEAVE_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LEAVE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ְ���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$LEAVE_RANGE);
    	$ARRAY_COUNT=sizeof($ARRAY_STATIS);
    	for($i=0;$i<$ARRAY_COUNT;$i++)
      {//echo $ARRAY_STATIS[$i];echo "<br>";
         $LEAVERANGE=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($LEAVERANGE[1]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
        	   else
               $WHEN_STR.="  and '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
             $LEAVERANGE[1]++;
         }
         if($LEAVERANGE[0]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
        	   else
               $WHEN_STR.="  and QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
         }

         $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
         $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select (CASE ".$STRCASE."  END) as SUMNAME,
              COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
              from HR_STAFF_LEAVE a left outer join USER b on a.LEAVE_PERSON=b.USER_ID".$condition_str." GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	$strXML = "<chart caption='"._("����ְͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_FZ")
{
	 //��ְ����
   if($SUMFIELD==1)
   {
    	$query1="select REAPPOINTMENT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_REINSTATEMENT.REINSTATEMENT_ID) as TOTALCOUNT from  HR_STAFF_REINSTATEMENT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_REINSTATEMENT.REINSTATEMENT_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_REINSTATEMENT");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_ZC")
{
	 //��ȡ��ʽ
   if($SUMFIELD==1)
   {
    	$query1="select GET_METHOD  as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TITLE_EVALUATION");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ȡ��ʽͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ȡְ��
   if($SUMFIELD==2)
   {
    	$query1="select POST_NAME as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����ȡְ��ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_GH")
{
	 //�ػ�����
   if($SUMFIELD==1)
   {
    	$query1="select CARE_TYPE as SUMNAME,COUNT(HR_STAFF_CARE.CARE_ID) as TOTALCOUNT from  HR_STAFF_CARE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CARE.BY_CARE_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CARE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("���ػ�����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if(empty($arrData))
   Message("",_("�޼�¼"));
else
{
   foreach ($arrData as $arSubData)
      $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "'/>";
   $strXML .= "</chart>";
   echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Pie3D.swf", "", $strXML, "FactorySum", "80%", "500", false, false);
}
?>
</body>
</html>
<?
}
if($CHOSE==2){

$HTML_PAGE_TITLE = _("����״ͼ����");
include_once("inc/header.inc.php");
?>

<script language="jAVASCRIPT" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<?
if($MODULE=="HR_INFO")
{
   //ѧ��
   if($SUMFIELD==1)
   {
    	$query1="select STAFF_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_HIGHEST_SCHOOL");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
    	$strXML = "<chart caption='"._("��ѧ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //����//������ͳ�ƣ���֯sql���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE);
    	$ARRAY_COUNT=sizeof($ARRAY_STATIS);
    	for($i=0;$i<$ARRAY_COUNT;$i++)
      {
         $AGERANG=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($AGERANG[1]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= STAFF_BIRTH";
        	   else
               $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<=STAFF_BIRTH";

         }
         if($AGERANG[0]!="")
         {
             $AGERANG[0]--;
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
        	   else
               $WHEN_STR.="  and STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
         }

         $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
         $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
               COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
               from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	$strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //�Ա�
   if($SUMFIELD==3)
   {
      $query1="select  STAFF_SEX as SUMNAME,
             COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
             from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query." GROUP BY SUMNAME";
   	  $cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="0")
            $arrData[$i][1]=_("��");
         if($arrData[$i][1]=="1")
            $arrData[$i][1]=_("Ů");
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("δ��д");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	$i++;
    	}
    	$strXML = "<chart caption='"._("���Ա�ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //������ò
   if($SUMFIELD==4)
   {
       $query1="select STAFF_POLITICAL_STATUS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
       while($ROW=mysql_fetch_array($cursor))
       {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];
          $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
          if($arrData[$i][1]=="")
      	      $arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	    $i++;
       }
       $strXML = "<chart caption='"._("��������òͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //��ְ״̬
   if($SUMFIELD==5)
   {
      $query1="select WORK_STATUS  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"WORK_STATUS");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	    $i++;
      }
      $strXML = "<chart caption='"._("����ְ״̬ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //����
   if($SUMFIELD==6)
   {
      $query1="select STAFF_NATIVE_PLACE  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"AREA");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
   	  $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //ְ��
   if($SUMFIELD==7)
   {
   	  $query1="select PRESENT_POSITION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"PRESENT_POSITION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
      }
   		$strXML = "<chart caption='"._("��ְ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //Ա������
   if($SUMFIELD==8)
   {
   	  $query1="select STAFF_OCCUPATION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_OCCUPATION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	    $i++;
      }
   		$strXML = "<chart caption='"._("��Ա������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }

   //���뱾��λʱ��
   if($SUMFIELD==9)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
   	  {
   	     $YEAR[$i]=$j;
   	     $Y_M_D[$i]=$YEAR[$i]."-01-01";
   	     $j++;
   	  }
   	  for($i=0;$i<51;$i++)
   	  {
   	     $WHEN_STR="";
   	     if($Y_M_D[$i]!=="")
   	        $WHEN_STR.=" WHEN DATES_EMPLOYED between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
   	     $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
         $STRCASE=$STRCASE.$WHEN_STR;
   	  }

   	  $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	   $i++;
    	}
      $strXML = "<chart caption='"._("�����뵥λʱ��ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }


   //����//������λ����ͳ�ƣ���֯sql���
   if($SUMFIELD==10)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE1);
      $ARRAY_COUNT=sizeof($ARRAY_STATIS);
      for($i=0;$i<$ARRAY_COUNT;$i++)
      {
         $AGERANG=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($AGERANG[1]!="")
         {
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
            else
               $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
         }
         if($AGERANG[0]!="")
         {
            $AGERANG[0]--;
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
            else
               $WHEN_STR.="  and DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
         }

          $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
          $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
              COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
              from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
        else
            $arrData[$i][1]=$ROW["SUMNAME"]._("��");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	 $i++;
    	}
    	 $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //ѧλ
   if($SUMFIELD==11)
   {
    	$query1="select STAFF_HIGHEST_DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("δ����ѧϰ����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
      }
      $strXML = "<chart caption='"._("������ѧλͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_HT")
{
	 //��ͬ����
   if($SUMFIELD==1)
   {
    	$query1="select CONTRACT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ͬ����ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ͬ״̬
   if($SUMFIELD==2)
   {
    	$query1="select STATUS as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ͬ״̬ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
    //��ͬ����
   if($SUMFIELD==3)
   {
    	$query1="select CONTRACT_SPECIALIZATION  as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]==1)
            $arrData[$i][1]=_("�й̶�����");
         if($arrData[$i][1]==2)
            $arrData[$i][1]=_("�޹̶�����");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ͬ����ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
    //���뱾��λʱ��
   if($SUMFIELD==4)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
   	  {
   	     $YEAR[$i]=$j;
   	     $Y_M_D[$i]=$YEAR[$i]."-01-01";
   	     $j++;
   	  }
   	  for($i=0;$i<51;$i++)
   	  {
   	     $WHEN_STR="";
   	     if($Y_M_D[$i]!=="")
   	        $WHEN_STR.=" WHEN MAKE_CONTRACT  between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
   	     $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
          $STRCASE=$STRCASE.$WHEN_STR;
   	  }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
      $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];
          if($arrData[$i][1]=="")
       		   $arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
      }
      $strXML = "<chart caption='"._("����ͬǩ������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JC")
{
   	//������Ŀ
   if($SUMFIELD==1)
   {
    	$query1="select INCENTIVE_ITEM  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_INCENTIVE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
       $strXML = "<chart caption='"._("��������Ŀͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��������
   if($SUMFIELD==2)
   {
    	$query1="select INCENTIVE_TYPE  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp=$arrData[$i][1];
         $query2="select CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE' and CODE_NO='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         if($ROW2=mysql_fetch_array($cursor2))
             $arrData[$i][1]=$ROW2["CODE_NAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_ZZ")
{
	 	//֤������
   if($SUMFIELD==1)
   {
    	 $query1="select LICENSE_TYPE   as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("��֤������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //֤��״̬
   if($SUMFIELD==2)
   {
    	$query1="select STATUS    as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("��֤��״̬ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_XX")
{
	 //����ѧλ
   if($SUMFIELD==1)
   {
    	$query1="select DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
      	 $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("������ѧλͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JL")
{
	 //���ڲ���
   if($SUMFIELD==1)
   {
    	$query1="select WORK_BRANCH  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	}
      $strXML = "<chart caption='"._("�����ڲ���ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //����ְ��
   if($SUMFIELD==2)
   {
    	$query1="select POST_OF_JOB  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("������ְ��ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_JN")
{
   	//��������
   if($SUMFIELD==1)
   {
    	 $query1="select ABILITY_NAME as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //���ܼ���
   if($SUMFIELD==2)
   {
    	 $query1="select SKILLS_LEVEL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("�����ܼ���ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_GX")
{
   	//�뱾�˹�ϵ
   if($SUMFIELD==1)
   {
    	 $query1="select RELATIONSHIP  as SUMNAME,COUNT(DISTINCT b.USER_ID  ) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_RELATIVES");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("���뱾�˹�ϵͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //������ò
   if($SUMFIELD==2)
   {
    	 $query1="select POLITICS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("��������òͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_DD")
{
	 //��������
   if($SUMFIELD==1)
   {
    	 $query1="select TRANSFER_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_TRANSFER.TRANSFER_ID) as TOTALCOUNT from  HR_STAFF_TRANSFER,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TRANSFER.TRANSFER_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TRANSFER");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����������ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_LZ")
{
   		//��ְ����
   if($SUMFIELD==1)
   {
    	 $query1="select QUIT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_LEAVE.LEAVE_ID) as TOTALCOUNT from  HR_STAFF_LEAVE,HR_STAFF_INFO,USER as b  ".$condition_str."and HR_STAFF_LEAVE.LEAVE_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LEAVE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ְ���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$LEAVE_RANGE);
    	 $ARRAY_COUNT=sizeof($ARRAY_STATIS);
    	 for($i=0;$i<$ARRAY_COUNT;$i++)
      {//echo $ARRAY_STATIS[$i];echo "<br>";
         $LEAVERANGE=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($LEAVERANGE[1]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
        	   else
               $WHEN_STR.="  and '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
             $LEAVERANGE[1]++;
         }
         if($LEAVERANGE[0]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
        	   else
               $WHEN_STR.="  and QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
         }

       $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
       $STRCASE=$STRCASE.$WHEN_STR;
       }

       $query1="select (CASE ".$STRCASE."  END) as SUMNAME,
               COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
               from HR_STAFF_LEAVE a left outer join USER b on a.LEAVE_PERSON=b.USER_ID".$condition_str." GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
       	$i++;
    	 }
    	 $strXML = "<chart caption='"._("����ְͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_FZ")
{
	 //��ְ����
   if($SUMFIELD==1)
   {
    	 $query1="select REAPPOINTMENT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_REINSTATEMENT.REINSTATEMENT_ID) as TOTALCOUNT from  HR_STAFF_REINSTATEMENT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_REINSTATEMENT.REINSTATEMENT_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_REINSTATEMENT");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ְ����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_ZC")
{
	 //��ȡ��ʽ
   if($SUMFIELD==1)
   {
    	 $query1="select GET_METHOD  as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TITLE_EVALUATION");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ȡ��ʽͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //��ȡְ��
   if($SUMFIELD==2)
   {
    	 $query1="select POST_NAME as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("����ȡְ��ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if($MODULE=="HR_GH")
{
	 //�ػ�����
   if($SUMFIELD==1)
   {
    	 $query1="select CARE_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CARE.CARE_ID) as TOTALCOUNT from  HR_STAFF_CARE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CARE.BY_CARE_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CARE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $i++;
    	 }
      $strXML = "<chart caption='"._("���ػ�����ͳ��")."' formatNumberScale='0'  baseFontSize='18'>";
   }
}
if(empty($arrData))
   Message("",_("�޼�¼"));
else
{
   foreach ($arrData as $arSubData)
         $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "'/>";
   $strXML .= "</chart>";

   echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Column3D.swf", "", "$strXML", "myFirst", "80%", "500", false, false);
}
?>
   </BODY>
</HTML>
<?}
if($CHOSE==3){

$HTML_PAGE_TITLE = _("ͳ�Ʊ�");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
$STAFF_ID_STR = "";
$CONTRACT_ID_STR = "";
$INCENTIVE_ID_STR = "";
$LICENSE_ID_STR = "";
$L_EXPERIENCE_ID_STR = "";
$W_EXPERIENCE_ID_STR = "";
$SKILLS_ID_STR = "";
$RELATIVES_ID_STR = "";
$TRANSFER_ID_STR = "";
$LEAVE_ID_STR = "";
$REINSTATEMENT_ID_STR = "";
$EVALUATION_ID_STR = "";
$CARE_ID_STR = "";
if($MODULE=="HR_INFO")
{  //ѧ��
   if($SUMFIELD==1)
   {
    	 $query1="select STAFF_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
       while($ROW=mysql_fetch_array($cursor))
       {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and STAFF_HIGHEST_SCHOOL='$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
          $STAFF_ID_STR = td_trim($STAFF_ID_STR);

          $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_HIGHEST_SCHOOL");
          if($arrData[$i][1]=="")
          		$arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
          $STAFF_ID_STR = "";
          $i++;
    	 }
    	 $strXML = _("��ѧ��ͳ�ƣ��ˣ�");
   }

   //����//������ͳ�ƣ���֯sql���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE);
    	 $ARRAY_COUNT=sizeof($ARRAY_STATIS);
    	 for($i=0;$i< $ARRAY_COUNT;$i++)
       {
          $AGERANG=explode("-",$ARRAY_STATIS[$i]);
          $WHEN_STR="";
          if($AGERANG[1]!="")
          {
             if($WHEN_STR=="")
                $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= STAFF_BIRTH";
             else
                $WHEN_STR.=" and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= STAFF_BIRTH";
          }
          if($AGERANG[0]!="")
          {
             $AGERANG[0]--;
             if($WHEN_STR=="")
                $WHEN_STR.=" WHEN STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
             else
                $WHEN_STR.="  and STAFF_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
          }

          $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
          $STRCASE=$STRCASE.$WHEN_STR;
       }

       $query1="select (CASE ".$STRCASE."  END) as SUMNAME,
               COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
               from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
       while($ROW=mysql_fetch_array($cursor))
       {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];
          $tmp = $arrData[$i][1];
          $tmp_str = explode("-",$tmp);
          if($tmp_str[0]=="" ||$tmp_str[1]=="" )
          {
             $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query.$WHERE_STR." and STAFF_BIRTH='0000-00-00'";
          }
          else
          {
             $tmp_str[0]--;
             $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query.$WHERE_STR." and STAFF_BIRTH>=DATE_SUB(CURRENT_DATE(),INTERVAL $tmp_str[1] YEAR) and STAFF_BIRTH <= DATE_SUB(CURRENT_DATE(),INTERVAL $tmp_str[0] YEAR)";
          }
          //echo $query2;
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
          $STAFF_ID_STR = td_trim($STAFF_ID_STR);
          if($arrData[$i][1]=="")
             $arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
          $STAFF_ID_STR = "";
       	  $i++;
    	 }
    	 $strXML = _("������ͳ�ƣ��ˣ�");
   }

   //�Ա�
   if($SUMFIELD==3)
   {
      $query1="select STAFF_SEX as SUMNAME,
             COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
             from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query." GROUP BY SUMNAME";
   	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and STAFF_SEX='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("δ��д");
         if($arrData[$i][1]=="0")
            $arrData[$i][1]=_("��");
         if($arrData[$i][1]=="1")
            $arrData[$i][1]=_("Ů");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
       	 $i++;
    	}
    	  $strXML = _("���Ա�ͳ�ƣ��ˣ�");
   }

   //������ò
   if($SUMFIELD==4)
   {
      $query1="select STAFF_POLITICAL_STATUS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and STAFF_POLITICAL_STATUS='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
    	   $i++;
      }
      $strXML = _("��������òͳ�ƣ��ˣ�");
   }

   //��ְ״̬
   if($SUMFIELD==5)
   {
      $query1="select WORK_STATUS  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and WORK_STATUS='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"WORK_STATUS");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
    	    $i++;
      }
      $strXML = _("����ְ״̬ͳ�ƣ��ˣ�");
   }

   //����
   if($SUMFIELD==6)
   {
      $query1="select STAFF_NATIVE_PLACE  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
      $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and STAFF_NATIVE_PLACE='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"AREA");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
    	    $i++;
      }
   	  $strXML = _("������ͳ�ƣ��ˣ�");
   }

   //ְ��
   if($SUMFIELD==7)
   {
   	 $query1="select PRESENT_POSITION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and PRESENT_POSITION='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"PRESENT_POSITION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
    	    $i++;
      }
   		$strXML = _("��ְ��ͳ�ƣ��ˣ�");
   }

   //Ա������
   if($SUMFIELD==8)
   {
   	 $query1="select STAFF_OCCUPATION  as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and STAFF_OCCUPATION='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_OCCUPATION");
         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
    	    $i++;
      }
   		$strXML = _("��Ա������ͳ�ƣ��ˣ�");
   }

   //���뱾��λʱ��
   if($SUMFIELD==9)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
   	 {
   	    $YEAR[$i]=$j;
   	    $Y_M_D[$i]=$YEAR[$i]."-01-01";
   	    $j++;
   	 }
   	 for($i=0;$i<51;$i++)
   	 {
   	    $WHEN_STR="";
   	    if($Y_M_D[$i]!=="")
   	       $WHEN_STR.=" WHEN DATES_EMPLOYED between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
   	    $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
         $STRCASE=$STRCASE.$WHEN_STR;
   	 }

   	 $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
     $cursor= exequery(TD::conn(),$query1);
     $i=0;
     while($ROW=mysql_fetch_array($cursor))
     {
        $HRMS_COUNT++;
        $arrData[$i][1]=$ROW["SUMNAME"];

        if($arrData[$i][1]!="")
        {
           $tmp = $arrData[$i][1];
           $tmp1 = $tmp + 1 ;
           $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and DATES_EMPLOYED>='$tmp-01-01' and DATES_EMPLOYED <= '$tmp1-01-01'";
        }
        else
        {
           $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query." and DATES_EMPLOYED ='0000-00-00'";
        }
        $cursor2= exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
           $STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
        $STAFF_ID_STR = td_trim($STAFF_ID_STR);

        if($arrData[$i][1]=="")
     		   $arrData[$i][1]=_("����");
        $arrData[$i][2]=$ROW["TOTALCOUNT"];
        $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
        $STAFF_ID_STR = "";
        $i++;
     }
     $strXML = _("�����뵥λʱ��ͳ�ƣ��ˣ�");
   }

   //����//������λ����ͳ�ƣ���֯sql���
   if($SUMFIELD==10)
   {
      $ARRAY_STATIS=explode(",",$AGE_RANGE1);
      $ARRAY_COUNT=sizeof($ARRAY_STATIS);
      for($i=0;$i<$ARRAY_COUNT;$i++)
      {
         $AGERANG=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($AGERANG[1]!="")
         {
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
            else
               $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR) <= DATES_EMPLOYED ";
         }
         if($AGERANG[0]!="")
         {
            $AGERANG[0]--;
            if($WHEN_STR=="")
               $WHEN_STR.=" WHEN DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
            else
               $WHEN_STR.="  and DATES_EMPLOYED <=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
         }

          $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
          $STRCASE=$STRCASE.$WHEN_STR;
      }

      $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
              COUNT(DISTINCT b.USER_ID) as TOTALCOUNT
              from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID".$query.$WHERE_STR." GROUP BY SUMNAME";
    	$cursor= exequery(TD::conn(),$query1);
    	$i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp = $arrData[$i][1];

                   $tmp_str = explode("-",$tmp);
          if($tmp_str[0]=="" ||$tmp_str[1]=="" )
          {
             $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query.$WHERE_STR." and DATES_EMPLOYED='0000-00-00'";
          }
          else
          {
             $tmp_str[0]--;
             $query2 = "select a.STAFF_ID from HR_STAFF_INFO a left outer join USER b on a.USER_ID=b.USER_ID ".$query.$WHERE_STR." and DATES_EMPLOYED>=DATE_SUB(CURRENT_DATE(),INTERVAL $tmp_str[1] YEAR) and DATES_EMPLOYED <= DATE_SUB(CURRENT_DATE(),INTERVAL $tmp_str[0] YEAR)";
          }
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
         	$STAFF_ID_STR.= $ROW2['STAFF_ID'].",";
         $STAFF_ID_STR = td_trim($STAFF_ID_STR);
         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         else
            $arrData[$i][1]=$arrData[$i][1]._("��");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;
         $STAFF_ID_STR = "";
       	$i++;
    	}
    	 $strXML = "<chart caption='"._("������ͳ�ƣ��ˣ�")."' formatNumberScale='0'  baseFontSize='18'>";
   }
   //ѧλ
   if($SUMFIELD==11)
   {
    	 $query1="select STAFF_HIGHEST_DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT,a.USER_ID from  HR_STAFF_INFO a
    	 LEFT OUTER JOIN USER b on a.USER_ID=b.USER_ID ".$query."GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp = $arrData[$i][1];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");

         $query2= "select HR_STAFF_LEARN_EXPERIENCE.L_EXPERIENCE_ID from HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID AND DEGREE='$tmp'";

         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $L_EXPERIENCE_ID_STR.= $ROW2['L_EXPERIENCE_ID'].",";
         $L_EXPERIENCE_ID_STR = td_trim($L_EXPERIENCE_ID_STR);
         if($arrData[$i][1]=="")
         {
         		$arrData[$i][1]=_("δ����ѧϰ����");
         }
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?L_EXPERIENCE_ID_STR=".$L_EXPERIENCE_ID_STR."&FLAG=1";
         $L_EXPERIENCE_ID_STR = "";
         $i++;
    	}
      $strXML = _("��ѧλͳ��");
   }
}
if($MODULE=="HR_HT")
{
	 //��ͬ����
   if($SUMFIELD==1)
   {
    	 $query1="select CONTRACT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
       while($ROW=mysql_fetch_array($cursor))
       {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2 = "select HR_STAFF_CONTRACT.CONTRACT_ID from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and CONTRACT_TYPE = '$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $CONTRACT_ID_STR.= $ROW2["CONTRACT_ID"].",";
          $CONTRACT_ID_STR = td_trim($CONTRACT_ID_STR);

          $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT1");
          if($arrData[$i][1]=="")
          		$arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?CONTRACT_ID_STR=".$CONTRACT_ID_STR;
          $CONTRACT_ID_STR = "";
          $i++;
    	 }
        $strXML = _("����ͬ����ͳ�ƣ��ˣ�");
   }
   //��ͬ״̬
   if($SUMFIELD==2)
   {
    	 $query1="select STATUS as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select HR_STAFF_CONTRACT.CONTRACT_ID from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and STATUS = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $CONTRACT_ID_STR.= $ROW2['CONTRACT_ID'].",";
         $CONTRACT_ID_STR = td_trim($CONTRACT_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CONTRACT2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?CONTRACT_ID_STR=".$CONTRACT_ID_STR;
         $CONTRACT_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����ͬ״̬ͳ�ƣ��ˣ�");
   }
    //��ͬ����
   if($SUMFIELD==3)
   {
    	 $query1="select CONTRACT_SPECIALIZATION  as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from  HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select HR_STAFF_CONTRACT.CONTRACT_ID from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and CONTRACT_SPECIALIZATION = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $CONTRACT_ID_STR.= $ROW2['CONTRACT_ID'].",";
         $CONTRACT_ID_STR = td_trim($CONTRACT_ID_STR);

         if($arrData[$i][1]==1)
            $arrData[$i][1]=_("�й̶�����");
         if($arrData[$i][1]==2)
            $arrData[$i][1]=_("�޹̶�����");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?CONTRACT_ID_STR=".$CONTRACT_ID_STR;
         $CONTRACT_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����ͬ����ͳ�ƣ��ˣ�");
   }
    //���뱾��λʱ��
   if($SUMFIELD==4)
   {
      $j=1975;
      for($i=0;$i<52;$i++)
   	 {
   	    $YEAR[$i]=$j;
   	    $Y_M_D[$i]=$YEAR[$i]."-01-01";
   	    $j++;
   	 }
   	 for($i=0;$i<51;$i++)
   	 {
   	    $WHEN_STR="";
   	    if($Y_M_D[$i]!=="")
   	       $WHEN_STR.=" WHEN MAKE_CONTRACT  between '".$Y_M_D[$i]. "' and '".$Y_M_D[$i+1]."'";
   	    $WHEN_STR=$WHEN_STR." THEN  '".$YEAR[$i]."'";
        $STRCASE=$STRCASE.$WHEN_STR;
   	 }

   	 $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,COUNT(DISTINCT HR_STAFF_CONTRACT.CONTRACT_ID) as TOTALCOUNT from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
     $cursor= exequery(TD::conn(),$query1);
     $i=0;
     while($ROW=mysql_fetch_array($cursor))
     {
        $HRMS_COUNT++;
        $arrData[$i][1]=$ROW["SUMNAME"];

        if($arrData[$i][1]!="")
        {
           $tmp = $arrData[$i][1];
           $tmp1 = $tmp + 1 ;
           $query2 = "select HR_STAFF_CONTRACT.CONTRACT_ID from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and MAKE_CONTRACT>='$tmp-01-01' and MAKE_CONTRACT <= '$tmp1-01-01'";
        }
        else
        {
           $query2 = "select HR_STAFF_CONTRACT.CONTRACT_ID from HR_STAFF_CONTRACT,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_CONTRACT.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and DATES_EMPLOYED=''";
        }

         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $CONTRACT_ID_STR.= $ROW2['CONTRACT_ID'].",";
         $CONTRACT_ID_STR = td_trim($CONTRACT_ID_STR);

         if($arrData[$i][1]=="")
      		   $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?CONTRACT_ID_STR=".$CONTRACT_ID_STR;
         $CONTRACT_ID_STR = "";
    	   $i++;
    	}
      $strXML = _("����ͬǩ������ͳ�ƣ��ˣ�");
   }
}
if($MODULE=="HR_JC")
{
   	//������Ŀ
   if($SUMFIELD==1)
   {
    	  $query1="select INCENTIVE_ITEM  as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
       $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2 = "select HR_STAFF_INCENTIVE.INCENTIVE_ID from HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and INCENTIVE_ITEM = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $INCENTIVE_ID_STR.= $ROW2['INCENTIVE_ID'].",";
         $INCENTIVE_ID_STR = td_trim($INCENTIVE_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_INCENTIVE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?INCENTIVE_ID_STR=".$INCENTIVE_ID_STR;
         $INCENTIVE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("��������Ŀͳ��");
   }
   //��������
   if($SUMFIELD==2)
   {
    	 $query1="select INCENTIVE_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_INCENTIVE.INCENTIVE_ID) as TOTALCOUNT from  HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp=$arrData[$i][1];
         $query2="select CODE_NAME from HR_CODE where PARENT_NO='INCENTIVE_TYPE' and CODE_NO='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         if($ROW2=mysql_fetch_array($cursor2))
             $arrData[$i][1]=$ROW2["CODE_NAME"];
         $tmp = $arrData[$i][1];
         $query2 = "select HR_STAFF_INCENTIVE.INCENTIVE_ID from HR_STAFF_INCENTIVE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_INCENTIVE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and INCENTIVE_TYPE = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $INCENTIVE_ID_STR.= $ROW2['INCENTIVE_ID'].",";
         $INCENTIVE_ID_STR = td_trim($INCENTIVE_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?INCENTIVE_ID_STR=".$INCENTIVE_ID_STR;
         $INCENTIVE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����������ͳ��");
   }
}
if($MODULE=="HR_ZZ")
{
	 	//֤������
   if($SUMFIELD==1)
   {
    	 $query1="select LICENSE_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_LICENSE.LICENSE_ID from HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and HR_STAFF_LICENSE.LICENSE_TYPE='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $LICENSE_ID_STR.= $ROW2['LICENSE_ID'].",";
         $LICENSE_ID_STR = td_trim($LICENSE_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE1");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?LICENSE_ID_STR=".$LICENSE_ID_STR;
         $LICENSE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("��֤������ͳ��");
   }
   //֤��״̬
   if($SUMFIELD==2)
   {
    	 $query1="select STATUS as SUMNAME,COUNT(DISTINCT HR_STAFF_LICENSE.LICENSE_ID ) as TOTALCOUNT from  HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_LICENSE.LICENSE_ID from HR_STAFF_LICENSE,HR_STAFF_INFO,USER as b ".$query."and HR_STAFF_LICENSE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and HR_STAFF_LICENSE.STATUS='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $LICENSE_ID_STR.= $ROW2['LICENSE_ID'].",";
         $LICENSE_ID_STR = td_trim($LICENSE_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LICENSE2");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?LICENSE_ID_STR=".$LICENSE_ID_STR;
         $LICENSE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("��֤��״̬ͳ��");
   }
}
if($MODULE=="HR_XX")
{
	 //����ѧλ
   if($SUMFIELD==1)
   {
    	 $query1="select DEGREE as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];
         $tmp = $arrData[$i][1];
         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"EMPLOYEE_HIGHEST_DEGREE");

         $query2= "select HR_STAFF_LEARN_EXPERIENCE.L_EXPERIENCE_ID from HR_STAFF_LEARN_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LEARN_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID AND DEGREE='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $L_EXPERIENCE_ID_STR.= $ROW2['L_EXPERIENCE_ID'].",";
         $L_EXPERIENCE_ID_STR = td_trim($L_EXPERIENCE_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?L_EXPERIENCE_ID_STR=".$L_EXPERIENCE_ID_STR."&FLAG=1";
         $L_EXPERIENCE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("������ѧλͳ��");
   }
}
if($MODULE=="HR_JL")
{
	 //���ڲ���
   if($SUMFIELD==1)
   {
    	 $query1="select WORK_BRANCH  as SUMNAME,COUNT(DISTINCT HR_STAFF_WORK_EXPERIENCE.W_EXPERIENCE_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_WORK_EXPERIENCE.W_EXPERIENCE_ID from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and WORK_BRANCH = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $W_EXPERIENCE_ID_STR.= $ROW2['W_EXPERIENCE_ID'].",";
         $W_EXPERIENCE_ID_STR = td_trim($W_EXPERIENCE_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?W_EXPERIENCE_ID_STR=".$W_EXPERIENCE_ID_STR;
         $W_EXPERIENCE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("�����ڲ���ͳ��");
   }
   //����ְ��
   if($SUMFIELD==2)
   {
    	 $query1="select POST_OF_JOB  as SUMNAME,COUNT(DISTINCT HR_STAFF_WORK_EXPERIENCE.W_EXPERIENCE_ID) as TOTALCOUNT from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_WORK_EXPERIENCE.W_EXPERIENCE_ID from  HR_STAFF_WORK_EXPERIENCE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_WORK_EXPERIENCE.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and POST_OF_JOB = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $W_EXPERIENCE_ID_STR.= $ROW2['W_EXPERIENCE_ID'].",";
         $W_EXPERIENCE_ID_STR = td_trim($W_EXPERIENCE_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?W_EXPERIENCE_ID_STR=".$W_EXPERIENCE_ID_STR;
         $W_EXPERIENCE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("������ְ��ͳ��");
   }
}
if($MODULE=="HR_JN")
{
   	//��������
   if($SUMFIELD==1)
   {
    	 $query1="select ABILITY_NAME as SUMNAME,COUNT(DISTINCT HR_STAFF_LABOR_SKILLS.SKILLS_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_LABOR_SKILLS.SKILLS_ID from HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and ABILITY_NAME = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $SKILLS_ID_STR.= $ROW2['SKILLS_ID'].",";
         $SKILLS_ID_STR = td_trim($SKILLS_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?SKILLS_ID_STR=".$SKILLS_ID_STR;
         $SKILLS_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����������ͳ��");
   }
   //���ܼ���
   if($SUMFIELD==2)
   {
    	 $query1="select SKILLS_LEVEL as SUMNAME,COUNT(DISTINCT HR_STAFF_LABOR_SKILLS.SKILLS_ID) as TOTALCOUNT from  HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_LABOR_SKILLS.SKILLS_ID from HR_STAFF_LABOR_SKILLS,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_LABOR_SKILLS.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and SKILLS_LEVEL = '$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $SKILLS_ID_STR.= $ROW2['SKILLS_ID'].",";
         $SKILLS_ID_STR = td_trim($SKILLS_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?SKILLS_ID_STR=".$SKILLS_ID_STR;
         $SKILLS_ID_STR = "";
         $i++;
    	 }
      $strXML = _("�����ܼ���ͳ��");
   }
}
if($MODULE=="HR_GX")
{
   	//�뱾�˹�ϵ
   if($SUMFIELD==1)
   {
    	 $query1="select RELATIONSHIP  as SUMNAME,COUNT(DISTINCT b.USER_ID  ) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_RELATIVES.RELATIVES_ID from HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and RELATIONSHIP='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $RELATIVES_ID_STR.= $ROW2['RELATIVES_ID'].",";
         $RELATIVES_ID_STR = td_trim($RELATIVES_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_RELATIVES");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?RELATIVES_ID_STR=".$RELATIVES_ID_STR;
         $RELATIVES_ID_STR = "";
         $i++;
    	 }
      $strXML = _("���뱾�˹�ϵͳ��");
   }
   //������ò
   if($SUMFIELD==2)
   {
    	 $query1="select POLITICS as SUMNAME,COUNT(DISTINCT b.USER_ID) as TOTALCOUNT from  HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_RELATIVES.RELATIVES_ID from HR_STAFF_RELATIVES,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_RELATIVES.STAFF_NAME=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and POLITICS='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $RELATIVES_ID_STR.= $ROW2['RELATIVES_ID'].",";
         $RELATIVES_ID_STR = td_trim($RELATIVES_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_POLITICAL_STATUS");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?RELATIVES_ID_STR=".$RELATIVES_ID_STR;
         $RELATIVES_ID_STR = "";
         $i++;
    	 }
      $strXML = _("��������òͳ��");
   }
}
if($MODULE=="HR_DD")
{
	 //��������
   if($SUMFIELD==1)
   {
    	 $query1="select TRANSFER_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_TRANSFER.TRANSFER_ID) as TOTALCOUNT from  HR_STAFF_TRANSFER,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TRANSFER.TRANSFER_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_TRANSFER.TRANSFER_ID from  HR_STAFF_TRANSFER,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TRANSFER.TRANSFER_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and TRANSFER_TYPE='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $TRANSFER_ID_STR.= $ROW2['TRANSFER_ID'].",";
         $TRANSFER_ID_STR = td_trim($TRANSFER_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TRANSFER");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?TRANSFER_ID_STR=".$TRANSFER_ID_STR;
         $TRANSFER_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����������ͳ��");
   }
}
if($MODULE=="HR_LZ")
{
   		//��ְ����
   if($SUMFIELD==1)
   {
    	 $query1="select QUIT_TYPE as SUMNAME,COUNT(HR_STAFF_LEAVE.LEAVE_ID) as TOTALCOUNT from  HR_STAFF_LEAVE,HR_STAFF_INFO,USER as b  ".$condition_str."and HR_STAFF_LEAVE.LEAVE_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $query2= "select HR_STAFF_LEAVE.LEAVE_ID from  HR_STAFF_LEAVE,HR_STAFF_INFO,USER as b  ".$condition_str."and HR_STAFF_LEAVE.LEAVE_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and QUIT_TYPE='$tmp'";
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $LEAVE_ID_STR.= $ROW2['LEAVE_ID'].",";
         $LEAVE_ID_STR = td_trim($LEAVE_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_LEAVE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?LEAVE_ID_STR=".$LEAVE_ID_STR;
         $LEAVE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����ְ����ͳ��");
   }
   //��ְ���
   if($SUMFIELD==2)
   {
      $ARRAY_STATIS=explode(",",$LEAVE_RANGE);
    	 $ARRAY_COUNT=sizeof($ARRAY_STATIS);
    	 for($i=0;$i<$ARRAY_COUNT;$i++)
       {//echo $ARRAY_STATIS[$i];echo "<br>";
         $LEAVERANGE=explode("-",$ARRAY_STATIS[$i]);
         $WHEN_STR="";
         if($LEAVERANGE[1]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
        	   else
               $WHEN_STR.="  and '".$LEAVERANGE[1]."-12-31' >= QUIT_TIME_FACT";
             $LEAVERANGE[1]++;
         }
         if($LEAVERANGE[0]!="")
         {
        	   if($WHEN_STR=="")
               $WHEN_STR.=" WHEN QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
        	   else
               $WHEN_STR.="  and QUIT_TIME_FACT >= '".$LEAVERANGE[0]."-01-01'";
         }

         $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
         $STRCASE=$STRCASE.$WHEN_STR;
       }

       $query1="select (CASE ".$STRCASE."  END) as SUMNAME,
               COUNT(a.LEAVE_ID) as TOTALCOUNT
               from HR_STAFF_LEAVE a left outer join USER b on a.LEAVE_PERSON=b.USER_ID".$condition_str." GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

         $tmp = $arrData[$i][1];
         $tmp_str = explode("-",$tmp);
         if($tmp_str[0]=="" ||$tmp_str[1]=="" )
         {
            $query2 = "select a.LEAVE_ID from HR_STAFF_LEAVE a left outer join USER b on a.LEAVE_PERSON=b.USER_ID".$condition_str." and QUIT_TIME_FACT = '0000-00-00' ";
         }
         else
         {
            $query2 = "select a.LEAVE_ID from HR_STAFF_LEAVE a left outer join USER b on a.LEAVE_PERSON=b.USER_ID".$condition_str." and QUIT_TIME_FACT <= '".$tmp_str[1]."-01-01' and QUIT_TIME_FACT >= '".$tmp_str[0]."-01-01' ";
            $tmp_str[1]++;
         }
         $cursor2= exequery(TD::conn(),$query2);
         while($ROW2=mysql_fetch_array($cursor2))
            $LEAVE_ID_STR.= $ROW2['LEAVE_ID'].",";
         $LEAVE_ID_STR = td_trim($LEAVE_ID_STR);

         if($arrData[$i][1]=="")
            $arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
         $arrData[$i][3]="detail.php?LEAVE_ID_STR=".$LEAVE_ID_STR;
         $LEAVE_ID_STR = "";
       	$i++;
    	 }
    	 $strXML = _("����ְͳ�ƣ��ˣ�");
   }
}
if($MODULE=="HR_FZ")
{
	 //��ְ����
   if($SUMFIELD==1)
   {
    	 $query1="select REAPPOINTMENT_TYPE as SUMNAME,COUNT(DISTINCT HR_STAFF_REINSTATEMENT.REINSTATEMENT_ID) as TOTALCOUNT from  HR_STAFF_REINSTATEMENT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_REINSTATEMENT.REINSTATEMENT_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
       while($ROW=mysql_fetch_array($cursor))
       {
          $HRMS_COUNT++;
          $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2= "select HR_STAFF_REINSTATEMENT.REINSTATEMENT_ID from  HR_STAFF_REINSTATEMENT,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_REINSTATEMENT.REINSTATEMENT_PERSON=HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and REAPPOINTMENT_TYPE='$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $REINSTATEMENT_ID_STR.= $ROW2['REINSTATEMENT_ID'].",";
          $REINSTATEMENT_ID_STR = td_trim($REINSTATEMENT_ID_STR);

          $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_REINSTATEMENT");
          if($arrData[$i][1]=="")
          		$arrData[$i][1]=_("����");
          $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?REINSTATEMENT_ID_STR=".$REINSTATEMENT_ID_STR;
          $REINSTATEMENT_ID_STR = "";
          $i++;
    	 }
       $strXML = _("����ְ����ͳ��");
   }
}
if($MODULE=="HR_ZC")
{
	 //��ȡ��ʽ
   if($SUMFIELD==1)
   {
    	 $query1="select GET_METHOD  as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2= "select HR_STAFF_TITLE_EVALUATION.EVALUATION_ID from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and GET_METHOD='$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $EVALUATION_ID_STR.= $ROW2['EVALUATION_ID'].",";
          $EVALUATION_ID_STR = td_trim($EVALUATION_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_TITLE_EVALUATION");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?EVALUATION_ID_STR=".$EVALUATION_ID_STR;
          $EVALUATION_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����ȡ��ʽͳ��");
   }
   //��ȡְ��
   if($SUMFIELD==2)
   {
    	 $query1="select POST_NAME as SUMNAME,COUNT(DISTINCT HR_STAFF_TITLE_EVALUATION.EVALUATION_ID) as TOTALCOUNT from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2= "select HR_STAFF_TITLE_EVALUATION.EVALUATION_ID from  HR_STAFF_TITLE_EVALUATION,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_TITLE_EVALUATION.BY_EVALU_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and POST_NAME='$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $EVALUATION_ID_STR.= $ROW2['EVALUATION_ID'].",";
          $EVALUATION_ID_STR = td_trim($EVALUATION_ID_STR);

         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?EVALUATION_ID_STR=".$EVALUATION_ID_STR;
          $EVALUATION_ID_STR = "";
         $i++;
    	 }
      $strXML = _("����ȡְ��ͳ��");
   }
}
if($MODULE=="HR_GH")
{
	 //�ػ�����
   if($SUMFIELD==1)
   {
    	 $query1="select CARE_TYPE as SUMNAME,COUNT(HR_STAFF_CARE.CARE_ID) as TOTALCOUNT from  HR_STAFF_CARE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CARE.BY_CARE_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID GROUP BY SUMNAME";
    	 $cursor= exequery(TD::conn(),$query1);
    	 $i=0;
      while($ROW=mysql_fetch_array($cursor))
      {
         $HRMS_COUNT++;
         $arrData[$i][1]=$ROW["SUMNAME"];

          $tmp = $arrData[$i][1];
          $query2= "select HR_STAFF_CARE.CARE_ID from  HR_STAFF_CARE,HR_STAFF_INFO,USER as b  ".$query."and HR_STAFF_CARE.BY_CARE_STAFFS =HR_STAFF_INFO.USER_ID and HR_STAFF_INFO.USER_ID=b.USER_ID and CARE_TYPE='$tmp'";
          $cursor2= exequery(TD::conn(),$query2);
          while($ROW2=mysql_fetch_array($cursor2))
             $CARE_ID_STR.= $ROW2['CARE_ID'].",";
          $CARE_ID_STR = td_trim($CARE_ID_STR);

         $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"HR_STAFF_CARE");
         if($arrData[$i][1]=="")
         		$arrData[$i][1]=_("����");
         $arrData[$i][2]=$ROW["TOTALCOUNT"];
          $arrData[$i][3]="detail.php?CARE_ID_STR=".$CARE_ID_STR;
          $CARE_ID_STR = "";
         $i++;
    	 }
      $strXML = _("���ػ�����ͳ��");
   }
}
if(empty($arrData))
   Message("",_("�޼�¼"));
else
{
   echo "<br><center><b>".$strXML."</b></center><br>";
?>
   <TABLE align="center" width="600" class="TableBlock">
<?
   foreach ($arrData as $arSubData)
   {
?>
      <tr>
      	<td class='TableContent' width='300'><?=$arSubData[1]?></td>
      	<td class='TableData' align='center'>
      		<a href="javascript:;" onClick="window.open('<?=$arSubData[3]?>','','height=500,width=1200,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=360,resizable=yes');">
      		<?=$arSubData[2]?></a>
        </td>
      </tr>
<?
   }
}
?>
      </TABLE>
   </BODY>
</HTML>
<?}?>