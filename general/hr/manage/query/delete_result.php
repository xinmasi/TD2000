<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
ob_start();

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($condition_query!="")  //删除查询结果的用户档案
{
	 if($condition_query=="clear_dirty")
	 { 
		  //清除无用户名的废弃人事档案
 		  $query="select a.USER_ID from HR_STAFF_INFO a left join USER b on a.USER_ID=b.USER_ID where b.USER_ID is null";
 		  $cursor=exequery(TD::conn(),$query);
 		  $TOTAL=sprintf(_("共%d条"), mysql_num_rows($cursor));
 		  while($ROW=mysql_fetch_array($cursor))
      {
           $USER_ID=$ROW["USER_ID"];
 		      $query1="select PHOTO_NAME,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_INFO where USER_ID='$USER_ID'";
    	      $cursor1=exequery(TD::conn(),$query1);
           if($ROW1=mysql_fetch_array($cursor1))
           {
              $ATTACHMENT_ID=$ROW1["ATTACHMENT_ID"];
            	 $ATTACHMENT_NAME=$ROW1["ATTACHMENT_NAME"];
            	 $PHOTO_NAME=$ROW1["PHOTO_NAME"];
           }
           if($ATTACHMENT_NAME!="")
           { 
              delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
           }
           if($PHOTO_NAME!="")
           {
 	           $FILENAME=MYOA_ATTACH_PATH."hrms_pic/$PHOTO_NAME";
 	           if(file_exists($FILENAME))
              {
                 unlink($FILENAME);
              }
           }
           
           $query2="delete from HR_STAFF_INFO where USER_ID='$USER_ID'";
           exequery(TD::conn(),$query2);
           del_field_data("HR_STAFF_INFO",$USER_ID);
    	   }//end while
    }
    else
    {
	     $query=str_replace("\'","'",$condition_query);
	     $cursor= exequery(TD::conn(),$query); 
       while($ROW=mysql_fetch_array($cursor))
       {
           $USER_ID=$ROW["USER_ID"];
		
	         $query1="select PHOTO_NAME,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_INFO where USER_ID='$USER_ID'";
   	       $cursor1=exequery(TD::conn(),$query1);
           if($ROW1=mysql_fetch_array($cursor1))
           {
        	     $ATTACHMENT_ID=$ROW1["ATTACHMENT_ID"];
        	     $ATTACHMENT_NAME=$ROW1["ATTACHMENT_NAME"];
        	     $PHOTO_NAME=$ROW1["PHOTO_NAME"];
           }
           if($ATTACHMENT_NAME!="")
           { 
               delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
           }
           if($PHOTO_NAME!="")
           {
	            $FILENAME=MYOA_ATTACH_PATH."hrms_pic/$PHOTO_NAME";
	            if(file_exists($FILENAME))
              { 
                 unlink($FILENAME);
              }
          }
          $query2="delete from HR_STAFF_INFO where USER_ID='$USER_ID'";
          exequery(TD::conn(),$query2);
          del_field_data("HR_STAFF_INFO",$USER_ID);
   	}//end while
  }//end else
  Message(_("提示"),$TOTAL._("信息删除成功！"));
}

?>
<div align="center">
	<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">
</div>
</body>
</html>
