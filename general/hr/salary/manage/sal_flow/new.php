<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新建工资流程");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function CheckForm()
{
   if(document.form1.BEGIN_DATE.value=="")
   {
	   alert("<?=_("上报起始日期不能为空！")?>");
	   return false;
   }

   if(document.form1.END_DATE.value=="")
   {
	   alert("<?=_("上报截止日期不能为空！")?>");
	   return false;
   }
   //document.getElementByName('form1').submit();
   document.getElementById("aunt").disabled = 'disabled';
}
</script>


<body class="bodycolor">
<?
$CUR_YEAR=date("Y",time());
$CUR_MONTH=date("m",time()); 
$CUR_DATE=date("Y-m-d",time());
$CUR_MON=date("m",time());

if(substr($CUR_MON,0,1)==0)
   $CUR_MON=substr($CUR_MON,1,strlen($CUR_MON)-1);
if(!isset($FLOW_ID))	 {
   $LABEL1=_("新建工资流程");
   $BUTTON1=_("新建");
   $BEGIN_DATE=$CUR_DATE;
   $END_DATE=$CUR_DATE;
   $CONTENT=$CUR_MON._("月份工资");
   $SAL_YEAR=$CUR_YEAR;
   $SAL_MONTH=$CUR_MONTH;
}
else {
  $query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    	//print_r($ROW);
    	$LABEL1=_("编辑工资流程");
    	$BUTTON1=_("保存");
    	$BEGIN_DATE=$ROW["BEGIN_DATE"];
    	$END_DATE=$ROW["END_DATE"];
    	$SAL_YEAR=$ROW["SAL_YEAR"];
    	$SAL_MONTH=$ROW["SAL_MONTH"];    	
    	
    	if($END_DATE=="0000-00-00 00:00:00" || $END_DATE=="1980-01-01 00:00:00") $END_DATE="";
    	   $CONTENT=$ROW["CONTENT"];
  }
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"><?=$LABEL1?></span>
    </td>
  </tr>
</table>

<form action="add.php" method="post" name="form1" onSubmit="return CheckForm();">
  <table width="400" align="center" class="TableBlock">
    <tr>
      <td nowrap class="TableData"><?=_("起始日期：")?></td>
      <td class="TableData"><input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()"/></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("截止日期：")?></td>
      <td class="TableData"><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("工资月份：")?></td>
      <td class="TableData">
      	<?=_("年度：")?>
    	<select name="SAL_YEAR" id="SAL_YEAR" class="SmallSelect">
<?
        for($I=2000;$I<=2100;$I++)
        {
?>
          <option value="<?=$I?>" <? if($I==$SAL_YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
        }
?>
        </select>      	

      	<?=_("月份：")?>
        <select name="SAL_MONTH" id="SAL_MONTH" class="SmallSelect">
<?
        for($I=1;$I<=12;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$SAL_MONTH) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
        }
?>
        </select>      	
      </td>
    </tr>    
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData">
      	<textarea name="CONTENT" cols="20" rows="2" class="BigInput" value=""><?=$CONTENT?></textarea>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData">
<?=sms_remind(4);?>
      </td>
    </tr>
   <tfoot align="center" class="TableFooter">
      <td nowrap colspan="2" align="center">
      	<input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
      	<input type="submit" value="<?=$BUTTON1?>" class="BigButton" name="button" id="aunt">&nbsp;&nbsp;&nbsp;
      	<input type="button" value="<?=_("返回")?>" class="BigButton" name="button" onClick="location='index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>'";></td>
    </tfoot>
</table>
</form>