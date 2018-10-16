<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if($fld_str=="")
{
   $query = "SELECT ITEM_ID from SAL_ITEM";
   $cursor= exequery(TD::conn(),$query);
   $FLOW_COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
     	$STYLE.="S".$ROW["ITEM_ID"].",";
   }
   $STYLE.="ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P,INSURANCE_DATE,BANK1,BANK_ACCOUNT1,BANK2,BANK_ACCOUNT2,";
   $STYLE.="MEMO";
}
else
{
	 //去掉多于的","	 
   $STYLE=substr($fld_str,0,-1);
}


$HTML_PAGE_TITLE = _("工资报表");
include_once("inc/header.inc.php");
?>


<style type="text/css">
.small {  font-size: 9pt;}
</style>


<body>

<?
 $query = "SELECT count(*) from SAL_ITEM";
 $ITEM_COUNT=0;
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];

 if($ITEM_COUNT==0)
 {
   Message(_("提示"),_("尚未定义工资项目"));
   Button_back();
   exit;
 }
?>

<table  bordercolor="#000000" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=2 bordercolor='#000000' class="small" align="center">

    <tr bgcolor="#D3E5FA">
      <td nowrap align="center"><b><?=_("部门")?></b></td>
      <td nowrap align="center"><b><?=_("姓名")?></b></td>
      <td nowrap align="center"><b><?=_("角色")?></b></td>
<?
 $STYLE_ARRAY=explode(",",$STYLE);
 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
 $COUNT=0;
 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 for($I=0;$I < $ARRAY_COUNT;$I++)
 {
 	    if(substr($STYLE_ARRAY[$I],0,1)=="S")
 	    {
      	$query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='".substr($STYLE_ARRAY[$I],1)."'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
           	$ITEM_NAME=$ROW["ITEM_NAME"];
            $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
        }
        $COUNT++; 
        echo "<td nowrap align='center'><b>$ITEM_NAME</b></td>";  	
      }
      else
      {
      	
      	if($STYLE_ARRAY[$I]=="ALL_BASE")
      	{  
      		$COUNT++;
      	   echo "<td nowrap align='center'><b>"._("保险基数")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_BASE")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("养老保险")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_U")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("单位养老")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_P")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("个人养老")."</b></td>";
        }
      	if($STYLE_ARRAY[$I]=="MEDICAL_BASE")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("医疗保险")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="MEDICAL_U")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("单位医疗")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="MEDICAL_P")
      	{  
      		$COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("个人医疗")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="FERTILITY_BASE")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("生育保险")."</b></td>";      	   
        }
      	if($STYLE_ARRAY[$I]=="FERTILITY_U")
      	{  
      		$COUNT++;  
      	   echo "<td nowrap align='center'><b>"._("单位生育")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_BASE")
      	{  
      		$COUNT++;  
      	   echo "<td nowrap align='center'><b>"._("失业保险")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_U")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("单位失业")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_P")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("个人失业")."</b></td>";
        }
      	if($STYLE_ARRAY[$I]=="INJURIES_BASE")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("工伤保险")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="INJURIES_U")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("单位工伤")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_BASE")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("住房公积金")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_U")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("单位住房")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_P")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("个人住房")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="INSURANCE_DATE")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("投保日期")."</b></td>"; 
      	}
      	if($STYLE_ARRAY[$I]=="BANK1")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("开户行1")."</b></td>"; 
      	}
      	if($STYLE_ARRAY[$I]=="BANK_ACCOUNT1")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("个人账户1")."</b></td>"; 
      	}
      	if($STYLE_ARRAY[$I]=="BANK2")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("开户行2")."</b></td>"; 
      	} 
      	if($STYLE_ARRAY[$I]=="BANK_ACCOUNT2")
      	{  
      		$COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("个人账户2")."</b></td>"; 
      	}	
      }
      if($STYLE_ARRAY[$I]=="MEMO")
      {
      	 $COUNT++;
      	 echo "<td nowrap align='center'><b>"._("备注")."</b></td>";
      }    
   }//end for
?>
    </tr>
<?
if($COPY_TO_ID!="")
{
   $COPY_TO_ID="'".str_replace(",","','",substr($COPY_TO_ID,0,-1))."'";
   $WHERE_STR.=" and USER.USER_ID in ($COPY_TO_ID)";
}
if($TOID!="" and $TOID!="ALL_DEPT")
{
   	 $TOID="'".str_replace(",","','",substr($TOID,0,-1))."'";
     $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($TOID)";
} 
if($PRIV_ID!="")
{
   $PRIV_ID="'".str_replace(",","','",substr($PRIV_ID,0,-1))."'";
   $WHERE_STR.=" and USER.USER_PRIV in ($PRIV_ID)";
} 
if($DEPT_FLAG!=1)
{
    $query = "SELECT * from USER,USER_PRIV,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV".$WHERE_STR." order by DEPT_NO,PRIV_NO,USER_NAME";
}
else
{
  	$query = "SELECT * from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and DEPT_ID=0";
}
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $USER_COUNT++;

    $USER_ID   = $ROW["USER_ID"];
    $USER_NAME = $ROW["USER_NAME"];
    $DEPT_ID   = $ROW["DEPT_ID"];
    $USER_PRIV = $ROW["USER_PRIV"];

    if($DEPT_ID==0)
	{
		$DEPT_NAME=_("离职人员/外部人员");
	}  
    else
    {
		$DEPT_ID = intval($DEPT_ID);	
        $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
		{
			$DEPT_NAME=$ROW["DEPT_NAME"];
		}
    }
    $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
	{
		$USER_PRIV=$ROW["PRIV_NAME"];
	}
         
?>
    <tr>
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$USER_PRIV?></td>
<?
$query2="select * from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor2= exequery(TD::conn(),$query2);
if(mysql_fetch_array($cursor2)=="")
{
	$query3="insert into HR_STAFF_INFO (CREATE_USER_ID,CREATE_DEPT_ID,USER_ID,DEPT_ID,STAFF_NO,WORK_NO,WORK_TYPE,STAFF_NAME,BEFORE_NAME,STAFF_E_NAME,STAFF_CARD_NO,STAFF_SEX,STAFF_BIRTH,STAFF_NATIVE_PLACE,STAFF_NATIVE_PLACE2,STAFF_DOMICILE_PLACE,STAFF_NATIONALITY,STAFF_MARITAL_STATUS,STAFF_POLITICAL_STATUS,JOIN_PARTY_TIME,STAFF_PHONE,STAFF_MOBILE,STAFF_LITTLE_SMART,STAFF_EMAIL,STAFF_MSN,STAFF_QQ,HOME_ADDRESS,OTHER_CONTACT,JOB_BEGINNING,WORK_AGE,STAFF_HEALTH,STAFF_HIGHEST_SCHOOL,STAFF_HIGHEST_DEGREE,GRADUATION_DATE,GRADUATION_SCHOOL,STAFF_MAJOR,COMPUTER_LEVEL,FOREIGN_LANGUAGE1,FOREIGN_LEVEL1,FOREIGN_LANGUAGE2,FOREIGN_LEVEL2,FOREIGN_LANGUAGE3,FOREIGN_LEVEL3,STAFF_SKILLS,STAFF_OCCUPATION,ADMINISTRATION_LEVEL,JOB_POSITION,PRESENT_POSITION,DATES_EMPLOYED,JOB_AGE,BEGIN_SALSRY_TIME,RECORD_DATE,WORK_STATUS,STAFF_CS,STAFF_CTR,REMARK,STAFF_COMPANY,PHOTO_NAME,ATTACHMENT_ID,ATTACHMENT_NAME,RESUME,LEAVE_TYPE,STAFF_TYPE,YES_OR_NOT,USERDEF1,USERDEF2,USERDEF3,USERDEF4,USERDEF5,CERTIFICATE,SURETY,INSURE,BODY_EXAMIM,ADD_TIME,LAST_UPDATE_TIME,WORK_LEVEL,WORK_JOB,IS_LUNAR) values ('admin','','$USER_ID','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')";
	exequery(TD::conn(),$query3);
}
else
{
   $query1="select * from SAL_DATA,HR_STAFF_INFO where SAL_DATA.USER_ID = HR_STAFF_INFO.USER_ID and FLOW_ID='$FLOW_ID' and HR_STAFF_INFO.USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      for($I=0;$I< $COUNT;$I++)
      {
          $STR=$STYLE_ARRAY[$I];
          if($STR!="MEMO" && $STR!="INSURANCE_DATE" && $STR!="BANK1" && $STR!="BANK_ACCOUNT1" && $STR!="BANK2" && $STR!="BANK_ACCOUNT2")
		  {
			  $$STR=format_money($ROW[$STR]);  
		  }            
          else if($STR=="INSURANCE_DATE")
		  {
			  $$STR=$ROW["INSURANCE_DATE"]; 
		  }  
          else if($STR=="BANK1")
		  {
			  $$STR=$ROW["BANK1"];
		  }
		  else if($STR=="BANK_ACCOUNT1")
		  {
			  $$STR=$ROW["BANK_ACCOUNT1"];  
		  }   
          else if($STR=="BANK2")
		  {
			 $$STR=$ROW["BANK2"]; 
		  }   
		  else if($STR=="MEMO")
		  {
			 $$STR=$ROW["MEMO"]; 
		  }  
         else
		 {
			$$STR=$ROW["BANK_ACCOUNT2"]; 
	     }      
       }
   }
   else
   {
       for($I=0; $I< $COUNT;$I++)
       {
           $STR  = $STYLE_ARRAY[$I];
           $$STR = "";
       }
   }
}
   for($I=0; $I< $COUNT; $I++)
   {
       $STR       = $STYLE_ARRAY[$I];
       $STR_COUNT = $STR."_COUNT";
       $$STR_COUNT+=$$STR;

       if($$STR=="")
	   {
		  $$STR="&nbsp;"; 
	   }
          
?>
      <td nowrap align="right"><?=$$STR?></td>
<?
   }
?>
    </tr>
<?
}

if($USER_COUNT==0)
{
?>
  <div align="center"><b><?=_("尚未定义用户")?></b></div><br>
<?
}
else
{
?>
  <tr bgcolor="#EEEEEE">
   <td nowrap align="center" colspan="3"><b><?=_("合计")?></b></td>
<?
   for($I=0;$I< $COUNT;$I++)
   {
      $STR       = "S".$ITEM_ID[$I];
      $STR_COUNT = $STR."_COUNT";
      //var_dump($$STR_COUNT);
      
      $DATA = format_money($$STR_COUNT);

      if($DATA=="")
	  {
		 $DATA="&nbsp;"; 
	  }         
?>

   <td nowrap align="right"><b><?=$DATA?></b></td>
   
<?
   }
?>
  </tr>
<?
 }
?>
</table>
</div>
</body>
</html>