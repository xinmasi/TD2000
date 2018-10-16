<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除人事档案");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($DEPT_ID!="")  //删除部门的所有用户档案
	$query="select USER_ID from HR_STAFF_INFO where DEPT_ID='$DEPT_ID'";
else
	$query="select USER_ID,DEPT_ID from HR_STAFF_INFO where find_in_set(USER_ID,'$USER_ID')";	
	
$cursor= exequery(TD::conn(),$query); 
while($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $PHOTO_NAME=$ROW["PHOTO_NAME"];      
   
   if($PHOTO_NAME!="")
   {
      $FILENAME=MYOA_ATTACH_PATH."hrms_pic2/$PHOTO_NAME";
      if(file_exists($FILENAME))
         unlink($FILENAME);
   }
   $query2="delete from HR_STAFF_INFO where USER_ID='$USER_ID'";
   exequery(TD::conn(),$query2);
}

$COUNT = mysql_affected_rows();
if($COUNT > 0)
	 Message("", _("删除用户成功"));
else
	 Message(_("错误"), _("删除用户失败"));
if($FLAG==1)
   header("location: user_list.php?start=$start&DEPT_NAME=$DEPT_NAME&DEPT_ID=$DEPT_ID");
else
   header("location: search.php?start=$start");

?>
</body>
</html>