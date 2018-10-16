<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("退休人员查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function Remind_Report(event)
{
    if(document.form1.QUERY_DATE1.value=="" && document.form1.QUERY_DATE2.value=="")
    {
        alert("<?=_("请确定时间范围")?>");
        return;
    }
    myleft=document.body.scrollLeft+event.clientX-event.offsetX-80;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+140;
    URL="retire.php?QUERY_DATE1="+document.form1.QUERY_DATE1.value+"&QUERY_DATE2="+document.form1.QUERY_DATE2.value;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"discard","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>


<body class="bodycolor">
<?
$CURENT_YEAR = date("Y",time());
$DAY_BEGIN = $CURENT_YEAR."-01-01";
$DAY_END = $CURENT_YEAR."-12-31";
?>
<form method="post" name="form1" enctype="multipart/form-data" action="#">
    <table border="0" width="100%" cellpadding="3" cellspacing="1" align="center" bgcolor="#000000">
        <tr>
            <td class="TableHeader">
                &nbsp;<?=_("退休人员查询")?>&nbsp;
                <?=_("从")?>
                <input type="text" id="start_time" name="QUERY_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$DAY_BEGIN?>" onClick="WdatePicker()"/>
                <?=_("至")?>
                <input type="text" name="QUERY_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$DAY_END?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
                <input type="button" value="<?=_("确定")?>" class="SmallButton" onClick="Remind_Report(event);">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
    <br>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
        <tr>
            <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
        </tr>
    </table>

    <?
    $CUR_YEAR=date("Y");
    $CUR_MONTH=date("n");

    //------退休人员-----
    $SYS_PARA_ARRAY = get_sys_para("RETIRE_AGE");
    $PARA_VALUE=$SYS_PARA_ARRAY["RETIRE_AGE"];

    $AGE_ARRAY=explode(",",$PARA_VALUE);//[0]为男退休年龄，[1]为女退休年龄
    $query1="select *  from HR_STAFF_INFO a
         LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
         LEFT OUTER JOIN DEPARTMENT f ON b.DEPT_ID=f.DEPT_ID";
    $query1.=" where 1=1";
    $query1.=" and b.DEPT_ID!='0' ";
    $cursor= exequery(TD::conn(),$query1);//echo $query1;
    $HRMS_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $DEPT_ID=$ROW["DEPT_ID"];
        $USER_ID=$ROW["USER_ID"];
        $STAFF_SEX=$ROW["STAFF_SEX"];
        $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
        $WORK_STATUS=$ROW["WORK_STATUS"];
        if($STAFF_BIRTH=="0000-00-00"||$STAFF_BIRTH=="1900-01-01")continue;
        if($WORK_STATUS == "02"||$WORK_STATUS == "03"||$WORK_STATUS == "04") continue;
        //if($STAFF_SEX=="0")
        //{
        //    $YEAR_MIN=date("Y",time())-$AGE_ARRAY[0];
        //    $YEAR_BIRTHDAY=date("Y",strtotime($STAFF_BIRTH));
        //    $MONTH_MIN=date("n",time());
        //    $MONTH_BIRTHDAY=date("n",strtotime($STAFF_BIRTH));
        //    if($YEAR_MIN!=$YEAR_BIRTHDAY)
        //       continue;
        //    if($MONTH_MIN!=$MONTH_BIRTHDAY)
        //       continue;
        //}
        //else
        //{
        //  	$YEAR_MIN=date("Y")-$AGE_ARRAY[1];
        //    $YEAR_BIRTHDAY=date("Y",strtotime($STAFF_BIRTH));
        //    $MONTH_MIN=date("n");
        //    $MONTH_BIRTHDAY=date("n",strtotime($STAFF_BIRTH));
        //  	if($YEAR_MIN!=$YEAR_BIRTHDAY)
        //       continue;
        //    if($MONTH_MIN!=$MONTH_BIRTHDAY)
        //       continue;
        //}
        if($STAFF_SEX=="0")
        {
            $YEAR_MIN=date("Y",time())-$AGE_ARRAY[0];
            $YEAR_MIN.=date("-m-d",time());
            if(compare_date($STAFF_BIRTH,$YEAR_MIN)!="-1")
                continue;
        }
        else
        {
            $YEAR_MIN=date("Y",time())-$AGE_ARRAY[1];
            $YEAR_MIN.=date("-m-d",time());
            if(compare_date($STAFF_BIRTH,$YEAR_MIN)!="-1")
                continue;
        }
        $HRMS_COUNT++;
    }
    //echo $HRMS_COUNT;
    //exit;
    if($HRMS_COUNT!=0)
    {
        ?>
        <br>
        <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
            <tr>
                <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" height="20"><span class="big3"> <?=_("本月退休人员")?></span><br>
                </td>
                <td valign="bottom" align="right"><span class="small1"><?=sprintf(_("共%s条信息"), '<span class="big4">'.$HRMS_COUNT.'</span>')?></span>
                </td>
            </tr>
        </table>
        <?
        $HRMS_COUNT=0;
        $query1="select * from HR_STAFF_INFO,USER";
        if($DEPT_ID!="0")
            $query1.=",DEPARTMENT";
        $query1.=" where HR_STAFF_INFO.USER_ID=USER.USER_ID";
        if($DEPT_ID!="0")
            $query1.=" and USER.DEPT_ID=DEPARTMENT.DEPT_ID";
        $query1.=" and USER.DEPT_ID!='0' ";
        $cursor= exequery(TD::conn(),$query1); //echo $query1;

        while($ROW=mysql_fetch_array($cursor))
        {
            $NATIVE_PLACENAME="";
            $USER_ID=$ROW["USER_ID"];
            $USER_NAME=$ROW["USER_NAME"];
            $DEPT_NAME=$ROW["DEPT_NAME"];
            $DEPT_ID=$ROW["DEPT_ID"];
            $STAFF_SEX=$ROW["STAFF_SEX"];
            $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
            $WORK_STATUS=$ROW["WORK_STATUS"];

            $query1 = "SELECT CODE_NAME from HR_CODE where PARENT_NO='AREA'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))$NATIVE_PLACENAME=$ROW["CODE_NAME"];
            if($STAFF_SEX=="0")
            {
                $SEX_DESC=_("男");
            }
            else if($STAFF_SEX=="1")
            {
                $SEX_DESC=_("女");
            }
            else
            {
                $SEX_DESC="";
            }
            if($STAFF_BIRTH=="0000-00-00"||$STAFF_BIRTH=="1900-01-01")
                continue;
            if($WORK_STATUS == "02"||$WORK_STATUS == "03"||$WORK_STATUS == "04")
                continue;
            //if($STAFF_SEX=="0")
            //{
            //   $YEAR_MIN=date("Y",time())-$AGE_ARRAY[0];
            //   $YEAR_BIRTHDAY=date("Y",strtotime($STAFF_BIRTH));
            //   $MONTH_MIN=date("n",time());
            //   $MONTH_BIRTHDAY=date("n",strtotime($STAFF_BIRTH));
            //   if($YEAR_MIN!=$YEAR_BIRTHDAY)
            //      continue;
            //   if($MONTH_MIN!=$MONTH_BIRTHDAY)
            //      continue;
            //}
            //else
            //{
            //   $YEAR_MIN=date("Y",time())-$AGE_ARRAY[1];
            //   $YEAR_BIRTHDAY=date("Y",strtotime($STAFF_BIRTH));
            //   $MONTH_MIN=date("n",time());
            //   $MONTH_BIRTHDAY=date("n",strtotime($STAFF_BIRTH));
            //   if($YEAR_MIN!=$YEAR_BIRTHDAY)
            //      continue;
            //   if($MONTH_MIN!=$MONTH_BIRTHDAY)
            //      continue;
            //}


            if($STAFF_SEX=="0")
            {
                $YEAR_MIN=date("Y",time())-$AGE_ARRAY[0];
                $YEAR_MIN.=date("-m-d",time());
                if(compare_date($STAFF_BIRTH,$YEAR_MIN)!="-1")
                    continue;
            }
            else
            {
                $YEAR_MIN=date("Y",time())-$AGE_ARRAY[1];
                $YEAR_MIN.=date("-m-d",time());
                if(compare_date($STAFF_BIRTH,$YEAR_MIN)!="-1")
                    continue;
            }
            $HRMS_COUNT++;
            if($HRMS_COUNT==1)
            {
                ?>
                <table width="100%" class="TableList">
                <tr class="TableHeader">
                    <td nowrap align="center"><?=_("部门")?></td>
                    <td nowrap align="center"><?=_("姓名")?></td>
                    <td nowrap align="center"><?=_("性别")?></td>
                    <td nowrap align="center"><?=_("出生日期")?></td>
                    <td nowrap align="center"><?=_("操作")?></td>
                </tr>
                <?
            }
            ?>
            <tr class="TableData">
                <td nowrap align="center"><?=$DEPT_NAME?></td>
                <td nowrap align="center"><?=$USER_NAME?></td>
                <td nowrap align="center"><?=$SEX_DESC?></td>
                <td nowrap align="center"><?=$STAFF_BIRTH?></td>
                <td nowrap align="center">
                    <a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                </td>
            </tr>
            <?
        }
        ?>
        </table>
        <?
    }
    else
    {
        ?>
        <br>
        <div align="center">
            <?
            Message("",_("本月无退休人员!"));
            ?>
        </div>
        <?
    }
    if(date("m")=="1")
    {
        ?>
        <div id="mytip1" style="border:1px dotted #DE7293;padding:2px;font-size:10pt;width:100%;position: absolute;bottom:0px"><img src="<?=MYOA_STATIC_SERVER?>/static/images/attention.gif" width="16" height="16" align="absmiddle" /><b><?=_("说明：")?></b><?=sprintf(_("现在时间是%s年1月，请按实际情况设置当前年度的员工年假天数。"), date("Y"))?> &nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:this.parentNode.style.display='none';" title="<?=_("关闭此提示")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absmiddle"></a></div>
        <?
    }
    ?>
</form>
</body>
</html>
