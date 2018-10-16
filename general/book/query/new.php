<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("借书登记");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TO_ID.value=="")
   { alert("<?=_("借书人不能为空！")?>");
     return (false);
   }
   
   if(document.form1.BOOK_NO.value=="")
   { alert("<?=_("编号不能为空！")?>");
     return (false);
   }
}

function LoadWindow2()
{
	temp=document.form1.TO_ID.value;
  URL="bookno_select/?USER_ID=" + temp +"&LEND_FLAG=" +1;
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
 	 window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
  	 window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("借书登记")?></span>
    </td>
  </tr>
</table>
<br>
<br>

<table class="TableBlock"  width="400" align="center" >
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("借书人：")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="TO_ID" value="<?=$_SESSION["LOGIN_USER_ID"]?>"> 	
      <input type="text" name="TO_NAME" size="13" class="BigStatic" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>&nbsp;
     </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("图书编号：")?></td>
    <td class="TableData">
      <input type="text" name="BOOK_NO" class="BigStatic" size="13" maxlength="100" readonly value="<?=$BOOK_NO?>">&nbsp;
    </td> 
   </tr>
<?
$CUR_DATE=date("Y-m-d",time());
$END_DATE = date("Y-m-d",dateadd("d",30,$CUR_DATE));
?>
   <tr>
    <td nowrap class="TableData"><?=_("借书日期：")?></td>
    <td nowrap class="TableData">
      <input type="text" name="BORROW_DATE" size="11" maxlength="19" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
      <?=_("为空为当前日期")?>
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("归还日期：")?></td>
    <td nowrap class="TableData">
      <input type="text" name="RETURN_DATE" size="11" maxlength="19" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()">
      <?=_("为空为从借书之日起30天的日期")?>
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("备注：")?></td>
    <td nowrap class="TableData">
      <textarea name="BORROW_REMARK" class="BigInput" cols="35" rows="3"><?=$BORROW_REMARK?></textarea>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("确定")?>" class="BigButton" title="<?=_("保存借书信息")?>" name="button">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript:window.close();">
    </td>
   </tr>
  </form>
</table>

</body>
</html>
<?
function gettime($d)
{
   if(is_numeric($d))
      return $d;
   else
   {
      if(!is_string($d))
         return 0;
      if(strpos($d, ":") > 0)
      {
         $buf = explode(" ",$d);
         $year = explode("-",$buf[0]);
         $hour = explode(":",$buf[1]);
         if(stripos($buf[2], "pm") > 0)
            $hour[0] += 12;
            
         return mktime($hour[0],$hour[1],$hour[2],$year[1],$year[2],$year[0]);
      }
      else
      {
         $year = explode("-",$d);
         return mktime(0,0,0,$year[1],$year[2],$year[0]);
      }
   }
}


function dateadd($interval, $number, $date)
{
   $date = gettime($date);
   $date_time_array = getdate($date); 
   $hours = $date_time_array["hours"]; 
   $minutes = $date_time_array["minutes"]; 
   $seconds = $date_time_array["seconds"]; 
   $month = $date_time_array["mon"]; 
   $day = $date_time_array["mday"]; 
   $year = $date_time_array["year"]; 
   switch ($interval)
   { 
      case "yyyy": $year +=$number; break; 
      case "q": $month +=($number*3); break; 
      case "m": $month +=$number; break; 
      case "y": 
      case "d": 
      case "w": $day+=$number; break; 
      case "ww": $day+=($number*7); break; 
      case "h": $hours+=$number; break; 
      case "n": $minutes+=$number; break; 
      case "s": $seconds+=$number; break; 
   } 
   $timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year); 
   return $timestamp;
} 
?>