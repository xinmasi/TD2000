<!-------------------- 报表系统 ----------------------->
<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("general/reportshop/utils/utils.func.php");
if (!HasTable("crs_quick_reportstate")) {
    include_once("general/reportshop/update/quick_reportstate.php");
}
$MODULE_FUNC_ID = "";
$MODULE_DESC = "报表提示";
$MODULE_BODY = $MODULE_OP = "";
if ($MODULE_FUNC_ID == "" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID)) {
    $COUNT = 0;
    $MODULE_BODY .= "<ul>";

//------------------------------------------公告---------------------------------------------
    $sql = "select * from crscell.crs_reportbulletin where (begintime='0000-00-00' or begintime<=DATE(now())) and (endtime='0000-00-00' or endtime>=DATE(now()))";
    $cursor = exequery(TD::conn(), $sql);
    while ($row = mysql_fetch_array($cursor)) {
        if (!CanRead($row["repid"], "", $row["userid"], $row["writetime"]))
            continue;
        $COUNT++;
        if ($COUNT > $MAX_COUNT)
            break;
        $seq = GetRepNameById($row["repid"]); //GetAutoCodeByRep($row["repid"], "", $row2["userid"], $row2["writetime"]);
        $MODULE_BODY .= '<li><a target="_blank" href="/general/reportshop/workshop/report/phpcell/index.php?repid=' . $row["repid"] .
            '&openmode=read&writetime=' . $row["writetime"] . '&userid=' . $row["userid"] . '">' .
            $seq . '[' . GetUserNameById1(0, $row["userid"]) . ' ' . $row["writetime"] . ']</a></li>';
    }

//------------------------------------------ 办理 --------------------------------------------
    $arr_entrust_map = array();
    $state = "todo";
    $repid_list = "";
    $sql = "select id, support_html_rep from crscell.crs_report where (isstop <> 'y' or isstop is null)";
    $cursor = exequery(TD::conn(), $sql);
    while ($row = mysql_fetch_array($cursor)) {
        $repid_list .= $row["id"] . ",";
    }
    //提取委托给本地用户的委托人列表
    $now = strtotime(date("Y-m-d", time()));
    $assign_user_list = "";
    $sql = "select repid, user_id, begin_date, end_date from crscell.crs_entrust where find_in_set('$_SESSION[LOGIN_USER_ID]', to_id) and status=1"; //(to_id = '$_SESSION[LOGIN_USER_ID]' or to_id like '$_SESSION[LOGIN_USER_ID],%' or to_id like '%,$_SESSION[LOGIN_USER_ID],%')
    $cursor = exequery(TD::conn(), $sql);
    while ($row = mysql_fetch_array($cursor)) {
        if ($row["begin_date"] == "0000-00-00" || $row["begin_date"] == "")
            $bt = $now;
        else
            $bt = strtotime($row["begin_date"]);

        if ($row["end_date"] == "0000-00-00" || $row["end_date"] == "")
            $et = $now;
        else
            $et = strtotime($row["end_date"]);
        if ($bt <= $now && $now <= $et) {
            $user = $row["user_id"];
            $assign_user_list .= "$user,";
            if(empty($arr_entrust_map[$user])) $arr_entrust_map[$user] = array();
            $arr = explode(",", rtrim($row["repid"], ","));
            $arr_entrust_map[$user] = array_merge($arr_entrust_map[$user], $arr);
        }
    }

    $sql = "select * from crscell.crs_quick_reportstate where find_in_set(repid, '$repid_list') and
		(writer='$_SESSION[LOGIN_USER_ID]' or find_in_set(writer, '$assign_user_list')) and finished<>'Y' and (endtime is null or endtime='') order by writetime desc, id desc";
    $cursor = exequery(TD::conn(), $sql);
    while ($row = mysql_fetch_array($cursor)) {
        //过滤委托规则
        if($row["writer"] != $_SESSION["LOGIN_USER_ID"] && !empty($arr_entrust_map[$row["writer"]])){
            if(!in_array($row["repid"], $arr_entrust_map[$row["writer"]])){
                continue;
            }
        }

        $key = $row["repid"] . $row["userid"] . $row["writetime"];
        if (isset($rep_write_map) && in_array($key, $rep_write_map))
            continue;

        if (empty($row["writetime2"])) {
            $s_state = GetTaskState2($row["repid"], $row["workflow"], $row["userid"], $row["writetime"]); //有依赖？
            if ($s_state != "")
                continue;
        }
        if (true) {
            $rep_write_map[] = $key;
            $COUNT++;
            if ($COUNT > $MAX_COUNT)
                break;
            $seq = GetAutoCodeByRep($row["repid"], $row["organid"], $row["userid"], $row["writetime"]);
            $lab = GetRepNameById($row["repid"]);
            if ($row["workflow"] == "") {
                $sql = "select id from crscell.crs_report where id = " . $row["repid"] . " and mode = 'y'";
                $cursor1 = exequery(TD::conn(), $sql);
                if ($row1 = mysql_fetch_array($cursor1))
                    $lab = "协报 " . $lab;
                else {
                    $sql = "select label from crscell.crs_workflow where repid = " . $row["repid"] . " and is_begin = '是'";
                    $cursor1 = exequery(TD::conn(), $sql);
                    if ($row1 = mysql_fetch_array($cursor1))
                        $lab = $row1["label"] . ' ' . $lab;
                }
            } else
            if ($row["workflow"] == "!")
                $lab = "确认 " . $lab;
            else
                $lab = AddBeginTask($row["repid"], $row["workflow"]) . " " . $lab;

            $MODULE_BODY .= "<li><a target='_blank' href='/general/reportshop/workshop/report/phpcell/index.php?repid=$row[repid]&openmode=edit&userid=$row[userid]&writetime=$row[writetime]&curtask=$row[workflow]'>[" . $seq . "] " . $lab . "</a></li>";
        }
    }

    /* 定期填报 */
    $sql = "select id, repname, kindid from crscell.crs_report where type='统计报表' and statistype not like '不定期' and (isstop='' or isstop is null)";
    $cursor = exequery(TD::conn(), $sql);
    while (false && $row = mysql_fetch_array($cursor)) {
        if (!HasWritePriv($row["id"]))
            continue;
        $repstate = GetRepState($RepList, $CurPeriod, $RemindDay, $row["id"], "");
        if ($repstate == "remind") {
            $COUNT++;
//     	if($COUNT>$MAX_COUNT) break;
            $MODULE_BODY .= "<li><a target='_blank' href='/general/reportshop/workshop/report/phpcell/index.php?repid=$row[id]&openmode=write'>填报 $row[repname]</a></li>";
        }
    }

    /* 预警、提醒 */
    $remind_arr[] = "";
    $sql = "select crscell.crs_report.id as repid, repname, remind_label, remind_cond, remind_vcond from crscell.crs_detailreadpriv cross join crscell.crs_report on 
  	crscell.crs_detailreadpriv.reportid=crscell.crs_report.id where remind_cond <> '' and remind_cond is not null
  	order by crscell.crs_report.orderno, crscell.crs_report.repno";
    $cursor = exequery(TD::conn(), $sql);
    while (false && $row = mysql_fetch_array($cursor)) {
        //解析预警条件
        $res = "";
        $tab_arr = null;
        $str = $row["remind_vcond"];
        if ($str != "") {
            $str = str_replace("当前用户", "'" . GetUserNameById1($_SESSION["LOGIN_DEPT_ID"], $_SESSION["LOGIN_USER_ID"]) . "'", $str);
            $str = str_replace("当前部门", "'" . GetOrganNameById($_SESSION["LOGIN_DEPT_ID"]) . "'", $str);
            $str = str_replace("当前角色", "'" . $_SESSION["LOGIN_USER_PRIV"] . "'", $str);
            //$str = str_replace("填报部门", "organid", $str);
            //$str = str_replace("填报人", "userid", $str);
            $str = str_replace("填报时间", "writetime", $str);
            $str = str_replace("系统日期", "now()", $str);
            $str = str_replace("本报表.", "", $str);

            $str = str_replace(' and ', '&', $str);
            $arr = explode('&', $str);
            for ($i = 0; $i < count($arr); $i++) {
                $str = str_replace(' or ', '|', $arr[$i]);
                $arr2 = explode('|', $str);
                for ($j = 0; $j < count($arr2); $j++) {
                    $str = $arr2[$j];
                    $p = strpos($str, "crscell.crs_tabledata");
                    while ($p !== false) {
                        $p1 = strpos($str, "c", $p + 20);
                        $tab = substr($str, $p, $p1 - $p);
                        if (!isset($tab_arr) || !in_array($tab, $tab_arr)) {
                            $tab_arr[] = $tab;
                        }
                        $p2 = strpos($str, ".", $p + 20);
                        $str = substr_replace($str, "", $p1, $p2 - $p1);
                        $p = strpos($str, "crscell.crs_tabledata", $p + 20);
                    }
                    {
                        $arr2[$j] = $str;
                    }
                }

                $str = "";
                for ($j = 0; $j < count($arr2); $j++) {
                    if ($arr2[$j] != "") {
                        if ($str != "") {
                            $str .= " or ";
                        }
                        $str .= $arr2[$j];
                    }
                }
                if ($str != "") {
                    if ($res != "")
                        $res .= " and ";
                    $res .= "$str";
                }
            }
        }

        //构建查询表列表
        $tab_list = "";
        $prev_tab = "";
        if (isset($tab_arr)) {
            for ($i = 0; $i < count($tab_arr); $i++) {
                $tab = $tab_arr[$i];
                if ($tab_list == "")
                    $tab_list = $tab;
                else {
                    $tab_list .= " cross join $tab on $prev_tab.userid=$tab.userid and $prev_tab.writetime=$tab.writetime";
                }
                $prev_tab = $tab;
            }
        }

        if ($tab_list != "") {
            $sql = "select distinct $prev_tab.userid, $prev_tab.writetime from $tab_list where $res order by $prev_tab.writetime desc, $prev_tab.organid, $prev_tab.userid";
            $cursor2 = exequery(TD::conn(), $sql);
            while ($row2 = mysql_fetch_array($cursor2)) {
                //if($COUNT>$MAX_COUNT) break;
                if (in_array($row["repid"] . $row2["userid"] . $row2["writetime"], $remind_arr))
                    continue;
                if (!CanRead($row["repid"], "", $row2["userid"], $row2["writetime"]))
                    continue;
                $COUNT++;
                $seq = $row["repname"]; //GetAutoCodeByRep($row["repid"], "", $row2["userid"], $row2["writetime"]);
                $MODULE_BODY .= '<li><a target="_blank" href=\'/general/reportshop/workshop/report/phpcell/index.php?repid=' . $row["repid"] .
                    '&openmode=read&writetime=' . $row2["writetime"] . '&userid=' . $row2["userid"] . '\'>' .
                    $row["remind_label"] . ' ' . $seq . '[' . GetUserNameById1(0, $row2["userid"]) . ' ' . $row2["writetime"] . ']</a></li>';
            }
        }
    }

    if ($COUNT == 0)
        $MODULE_BODY .= "<li>暂无提示</li>";
    $MODULE_BODY .= "<ul>";
}
?>