<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
ob_start();


include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($condition_cascade!="")  
{
   $query=str_replace("\'","'",$condition_cascade);
   $cursor= exequery(TD::conn(),$query); 
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      
       //删除查询结果的用户档案
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
      
      //删除查询结果的合同管理记录
      $query_contract="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
      $cursor_contract=exequery(TD::conn(),$query_contract);
      if($ROW_CONTRACT=mysql_fetch_array($cursor_contract))
      {
   	     $ATTACHMENT_ID_CONTRACT=$ROW_CONTRACT["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_CONTRACT=$ROW_CONTRACT["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_CONTRACT!="")
      { 
          delete_attach($ATTACHMENT_ID_CONTRACT,$ATTACHMENT_NAME_CONTRACT);
      }
      $query_contract_delete="delete from HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_contract_delete);
      
      //删除查询结果的奖惩管理记录
      $query_incentive="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_INCENTIVE where STAFF_NAME='$USER_ID'";
      $cursor_incentive=exequery(TD::conn(),$query_incentive);
      if($ROW_INCENTIVE=mysql_fetch_array($cursor_incentive))
      {
   	     $ATTACHMENT_ID_INCENTIVE=$ROW_INCENTIVE["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_INCENTIVE=$ROW_INCENTIVE["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_INCENTIVE!="")
      { 
          delete_attach($ATTACHMENT_ID_INCENTIVE,$ATTACHMENT_NAME_INCENTIVE);
      }
      $query_incentive_delete="delete from HR_STAFF_INCENTIVE where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_incentive_delete); 
      //删除查询结果的证照管理记录
      $query_license="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LICENSE where STAFF_NAME='$USER_ID'";
      $cursor_license=exequery(TD::conn(),$query_license);
      if($ROW_LICENSE=mysql_fetch_array($cursor_license))
      {
   	     $ATTACHMENT_ID_LICENSE=$ROW_LICENSE["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_LICENSE=$ROW_LICENSE["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_LICENSE!="")
      { 
          delete_attach($ATTACHMENT_ID_LICENSE,$ATTACHMENT_NAME_LICENSE);
      }
      $query_license_delete="delete from HR_STAFF_LICENSE where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_license_delete); 
      //删除查询结果的学习经历记录
      $query_learn="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$USER_ID'";
      $cursor_learn=exequery(TD::conn(),$query_learn);
      if($ROW_LEARN=mysql_fetch_array($cursor_learn))
      {
   	     $ATTACHMENT_ID_LEARN=$ROW_LEARN["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_LEARN=$ROW_LEARN["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_LEARN!="")
      { 
          delete_attach($ATTACHMENT_ID_LEARN,$ATTACHMENT_NAME_LEARN);
      }
      $query_learn_delete="delete from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_learn_delete); 
      //删除查询结果的工作经历记录
      $query_work="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID'";
      $cursor_work=exequery(TD::conn(),$query_work);
      if($ROW_WORK=mysql_fetch_array($cursor_work))
      {
   	     $ATTACHMENT_ID_WORK=$ROW_WORK["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_WORK=$ROW_WORK["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_WORK!="")
      { 
          delete_attach($ATTACHMENT_ID_WORK,$ATTACHMENT_NAME_WORK);
      }
      $query_work_delete="delete from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_work_delete);
      //删除查询结果的劳动技能记录
      $query_skill="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LABOR_SKILLS where STAFF_NAME='$USER_ID'";
      $cursor_skill=exequery(TD::conn(),$query_skill);
      if($ROW_SKILL=mysql_fetch_array($cursor_skill))
      {
   	     $ATTACHMENT_ID_SKILL=$ROW_SKILL["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_SKILL=$ROW_SKILL["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_SKILL!="")
      { 
          delete_attach($ATTACHMENT_ID_SKILL,$ATTACHMENT_NAME_SKILL);
      }
      $query_skill_delete="delete from HR_STAFF_LABOR_SKILLS where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_skill_delete);
      //删除查询结果的社会关系记录
      $query_relatives="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_RELATIVES where STAFF_NAME='$USER_ID'";
      $cursor_relatives=exequery(TD::conn(),$query_relatives);
      if($ROW_RELATIVES=mysql_fetch_array($cursor_relatives))
      {
   	     $ATTACHMENT_ID_RELATIVES=$ROW_RELATIVES["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_RELATIVES=$ROW_RELATIVES["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_RELATIVES!="")
      { 
          delete_attach($ATTACHMENT_ID_RELATIVES,$ATTACHMENT_NAME_RELATIVES);
      }
      $query_relatives_delete="delete from HR_STAFF_RELATIVES where STAFF_NAME='$USER_ID'";
      exequery(TD::conn(),$query_relatives_delete);
      //删除查询结果的人事调动记录
      $query_transfer="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_TRANSFER where TRANSFER_PERSON='$USER_ID'";
      $cursor_transfer=exequery(TD::conn(),$query_transfer);
      if($ROW_TRANSFER=mysql_fetch_array($cursor_transfer))
      {
   	     $ATTACHMENT_ID_TRANSFER=$ROW_TRANSFER["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_TRANSFER=$ROW_TRANSFER["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_TRANSFER!="")
      { 
          delete_attach($ATTACHMENT_ID_TRANSFER,$ATTACHMENT_NAME_TRANSFER);
      }
      $query_relatives_delete="delete from HR_STAFF_TRANSFER where TRANSFER_PERSON='$USER_ID'";
      exequery(TD::conn(),$query_relatives_delete);
      //删除查询结果的离职管理记录
      $query_leave="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID'";
      $cursor_leave=exequery(TD::conn(),$query_leave);
      if($ROW_LEAVE=mysql_fetch_array($cursor_leave))
      {
   	     $ATTACHMENT_ID_LEAVE=$ROW_LEAVE["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_LEAVE=$ROW_LEAVE["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_LEAVE!="")
      { 
          delete_attach($ATTACHMENT_ID_LEAVE,$ATTACHMENT_NAME_LEAVE);
      }
      $query_leave_delete="delete from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID'";
      exequery(TD::conn(),$query_leave_delete);
      //删除查询结果的复职管理记录
      $query_reinstatement="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID'";
      $cursor_reinstatement=exequery(TD::conn(),$query_reinstatement);
      if($ROW_REINSTATEMENT=mysql_fetch_array($cursor_reinstatement))
      {
   	     $ATTACHMENT_ID_REINSTATEMENT=$ROW_REINSTATEMENT["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_REINSTATEMENT=$ROW_REINSTATEMENT["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_REINSTATEMENT!="")
      { 
          delete_attach($ATTACHMENT_ID_REINSTATEMENT,$ATTACHMENT_NAME_REINSTATEMENT);
      }
      $query_reinstatement_delete="delete from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID'";
      exequery(TD::conn(),$query_reinstatement_delete);
      //删除查询结果的职称评定记录
      $query_evaluation_delete="delete from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='$USER_ID'";
      exequery(TD::conn(),$query_evaluation_delete);      
      //删除查询结果的员工关怀记录
      $query_care="select ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID'";
      $cursor_care=exequery(TD::conn(),$query_care);
      if($ROW_CARE=mysql_fetch_array($cursor_care))
      {
   	     $ATTACHMENT_ID_CARE=$ROW_CARE["ATTACHMENT_ID"];
   	     $ATTACHMENT_NAME_CARE=$ROW_CARE["ATTACHMENT_NAME"];
      }
      if($ATTACHMENT_NAME_CARE!="")
      { 
          delete_attach($ATTACHMENT_ID_CARE,$ATTACHMENT_NAME_CARE);
      }
      $query_care_delete="delete from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID'";
      exequery(TD::conn(),$query_care_delete);
   }//end while
   Message(_("提示"),$TOTAL._("信息删除成功！"));
}

?>
<div align="center">
	<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">
</div>
</body>
</html>
