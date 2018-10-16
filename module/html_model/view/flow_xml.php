<?
include_once("inc/auth.inc.php");

$where_str = "";
if($MODEL_TYPE != "")
{
	$where_str = " where MODEL_TYPE='$MODEL_TYPE'";
}

ob_end_clean();

header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"".MYOA_CHARSET."\"?>\n";
echo "<Templates>";

$query = "select * from HTML_MODEL".$where_str." order by MODEL_NO";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$MODEL_NAME=$ROW["MODEL_NAME"];
	$CONTENT =$ROW["CONTENT"];
	$CONTENT = @gzuncompress($CONTENT);
	$CONTENT=str_replace(chr(10),"",$CONTENT);
	$CONTENT=str_replace(chr(13),"",$CONTENT);

?>
	<Template title="<?=$MODEL_NAME?>">
    <Html>
      <![CDATA[
        <?=$CONTENT?>
      ]]>
    </Html>
  	</Template>
<?
}
echo "</Templates>";
?>