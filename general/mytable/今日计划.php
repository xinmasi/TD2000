<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="97";
$MODULE_DESC=_("今日计划");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'work_plan';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'work_plan\',\''._("今日计划").'\',\'/general/work_plan/show/\');">'._("全部").'&nbsp;</a>';


$MODULE_BODY.= "<ul>";

$RANGE_STR="(TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_PERSON_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPATOR) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPINION_LEADER))";
$CUR_DATE=date("Y-m-d",time());
$DATE_STR="BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE='0000-00-00') ";

$COUNT=0;
//============================ 显示已发布工作计划 =======================================
$query = "SELECT PLAN_ID,NAME,BEGIN_DATE,END_DATE,TYPE,SUSPEND_FLAG,CREATOR from WORK_PLAN where PUBLISH='1' and SUSPEND_FLAG='1' and (".$RANGE_STR.") and ".$DATE_STR ." order by CREATE_DATE desc,TYPE desc limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $PLAN_ID=$ROW["PLAN_ID"];
   $NAME=$ROW["NAME"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $TYPE=$ROW["TYPE"];
   $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
   $CREATOR=$ROW["CREATOR"];
   
   $USER_NAME="";
   $query1 = "SELECT USER_NAME,DEPT_NAME from USER,DEPARTMENT where USER.USER_ID='$CREATOR' and USER.DEPT_ID=DEPARTMENT.DEPT_ID";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
   	  $USER_NAME=$ROW1["USER_NAME"];
   	  $DEPT_NAME=$ROW1["DEPT_NAME"];
   	}

   $query1 = "SELECT TYPE_NAME from PLAN_TYPE where TYPE_ID='$TYPE'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $TYPE_DESC=$ROW1["TYPE_NAME"]._("：");
   else
      $TYPE_DESC="";

   if($END_DATE=="0000-00-00")
      $END_DATE="";

   $SUBJECT_TITLE="";
   if(strlen($NAME) > 30)
   {
      $SUBJECT_TITLE=$NAME;
      $NAME=csubstr($NAME, 0, 30)."...";
   }
   $NAME=td_htmlspecialchars($NAME);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

   $MODULE_BODY.='<li>'.$TYPE_DESC.'<a href="javascript:plan_detail('.$PLAN_ID.');" title="'.$SUBJECT_TITLE.'">'.$NAME.'</a> ['.$USER_NAME.'('.$DEPT_NAME.')'.']('.$BEGIN_DATE.' - '.$END_DATE.')</li>';
}//while

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无工作计划")."</li>";

$MODULE_BODY.= "<ul>
<script>
function plan_detail(PLAN_ID)
{
 URL='/general/work_plan/show/plan_detail.php?PLAN_ID='+PLAN_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,'read_work_plan','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=120,left='+myleft+',resizable=yes');
}
</script>";

}
?>