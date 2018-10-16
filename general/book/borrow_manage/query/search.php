<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("历史记录查询");
include_once("inc/header.inc.php");
?>


<script>
function delete_borrow(BORROW_ID,TO_ID,BOOK_NO,BOOK_STATUS)
{
  msg='<?=_("确认要删除该记录吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?BORROW_ID=" + BORROW_ID + "&TO_ID=" + TO_ID + "&BOOK_NO=" + BOOK_NO + "&BOOK_STATUS=" + BOOK_STATUS;
     window.location=URL;
  }
}

</script>


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

//----------- 合法性校验 ---------
if($START_B!="")
{
   $TIME_OK=is_date($START_B);
   if(!$TIME_OK)
   {
   	  $MSG1 = sprintf(_("开始时间的格式不对，应形如%s"), $CUR_DATE);
      Message(_("错误"),$MSG1);
      Button_Back();
      exit;
   }
}

if($END_B!="")
{
   $TIME_OK=is_date($END_B);
   if(!$TIME_OK)
   {
   	  $MSG2 = sprintf(_("开始时间的格式不对，应形如%s"), $CUR_DATE);
      Message(_("错误"),$MSG2);
      Button_Back();
      exit;
   }
}

if($START_R!="")
{
   $TIME_OK=is_date($START_R);
   if(!$TIME_OK)
   {  
   	  $MSG3 = sprintf(_("开始时间的格式不对，应形如%s"), $CUR_DATE);
      Message(_("错误"),$MSG3);
      Button_Back();
      exit;
   }
}

if($END_R!="")
{
   $TIME_OK=is_date($END_R);
   if(!$TIME_OK)
   {
   	  $MSG4 = sprintf(_("开始时间的格式不对，应形如%s"), $CUR_DATE);
      Message(_("错误"),$MSG4);
      Button_Back();
      exit;
   }
}

$query1 = "SELECT * from BOOK_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER_ID)";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
   $MANAGE_DEPT_ID.=$ROW1["MANAGE_DEPT_ID"];
   
//-----------先组织SQL语句-----------
if($TO_ID!="")
   $WHERE_STR.=" and BUSER_ID='$TO_ID'";
if($BOOK_NO!="")
   $WHERE_STR.=" and BOOK_NO='$BOOK_NO'";
if($START_B!="")
   $WHERE_STR.=" and BORROW_DATE>='$START_B'";
if($END_B!="")
   $WHERE_STR.=" and BORROW_DATE<='$END_B'";
if($BOOK_STATUS1=="1")
   $WHERE_STR.=" and BOOK_STATUS='1' and STATUS='1'";
if($BOOK_STATUS1=="0")
   $WHERE_STR.=" and ((BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0'))";
if($BOOK_STATUS1=="")
   $WHERE_STR.=" and ((BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0') or (BOOK_STATUS='1' and STATUS='1'))";   
if($BORROW_REMARK!="")
   $WHERE_STR.=" and BORROW_REMARK like '%".$BORROW_REMARK."%'"; 
   
$query="SELECT * from BOOK_MANAGE where STATUS!='2'".$WHERE_STR." order by RETURN_DATE desc";
$cursor= exequery(TD::conn(),$query);
$BOOK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $BOOK_NO1=$ROW["BOOK_NO"];  
  
  $query2 = "SELECT DEPT from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
  $cursor2= exequery(TD::conn(),$query2);
  if($ROW2=mysql_fetch_array($cursor2)) 
     $DEPT=$ROW2["DEPT"];
  
   if(!find_id($MANAGE_DEPT_ID,$DEPT) && $MANAGE_DEPT_ID!="ALL_DEPT")
      continue;	
      
  $BOOK_COUNT++;    	
}

if($BOOK_COUNT==0)
{
    Message(_("提示"),_("没有符合条件的借书记录"));
?>
<br>
<div align="center">
<input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';">
</div>
<?
    exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
   <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("历史记录查询")?> </span><br>
   </td>
 </tr>
</table>

<table class="TableList" width="95%" align="center">
 <tr class="TableHeader">
    <td nowrap align="center"><?=_("借书人")?></td>
    <td nowrap align="center"><?=_("图书编号")?></td>    
    <td nowrap align="center"><?=_("书名")?></td>
    <td nowrap align="center"><?=_("借书日期")?></td>
    <td nowrap align="center"><?=_("还书日期")?></td>
    <td nowrap align="center"><?=_("实还日期")?></td>
    <td nowrap align="center"><?=_("登记人")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
    <td nowrap align="center"><?=_("备注")?></td>    
    <td nowrap align="center"><?=_("操作")?></td>
 </tr>

<?
$cursor1 = exequery(TD::conn(), $query);
$BOOK_COUNT = 0;
while($ROW=mysql_fetch_array($cursor1))
{
  $BOOK_COUNT++;
  $BORROW_ID=$ROW["BORROW_ID"];
  $BUSER_ID=$ROW["BUSER_ID"];
  $BOOK_NO1=$ROW["BOOK_NO"];
  $BORROW_DATE=$ROW["BORROW_DATE"];
  $RUSER_ID=$ROW["RUSER_ID"];
  $RETURN_DATE=$ROW["RETURN_DATE"];
  $BOOK_STATUS2=$ROW["BOOK_STATUS"];
  $REAL_RETURN_TIME=$ROW["REAL_RETURN_TIME"];
  $STATUS=$ROW["STATUS"];
  $BORROW_REMARK=$ROW["BORROW_REMARK"];
  
  $query2 = "SELECT DEPT from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
  $cursor2= exequery(TD::conn(),$query2);
  if($ROW2=mysql_fetch_array($cursor2)) 
     $DEPT=$ROW2["DEPT"];
  
   if(!find_id($MANAGE_DEPT_ID,$DEPT) && $MANAGE_DEPT_ID!="ALL_DEPT")
      continue;

  if($REAL_RETURN_TIME=="0000-00-00")
     $REAL_RETURN_TIME="";

  $query2="select * from USER where USER_ID='$BUSER_ID'";
  $cursor2= exequery(TD::conn(),$query2);
  while($ROW=mysql_fetch_array($cursor2))
     $USER_NAME1=$ROW["USER_NAME"];

  $query2="select * from USER where USER_ID='$RUSER_ID'";
  $cursor2= exequery(TD::conn(),$query2);
  while($ROW=mysql_fetch_array($cursor2))
     $USER_NAME2=$ROW["USER_NAME"];

  $query2="select * from BOOK_INFO where BOOK_NO='$BOOK_NO1'";
  $cursor2= exequery(TD::conn(),$query2);
  while($ROW=mysql_fetch_array($cursor2))
     $BOOK_NAME=$ROW["BOOK_NAME"];

  if($BOOK_STATUS2=='1' and $STATUS=='1')
  	 $BOOK_STATUS_DESC=_("已还");
  if(($BOOK_STATUS2=='0' and $STATUS=='1') or ($BOOK_STATUS2=='1' and $STATUS=='0'))
  	 $BOOK_STATUS_DESC=_("未还");

  if($BOOK_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>

  <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$USER_NAME1?></td>
      <td nowrap align="center"><?=$BOOK_NO1?></td>
      <td nowrap align="center"><?=$BOOK_NAME?></td>
      <td nowrap align="center"><?=$BORROW_DATE?></td>
      <td nowrap align="center"><?=$RETURN_DATE?></td>
      <td nowrap align="center"><?=$REAL_RETURN_TIME?></td>
      <td nowrap align="center"><?=$USER_NAME2?></td>
      <td nowrap align="center"><?=$BOOK_STATUS_DESC?></td>
      <td nowrap align="center"><?=$BORROW_REMARK?></td>      
<?
if(($BOOK_STATUS2=='0' and $STATUS=='1') or ($BOOK_STATUS2=='1' and $STATUS=='0'))
{
?>
      <td nowrap align="center">&nbsp;</td>
<?
}
if($BOOK_STATUS2=='1' and $STATUS=='1')
{
?>
      <td nowrap align="center">
        <a href="javascript:delete_borrow('<?=$BORROW_ID?>','<?=$TO_ID?>','<?=$BOOK_NO?>','<?=$BOOK_STATUS1?>');"> <?=_("彻底删除")?></a>
     	</td>
<?
}
?>
  </tr>
<?
}//while
?>
</table>

<br>
<div align="center">
<input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';">
</div>

</body>
</html>