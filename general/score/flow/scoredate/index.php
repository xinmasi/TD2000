<?
include_once("inc/auth.inc.php");
?>
<script Language="JavaScript">
 function chk(input)
  {
    for(var i=0;i<document.form1.c1.length;i++)
    {
      document.form1.c1[i].checked = false;
    }

    input.checked = true;
    return true;
  }
function CheckForm()
{
    if(document.form1.BEGIN_FROM_DATE.value!="" || document.form1.BEGIN_TO_DATE.value!="")
    {
        if(document.form1.BEGIN_FROM_DATE.value=="" || document.form1.BEGIN_TO_DATE.value=="")
        {
            alert("<?=_("请填写完日期信息！")?>");        
            return (false);
        }
        if(document.form1.BEGIN_TO_DATE.value < document.form1.BEGIN_FROM_DATE.value)
        {
            alert("<?=_("结束日期不能小于开始日期！")?>");        
            return (false);
        }        
    }
    if(document.form1.END_FROM_DATE.value!="" || document.form1.END_TO_DATE.value!="")
    {
        if(document.form1.END_FROM_DATE.value=="" || document.form1.END_TO_DATE.value=="")
        {
            alert("<?=_("请填写完日期信息！")?>");        
            return (false);
        }
        if(document.form1.END_FROM_DATE.value < document.form1.END_TO_DATE.value)
        {
            alert("<?=_("结束日期不能小于开始日期！")?>");        
            return (false);
        }        
    }
  document.form1.submit();
}
</script>

<?
$HTML_PAGE_TITLE = _("考核数据查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">


<?
  $CUR_DATE=date("Y-m-d",time());
  $query = "SELECT count(*) from SCORE_FLOW where END_DATE<='$CUR_DATE' and  END_DATE <> '0000-00-00' order by SEND_TIME desc";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $HISTORY_COUNT=$ROW[0];
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("考核数据查询")?></span>
    </td>
  </tr>
</table>
<br>
<div align="center">

<table class="TableBlock" align="center" >
  <form action="list.php"  method="post" name="form1">
  <tr>
    <td nowrap class="TableContent"><?=_("考核任务名称：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="FLOWTITLE" class="BigInput">
    </td>
  </tr>

  <tr>
      <td nowrap class="TableContent"><?=_("考核人：")?></td>
      <td class="TableData">
        <input type="hidden" name="SECRET_TO_ID" value="<?=$RANKMAN?>">
        <textarea cols=40 name="SECRET_TO_NAME" rows="2" class="BigStatic" wrap="yes" readonly><?=$RAN_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('124','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>

    <tr>
      <td nowrap class="TableContent"><?=_("被考核人：")?></td>
      <td class="TableData">
        <input type="hidden" name="PARTICIPANT_TO_ID" value="<?=$PARTICIPANT?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$PARTI_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('124','','PARTICIPANT_TO_ID', 'TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PARTICIPANT_TO_ID', 'TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableContent"><?=_("考核指标集：")?></td>
      <td class="TableData">
         <select name="GROUP" class="BigSelect">
             <option value="">   </option>
<?
               $query = "SELECT * from SCORE_GROUP";
               $cursor= exequery(TD::conn(),$query);
               while($ROW=mysql_fetch_array($cursor))
              {
                $GROUPID=$ROW["GROUP_ID"];
                $GROUPNAME=$ROW["GROUP_NAME"];
?>
               <option value="<?=$GROUPID?>"><?=$GROUPNAME?></option>
<?
              }
?>
        </select>
      </td>
    </tr>

   <tr>
    <td nowrap class="TableContent"><?=_("生效日期：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="BEGIN_FROM_DATE" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$DATE?>" onClick="WdatePicker()"/>&nbsp;
    &nbsp;<?=_("至")?>&nbsp;
       <input type="text" name="BEGIN_TO_DATE" size="15" maxlength="10" class="BigInput" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>&nbsp;
    </td>

   </tr>
      <tr>
    <td nowrap class="TableContent"><?=_("终止日期：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="END_FROM_DATE" size="15" maxlength="10" class="BigInput" id="stop_start_time" value="<?=$DATE?>" onClick="WdatePicker()"/>&nbsp;
    &nbsp;<?=_("至")?>&nbsp;
        <input type="text" name="END_TO_DATE" size="15" maxlength="10" class="BigInput" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'stop_start_time\')}'})"/>&nbsp;
    </td>
   </tr>

   <tr>
      <td nowrap class="TableContent"> <?=_("查询范围：")?></td>
      <td class="TableData">


         <input type="checkbox" name="c1" id="Id_c1" value="1" onClick="return chk(this);"><label for="Id_c1"><?=_("全部")?></label>&nbsp;&nbsp;
         <input type="checkbox" name="c1" id="Id_c2" value="2" onClick="return chk(this);" checked><label for="Id_c2"><?=_("已终止")?></label>&nbsp;&nbsp;

      </td>
    </tr>
   <tfoot align="center" class="TableFooter">
    <td nowrap colspan="2">
        <input type="button" value="<?=_("查询")?>" class="BigButton" title="<?=_("模糊查询")?>" onClick="return CheckForm();">
    </td>
   </tfoot>
  </form>
</table>

</div>

</body>
</html>