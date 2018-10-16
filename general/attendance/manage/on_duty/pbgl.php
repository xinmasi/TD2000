<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

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

$dun_query="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='PAIBAN_TYPE' order by CODE_ORDER";
$dun_cursor= exequery(TD::conn(),$dun_query);
while($ROW=mysql_fetch_array($dun_cursor))
{
    $DUN_CODE_NO=$ROW["CODE_NO"];
    $DUN_CODE_NAME=$ROW["CODE_NAME"];
    $CODE_EXT=unserialize($ROW["CODE_EXT"]);
    if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
    	 $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
    	 
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


$HTML_PAGE_TITLE = _("日程安排查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>

<script>
function init()
{
   var tbl = document.getElementById("cal_table");
   if(!tbl) return;
   for(i=0;i<tbl.rows.length;i++)
   {
      for(j=0;j<tbl.rows[i].cells.length;j++)
      {
         var td=tbl.rows[i].cells[j];
         if(td.id.substr(0,3)=="td_")
         {
            td.ondblclick =function() {new_arrange2(this.id.substr(3));};
         }
         //else if(td.id.substr(0,3)=="tw_")
         //{
         //   td.ondblclick =function() {new_arrange2(this.id.substr(3))};
         //   td.title="双击安排该天值班";
         //}
      }
   }
}

function new_arrange2(ZBSJ_B)
{
   window.open('index_new.php?ZBSJ_B='+ZBSJ_B,'oa_sub_window','height=420,width=620,status=0,toolbar=no,menubar=no,location=no,left=250,top=200,scrollbars=yes,resizable=yes');
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

function my_modify(PAIBAN_ID)
{
  myleft=(screen.availWidth-620)/2;
  mytop=(screen.availHeight-400)/2;
  window.open("new.php?PAIBAN_ID="+PAIBAN_ID,"note_win"+PAIBAN_ID,"height=420,width=620,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

function del_zhiban(PAIBAN_ID)
{
   var url='delete.php?PAIBAN_ID='+PAIBAN_ID+'&PAIBAN_TYPE='+document.form1.PAIBAN_TYPE.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
   if(document.form1.DEPT_ID) url+='&DEPT_ID='+document.form1.DEPT_ID.value;
   if(window.confirm("<?=_("删除后将不可恢复，确定删除吗？")?>"))
      location=url;
}

</script>

<br>
<body class="bodycolor" onLoad="init();">
<div class="small" style="clear:both;">
 <div style="float:left;">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['PAIBAN_TYPE']?>" name="PAIBAN_TYPE">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <input type="button" value="<?=_("今天")?>" class="SmallButton" title="<?=_("今天")?>" onClick="location='<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>'">
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
   <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a>
   <select name="YEAR" class="SmallSelect" onChange="My_Submit();">
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
   <select name="MONTH" class="SmallSelect" onChange="My_Submit();">
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
   <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span><?=$PAIBAN_TYPE_DESC?><?=menu_arrow("DOWN")?></span></a>
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status2('');"><?=_("全部值班")?></a>
<? 
   $dunquery="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='PAIBAN_TYPE' order by CODE_ORDER";
   $duncursor= exequery(TD::conn(),$dunquery);
   while($ROW=mysql_fetch_array($duncursor))
   {
       $DUNCODE_NO=$ROW["CODE_NO"];
       $DUNCODE_NAME=$ROW["CODE_NAME"];
       $DUNCODE_EXT=unserialize($ROW["CODE_EXT"]);
				if(is_array($DUNCODE_EXT) && $DUNCODE_EXT[MYOA_LANG_COOKIE] != "")
 				   $DUNCODE_NAME = $DUNCODE_EXT[MYOA_LANG_COOKIE];
 						  
       echo "<a href=\"javascript:set_status2(".$DUNCODE_NO.");\" style=\"color:#0000FF;\">".$DUNCODE_NAME."</a>";
   }
?>
   </div>
 </div>
 <div style="float:right;">
 	 <a href="javascript:new_arrange2();"><?=_("安排值班")?></a>
   <select name="DEPT_ID" class="SmallSelect" onChange="My_Submit();">
   	 <option value="" ><?=_("全体部门")?></option>
     <?=my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => "1","DEPT_ID_STR" => $DEPT_ID_STR));?>
   </select>
   </form>
 </div>
</div>

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CODE_NAME=array();
$CODE_NAME2=array();
$MANAGER=array();
if($DEPT_ID!="")
   $WHERE_STR2 = "and ZHIBANREN_DEPT='$DEPT_ID'";
//============================ 显示日程安排 =======================================
$query = "SELECT * from ZBAP_PAIBAN where (ZBSJ_B>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_B<='".date("Y-m-d",$MONTH_END)." 23:59:59' || ZBSJ_E>='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_E<='".date("Y-m-d",$MONTH_END)." 23:59:59' || ZBSJ_B<='".date("Y-m-d",$MONTH_BEGIN)." 00:00:00' and ZBSJ_E>='".date("Y-m-d",$MONTH_END)." 23:59:59') ".$WHERE_STR2.$CONDITION_STR." order by ZBSJ_B";
$cursor= exequery(TD::conn(),$query, $connstatus);
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

   $DESC_STR = substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;".substr($ZBSJ_B,11,5)." - ".substr($ZBSJ_E,11,5)."<br>";
   if(!array_key_exists($PAIBAN_TYPE, $CODE_NAME)) 
      $CODE_NAME[$PAIBAN_TYPE]=get_code_name($PAIBAN_TYPE,"PAIBAN_TYPE");
   if(!array_key_exists($ZHIBAN_TYPE, $CODE_NAME2)) 
      $CODE_NAME2[$ZHIBAN_TYPE]=get_code_name($ZHIBAN_TYPE,"ZHIBAN_TYPE");      
   $CAL_TITLE=_("排班类型：").$CODE_NAME[$PAIBAN_TYPE]."\n";
   $CAL_TITLE.=_("值班类型：").$CODE_NAME2[$ZHIBAN_TYPE]."\n";    
 
   //跨天
   if(substr($ZBSJ_B,0,10) != substr($ZBSJ_E,0,10))
   {
   	//echo date("Y-m-d",mktime(0,0,0,date("m",strtotime($ZBSJ_B)),date("d",strtotime($ZBSJ_B))+1,date("Y",strtotime($ZBSJ_B))));
   	  $DESC_STR= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;".substr($ZBSJ_B,11,5)." - 00:00<br>";
   	  $DESC_STR2= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;00:00 - ".substr($ZBSJ_E,11,5)."<br>";
   	  $DESC_STR3= substr(GetUserNameById($ZHIBANREN),0,-1)."&nbsp;00:00 - 24:00<br>";
   	  $DATE_TEM="";
   	  $CAL_ARRAY[date("m-j",strtotime($ZBSJ_B))].="
      <div title=''>
      <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
      ".$DESC_STR."</a>
      </div>\n";  
      for($DATE_TEM=date("Y-m-d",mktime(0,0,0,date("m",strtotime($ZBSJ_B)),date("d",strtotime($ZBSJ_B))+1,date("Y",strtotime($ZBSJ_B))));strtotime($DATE_TEM)<strtotime(substr($ZBSJ_E,0,10));$DATE_TEM=date("Y-m-d",mktime(0,0,0,date("m",strtotime($DATE_TEM)),date("d",strtotime($DATE_TEM))+1,date("Y",strtotime($DATE_TEM)))))
      {
	   	  $CAL_ARRAY[date("m-j",strtotime($DATE_TEM))].="
	      <div title='".$CAL_TITLE."'>
	      <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
	      ".$DESC_STR3."</a>
	      </div>\n";
      }
   	  $CAL_ARRAY[date("m-j",strtotime($ZBSJ_E))].="
      <div title='".$CAL_TITLE."'>
      <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
      ".$DESC_STR2."</a>
      </div>\n";
   }
   else
   {
      $CAL_ARRAY[date("m-j",strtotime($ZBSJ_B))].="
      <div title='".$CAL_TITLE."'>
      <a id=\"cal_".$PAIBAN_ID."\" href='javascript:my_note2($PAIBAN_ID);' onmouseover=\"showMenu(this.id);\">
      ".$DESC_STR."</a>
      </div>\n";
   }
   if($PAIBAN_APR==$_SESSION["LOGIN_USER_ID"])
   {
      $OP_MENU.="<span id=\"cal_".$PAIBAN_ID."_menu\" class=\"attach_div\">\n";
      $OP_MENU.="<a href='javascript:my_note2($PAIBAN_ID);'>". _("查看")."</a>\n";
      $OP_MENU.="<a href='javascript:my_modify($PAIBAN_ID);'>". _("修改")."</a>\n";
      $OP_MENU.="<a href=\"javascript:del_zhiban($PAIBAN_ID);\">". _("删除")."</a>\n";
      $OP_MENU.="</span>\n";
   }
}
?>
  <table id="cal_table" class="TableBlock" width="100%" align="center">
    <tr align="center" class="TableHeader" height="20">
      <td width="5%"><b><?=_("周数")?></b></td>
      <td width="14%"><b><?=_("星期一")?></b></td>
      <td width="14%"><b><?=_("星期二")?></b></td>
      <td width="14%"><b><?=_("星期三")?></b></td>
      <td width="14%"><b><?=_("星期四")?></b></td>
      <td width="13%"><b><?=_("星期五")?></b></td>
      <td width="13%"><b><?=_("星期六")?></b></td>
      <td width="13%"><b><?=_("星期日")?></b></td>
    </tr>
<?
for($I=1;$I<=date("t",$DATE);$I++)
{
  $WEEK=date("w",strtotime($YEAR."-".$MONTH."-".$I));
  $TEMP_DATE = $YEAR."-".$MONTH."-".$I;
  $WEEK= $WEEK==0 ? 6: $WEEK-1;

  if($WEEK==0 || $I==1)
  {
     $WEEKS=date("W", $MONTH_BEGIN+($I-1)*24*3600);
     $WEEK_BEGIN=date("Ymd", strtotime("-".$WEEK."days",strtotime($YEAR."-".$MONTH."-".$I)));
     $MSG = sprintf(_("第%d周"), $WEEKS);
     echo "  <tr height=\"80\" class=\"TableData\">\n";
     echo "    <td id=\"tw_".$WEEK_BEGIN."\" class=\"TableContent\" align=\"center\">".($MSG)."</td>\n";
  }

  for($J=0;$J<$WEEK&&$I==1;$J++)
  {
?>
     <td class="TableData" valign="top">&nbsp;</td>
<?
}
?>
     <td id="td_<?=strtotime($YEAR."-".$MONTH."-".$I)?>" class="<?if($I==$DAY) echo "TableRed";?>" valign="top">
       <div align="right" class="<?=$I==$DAY ? "TableRed" : "TableContent";?>"  title="<?=_("双击安排该天值班")?>" style="cursor:hand;width: 100%;">
         <font color="blue"><b><?=$I?></b></font>
       </div>
       <div>
       	<?=$CAL_ARRAY[$MONTH.'-'.$I]?>
       </div>
     </td>
<?
  if ($WEEK==6)
     echo "</tr>";
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
<?=$OP_MENU_AFF?>
<?=$OP_MENU_TASK?>
<br>
</body>
</html>

