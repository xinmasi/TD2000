<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");
include_once("inc/itask/itask.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
//2013-04-11 主服务器查询
if($IS_MAIN==1)
{
    $QUERY_MASTER = true;
}
else
{
    $QUERY_MASTER = "";
}
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
$CUR_DATE   = date("Y-m-d H:i:s",time());
if($OPERATION == 1)
{
    $query = "update VOTE_TITLE set BEGIN_DATE='$CUR_DATE' where VOTE_ID='$VOTE_ID'";
}
else if($OPERATION == 9)
{
    $flag_childvote = false;
    $query  = "select VOTE_ID from VOTE_TITLE where PARENT_ID='$VOTE_ID'";
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    $ITEM_ID_ARR    = array();
    $COUNT          = 0;
    while($ROW1=mysql_fetch_array($cursor)){
        $COUNT++;
        $VOTE_ID = $ROW1["VOTE_ID"];
        $query1="select ITEM_ID from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
        $cursor1= exequery(TD::conn(),$query1,$QUERY_MASTER);
        if($ROW1=mysql_fetch_array($cursor1)){
            $ITEM_ID_ARR[] = $ROW1["ITEM_ID"];
        }
    }
    //如果有一个子投票的项目为空，不可发布
    if(count($ITEM_ID_ARR) != 0 && count($ITEM_ID_ARR)==$COUNT){
        $flag_childvote = true;
    }
    $query1 = "select TYPE from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
    $cursor = exequery(TD::conn(),$query1,$QUERY_MASTER);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TYPE   = $ROW["TYPE"];
    }
    $query1 = "select ITEM_ID from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
    $cursor = exequery(TD::conn(),$query1,$QUERY_MASTER);
    if(mysql_num_rows($cursor) > 0 || $flag_childvote || $TYPE=="2")
    {
        $query1     = "SELECT TO_ID,PARENT_ID,BEGIN_DATE,SEND_TIME,SUBJECT,PRIV_ID,USER_ID,CONTENT from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
        $cursor1    = exequery(TD::conn(),$query1,$QUERY_MASTER);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $TO_ID      = $ROW1["TO_ID"];
            $PARENT_ID  = $ROW1["PARENT_ID"];
            $BEGIN_DATE = $ROW1["BEGIN_DATE"];
            $SEND_TIME  = $ROW1["SEND_TIME"];
            $SUBJECT    = $ROW1["SUBJECT"];
            $PRIV_ID    = $ROW1["PRIV_ID"];
            $USER_ID    = $ROW1["USER_ID"];
            $CONTENT    = $ROW1["CONTENT"];
        }

        //------- 事务提醒 --------
        $SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
        $PARA_VALUE     = $SYS_PARA_ARRAY["SMS_REMIND"];
        $REMIND_ARRAY   = explode("|", $PARA_VALUE);
        $SMS_REMIND     = $REMIND_ARRAY[0];
        $SMS2_REMIND    = $REMIND_ARRAY[1];
        $ALLOW_SMS      = $REMIND_ARRAY[2];

        $USER_ID_STR = '';   //发布范围内的所有用户
        if($PARENT_ID){
            $query2     = "SELECT TO_ID,PRIV_ID,USER_ID,SUBJECT from VOTE_TITLE where VOTE_ID='$PARENT_ID'";
            $cursor2    = exequery(TD::conn(),$query2,$QUERY_MASTER);
            if($ROW2=mysql_fetch_array($cursor2)){
                $TO_ID      = $ROW2["TO_ID"];
                $PRIV_ID    = $ROW2["PRIV_ID"];
                $USER_ID    = $ROW2["USER_ID"];
                $SUBJECT    = $ROW2["SUBJECT"];
            }
        }
        if($TO_ID == "ALL_DEPT")
        {
            $query2="select USER_ID from USER where NOT_LOGIN=0";
        }
        else
        {
            $query2="select USER_ID from USER where NOT_LOGIN=0";

            $where_str = '';
            if($PRIV_ID != '')
            {
                $where_str .= " or find_in_set(USER_PRIV,'$PRIV_ID')";
            }
            if($USER_ID != '')
            {
                $where_str .= " or find_in_set(USER_ID,'$USER_ID')";
            }
            if($TO_ID != '')
            {
                $where_str .= " or find_in_set(DEPT_ID,'$TO_ID')";
            }
            if($where_str != '')
            {
                $query2 .= " and (".substr($where_str, 4).")";
            }
            else
            {
                $query2 .= " and 1=0";
            }
        }
        $cursor2    = exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $USER_ID_STR .= $ROW2["USER_ID"].",";
        }

        if($TO_ID != "ALL_DEPT")
        {
            $where_str = '';
            //辅助角色
            $MY_ARRAY   = explode(",",$PRIV_ID);
            $ARRAY_COUNT= sizeof($MY_ARRAY);
            for($I=0;$I<$ARRAY_COUNT;$I++)
            {
                if($MY_ARRAY[$I] != "")
                {
                    $where_str .= " or find_in_set('".$MY_ARRAY[$I]."',USER_PRIV_OTHER)";
                }
            }
            //辅助部门
            $MY_ARRAY_DEPT      = explode(",",$TO_ID);
            $ARRAY_COUNT_DEPT   = sizeof($MY_ARRAY_DEPT);
            for($I=0;$I<$ARRAY_COUNT_DEPT;$I++)
            {
                if($MY_ARRAY_DEPT[$I]!="")
                {
                    $where_str .= " or find_in_set('".$MY_ARRAY_DEPT[$I]."',DEPT_ID_OTHER)";
                }
            }

            if($where_str != "")
            {
                $query3     = "select USER_ID from USER where NOT_LOGIN=0 and (".substr($where_str, 4).")";
                $cursor3    = exequery(TD::conn(),$query3);
                while($ROW3=mysql_fetch_array($cursor3))
                {
                    if(!find_id($USER_ID_STR,$ROW3["USER_ID"]))
                    {
                        $USER_ID_STR.=$ROW3["USER_ID"].",";
                    }
                }
            }
        }

        //排除没有投票菜单权限的人
        $USER_PRIV_STR          = '';
        $USER_PRIV_OTHER_SQL    = '';
        $query2     = "select USER_PRIV from USER_PRIV where find_in_set('148', FUNC_ID_STR)";
        $cursor2    = exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $USER_PRIV_STR .= $ROW2["USER_PRIV"].",";   //主角色
            $USER_PRIV_OTHER_SQL .= " or find_in_set('".$ROW2["USER_PRIV"]."',USER_PRIV_OTHER)";   //辅助角色
        }

        $USER_ID_STR_148            = '';  //所有有148菜单权限的用户
        $NOT_LOGIN_USER_ID_STR_148  = '';  //所有有148菜单权限的禁止登录用户
        if($USER_PRIV_STR != '')
        {
            $query2     = "select USER_ID from USER where NOT_LOGIN=0 and (find_in_set(USER_PRIV, '$USER_PRIV_STR') or ".substr($USER_PRIV_OTHER_SQL, 4).")";
            $cursor2    = exequery(TD::conn(),$query2);
            while($ROW2=mysql_fetch_array($cursor2))
            {
                $USER_ID_STR_148 .= $ROW2["USER_ID"].",";
            }
        }

        $USER_ID_STR = check_id($USER_ID_STR_148, $USER_ID_STR, true);   //所有内部短信提醒人员：所有发布范围选择的允许登录的用户中有148菜单权限的
        if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        {
            $SEND_TIME=$BEGIN_DATE;
        }

        if(find_id($ALLOW_SMS,"11") && find_id($SMS_REMIND,"11"))
        {
            $SUBJECT        = gbk_stripslashes($SUBJECT);
            $SMS_CONTENT    = _("请查看投票")."\n"._("标题：").csubstr($SUBJECT,0,100);
            $VOTE_ID_SEND   = $PARENT_ID==0?$VOTE_ID:$PARENT_ID;
            $REMIND_URL     = "1:vote/show/read_vote.php?VOTE_ID=".$VOTE_ID_SEND;
            send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,"11",$SMS_CONTENT,$REMIND_URL,$VOTE_ID_SEND);
            /*$WX_OPTIONS = array(
               "module" => "vote",
               "module_action" => "vote.read",
               "user" => $USER_ID_STR,
               "description"=>$CONTENT,
               "content" =>_("您有新的投票！")._("主题：").$SUBJECT,
               "params" => array(
                   "VOTE_ID" => $VOTE_ID
               )
              );
           WXQY_VOTE($WX_OPTIONS);*/
            if(find_id($SMS2_REMIND,"11"))
            {
                $SMS_CONTENT    = sprintf(_("OA投票,来自%s:%s"),$_SESSION["LOGIN_USER_NAME"],csubstr($SUBJECT,0,100));
                send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,11);
            }
        }
        //也要更新父级id
        $sql        = "select PARENT_ID from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
        $cursor_p   = exequery(TD::conn(),$sql);
        if($ROW_P=mysql_fetch_array($cursor_p)){
            if($ROW_P['PARENT_ID'] != 0){
                exequery(TD::conn(),"update VOTE_TITLE set PUBLISH='1' where VOTE_ID='{$ROW_P['PARENT_ID']}'");
            }
        }
        $query = "update VOTE_TITLE set PUBLISH='1' where VOTE_ID='$VOTE_ID'";
    }
    else
    {
        $flag_publish = "0";
    }
}
else if($OPERATION == 2)//立即终止
{
    $query = "update VOTE_TITLE set END_DATE='$CUR_DATE' where VOTE_ID='$VOTE_ID' or PARENT_ID='$VOTE_ID'";
}
else//恢复生效
{
    $query = "update VOTE_TITLE set END_DATE='0000-00-00' where VOTE_ID='$VOTE_ID'";
}

if($query != "")
{
    if($_SESSION["LOGIN_USER_PRIV"]!="1")
    {
        $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    }
    exequery(TD::conn(),$query);
}
if($flag_publish == "0" && !$flag_childvote && $TYPE != "2")
{
    Message(_("提示"),_("投票项目为空时，无法发布！"));
    ?>
    <br><div align="center"><input type="button"  value="<?=_("添加投票项目")?>" class="BigButton" onClick="location='item/index.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>'">&nbsp;&nbsp;<input type="button"  value="<?=_("添加子投票")?>" class="BigButton" onClick="location='vote.php?PARENT_ID=<?=$VOTE_ID?>&start=<?=$start?>'"></div>
    <?
}
else
{
    header("location: index1.php?start=$start&IS_MAIN=1");
}
?>
</body>
</html>
