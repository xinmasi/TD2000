<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("值班排班安排");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'hr';
?>
<script type="text/javascript">
function my_note(PAIBAN_ID)
{
  myleft=(screen.availWidth-400)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("../../attendance/personal/on_duty/note.php?PAIBAN_ID="+PAIBAN_ID,"note_win"+PAIBAN_ID,"height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}
</script>
<?
$CUR_DATA=date("Y-m-d",time());
$CUR_DATA1=date("Y-m-d H:i:s",time());
$D=date('t'); //获取当月天数
$KS_DATE=date("Y-m",time())."-01"; //本月开始日子
$JS_DATE=date("Y-m",time())."-".$D; //本月月底结束日子
$COUNT=0;
$STR=$STR1="";
$query1 = "SELECT * from ZBAP_PAIBAN where ZBSJ_B>='".$KS_DATE." 00:00:00' and ZBSJ_E<='".$JS_DATE." 23:59:59' order by ZBSJ_B ASC";
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1))
{
	 $COUNT++;
   $PAIBAN_ID=$ROW["PAIBAN_ID"];
   $ZHIBANREN=$ROW["ZHIBANREN"];
   $ZBSJ_B=$ROW["ZBSJ_B"];
   $ZBSJ_E=$ROW["ZBSJ_E"];

	 $query2="select USER_NAME from USER where USER_ID='$ZHIBANREN'";
	 $cursor2= exequery(TD::conn(),$query2);
	 if($ROW2=mysql_fetch_array($cursor2))
	  	$ZBREN=$ROW2["USER_NAME"];
	  	
	  	//获取当月值班人
	 if(substr($ZBSJ_B,0,10)==substr($ZBSJ_E,0,10))
		 $STR.="<a title='".$ZBSJ_B." ～ ".$ZBSJ_E."' href='javascript:my_note($PAIBAN_ID)'>".$ZBREN."(".substr($ZBSJ_E,8,2).")</a>";
	 else
		 $STR.="<a title='".$ZBSJ_B." ～ ".$ZBSJ_E."' href='javascript:my_note($PAIBAN_ID)'>".$ZBREN."(".substr($ZBSJ_B,8,2)."～".substr($ZBSJ_E,8,2).")</a>";
		 $STR.=",";
    
     //获取今日值班人
	 if($CUR_DATA1>$ZBSJ_E)
	    continue;
	 else
	 {
      if($CUR_DATA >= substr($ZBSJ_B,0,10) && $CUR_DATA <= substr($ZBSJ_E,0,10))
		  {
		   	 if(substr($ZBSJ_B,0,10)==substr($ZBSJ_E,0,10))
			 		  $STR1.="<a title='".$ZBSJ_B." ～ ".$ZBSJ_E."' href='javascript:my_note($PAIBAN_ID)' style='color:red;'>".$ZBREN."(".substr($ZBSJ_E,8,2).")</a>";
		   	 else
			 		  $STR1.="<a title='".$ZBSJ_B." ～ ".$ZBSJ_E."' href='javascript:my_note($PAIBAN_ID)' style='color:red;'>".$ZBREN."(".substr($ZBSJ_B,8,2)."～".substr($ZBSJ_E,8,2).")</a>";
			 	 $STR1.=",";
		  }
	 }
}
if($COUNT==0)
   $MODULE_BODY.="<li>"._("本月值班人还没有值班安排")."</li>";
else
{
   $MODULE_BODY.=_("今日值班人：").substr($STR1,0,-1)."</br>";
   $MODULE_BODY.=_("本月值班人：").substr($STR,0,-1);
}
?>