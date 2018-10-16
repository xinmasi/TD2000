<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');
$CUR_TIME=date("Y-m-d H:i:s",time());

if($BTN_OP!="")
{
   $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-".$DAY));
   if(stristr($BTN_OP, "month"))
      $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-01"));
   
   $YEAR=date("Y",$DATE);
   $MONTH=date("m",$DATE);
   
   if(!stristr($BTN_OP, "month"))
      $DAY=date("d",$DATE);
}

if(!$YEAR)
   $YEAR = $CUR_YEAR;
if(!$MONTH)
   $MONTH = $CUR_MON;
if(!$DAY)
   $DAY = $CUR_DAY;

if(!checkdate($MONTH,$DAY,$YEAR))
{
   $DAY=date("t", strtotime($YEAR."-".$MONTH."-01"));
}
$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$MONTH_BEGIN=strtotime($YEAR."-".$MONTH."-01");
$MONTH_END=strtotime($YEAR."-".$MONTH."-".date("t",$DATE));

$CUR_TIME=date("Y-m-d H:i:s",time());
$CONDITION_STR="";

$dun_query="select CODE_NO,CODE_NAME from SYS_CODE where PARENT_NO='PAIBAN_TYPE' order by CODE_ORDER";
$dun_cursor= exequery(TD::conn(),$dun_query);
while($ROW=mysql_fetch_array($dun_cursor))
{
    $DUN_CODE_NO=$ROW["CODE_NO"];
    $DUN_CODE_NAME=$ROW["CODE_NAME"];
    if($_GET['PAIBAN_TYPE']==$DUN_CODE_NO)
    {
       $CONDITION_STR.=" and PAIBAN_TYPE='".$DUN_CODE_NO."'";
       $PAIBAN_TYPE_DESC="<font color='#0000FF'>".$DUN_CODE_NAME."</font>";
       break;
    }
    else
    {
    	 $PAIBAN_TYPE_DESC=_("全部值班");
    }
}


$HTML_PAGE_TITLE = _("值班安排");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/2.1.1/fullcalendar.print.css" media="print">

<script src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<style>
body{background-color:#fff !important; }
.T_gc{color: #51a351;}
.T_rc{color: #bd362f;}
.theader{font-size: 14px;font-weight: bold;border-top: 3px solid #ccc;line-height: 30px}
#cal_table {margin: 20px 40px;}
.fc-widget-header th{font-weight: bold;padding: 5px 0;font-size: 14px;background-color: #efefef;}
.fc-title, .fc-event{font-size: 12px;}
.fc-day-grid-event .fc-time{font-weight: normal;}
.fc-event{cursor: pointer;}
#mapiframe{width: 100%;height: 325px;}
.select-org{position:absolute;top: 20px;right: 41px;}
#myModal .modal-body{max-height:455px;}
#mapiframe{height:455px;}
.modal.fade.in{top: 4%;}
#cal_table th, #cal_table td {
    border-left: 1px solid #dddddd;
}
.fc th, .fc td {
    border: 1px solid  #dddddd;
    padding: 0;
    vertical-align: top;
}
a:hover{
  text-decoration: none;
}
a span{
  color: #383838;
}
</style>
<script>
var $ = $$;
function set_date(id)
{
   var td_cur =$("td_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);  
   var div_cur=$("div_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);
   var td=$(id);
   var div=$("div_"+id.substr(3));
   if(!td || !td_cur || !div || !div_cur) return;
   td_cur.className="";
   div_cur.className="TableContent";
   td.className="TableRed";
   div.className="TableRed";
   document.form1.YEAR.value=id.substr(3,4);
   document.form1.MONTH.value=id.substr(7,2);
   document.form1.DAY.value=id.substr(9,2);
}

function init()
{
   var tbl = $$("cal_table");
   if(!tbl) return;
   for(i=0;i<tbl.rows.length;i++)
   {
      for(j=0;j<tbl.rows[i].cells.length;j++)
      {
         var td=tbl.rows[i].cells[j];
         if(td.id.substr(0,3)=="td_")
         {	          
	          td.onmouseover=function ()
	          {
	             var td=$(this.id);
               var div=$("div_"+this.id.substr(3));	
               td.className="TableRed";
               div.className="TableRed";
	          }
	          td.onmouseout=function ()
	          {
	             var td=$(this.id);
               var div=$("div_"+this.id.substr(3));	
               td.className="";
               div.className="TableContent";
	          }
         }
      }
   }
}

function set_status2(status)
{
  document.form1.PAIBAN_TYPE.value=status;
  My_Submit();
}
function set_view2(view, cname)
{
    if(cname=="" || typeof(cname)=='undefined') cname="cal_view";
    var exp = new Date();
    exp.setTime(exp.getTime() + 24*60*60*1000);
    document.cookie = cname+"="+ escape (view) + ";expires=" + exp.toGMTString()+";path=/";
    
    var url=view+'.php?PAIBAN_TYPE='+document.form1.PAIBAN_TYPE.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
    if(document.form1.DEPT_ID) url+='&DEPT_ID='+document.form1.DEPT_ID.value;
    location=url;
}
function my_note2(PAIBAN_ID)
{
  myleft=(screen.availWidth-400)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("note.php?PAIBAN_ID="+PAIBAN_ID,"note_win"+PAIBAN_ID,"height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}
function my_dairy(PAIBAN_ID)
{
  myleft=(screen.availWidth-400)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("my_dairy.php?PAIBAN_ID="+PAIBAN_ID,"note_win"+PAIBAN_ID,"height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
	
}
</script>
<body class="bodycolor" onload="init();">
<div class="small" style="clear:both;">
 <div style="float:left;margin-left: 25px;">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin:15px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['PAIBAN_TYPE']?>" name="PAIBAN_TYPE">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <input type="button" value="<?=_("今天")?>" class="btn" title="<?=_("今天")?>" onclick="location='<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>'">
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
   <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a>
   <select name="YEAR" class="input-small" onchange="My_Submit();">
<?
   for($I=2000;$I<=2030;$I++)
   {
?>
      <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
   }
?>
   </select>
<!-------------- 月 ------------>
   <select name="MONTH" class="input-small" onchange="My_Submit();">
<?
   for($I=1;$I<=12;$I++)
   {
     if($I<10)
        $I="0".$I;
?>
     <option value="<?=$I?>" <? if($I==$MONTH) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
   }
?>
   </select>
   <a href="javascript:set_mon(1);" class="ArrowButtonR" title="<?=_("下一月")?>"></a>
   <a href="javascript:set_year(1);" class="ArrowButtonRR" title="<?=_("下一年")?>"></a>&nbsp;
   <a id="status" href="javascript:void();" class="dropdown" onclick="showMenu(this.id,'1');" hidefocus="true"><span><?=$PAIBAN_TYPE_DESC?><?=menu_arrow("DOWN")?></span></a>&nbsp;
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status2('');"><?=_("全部值班")?></a>
<? 
   $dunquery="select CODE_NO,CODE_NAME from SYS_CODE where PARENT_NO='PAIBAN_TYPE' order by CODE_ORDER";
   $duncursor= exequery(TD::conn(),$dunquery);
   while($ROW=mysql_fetch_array($duncursor))
   {
       $DUNCODE_NO=$ROW["CODE_NO"];
       $DUNCODE_NAME=$ROW["CODE_NAME"];
       echo "<a href=\"javascript:set_status2(".$DUNCODE_NO.");\" style=\"color:#0000FF;\">".$DUNCODE_NAME."</a>";
   }
?>
   </div>
 </div>
 <div style="float:right;margin: 15px 20px 15px 15px;">
   <span class="">
      <a class="btn btn-large" href="javascript:set_view2('list');" title="<?=_("值班列表视图")?>"><span><?=_("值班列表视图")?></span></a>
      <a class="btn btn-large" href="javascript:set_view2('index');" title="<?=_("值班月视图")?>"><span><?=_("值班月视图")?></span></a>
   </span>
   </form>
 </div>
</div>

<?
$CODE_NAME=array();
$CODE_NAME2=array(); 
//============================ 显示值班安排 =======================================

$SYS_PARA_ARRAY = get_sys_para("KEEP_WATCH");
$KEEP_WATCH     = $SYS_PARA_ARRAY["KEEP_WATCH"];

$where = "";
if($KEEP_WATCH==1)
{
    $where = "1=1 ";
}
else
{
    $where = "ZHIBANREN='".$_SESSION["LOGIN_USER_ID"]."' ";
}


$query = "SELECT * from ZBAP_PAIBAN where ".$where.$CONDITION_STR." and (ZBSJ_B>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_B<='".date("Y-m-d",$MONTH_END)." 23:59:59' || ZBSJ_E>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_E<='".date("Y-m-d",$MONTH_END)." 23:59:59' || ZBSJ_B<='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_E>='".date("Y-m-d",$MONTH_END)." 23:59:59') order by ZBSJ_B";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
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
   $ZB_RZ=$ROW["ZB_RZ"];  

   $DESC_STR = substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;".substr($ZBSJ_B,11,5)." - ".substr($ZBSJ_E,11,5)."<br>";
 
   if(!array_key_exists($PAIBAN_TYPE, $CODE_NAME)) 
      $CODE_NAME[$PAIBAN_TYPE]=get_code_name($PAIBAN_TYPE,"PAIBAN_TYPE");
   if(!array_key_exists($ZHIBAN_TYPE, $CODE_NAME2)) 
      $CODE_NAME2[$ZHIBAN_TYPE]=get_code_name($ZHIBAN_TYPE,"ZHIBAN_TYPE");      
   $CAL_TITLE=_("排班类型：").$CODE_NAME[$PAIBAN_TYPE]."\n";
   $CAL_TITLE.=_("值班类型：").$CODE_NAME2[$ZHIBAN_TYPE]."\n";  
   
   if(substr($ZBSJ_B,0,10) != substr($ZBSJ_E,0,10))
   {
  	  $DESC_STR= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;".substr($ZBSJ_B,11,5)." - 00:00<br>";
  	  $DESC_STR2= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;00:00 - ".substr($ZBSJ_E,11,5)."<br>";
   	  $DESC_STR3= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;00:00 - 24:00<br>";
   	  $DATE_TEM="";
  	  $CAL_ARRAY[date("j",strtotime($ZBSJ_B))].="
     <div title=''>
     <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
     ".$DESC_STR."</a>
     </div>\n";   	  
      for($DATE_TEM=date("Y-m-d",mktime(0,0,0,date("m",strtotime($ZBSJ_B)),date("d",strtotime($ZBSJ_B))+1,date("Y",strtotime($ZBSJ_B))));strtotime($DATE_TEM)<strtotime(substr($ZBSJ_E,0,10));$DATE_TEM=date("Y-m-d",mktime(0,0,0,date("m",strtotime($DATE_TEM)),date("d",strtotime($DATE_TEM))+1,date("Y",strtotime($DATE_TEM)))))
      {
	   	  $CAL_ARRAY[date("j",strtotime($DATE_TEM))].="
	      <div title='".$CAL_TITLE."'>
	      <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
	      ".$DESC_STR3."</a>
	      </div>\n";
      }
  	  $CAL_ARRAY[date("j",strtotime($ZBSJ_E))].="
     <div title='".$CAL_TITLE."'>
     <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
     ".$DESC_STR2."</a>
     </div>\n";
   }
   else
   {
     $CAL_ARRAY[date("j",strtotime($ZBSJ_B))].="
     <div title='".$CAL_TITLE."'>
     <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
     ".$DESC_STR."</a>
     </div>\n";    	    	
   }
   
     $OP_MENU.="<span id=\"cal_".$PAIBAN_ID."_menu\" class=\"attach_div\">\n";
     $OP_MENU.="<a href='javascript:my_note2($PAIBAN_ID);'> "._("查看")."</a>\n";
     if(strtotime($ZBSJ_B) <= strtotime($CUR_TIME) && $ZHIBANREN==$_SESSION['LOGIN_USER_ID'])
     {
       if($ZB_RZ=="")
          $OP_MENU.="<a href='javascript:my_dairy($PAIBAN_ID);'>"._("写日志")."</a>\n";
	   else
		  $OP_MENU.="<a href='javascript:my_dairy($PAIBAN_ID);'>"._("修改日志")."</a>\n";
     }
     $OP_MENU.="</span>\n";    
}
?>
<table id="cal_table" class="TableBlock fc fc-widget-header" width="100%" align="center">
  <tr align="center" class="TableHeader">
    <th class="fc-day-header fc-widget-header" width="6%"><b><?=_("周数")?></b></th>
    <th class="fc-day-header fc-widget-header" width="14%"><b><?=_("星期一")?></b></th>
    <th class="fc-day-header fc-widget-header" width="14%"><b><?=_("星期二")?></b></th>
    <th class="fc-day-header fc-widget-header" width="14%"><b><?=_("星期三")?></b></th>
    <th class="fc-day-header fc-widget-header" width="14%"><b><?=_("星期四")?></b></th>
    <th class="fc-day-header fc-widget-header" width="14%"><b><?=_("星期五")?></b></th>
    <th class="fc-day-header fc-widget-header" width="12%"><b><?=_("星期六")?></b></th>
    <th class="fc-day-header fc-widget-header" width="12%"><b><?=_("星期日")?></b></th>
  </tr>
<?
for($I=1;$I<=date("t",$DATE);$I++)
{
  $WEEK=date("w",strtotime($YEAR."-".$MONTH."-".$I));
  $WEEK= $WEEK==0 ? 6: $WEEK-1;

  if($WEEK==0 || $I==1)
  {
     $WEEKS=date("W", $MONTH_BEGIN+($I-1)*24*3600);
     $WEEK_BEGIN=date("Ymd", strtotime("-".$WEEK."days",strtotime($YEAR."-".$MONTH."-".$I)));
     echo "  <tr height=\"80\" class=\"TableData\">\n";
     echo "    <td id=\"tw_".$WEEK_BEGIN."\" class=\"TableContent\" align=\"center\">".sprintf(_("第(%s)周"),$WEEKS)."</td>\n";
  }

  for($J=0;$J<$WEEK&&$I==1;$J++)
  { 
?>
    <td class="TableData" valign="top">&nbsp</td>
<?
  }
?>
    <td id="td_<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" class="<?if($I==$DAY) echo "TableRed";?>" valign="top">
      <div id="div_<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" align="right" class="<?=$I==$DAY ? "TableRed" : "TableContent";?>" style="cursor:hand;width: 100%;">
        <font color="blue"><b><?=$I?></b></font>
      </div>
      <div>
        <?=$CAL_ARRAY[$I]?>
      </div>
    </td>
<?
  if ($WEEK==6)
     echo "  </tr>\n";
}//while

//------------- 补结尾空格 -------------
if($WEEK!=6)
{
  for($I=$WEEK;$I<6;$I++)
  {
?>
    <td class="TableData">&nbsp</td>
<?
  }
?>
  </tr>
<?
}
?>
</table>
<?=$OP_MENU?>
</body>
</html>

