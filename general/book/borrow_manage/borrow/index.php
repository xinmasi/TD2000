<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("����Ǽ�")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("����Ǽ�")?>" class="BigButton" onClick="location='new.php';" title="<?=_("����Ǽ�")?>">
</div>

<br>
<?
$BORROW_COUNT=0;
$query = "SELECT count(*) from BOOK_MANAGE where BOOK_STATUS='0' and STATUS='1' and REG_FLAG='1' and RUSER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $BORROW_COUNT=$ROW[0];

if($BORROW_COUNT==0)
{
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span>&nbsp;
    </td>
  </tr>
</table>
<br>
<?
Message("",_("û�н�����Ϣ"));
$FLAG=1;
}

//�޸���������״̬--yc
update_sms_status('73',0);

if($FLAG!=1)
{
$PER_PAGE=15;
$PAGES=10;
$PAGE_COUNT=ceil($BORROW_COUNT/$PER_PAGE);

if($CUR_PAGE<=0 || $CUR_PAGE=="")
   $CUR_PAGE=1;
if($CUR_PAGE>$PAGE_COUNT)
   $CUR_PAGE=$PAGE_COUNT;

$MSG = sprintf(_("��%d��"),$BORROW_COUNT);
$MSG2 = sprintf(_("ÿҳ��ʾ%d��"), $PER_PAGE);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span>&nbsp;

    </td>
    <td align="right" valign="bottom" class="small1"><?=$MSG?>&nbsp;&nbsp;<?=$MSG2?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index.php?CUR_PAGE=1"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index.php?CUR_PAGE=<?=$PAGE_COUNT?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
$MSG3 = sprintf(_("��%dҳ"), $PAGES);
?>
      <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>"><?=$MSG3?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
   if($I>$PAGE_COUNT)
      break;

   if($I==$CUR_PAGE)
   {
?>
       [<?=$I?>]&nbsp;
<?
   }
   else
   {
?>
       [<a class="A1" href="index.php?CUR_PAGE=<?=$I?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
$MSG4 = sprintf(_("��%dҳ"), $PAGES);
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$I?>"><?=$MSG4?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE-1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE+1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}
?>
    </td>
    </tr>
</table>

<table class="TableList"  width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("ͼ����")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("�Ǽ���")?></td>
    <td nowrap align="center"><?=_("��ע")?></td>
    <td nowrap align="center" width="6%"><?=_("����")?></td>
  </tr>
<?
//============================ ��ʾͼ�����Աֱ�ӵǼǵ�ͼ�� =======================================
$query = "SELECT * from BOOK_MANAGE where BOOK_STATUS='0' and STATUS='1' and REG_FLAG='1' and RUSER_ID='".$_SESSION["LOGIN_USER_ID"]."'order by RETURN_DATE desc";
$cursor= exequery(TD::conn(),$query);
$BORROW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $BORROW_COUNT++;

   if($BORROW_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
      continue;
   if($BORROW_COUNT>$CUR_PAGE*$PER_PAGE)
      break;

   $BORROW_ID=$ROW["BORROW_ID"];
   $BUSER_ID=$ROW["BUSER_ID"];
   $BOOK_NO=$ROW["BOOK_NO"];
   $BORROW_DATE=$ROW["BORROW_DATE"];
   $BORROW_REMARK=$ROW["BORROW_REMARK"];
   $RUSER_ID=$ROW["RUSER_ID"];
   $RETURN_DATE=$ROW["RETURN_DATE"];

   $query1="select * from USER where USER_ID='$BUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $USER_NAME1=$ROW["USER_NAME"];

   $query1="select * from USER where USER_ID='$RUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $USER_NAME2=$ROW["USER_NAME"];

   $query1="select * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $BOOK_NAME=$ROW["BOOK_NAME"];

   if($BORROW_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$USER_NAME1?></td>
      <td nowrap align="center"><?=$BOOK_NO?></td>
      <td nowrap align="center"><?=$BOOK_NAME?></td>
      <td nowrap align="center"><?=$BORROW_DATE?></td>
      <td nowrap align="center"><?=$RETURN_DATE?></td>
      <td nowrap align="center"><?=$USER_NAME2?></td>
      <td nowrap align="center"><?=$BORROW_REMARK?></td>
      <td nowrap align="center"><a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&OP_FLAG=11"><?=_("����")?></a></td>
    </tr>
<?
}//while
?>
</table>
<?
}
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("����ȷ��")?></span>&nbsp;
    </td>
  </tr>
</table>
<?
$query1 = "SELECT * from BOOK_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER_ID)";
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1))
   $MANAGE_DEPT_ID.=$ROW["MANAGE_DEPT_ID"].",";   

$query = "SELECT * from BOOK_MANAGE where BOOK_STATUS='0' and STATUS='0' and REG_FLAG='0' order by BORROW_DATE desc";
$cursor= exequery(TD::conn(),$query);
$BORROW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $BOOK_NO=$ROW["BOOK_NO"];

   $query1 = "SELECT * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $DEPT=$ROW1["DEPT"];
   if(!find_id($MANAGE_DEPT_ID,$DEPT) && !find_id($MANAGE_DEPT_ID, "ALL_DEPT"))     
      continue;
      
   $BORROW_COUNT++;

if($BORROW_COUNT==1)
{
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("ͼ����")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("�Ǽ���")?></td>
    <td nowrap align="center"><?=_("��ע")?></td>
    <td nowrap align="center" width="6%"><?=_("����")?></td>
  </tr>

<?	
}
   $BORROW_ID=$ROW["BORROW_ID"];
   $BUSER_ID=$ROW["BUSER_ID"];
   $BORROW_DATE=$ROW["BORROW_DATE"];
   $BORROW_REMARK=$ROW["BORROW_REMARK"];
   $RUSER_ID=$ROW["RUSER_ID"];
   $RETURN_DATE=$ROW["RETURN_DATE"];
   
   $query1="select * from USER where USER_ID='$BUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME1=$ROW["USER_NAME"];

   $query1="select * from USER where USER_ID='$RUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME2=$ROW["USER_NAME"];

   $query1="select * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $BOOK_NAME=$ROW["BOOK_NAME"];

   if($BORROW_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
      
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$USER_NAME1?></td>
      <td nowrap align="center"><?=$BOOK_NO?></td>
      <td nowrap align="center"><?=$BOOK_NAME?></td>
      <td nowrap align="center"><?=$BORROW_DATE?></td>
      <td nowrap align="center"><?=$RETURN_DATE?></td>
      <td nowrap align="center"><?=$USER_NAME2?></td>
      <td nowrap align="center"><?=$BORROW_REMARK?></td>
      <td nowrap align="center">
      	<a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&OP_FLAG=21"><?=_("ͬ��")?></a>
      	<a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&OP_FLAG=22"><?=_("�˻�")?></a>
      	</td>
    </tr>
<?
}//while
echo "</table>";
if($BORROW_COUNT==0)
   Message("",_("û�н���ȷ�ϼ�¼"));
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" align="absmiddle"><span class="big3"> <?=_("����ȷ��")?></span>&nbsp;
    </td>
  </tr>
</table>
<?
$query = "SELECT * from BOOK_MANAGE where BOOK_STATUS='1' and STATUS='0' and REG_FLAG='0' order by RETURN_DATE desc";
$cursor= exequery(TD::conn(),$query);
$BORROW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $BOOK_NO=$ROW["BOOK_NO"];

   $query1 = "SELECT * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $DEPT=$ROW1["DEPT"];

   if(!find_id($MANAGE_DEPT_ID,$DEPT) && !find_id($MANAGE_DEPT_ID, "ALL_DEPT"))     
      continue;

   $BORROW_COUNT++;

if($BORROW_COUNT==1)
{
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("������")?></td>
    <td nowrap align="center"><?=_("ͼ����")?></td>
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("�Ǽ���")?></td>
    <td nowrap align="center"><?=_("��ע")?></td>
    <td nowrap align="center" width="6%"><?=_("����")?></td>
  </tr>

<?	
}
   $BORROW_ID=$ROW["BORROW_ID"];
   $BUSER_ID=$ROW["BUSER_ID"];
   $BORROW_DATE=$ROW["BORROW_DATE"];
   $BORROW_REMARK=$ROW["BORROW_REMARK"];
   $RUSER_ID=$ROW["RUSER_ID"];
   $RETURN_DATE=$ROW["RETURN_DATE"];

   $query1="select * from USER where USER_ID='$BUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME1=$ROW["USER_NAME"];

   $query1="select * from USER where USER_ID='$RUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME2=$ROW["USER_NAME"];

   $query1="select * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $BOOK_NAME=$ROW["BOOK_NAME"];

   if($BORROW_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";      
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$USER_NAME1?></td>
      <td nowrap align="center"><?=$BOOK_NO?></td>
      <td nowrap align="center"><?=$BOOK_NAME?></td>
      <td nowrap align="center"><?=$BORROW_DATE?></td>
      <td nowrap align="center"><?=$RETURN_DATE?></td>
      <td nowrap align="center"><?=$USER_NAME2?></td>
      <td nowrap align="center"><?=$BORROW_REMARK?></td>
      <td nowrap align="center">
      	<a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&OP_FLAG=31"><?=_("ͬ��")?></a>
      	<a href="manage.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&OP_FLAG=32"><?=_("�˻�")?></a>      	
      	</td>
    </tr>
<?
}//while
echo "</table>";
if($BORROW_COUNT==0)
   Message("",_("û�л���ȷ�ϼ�¼"));
?>
</body>
</html>