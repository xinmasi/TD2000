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
	
$query="select * from ADDRESS_GROUP where USER_ID='' and GROUP_ID!='$GROUP_ID'";
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

$query="update ADDRESS_GROUP set GROUP_NAME='$GROUP_NAME',PRIV_DEPT='$TO_ID',PRIV_ROLE='$PRIV_ID',PRIV_USER='$COPY_TO_ID',ORDER_NO='$ORDER_NO' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

?>

<script>
location="index.php?GROUP_ID=<?=$GROUP_ID?>";
</script>

</body>
</html>
