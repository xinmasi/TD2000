<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("�ļ�����");
include_once("inc/header.inc.php");
?>
<script>
function open_file(FILE_ID)
{
 URL="./read_file.php?FILE_ID="+FILE_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=300;
 window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_file(FILE_ID,CUR_PAGE)
{
 msg='<?=_("ȷ��Ҫɾ�������ļ���")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}

function delete_all()
{
  delete_str="";
  for(i=0;i<document.getElementsByName('file_select').length;i++)
  {

      el=document.getElementsByName('file_select').item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName('file_select');
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ���ļ���������ѡ������һ����")?>");
     document.form1.reset();
     return;
  }


 msg='<?=_("ȷ��Ҫɾ����ѡ�е��ļ���")?>';
 if(window.confirm(msg))
 {
  url="./delete_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
  window.location=url;
 }
}
function recover_file(FILE_ID,CUR_PAGE)
{
 msg='<?=_("ȷ��Ҫ��ԭ�����ļ���")?>';
 if(window.confirm(msg))
 {
  URL="recover.php?FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}

function recover_all()
{
  delete_str="";
  for(i=0;i<document.getElementsByName('file_select').length;i++)
  {

      el=document.getElementsByName('file_select').item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName('file_select');
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫ��ԭ�ļ���������ѡ������һ����")?>");
     document.form1.reset();
     return;
  }


 msg='<?=_("ȷ��Ҫ��ԭ��ѡ�е��ļ���")?>';
 if(window.confirm(msg))
 {
  url="./recover_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
  window.location=url;
 }
}

function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_all()
{
 for (i=0;i<document.getElementsByName('file_select').length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
     document.getElementsByName('file_select').item(i).checked=true;
   else
     document.getElementsByName('file_select').item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
     document.getElementsByName('file_select').checked=true;
   else
     document.getElementsByName('file_select').checked=false;
 }
}
function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
   	  
}
</script>


<body class="bodycolor">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT count(*) from RMS_FILE where DEL_USER<>''";
 else
    $query = "SELECT count(*) from RMS_FILE where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' and DEL_USER<>''";

 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_FILE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_FILE_COUNT=$ROW[0];

 if($RMS_FILE_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�������ļ�")?></span>&nbsp;
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("���ļ�"));
   exit;
 }
 
 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_FILE_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�������ļ�")?></span>&nbsp;
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("��%s��"),"<span class='big4'>&nbsp;".$RMS_FILE_COUNT."</span>&nbsp;")?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("��%dҳ"),$PAGES)?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("��%dҳ"),$PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��һҳ")?></a>&nbsp;
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
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}
?>
       &nbsp;
    </td>
    </tr>
</table>
<?
if($ASC_DESC=="")
   $ASC_DESC="1";
if($FIELD=="")
   $FIELD="FILE_CODE";
 //============================ ��ʾ�ѷ����ļ� =======================================
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT * from RMS_FILE where DEL_USER<>''";
 else
    $query = "SELECT * from RMS_FILE where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' and DEL_USER<>''";

$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
   
?>
<br>
<table class="TableList" width="100%">
  <form action="?"  method="post" name="form1">
  <tr class="TableHeader">
	  <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"></td>
      <td nowrap align="center" onclick="order_by('FILE_CODE','<?if($FIELD=="FILE_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�ļ���")?></u><?if($FIELD=="FILE_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('FILE_TITLE','<?if($FIELD=="FILE_TITLE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�ļ�����")?></u><?if($FIELD=="FILE_TITLE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("�ܼ�")?></td>
      <td nowrap align="center" onclick="order_by('SEND_UNIT','<?if($FIELD=="SEND_UNIT"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("���ĵ�λ")?></u><?if($FIELD=="SEND_UNIT"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('SEND_DATE','<?if($FIELD=="SEND_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("����ʱ��")?></u><?if($FIELD=="SEND_DATE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>

<?
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_FILE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_FILE_COUNT++;
    
    if($RMS_FILE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_FILE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $SECRET=$ROW["SECRET"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $URGENCY=$ROW["URGENCY"];
    $ROLL_ID=$ROW["ROLL_ID"];
    
    $query1 = "SELECT * from RMS_ROLL where ROLL_ID='$ROLL_ID'";
	  $cursor1= exequery(TD::conn(),$query1);
	  if($ROW=mysql_fetch_array($cursor1))
	     $ROLL_NAME=$ROW["ROLL_NAME"];    

    $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
	  if($SEND_DATE=='0000-00-00') $SEND_DATE='';

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");
       
    if($RMS_FILE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
	  <td nowrap align="center"><input type="checkbox" name="file_select" value="<?=$FILE_ID?>" onClick="check_one(self);"></td>
      <td align="center">
      <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
      </td>
      <td nowrap align="center"><?=$FILE_TITLE?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$SEND_UNIT?></td>
      <td nowrap align="center"><?=$SEND_DATE?></td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center">
      <a href="javascript:recover_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("��ԭ")?></a>
      <a href="javascript:delete_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
 }
?>

<tr class="TableControl">
<td colspan="9" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button"  value="<?=_("������ԭ")?>" class="SmallButton" onClick="recover_all()" title="<?=_("��ԭ��ѡ���ļ�")?>">&nbsp;&nbsp;
    <input type="button"  value="<?=_("����ɾ��")?>" class="SmallButton" onClick="delete_all()" title="<?=_("ɾ����ѡ���ļ�")?>">
</td>
</tr>
</form>

</table>
</body>

</html>
