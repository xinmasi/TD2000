<?
include_once("inc/auth.inc.php");
include_once ("inc/utility_all.php");
ob_end_clean();

if(isset($action) && $action=="vague")
{
	$project_id = td_htmlspecialchars($project_id);
	$sql ="SELECT b.APPROVE from office_products as a,office_depository as b WHERE find_in_set(a.OFFICE_PROTYPE,b.OFFICE_TYPE_ID) and a.PRO_ID = '$project_id'";
	$cursor= exequery(TD::conn(),$sql);
	if($ROW=mysql_fetch_array($cursor))
	{
		echo $ROW['APPROVE'];
	}
}
else if(isset($action) && $action=="vague_more")
{
	$stroid = td_trim(td_htmlspecialchars($stroid));
	$OFFICE_PROTYPE = "";
	$sql = "SELECT OFFICE_PROTYPE FROM office_products WHERE find_in_set(PRO_ID,'$stroid') group by OFFICE_PROTYPE";
	$cursor= exequery(TD::conn(),$sql);
	while($ROW=mysql_fetch_array($cursor))
	{
		$OFFICE_PROTYPE .= $ROW['OFFICE_PROTYPE'].",";
	}
	$OFFICE_PROTYPE = td_trim($OFFICE_PROTYPE);
	$counts = 0;
	if($OFFICE_PROTYPE!="")
	{
		$OFFICE_PROTYPE_ARRAY = explode(",",$OFFICE_PROTYPE);
		for($i=0;$i<count($OFFICE_PROTYPE_ARRAY);$i++)
		{
			$sql1 = "SELECT APPROVE FROM office_depository WHERE find_in_set('{$OFFICE_PROTYPE_ARRAY[$i]}',OFFICE_TYPE_ID) and APPROVE = 1";
			$cursor= exequery(TD::conn(),$sql1);
			if(mysql_affected_rows()>0)
			{
				$counts++;
			}
		}	
	}
	echo $counts>0?"1":"0";
}
else
{
	$OFFICE_DEPOSITORY = td_htmlspecialchars($OFFICE_DEPOSITORY);
	$query="select APPROVE from office_depository where OFFICE_TYPE_ID='$OFFICE_DEPOSITORY'";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		echo $ROW['APPROVE'];
	}
}


?>