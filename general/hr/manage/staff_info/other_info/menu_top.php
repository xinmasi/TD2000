<?
include_once("inc/auth.inc.php");

//---------查看相关信息是否存在----------
$query = "SELECT * from  HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$CONTRACT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CONTRACT_COUNT++;
}
$query = "SELECT * from HR_STAFF_INCENTIVE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$INCENTIVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $INCENTIVE_COUNT++;
}
$query = "SELECT * from  HR_STAFF_LICENSE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$LICENSE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LICENSE_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EXPERIENCE_COUNT++;
}
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$W_EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $W_EXPERIENCE_COUNT++;
}
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$SKILLS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $SKILLS_COUNT++;
}
$query = "SELECT * from HR_STAFF_RELATIVES where STAFF_NAME ='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$RELATIVES_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $RELATIVES_COUNT++;
}
$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$TRANSFER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_COUNT++;
}
$query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$REINSTATEMENT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $REINSTATEMENT_COUNT++;
}
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$EVALUATION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EVALUATION_COUNT++;
}
$query = "SELECT * from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$CARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CARE_COUNT++;
}
$query = "SELECT * from HR_TRAINING_RECORD where STAFF_USER_ID='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
$CARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CARE_COUNT++;
}

   
$MENU_TOP=array();

//if($CONTRACT_COUNT!=0)
   $MENU_TOP[]=array("text" => _("合同"), "href" => "contract_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($INCENTIVE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("奖惩"), "href" => "incentive_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($LICENSE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("证照"), "href" => "license_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($EXPERIENCE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("学习"), "href" => "experience_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($W_EXPERIENCE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("工作"), "href" => "w_experience_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($SKILLS_COUNT!=0)
   $MENU_TOP[]=array("text" => _("技能"), "href" => "skills_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($RELATIVES_COUNT!=0)
   $MENU_TOP[]=array("text" => _("关系"), "href" => "relatives_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($TRANSFER_COUNT!=0)
   $MENU_TOP[]=array("text" => _("调动"), "href" => "transfer_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($LEAVE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("离职"), "href" => "leave_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($REINSTATEMENT_COUNT!=0)
   $MENU_TOP[]=array("text" => _("复职"), "href" => "reinstatement_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($EVALUATION_COUNT!=0)
   $MENU_TOP[]=array("text" => _("职称"), "href" => "evaluation_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
//if($CARE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("关怀"), "href" => "care_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
 
 //if($CARE_COUNT!=0)
   $MENU_TOP[]=array("text" => _("培训"), "href" => "record_list.php?USER_ID=$USER_ID", "target" => "menu_main", "title" => "", "img" => "");
  
   $SCRIPT.="<script language=\"JavaScript\">
window.onresize = function()
{
   var navScrollWidth;
   var navScroll = document.getElementById(\"navScroll\");
   navScrollWidth = navScroll.clientWidth == 0 ? 26 : navScroll.clientWidth;
   if(navScroll)
   {
      var panel = document.getElementById(\"navPanel\");
      var menu=document.getElementById(\"navMenu\");
   }
}

function show_tree2()
{
   var target1=parent.document.getElementById('other_info');
   if(target1.style.display=='block')
      target1.style.display='none';   
   var target2=parent.document.getElementById('back_ground');
   if(target2.style.display=='block')
      target2.style.display='none';  
}
</script>";

$MENU_RIGHT='<div style="float:left;padding-top:6px;"><a id="btn" href="javascript:show_tree2();" class="A1" style="text-decoration: none;">'._("关闭").'</a></div>';  
include_once("inc/menu_top.php");
?>
