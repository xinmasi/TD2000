<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");
include_once("inc/itask/itask.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
//2013-04-11 ����������ѯ
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
    //�����һ����ͶƱ����ĿΪ�գ����ɷ���
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

        //------- �������� --------
        $SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
        $PARA_VALUE     = $SYS_PARA_ARRAY["SMS_REMIND"];
        $REMIND_ARRAY   = explode("|", $PARA_VALUE);
        $SMS_REMIND     = $REMIND_ARRAY[0];
        $SMS2_REMIND    = $REMIND_ARRAY[1];
        $ALLOW_SMS      = $REMIND_ARRAY[2];

        $USER_ID_STR = '';   //������Χ�ڵ������û�
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
            //������ɫ
            $MY_ARRAY   = explode(",",$PRIV_ID);
            $ARRAY_COUNT= sizeof($MY_ARRAY);
            for($I=0;$I<$ARRAY_COUNT;$I++)
            {
                if($MY_ARRAY[$I] != "")
                {
                    $where_str .= " or find_in_set('".$MY_ARRAY[$I]."',USER_PRIV_OTHER)";
                }
            }
            //��������
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

        //�ų�û��ͶƱ�˵�Ȩ�޵���
        $USER_PRIV_STR          = '';
        $USER_PRIV_OTHER_SQL    = '';
        $query2     = "select USER_PRIV from USER_PRIV where find_in_set('148', FUNC_ID_STR)";
        $cursor2    = exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $USER_PRIV_STR .= $ROW2["USER_PRIV"].",";   //����ɫ
            $USER_PRIV_OTHER_SQL .= " or find_in_set('".$ROW2["USER_PRIV"]."',USER_PRIV_OTHER)";   //������ɫ
        }

        $USER_ID_STR_148            = '';  //������148�˵�Ȩ�޵��û�
        $NOT_LOGIN_USER_ID_STR_148  = '';  //������148�˵�Ȩ�޵Ľ�ֹ��¼�û�
        if($USER_PRIV_STR != '')
        {
            $query2     = "select USER_ID from USER where NOT_LOGIN=0 and (find_in_set(USER_PRIV, '$USER_PRIV_STR') or ".substr($USER_PRIV_OTHER_SQL, 4).")";
            $cursor2    = exequery(TD::conn(),$query2);
            while($ROW2=mysql_fetch_array($cursor2))
            {
                $USER_ID_STR_148 .= $ROW2["USER_ID"].",";
            }
        }

        $USER_ID_STR = check_id($USER_ID_STR_148, $USER_ID_STR, true);   //�����ڲ�����������Ա�����з�����Χѡ��������¼���û�����148�˵�Ȩ�޵�
        if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        {
            $SEND_TIME=$BEGIN_DATE;
        }

        if(find_id($ALLOW_SMS,"11") && find_id($SMS_REMIND,"11"))
        {
            $SUBJECT        = gbk_stripslashes($SUBJECT);
            $SMS_CONTENT    = _("��鿴ͶƱ")."\n"._("���⣺").csubstr($SUBJECT,0,100);
            $VOTE_ID_SEND   = $PARENT_ID==0?$VOTE_ID:$PARENT_ID;
            $REMIND_URL     = "1:vote/show/read_vote.php?VOTE_ID=".$VOTE_ID_SEND;
            send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,"11",$SMS_CONTENT,$REMIND_URL,$VOTE_ID_SEND);
            /*$WX_OPTIONS = array(
               "module" => "vote",
               "module_action" => "vote.read",
               "user" => $USER_ID_STR,
               "description"=>$CONTENT,
               "content" =>_("�����µ�ͶƱ��")._("���⣺").$SUBJECT,
               "params" => array(
                   "VOTE_ID" => $VOTE_ID
               )
              );
           WXQY_VOTE($WX_OPTIONS);*/
            if(find_id($SMS2_REMIND,"11"))
            {
                $SMS_CONTENT    = sprintf(_("OAͶƱ,����%s:%s"),$_SESSION["LOGIN_USER_NAME"],csubstr($SUBJECT,0,100));
                send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,11);
            }
        }
        //ҲҪ���¸���id
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
else if($OPERATION == 2)//������ֹ
{
    $query = "update VOTE_TITLE set END_DATE='$CUR_DATE' where VOTE_ID='$VOTE_ID' or PARENT_ID='$VOTE_ID'";
}
else//�ָ���Ч
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
    Message(_("��ʾ"),_("ͶƱ��ĿΪ��ʱ���޷�������"));
    ?>
    <br><div align="center"><input type="button"  value="<?=_("���ͶƱ��Ŀ")?>" class="BigButton" onClick="location='item/index.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>'">&nbsp;&nbsp;<input type="button"  value="<?=_("�����ͶƱ")?>" class="BigButton" onClick="location='vote.php?PARENT_ID=<?=$VOTE_ID?>&start=<?=$start?>'"></div>
    <?
}
else
{
    header("location: index1.php?start=$start&IS_MAIN=1");
}
?>
</body>
</html>
