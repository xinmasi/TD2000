<?
include_once("inc/workflow/inc/workflow.inc.php");
$MODULE_FUNC_ID    = "";
$MODULE_DESC       = _("工作流");
$MODULE_BODY       = $MODULE_OP = "";
$MODULE_HEAD_CLASS = 'workflow';

$MODULE_TYPE .= '<a href="javascript:get_list(\'0\');">'._("待办工作").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_list(\'1\');">'._("会签工作").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_list(\'2\');">'._("我的关注").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_list(\'3\');">'._("挂起工作").'</a> ';

$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'workflow\',\''._("工作流").'\',\'/general/workflow/list/\');">'._("全部").'&nbsp;</a>';

$FLOW_ARRAY = array();
$FLOW_PRCS_ARRAY = array();

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
    include_once("general/workflow/plugin/plugin.inc.php");
    include_once("inc/utility_flow.php");
    $ELEMENT_ARRAY_ALL = array();
    $COUNT=0;
    $MODULE_BODY.= "<ul>";

    $query = "SELECT FLOW_RUN_PRCS.PRCS_ID,FLOW_RUN_PRCS.ID AS PRCS_KEY_ID,FLOW_RUN.RUN_ID,FLOW_RUN.FLOW_ID,RUN_PRCS_NAME,PRCS_FLAG,FLOW_PRCS,FLOW_NAME,RUN_NAME,FLOW_TYPE,LIST_FLDS_STR,FORM_ID from FLOW_RUN_PRCS,FLOW_RUN,FLOW_TYPE WHERE FLOW_RUN_PRCS.RUN_ID=FLOW_RUN.RUN_ID and FLOW_RUN.FLOW_ID=FLOW_TYPE.FLOW_ID and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DEL_FLAG='0' and PRCS_FLAG<>'3' and PRCS_FLAG<>'4' and PRCS_FLAG<>'5' and PRCS_FLAG<>'6' and CHILD_RUN='0' order by FLOW_RUN_PRCS.PRCS_FLAG,PRCS_TIME desc limit 0,$MAX_COUNT";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        if($COUNT>$MAX_COUNT)
        {
            break;
        }

        $PRCS_ID       = $ROW["PRCS_ID"];
        $RUN_ID        = $ROW["RUN_ID"];
        $FLOW_ID       = $ROW["FLOW_ID"];
        $PRCS_FLAG     = $ROW["PRCS_FLAG"];
        $FLOW_PRCS     = $ROW["FLOW_PRCS"];
        $FLOW_NAME     = $ROW["FLOW_NAME"];
        $RUN_NAME      = $ROW["RUN_NAME"];
        $FLOW_TYPE     = $ROW["FLOW_TYPE"];
        $LIST_FLDS_STR = $ROW["LIST_FLDS_STR"];
        $FORM_ID       = $ROW["FORM_ID"];
        $PRCS_KEY_ID   = $ROW["PRCS_KEY_ID"];

        $query2 = "SELECT OP_FLAG from ".$use_databases."FLOW_RUN_PRCS".$archive_time." where RUN_ID='$RUN_ID'and FLOW_PRCS='$FLOW_PRCS'and PRCS_ID='$PRCS_ID'";
        $cursor2= exequery(TD::conn(),$query2,true);
        if($ROW2=mysql_fetch_array($cursor2))
        {
            $OP_FLAG=$ROW2[0]; 
        }
        $RUN_PRCS_NAME = $ROW["RUN_PRCS_NAME"];   //柔性步骤名称
        $PRCS_TYPE     = getPrcsType($FLOW_ID,$FLOW_PRCS); //柔性节点
        if(!$FLOW_PRCS_ARRAY["$FLOW_ID"])
        {
            $FLOW_PRCS_ARRAY["$FLOW_ID"] = array(); 
        }
        $PRCS_NAME = "";
        if($FLOW_TYPE==1)
        {
            if(!array_key_exists($FLOW_PRCS,$FLOW_PRCS_ARRAY["$FLOW_ID"]))
            {
                $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"] = getFlowPrcsName($FLOW_ID, $FLOW_PRCS);
            }
            $PRCS_NAME = $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"];
        }

        $ITEM_DESC = "";

        if($PRCS_FLAG=="1")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_close.gif' width=16 height=16 alt='"._("未接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="2")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_open.gif' width=16 height=16 alt='"._("已接收")."' align='absmiddle'>";
        else
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/flow_next.gif' width=16 height=16 alt='"._("已办结")."' align='absmiddle'>";

        $MODULE_BODY.='<li><a href="/general/workflow/list/?FLOW_ID='.$FLOW_ID.'" target="_blank" >['.$FLOW_NAME.']</a>'.$STATUS;

        if($PRCS_FLAG!="3")
        {
            //$MODULE_BODY.='<a href="/general/workflow/list/input_form/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_ID='.$PRCS_ID.'&FLOW_PRCS='.$FLOW_PRCS.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
            $MODULE_BODY.="<a  href=javascript:add_handle(\"$MENU_FLAG\",\"$RUN_ID\",\"$PRCS_KEY_ID\",\"$FLOW_ID\",\"$PRCS_ID\",\"$FLOW_PRCS\")>".$RUN_NAME." ".$PRCS_NAME." ".$ITEM_DESC."</a></li>";	
        }
        else
        {
            $MODULE_BODY.='<a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
        }
    }
    if(($COUNT > $MAX_COUNT/3))
    {
        $MORE = 1;
    }
    else
    {
        $MORE = 2;
    }
    
    if($COUNT==0)
        $MODULE_BODY.= "<li>"._("暂无待办工作")."</li>";

    $MODULE_BODY.= "</ul>";

    $MODULE_BODY.='<script>
        function get_list(req)
        {
            var obj = $("module_'.$MODULE_ID.'_ul");
            if(!obj) return;

            if(typeof(req) != "object")
            {
                obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle">'._(" 加载中，请稍候……").'\';

                _get("workflow.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'&MODULE_ID=Math.random()", arguments.callee);          
            }
            else
            {
                obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);    
            }
        }
    </script>';
}
?>