<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($GROUP_NAME==_("默认"))
{
	Message("",_("该组名已经存在！"));
	Button_Back();
	exit;
}

$query="select * from ADDRESS_GROUP where USER_ID=''";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$GROUP_NAME1=$ROW["GROUP_NAME"];

	if($GROUP_NAME1==$GROUP_NAME)
	{
	   Message("",_("该组名已经存在！"));
	   Button_Back();
	   exit;
	}
}

$query="insert into ADDRESS_GROUP (GROUP_NAME,PRIV_DEPT,PRIV_ROLE,PRIV_USER,ORDER_NO) values ('$GROUP_NAME','$TO_ID','$PRIV_ID','$COPY_TO_ID','$ORDER_NO')";
exequery(TD::conn(),$query);

?>

<script>
location="index.php?GROUP_ID=<?=$GROUP_ID?>";
</script>


</body>
</html>
