<?
include_once("inc/auth.inc.php");
include_once("inc/utility_flow.php");
include_once("general/workflow/prcs_role.php");


$HTML_PAGE_TITLE = _("新建工作");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
if(!prcs_role($FLOW_ID,1))
{
    Message("<body class=bodycolor>"._("提示"),_("没有该流程新建权限，请与OA管理员联系"));
    Button_Back();
 
}

//--- 自动编号---
$CUR_TIME=date("Y-m-d H:i:s",time());

$FLOW_ID = intval($FLOW_ID);
$query = "SELECT * from FLOW_TYPE WHERE FLOW_ID=".$FLOW_ID;
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
   $FLOW_NAME=$ROW["FLOW_NAME"];
   $FLOW_TYPE=$ROW["FLOW_TYPE"];
   $FORM_ID=$ROW["FORM_ID"];
   $AUTO_NAME = $ROW["AUTO_NAME"];
   $AUTO_NUM = $ROW["AUTO_NUM"];
   $AUTO_LEN = $ROW["AUTO_LEN"];
   $AUTO_EDIT = $ROW["AUTO_EDIT"];
   $FLOW_SORT = $ROW["FLOW_SORT"];
   $FLOW_DESC = $ROW["FLOW_DESC"];
   $FLOW_DESC=str_replace("\n","<br>",$FLOW_DESC);
}

if($AUTO_NAME=="")
   $RUN_NAME=$FLOW_NAME."(".$CUR_TIME.")";
else
{
   $RUN_NAME=$AUTO_NAME;
   $CUR_YEAR=date("Y",time());
   $CUR_MON=date("m",time());
   $CUR_DAY=date("d",time());
   $CUR_HOUR = date('H');
   $CUR_MINITE = date('i');
   $CUR_SECOND = date('s');

   $query = "SELECT SORT_NAME from FLOW_SORT WHERE SORT_ID='$FLOW_SORT'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $SORT_NAME=$ROW["SORT_NAME"];

   $query = "SELECT DEPT_NAME from DEPARTMENT WHERE DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $DEPT_NAME=$ROW["DEPT_NAME"];

   $LONG_DEPT_NAME=dept_long_name($_SESSION["LOGIN_DEPT_ID"]);

   $query = "SELECT PRIV_NAME from USER_PRIV WHERE USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $PRIV_NAME=$ROW["PRIV_NAME"];

   $AUTO_NUM++;
   $LEN=strlen($AUTO_NUM);
   for($I=0;$I<$AUTO_LEN-$LEN;$I++)
    	 $AUTO_NUM="0".$AUTO_NUM;

   $RUN_NAME=str_replace("{Y}",$CUR_YEAR,$RUN_NAME);   //{Y}：表示年
   $RUN_NAME=str_replace("{M}",$CUR_MON,$RUN_NAME);    //{M}：表示月
   $RUN_NAME=str_replace("{D}",$CUR_DAY,$RUN_NAME);    //{D}：表示日
   $RUN_NAME=str_replace("{H}",$CUR_HOUR,$RUN_NAME);   //{H}：表示时
   $RUN_NAME=str_replace("{I}",$CUR_MINITE,$RUN_NAME); //{I}：表示分
   $RUN_NAME=str_replace("{S}",$CUR_SECOND,$RUN_NAME); //{S}：表示秒

   $RUN_NAME=str_replace("{F}",$FLOW_NAME,$RUN_NAME);  //{F}：表示流程名
   $RUN_NAME=str_replace("{FS}",$SORT_NAME,$RUN_NAME);  //{FS}：表示流程分类名
   $RUN_NAME=str_replace("{U}",$_SESSION["LOGIN_USER_NAME"],$RUN_NAME);  //{U}：表示用户姓名
   $RUN_NAME=str_replace("{SD}",$DEPT_NAME,$RUN_NAME);      //{SD}：表示短部门名
   $RUN_NAME=str_replace("{LD}",$LONG_DEPT_NAME,$RUN_NAME); //{LD}：表示长部门名
   $RUN_NAME=str_replace("{R}",$PRIV_NAME,$RUN_NAME);       //{R}：表示角色
   $RUN_NAME=str_replace("{N}",$AUTO_NUM,$RUN_NAME);        //{N}：表示编号
}

$RUN_NAME=td_htmlspecialchars($RUN_NAME);

$query = "SELECT 1 from FLOW_RUN WHERE RUN_NAME='$RUN_NAME' and FLOW_ID='$FLOW_ID'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
   Message(_("提示"),_("输入的工作名称/文号与之前的工作重复，请重新设置。"));
?>
<div align=center><input type="button"  value="<?=_("重新设置")?>" class="BigButton" onClick="location='edit.php?FLOW_ID=<?=$FLOW_ID?>';"></div>
<?
   exit;
}

$query = "SELECT MAX(RUN_ID) from FLOW_RUN";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $RUN_ID=$ROW[0]+1;

if(strstr($RUN_NAME,'{RUN}'))
	$RUN_NAME = str_replace('{RUN}',$RUN_ID,$RUN_NAME);
	
$CUR_TIME=date("Y-m-d H:i:s",time());
$query="insert into FLOW_RUN(RUN_ID,RUN_NAME,FLOW_ID,BEGIN_USER,BEGIN_TIME,BEGIN_DEPT) values ('$RUN_ID','$RUN_NAME',$FLOW_ID,'".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','".$_SESSION["LOGIN_DEPT_ID"]."')";
exequery(TD::conn(),$query);

$query="insert into FLOW_RUN_PRCS(RUN_ID,PRCS_ID,USER_ID,PRCS_DEPT,PRCS_FLAG,FLOW_PRCS,CREATE_TIME) values ($RUN_ID,1,'".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','1','1','$CUR_TIME')";
exequery(TD::conn(),$query);

$FLOW_ID = intval($FLOW_ID);
$query = "SELECT * from FLOW_TYPE WHERE FLOW_ID=".$FLOW_ID;
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
   $FORM_ID=$ROW["FORM_ID"];
   $AUTO_NAME = $ROW["AUTO_NAME"];
}

if(strstr($AUTO_NAME,"{N}"))
{
	$query="update FLOW_TYPE set AUTO_NUM=AUTO_NUM+1 where FLOW_ID=".$FLOW_ID;
  exequery(TD::conn(),$query);
}

//若没生成缓存则生成	 
/* 
$FILE_PATH = MYOA_ROOT_PATH."/inc/workflow_cache/form/".$FORM_ID.".php";
if(!file_exists($FILE_PATH))
	cache_form($FORM_ID);     
include_once("inc/workflow_cache/form/".$FORM_ID.".php");
*/

$run_data = array("run_id" => $RUN_ID);
$run_data["begin_user"] = $_SESSION["LOGIN_USER_ID"];
$run_data["begin_time"] = $CUR_TIME;
$run_data["run_name"] = $RUN_NAME;

$WORKFLOW_ELEMENT_ARRAY = TD::get_cache('workflow/form/ELEMENT_ARRAY_'.$FORM_ID);
foreach($WORKFLOW_ELEMENT_ARRAY as $ENAME => $ELEMENT_ARR)
{
  //--- 默认值 ---
  $ECLASS = $ELEMENT_ARR["CLASS"];  
  $ITEM_ID = $ELEMENT_ARR["ITEM_ID"];
  $EVALUE = $ELEMENT_ARR["VALUE"];  
  $ETAG = $ELEMENT_ARR["TAG"]; 
  
  if(date_or_user_or_more($ELEMENT_ARR))
     continue;

  if($ETAG=="INPUT" && stristr($ELEMENT,"checkbox"))
  {
      if(stristr($ELEMENT,"CHECKED") || stristr($ELEMENT,' checked="checked"'))
        $ITEM_DATA="on";
      else
        $ITEM_DATA="";
  }
  
  if($ETAG!="SELECT" && $ECLASS!="LIST_VIEW")
  {
     $ITEM_DATA=$EVALUE;
     $ITEM_DATA=str_replace("\"","",$ITEM_DATA);
     if($ITEM_DATA=="{MACRO}") //add by sogo
        $ITEM_DATA="";
  }
  else
     $ITEM_DATA="";
  /*
  $query="insert into FLOW_RUN_DATA(RUN_ID,ITEM_ID,ITEM_DATA ) values ('$RUN_ID','$ITEM_ID','$ITEM_DATA')";
  exequery(TD::conn(),$query);
  */
  
  $KEY = "DATA_" . $ITEM_ID;
  $run_data[$KEY] = $ITEM_DATA;
}

insert_table_data($FLOW_ID,$run_data);

//更新项目任务
$query = "update PROJ_TASK SET RUN_ID_STR=CONCAT(RUN_ID_STR,'$RUN_ID,') WHERE TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);
header("location: /general/workflow/list/input_form/?RUN_ID=$RUN_ID&FLOW_ID=$FLOW_ID&PRCS_ID=1&FLOW_PRCS=1");

?>
