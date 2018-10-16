<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$STR="";
$STR.="<TR class=TableData>";
$STR.="<TD>"._("使用人：")."<br></TD>";
$STR.="<TD>";
$query1="select USER.USER_ID as USER_ID,USER.USER_NAME as USER_NAME from OA_SOURCE,USER where OA_SOURCE.SOURCEID='$SOURCE_NO' and (find_in_set(USER.USER_ID,OA_SOURCE.VISIT_USER) or find_in_set(USER.USER_PRIV,OA_SOURCE.VISIT_PRIV ))   ";
$cursor1 = exequery(TD::conn(),$query1);
$STR.="<select name='USER'>";
$selecteds = ($USER_ID == $LOGIN_ORG_ID || $USER_ID =="") ? "selected" :"";
$STR.="<option value='".$_SESSION["LOGIN_USER_ID"]."' ".$selecteds.">".$_SESSION["LOGIN_USER_NAME"]."</option>";
while($ROW1=mysql_fetch_array($cursor1))
{
	$selected = $USER_ID == $ROW1[USER_ID] ? "selected" :"";
	$STR.="<option value='$ROW1[USER_ID]'".$selected.">".$ROW1[USER_NAME]."</option>";
}
$STR.="</select>";
$STR.="</TD><br>";
$STR.="<TR class=TableData>";
$STR.="<TD>"._("星期设定：")."<br></TD>";
$STR.="<TD>";
//..............
if($SOURCE_NO!="")
{
	$query="select WEEKDAY_SET,TIME_TITLE from OA_SOURCE where SOURCEID='$SOURCE_NO' ";
	$cursor = exequery(TD::conn(),$query);
	if($ROWS=mysql_fetch_array($cursor))
	{
		$WEEKDAY_SET=$ROWS["WEEKDAY_SET"];
		$TIME_TITLE=$ROWS["TIME_TITLE"];
		$TIME_TITLE_ARRAY=explode(',',$TIME_TITLE);
	
	}
}
   

      if(find_id($WEEKDAY_SET,"1")) { $STR.="<input type='checkbox' name='WEEK1' id='WEEK1' "; if(find_id($WEEKDAY_SET2,"1")) { $STR.="checked";} $STR.="><label for='WEEK1'>"._("星期一")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"2")) { $STR.="<input type='checkbox' name='WEEK2' id='WEEK2' "; if(find_id($WEEKDAY_SET2,"2")) { $STR.="checked";}$STR.="><label for='WEEK2'>"._("星期二")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"3")) { $STR.="<input type='checkbox' name='WEEK3' id='WEEK3' "; if(find_id($WEEKDAY_SET2,"3")) { $STR.="checked";}$STR.="><label for='WEEK3'>"._("星期三")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"4")) { $STR.="<input type='checkbox' name='WEEK4' id='WEEK4' "; if(find_id($WEEKDAY_SET2,"4")) { $STR.="checked";}$STR.="><label for='WEEK4'>"._("星期四")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"5")) { $STR.="<input type='checkbox' name='WEEK5' id='WEEK5' "; if(find_id($WEEKDAY_SET2,"5")) { $STR.="checked";} $STR.="><label for='WEEK5'>"._("星期五")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"6")) { $STR.="<input type='checkbox' name='WEEK6' id='WEEK6' "; if(find_id($WEEKDAY_SET2,"6")) { $STR.="checked";} $STR.="><label for='WEEK6'>"._("星期六")."</label>&nbsp; "; }
      if(find_id($WEEKDAY_SET,"0")) { $STR.="<input type='checkbox' name='WEEK0' id='WEEK0' "; if(find_id($WEEKDAY_SET2,"0")) { $STR.="checked";} $STR.="><label for='WEEK0'>"._("星期日")."</label>&nbsp; "; }
		$STR.="</TD><br>";
      $STR.="<TR class=TableData>";
		$STR.="<TD>"._("时间段：")."<br></TD>";
		$STR.="<TD>";
		for($i=0;$i<count($TIME_TITLE_ARRAY);$i++)
		{
			if(find_id($TIME_TITLE,$TIME_TITLE_ARRAY[$i])) {$STR.=" <input type='checkbox' name='TIMESTR[]' value='$TIME_TITLE_ARRAY[$i]' id='TIME".$i."'"; if(find_id($TIME_TITLE2,$TIME_TITLE_ARRAY[$i])) $STR.= "checked";$STR.="><label for='TIME'".$i."'>$TIME_TITLE_ARRAY[$i]</label>&nbsp;"; }
		}
		$STR.="</TD></TR>";	
echo $STR;
?>
