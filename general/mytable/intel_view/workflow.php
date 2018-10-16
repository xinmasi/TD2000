<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
switch ($TYPE)
{
    case 0:
     include_once("general/workflow/plugin/plugin.inc.php");
     include_once("inc/workflow/inc/workflow.inc.php");
     include_once("inc/utility_flow.php");
          $OUTPUT = get_op($MAX_COUNT);
          break;
    case 1:
          $OUTPUT = get_sign($MAX_COUNT);
          break;
    case 2:
          $OUTPUT = get_focus($MAX_COUNT);
          break;
    case 3:
		  include_once("inc/workflow/inc/workflow.inc.php");
          $OUTPUT = get_pending($MAX_COUNT);
          break;
}

if($MODULE_SCROLL=="true" && stristr($OUTPUT, "href"))
{
   $OUTPUT='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$OUTPUT.'</marquee>';
}
echo $OUTPUT;


function get_op($MAX_COUNT)
{
    $ELEMENT_ARRAY_ALL = array();
    $COUNT=0;
    $FLOW_ARRAY = array();
    $FLOW_PRCS_ARRAY = array();

    $MODULE_BODY= "<ul>";

    $query = "SELECT FLOW_RUN_PRCS.PRCS_ID,FLOW_RUN.RUN_ID,FLOW_RUN.FLOW_ID,FLOW_RUN_PRCS.ID AS PRCS_KEY_ID,PRCS_FLAG,FLOW_PRCS,FLOW_NAME,RUN_NAME,FLOW_TYPE,LIST_FLDS_STR,FORM_ID from FLOW_RUN_PRCS,FLOW_RUN,FLOW_TYPE WHERE FLOW_RUN_PRCS.RUN_ID=FLOW_RUN.RUN_ID and FLOW_RUN.FLOW_ID=FLOW_TYPE.FLOW_ID and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DEL_FLAG='0' and PRCS_FLAG<>'3' and PRCS_FLAG<>'4' and PRCS_FLAG<>'5' and PRCS_FLAG<>'6' and CHILD_RUN='0' order by FLOW_RUN_PRCS.PRCS_FLAG,PRCS_TIME desc limit 0,$MAX_COUNT";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;

        if($COUNT > $MAX_COUNT)
            break;

        $PRCS_ID        = $ROW["PRCS_ID"];
        $RUN_ID         = $ROW["RUN_ID"];
        $FLOW_ID        = $ROW["FLOW_ID"];
        $PRCS_FLAG      = $ROW["PRCS_FLAG"];
        $FLOW_PRCS      = $ROW["FLOW_PRCS"];
        $FLOW_NAME      = $ROW["FLOW_NAME"];
        $RUN_NAME       = $ROW["RUN_NAME"];
        $FLOW_TYPE      = $ROW["FLOW_TYPE"];
        $LIST_FLDS_STR  = $ROW["LIST_FLDS_STR"];
        $FORM_ID        = $ROW["FORM_ID"];
        $PRCS_KEY_ID    = $ROW["PRCS_KEY_ID"];

        if(!$FLOW_PRCS_ARRAY["$FLOW_ID"])
            $FLOW_PRCS_ARRAY["$FLOW_ID"] = array();

        $PRCS_NAME = "";
        if($FLOW_TYPE==1)
        {
            if(!array_key_exists($FLOW_PRCS,$FLOW_PRCS_ARRAY["$FLOW_ID"]))
            {
                $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"] = getFlowPrcsName($FLOW_ID, $FLOW_PRCS);
            }

            $PRCS_NAME = $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"];
        }

        if($PRCS_FLAG=="1")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_close.gif' width=16 height=16 alt='"._("未接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="2")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_open.gif' width=16 height=16 alt='"._("已接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="6")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/sms_type4.gif' width=16 height=16 alt='"._("已挂起")."' align='absmiddle'>";
        else
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/flow_next.gif' width=16 height=16 alt='"._("已办结")."' align='absmiddle'>";

        $MODULE_BODY.='<li><a href="/general/workflow/list/?FLOW_ID='.$FLOW_ID.'">['.$FLOW_NAME.']</a>'.$STATUS;

        if($PRCS_FLAG!="3")
            $MODULE_BODY.='<a href="/general/workflow/list/input_form/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_ID='.$PRCS_ID.'&FLOW_PRCS='.$FLOW_PRCS.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
        else
            $MODULE_BODY.='<a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
    }

    if($COUNT > $MAX_COUNT/3){
        $MORE = 1;
    }else{
        $MORE = 2;
    }

    if($COUNT==0)
        $MODULE_BODY.= "<li>"._("暂无待办工作")."</li>";

    $MODULE_BODY.= "</ul>";
    $MODULE_BODY.= "<input type='hidden' id='MORE' name='MORE' value='$MORE'>";
    $MODULE_BODY.= "<input type='hidden' id='MORE_URL' name='MORE_URL' value='<a href=\"/general/workflow/list/index.php?\" target=\"_blank\">"._("查看更多")."</a>'>";

    return $MODULE_BODY;
}

function get_sign($MAX_COUNT)
{
    $MODULE_BODY= "<ul>";

    $COUNT=0;
    $query = "SELECT FLOW_RUN_PRCS.PRCS_ID,FLOW_RUN.RUN_ID,FLOW_RUN.FLOW_ID,FLOW_RUN_PRCS.ID AS PRCS_KEY_ID,PRCS_FLAG,FLOW_PRCS,FLOW_NAME,RUN_NAME from FLOW_RUN_PRCS,FLOW_RUN,FLOW_TYPE WHERE FLOW_RUN_PRCS.RUN_ID=FLOW_RUN.RUN_ID and FLOW_RUN.FLOW_ID=FLOW_TYPE.FLOW_ID and DEL_FLAG=0 and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and OP_FLAG='0' order by FLOW_RUN_PRCS.PRCS_FLAG,PRCS_TIME desc limit 0,$MAX_COUNT";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $PRCS_ID        = $ROW["PRCS_ID"];
        $RUN_ID         = $ROW["RUN_ID"];
        $FLOW_ID        = $ROW["FLOW_ID"];
        $PRCS_FLAG      = $ROW["PRCS_FLAG"];
        $FLOW_PRCS      = $ROW["FLOW_PRCS"];
        $FLOW_NAME      = $ROW["FLOW_NAME"];
        $RUN_NAME       = $ROW["RUN_NAME"];
        $PRCS_KEY_ID    = $ROW["PRCS_KEY_ID"];

        if($PRCS_FLAG=="1")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_close.gif' alt='"._("未接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="2")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_open.gif' width=16 height=16 alt='"._("已接收")."' align='absmiddle'>";
        else
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/flow_next.gif' width=16 height=16 alt='"._("已办结")."' align='absmiddle'>";

        $MODULE_BODY.='<li>'.$STATUS;
        if($PRCS_FLAG!="3" && $PRCS_FLAG!="4")
            $MODULE_BODY.='<a href="/general/workflow/list/input_form/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_ID='.$PRCS_ID.'&FLOW_PRCS='.$FLOW_PRCS.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$FLOW_NAME.' - '.$RUN_NAME.'</a></li>';
        else
            $MODULE_BODY.='<a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'&PRCS_KEY_ID='.$PRCS_KEY_ID.'" target="_blank">'.$FLOW_NAME.' - '.$RUN_NAME.'</a></li>';
    }
    if($COUNT > $MAX_COUNT/3){
        $MORE = 1;
    }else{
        $MORE = 2;
    }

    if($COUNT==0)
        $MODULE_BODY.= "<li>"._("暂无会签流程")."</li>";

    $MODULE_BODY.= "<ul>";
    $MODULE_BODY.= "<input type='hidden' id='MORE' name='MORE' value='$MORE'>";
    $MODULE_BODY.= "<input type='hidden' id='MORE_URL' name='MORE_URL' value='<a href=\"/general/workflow/query/\" target=\"_blank\">"._("查看更多")."</a>'>";
    return $MODULE_BODY;
}

function get_focus($MAX_COUNT)
{
    $MODULE_BODY= "<ul style='height:100'>";
    $COUNT=0;

    $query = "SELECT a.RUN_ID,a.FLOW_ID,a.RUN_NAME,b.FLOW_NAME FROM FLOW_RUN AS a,FLOW_TYPE AS b WHERE a.FLOW_ID=b.FLOW_ID AND DEL_FLAG=0 AND find_in_set('".$_SESSION["LOGIN_USER_ID"]."',FOCUS_USER) ORDER BY RUN_ID DESC";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $RUN_ID         = $ROW["RUN_ID"];
        $FLOW_NAME      = $ROW["FLOW_NAME"];
        $RUN_NAME       = $ROW["RUN_NAME"];
        
        $MODULE_BODY .= '<li><a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'" target="_blank">'._("【").$FLOW_NAME._("】 - ").' '.$RUN_NAME.'</a></li>';
        if($COUNT>$MAX_COUNT)
            break;
    }

    if($COUNT > $MAX_COUNT/3){
        $MORE = 1;
    }else{
        $MORE = 2;
    }

    if($COUNT==0)
        $MODULE_BODY.= "<li>"._("暂无关注工作")."</li>";

    $MODULE_BODY.= "</ul>";
    $MODULE_BODY.= "<input type='hidden' id='MORE' name='MORE' value='$MORE'>";
    $MODULE_BODY.= "<input type='hidden' id='MORE_URL' name='MORE_URL' value='<a href=\"/general/workflow/query/index.php\" target=\"_blank\">"._("查看更多")."</a>'>";
    return $MODULE_BODY;
}

function get_pending($MAX_COUNT)
{
    $ELEMENT_ARRAY_ALL = array();
    $COUNT=0;
    $FLOW_ARRAY = array();
    $FLOW_PRCS_ARRAY = array();

    $MODULE_BODY= "<ul>";

    $query = "SELECT FLOW_RUN_PRCS.PRCS_ID,FLOW_RUN.RUN_ID,FLOW_RUN.FLOW_ID,PRCS_FLAG,FLOW_PRCS,FLOW_NAME,RUN_NAME,FLOW_TYPE,LIST_FLDS_STR,FORM_ID from FLOW_RUN_PRCS,FLOW_RUN,FLOW_TYPE WHERE FLOW_RUN_PRCS.RUN_ID=FLOW_RUN.RUN_ID and FLOW_RUN.FLOW_ID=FLOW_TYPE.FLOW_ID and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DEL_FLAG='0' and PRCS_FLAG<>'1' and PRCS_FLAG<>'2' and PRCS_FLAG<>'3' and PRCS_FLAG<>'4' and PRCS_FLAG<>'5' and CHILD_RUN='0' order by FLOW_RUN_PRCS.PRCS_FLAG,PRCS_TIME desc limit 0,$MAX_COUNT";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        if($COUNT>$MAX_COUNT)
            break;

        $PRCS_ID        = $ROW["PRCS_ID"];
        $RUN_ID         = $ROW["RUN_ID"];
        $FLOW_ID        = $ROW["FLOW_ID"];
        $PRCS_FLAG      = $ROW["PRCS_FLAG"];
        $FLOW_PRCS      = $ROW["FLOW_PRCS"];
        $FLOW_NAME      = $ROW["FLOW_NAME"];
        $RUN_NAME       = $ROW["RUN_NAME"];
        $FLOW_TYPE      = $ROW["FLOW_TYPE"];
        $LIST_FLDS_STR  = $ROW["LIST_FLDS_STR"];
        $FORM_ID        = $ROW["FORM_ID"];

        if(!$FLOW_PRCS_ARRAY["$FLOW_ID"])
            $FLOW_PRCS_ARRAY["$FLOW_ID"] = array();

        $PRCS_NAME = "";
        if($FLOW_TYPE==1)
        {
            if(!array_key_exists($FLOW_PRCS,$FLOW_PRCS_ARRAY["$FLOW_ID"]))
            {
                $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"] = getFlowPrcsName($FLOW_ID, $FLOW_PRCS);
            }

            $PRCS_NAME = $FLOW_PRCS_ARRAY["$FLOW_ID"]["$FLOW_PRCS"];
        }

        if($PRCS_FLAG=="1")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_close.gif' width=16 height=16 alt='"._("未接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="2")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/email_open.gif' width=16 height=16 alt='"._("已接收")."' align='absmiddle'>";
        else if($PRCS_FLAG=="6")
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/sms_type4.gif' width=16 height=16 alt='"._("已挂起")."' align='absmiddle'>";
        else
            $STATUS="<img src='".MYOA_STATIC_SERVER."/static/images/flow_next.gif' width=16 height=16 alt='"._("已办结")."' align='absmiddle'>";

        $MODULE_BODY.='<li><a href="/general/workflow/list/?FLOW_ID='.$FLOW_ID.'" target="_blank">['.$FLOW_NAME.']</a>'.$STATUS;

        if($PRCS_FLAG!="3")
        {
            $MODULE_BODY.='<a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
        }else{
            $MODULE_BODY.='<a href="/general/workflow/list/print/?RUN_ID='.$RUN_ID.'&FLOW_ID='.$FLOW_ID.'" target="_blank">'.$RUN_NAME.' '.$PRCS_NAME.' '.$ITEM_DESC.'</a></li>';
        }
    }

    if($COUNT==0)
    $MODULE_BODY.= "<li>"._("暂无挂起工作")."</li>";

    $MODULE_BODY.= "</ul>";
    return $MODULE_BODY;
}
?>