<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>

<body class="bodycolor">

<?
$MANAGER=$SECRET_TO_ID;
$PARTICIPATOR=$TCOPY_TO_ID;

//----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("开始日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=<?=$PLAN_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&NAME=<?=$NAME?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>&TYPE=<?=$TYPE?>&MANAGER=<?=$MANAGER?>&PARTICIPATOR=<?=$PARTICIPATOR?>&ATTACHMENT=<?=$ATTACHMENT?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID?>&ATTACHMENT_NAME=<?=$ATTACHMENT_NAME?>&ATTACHMENT_COMMENT=<?=$ATTACHMENT_COMMENT?>&REMARK=<?=$REMARK?>'">
        </div>
        <?
        exit;
    }
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("终止日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=<?=$PLAN_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&NAME=<?=$NAME?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>&TYPE=<?=$TYPE?>&MANAGER=<?=$MANAGER?>&PARTICIPATOR=<?=$PARTICIPATOR?>&ATTACHMENT=<?=$ATTACHMENT?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID?>&ATTACHMENT_NAME=<?=$ATTACHMENT_NAME?>&ATTACHMENT_COMMENT=<?=$ATTACHMENT_COMMENT?>&REMARK=<?=$REMARK?>'">
        </div>
        <?
        exit;
    }
}

$CUR_DATE=date("Y-m-d",time());

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
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=<?=$PLAN_ID?>&TO_ID=<?=$TO_ID?>&TO_NAME=<?=$TO_NAME?>&NAME=<?=$NAME?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&CONTENT=<?=$CONTENT?>&TYPE=<?=$TYPE?>&MANAGER=<?=$MANAGER?>&PARTICIPATOR=<?=$PARTICIPATOR?>&ATTACHMENT=<?=$ATTACHMENT?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID?>&ATTACHMENT_NAME=<?=$ATTACHMENT_NAME?>&ATTACHMENT_COMMENT=<?=$ATTACHMENT_COMMENT?>&REMARK=<?=$REMARK?>'">
        </div>
        <?
        exit;
    }

    $END_DATE="$END_DATE";
}
else
    $END_DATE="null";

if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

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
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CONTENT);
$CONTENT = replace_attach_url($CONTENT);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}
//------------------- 保存 -----------------------

//发布范围（人员）加上批准领导

$CUR_TIME=date("Y-m-d H:i:s",time());
$ADD_TIME=date("Y-m-d H:i:s");
$CAL_ID=1;

$MY_ARRAY=explode(",",$TO_ID4);
$ARRAY_COUNT=sizeof($MY_ARRAY);
if($MY_ARRAY[$ARRAY_COUNT-1]=="")
    $ARRAY_COUNT--;
for($I=0;$I<$ARRAY_COUNT;$I++)
    if(!find_id($TO_ID3,$MY_ARRAY[$I]) && $MY_ARRAY[$I]!=$_SESSION["LOGIN_USER_ID"])
        $TO_ID3.=$MY_ARRAY[$I].",";


if($PLAN_ID==""){
    $query="INSERT INTO WORK_PLAN (NAME,CONTENT,BEGIN_DATE,END_DATE,TYPE,TO_ID,MANAGER,PARTICIPATOR,CREATOR,CREATE_DATE,ATTACHMENT_ID,ATTACHMENT_NAME,ATTACHMENT_COMMENT,REMARK,TO_PERSON_ID,PUBLISH,OPINION_LEADER)
   VALUES ('$NAME','$CONTENT','$BEGIN_DATE','$END_DATE','$TYPE','$TO_ID','$MANAGER','$PARTICIPATOR','$CREATOR','$CREATE_DATE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$ATTACHMENT_COMMENT','$REMARK','$TO_ID3','$PUBLISH','$TO_ID4')";
    exequery(TD::conn(),$query);
    $WORK_PLAN_ID=mysql_insert_id();

    $MANAGER_ARRAY=explode(",",$MANAGER);
    for($I=0;$I< count($MANAGER_ARRAY);$I++)
    {
        if(!find_id(str_replace(" ,","",$PARTICIPATOR),$MANAGER_ARRAY[$I]) && $MANAGER_ARRAY[$I]!="")
            $PARTICIPATOR.= $MANAGER_ARRAY[$I].",";

    }

    $PARTICIPATOR_ARRAY=explode(",",$PARTICIPATOR);
    for($I=0;$I< count($PARTICIPATOR_ARRAY);$I++)
    {
        if($PARTICIPATOR_ARRAY[$I]!="")
        {
            if ($CUR_DATE<$BEGIN_DATE)
                $TASK_STATUS1='1';
            else
                $TASK_STATUS1='2';

            $sql="insert into TASK(USER_ID,TASK_NO,TASK_TYPE,TASK_STATUS,COLOR,IMPORTANT,SUBJECT,EDIT_TIME,BEGIN_DATE,END_DATE,CONTENT,RATE,FINISH_TIME,TOTAL_TIME,USE_TIME,CAL_ID,MANAGER_ID,ADD_TIME,WORK_PLAN_ID)
  VALUES ('$PARTICIPATOR_ARRAY[$I]','0','1','$TASK_STATUS1','','','$NAME','$CUR_TIME','$BEGIN_DATE','$END_DATE','$CONTENT','','0000-00-00','','','$CAL_ID','".$_SESSION["LOGIN_USER_ID"]."','$ADD_TIME','$WORK_PLAN_ID')";
            exequery(TD::conn(),$sql);
        }
    }
}
else
{
    $query="update WORK_PLAN set ";
    if($PUBLISH=="1")
        $query.="CREATE_DATE ='$CUR_DATE',PUBLISH='$PUBLISH',";
    $query.="NAME='$NAME',CONTENT='$CONTENT',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',TYPE='$TYPE',TO_ID='$TO_ID',MANAGER='$MANAGER',PARTICIPATOR='$PARTICIPATOR',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',ATTACHMENT_COMMENT='$ATTACHMENT_COMMENT',REMARK='$REMARK',TO_PERSON_ID='$TO_ID3',OPINION_LEADER='$TO_ID4' where PLAN_ID='$PLAN_ID'";
    exequery(TD::conn(),$query);

    $MANAGER_ARRAY=explode(",",$MANAGER);
    for($I=0;$I< count($MANAGER_ARRAY);$I++)
    {
        if(!find_id(str_replace(" ,","",$PARTICIPATOR),$MANAGER_ARRAY[$I]) && $MANAGER_ARRAY[$I]!="")
            $PARTICIPATOR.= $MANAGER_ARRAY[$I].",";

    }

    $PARTICIPATOR_ARRAY=explode(",",$PARTICIPATOR);
    for($I=0;$I< count($PARTICIPATOR_ARRAY);$I++)
    {
        if($PARTICIPATOR_ARRAY[$I]!=""){

            $sql1="select * from TASK where WORK_PLAN_ID='$PLAN_ID'and USER_ID='$PARTICIPATOR_ARRAY[$I]' and MANAGER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
            $re=exequery(TD::conn(),$sql1);
            if ($ROW1=mysql_fetch_array($re))
            {
                $sql="update TASK set EDIT_TIME='$CUR_TIME',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',CONTENT='$CONTENT',SUBJECT='$NAME',ADD_TIME='$ADD_TIME' WHERE WORK_PLAN_ID='$PLAN_ID'and USER_ID='$PARTICIPATOR_ARRAY[$I]' and MANAGER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
            }else
            {
                if ($CUR_DATE<$BEGIN_DATE)
                    $TASK_STATUS1='1';
                else
                    $TASK_STATUS1='2';

                $sql="insert into TASK(USER_ID,TASK_NO,TASK_TYPE,TASK_STATUS,COLOR,IMPORTANT,SUBJECT,EDIT_TIME,BEGIN_DATE,END_DATE,CONTENT,RATE,FINISH_TIME,TOTAL_TIME,USE_TIME,CAL_ID,MANAGER_ID,ADD_TIME,WORK_PLAN_ID)
                 VALUES ('$PARTICIPATOR_ARRAY[$I]','0','1','$TASK_STATUS1','','','$NAME','$CUR_TIME','$BEGIN_DATE','$END_DATE','$CONTENT','','0000-00-00','','','$CAL_ID','".$_SESSION["LOGIN_USER_ID"]."','$ADD_TIME','$PLAN_ID')";
            }
            exequery(TD::conn(),$sql);
        }
    }
}

if(($SMS_REMIND=="on" || $SMS2_REMIND=="on") && $PUBLISH==1 && $OP==0)
{
    $query="select USER_NAME from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $USER_NAME=$ROW["USER_NAME"];
    $SMS_CONTENT=sprintf(_("%s发布新的工作计划，请注意查看，计划名称：%s"),$USER_NAME,$NAME);
    if(mb_detect_encoding($SMS_CONTENT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SMS_CONTENT = stripslashes($SMS_CONTENT);
    }
    //COPY_TO_ID
    $MY_ARRAY=explode(",",$TCOPY_TO_ID);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    if($MY_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
    for($I=0;$I<$ARRAY_COUNT;$I++){
        if($MY_ARRAY[$I]!=$_SESSION["LOGIN_USER_ID"])
            $TO_ID_STR.=$MY_ARRAY[$I].",";}

    //SECRET_TO_ID
    $MY_ARRAY=explode(",",$SECRET_TO_ID);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    if($MY_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
    for($I=0;$I<$ARRAY_COUNT;$I++)
        if(!find_id($TO_ID_STR,$MY_ARRAY[$I]))
            $TO_ID_STR.=$MY_ARRAY[$I].",";

    //TO_ID3
    $MY_ARRAY=explode(",",$TO_ID3);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    if($MY_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
    for($I=0;$I<$ARRAY_COUNT;$I++)
        if(!find_id($TO_ID_STR,$MY_ARRAY[$I]))
            $TO_ID_STR.=$MY_ARRAY[$I].",";

    $TO_ID=td_trim($TO_ID);
    if($TO_ID!="")
    {
        if($TO_ID != "ALL_DEPT")
            $query="select USER_ID from USER where DEPT_ID in ($TO_ID) and not find_in_set(USER_ID,'$TO_ID_STR')";
        else
            $query="select USER_ID from USER where not find_in_set(USER_ID,'$TO_ID_STR')";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $USER_ID=$ROW["USER_ID"];
            $UID_STR.=$USER_ID.",";
        }
        $TO_ID_STR.=$UID_STR;
    }

}

$workid ="";
if($PLAN_ID=="")
{
    $workid = $WORK_PLAN_ID;
    $REMIND_URL="1:work_plan/show/plan_detail.php?PLAN_ID=".$WORK_PLAN_ID;
}

else
{
    $workid = $PLAN_ID;
    $REMIND_URL="1:work_plan/show/plan_detail.php?PLAN_ID=".$PLAN_ID;
}


if($SMS_REMIND=="on" && $PUBLISH==1 && $OP==0 && $TO_ID_STR!="")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,12,$SMS_CONTENT,$REMIND_URL,$workid);

if($SMS2_REMIND=="on" && $PUBLISH==1 && $OP==0 && $TO_ID_STR!="")
{
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,$SMS_CONTENT,12);
}

if($QUERY_ALTER == 1)
{
    if($PUBLISH == 0)
    {
        if($OP==1)  //$OP=1 上传附件动作
        {
            Message("",_("工作计划保存成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=$PLAN_ID'"></p><?
            // header("location: index.php?PLAN_ID=$PLAN_ID");
        }else
        {
            Message("",_("工作计划保存成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='../search.php'"></p><?
            //header("location: ../search.php");
        }

    }else
    {
        if($OP==1)  //$OP=1 上传附件动作
        {
            Message("",_("工作计划提交成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=$PLAN_ID'"></p><?
            //header("location: index.php?PLAN_ID=$PLAN_ID");
        }else
        {
            Message("",_("工作计划提交成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='../search.php'"></p><?
            //header("location: ../search.php");
        }
    }

}
if($QUERY_ALTER != 1)
{
    if($PUBLISH == 0)
    {
        if($OP==1)  //$OP=1 上传附件动作
        {
            Message("",_("工作计划保存成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=$PLAN_ID'"></p><?
            // header("location: index.php?PLAN_ID=$PLAN_ID");
        }else
        {
            Message("",_("工作计划保存成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='../index1.php'"></p><?
            //header("location: ../index1.php");
        }

    }else
    {
        if($OP==1)  //$OP=1 上传附件动作
        {
            Message("",_("工作计划提交成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PLAN_ID=$PLAN_ID'"></p><?
            // header("location: index.php?PLAN_ID=$PLAN_ID");
        }else
        {
            Message("",_("工作计划提交成功!"));
            ?><P align="center"><input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='../index1.php'"></p><?
            //header("location: ../index1.php");
        }
    }
}

?>

</body>
</html>
