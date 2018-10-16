<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("fun_compute.func.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//-- 合法性检验 --
for($I=1;$I<=$ITEM_COUNT;$I++)
{
  $STR="S".$I;
  $STR1="S".$I."_NAME";

  if($$STR!="")
  {
    if(!is_number($$STR) && !is_money($$STR) )
    {
       Message(_("错误"),_("输入的金额格式不对，应形如 123 或 123.45"));

       $URL="sal_data.php?RECALL=1&FLOW_ID=$FLOW_ID&USER_ID=$USER_ID&USER_NAME=$USER_NAME&OPERATION=$OPERATION";
       for($I=1;$I<=$ITEM_COUNT;$I++)
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
}

//-- 保存 --
if($INSURANCE_OTHER=="on")
  $INSURANCE_OTHER=1;
else
  $INSURANCE_OTHER=0;

$query3 = "SELECT ITEM_ID from sal_item where ITEM_NAME='税前工资';";
$cursor3= exequery(TD::conn(),$query3);
if($ROW3=mysql_fetch_array($cursor3))
{
    $SAL_DATA_NUMBER="S".$ROW3["ITEM_ID"];
}    
if($OPERATION==1)
{
  if($INSURANCE_OTHER==1)
  {
    $query="insert into SAL_DATA(FLOW_ID,USER_ID,MEMO,IS_FINA_INPUT";
    for($I=1;$I<=$ITEM_COUNT;$I++)
        $query.=",S".$I;
        
    $query.=",ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P,INSURANCE_OTHER,INSURANCE_DATE,REPORT"; 
    $query.=") values ('$FLOW_ID','$USER_ID','$MEMO','1',";
 
    for($I=1;$I<=$ITEM_COUNT;$I++)
    {
      $STR="S".$I;
      if($$STR=="")
         $$STR="0";
 
      $query.="'".$$STR."'";
 
      if($I!=$ITEM_COUNT)
         $query.=",";
    }  
    $query.= ",'".$ALL_BASE."',";
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
    $query.= "'".$HOUSING_P."',";
    $query.= "'".$INSURANCE_OTHER."',";
    $query.= "'".$INSURANCE_DATE."',";  
    $query.= "'".$$SAL_DATA_NUMBER."'";
    $query.=")";
 }
 else
 {
   $query="insert into SAL_DATA(FLOW_ID,USER_ID,INSURANCE_OTHER,MEMO,IS_FINA_INPUT";
   for($I=1;$I<=$ITEM_COUNT;$I++)
       $query.=",S".$I;
   $query.=",REPORT) values ('$FLOW_ID','$USER_ID','$INSURANCE_OTHER','$MEMO','1',";

   for($I=1;$I<=$ITEM_COUNT;$I++)
   {
     $STR="S".$I;
     if($$STR=="")
        $$STR="0";

     $query.="'".$$STR."'";

     if($I!=$ITEM_COUNT)
        $query.=",";
   }  
   $query.=",'".$$SAL_DATA_NUMBER."')";
 }
}
else
{
 if($INSURANCE_OTHER==1)
 {
   $query="update SAL_DATA set ";
   for($I=1;$I<=$ITEM_COUNT;$I++)
   {
      $STR="S".$I;
      if($$STR=="")
         $$STR="0";

      $query.=$STR."='".$$STR."'";

      if($I!=$ITEM_COUNT)
         $query.=",";
   }
   if($ITEM_COUNT>0) 
      $query.=",MEMO='$MEMO'";   
      $query.= ",PENSION_BASE='".$PENSION_BASE."',";
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
      $query.= "HOUSING_P='".$HOUSING_P."',";
      $query.= "INSURANCE_OTHER='".$INSURANCE_OTHER."',";
      $query.= "IS_FINA_INPUT='1',";
      $query.= "INSURANCE_DATE='".$INSURANCE_DATE."',";
      $query.= "REPORT='".$$SAL_DATA_NUMBER."'";
      $query.=" where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
 }
 else
 {
  $query="update SAL_DATA set ";
   for($I=1;$I<=$ITEM_COUNT;$I++)
   {
      $STR="S".$I;
      if($$STR=="")
         $$STR="0";

      $query.=$STR."='".$$STR."'";

      if($I!=$ITEM_COUNT)
         $query.=",";
   }
   if($ITEM_COUNT>0) 
      $query.=",MEMO='$MEMO'";
      $query.= ",ALL_BASE='0.00',";
      $query.= "PENSION_BASE='0.00',";
      $query.= "PENSION_U='0.00',"; 
      $query.= "PENSION_P='0.00',";     
      $query.= "MEDICAL_BASE='0.00',";     
      $query.= "MEDICAL_U='0.00',";
      $query.= "MEDICAL_P='0.00',";
      $query.= "FERTILITY_BASE='0.00',";
      $query.= "FERTILITY_U='0.00',";
      $query.= "UNEMPLOYMENT_BASE='0.00',";
      $query.= "UNEMPLOYMENT_U='0.00',";
      $query.= "UNEMPLOYMENT_P='0.00',";     
      $query.= "INJURIES_BASE='0.00',";     
      $query.= "INJURIES_U='0.00',";
      $query.= "HOUSING_BASE='0.00',";    
      $query.= "HOUSING_U='0.00',";     
      $query.= "HOUSING_P='0.00',";
      $query.= "INSURANCE_OTHER='".$INSURANCE_OTHER."',";
      $query.= "IS_FINA_INPUT='1',";      
      $query.= "INSURANCE_DATE='0000-00-00',";
      $query.= "REPORT='".$$SAL_DATA_NUMBER."'";
   $query.=" where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
 }
}
exequery(TD::conn(),$query);
Message(_("提示"),sprintf(_("员工 %s 的工资数据已上报%s请继续选择其他员工"), $USER_NAME, "<br><br>"));
//comput_allitem($FLOW_ID,$USER_ID);
?>
<br>
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='sal_data.php?UID=<?=$UID?>&USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DEPT_ID=<?=$DEPT_ID?>'">&nbsp;
</div>


</body>
</html>
