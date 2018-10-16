<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("保存投票");
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>




<body class="bodycolor">

<?
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("生效日期格式不对，应形如 1999-1-2"));
        ?>

        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>'">
        </div>

        <?
        exit;
    }
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("终止日期格式不对，应形如 1999-1-2"));
        ?>

        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location=' new.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>'">
        </div>

        <?
        exit;
    }
}

$MAX_NUM=intval($MAX_NUM);
$MIN_NUM=intval($MIN_NUM);

$CUR_DATE=date("Y-m-d H:i:s",time());

if($BEGIN_DATE=="")
    $BEGIN_DATE=$CUR_DATE;

if($END_DATE!="")
{
    if(compare_date($BEGIN_DATE,$END_DATE)==1)
    {
        Message(_("错误"),_("生效日期不能晚于终止日期"));
        ?>

        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>'">
        </div>

        <?
        exit;
    }
}
else
    $END_DATE="0000-00-00 00:00:00";



$where="";
if($PARENT_ID!="")
{
    $where=" AND VOTE_ID!='$VOTE_ID'";
}

$sql="SELECT VOTE_ID FROM vote_title WHERE SUBJECT = '$SUBJECT' AND PARENT_ID = '$PARENT_ID'".$where;
$cursor= exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
    Message(_("错误"),_("投票标题重复"));
    Button_Back();
    exit;
}

$SEND_TIME=date("Y-m-d H:i:s",time());

if($ANONYMITY=="on")
    $ANONYMITY='1';
else
    $ANONYMITY='0';

if($TOP=="on")
    $TOP='1';
else
    $TOP='0';
if($BEGIN_DATE!="")
    $BEGIN_DATE=$BEGIN_DATE.date(" H:i:s",time());
if($VOTE_ID=="")
{
    $query="insert into VOTE_TITLE(FROM_ID,PARENT_ID,TO_ID,PRIV_ID,USER_ID,SUBJECT,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,TYPE,MAX_NUM,MIN_NUM,ANONYMITY,VIEW_PRIV,PUBLISH,VOTE_NO,ATTACHMENT_ID,ATTACHMENT_NAME,TOP,VIEW_RESULT_PRIV_ID,VIEW_RESULT_USER_ID) values ('".$_SESSION["LOGIN_USER_ID"]."','$PARENT_ID','$TO_ID','$PRIV_ID','$COPY_TO_ID','$SUBJECT','$CONTENT','$SEND_TIME','$BEGIN_DATE','$END_DATE','$TYPE','$MAX_NUM','$MIN_NUM','$ANONYMITY','$VIEW_PRIV','$PUBLISH','$VOTE_NO','$ATTACHMENT_ID','$ATTACHMENT_NAME','$TOP','$VIEW_RESULT_PRIV_ID','$VIEW_RESULT_USER_ID')";

}
else
{
    if($VOTE_COUNT_CLEAR == "0")
    {
        $query="UPDATE vote_title SET TOP='$TOP',PARENT_ID='$PARENT_ID',TO_ID='$TO_ID',PRIV_ID='$PRIV_ID',USER_ID='$COPY_TO_ID',SUBJECT='$SUBJECT',CONTENT='$CONTENT',SEND_TIME='$SEND_TIME',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',TYPE='$TYPE',MAX_NUM='$MAX_NUM',MIN_NUM='$MIN_NUM',ANONYMITY='$ANONYMITY',VIEW_PRIV='$VIEW_PRIV',PUBLISH='$PUBLISH',VOTE_NO='$VOTE_NO',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',VIEW_RESULT_PRIV_ID='$VIEW_RESULT_PRIV_ID',VIEW_RESULT_USER_ID='$VIEW_RESULT_USER_ID' WHERE VOTE_ID='$VOTE_ID'";
//       $sql="update VOTE_ITEM set VOTE_COUNT=0,VOTE_USER='' where VOTE_ID='$VOTE_ID'";
    }
    elseif($VOTE_COUNT_CLEAR == "1")
    {
        $query="UPDATE vote_title SET TOP='$TOP',PARENT_ID='$PARENT_ID',TO_ID='$TO_ID',PRIV_ID='$PRIV_ID',USER_ID='$COPY_TO_ID',SUBJECT='$SUBJECT',CONTENT='$CONTENT',SEND_TIME='$SEND_TIME',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',TYPE='$TYPE',MAX_NUM='$MAX_NUM',MIN_NUM='$MIN_NUM',ANONYMITY='$ANONYMITY',VIEW_PRIV='$VIEW_PRIV',PUBLISH='$PUBLISH',VOTE_NO='$VOTE_NO',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',READERS='',VIEW_RESULT_PRIV_ID='$VIEW_RESULT_PRIV_ID',VIEW_RESULT_USER_ID='$VIEW_RESULT_USER_ID'  WHERE VOTE_ID='$VOTE_ID'";
        $str_vote_sub_item="SELECT VOTE_ID FROM vote_title WHERE PARENT_ID='$VOTE_ID';";
        $res_vote_sub_item=exequery(TD::conn(), $str_vote_sub_item);
        $str_vote_sub_item_ids='';
        while($row=mysql_fetch_array($res_vote_sub_item))
        {
            if($str_vote_sub_item_ids == '')
            {
                $str_vote_sub_item_ids="'".$row['VOTE_ID']."'";
            }
            else
            {
                $str_vote_sub_item_ids .= ",'".$row['VOTE_ID']."'";
            }
        }
        if($str_vote_sub_item_ids != '')
        {
            $sql="UPDATE vote_item SET VOTE_COUNT=0,VOTE_USER='' WHERE VOTE_ID IN ($str_vote_sub_item_ids);";
            exequery(TD::conn(), $sql);
            $sql="DELETE FROM vote_data WHERE ITEM_ID IN ($str_vote_sub_item_ids);";
            exequery(TD::conn(), $sql);
        }
        $sql="UPDATE vote_item SET VOTE_COUNT=0,VOTE_USER='' WHERE VOTE_ID='$VOTE_ID'";
        exequery(TD::conn(), $sql);
        $sql="DELETE FROM vote_data WHERE ITEM_ID='$VOTE_ID'";
        exequery(TD::conn(), $sql);
    }
}
exequery(TD::conn(),$query);


if($VOTE_ID=="")
    $VOTE_ID=mysql_insert_id();
if($TO_ID=="ALL_DEPT")
    $query="select USER_ID from USER where NOT_LOGIN = 0";
else
    $query="select USER_ID from USER where NOT_LOGIN = 0 and (find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$COPY_TO_ID') or find_in_set(DEPT_ID,'$TO_ID'))";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
    $USER_ID_STR.=$ROW["USER_ID"].",";

//辅助角色
$MY_ARRAY=explode(",",$PRIV_ID);
$ARRAY_COUNT=sizeof($MY_ARRAY);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    if($MY_ARRAY[$I]=="")
        continue;
    $query="select USER_ID from USER where NOT_LOGIN = 0 and find_in_set('$MY_ARRAY[$I]',USER_PRIV_OTHER)";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        if(!find_id($USER_ID_STR,$ROW["USER_ID"]))
            $USER_ID_STR.=$ROW["USER_ID"].",";
    }
}
//辅助部门
$MY_ARRAY_DEPT=explode(",",$TO_ID);
$ARRAY_COUNT_DEPT=sizeof($MY_ARRAY_DEPT);
for($I=0;$I<$ARRAY_COUNT_DEPT;$I++)
{
    if($MY_ARRAY_DEPT[$I]=="")
        continue;
    $query_d="select USER_ID from USER where NOT_LOGIN = 0 and find_in_set('$MY_ARRAY_DEPT[$I]',DEPT_ID_OTHER)";
    $cursor_d=exequery(TD::conn(),$query_d);
    while($ROWD=mysql_fetch_array($cursor_d))
    {
        if(!find_id($USER_ID_STR,$ROWD["USER_ID"]))
            $USER_ID_STR.=$ROWD["USER_ID"].",";
    }
}
//排除没有投票菜单权限的人
$USER_ID_STR_ARRAY=explode(",",$USER_ID_STR);
$USER_ID_STR_ARRAY_COUNT=sizeof($USER_ID_STR_ARRAY);
for($I=0;$I<$USER_ID_STR_ARRAY_COUNT;$I++)
{
    if($USER_ID_STR_ARRAY[$I]=="")
        continue;

    $FUNC_ID_STR=GetfunmenuByuserID($USER_ID_STR_ARRAY[$I]);
    if(!find_id($FUNC_ID_STR,148))
        $USER_ID_STR=str_replace($USER_ID_STR_ARRAY[$I],'',$USER_ID_STR);

}
//.............

//------- 事务提醒 --------
if($SMS_REMIND=="on"&&$PUBLISH=="1"&&$OP!="0")
{
    $SMS_CONTENT=_("请查看投票！")."\n"._("标题：").csubstr($SUBJECT,0,100);

    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;
    $REMIND_URL="1:vote/show/read_vote.php?VOTE_ID=".$VOTE_ID;
    if(mb_detect_encoding($SMS_CONTENT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SMS_CONTENT = stripslashes($SMS_CONTENT);
    }
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,"11",$SMS_CONTENT,$REMIND_URL,$VOTE_ID,$VOTE_ID);
}

if($SMS2_REMIND=="on" && $PUBLISH=="1" && $OP!="0")
{
    if(compare_date($BEGIN_DATE,$CUR_DATE)!=0)
        $SEND_TIME=$BEGIN_DATE;

    $SMS_CONTENT= sprintf(_("OA投票,来自%s:%s"),$_SESSION["LOGIN_USER_NAME"],csubstr($SUBJECT,0,100));
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,11);
}

if($PARENT_ID!="" && $PARENT_ID!="0")
{
    header("location: vote.php?PARENT_ID=$PARENT_ID&start=$start&IS_MAIN=1");
}
else
{
    if($OP=="0")
    {
        header("location: new.php?VOTE_ID=$VOTE_ID&start=$start&IS_MAIN=1");
    }
    else
    {
        Message(_("提示"),_("投票保存成功！"));
        ?>
        <br><div align="center">
        <?
        if($TYPE!="2"){
            ?>
            <input type="button" value="<?=_("添加投票项目")?>" class="BigButton" onClick=location="item/index.php?VOTE_ID=<?=$VOTE_ID?>&IS_MAIN=1">&nbsp;&nbsp;
            <?
        }
        ?>
        <input type="button" value="<?=_("添加子投票")?>" class="BigButton" onClick=location="vote.php?PARENT_ID=<?=$VOTE_ID?>&IS_MAIN=1">&nbsp;&nbsp;<input type="button" value="<?=_("返回")?>" class="BigButton" onClick=location="index1.php?IS_MAIN=1"></div>
        <?
    }
}

?>
</body>
</html>
