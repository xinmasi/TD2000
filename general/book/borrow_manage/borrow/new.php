<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����Ǽ�");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TO_ID.value=="")
   { alert("<?=_("�����˲���Ϊ�գ�")?>");
     return (false);
   }
   
   if(document.form1.BOOK_NO.value=="")
   { alert("<?=_("��Ų���Ϊ�գ�")?>");
     return (false);
   }
}

function LoadWindow2()
{
	temp=document.form1.TO_ID.value;
    var userAgent = navigator.userAgent.toLowerCase();
    var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
    var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
    var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
    var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
    URL="bookno_select/?USER_ID=" + temp +"&LEND_FLAG=" +1;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
        window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
    }
    else
    {
        event =arguments.callee.caller.arguments[0];
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
        window.open(URL,"parent","status=0,resizable=yes,top="+loc_y+",left="+loc_x+",dialog=yes,modal=yes,dependent=yes,minimizable=no,toolbar=no,menubar=no,location=no,scrollbars=yes",true);
    }
  
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("����Ǽ�")?></span>
    </td>
  </tr>
</table>
<br>
<br>

<table class="TableBlock"  width="400" align="center" >
  <form action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�����ˣ�")?></td>
    <td nowrap class="TableData">
      <input type="hidden" name="TO_ID" value=""> 	
      <input type="text" name="TO_NAME" size="13" class="BigStatic" value="" readonly>&nbsp;
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('51','','TO_ID', 'TO_NAME')" title="<?=_("ָ��������")?>"><?=_("ָ��")?></a><br>
     </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ͼ���ţ�")?></td>
    <td class="TableData">
      <input type="text" name="BOOK_NO" class="BigStatic" size="13" maxlength="100" readonly value="<?=$BOOK_NO?>">&nbsp;
      <input type="button" value="<?=_("ѡ��")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("ѡ��ͼ����")?>" name="button">
    </td> 
   </tr>
<?
$CUR_DATE=date("Y-m-d",time());
$END_DATE = date("Y-m-d",dateadd("d",30,$CUR_DATE));
?>
   <tr>
    <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
    <td nowrap class="TableData">
      <input type="text" name="BORROW_DATE" size="10" maxlength="19" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
     <?=_("Ϊ��Ϊ��ǰ����")?>
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�黹���ڣ�")?></td>
    <td nowrap class="TableData">
      <input type="text" name="RETURN_DATE" size="10" maxlength="19" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()">
       <?=_("Ϊ��Ϊ�ӽ���֮����30�������")?>
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("��ע��")?></td>
    <td nowrap class="TableData">
      <textarea name="BORROW_REMARK" class="BigInput" cols="35" rows="3"><?=$BORROW_REMARK?></textarea>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" title="<?=_("���������Ϣ")?>" name="button">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
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