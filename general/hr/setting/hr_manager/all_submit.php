<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("人力资源管理员批量设置");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$COPY_TO_ID = str_replace("'","",$COPY_TO_ID);
$TO_ID      = str_replace("'","",$TO_ID);
$TO_ID_HR   = str_replace("'","",$TO_ID_HR);


if($OPERATION==0)
{
   if($TO_ID=="ALL_DEPT")
   	  $query="select DEPT_HR_MANAGER,DEPT_ID,DEPT_HR_SPECIALIST from HR_MANAGER";
   else
      $query="select DEPT_HR_MANAGER,DEPT_ID,DEPT_HR_SPECIALIST from HR_MANAGER where find_in_set(DEPT_ID,'$TO_ID')";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
	   $added_uid = check_id($ROW['DEPT_HR_MANAGER'],$COPY_TO_ID,false);
	   $added_hr  = check_id($ROW['DEPT_HR_SPECIALIST'],$TO_ID_HR,false);
	   
	   $query="update HR_MANAGER set DEPT_HR_MANAGER= CONCAT(DEPT_HR_MANAGER,'$added_uid'),DEPT_HR_SPECIALIST= CONCAT(DEPT_HR_SPECIALIST,'$added_hr') where DEPT_ID='{$ROW['DEPT_ID']}'";
	   exequery(TD::conn(),$query);
   }	
}else
{
   if($TO_ID=="ALL_DEPT")
   	  $query="select DEPT_HR_MANAGER,DEPT_ID,DEPT_HR_SPECIALIST from HR_MANAGER";
   else
      $query="select DEPT_HR_MANAGER,DEPT_ID,DEPT_HR_SPECIALIST from HR_MANAGER where find_in_set(DEPT_ID,'$TO_ID')";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
	   $out_add = check_id($COPY_TO_ID,$ROW['DEPT_HR_MANAGER'],false);
	   $out_hr  = check_id($TO_ID_HR,$ROW['DEPT_HR_SPECIALIST'],false);
	   
	   $query="update HR_MANAGER set DEPT_HR_MANAGER='$out_add',DEPT_HR_SPECIALIST='$out_hr' where DEPT_ID='{$ROW['DEPT_ID']}'";
	   exequery(TD::conn(),$query);
   }	
}
header("location: index1.php?connstatus=1");
?>
</body>
</html>
