<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("选择模版");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js"></script>
<script Language="JavaScript">
var parent_window =window.opener;

function click_model(ID,DOC_TYPE)
{
  parent_window.TANGER_OCX_AddDocHeader(ID,DOC_TYPE);
  <? 
  if($SEC_OC_MARK=="forbid_save")
  {
   ?>
     parent_window.TANGER_OCX_SetMarkModify(false);
  <?
  }   
  ?>
  window.close();
}
</script>


<body class="bodycolor">

<?
if(isset($DOT_TPL_STR)){//需要显示部分模版
	$DOT_TPL_STR = rtrim($DOT_TPL_STR,',');
	$query_str = "and DOC_ID in($DOT_TPL_STR)";
}
$COUNT = 0;
$query="select * from WORD_MODEL where 1=1 ".$query_str." order by CREATE_TIME desc";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$DOC_ID=$ROW["DOC_ID"];
	$CREATE_TIME=$ROW["CREATE_TIME"];
	$PRIV_STR=$ROW["PRIV_STR"];
	$USER_ID=$ROW["USER_ID"];
	$MODEL_NAME=$ROW["MODEL_NAME"];
	$TYPE=$ROW["TYPE"];

  if($PRIV_STR!="")
  {
    $PRIV_ARRAY=explode("|",$PRIV_STR);
    $PRIV_USER=$PRIV_ARRAY[0];
    $PRIV_DEPT=$PRIV_ARRAY[1];
    $PRIV_ROLE=$PRIV_ARRAY[2]; 
    
    if(!($PRIV_DEPT=="ALL_DEPT" || find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) || find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"])))
       continue;
  }
 
  $COUNT++;
  
  if($COUNT==1)
  {
?>
<table class="TableBlock" width="100%" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<tr class="TableHeader">
  <td align="center"><b><?=_("选择模版")?></b></td>
</tr>

<?
   }
?>

<tr class="TableData">
  <td class="menulines" align="center" onclick=javascript:click_model('<?=$DOC_ID?>','<?=$TYPE?>') style="cursor:hand">
  	<?=$MODEL_NAME?>
  </td>
</tr>
<?
}

if($COUNT==0)
   Message("",_("没有定义模版"),"blank");
else
	 echo "</table>";
?>

</body>
</html>
