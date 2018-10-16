<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("日程安排导出");
include_once("inc/header.inc.php");
?>



<STYLE type=text/css>.HdrPaddingTop {
	PADDING-TOP: 5px
}
.HdrPaddingBttm {
	PADDING-BOTTOM: 5px
}
BODY {
	MARGIN-TOP: 2px; MARGIN-LEFT: 0px; COLOR: #000000; MARGIN-RIGHT: 0px
}
A {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
BODY {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TABLE {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TD {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TR {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TABLE {
	BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px
}
.FF {
	COLOR: #000000
}
</STYLE>

<body>
<P>
<TABLE cellSpacing=0 cellPadding=3 width=640 align=center border=0>
  <TBODY>
  <TR>
    <TD style="BORDER-BOTTOM: #000099 1px solid" colSpan="2"></TD>
  </TR>
  <TR>
    <TD noWrap><FONT class=FF><?=$_SESSION["LOGIN_USER_NAME"]?> &lt;<?=$_SESSION["LOGIN_USER_ID"]?>&gt;</FONT></TD>
    <TD noWrap align=right><FONT class=FF><?=_("已打印：")?> <?=date(_("Y年m月d日 H:i:s"),time())?> 
  </FONT></TD></TR></TR>
  <TR>
    <TD colSpan=2></TD>
  </TR>
  <TR>
    <TD colSpan=2>
      <HR color=#808080 SIZE=3>
    </TD>
  </TR>
  </TBODY>
</TABLE>

<TABLE cellSpacing=0 cellPadding=0 width=640 align=center border=0>
  <TBODY>
  <TR>
    <TD class=HdrPaddingBttm width="100%">

<?
 $CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("Y-m-d H:i:s",time());
 $CUR_TIME=strtotime($CUR_TIME);
  //----------- 合法性校验 ---------
  if($SEND_TIME_MIN!="")
  {
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { 
    	$MSG1 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG1);
      Button_Back();
      exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
    $SEND_TIME_MIN=strtotime($SEND_TIME_MIN);
  }

  if($SEND_TIME_MAX!="")
  {
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { 
    	$MSG2 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG2);
      Button_Back();
      exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
    $SEND_TIME_MAX=strtotime($SEND_TIME_MAX);
  }

 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";
 if($CAL_LEVEL=="0")
    $CONDITION_STR.=" and CAL_LEVEL=''";
 else if($CAL_LEVEL=="1")
    $CONDITION_STR.=" and CAL_LEVEL='1'";
 else if($CAL_LEVEL=="2")
    $CONDITION_STR.=" and CAL_LEVEL='2'";
 else if($CAL_LEVEL=="3")
    $CONDITION_STR.=" and CAL_LEVEL='3'";
 else if($CAL_LEVEL=="4")
    $CONDITION_STR.=" and CAL_LEVEL='4'";
    
 if($CAL_TYPE!="")
    $CONDITION_STR.=" and CAL_TYPE='$CAL_TYPE'";
 if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
 if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and CAL_TIME>='$SEND_TIME_MIN'";
 if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and END_TIME<='$SEND_TIME_MAX'";
    
 if($OVER_STATUS=="1")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME'";
 else if($OVER_STATUS=="2")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME' and END_TIME>='$CUR_TIME'";
 else if($OVER_STATUS=="3")
    $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME'";
 else if($OVER_STATUS=="4")
    $CONDITION_STR.=" and OVER_STATUS='1'";

 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR." order by CAL_TIME,END_TIME";

$CUR_DATE="";
$CAL_COUNT=0;
$MANAGER=array();
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $CAL_COUNT++;

    $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    
    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME=_("安排人:").$MANAGER[$MANAGER_ID];
    }
    
    $CONTENT=td_htmlspecialchars($CONTENT);
    
    $CAL_DATE=substr($CAL_TIME,0,10);
    if($CAL_DATE!=$CUR_DATE)
    {
       echo "<br><b>".$CAL_DATE._("：")."</b><br>";
       $CUR_DATE=$CAL_DATE;
    }
    $CAL_LEVEL_DESC = $CAL_LEVEL!="" ? cal_level_desc($CAL_LEVEL) : "";
    $DESC="";
    if($CAL_LEVEL_DESC!="" || $MANAGER_NAME!="")
       $DESC=" (".$CAL_LEVEL_DESC." ".$MANAGER_NAME.")";
    
    if(substr($CAL_TIME,0,10) == substr($END_TIME,0,10))
    {
       $CAL_TIME=substr($CAL_TIME,11,5);
       $END_TIME=substr($END_TIME,11,5);
    }
    else
    {
       $CAL_TIME=substr($CAL_TIME,11,5);
       $END_TIME=substr($END_TIME,0,16);
    }
    echo "&nbsp;&nbsp;&nbsp;".$CAL_TIME." - ".$END_TIME."&nbsp;&nbsp;&nbsp;".$CONTENT.$DESC."<br>";
}
?>
    </TD>
  </TR>
  </TBODY>
</TABLE>
</body>

</html>
