<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
while(list($DEPT_ID1, $DEPT) = each($DEPARTMENT_ARRAY))
{
   if($DEPT["DEPT_PARENT"]!=$PARENT_ID)
      continue;
   $DEPT_ID = $DEPT_ID1;
   $DEPT_NAME1=$DEPT["DEPT_NAME"];
   break;
}

$HTML_PAGE_TITLE = _("ÀëÖ°ÈËÔ±");
include_once("inc/header.inc.php");

?>
<style>
html,body{height:100%;}
.staff{position:relative;height:100%;}
.staff_l{position:absolute;top:0;left:0;width:200px;height:100%;}
.staff_r{margin-left:201px;height:100%;}
</style>
<div class="staff">
    <div class="staff_l"><iframe name="left_tree" src="left_tree2.php" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
    <div class="staff_r"><iframe name="staff_info" src="blank.php?DEPT_ID=<?=$DEPT_ID?>&DEPT_NAME=<?=urlencode($DEPT_NAME1)?>" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
</div>
</body>
</html>