<?
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("饼图");
include_once("inc/header.inc.php");
?>
<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td>
  	<img src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" width="18" HEIGHT="18"><span class="big3"><?=_("人才档案统计结果")?></span>&nbsp;
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';" title="<?=_("返回")?>">
  </td>
</tr>
</table>
<br>
<center>
<?
	if($POSITION=="")
		 $query.=" where 1=1";
	else	
	   $query.=" where POSITION = $POSITION ";

	$CUR_DATE=date("Y-m-d");
  if($SUMFIELD==1){//学历
   $query1="select EMPLOYEE_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT from  HR_RECRUIT_POOL ".$query." GROUP BY SUMNAME";
 	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"STAFF_HIGHEST_SCHOOL");
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
   $i++;
 	}
}

if($SUMFIELD==2){//年龄
	 //按年龄统计，组织sql语句
 	$ARRAY_STATIS=explode(",",$AGE_RANGE);
 	$ARRAY_COUNT=sizeof($ARRAY_STATIS);
 	for ($i=0;$i<$ARRAY_COUNT;$i++)
  {
  	$AGERANG=explode("-",$ARRAY_STATIS[$i]);
    $WHEN_STR="";
    if($AGERANG[1]!="")
    {
     	$AGERANG[1]++;
     	if($WHEN_STR=="")
         $WHEN_STR.=" WHEN DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<= EMPLOYEE_BIRTH";
     	else
         $WHEN_STR.="  and DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[1] YEAR)<=EMPLOYEE_BIRTH";
    }
    if($AGERANG[0]!="")
    {
     	if($WHEN_STR=="")
         $WHEN_STR.=" WHEN EMPLOYEE_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
     	else
         $WHEN_STR.="  and EMPLOYEE_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $AGERANG[0]  YEAR) ";
    }

   $WHEN_STR=$WHEN_STR." THEN '$ARRAY_STATIS[$i]' ";
   $STRCASE=$STRCASE.$WHEN_STR;
  }

 	 $query1="select  (CASE ".$STRCASE."  END) as SUMNAME,
            COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT 
            from HR_RECRUIT_POOL".$query.$WHERE_STR." GROUP BY SUMNAME";

 	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	 $i++;
 	}
}

if($SUMFIELD==3){//性别
	
	 $query1="select  EMPLOYEE_SEX as SUMNAME,
          COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT 
          from HR_RECRUIT_POOL".$query." GROUP BY SUMNAME";
          
	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   if($arrData[$i][1]==0) $arrData[$i][1]=_("男");
   if($arrData[$i][1]==1) $arrData[$i][1]=_("女");
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	 $i++;
 	}
}

if($SUMFIELD==4){//所学专业
		
	 $query1="select EMPLOYEE_MAJOR as SUMNAME,COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT from  HR_RECRUIT_POOL  ".$query." GROUP BY SUMNAME";
 	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"POOL_EMPLOYEE_MAJOR");
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	 $i++;
 	}		
}

if($SUMFIELD==5){//籍贯
	
	 $query1="select EMPLOYEE_NATIVE_PLACE  as SUMNAME,COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT from  HR_RECRUIT_POOL  ".$query." GROUP BY SUMNAME";
 	 $cursor= exequery(TD::conn(),$query1);
   $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"AREA");
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	 $i++;
 	}	
}


if($SUMFIELD==6){//期望工作性质
		
	 $query1="select JOB_CATEGORY as SUMNAME,COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT from  HR_RECRUIT_POOL  ".$query." GROUP BY SUMNAME";
 	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $arrData[$i][1]=$ROW["SUMNAME"];
   $arrData[$i][1]=get_hrms_code_name($arrData[$i][1],"JOB_CATEGORY");
   if($arrData[$i][1]=="") $arrData[$i][1]=_("其他");
   $arrData[$i][2]=$ROW["TOTALCOUNT"];
 	 $i++;
 	}		
}
 if($SUMFIELD==1) $SUMFIELD=_("学历");
 if($SUMFIELD==2) $SUMFIELD=_("年龄");
 if($SUMFIELD==3) $SUMFIELD=_("性别");
 if($SUMFIELD==4) $SUMFIELD=_("专业");
 if($SUMFIELD==5) $SUMFIELD=_("籍贯");
 if($SUMFIELD==6) $SUMFIELD=_("期望工作性质");
$strXML = "<chart caption='".sprintf(_("按%s统计（人）"), $SUMFIELD)."'formatNumberScale='0'>";
if(!empty($arrData))
foreach ($arrData as $arSubData)
   $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "' />";
$strXML .= "</chart>";
if(!empty($arrData))
   echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Pie3D.swf", "", $strXML, "FactorySum", "600", "300", false, false);
else
   Message(_("提示"),_("无记录！"));
?>
</body>
</html>
