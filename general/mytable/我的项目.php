<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="186";
$MODULE_DESC=_("我的项目");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'project';

include_once("inc/utility_project.php");
$disable_admin = true;
$auth_sql = project_auth_sql($disable_admin);

if(find_id($USER_FUNC_ID_STR, "186"))
  $MODULE_TYPE .='<a href="javascript:get_project(\'0\');">'._("我的项目").'</a> '; 
if(find_id($USER_FUNC_ID_STR, "187"))
  $MODULE_TYPE .='<a href="javascript:get_project(\'1\');">'._("项目任务").'</a>'; 

if(find_id($USER_FUNC_ID_STR, "186"))
{
    $code_arr = array();
    $query = "SELECT CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='PROJ_TYPE'";
    $cursor= exequery(TD::conn(),$query);
    while($row = mysql_fetch_array($cursor))
    {
        $CODE_NO        = $row["CODE_NO"];
        $CODE_NAME      = $row["CODE_NAME"];
        $CODE_EXT       = unserialize($row["CODE_EXT"]);
        if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
            $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

        $code_arr[$CODE_NO] = $CODE_NAME;
    }

  $STATUS_ARR=array("0"=>_("立项中"),"1"=>_("审批中"),"2"=>_("进行中"),"3"=>_("已结束"));	
  $MODULE_BODY.= "<ul>";
  $query = "select * FROM PROJ_PROJECT where PROJ_STATUS<'3' $auth_sql ORDER BY PROJ_START_TIME DESC limit 0,$MAX_COUNT";
  $COUNT = 0;
  $cursor = exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
  	$COUNT++;
  	$PROJ_ID = $ROW["PROJ_ID"];
  	$PROJ_NAME = $ROW["PROJ_NAME"];
  	$PROJ_TYPE = $ROW["PROJ_TYPE"];
  	$PROJ_OWNER = $ROW["PROJ_OWNER"];
  	$PROJ_START_TIME = $ROW["PROJ_START_TIME"];
  	$PROJ_END_TIME = $ROW["PROJ_END_TIME"];
  	$PROJ_STATUS = $ROW["PROJ_STATUS"];
    
    $PROJ_ACT_END_TIME = $ROW["PROJ_ACT_END_TIME"];
    if($PROJ_ACT_END_TIME=="0000-00-00" || $PROJ_ACT_END_TIME=="")
       $PROJ_ACT_END_TIME="-";
    
  	$STATUS='<font color="green">'.$STATUS_ARR[$PROJ_STATUS].'</font>';
  	if($ROW["PROJ_STATUS"]==2 && compare_date($CUR_DATE,$ROW["PROJ_END_TIME"])==1)
  	   $STATUS = '<span style="font-color:red">'._("已超时").'</span>';
  
    $PROJ_NAME=td_htmlspecialchars($PROJ_NAME);
    if(strlen($PROJ_NAME)>40)
       $PROJ_NAME=csubstr($PROJ_NAME,0,40)."...";
    $PROJ_TYPE_NAME=$code_arr[$PROJ_TYPE];
  
    $MODULE_BODY.='<li>['.$PROJ_TYPE_NAME.']';
    $PROJ_ACTION = "window.open('/general/project/portal/details/?PROJ_ID=".$PROJ_ID."','','height=700,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=50,resizable=yes');";
    $MODULE_BODY.=' <a href="#" onclick="'.$PROJ_ACTION.'">'.$PROJ_NAME.' ('.$STATUS.')</a></li>';
  }
  
  if($COUNT==0)
     $MODULE_BODY.="<li>"._("暂无项目")."</li>";
  
  $MODULE_BODY.= "<ul>";
}
else if(find_id($USER_FUNC_ID_STR, "187"))
{
	$CUR_DATE = date("Y-m-d",time());
   $MODULE_BODY.= "<ul id='task_0'>";
    $query = "select * FROM PROJ_PROJECT p,PROJ_TASK t where p.PROJ_STATUS<'3' $auth_sql AND t.TASK_USER='".$_SESSION["LOGIN_USER_ID"]."' and t.TASK_START_TIME<='$CUR_DATE' AND t.TASK_PERCENT_COMPLETE<>'100' AND p.PROJ_ID = t.PROJ_ID ORDER BY TASK_LEVEL,TASK_END_TIME DESC limit 0,$MAX_COUNT";
   $COUNT = 0;
   $cursor = exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
   	$COUNT++;
   	$PROJ_ID = $ROW["PROJ_ID"];
   	$TASK_ID = $ROW["TASK_ID"];
   	$TASK_NAME = $ROW["TASK_NAME"];
   	$TASK_START_TIME = $ROW["TASK_START_TIME"];
   	$TASK_END_TIME = $ROW["TASK_END_TIME"];
     
   	$STATUS='<font color="green">'.("- 执行中").'</font>';
   	if(compare_date($CUR_DATE,$TASK_END_TIME)==1)
   	   $STATUS = '<font color="red">- '._("已超时").'</font>';
   
     $TASK_NAME=td_htmlspecialchars($TASK_NAME);
     if(strlen($TASK_NAME)>40)
        $TASK_NAME=csubstr($TASK_NAME,0,40)."...";
   
     $MODULE_BODY.='<li>';
     $MODULE_BODY.='<a href="/general/project/task/index1.php?PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID.'">'.$TASK_NAME.'</a> '.$STATUS.'</li>';
   }
   
   if($COUNT==0)
      $MODULE_BODY.="<li>"._("暂无项目任务")."</li>";
   
   $MODULE_BODY.= "</ul>";
}

$MODULE_BODY.='<script>
function get_project(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("project.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE_ID="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
</script>';
?>