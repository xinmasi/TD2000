<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("培训计划详细信息");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("培训计划详细信息")?></span><br>
        </td>
    </tr>
</table>
<?

//修改事务提醒状态--yc
update_sms_status('61',$T_PLAN_ID);

$query = "SELECT * from HR_TRAINING_PLAN where T_PLAN_ID='$T_PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PLAN_COUNT++;

    $T_PLAN_ID=$ROW["T_PLAN_ID"];
    $T_PLAN_NO=$ROW["T_PLAN_NO"];
    $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
    $T_CHANNEL=$ROW["T_CHANNEL"];
    $T_BCWS=$ROW["T_BCWS"];
    $COURSE_START_TIME=$ROW["COURSE_START_TIME"];
    $COURSE_END_TIME=$ROW["COURSE_END_TIME"];
    $ASSESSING_OFFICER=$ROW["ASSESSING_OFFICER"];
    $ASSESSING_TIME=$ROW["ASSESSING_TIME"];
    $ASSESSING_VIEW=$ROW["ASSESSING_VIEW"];
    $ASSESSING_STATUS=$ROW["ASSESSING_STATUS"];
    $T_JOIN_NUM=$ROW["T_JOIN_NUM"];
    $T_JOIN_DEPT=$ROW["T_JOIN_DEPT"];
    $T_JOIN_PERSON=$ROW["T_JOIN_PERSON"];
    $T_REQUIRES=$ROW["T_REQUIRES"];
    $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
    $T_INSTITUTION_INFO=$ROW["T_INSTITUTION_INFO"];
    $T_INSTITUTION_CONTACT=$ROW["T_INSTITUTION_CONTACT"];
    $T_INSTITU_CONTACT_INFO=$ROW["T_INSTITU_CONTACT_INFO"];
    $T_COURSE_NAME=$ROW["T_COURSE_NAME"];
    $SPONSORING_DEPARTMENT=$ROW["SPONSORING_DEPARTMENT"];
    $CHARGE_PERSON=$ROW["CHARGE_PERSON"];
    $COURSE_HOURS=$ROW["COURSE_HOURS"];
    $COURSE_PAY=$ROW["COURSE_PAY"];
    $T_COURSE_TYPES=$ROW["T_COURSE_TYPES"];
    $T_DESCRIPTION=$ROW["T_DESCRIPTION"];
    $REMARK=$ROW["REMARK"];
    $T_ADDRESS=$ROW["T_ADDRESS"];
    $T_CONTENT=$ROW["T_CONTENT"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    $T_COURSE_TYPES=get_hrms_code_name($T_COURSE_TYPES,"T_COURSE_TYPE");

    $SPONSORING_DEPARTMENT_NAME=substr(GetDeptNameById($ROW["SPONSORING_DEPARTMENT"]),0,-1);
    $CHARGE_PERSON_NAME=substr(GetUserNameById($ROW["CHARGE_PERSON"]),0,-1);
    $ASSESSING_OFFICER_NAME=substr(GetUserNameById($ROW["ASSESSING_OFFICER"]),0,-1);
    $T_JOIN_PERSON_NAME=GetUserNameById($ROW["T_JOIN_PERSON"]);

    if($T_JOIN_DEPT=="ALL_DEPT")
        $T_JOIN_DEPT_NAME=_("全体部门");
    else
        $T_JOIN_DEPT_NAME=GetDeptNameById($T_JOIN_DEPT);

    if($T_CHANNEL=="0")
        $T_CHANNEL=_("内部培训");
    if($T_CHANNEL=="1")
        $T_CHANNEL=_("渠道培训");

    if($COURSE_START_TIME=="0000-00-00 00:00:00")
        $COURSE_START_TIME="";
    if($COURSE_END_TIME=="0000-00-00 00:00:00")
        $COURSE_END_TIME="";

    if($ASSESSING_STATUS=="0")
        $ASSESSING_STATUS=_("待审批");
    if($ASSESSING_STATUS=="1")
        $ASSESSING_STATUS=_("审批已通过");
    if($ASSESSING_STATUS=="2")
        $ASSESSING_STATUS=_("审批未通过");
    ?>
    <table class="TableBlock" width="90%" align="center">
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训计划编号：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180" width="180"><?=$T_PLAN_NO?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训计划名称：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_PLAN_NAME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训渠道：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_CHANNEL?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训形式：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$T_COURSE_TYPES?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("主办部门：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$SPONSORING_DEPARTMENT_NAME?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("负责人：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$CHARGE_PERSON_NAME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("计划参与培训人数：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_JOIN_NUM?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训地点：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$T_ADDRESS?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训机构名称：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$T_INSTITUTION_NAME?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训机构联系人：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_INSTITUTION_CONTACT?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训机构相关信息：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$T_INSTITUTION_INFO?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训机构联系人相关信息：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_INSTITU_CONTACT_INFO?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训课程名称：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$T_COURSE_NAME?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("总课时：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$COURSE_HOURS?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("开课时间：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$COURSE_START_TIME?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("结课时间：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$COURSE_END_TIME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训预算：")?></td>
            <td nowrap nowrap align="left" class="TableData" width="180"><?=$T_BCWS?></td>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("审批人：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$ASSESSING_OFFICER_NAME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("审批时间：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$ASSESSING_TIME=="0000-00-00 00:00:00"?"":$JOB_BEGINNING;?></td>

            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("审批状态：")?></td>
            <td nowrap align="left" class="TableData" width="180"><?=$ASSESSING_STATUS?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("审批意见：")?></td>
            <td nowrap align="left" class="TableData" colspan="3"><?=$ASSESSING_VIEW?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("参与培训部门：")?></td>
            <td nowrap align="left" class="TableData" colspan="3"><?=$T_JOIN_DEPT_NAME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("参与培训人员：")?></td>
            <td nowrap nowrap align="left" class="TableData" colspan="3"><?=$T_JOIN_PERSON_NAME?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训要求：")?></td>
            <td nowrap align="left" class="TableData" colspan="3"><?=$T_REQUIRES?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训说明：")?></td>
            <td nowrap align="left" class="TableData" colspan="3"><?=$T_DESCRIPTION?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("培训内容：")?></td>
            <td nowrap align="left" class="TableData" colspan="3"><?=$T_CONTENT?></td>
        </tr>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
            <td nowrap nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
        </tr>
        <?
        $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
        if($ATTACH_ARRAY["NAME"]!="")
        {
        ?>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("附件文档")?>:</td> <br>
            <td  class="TableData" colspan="3"> <?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1)?></td>
        </tr>
        <?
        }

        if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
        {
        ?>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent">
                <?=_("附件图片")?>: <br><br>
            </td>
            <td  class="TableData" colspan="3">
                <?
                $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
                $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
                $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
                for($I=0;$I<$ARRAY_COUNT;$I++)
                {
                    if($ATTACHMENT_ID_ARRAY[$I]=="")
                        continue;

                    $MODULE=attach_sub_dir();
                    $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
                    $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
                    if($YM)
                        $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
                    $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

                    if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
                    {
                        $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                        if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
                        else
                            $WIDTH=100;
                        ?>
                    <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
                        <?
                    }
                }
                ?>
            </td>
        </tr>
        <?
        }
        ?>
        <tr>
            <td nowrap nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
            <td nowrap nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="4">
                <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>"> &nbsp;<input type="button" value="<?=_("打印")?>" class="BigButton" onClick="window.print();" title="<?=_("打印")?>">
            </td>
        </tr>
    </table>
    <?
}
else
    Message("",_("未找到相应记录！"));
?>
</body>

</html>
