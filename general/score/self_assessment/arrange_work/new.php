<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if($CAL_ID=="")
   $WIN_TITLE=_("新建工作任务");
else
   $WIN_TITLE=_("编辑工作任务");

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.CONTENT.value=="")
   { alert("<?=_("工作内容不能为空！")?>");
     return (false);
   }
   return (true);
}

</script>



<?
$BEGIN_HOUR="09";
$BEGIN_MIN="00";
$END_HOUR="16";
$END_MIN="00";
$CAL_ID=intval($CAL_ID);
 
if($CAL_ID!="")
{
   $query = "SELECT * from CALENDAR where CAL_ID='$CAL_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $CAL_TIME=$ROW["CAL_TIME"];
      $END_TIME=$ROW["END_TIME"];
      $CAL_TYPE=$ROW["CAL_TYPE"];
      $CONTENT=$ROW["CONTENT"];
      $USER_ID=$ROW["USER_ID"];
   }
   
   $YEAR=date("Y",strtotime($CAL_TIME));
   $MONTH=date("m",strtotime($CAL_TIME));
   $DAY=date("d",strtotime($CAL_TIME));
   $BEGIN_HOUR=date("H",strtotime($CAL_TIME));
   $BEGIN_MIN=date("i",strtotime($CAL_TIME));
   $END_HOUR=date("H",strtotime($END_TIME));
   $END_MIN=date("i",strtotime($END_TIME));
}

$query = "select * from USER where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $USER_NAME=$ROW["USER_NAME"];
?>

<body class="bodycolor" onload="document.form1.CONTENT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$WIN_TITLE?>(<?=sprintf(_("%s年%s月%s日"), $YEAR, $MONTH, $DAY)?>)</span>
    </td>
  </tr>
</table>

<br>
 <table border="0" width="450" cellpadding="2" cellspacing="1" align="center" bgcolor="#000000" class="small">
  <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr height="30">
      <td nowrap class="TableData"> <?=_("姓名：")?></td>
      <td class="TableData">
        <?=$USER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("开始时间：")?></td>
      <td class="TableData">
<!-------------- 时 ------------>
        <select name="CAL_HOUR" class="BigSelect">
<?
        for($I=0;$I<=23;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$BEGIN_HOUR) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select><?=_("：")?>

<!-------------- 分 ------------>
        <select name="CAL_MIN" class="BigSelect">
<?
        for($I=0;$I<=59;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$BEGIN_MIN) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("结束时间：")?></td>
      <td class="TableData">

<!-------------- 时 ------------>
        <select name="END_HOUR" class="BigSelect">
<?
        for($I=0;$I<=23;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$END_HOUR) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select><?=_("：")?>

<!-------------- 分 ------------>
        <select name="END_MIN" class="BigSelect">
<?
        for($I=0;$I<=59;$I++)
        {
          if($I<10)
             $I="0".$I;
?>
          <option value="<?=$I?>" <? if($I==$END_MIN) echo "selected";?>><?=$I?></option>
<?
        }
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("工作内容：")?></td>
      <td class="TableData">
        <textarea name="CONTENT" cols="45" rows="5" class="BigInput"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData"> 
<?=sms_remind(5);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
      
        <input type="hidden" name="CAL_YEAR" value="<?=$YEAR?>">
        <input type="hidden" name="CAL_MON" value="<?=$MONTH?>">
        <input type="hidden" name="CAL_DAY" value="<?=$DAY?>">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
        <input type="hidden" name="CAL_ID" value="<?=$CAL_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>