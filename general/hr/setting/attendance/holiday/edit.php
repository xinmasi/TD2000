<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("编辑日期");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.BEGIN_DATE.value=="")
   { alert("<?=_("起始日期不能为空！")?>");
     return (false);
   }
   if(document.form1.END_DATE.value=="")
   { alert("<?=_("结束日期不能为空！")?>");
     return (false);
   }
   var pattern = new RegExp(/^\s+$/);
  var re=pattern.test(document.form1.HOLIDAY_NAME.value);
 if(re)
 { alert("<?=_("节假日名称不能为空！")?>");
   return (false);
 }
   return true;
}

</script>


<?
 $query = "SELECT * from ATTEND_HOLIDAY where HOLIDAY_ID='$HOLIDAY_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $HOLIDAY_NAME=$ROW["HOLIDAY_NAME"];
}
?>

<body class="attendance">
<h5 class="attendance-title"><?=_("编辑日期")?></h5>
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
  <table class="table table-small table-bordered" align="center" style="width: 40% !important;">

   <tr>
    <td nowrap class="TableData"><?=_("起始日期：")?></td>
    <td nowrap class="TableData">



        <input type="text" name="BEGIN_DATE" class="" size="10" maxlength="10" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">


        <!--<input type="text" name="BEGIN_DATE" class="" size="10" maxlength="10" value="<?=$BEGIN_DATE?>">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" border="0" style="cursor:hand" onclick="td_calendar('form1.BEGIN_DATE');">-->
    </td>
   <tr>
    <td nowrap class="TableData"><?=_("结束日期：")?></td>
    <td nowrap class="TableData">

         <input type="text" name="END_DATE" class="" size="10" maxlength="10" value="<?=$END_DATE?>" onClick="WdatePicker()">


        <!--<input type="text" name="END_DATE" class="" size="10" maxlength="10" value="<?=$END_DATE?>">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" border="0" style="cursor:hand" onclick="td_calendar('form1.END_DATE');">-->
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("节假日名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="HOLIDAY_NAME" class="" size="13" maxlength="10" value="<?=$HOLIDAY_NAME?>">
    </td>
   </tr>
   <tr>
    <td nowrap  class="" colspan="2" align="center">
        <input type="hidden" value="<?=$HOLIDAY_ID?>" name="HOLIDAY_ID">
        <input type="submit" value="<?=_("确定")?>" class="btn btn-primary" style="margin-left: 30%;">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="btn" style="margin-left: 10%;" onclick="location='index.php'">
    </td>
  </table>
</form>

</body>
</html>