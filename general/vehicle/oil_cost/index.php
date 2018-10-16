<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("司机油耗");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    jQuery(document).ready(function(){
        jQuery("#form1").validationEngine({promptPosition:"topRight"});
    });
</script>
<script>
    function auto(count)
    {
        var MILEAGE="MILEAGE_"+count;
        var OIL_COUNT="OIL_COUNT_"+count;
        var PER_OIL_COUNT="PER_OIL_COUNT_"+count;

        var MILEAGE_VALUE=document.getElementById(MILEAGE).value;
        var OIL_COUNT =document.getElementById(OIL_COUNT).value;
        if(OIL_COUNT>0 && MILEAGE_VALUE>0)
            document.getElementById(PER_OIL_COUNT).value=parseFloat(OIL_COUNT/MILEAGE_VALUE);
    }

</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("司机油耗列表")?> </span>
        </td>
    </tr>
</table>
<form name="form2"  method="post">
    <table class="small" width="70%" align="center">
        <tr>
            <td nowrap align="center"><?=_("统计时间：")?>
                <select name="YEAR" class="SmallSelect">

                    <?
                    if($YEAR=="")
                        $CUR_YEAR=date("Y",time());
                    else
                        $CUR_YEAR=$YEAR;
                    if($MONTH=="")
                        $CUR_MONTH=date("m",time());
                    else
                        $CUR_MONTH=$MONTH;

                    $NEXT_MONTH=$CUR_MONTH+1;
                    if($NEXT_MONTH<10)
                        $NEXT_MONTH="0".$NEXT_MONTH;
                    for($I=$CUR_YEAR-5;$I<=$CUR_YEAR+5;$I++)
                    {
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
                        <?
                    }
                    ?>
                </select>

                <select name="MONTH" class="SmallSelect">
                    <?
                    for($J=1;$J<=12;$J++)
                    {
                        if($J<10)
                            $J="0".$J;
                        ?>
                        <option value="<?=$J?>" <? if($J==$CUR_MONTH) echo "selected";?>><?=$J?><?=_("月")?></option>
                        <?
                    }
                    ?>
                </select>&nbsp;&nbsp;
                <input type="hidden" id="OPERATION" name="OPERATION" value="">
                <input type="button" value="<?=_("查询")?>" class="SmallButton" onClick="document.getElementById('OPERATION').value='';document.form2.submit();">
                <input type="button" value="<?=_("导出")?>" class="SmallButton" onClick="document.getElementById('OPERATION').value='excel';document.form2.submit();">
</form>
</td>
</tr>
</table>
<br>
<?

$query = "SELECT * FROM VEHICLE_OIL_USE WHERE MONTH='$CUR_MONTH' and YEAR='$CUR_YEAR'";
$cursor= exequery(TD::conn(),$query);
$EXCEL_HEAD=array(_("司机姓名"),_("里程数(公里)"),_("耗油数(升)"),_("油耗(升/公里)"));
$EXCEL_OUT_ARRAY = array();
if(mysql_num_rows($cursor)!=0)
{
    $cursor1= exequery(TD::conn(),$query);
?>
<form name="form1" id="form1" method="post" action="update.php">
    <table class="TableList" width="350" align="center" name="1">
        <tr class="TableHeader">
            <td nowrap align="center"><?=_("司机姓名")?></td>
            <td nowrap align="center"><?=_("里程数（公里）")?></td>
            <td nowrap align="center"><?=_("耗油数（升）")?></td>
            <td nowrap align="center" size="10"><?=_("油耗（升/公里）")?></td>
        </tr>

        <?
        $COUNT=0;
        while($ROW=mysql_fetch_array($cursor1))
        {
            $COUNT++;
            if($ROW['DRIVER']=='')
                continue;
            ?>
            <tr class="TableLine2">
                <td nowrap align="center"><input class="BigInput" id="DRIVER_<?=$COUNT?>" name="DRIVER_<?=$COUNT?>" type="text" readonly="true" value="<?=$ROW['DRIVER']?>"></td>
                <td nowrap align="center"><input class="BigInput validate[required,custom[nonNegative]]" data-prompt-position="topRight:0,-6" id="MILEAGE_<?=$COUNT?>" name="MILEAGE_<?=$COUNT?>" type="text" value="<?=$ROW['MILEAGE']=="0"? "":$ROW['MILEAGE']?>"></td>
                <td nowrap align="center"><input class="BigInput validate[required,custom[nonNegative]]" data-prompt-position="topRight:0,-6" id="OIL_COUNT_<?=$COUNT?>" name="OIL_COUNT_<?=$COUNT?>" type="text" onblur="auto(<?=$COUNT?>)" value="<?=$ROW['OIL_USE']=="0"? "":$ROW['OIL_USE']?>" size="10"></td>
                <td nowrap align="center"><input class="BigInput " id="PER_OIL_COUNT_<?=$COUNT?>" name="PER_OIL_COUNT_<?=$COUNT?>" type="text" readonly="true" value="<?=$ROW['PER_OIL_USE']=="0"?"":$ROW['PER_OIL_USE']?>" size="10"></td>
                <td nowrap align="center"><input name="ID_<?=$COUNT?>" type="hidden" value="<?=$ROW['ID']?>"></td>
            </tr>
            <?
            $EXCEL_OUT = "";
            $EXCEL_OUT.=format_cvs($ROW['DRIVER']).",";
            $EXCEL_OUT.=format_cvs($ROW['MILEAGE']).",";
            $EXCEL_OUT.=format_cvs($ROW['OIL_USE']).",";
            $EXCEL_OUT.=format_cvs($ROW['PER_OIL_USE']);
            $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
        }
        }
        else
        {
        $query = "SELECT DISTINCT(VU_DRIVER) FROM VEHICLE_USAGE";
        $cursor= exequery(TD::conn(),$query);
        ?>
        <table class="TableList" width="350" align="center" name="2">
            <tr class="TableHeader">
                <td nowrap align="center"><?=_("司机姓名")?></td>
                <td nowrap align="center"><?=_("里程数（公里）")?></td>
                <td nowrap align="center"><?=_("耗油数（升）")?></td>
            </tr>
            <form name="form1" method="post" action="add.php">
                <?
                $COUNT=0;
                while($ROW=mysql_fetch_array($cursor))
                {
                    $COUNT++;
                    $MILEAGE=0;
                    $DRIVER = $ROW['VU_DRIVER'];
                    if($DRIVER=='')
                        continue;
                    $query1 = "SELECT * from VEHICLE_USAGE where VU_DRIVER='$DRIVER' and VU_START>='$CUR_YEAR-$CUR_MONTH' and VU_START<='$CUR_YEAR-$NEXT_MONTH'";
                    $cursor1= exequery(TD::conn(),$query1);
                    while($ROW=mysql_fetch_array($cursor1))
                    {
                        $VU_MILEAGE_TRUE=$ROW["VU_MILEAGE_TRUE"];
                        $VU_MILEAGE=$ROW["VU_MILEAGE"];
                        if($VU_MILEAGE_TRUE!="")
                            $TRUE_MILES=$VU_MILEAGE_TRUE;
                        else
                            $TRUE_MILES=$VU_MILEAGE;
                        $MILEAGE += $TRUE_MILES;
                    }
                    ?>
                    <tr class="TableLine2">
                        <td nowrap align="center"><input class="BigInput" name="DRIVER_<?=$COUNT?>" type="text" readonly="true" value="<?=$DRIVER?>"></td>
                        <td nowrap align="center"><input class="BigInput" name="MILEAGE_<?=$COUNT?>" type="text" value="<?=$ROW['MILEAGE']=="0"? "":$MILEAGE?>"></td>
                        <td nowrap align="center"><input class="BigInput" name="OIL_COUNT_<?=$COUNT?>" type="text" value="<?=$ROW['OIL_USE']=="0"? "":$ROW['OIL_USE']?>" size="10"></td>
                    </tr>
                    <?
                    $EXCEL_OUT = "";
                    $EXCEL_OUT.=format_cvs($ROW['DRIVER']).",";
                    $EXCEL_OUT.=format_cvs($ROW['MILEAGE']).",";
                    $EXCEL_OUT.=format_cvs($ROW['OIL_USE']).",";
                    $EXCEL_OUT.=format_cvs($ROW['PER_OIL_USE']);
                    $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
                }
                }
                ?>
        </table>
        <table class="TableContol" width="350" align="center">
            <tr>
                <td nowrap align="center">
                    <input type="hidden" value="<?=$CUR_YEAR?>" name="YEAR">
                    <input type="hidden" value="<?=$CUR_MONTH?>" name="MONTH">
                    <input type="hidden" value="<?=$COUNT?>" name="COUNT">
                    <input type="submit" value="<?=_("保存")?>" class="BigButton"></td>
            </tr>
</form>
</table>
</body>
</html>
<?
if($OPERATION=="excel")
{
    ob_end_clean();
    require_once ('inc/ExcelWriter.php');

    $objExcel = new ExcelWriter();
    $objExcel->setFileName(_("油耗统计报表"));
    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>