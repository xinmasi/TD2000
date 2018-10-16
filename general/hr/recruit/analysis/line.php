<?
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("列表");
include_once("inc/header.inc.php");
?>

<script language="JavaScript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td>
  	<img src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" width="18" HEIGHT="18"><span class="big3"><?=_("人才档案统计结果")?></span>&nbsp;
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';" title="<?=_("返回")?>">
  </td>
</tr>
</table>
<?
if($POSITION=="")
		 $query.=" where 1=1";
	else	
	   $query.=" where POSITION = '$POSITION' ";
$CUR_DATE=date("Y-m-d");
 if($SUMFIELD==1){//学历
 	 $query1="select EMPLOYEE_HIGHEST_SCHOOL as SUMNAME,COUNT(DISTINCT EXPERT_ID) as TOTALCOUNT from  HR_RECRUIT_POOL  ".$query." GROUP BY SUMNAME";
 	 $cursor= exequery(TD::conn(),$query1);
 	 $i=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $HRMS_COUNT++;
   $IDS="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and EMPLOYEE_HIGHEST_SCHOOL=".$ROW["SUMNAME"];
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
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
 	$BEGIN=substr($ARRAY_STATIS[0],0,strpos($ARRAY_STATIS[0],"-"));
 	$END=substr($ARRAY_STATIS[$ARRAY_COUNT-1],strpos($ARRAY_STATIS[$ARRAY_COUNT-1],"-")+1);
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
   $IDS="";
   $IDS_OTHER="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and (CASE ".$STRCASE."  END)='".$ROW["SUMNAME"]."'";
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
   
   
   $query3="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and !(DATE_SUB(CURRENT_DATE(),INTERVAL $END YEAR)<= EMPLOYEE_BIRTH and EMPLOYEE_BIRTH<=DATE_SUB(CURRENT_DATE(),INTERVAL $BEGIN YEAR))";
   $CUR3=exequery(TD::conn(),$query3);
   while($ROW3=mysql_fetch_array($CUR3))
   		$IDS_OTHER.=$ROW3["EXPERT_ID"].",";
   if(substr($IDS_OTHER,-1)==",")
   		$IDS_OTHER=substr($IDS_OTHER,0,-1);
   $arrData[$i][1]=$ROW["SUMNAME"];
   if($arrData[$i][1]=="")
   {
    $arrData[$i][1]=_("其他");
    $arrData[$i][3]="detail.php?IDS_STR=".$IDS_OTHER;
   }
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
   $IDS="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and EMPLOYEE_SEX=".$ROW["SUMNAME"];
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
   
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
   $IDS="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and EMPLOYEE_MAJOR=".$ROW["SUMNAME"];
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
   
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
   $IDS="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and EMPLOYEE_NATIVE_PLACE=".$ROW["SUMNAME"];
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
   
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
   
   $IDS="";
   $query2="SELECT EXPERT_ID FROM HR_RECRUIT_POOL ".$query." and JOB_CATEGORY=".$ROW["SUMNAME"];
   $CUR=exequery(TD::conn(),$query2);
   while($ROW1=mysql_fetch_array($CUR))
   		$IDS.=$ROW1["EXPERT_ID"].",";
   if(substr($IDS,-1)==",")
   		$IDS=substr($IDS,0,-1);
   $arrData[$i][3]="detail.php?IDS_STR=".$IDS;
   
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

$strXML = _("按").$SUMFIELD._("统计（人）：");
if(empty($arrData))
   Message("",_("无记录"));
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
      		<a href="javascript:;" onClick="window.open('<?=$arSubData[3]?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');">
      		<?=$arSubData[2]?></a>
        </td>
      </tr>
<?
   }
}
?>
      </TABLE>
      
</body>
</html>

