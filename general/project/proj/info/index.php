<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目基本信息");
include_once("inc/header.inc.php");

include_once("../proj_priv.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/sys_code_field.php");

$query = "select * from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
	$PROJ_NUM = $ROW["PROJ_NUM"];
	$PROJ_NAME = $ROW["PROJ_NAME"];
	$COST_TYPE = $ROW["COST_TYPE"];
	$PROJ_TYPE = $ROW["PROJ_TYPE"];
	$PROJ_DEPT = $ROW["PROJ_DEPT"];
	$PROJ_START_TIME = $ROW["PROJ_START_TIME"];
	$PROJ_END_TIME = $ROW["PROJ_END_TIME"];
	$PROJ_DESCRIPTION = $ROW["PROJ_DESCRIPTION"];
	$PROJ_OWNER = $ROW["PROJ_OWNER"];
	$PROJ_MANAGER = $ROW["PROJ_MANAGER"];
   $PROJ_USER = $ROW["PROJ_USER"];
   $PROJ_PRIV = $ROW["PROJ_PRIV"];
   $APPROVE_LOG=$ROW["APPROVE_LOG"];
   $APPROVE_LOG_ARR=explode("|*|",$APPROVE_LOG);
   $PROJ_USER_ARRAY = explode("|",$PROJ_USER);
   $PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);

   
   $COST_MONEY=$ROW["COST_MONEY"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
	$COST_MONEY=explode(",",$COST_MONEY);
}
if($PROJ_END_TIME && $PROJ_START_TIME)
  $DIFF_DAY = floor((strtotime($ROW["PROJ_END_TIME"]) - strtotime($ROW["PROJ_START_TIME"]))/86400)+1;

$query = "select USER_NAME from USER WHERE USER_ID='$PROJ_OWNER'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
   $PROJ_OWNER_NAME = $ROW["USER_NAME"];

$query = "select USER_NAME from USER WHERE USER_ID='$PROJ_MANAGER'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
   $PROJ_MANAGER_NAME = $ROW["USER_NAME"];

if($PROJ_DEPT)
{
	if($PROJ_DEPT=="ALL_DEPT")
	   $PROJ_DEPT_NAME=_("全体部门");
	else
	{
  	$query = "select DEPT_NAME from DEPARTMENT WHERE FIND_IN_SET(DEPT_ID,'$PROJ_DEPT')";
    $cursor = exequery(TD::conn(), $query);
    while($ROW = mysql_fetch_array($cursor))
       $PROJ_DEPT_NAME .= $ROW["DEPT_NAME"].",";
  }
} 

  $PROJ_USER_ARRAY = explode("|",$PROJ_USER);
  $PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);

?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function get_define_type(CODE_ID,PROJ_ID)
{
  _get('/inc/sys_code_field_get.php',"CODE_ID="+CODE_ID+"&PROJ_ID="+PROJ_ID,callback);
}

function callback(req){
   if(req.status==200)
   {
      if(req.responseText)
      {
        	document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = req.responseText;
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = '';
      }else{
         document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = '';
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = 'none';   
      }
   }   
}   
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"/>
	<span class="big3"> <?=_("项目基本信息")?></span>
	<td></tr>
</table>
<table class="TableList" width="100%" align="left" topmargin="5">
	<tr>
  	<td nowrap class="TableContent"><?=_("项目编号：")?></td>
  	<td class="TableData"><?=$PROJ_NUM?></td>
  </tr>
	<tr>
  	<td nowrap class="TableContent" width=100><?=_("项目名称：")?></td>
  	<td class="TableData" colspan=3> <?=$PROJ_NAME?></td>  	  	
  </tr>
	<tr>
  	<td nowrap class="TableContent" width=100><?=_("创建者：")?></td>
  	<td class="TableData" colspan=3> <?=$PROJ_OWNER_NAME?></td>  	  	
  </tr>
	<tr>
  	<td nowrap class="TableContent" width=100><?=_("审批者：")?></td>
  	<td class="TableData" colspan=3> <?=$PROJ_MANAGER_NAME?></td>  	  	
  </tr>
<?
  $DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
  for($i=0; $i < count($PROJ_PRIV_ARRAY); $i++)
  {
  	 if($PROJ_PRIV_ARRAY[$i]=='')
  	   continue;  	 
  	 $PROJ_USER_NAME="";
  	 $PRIV_NAME = get_code_name($PROJ_PRIV_ARRAY[$i],"PROJ_ROLE");
  	 $query = "select USER_NAME,DEPT_ID from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER_ARRAY[$i]')";
     $cursor = exequery(TD::conn(), $query);
     while($ROW = mysql_fetch_array($cursor))
  	    $PROJ_USER_NAME .= '<span title="'.$DEPARTMENT_ARRAY[$ROW["DEPT_ID"]]["DEPT_LONG_NAME"].'">'.$ROW["USER_NAME"].'</span>,';
  	    
  	 echo '
  	      <tr>
            <td class="TableContent" nowrap width=120>'.$PRIV_NAME.'：</td>
            <td class="TableData">'.$PROJ_USER_NAME.'</td>  	
          </tr>';
  }
?>
  <tr>
    <td nowrap class="TableContent"><?=_("项目类别：")?></td>
  	<td class="TableData"><?=get_code_name($PROJ_TYPE,"PROJ_TYPE")?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("参与部门：")?></td>
  	<td class="TableData"><?=$PROJ_DEPT_NAME?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("项目计划周期：")?></td>
  	<td class="TableData" colspan=3><?=$PROJ_START_TIME?>&nbsp;<?=_("至")?>&nbsp;<?=$PROJ_END_TIME?></td>
  </tr>
  <tr>
  	<td nowrap class="TableContent"><?=_("项目工期：")?></td>
  	<td class="TableData" colspan=3><?=$DIFF_DAY?> <?=_("工作日")?></td>
  </tr>
  <tr>
    <td nowrap class="TableContent"><?=_("项目描述：")?></td>
  	<td class="TableData Content" style="word-break:break-all;" colspan=3><?=$PROJ_DESCRIPTION?></td>
  </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap class="TableData" colspan="3">
<?
      if($ATTACHMENT_ID=="")
         echo _("无附件");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,0,0,0,0,0,0);
?>
      </td>
    </tr>
    
    <?
      //获取全局字段设置
      $ARRAY_SETTINGS = get_all_settings();
      
      //获取本项目全局字段值
      $ARRAY_GDATA =  proj_get_data_array_all($PROJ_TYPE,$PROJ_ID);
      
      //获取本项目自定义字段值
      $ARRAY_DATA = proj_get_field_text($PROJ_TYPE,$PROJ_ID);
      
      $HAS_ARRAY_DATA = false;
      
      if(is_array($ARRAY_DATA)) {
   		foreach((array)$ARRAY_DATA as $key => $value){
   		      if($value['HTML'] != "") $HAS_ARRAY_DATA = true;break;
   		}
   	}
      
      if(count($ARRAY_GDATA) > 0 || $HAS_ARRAY_DATA){
    ?>  
      <tr class="TableHeader">
         <td colspan=5 height=20><div style="float:left;font-weight:bold;">&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?=_("项目自定义信息")?></div></td>
      </tr>      
    <? } ?>
      
    <?  
   	if(is_array($ARRAY_SETTINGS)) {
   		foreach((array)$ARRAY_SETTINGS as $key => $value){
   		   if($ARRAY_GDATA[$value['FIELDNO']] == "") continue;   
    ?>
    <tr>
      <td nowrap class="TableContent"><?=$value['FIELDNAME']?></td>
      <td  class="TableData" colspan=3><?=$ARRAY_GDATA[$value['FIELDNO']]?></td>  
    </tr>
   <? }} ?>
    
   <?
   	if(is_array($ARRAY_DATA)) {
   		foreach((array)$ARRAY_DATA as $key => $value){
   		   if($value['HTML'] == "") continue;
   ?>
    <tr>
      <td nowrap class="TableContent"><?=$value['FIELDNAME']?></td>
      <td  class="TableData" colspan=3><?=$value['HTML']?></td>  
    </tr>
   <? }} ?>
   
   
<?
$COUNT=0;
$query="select id,type_name from proj_budget_type where char_length(type_no) = 6";

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$CODE_ID=$ROW["id"];
	$CODE_NAME=$ROW["type_name"];
	$s_query_sub= "select  BUDGET_AMOUNT  from proj_budget where TYPE_CODE = '$CODE_ID'and PROJ_ID = '$PROJ_ID'";
	$res_cursor_sub = exequery(TD::conn(), $s_query_sub);
	$a_sub = mysql_fetch_array($res_cursor_sub);
	$i_budget_amount = $a_sub["BUDGET_AMOUNT"]; 
	
	
	$CODE_EXT=unserialize($ROW["CODE_EXT"]);
	if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
	   $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
 
  if($i_budget_amount!="")
  {
    if($COUNT==0)
    {
?>
  <tr class="TableHeader">
    <td colspan=5 height=20><div style="float:left;font-weight:bold;">&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?=_("项目经费成本")?></div></td>
  </tr>
<?  	
    }
?>

    <tr>
      <td class="TableContent"><?=$CODE_NAME?><?=_("：")?></td>
      <td class="TableData" nowrap colspan=3><?=$i_budget_amount?></td>  	
    </tr>
<?
    $TOTAL+=$i_budget_amount;
  }
  $COUNT++;
}
if($TOTAL)
{
?>
    <tr>
      <td class="TableContent"><?=_("合计：")?></td>
      <td class="TableData" nowrap colspan=3><?=$TOTAL?></td>  	
    </tr>
<?
}
$COUNT=0;
?>
    <tr class="TableHeader">
      <td nowrap colspan=4><div style="float:left;font-weight:bold;">&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"> <?=_("审批记录")?></div></td>
    </tr>
<?
foreach((array)$APPROVE_LOG_ARR AS $V)
{
	if($V!="")
	{

    $COUNT++;
    if($COUNT%2==0)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap colspan=4><?=$V?></td>
    </tr>
<?
   }
}
if($COUNT<=0):?>
    <tr class="<?=$TableLine?>">
      <td nowrap colspan=4><?=_("暂无审批记录！");?></td>
    </tr>
<?endif;?>
</body>
</html>
	