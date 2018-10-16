<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//-- 合法性检验 --
for($I=1;$I<=50;$I++)
{
   $STR="S".$I;
   $STR1="S".$I."_NAME";

   if($$STR!="")
      if(!is_number($$STR) && !is_money($$STR) )
      {
        Message(_("错误"),_("输入的 ").$$STR1._(" 金额格式不对，应形如 123 或 123.45"));

        $URL="set_all.php?USER_ID=$USER_ID&USER_NAME=$USER_NAME";
        for($I=1;$I<=50;$I++)
        {
          $STR="S".$I;
          $URL.="&".$STR."=".$$STR;
        }
?>
        <br>
        <div align="center">
           <input type="button" value="<?=_("返回")?>" class="BigButton" name="button" onClick="location='<?=$URL?>'">
        </div>
<?
        exit;
      }
}
//处理选择范围
if($TO_ID!="")
{
	if($TO_ID=="ALL_DEPT")
	   $query1 = "select USER_ID from USER";
	else
	   $query1 = "select USER_ID from USER where find_in_set(DEPT_ID,'$TO_ID')";
	$cursor1=exequery(TD::conn(),$query1);
  while($ROW=mysql_fetch_array($cursor1))
      $USER_ID_STR.=$ROW["USER_ID"].",";
}
if($COPY_TO_ID!="")
{
  $query1 = "select USER_ID from USER where find_in_set(USER_ID,'$COPY_TO_ID')";
	$cursor1=exequery(TD::conn(),$query1);  
  while($ROW=mysql_fetch_array($cursor1))
  {  
  	 if(!find_id($USER_ID_STR,$ROW["USER_ID"]))
        $USER_ID_STR.=$ROW["USER_ID"].",";
  }

}
if($PRIV_ID!="")
{
	$query1 = "select USER_ID from USER where find_in_set(USER_PRIV,'$PRIV_ID')";
  $cursor1=exequery(TD::conn(),$query1);
  while($ROW=mysql_fetch_array($cursor1))
  {  
  	 if(!find_id($USER_ID_STR,$ROW["USER_ID"]))
        $USER_ID_STR.=$ROW["USER_ID"].",";
  }
        
}

//-- 保存 --
$USER_ID_ARRAY= explode(",",$USER_ID_STR);
$USER_ID_COUNT = COUNT($USER_ID_ARRAY);
if($USER_ID_ARRAY[$USER_ID_COUNT-1]=="")$USER_ID_COUNT--;	
   for($J=0;$J < $USER_ID_COUNT;$J++)
   {
     $query="select * from HR_SAL_DATA where USER_ID='$USER_ID_ARRAY[$J]'";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
     {      
       $query="update HR_SAL_DATA set ";
       for($I=1;$I<=50;$I++)
       {
         $STR="S".$I;
         if($$STR=="")
            $$STR="0";
         $query.=$STR."='".$$STR."',";
    
       }
       $query.= "ALL_BASE='".$ALL_BASE."',";     
       $query.= "PENSION_BASE='".$PENSION_BASE."',";
       $query.= "PENSION_U='".$PENSION_U."',"; 
       $query.= "PENSION_P='".$PENSION_P."',";     
       $query.= "MEDICAL_BASE='".$MEDICAL_BASE."',";     
       $query.= "MEDICAL_U='".$MEDICAL_U."',";
       $query.= "MEDICAL_P='".$MEDICAL_P."',";
       $query.= "FERTILITY_BASE='".$FERTILITY_BASE."',";
       $query.= "FERTILITY_U='".$FERTILITY_U."',";
       $query.= "UNEMPLOYMENT_BASE='".$UNEMPLOYMENT_BASE."',";
       $query.= "UNEMPLOYMENT_U='".$UNEMPLOYMENT_U."',";
       $query.= "UNEMPLOYMENT_P='".$UNEMPLOYMENT_P."',";     
       $query.= "INJURIES_BASE='".$INJURIES_BASE."',";     
       $query.= "INJURIES_U='".$INJURIES_U."',";
       $query.= "HOUSING_BASE='".$HOUSING_BASE."',";    
       $query.= "HOUSING_U='".$HOUSING_U."',";     
       $query.= "HOUSING_P='".$HOUSING_P."'";       
       $query.=" where USER_ID='".$USER_ID_ARRAY[$J]."'";
     }
     else
     {
       $query="insert into HR_SAL_DATA(USER_ID";
       for($I=1;$I<=50;$I++)
           $query.=",S".$I;
      
       $query.=",ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,
       UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P"; 
       $query.=") values ('".$USER_ID_ARRAY[$J]."',";
    
       for($I=1;$I<=50;$I++)
       {
         $STR="S".$I;
         if($$STR=="")
            $$STR="0";
    
         $query.="'".$$STR."',";       
       }
       $query.= "'".$ALL_BASE."',";    
       $query.= "'".$PENSION_BASE."',";
       $query.= "'".$PENSION_U."',";
       $query.= "'".$PENSION_P."',";  
       $query.= "'".$MEDICAL_BASE."',";  
       $query.= "'".$MEDICAL_U."',";  
       $query.= "'".$MEDICAL_P."',";  
       $query.= "'".$FERTILITY_BASE."',";  
       $query.= "'".$FERTILITY_U."',";  
       $query.= "'".$UNEMPLOYMENT_BASE."',";  
       $query.= "'".$UNEMPLOYMENT_U."',";  
       $query.= "'".$UNEMPLOYMENT_P."',";  
       $query.= "'".$INJURIES_BASE."',";  
       $query.= "'".$INJURIES_U."',";  
       $query.= "'".$HOUSING_BASE."',";  
       $query.= "'".$HOUSING_U."',";   
       $query.= "'".$HOUSING_P."'";  
       $query.=")";
     }
     exequery(TD::conn(),$query);     
  }
Message(_("提示"),_("批量设置已成功"));
Button_Back();
?>
</body>
</html>
