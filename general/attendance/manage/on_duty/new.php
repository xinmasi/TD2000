<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;
if($PAIBAN_ID=="")
   $WIN_TITLE=_("单人排班");
else
   $WIN_TITLE=_("修改排班"); 

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ZHIBANREN.value=="")
   { alert("<?=_("请选择人员！")?>");
     return (false);
   }
   if(document.form1.ZBSJ_E.value.length <= 10 || document.form1.ZBSJ_B.value.length <= 10)
   { alert("<?=_("值班日期后的时间均不能为空！")?>");
     return (false);
   }
   if(document.form1.ZBSJ_B.value!="" && document.form1.ZBSJ_E.value!="" && document.form1.ZBSJ_B.value >= document.form1.ZBSJ_E.value)
   { 
      alert("<?=_("结束时间不能小于开始时间！")?>");
      return (false);
   }
   return (true);
}
function resetTime()
{
   document.form1.ZBSJ_B.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime1()
{
   document.form1.ZBSJ_E.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>

<?
if($PAIBAN_ID!="")
{
   $query = "SELECT * from ZBAP_PAIBAN where PAIBAN_ID='$PAIBAN_ID'";
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
   {
		   $PAIBAN_ID=$ROW["PAIBAN_ID"];
		   $ZHIBANREN=$ROW["ZHIBANREN"];
		   $PAIBAN_TYPE=$ROW["PAIBAN_TYPE"];
		   $ZHIBAN_TYPE=$ROW["ZHIBAN_TYPE"];
		   $ZBSJ_B=$ROW["ZBSJ_B"];
		   $ZBSJ_E=$ROW["ZBSJ_E"]; 
		   $ZBYQ=$ROW["ZBYQ"];
		   $BEIZHU=$ROW["BEIZHU"]; 
		   $PAIBAN_APR=$ROW["PAIBAN_APR"]; 
		   $ANPAI_TIME=$ROW["ANPAI_TIME"];            
   }

   $query = "select USER_NAME from USER where USER_ID='$ZHIBANREN'";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
	    $ZHIBANREN_NAME =$ROW["USER_NAME"];     
}else{
	 $CUR_TIME=date("Y-m-d H:i:s",time());
   if($ZBSJ_B=="" || $ZBSJ_B=="undefined")
      $ZBSJ_B0 = time();
   else
      $ZBSJ_B0 = strtotime(date("Y-m-d",$ZBSJ_B)." ".substr($CUR_TIME,11));
   $ZBSJ_B=date("Y-m-d H:i:s",$ZBSJ_B0);  
   $ZBSJ_E=date("Y-m-d H:i:s",$ZBSJ_B0); 
}
?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-top:5px;">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$WIN_TITLE?></span>
    </td>
  </tr>
</table>
 <table class="TableBlock" width="100%" align="center">
  <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr height="30">
      <td nowrap class="TableData"> <?=_("值班人员：")?></td>
      <td class="TableData">
        <input type="hidden" name="ZHIBANREN" value="<?=$ZHIBANREN?>"> 	
        <input type="text" name="ZHIBANREN_NAME" size="13" class="BigStatic" value="<?=$ZHIBANREN_NAME?>" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('7','','ZHIBANREN', 'ZHIBANREN_NAME')"><?=_("选择")?></a> 
      </td>
      <td nowrap class="TableData"> <?=_("排班类型：")?></td>
      <td class="TableData">
        <select name="PAIBAN_TYPE" style="background: white;" title="<?=_("排班类型可在“系统管理”->“系统代码设置”模块设置。")?>">
          <?=code_list("PAIBAN_TYPE","$PAIBAN_TYPE")?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("值班类型：")?></td>
      <td class="TableData" colspan="3">
      	<select name="ZHIBAN_TYPE" style="background: white;" title="<?=_("值班类型可在“系统管理”->“系统代码设置”模块设置。")?>">
          <?=code_list("ZHIBAN_TYPE","$ZHIBAN_TYPE")?>
        </select>          
      </td>     	  
    </tr>        
    <tr>
      <td nowrap class="TableData"> <?=_("值班日期：")?></td>
      <td class="TableData" colspan="3">
        <?=_("从")?>
        <input type="text" name="ZBSJ_B" size="19" maxlength="19" class="BigInput" value="<?=$ZBSJ_B?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
        <?=_("到")?>
        <input type="text" name="ZBSJ_E" size="19" maxlength="19" class="BigInput" value="<?=$ZBSJ_E?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("值班要求：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="ZBYQ" cols="50" rows="4" class="BigInput"><?=$ZBYQ?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="BEIZHU" cols="50" rows="4" class="BigInput"><?=$BEIZHU?></textarea>
      </td>
    </tr>       
    <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan="3"> 
<?=sms_remind(55);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="4">
        <input type="hidden" name="PAIBAN_ID" value="<?=$PAIBAN_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>