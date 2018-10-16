<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>





<body class="bodycolor">
<br>
<!------------------------------------- 外出记录 ------------------------------->


<table class="TableList" width="86%" align="center">
  <tr >
      <td width="5%" align="center" ><?=_("部门")?></td>
      <td width="5%" align="center" ><?=_("姓名")?></td>
      <td width="8%" align="center" ><?=_("申请时间")?></td>
      <td width="12%" align="center"  ><?=_("外出原因")?></td>
      <td width="17%" align="center" ><?=_("登记")?>IP</td>
      <td width="10%" align="center" ><?=_("外出日期")?></td>
      <td width="8%" align="center" ><?=_("外出时间")?></td>
      <td width="8%" align="center" ><?=_("归来时间")?></td>
      <td width="6%" align="center" ><?=_("时长")?></td>
      <td width="6%" align="center" ><?=_("审批人员")?></td>
      <td width="4%" align="center" ><?=_("状态")?></td>
      <td width="11%" align="center" ><?=_("操作")?></td>
    </tr>
<?
 if($DEPARTMENT1!="ALL_DEPT")
    $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";

 $query = "SELECT * from ATTEND_OUT,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_OUT.USER_ID=USER.USER_ID and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by DEPT_NO,USER_NO,USER_NAME";
 $cursor= exequery(TD::conn(),$query);
 $OUT_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
   $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];
   $CREATE_DATE=$ROW["CREATE_DATE"]; 
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

   if(!is_dept_priv($DEPT_ID))
      continue;

   $OUT_COUNT++;

   if($STATUS=="0")
      $STATUS_DESC=_("外出");
   else if($STATUS=="1")
      $STATUS_DESC=_("已归来");
   if($ALLOW=='0')
      $STATUS_DESC=_("待批");
   if($ALLOW=='2')
      $STATUS_DESC=_("不批准");

   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OUT_COUNT>=1)
    {
  $ALL_HOURS3 = floor((strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) / 3600);
  $HOUR13 = (strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) % 3600;
  $MINITE3 = floor($HOUR13 / 60);
?>
    <tr>
      <td  align="center"><?=$USER_DEPT_NAME?></td>
      <td  align="center"><?=$USER_NAME?></td>
      <td  align="center"><?=$CREATE_DATE?></td>      
      <td   style="word-break: break-all"><?=$OUT_TYPE?></td>
      <td  align="center"><?=$REGISTER_IP?></td>
      <td  align="center"><?=$SUBMIT_DATE?></td>
      <td  align="center"><?=$OUT_TIME1?></td>
      <td  align="center"><?=$OUT_TIME2?></td>
      <td  align="center"><?=$ALL_HOURS3._("小时").$MINITE3._("分")?></td>
      <td  align="center"><?=$LEADER_NAME?></td>
      <td  align="center"><?=$STATUS_DESC?></td>
      <td  align="center">
	<?
    if($_SESSION["LOGIN_USER_PRIV"]==1)
    {
    ?>
            <a href="delete_out.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&SUBMIT_TIME=<?=urlencode($SUBMIT_TIME)?>"><?=_("删除")?></a>
    <?
    }
    ?>
          </td>
        </tr>
    <?
     }
 }
 if($OUT_COUNT<=0)
 {
	 message("",_("无外出记录"));  
 }
?>
</table>
</body>
</html>