<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $USER_NAME=$ROW["USER_NAME"];

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

        $URL="wage_info.php?RECALL=1&USER_ID=$USER_ID&USER_NAME=$USER_NAME&OPERATION=$OPERATION";
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

//-- 保存 --
if($OPERATION==1)
{
   $query="insert into HR_SAL_DATA(USER_ID";

   for($I=1;$I<=50;$I++)
       $query.=",S".$I;
  
   $query.=",ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,
   UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P"; 
   $query.=") values ('$USER_ID',";

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
else
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

   $query.=" where USER_ID='$USER_ID'";
   
}
  exequery(TD::conn(),$query);
  Message(_("提示"),$USER_NAME._("的薪酬基数已成功设置"));
  
  header("Location:sal_data.php?USER_ID=$USER_ID&FLOW_ID=$FLOW_ID");
?>
</body>
</html>
