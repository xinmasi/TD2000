<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/chinese_date.php");
include_once("inc/i18n/CDateFormatter.php");
ob_end_clean();

$SYS_INTERFACE = TD::get_cache("SYS_INTERFACE");
$BANNER_TEXT=$SYS_INTERFACE["BANNER_TEXT"];
$BANNER_FONT=$SYS_INTERFACE["BANNER_FONT"];
$ATTACHMENT_ID=$SYS_INTERFACE["ATTACHMENT_ID"];
$ATTACHMENT_NAME=$SYS_INTERFACE["ATTACHMENT_NAME"];
$IMG_WIDTH=$SYS_INTERFACE["IMG_WIDTH"];
$IMG_HEIGHT=$SYS_INTERFACE["IMG_HEIGHT"];
$WEATHER_CITY=$SYS_INTERFACE["WEATHER_CITY"];
$SHOW_RSS=$SYS_INTERFACE["SHOW_RSS"];

if(strstr($BANNER_FONT, "?"))
   $BANNER_FONT = substr($BANNER_FONT, 0, strpos($BANNER_FONT, "?"));

if(strpos($WEATHER_CITY, "_") !== FALSE || $SHOW_RSS=="1")
{
   include_once("inc/utility_cache.php");
   
   $ROW = GetUserInfoByUID($_SESSION["LOGIN_UID"], "WEATHER_CITY,SHOW_RSS");
   if(strpos($WEATHER_CITY, "_") !== FALSE)
      $WEATHER_CITY=$ROW["WEATHER_CITY"]==""? $WEATHER_CITY: $ROW["WEATHER_CITY"];
   if($SHOW_RSS=="1")
      $SHOW_RSS_USER=$ROW["SHOW_RSS"];
}

//天气
$WEATHER_HTML = '';
if(strpos($WEATHER_CITY, "_") !== FALSE)
{
   include_once("inc/weather.inc.php");
   $WEATHER_CACHE = TD::get_cache("WEATHER_CACHE_".bin2hex($WEATHER_CITY));
   if(is_array($WEATHER_CACHE) && isset($WEATHER_CACHE['a']))
   {
      $WEATHER_HTML = $WEATHER_CACHE['a'];
   }
   else
   {
      $query = "SELECT TASK_URL from OFFICE_TASK where TASK_CODE='get_external_data'";
      $cursor= exequery(TD::conn(), $query);
      if($ROW = mysql_fetch_array($cursor))
      {
         $TASK_URL=$ROW["TASK_URL"];
         
         include_once("inc/itask/itask.php");
         itask(array("EXEC_HTTP_TASK ".$TASK_URL));
      }
   }
}

include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/topbar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/plugin.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script language="JavaScript">
<?
list($CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND) = DateTimeEx(hexdec(dechex(time()+1)));
//$CUR_YEAR="2007";
//$CUR_MON="11";
//$CUR_DAY="8";
$TIME_STR="$CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND";
?>
var OA_TIME = new Date(<?=$TIME_STR?>);
function mdate()
{
   var solarTerm=sTerm(<?=$CUR_YEAR?>,<?=$CUR_MON?>,<?=$CUR_DAY?>);
   if(solarTerm != "")
      $('mdate').innerHTML = solarTerm;
}
function timeview()
{
  window.setTimeout( "timeview()", 1000 );
  
  document.getElementById('time_area').innerHTML = OA_TIME.toTimeString().substr(0,5);
  OA_TIME.setSeconds(OA_TIME.getSeconds()+1);
}

window.setInterval("window.location.reload()", 3600*1000);
</script>
<?
if(strpos($WEATHER_CITY, "_") !== FALSE)
{
?>
<script language="JavaScript">
function GetWeather(beUpdate)
{
   var WEATHER_CITY = $('w_county').options[$('w_county').selectedIndex].value;
   if(WEATHER_CITY.length != 6)
   {
      alert("<?=_("请选择城市")?>");
      return;
   }

   var w_province = $('w_province').options[$('w_province').selectedIndex].text;
   var w_city = $('w_city').options[$('w_city').selectedIndex].text;
   var w_county = $('w_county').options[$('w_county').selectedIndex].text;
   var WEATHER_CITY = [w_province, w_city, w_county].join("_");

   $('weather').innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("加载中，请稍候……")?>";
   _get("/inc/weather.php", "WEATHER_CITY="+ encodeURIComponent(WEATHER_CITY) +"&UPDATE="+beUpdate, function(req){
      if(req.responseText.substr(0, 6)=="error:")
         $('weather').innerHTML=req.responseText.substr(6)+" <a href=\"javascript:GetWeather();\"><?=_("刷新")?></a> <a href=\"#\" onclick=\"$('area_select').style.display='block';$('weather').style.display='none';\"><?=_("更改城市")?></a>";
      else
         $('weather').innerHTML=req.responseText;
   });
   
   $('area_select').style.display='none';
   $('weather').style.display='block';
}
</script>
<?
}
?>


<body STYLE="margin:0px;padding:0px" onLoad="mdate();timeview();<?if(strpos($WEATHER_CITY, "_") !== FALSE){?>InitProvince(ConvertWeatherCity('<?=$WEATHER_CITY?>'));if($('weather').innerText == ''){$('weather').innerHTML = '<a href=\'javascript:GetWeather();\'><?=_("查看天气")?></a>';}<?}?>">

<table class="topbar" height=50 width="100%" border=0 cellspacing=0 cellpadding=0>
  <tr height=40>
<?
   if($ATTACHMENT_ID!=""&&$ATTACHMENT_NAME!="")
   {
      include_once("inc/utility_file.php");
      
      $YM=substr($ATTACHMENT_ID,0,strpos($ATTACHMENT_ID,"_"));
      if($YM)
         $ATTACHMENT_ID=substr($ATTACHMENT_ID,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
    <td width="<?=$IMG_WIDTH?>" align="center"><img src="/inc/attach.php?MODULE=system&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME)?>" width="<?=$IMG_WIDTH?>" height="<?=$IMG_HEIGHT?>" align="absmiddle"></td>
<?
   }
   if($BANNER_TEXT!="")
   {
?>
    <td>
      <span id="banner_text" style="<?=$BANNER_FONT?>">&nbsp;<?=td_htmlspecialchars($BANNER_TEXT)?></span>
    </td>
<?
   }
   if($ATTACHMENT_ID=="" && $BANNER_TEXT=="")
   {
      if(file_exists(MYOA_ROOT_PATH."theme/".$_SESSION["LOGIN_THEME"]."/product.png"))
      {
?>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/product.png" align="absmiddle"></td>
<?
      }
      else
      {
?>
    <td id="banner_text" style="font-family:<?=$BANNER_FONT?> ;"><?=$_GET['PRODUCT_NAME']?></td>
<?
      }
   }
?>
    <td valign="top" nowrap>
      <div id="time"><span class="time_left"><span class="time_right">
<?
$df = new CDateFormatter(MYOA_LANG_COOKIE);
$local = $df->getLocale();
$languageID = $local->getLanguageID(MYOA_LANG_COOKIE);
$CUR_DATE=$CUR_YEAR."-".$CUR_MON."-".$CUR_DAY;
echo "<a href='calendar2\index.php' target='main' style=text-decoration:none> <span id='date' title='".$df->formatDate(time(),"long")."'>";
if(is_holiday($CUR_DATE))
   echo is_holiday($CUR_DATE);
else
   echo $df->formatDate(time(),"short");
echo "</span></a>";
echo " <b>".$local->getWeekDayName(date("w",time()),"wide")."</b>";

if($languageID == 'zh')
{
    $mdate=chinese_date($CUR_YEAR,$CUR_MON,$CUR_DAY);
    echo sprintf(' <span id="mdate" title="%s %s">', _("农历"), $mdate);
    if(is_festival($mdate))
        echo is_festival($mdate);
    else
        echo _("农历").$mdate;
    echo "</span>";
}
?>
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/time.gif" align="absmiddle">
        <a href="world_time/index.php" target="main" style=text-decoration:none><span id="time_area"></span></a>&nbsp;
      </span></span>
      </div>
      <div id="weather" title="<?=_("点击城市名称可以更改城市")?>"><?=$WEATHER_HTML?></div>
<?
if($SHOW_RSS=="1" && $SHOW_RSS_USER=="1")
{
?>
      <a id="today" href="today.php" target="_blank"><img src="<?=MYOA_STATIC_SERVER?>/static/images/rss.png" align="absMiddle"><?=_("资讯")?></a>
<?
}
?>
    </td>
  </tr>
</table>
<?
if(strpos($WEATHER_CITY, "_") !== FALSE)
{
?>
<div id="area_select">
  <select id="w_province" onChange="InitCity(this.value);"></select>
  <select id="w_city" onChange="InitCounty(this.value);"></select>
  <select id="w_county"></select>
  <input type="button" value="<?=_("确定")?>" class="SmallButton" onClick="GetWeather('1');">
  <input type="button" value="<?=_("取消")?>" class="SmallButton" onClick="$('area_select').style.display='none';$('weather').style.display='block';">
</div>
<?
}
?>
</body>
</html>
