<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("计划任务清单");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("计划任务清单")?> </span>
  </td>
 </tr>
</table>	
<?
$query = "SELECT * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $CREATOR=$ROW["CREATOR"];  
  $MANAGER=$ROW["MANAGER"]; 
  $CREATOR=$ROW["CREATOR"];  
  $MANAGER=$ROW["MANAGER"];   
  $CREATOR=$ROW["CREATOR"];  
  $MANAGER=$ROW["MANAGER"];   
  $CREATOR=$ROW["CREATOR"];  
  $MANAGER=$ROW["MANAGER"];    
}
?>

	
</body>
</html>	