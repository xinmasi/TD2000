<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
mysql_select_db("BUS", TD::conn());

$query="insert into CITY (CITY_ID,CITY_NAME) values('$CITY_ID','$CITY_NAME')";
exequery(TD::conn(),$query);

$TABLE=$CITY_ID."_LINE";
   
$query1="CREATE TABLE $TABLE (
`id` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`lineid` VARCHAR( 10 )  NULL ,
`PassBy` TEXT  NULL ,
`startTime` VARCHAR( 5 )  NULL ,
`endTime` VARCHAR( 5 )  NULL ,
`busType` VARCHAR( 12 )  NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE = MYISAM";

exequery(TD::conn(),$query1);

   Message(_("提示"),_("新建成功！"));

?>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="history.go(-2);"></center>
</body>
</html>
