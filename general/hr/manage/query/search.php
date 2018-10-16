<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("check_priv.inc.php");

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
    $start=0;

$HTML_PAGE_TITLE = _("人事档案查询");
include_once("inc/header.inc.php");
?>



<script>
    function delete_user(USER_ID)
    {
        if(confirm("<?=_("确定要删除该人事档案吗？删除后将不可恢复")?>"))
            location = "delete.php?USER_ID="+USER_ID;
    }

    function check_one(el)
    {
        if(!el.checked)
            document.getElementsByName("allbox").item(0).checked=false;
    }

    function check_all()
    {
        for (i=0;i<document.getElementsByName("hrms_select").length;i++)
        {
            if(document.getElementsByName("allbox").item(0).checked)
                document.getElementsByName("hrms_select").item(i).checked=true;
            else
                document.getElementsByName("hrms_select").item(i).checked=false;
        }

        if(i==0)
        {
            if(document.getElementsByName("allbox").item(0).checked)
                document.getElementsByName("hrms_select").checked=true;
            else
                document.getElementsByName("hrms_select").checked=false;
        }
    }
    function delete_mail()
    {
        delete_str="";
        for(i=0;i<document.getElementsByName("hrms_select").length;i++)
        {

            el=document.getElementsByName("hrms_select").item(i);
            if(el.checked)
            {  val=el.value;
                delete_str+=val + ",";
            }
        }

        if(i==0)
        {
            el=document.getElementsByName("hrms_select");
            if(el.checked)
            {  val=el.value;
                delete_str+=val + ",";
            }
        }

        if(delete_str=="")
        {
            alert("<?=_("要删除人事档案，请至少选择其中一条。")?>");
            return;
        }

        msg='<?=_("确认要删除所选人事档案吗？")?>';
        if(window.confirm(msg))
        {
            url="delete.php?USER_ID="+ delete_str +"&start=<?=$start?>";
            location=url;
        }
    }

    function delete_all(condition_query)
    {
        msg='<?=_("确认删除以上信息吗？")?>';
        if(window.confirm(msg))
        {
            URL="delete_result.php?condition_query="+condition_query;
            window.location=URL;
        }
    }

    function delete_cascade(condition_cascade)
    {
        var msg='<?=_("确认删除以上信息吗？确认删除请输入大写字母“OK”")?>\n<?=_("这将删除：1、以上列出的所有档案 2、所有有关的子模块记录")?>';
        if(window.prompt(msg,"")=="OK")
        {
            URL="delete_cascade.php?condition_cascade="+condition_cascade;
            window.location=URL;
        }
    }
</script>
<?
if($is_leave==1)
    $WHERE_STR.=" AND b.DEPT_ID=0 ";
elseif($is_leave==0)
    $WHERE_STR.=" AND b.DEPT_ID!=0 ";

$CONDITION_STR="";
//------------------------合法性校验------------------------
if($AGE_MIN!="")
{
    if($urlstr=="")$urlstr="AGE_MIN=".$AGE_MIN;
    else $urlstr=$urlstr."&AGE_MIN=".$AGE_MIN;
    $STAFF_AGE=intval($AGE_MIN);
    if(!is_int($STAFF_AGE)||$STAFF_AGE<=0)
    {
        Message(_("错误"),_("年龄应为正整数！"));
        Button_Back();
        exit;
    }
    $YEAR_MIN=date("Y",time())-$STAFF_AGE;
    $YEAR_MIN.=date("-m-d",time());
    $CONDITION_STR.=" and a.STAFF_BIRTH<='$YEAR_MIN'";
}
if($AGE_MAX!="")
{
    if($urlstr=="")$urlstr="AGE_MAX=".$AGE_MAX;
    else $urlstr=$urlstr."&AGE_MAX=".$AGE_MAX;
    $STAFF_AGE=intval($AGE_MAX);
    if(!is_int($STAFF_AGE)||$STAFF_AGE<=0)
    {
        Message(_("错误"),_("年龄应为正整数！"));
        Button_Back();
        exit;
    }
    $YEAR_MAX=date("Y",time())-$STAFF_AGE;
    $YEAR_MAX.=date("-m-d",time());
    $CONDITION_STR.=" and a.STAFF_BIRTH>='$YEAR_MAX'";
}
//------------------------ 生成条件字符串 ------------------
if ($TO_ID!="")
{
    if ($TO_ID!="ALL_DEPT")
    {
        $DEPT_ID2=$DEPT_ID=$TO_ID;
        if (substr($DEPT_ID,-1)==",")
            $DEPT_ID=substr($DEPT_ID,0,-1);
        $DEPT_ID="(".$DEPT_ID.")";
        $CONDITION_STR.=" and a.DEPT_ID in $DEPT_ID";
    }
}
if($USER_PRIV!="")
    $CONDITION_STR.=" and c.USER_PRIV='$USER_PRIV'";
if($STAFF_NAME!="")
    $CONDITION_STR.=" and a.STAFF_NAME like '%".$STAFF_NAME."%'";
if($STAFF_E_NAME!="")
    $CONDITION_STR.=" and a.STAFF_E_NAME like '%".$STAFF_E_NAME."%'";
if($WORK_STATUS!="")
    $CONDITION_STR.=" and a.WORK_STATUS='$WORK_STATUS'";
if($STAFF_NO!="")
    $CONDITION_STR.=" and a.STAFF_NO like '%".$STAFF_NO."%'";
if($WORK_NO!="")
    $CONDITION_STR.=" and a.WORK_NO like '%".$WORK_NO."%'";
if($STAFF_SEX!="")
    $CONDITION_STR.=" and a.STAFF_SEX='$STAFF_SEX'";
if($STAFF_CARD_NO!="")
    $CONDITION_STR.=" and a.STAFF_CARD_NO like '%".$STAFF_CARD_NO."%'";
if($BIRTHDAY_MIN!="")
    $CONDITION_STR.=" and a.STAFF_BIRTH>='$BIRTHDAY_MIN'";
if($BIRTHDAY_MAX!="")
    $CONDITION_STR.=" and a.STAFF_BIRTH<='$BIRTHDAY_MAX'";
if($STAFF_NATIONALITY!="")
    $CONDITION_STR.=" and a.STAFF_NATIONALITY like '%".$STAFF_NATIONALITY."%'";
if($STAFF_NATIVE_PLACE!="")
    $CONDITION_STR.=" and a.STAFF_NATIVE_PLACE='$STAFF_NATIVE_PLACE'";
if($STAFF_DOMICILE_PLACE!="")
    $CONDITION_STR.=" and a.STAFF_DOMICILE_PLACE like '%".$STAFF_DOMICILE_PLACE."%'";
if($WORK_TYPE!="")
    $CONDITION_STR.=" and a.WORK_TYPE like '%".$WORK_TYPE."%'";
if($STAFF_MARITAL_STATUS!="")
    $CONDITION_STR.=" and a.STAFF_MARITAL_STATUS='$STAFF_MARITAL_STATUS'";
if($STAFF_HEALTH!="")
    $CONDITION_STR.=" and a.STAFF_HEALTH like '%".$STAFF_HEALTH."%'";
if($STAFF_POLITICAL_STATUS!="")
    $CONDITION_STR.=" and a.STAFF_POLITICAL_STATUS='$STAFF_POLITICAL_STATUS'";
if($ADMINISTRATION_LEVEL!="")
    $CONDITION_STR.=" and a.ADMINISTRATION_LEVEL like '%".$ADMINISTRATION_LEVEL."%'";
if($STAFF_OCCUPATION!="")
    $CONDITION_STR.=" and a.STAFF_OCCUPATION='$STAFF_OCCUPATION'";
if($COMPUTER_LEVEL!="")
    $CONDITION_STR.=" and a.COMPUTER_LEVEL like '%".$COMPUTER_LEVEL."%'";
if($STAFF_HIGHEST_SCHOOL!="")
    $CONDITION_STR.=" and a.STAFF_HIGHEST_SCHOOL='$STAFF_HIGHEST_SCHOOL'";
if($STAFF_HIGHEST_DEGREE!="")
    $CONDITION_STR.=" and a.STAFF_HIGHEST_DEGREE='$STAFF_HIGHEST_DEGREE'";
if($STAFF_MAJOR!="")
    $CONDITION_STR.=" and a.STAFF_MAJOR like '%".$STAFF_MAJOR."%'";
if($GRADUATION_SCHOOL!="")
    $CONDITION_STR.=" and a.GRADUATION_SCHOOL like '%".$GRADUATION_SCHOOL."%'";
if($JOB_POSITION!="")
    $CONDITION_STR.=" and a.JOB_POSITION='$JOB_POSITION'";
if($PRESENT_POSITION!="")
    $CONDITION_STR.=" and a.PRESENT_POSITION='$PRESENT_POSITION'";
if($GRADUATION_MIN!="")
    $CONDITION_STR.=" and a.GRADUATION_DATE>='$GRADUATION_MIN'";
if($GRADUATION_MAX!="")
    $CONDITION_STR.=" and a.GRADUATION_DATE<='$GRADUATION_MAX'";
if($JOIN_PARTY_MIN!="")
    $CONDITION_STR.=" and a.JOIN_PARTY_TIME>='$JOIN_PARTY_MIN'";
if($JOIN_PARTY_MAX!="")
    $CONDITION_STR.=" and a.JOIN_PARTY_TIME<='$JOIN_PARTY_MAX'";
if($BEGINNING_MIN!="")
    $CONDITION_STR.=" and a.JOB_BEGINNING>='$BEGINNING_MIN'";
if($BEGINNING_MAX!="")
    $CONDITION_STR.=" and a.JOB_BEGINNING<='$BEGINNING_MAX'";
if($EMPLOYED_MIN!="")
    $CONDITION_STR.=" and a.DATES_EMPLOYED>='$EMPLOYED_MIN'";
if($EMPLOYED_MAX!="")
    $CONDITION_STR.=" and a.DATES_EMPLOYED<='$EMPLOYED_MAX'";
if($WORK_AGE_MIN!="")
    $CONDITION_STR.=" and a.WORK_AGE>='$WORK_AGE_MIN'";
if($WORK_AGE_MAX!="")
    $CONDITION_STR.=" and a.WORK_AGE<='$WORK_AGE_MAX'";
if($JOB_AGE_MIN!="")
    $CONDITION_STR.=" and a.JOB_AGE>='$JOB_AGE_MIN'";
if($JOB_AGE_MAX!="")
    $CONDITION_STR.=" and a.JOB_AGE<='$JOB_AGE_MAX'";
if($LEAVE_TYPE_MIN!="")
    $CONDITION_STR.=" and a.LEAVE_TYPE>='$LEAVE_TYPE_MIN'";
if($LEAVE_TYPE_MAX!="")
    $CONDITION_STR.=" and a.LEAVE_TYPE<='$LEAVE_TYPE_MAX'";
if($FOREIGN_LANGUAGE1!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE1='$FOREIGN_LANGUAGE1'";
if($FOREIGN_LANGUAGE2!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE2='$FOREIGN_LANGUAGE2'";
if($FOREIGN_LANGUAGE3!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE3='$FOREIGN_LANGUAGE3'";
if($FOREIGN_LEVEL1!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL1='$FOREIGN_LEVEL1'";
if($FOREIGN_LEVEL2!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL2='$FOREIGN_LEVEL2'";
if($FOREIGN_LEVEL3!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL3='$FOREIGN_LEVEL3'";

$query = "SELECT b.USER_ID from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON b.DEPT_ID=d.DEPT_ID
WHERE 1='1' ".$CONDITION_STR.$WHERE_STR.field_where_str("HR_STAFF_INFO",(empty($_POST)?$_GET:$_POST),"a.USER_ID");

$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);
$query = "SELECT *
 from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON a.DEPT_ID=d.DEPT_ID
WHERE  1='1' ".$CONDITION_STR.$WHERE_STR.field_where_str("HR_STAFF_INFO",(empty($_POST)?$_GET:$_POST),"a.USER_ID")." order by c.PRIV_NO,b.USER_NO,b.USER_NAME limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
$condition_query=$query;

?>
<body class="bodycolor">
<?
if($COUNT <= 0)
{
    Message("", _("无符合条件的人事档案"));
    Button_Back();
    exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("人事档案查询结果")?></span><br>
        </td>
        <?
        $QSTRING="";
        foreach ($_POST as $key=> $value)
            $QSTRING.=$key."=".$value."&";

        $THE_FOUR_VAR = $QSTRING."start";

        if($_SERVER['QUERY_STRING'] == "")
        {
            $_SERVER['QUERY_STRING'] = $QSTRING;
        }
        //------------------------ 展现字段的信息 ------------------
        $SYS_PARA_ARRAY = get_sys_para("HR_MANAGER_ARCHIVES");
        $HRMS_OPEN_FIELDS=$SYS_PARA_ARRAY["HR_MANAGER_ARCHIVES"];

        $OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
        $HR_LIST_SQL=str_replace(',', ",a.", trim($OPEN_ARRAY[0],','));
        $FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
        $NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);
        ?>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE,$THE_FOUR_VAR,null,false,1)?></td>
    </tr>
</table>
<table class="TableList" width="100%">
    <thead class="TableHeader">
    <td><?=_("部门")?></td>
    <td><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("用户头像")?></td>
    <td nowrap align="center"><?=_("档案头像")?></td>
    <td nowrap align="center"><?=_("OA角色")?></td>
    <?
    for($I=0;$I<count($FIELD_ARRAY);$I++)
    {
        if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
            continue;
        echo "<td nowrap align='center'>".$NAME_ARRAY[$I]."</td>";
    }
    ?>
    <td width="100"><?=_("操作")?></td>
    </thead>
    <?
    while($ROW = mysql_fetch_array($cursor))
    {
        $DEPT_NAME=$ROW["DEPT_NAME"];
        if($DEPT_NAME===NULL) $DEPT_NAME=_("离职人员/外部人员");
        $USER_ID = $ROW['USER_ID'];
        $STAFF_NO = $ROW['STAFF_NO'];
        $USER_NAME = $ROW['USER_NAME'];
        if($USER_NAME=="")
            $USER_NAME=substr(GetUserNameById($USER_ID),0,-1);
        if($USER_NAME=="")
        {
            $query = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$USER_ID'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
                $USER_NAME=$ROW["STAFF_NAME"];
            $USER_NAME=$USER_NAME."("."<font color='red'>"._("用户已删除")."</font>".")";
        }
        $STAFF_NAME = $ROW['STAFF_NAME'];
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
        $STAFF_EMAIL = $ROW['STAFF_EMAIL'];
        $HOME_ADDRESS = $ROW['HOME_ADDRESS'];
        $JOB_BEGINNING = $ROW['JOB_BEGINNING'];
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
        $ATTACHMENT_NAME = $ROW['ATTACHMENT_NAME'];
        $PHOTO=$ROW["PHOTO"];
        $PHOTO_NAME=$ROW["PHOTO_NAME"];
        $USER_PRIV_NAME_INFO=$ROW['USER_PRIV_NAME'];
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$DEPT_NAME?></td>
            <td align="center"><?=$STAFF_NAME==""?$USER_NAME:$STAFF_NAME?></td>
            <?
            if($PHOTO=="")
            {
                ?>
                <td nowrap align="center" style="font-weight: bold; color: red"><?=_("未上传")?></td>
                <?
            }
            else
            {
                ?>
                <td nowrap align="center"><?=_("已上传")?></td>
                <?
            }
            if($PHOTO_NAME=="")
            {
                ?>
                <td nowrap align="center" style="font-weight: bold; color: red"><?=_("未上传")?></td>
                <?
            }
            else
            {
                ?>
                <td nowrap align="center"><?=_("已上传")?></td>
                <?
            }
            ?>
            <td nowrap align="center"><?=$USER_PRIV_NAME_INFO?></td>
            <?
            for($I=0;$I<count($FIELD_ARRAY);$I++)
            {
                if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
                    continue;
                if($ROW[$FIELD_ARRAY[$I]]=="0000-00-00")
                {
                    $ROW[$FIELD_ARRAY[$I]]="";
                }
                if($FIELD_ARRAY[$I]=="STAFF_SEX")
                {
                    if($ROW[$FIELD_ARRAY[$I]]=="0")
                        $ROW[$FIELD_ARRAY[$I]] = _("男");
                    else
                        $ROW[$FIELD_ARRAY[$I]] = _("女");
                }
                if($FIELD_ARRAY[$I]=="STAFF_MARITAL_STATUS")
                {
                    if($ROW[$FIELD_ARRAY[$I]]=="0")
                        $ROW[$FIELD_ARRAY[$I]] = _("未婚");
                    elseif($ROW[$FIELD_ARRAY[$I]]=="1")
                        $ROW[$FIELD_ARRAY[$I]] = _("已婚");
                    elseif($ROW[$FIELD_ARRAY[$I]]=="1")
                        $ROW[$FIELD_ARRAY[$I]] = _("离异");
                    elseif($ROW[$FIELD_ARRAY[$I]]=="1")
                        $ROW[$FIELD_ARRAY[$I]] = _("丧偶");
                }

                if($FIELD_ARRAY[$I]=="WORK_JOB" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='POOL_POSITION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {
                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                if($FIELD_ARRAY[$I]=="WORK_LEVEL" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='WORK_LEVEL' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {
                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                if($FIELD_ARRAY[$I]=="STAFF_OCCUPATION" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_OCCUPATION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                if($FIELD_ARRAY[$I]=="EMPLOYEE_HIGHEST_DEGREE" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                if($FIELD_ARRAY[$I]=="PRESENT_POSITION" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='PRESENT_POSITION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                if($FIELD_ARRAY[$I]=="STAFF_HIGHEST_DEGREE" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }
                if($FIELD_ARRAY[$I]=="STAFF_HIGHEST_SCHOOL" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_HIGHEST_SCHOOL' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }
                if($FIELD_ARRAY[$I]=="STAFF_TYPE" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='HR_STAFF_TYPE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {
                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }
                if($FIELD_ARRAY[$I]=="WORK_STATUS" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='WORK_STATUS' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {

                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }
                if($FIELD_ARRAY[$I]=="STAFF_POLITICAL_STATUS" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_POLITICAL_STATUS' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {
                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }
                if($FIELD_ARRAY[$I]=="STAFF_NATIVE_PLACE" && $ROW[$FIELD_ARRAY[$I]]!="")
                {
                    $query11="select CODE_NAME from `hr_code` where PARENT_NO='AREA' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
                    $cursor11= exequery(TD::conn(),$query11);
                    if($ROW11=mysql_fetch_array($cursor11))
                    {
                        $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
                    }
                }

                echo "<td nowrap align='center'>".$ROW[$FIELD_ARRAY[$I]]."</td>";
            }
            ?>
            <td align="center">
                <a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            </td>
        </tr>
        <?
    }
    ?>
</table>
<br><center>
    <?
    if($_SESSION["LOGIN_USER_PRIV"]==1)
    {
        if($STAFF_COUNT!="" && $STAFF_COUNT!=0)
        {
            ?>
            <input type="button" class="BigButton" value="<?=_("全部删除")?>" onClick="delete_all('<?=urlencode($condition_query)?>')">&nbsp;&nbsp;
            <input type="button" class="BigButton" value="<?=_("删除废弃档案")?>" title="<?=_("清除已删用户的人事档案")?>" onClick="delete_all('clear_dirty')">&nbsp;&nbsp;
            <input type="button" class="BigButton" value="<?=_("删除档案及其子模块记录")?>" title="<?=_("删除人事档案信息及其子模块记录，如：合同管理等")?>" onClick="delete_cascade('<?=urlencode($condition_query)?>')">&nbsp;&nbsp;
            <?
        }
    }
    ?>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="goBack()"></center>
<script>
    function goBack() {
        var host = location.host;
        <?if($is_leave==1)
        {?>
            location.href ="http://"+host+"/general/hr/manage/query/query.php?is_leave=1";
        <?}
        else if($is_leave==0){?>
            location.href ="http://"+host+"/general/hr/manage/query/query.php?is_leave=0";    
        <?}?>
    }
</script>
</body>
</html>