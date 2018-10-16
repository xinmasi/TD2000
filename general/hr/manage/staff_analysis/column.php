<?
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("简单柱状图测试");
include_once("inc/header.inc.php");
?>
 
<script language="jAVASCRIPT" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<?

$CUR_DATE=date("Y-m-d");
if($TO_ID!="ALL_DEPT")  //未选中全体部门
{
  if ($TO_ID!="")  //选择若干部门
 	{
      $DEPT_ID=$TO_ID;
      if (substr($DEPT_ID,-1)==",")
 	      $DEPT_ID=substr($DEPT_ID,0,-1);
 	}
 	else  //未选择部门
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
else  //选中全体部门
{
    if($query=="")
       $query.=" where b.DEPT_ID !='0'";
    else
  	   $query.=" and b.DEPT_ID !='0'";		
} 
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td>
   <img src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" width="18" HEIGHT="18"><span class="big3"><?=_("人事档案统计结果")?></span>&nbsp;&nbsp;
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';" title="<?=_("返回")?>">
  </td>
</tr>
</table>
<br>
<?
//学历
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
      		$arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
      $i++;
 	 }
}

//年龄//按年龄统计，组织sql语句
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
     	   $AGERANG[1]++;
     	   if($WHEN_STR=="")
            $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= STAFF_BIRTH";
     	   else
            $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<=STAFF_BIRTH";
      }
      if($AGERANG[0]!="")
      {
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
         $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	$i++;
 	 }
}

//性别
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
      if($arrData[$i][1]==0) 
         $arrData[$i][1]=_("男");
      if($arrData[$i][1]==1) 
         $arrData[$i][1]=_("女");
      if($arrData[$i][1]=="") 
         $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
    	$i++;
 	  }
}
 
//政治面貌
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
   }
}

//在职状态
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
   }
}

//籍贯
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
   }
	
}

//职称
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
   }
		
}

//员工类型
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
   }
		
}

//加入本单位时间
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
   		   $arrData[$i][1]=_("其他");
      $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	    $i++;
 	  }

}



$strXML = "<chart caption='"._("学历统计（人）")."' formatNumberScale='0'>";
foreach ($arrData as $arSubData)
   $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "' />";
$strXML .= "</chart>";

echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Column3D.swf", "", "$strXML", "myFirst", "600", "300", false, false);

?>
   </BODY>
</HTML>
