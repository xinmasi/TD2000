<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("外出人员");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',30000);
</script>


<body class="bodycolor">

<!------------------------------------- 外出人员 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("外出人员")?></span><br>
    </td>
  </tr>
</table>

<br>

<?
 $CUR_DATE=date("Y-m-d",time());
 $query = "SELECT * from ATTEND_OUT,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and  ATTEND_OUT.USER_ID=USER.USER_ID and to_days(SUBMIT_TIME)=to_days('$CUR_DATE') and ALLOW='1' order by DEPT_NO,USER.USER_NAME";
 $cursor= exequery(TD::conn(),$query);
 $OUT_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OUT_COUNT++;
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];

   $DEPT_ID=$ROW["DEPT_ID"];
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OUT_COUNT==1)
    {
?>

    <table class="TableList" width="95%" align="center">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$OUT_TIME2?></td>
    </tr>
<?
 }

 if($OUT_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("外出原因")?></td>
      <td nowrap align="center"><?=_("外出时间")?></td>
      <td nowrap align="center"><?=_("预计归来时间")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("无外出人员"));
?>

<br>

</body>
</html>