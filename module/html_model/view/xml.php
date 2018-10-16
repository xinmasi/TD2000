<?
include_once("inc/auth.inc.php");

$where_str = "";
if($MODEL_TYPE != "")
{
   $where_str = " where MODEL_TYPE='$MODEL_TYPE' || MODEL_TYPE=''";
}

$OUTPUT = '';

$query = "select * from HTML_MODEL".$where_str." order by MODEL_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MODEL_NAME=$ROW["MODEL_NAME"];
   $CONTENT =$ROW["CONTENT"];
   $CONTENT = @gzuncompress($CONTENT);
   $CONTENT=str_replace(chr(10),"",$CONTENT);
   $CONTENT=str_replace(chr(13),"",$CONTENT);
   $CONTENT=str_replace("\\","\\\\",$CONTENT);  //zzj 2012-5-25 修改模板图片路径错误
   $OUTPUT .= "{";
   $OUTPUT .= "title:'".str_replace("'", "\\'", $MODEL_NAME)."',";
   $OUTPUT .= "image:null,";
   $OUTPUT .= "html:'".str_replace("'", "\\'", $CONTENT)."'";
   $OUTPUT .= "},";
}

ob_end_clean();
?>
CKEDITOR.addTemplates(
   'default',{
      imagesPath:null,
      templates:[<?=td_trim($OUTPUT)?>]
   }
);