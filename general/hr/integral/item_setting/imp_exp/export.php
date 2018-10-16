<?
include_once("inc/auth.inc.php");
ob_end_clean();

$FILE_CONTENT="";
$TABLE_NAME_STR="integral_item";
$TABLE_NAME_ARRAY= explode(",",$TABLE_NAME_STR);
$ARRAY_COUNT= sizeof($TABLE_NAME_ARRAY);
for($J=0;$J< $ARRAY_COUNT;$J++)
{
   $TABLE_NAME=$TABLE_NAME_ARRAY[$J];
   if($TABLE_NAME=="")
      continue;
   
   $FILE_CONTENT.="DROP TABLE IF EXISTS $TABLE_NAME;\n";
   //---------------- 获得CREATE语句 -----------------------
   $query = "SHOW CREATE TABLE $TABLE_NAME";
   $cursor= exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_row($cursor))
      $CREATE_STR=$ROW[1];
   $FILE_CONTENT.= strtoupper($CREATE_STR).";\n\n";
   
   //---------------- 获得INSERT语句 -----------------------
   $query = "SELECT * FROM $TABLE_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW = mysql_fetch_row($cursor))
   {
       $COMMA = "";
       $INSERT_STR = "INSERT INTO $TABLE_NAME VALUES(";
       $FIELD_NUM= mysql_num_fields($cursor);
       for($I = 0; $I < $FIELD_NUM; $I++) {
          $INSERT_STR .= $COMMA."'". addslashes($ROW[$I])."'";
          $COMMA = ",";
       }
       $INSERT_STR .= ");\n";
       $FILE_CONTENT.=$INSERT_STR;
   }
   $FILE_CONTENT.="\n\n\n";
}

$EXPORT_DATE=date("Y-m-d",time());

Header("Cache-control: private");
Header("Content-Type: application/octetstream");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ". strlen($FILE_CONTENT));
header('Content-Disposition: attachment; '.get_attachment_filename('CODE_'.$EXPORT_DATE.'.sql'));

for($POS=0;$POS<= strlen($FILE_CONTENT);$POS+=2000)
    echo substr($FILE_CONTENT,$POS,2000);
?>