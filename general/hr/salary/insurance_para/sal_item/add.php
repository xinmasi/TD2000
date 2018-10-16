<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加薪酬项目");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

<?
$FORMULANAME=str_replace('%','<',$_POST["FORMULANAME"]);
$FORMULANAME=str_replace('`','>',$FORMULANAME);
$FORMULA=str_replace('%','<',$_POST["FORMULA"]);
$FORMULA=str_replace('`','>',$FORMULA);
//echo $FORMULANAME."</br>".$FORMULA;
//exit();
$ISPRINT=1;
if($ITEM_TYPE=="1")
{
	$ISREPORT=1;
	$ISCOMPUTER=0;
}
else
{
	$ISREPORT=0;
	if($ITEM_TYPE=="2")
	   $ISCOMPUTER=1;
  else
     $ISCOMPUTER=0;
}
$query="insert into SAL_ITEM(ITEM_ID,ITEM_NAME,ITEM_NUM,ISPRINT,ISCOMPUTER,FORMULA,FORMULANAME,ISREPORT) values ($ITEM_ID,'$ITEM_NAME','$ITEM_NUM','$ISPRINT','$ISCOMPUTER','$FORMULA','$FORMULANAME','$ISREPORT')";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
