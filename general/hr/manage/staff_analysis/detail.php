<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("人事档案查询");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("详细情况")?> </span><br></td>
        <td align="right" valign="bottom" class="small1"></td>
    </tr>
</table>
<?
$CUR_DATE=date("Y-m-d",time());
if($STAFF_ID_STR!="")
{
    $query = "SELECT b.USER_ID,b.BYNAME,b.USER_NAME,a.STAFF_NO,a.STAFF_NAME,DEPT_NAME,WORK_NO,STAFF_AGE,STAFF_SEX,STAFF_CARD_NO,STAFF_BIRTH
    from HR_STAFF_INFO a
    LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
    LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
    LEFT OUTER JOIN DEPARTMENT d ON a.DEPT_ID=d.DEPT_ID
   WHERE a.STAFF_ID in ($STAFF_ID_STR)";
    $cursor=exequery(TD::conn(),$query);
    $COUNT = mysql_num_rows($cursor);
    if($COUNT <= 0)
    {
        Message("", _("无符合条件的人事档案"));
        Button_Back();
        exit;
    }
    ?>
    <table class="TableList" width="100%">
        <thead class="TableHeader">
        <td><?=_("部门")?></td>
        <td>OA<?=_("用户名")?></td>
        <td><?=_("姓名")?></td>
        <td><?=_("编号")?></td>
        <td><?=_("工号")?></td>
        <td><?=_("年龄")?></td>
        <td><?=_("性别")?></td>
        <td width="100"><?=_("操作")?></td>
        </thead>
        <?
        while($ROW = mysql_fetch_array($cursor))
        {
            $USER_ID = $ROW['USER_ID'];
			$BYNAME  = $ROW['BYNAME'];
            $STAFF_NO = $ROW['STAFF_NO'];
            $USER_NAME=$ROW["USER_NAME"];
            if($USER_NAME===NULL)
                $USER_NAME="<font color=\"red\">"._("用户已删除")."</font>";
            $DEPT_NAME=$ROW["DEPT_NAME"];
            if($DEPT_NAME===NULL)
                $DEPT_NAME=_("离职人员/外部人员");
            $WORK_NO = $ROW['WORK_NO'];
            $STAFF_SEX = $ROW['STAFF_SEX'];
            $STAFF_CARD_NO = $ROW['STAFF_CARD_NO'];
            $STAFF_BIRTH = $ROW['STAFF_BIRTH'];

            $STAFF_NATIVE_PLACE = $ROW['STAFF_NATIVE_PLACE'];
            $STAFF_NATIONALITY = $ROW['STAFF_NATIONALITYX'];
            $STAFF_MARITAL_STATUS = $ROW['STAFF_MARITAL_STATUS'];
            $STAFF_POLITICAL_STATUS = $ROW['STAFF_POLITICAL_STATUS'];
            $WORK_STATUS = $ROW['WORK_STATUS'];
            $ATTACHMENT = $ROW['ATTACHMENT'];
            $JOIN_PARTY_TIME = $ROW['JOIN_PARTY_TIME'];
            $STAFF_PHONE = $ROW['STAFF_PHONE'];
            $STAFF_MOBILE = $ROW['STAFF_MOBILE'];
            $STAFF_LITTLE_SMART = $ROW['STAFF_LITTLE_SMART'];
            $STAFF_MSN = $ROW['STAFF_MSN'];
            $STAFF_QQ = $ROW['STAFF_QQ'];
            $STAFF_NAME = $ROW['STAFF_NAME'];

            $STAFF_EMAIL = $ROW['STAFF_EMAIL'];
            $HOME_ADDRESS = $ROW['HOME_ADDRESS'];
            $JOB_BEGINNING = $ROW['JOB_BEGINNING'];
            $OTHER_CONTACT = $ROW['OTHER_CONTACT'];
            $WORK_AGE = $ROW['WORK_AGE'];
            $STAFF_HEALTH = $ROW['STAFF_HEALTH'];
            $STAFF_DOMICILE_PLACE = $ROW['STAFF_DOMICILE_PLACE'];
            $STAFF_HIGHEST_SCHOOL = $ROW['STAFF_HIGHEST_SCHOOL'];
            $STAFF_HIGHEST_DEGREE = $ROW['STAFF_HIGHEST_DEGREE'];
            $GRADUATION_SCHOOL = $ROW['GRADUATION_SCHOOL'];
            $STAFF_MAJOR = $ROW['STAFF_MAJOR'];
            $COMPUTER_LEVEL = $ROW['COMPUTER_LEVEL'];
            $FOREIGN_LANGUAGE1 = $ROW['FOREIGN_LANGUAGE1'];
            $FOREIGN_LANGUAGE2 = $ROW['FOREIGN_LANGUAGE2'];
            $FOREIGN_LANGUAGE3 = $ROW['FOREIGN_LANGUAGE3'];
            $FOREIGN_LEVEL1 = $ROW['FOREIGN_LEVEL1'];
            $FOREIGN_LEVEL2 = $ROW['FOREIGN_LEVEL2'];
            $FOREIGN_LEVEL3 = $ROW['FOREIGN_LEVEL3'];
            $STAFF_SKILLS = $ROW['STAFF_SKILLS'];
            $DEPT_ID = $ROW['DEPT_ID'];
            $WORK_TYPE = $ROW['WORK_TYPE'];
            $ADMINISTRATION_LEVEL = $ROW['ADMINISTRATION_LEVEL'];
            $JOB_POSITION = $ROW['JOB_POSITION'];
            $PRESENT_POSITION = $ROW['PRESENT_POSITION'];
            $DATES_EMPLOYED = $ROW['DATES_EMPLOYED'];
            $JOB_AGE = $ROW['JOB_AGE'];
            $BEGIN_SALSRY_TIME = $ROW['BEGIN_SALSRY_TIME'];
            $STAFF_OCCUPATION = $ROW['STAFF_OCCUPATION'];
            $REMARK = $ROW['REMARK'];
            $ATTACHMENT_NAME = $ROW['ATTACHMENT_NAME'];
            ?>
            <tr class="TableData">
                <td nowrap align="center"><?=$DEPT_NAME?></td>
                <td align="center"><?=$BYNAME?></td>
                <td align="center"><?=$STAFF_NAME?></td>
                <td align="center"><?=$STAFF_NO?></td>
                <td align="center"><?=$WORK_NO?></td>
                <?
                if($STAFF_BIRTH!="0000-00-00")
                {
                    $agearray = explode("-",$STAFF_BIRTH);
                    $cur = explode("-",$CUR_DATE);
                    $year=$agearray[0];
                    $STAFF_AGE=date("Y")-$year;
                    if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
                    {
                        $STAFF_AGE++;

                    }
                }
                else
                {
                    $STAFF_AGE="";
                }
                ?>
                <td align="center"><?=$STAFF_AGE?></td>
                <td align="center"><?if($STAFF_SEX=="0") echo _("男");else if($STAFF_SEX=="1") echo _("女");else echo _("未填写");?></td>
                <td align="center">
                    <a href="javascript:;" onClick="window.open('../query/staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                </td>
            </tr>
            <?
        }
        ?>
    </table>
    <?
}
if($CONTRACT_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_CONTRACT where CONTRACT_ID in ($CONTRACT_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $CONTRACT_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $CONTRACT_COUNT++;

        $CONTRACT_ID=$ROW["CONTRACT_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $STAFF_CONTRACT_NO=$ROW["STAFF_CONTRACT_NO"];
        $CONTRACT_TYPE=$ROW["CONTRACT_TYPE"];
        $MAKE_CONTRACT=$ROW["MAKE_CONTRACT"];
        $STATUS=$ROW["STATUS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
        $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");


        if($CONTRACT_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("合同编号")?></td>
            <td nowrap align="center"><?=_("合同类型")?></td>
            <td nowrap align="center"><?=_("合同签订时间")?></td>
            <td nowrap align="center"><?=_("合同状态")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$STAFF_CONTRACT_NO?></td>
            <td nowrap align="center"><?=$CONTRACT_TYPE?></td>
            <td nowrap align="center"><?=$MAKE_CONTRACT?></td>
            <td nowrap align="center"><?=$STATUS?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_contract/contract_detail.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($CONTRACT_COUNT==0)
    {
        Message("",_("无符合条件的合同信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($INCENTIVE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_INCENTIVE where INCENTIVE_ID in ($INCENTIVE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $INCENTIVE_COUNT++;

        $INCENTIVE_ID=$ROW["INCENTIVE_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
        $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"];
        $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
        $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");

        if($INCENTIVE_TYPE==1)
            $INCENTIVE_TYPE=_("奖励");
        if($INCENTIVE_TYPE==2)
            $INCENTIVE_TYPE=_("惩罚");

        if($INCENTIVE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("奖惩项目")?></td>
            <td nowrap align="center"><?=_("奖惩日期")?></td>
            <td nowrap align="center"><?=_("奖惩属性")?></td>
            <td nowrap align="center"><?=_("奖惩金额")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$INCENTIVE_ITEM?></td>
            <td nowrap align="center"><?=$INCENTIVE_TIME?></td>
            <td nowrap align="center"><?=$INCENTIVE_TYPE?></td>
            <td nowrap align="center"><?=$INCENTIVE_AMOUNT?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_incentive/incentive_detail.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    if($INCENTIVE_COUNT==0)
    {
        Message("",_("无符合条件的奖惩信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($LICENSE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_LICENSE where LICENSE_ID in ($LICENSE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $LICENSE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LICENSE_COUNT++;

        $LICENSE_ID=$ROW["LICENSE_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $LICENSE_TYPE=$ROW["LICENSE_TYPE"];
        $LICENSE_NO=$ROW["LICENSE_NO"];
        $LICENSE_NAME=$ROW["LICENSE_NAME"];
        $STATUS=$ROW["STATUS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        $LICENSE_TYPE=get_hrms_code_name($LICENSE_TYPE,"HR_STAFF_LICENSE1");
        $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_LICENSE2");


        if($LICENSE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("证照类型")?></td>
            <td nowrap align="center"><?=_("证照编号")?></td>
            <td nowrap align="center"><?=_("证照名称")?></td>
            <td nowrap align="center"><?=_("状态")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>
            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$LICENSE_TYPE?></td>
            <td nowrap align="center"><?=$LICENSE_NO?></td>
            <td nowrap align="center"><?=$LICENSE_NAME?></td>
            <td nowrap align="center"><?=$STATUS?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_license/license_detail.php?LICENSE_ID=<?=$LICENSE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($LICENSE_COUNT==0)
    {
        Message("",_("无符合条件的证照信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($FLAG==1 && $L_EXPERIENCE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where L_EXPERIENCE_ID in ($L_EXPERIENCE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $EXPERIENCE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $EXPERIENCE_COUNT++;

        $L_EXPERIENCE_ID=$ROW["L_EXPERIENCE_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $MAJOR=$ROW["MAJOR"];
        $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
        $ACADEMY_DEGREE=get_hrms_code_name($ACADEMY_DEGREE,'STAFF_HIGHEST_SCHOOL');
        $SCHOOL=$ROW["SCHOOL"];
        $WITNESS=$ROW["WITNESS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        if(strlen($SCHOOL) > 20)
            $SCHOOL=substr($SCHOOL, 0, 20)."...";

        if($EXPERIENCE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("所学专业")?></td>
            <td nowrap align="center"><?=_("所获学历")?></td>
            <td nowrap align="center"><?=_("所在院校")?></td>
            <td nowrap align="center"><?=_("证明人")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$MAJOR?></td>
            <td nowrap align="center"><?=$ACADEMY_DEGREE?></td>
            <td nowrap align="center"><?=$SCHOOL?></td>
            <td nowrap align="center"><?=$WITNESS?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_learn_experience/experience_detail.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($EXPERIENCE_COUNT==0)
    {
        Message("",_("无符合条件的学习经历信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($FLAG==1 && $L_EXPERIENCE_ID_STR=="")
{
    $USER_ID_STR="";
    $query="select distinct STAFF_NAME from HR_STAFF_LEARN_EXPERIENCE";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_ID_STR.=$ROW["STAFF_NAME"].",";
    }
    $USER_ID_STR=td_trim($USER_ID_STR);
    $query="select b.USER_ID,b.BYNAME,b.USER_NAME,a.STAFF_NO,a.STAFF_NAME,DEPT_NAME,WORK_NO,STAFF_AGE,STAFF_SEX,STAFF_CARD_NO,STAFF_BIRTH
				    from HR_STAFF_INFO a
				    LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
				    LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
				    LEFT OUTER JOIN DEPARTMENT d ON a.DEPT_ID=d.DEPT_ID
				    where true and b.DEPT_ID != '0'";
    if($USER_ID_STR!="")
        $query.=" and !find_in_set(a.USER_ID,'$USER_ID_STR')";

    $cursor=exequery(TD::conn(),$query);
    ?>
    <table class="TableList" width="100%">
        <thead class="TableHeader">
        <td><?=_("部门")?></td>
        <td>OA<?=_("用户名")?></td>
        <td><?=_("姓名")?></td>
        <td width="100"><?=_("操作")?></td>
        </thead>
        <?
        while($ROW = mysql_fetch_array($cursor))
        {
            $USER_ID    = $ROW['USER_ID'];
            $BYNAME     = $ROW['BYNAME'];
            $STAFF_NO   = $ROW['STAFF_NO'];
            $USER_NAME  = $ROW["USER_NAME"];
            if($USER_NAME===NULL)
                $USER_NAME="<font color=\"red\">"._("用户已删除")."</font>";
            $DEPT_NAME=$ROW["DEPT_NAME"];
            if($DEPT_NAME===NULL)
                $DEPT_NAME=_("离职人员/外部人员");
            $STAFF_NAME = $ROW['STAFF_NAME'];
            ?>
            <tr class="TableData">
                <td nowrap align="center"><?=$DEPT_NAME?></td>
                <td align="center"><?=$BYNAME?></td>
                <td align="center"><?=$STAFF_NAME?></td>
                <td align="center">
                    <a href="../staff_learn_experience/new.php?STAFF_NAME=<?=$USER_ID ?>"><?=_("新建学习经历")?></a>&nbsp;
                </td>
            </tr>
            <?
        }
        ?>
    </table>
    <?

}
if($W_EXPERIENCE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID in ($W_EXPERIENCE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $EXPERIENCE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $EXPERIENCE_COUNT++;

        $W_EXPERIENCE_ID=$ROW["W_EXPERIENCE_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $WORK_UNIT=$ROW["WORK_UNIT"];
        $MOBILE=$ROW["MOBILE"];
        $POST_OF_JOB=$ROW["POST_OF_JOB"];
        $WITNESS=$ROW["WITNESS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        if(strlen($WORK_UNIT) > 20)
            $WORK_UNIT=substr($WORK_UNIT, 0, 20);

        if($EXPERIENCE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("工作单位")?></td>
            <td nowrap align="center"><?=_("行业类别")?></td>
            <td nowrap align="center"><?=_("担任职务")?></td>
            <td nowrap align="center"><?=_("证明人")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$WORK_UNIT?></td>
            <td nowrap align="center"><?=$MOBILE?></td>
            <td nowrap align="center"><?=$POST_OF_JOB?></td>
            <td nowrap align="center"><?=$WITNESS?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_work_experience/experience_detail.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($EXPERIENCE_COUNT==0)
    {
        Message("",_("无符合条件的人事调动信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($SKILLS_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_LABOR_SKILLS where SKILLS_ID in ($SKILLS_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $SKILLS_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $SKILLS_COUNT++;

        $SKILLS_ID=$ROW["SKILLS_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $ABILITY_NAME=$ROW["ABILITY_NAME"];
        $SKILLS_LEVEL=$ROW["SKILLS_LEVEL"];
        $ISSUE_DATE=$ROW["ISSUE_DATE"];
        $EXPIRES=$ROW["EXPIRES"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        if($SKILLS_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("技能名称")?></td>
            <td nowrap align="center"><?=_("级别")?></td>
            <td nowrap align="center"><?=_("发证日期")?></td>
            <td nowrap align="center"><?=_("有效期")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>
            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$ABILITY_NAME?></td>
            <td nowrap align="center"><?=$SKILLS_LEVEL?></td>
            <td nowrap align="center"><?=$ISSUE_DATE?></td>
            <td nowrap align="center"><?=$EXPIRES?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_labor_skills/skills_detail.php?SKILLS_ID=<?=$SKILLS_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($SKILLS_COUNT==0)
    {
        Message("",_("无符合条件的人事调动信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($RELATIVES_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_RELATIVES where RELATIVES_ID in ($RELATIVES_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $RELATIVES_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $RELATIVES_COUNT++;

        $RELATIVES_ID=$ROW["RELATIVES_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $MEMBER=$ROW["MEMBER"];
        $RELATIONSHIP=$ROW["RELATIONSHIP"];
        $PERSONAL_TEL=$ROW["PERSONAL_TEL"];
        $JOB_OCCUPATION=$ROW["JOB_OCCUPATION"];
        $STAFF_NAME=$ROW["STAFF_NAME"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");

        $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);

        if($RELATIVES_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("单位员工")?></td>
            <td nowrap align="center"><?=_("成员姓名")?></td>
            <td nowrap align="center"><?=_("与本人关系")?></td>
            <td nowrap align="center"><?=_("职业")?></td>
            <td nowrap align="center"><?=_("联系电话（个人）")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$MEMBER?></td>
            <td nowrap align="center"><?=$RELATIONSHIP?></td>
            <td nowrap align="center"><?=$JOB_OCCUPATION?></td>
            <td nowrap align="center"><?=$PERSONAL_TEL?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_relatives/relatives_detail.php?RELATIVES_ID=<?=$RELATIVES_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($RELATIVES_COUNT==0)
    {
        Message("",_("无符合条件的社会关系信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($TRANSFER_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_ID in ($TRANSFER_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $TRANSFER_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $TRANSFER_COUNT++;

        $TRANSFER_ID=$ROW["TRANSFER_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
        $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
        $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
        $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");

        $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
        if($TRANSFER_PERSON_NAME=="")
            $TRANSFER_PERSON_NAME = '<font color=red>'._("用户已删除").'</font>';

        if($TRANSFER_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("调动人员")?></td>
            <td nowrap align="center"><?=_("调动类型")?></td>
            <td nowrap align="center"><?=_("调动日期")?></td>
            <td nowrap align="center"><?=_("调动生效日期")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$TRANSFER_PERSON_NAME?></td>
            <td nowrap align="center"><?=$TRANSFER_TYPE?></td>
            <td nowrap align="center"><?=$TRANSFER_DATE=="0000-00-00"?"":$TRANSFER_DATE;?></td>
            <td nowrap align="center"><?=$TRANSFER_EFFECTIVE_DATE=="0000-00-00"?"":$TRANSFER_EFFECTIVE_DATE;?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_transfer/transfer_detail.php?TRANSFER_ID=<?=$TRANSFER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($TRANSFER_COUNT==0)
    {
        Message("",_("无符合条件的人事调动信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($LEAVE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_LEAVE where LEAVE_ID in ($LEAVE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $LEAVE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_COUNT++;
        $LEAVE_ID=$ROW["LEAVE_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
        $QUIT_TYPE=$ROW["QUIT_TYPE"];
        $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
        $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");

        $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $JOB_POSITION=$ROW1["JOB_POSITION"];

        if($LEAVE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("离职人员")?></td>
            <td nowrap align="center"><?=_("担任职务")?></td>
            <td nowrap align="center"><?=_("离职类型")?></td>
            <td nowrap align="center"><?=_("拟离职日期")?></td>
            <td nowrap align="center"><?=_("工资截止日期")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=substr(GetUserNameById($LEAVE_PERSON),0,-1)?></td>
            <td nowrap align="center"><?=$JOB_POSITION?></td>
            <td nowrap align="center"><?=$QUIT_TYPE?></td>
            <td nowrap align="center"><?=$QUIT_TIME_PLAN=="0000-00-00"?"":$QUIT_TIME_PLAN;?></td>
            <td nowrap align="center"><?=$LAST_SALARY_TIME=="0000-00-00"?"":$LAST_SALARY_TIME;?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_leave/leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    if($LEAVE_COUNT==0)
    {
        Message("",_("无符合条件的员工离职信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($REINSTATEMENT_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_ID in ($REINSTATEMENT_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $REINSTATEMENT_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $REINSTATEMENT_COUNT++;

        $REINSTATEMENT_ID=$ROW["REINSTATEMENT_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $REAPPOINTMENT_TIME_PLAN=$ROW["REAPPOINTMENT_TIME_PLAN"];
        $REAPPOINTMENT_TYPE=$ROW["REAPPOINTMENT_TYPE"];
        $REINSTATEMENT_PERSON=$ROW["REINSTATEMENT_PERSON"];
        $NOW_POSITION=$ROW["NOW_POSITION"];
        $FIRST_SALARY_TIME=$ROW["FIRST_SALARY_TIME"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $REAPPOINTMENT_TYPE=get_hrms_code_name($REAPPOINTMENT_TYPE,"HR_STAFF_REINSTATEMENT");

        $REINSTATEMENT_PERSON_NAME=substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1);

        if($REINSTATEMENT_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("复职人员")?></td>
            <td nowrap align="center"><?=_("担任职务")?></td>
            <td nowrap align="center"><?=_("复职类型")?></td>
            <td nowrap align="center"><?=_("拟复职日期")?></td>
            <td nowrap align="center"><?=_("工资恢复日期")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$REINSTATEMENT_PERSON_NAME?></td>
            <td nowrap align="center"><?=$NOW_POSITION?></td>
            <td nowrap align="center"><?=$REAPPOINTMENT_TYPE?></td>
            <td nowrap align="center"><?=$REAPPOINTMENT_TIME_PLAN=="0000-00-00"?"":$REAPPOINTMENT_TIME_PLAN;?></td>
            <td nowrap align="center"><?=$FIRST_SALARY_TIME=="0000-00-00"?"":$FIRST_SALARY_TIME;?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_reinstatement/reinstatement_detail.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($REINSTATEMENT_COUNT==0)
    {
        Message("",_("无符合条件的员工复职信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($EVALUATION_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_TITLE_EVALUATION where EVALUATION_ID in ($EVALUATION_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $CARE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $EVALUATION_COUNT++;

        $EVALUATION_ID=$ROW["EVALUATION_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $POST_NAME=$ROW["POST_NAME"];
        $GET_METHOD=$ROW["GET_METHOD"];
        $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
        $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
        $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
        $ADD_TIME=$ROW["ADD_TIME"];

        $GET_METHOD=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");

        $BY_EVALU_NAME=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1);
        $APPROVE_PERSON_NAME=substr(GetUserNameById($APPROVE_PERSON),0,-1);

        if($EVALUATION_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("评定对象")?></td>
            <td nowrap align="center"><?=_("批准人")?></td>
            <td nowrap align="center"><?=_("获取职称")?></td>
            <td nowrap align="center"><?=_("获取方式")?></td>
            <td nowrap align="center"><?=_("获取时间")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$BY_EVALU_NAME?></td>
            <td nowrap align="center"><?=$APPROVE_PERSON_NAME?></td>
            <td nowrap align="center"><?=$POST_NAME?></td>
            <td nowrap align="center"><?=$GET_METHOD?></td>
            <td nowrap align="center"><?=$RECEIVE_TIME=="0000-00-00"?"":$RECEIVE_TIME;?></td>
            <td align="center">
                <a href="javascript:;" onClick="window.open('../staff_title_evaluation/evaluation_detail.php?EVALUATION_ID=<?=$EVALUATION_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }

    if($EVALUATION_COUNT==0)
    {
        Message("",_("无符合条件的职称评定信息！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
if($CARE_ID_STR!="")
{
    $query = "SELECT * from HR_STAFF_CARE where CARE_ID in ($CARE_ID_STR) order by ADD_TIME desc";
    $cursor=exequery(TD::conn(),$query);
    $CARE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $CARE_COUNT++;

        $CARE_ID=$ROW["CARE_ID"];
        $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
        $BY_CARE_STAFFS=$ROW["BY_CARE_STAFFS"];
        $CARE_DATE=$ROW["CARE_DATE"];
        $CARE_CONTENT=$ROW["CARE_CONTENT"];
        $PARTICIPANTS=$ROW["PARTICIPANTS"];
        $CARE_EFFECTS=$ROW["CARE_EFFECTS"];
        $CARE_FEES=$ROW["CARE_FEES"];
        $CARE_TYPE=$ROW["CARE_TYPE"];
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
        $ADD_TIME =$ROW["ADD_TIME"];

        $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");

        $BY_CARE_STAFFS_NAME = substr(GetUserNameById($BY_CARE_STAFFS),0,-1);
        $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);

        if($CARE_COUNT==1)
        {
            ?>
            <table class="TableList" width="100%">
            <thead class="TableHeader">
            <td nowrap align="center"><?=_("关怀类型")?></td>
            <td nowrap align="center"><?=_("被关怀员工")?></td>
            <td nowrap align="center"><?=_("关怀开支费用")?></td>
            <td nowrap align="center"><?=_("参与人")?></td>
            <td nowrap align="center"><?=_("关怀日期")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
            </thead>

            <?
        }
        ?>
        <tr class="TableData">
            <td align="center"><?=$TYPE_NAME?></td>
            <td nowrap align="center"><?=$BY_CARE_STAFFS_NAME?></td>
            <td nowrap align="center"><?=$CARE_FEES?>(<?=_("元")?>)</td>
            <td align="center"><?=$PARTICIPANTS_NAME?></td>
            <td nowrap align="center"><?=$CARE_DATE?></td>

            <td nowrap align="center">
                <a href="javascript:;" onClick="window.open('../staff_care/care_detail.php?CARE_ID=<?=$CARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    if($CARE_COUNT==0)
    {
        Message("",_("无符合条件的员工关怀！"));
        Button_Back();
        exit;
    }
    else
    {
        ?>
        </table>
        <?
    }
}
?>
</body>
</html>