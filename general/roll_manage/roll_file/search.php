<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�ļ���ѯ");
include_once("inc/header.inc.php");
?>


<script>
function open_file(FILE_ID)
{
 URL="../read_file.php?FILE_ID="+FILE_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=400;
 window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_file(FILE_ID,CUR_PAGE)
{
 msg='<?=_("ȷ��Ҫ���ٸ����ļ���")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}


function delete_all()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫ�����ļ���������ѡ������һ����")?>");
     document.form1.reset();
     return;
  }


 msg='<?=_("ȷ��Ҫ������ѡ�е��ļ���")?>';
 if(window.confirm(msg))
 {
  url="./delete_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
  window.location=url;
 }
}
function export_all()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("������ѡ������һ����")?>");
     document.form1.reset();
     return;
  }
  url="export_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
  window.open(url);
}

function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function file_troop(type)
{
	document.form1.action="troop.php?CUR_PAGE=<?=$CUR_PAGE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
	document.form1.target='_self';
	document.form1.submit();
}

function check_all()
{
 for (i=0;i<document.getElementsByName("file_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").item(i).checked=true;
   else
      document.getElementsByName("file_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").checked=true;
   else
      document.getElementsByName("file_select").checked=false;
 }
}

function change_roll()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫ����ļ���������ѡ������һ����")?>");
     document.form1.reset();
     return;
  }

  roll_id=document.getElementsByName("ROLL_ID").value;
  url="./change_roll.php?DELETE_STR="+ delete_str +"&ROLL_ID="+roll_id+"&PAGE_START=<?=$PAGE_START?>";
  location=url;
}
function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
</script>

<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
  //----------- �Ϸ���У�� ---------
  if($SEND_DATE0!="")
  {
    $TIME_OK=is_date($SEND_DATE0);

    if(!$TIME_OK)
    { Message(_("����"),_("�������ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $SEND_DATE0=$SEND_DATE0." 00:00:00";
  }

  if($SEND_DATE1!="")
  {
    $TIME_OK=is_date($SEND_DATE1);

    if(!$TIME_OK)
    { Message(_("����"),_("�������ڵĸ�ʽ���ԣ�Ӧ���� ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $SEND_DATE1=$SEND_DATE1." 23:59:59";
  }

 //------------------------ ���������ַ��� ------------------
 $CONDITION_STR="";
 if($FILE_CODE!="")
    $CONDITION_STR.=" and FILE_CODE like '%".$FILE_CODE."%'";
 if($FILE_SUBJECT!="")
    $CONDITION_STR.=" and FILE_SUBJECT like '%".$FILE_SUBJECT."%'";
 if($FILE_TITLE!="")
    $CONDITION_STR.=" and FILE_TITLE like '%".$FILE_TITLE."%'";
 if($FILE_TITLE0!="")
    $CONDITION_STR.=" and FILE_TITLE0 like '%".$FILE_TITLE0."%'";
 if($SEND_UNIT!="")
    $CONDITION_STR.=" and SEND_UNIT like '%".$SEND_UNIT."%'";
 if($REMARK!="")
    $CONDITION_STR.=" and RMS_FILE.REMARK like '%".$REMARK."%'";
if($ROOM_ID!="")
 		$CONDITION_STR.=" and RMS_ROLL.ROOM_ID='".$ROOM_ID."'";
    
 if($ROLL_ID!="")
 		$CONDITION_STR.=" and RMS_FILE.ROLL_ID='".$ROLL_ID."'";
 else
 {
 		if($ROOM_ID!="")
 		{
			$ROLL_IDS = "";
 			$query_room="select DISTINCT ROLL_ID from RMS_ROLL where ROOM_ID='".$ROOM_ID."'";
 			$cursor_room=exequery(TD::conn(),$query_room);
 			while($ROW_ROOM=mysql_fetch_array($cursor_room))
 				$ROLL_IDS .= $ROW_ROOM["ROLL_ID"].",";
 			if(substr($ROLL_IDS,-1)==",")
 				$ROLL_IDS=substr($ROLL_IDS,0,-1);
 			$CONDITION_STR.=" and find_in_set(RMS_FILE.ROLL_ID,'$ROLL_IDS') ";
 		}
 }
 
 if($SECRET!="")
    $CONDITION_STR.=" and RMS_FILE.SECRET='".$SECRET."'";
 if($URGENCY!="")
    $CONDITION_STR.=" and URGENCY='".$URGENCY."'";
 if($FILE_TYPE!="")
    $CONDITION_STR.=" and FILE_TYPE='".$FILE_TYPE."'";
 if($FILE_KIND!="")
    $CONDITION_STR.=" and FILE_KIND='".$FILE_KIND."'";
	
 if($SEND_DATE0!="")
    $CONDITION_STR.=" and SEND_DATE>='$SEND_DATE0'";
 if($SEND_DATE1!="")
    $CONDITION_STR.=" and SEND_DATE<='$SEND_DATE1'";
 if($FILE_PAGE0!="")
    $CONDITION_STR.=" and FILE_PAGE>='$FILE_PAGE0'";
 if($FILE_PAGE1!="")
    $CONDITION_STR.=" and FILE_PAGE<='$FILE_PAGE1'";
 if($PRINT_PAGE0!="")
    $CONDITION_STR.=" and PRINT_PAGE>='$PRINT_PAGE0'";
 if($PRINT_PAGE1!="")
    $CONDITION_STR.=" and PRINT_PAGE<='$PRINT_PAGE1'";

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("�ļ���ѯ���")?></span><br>
    </td>
  </tr>
</table>

<?
if($_SESSION["LOGIN_USER_PRIV"]=="1") {
    $query = "SELECT FILE_ID,FILE_CODE,FILE_TITLE,RMS_FILE.SECRET,SEND_UNIT,SEND_DATE,URGENCY,RMS_FILE.ROLL_ID from RMS_FILE ,RMS_ROLL where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_FILE.DEL_USER=''";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT FILE_ID,FILE_CODE,FILE_TITLE,RMS_FILE.SECRET,SEND_UNIT,SEND_DATE,URGENCY,RMS_FILE.ROLL_ID from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (find_in_set(RMS_FILE.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."')) and RMS_FILE.DEL_USER=''";
    }else{
        $query = "SELECT FILE_ID,FILE_CODE,FILE_TITLE,RMS_FILE.SECRET,SEND_UNIT,SEND_DATE,URGENCY,RMS_FILE.ROLL_ID from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (RMS_FILE.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."') and RMS_FILE.DEL_USER=''";
    }
}
else
{
    $query = "SELECT FILE_ID,FILE_CODE,FILE_TITLE,RMS_FILE.SECRET,SEND_UNIT,SEND_DATE,URGENCY,RMS_FILE.ROLL_ID from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (RMS_FILE.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."') and RMS_FILE.DEL_USER=''";
}
$query.=$CONDITION_STR." order by SEND_DATE desc";
$cursor=exequery(TD::conn(),$query);
$COUNT_NUM = mysql_num_rows($cursor);
 $RMS_FILE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_FILE_COUNT++;
   
    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $SECRET=$ROW["SECRET"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $URGENCY=$ROW["URGENCY"];
    $ROLL_ID=$ROW["ROLL_ID"];
    $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
	  if($SEND_DATE=='0000-00-00') 
	     $SEND_DATE='';

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");

    $query1 = "SELECT * from RMS_ROLL where ROLL_ID='$ROLL_ID'";
	  $cursor1=exequery(TD::conn(),$query1);
	  $STATUS="";
	  if($ROW=mysql_fetch_array($cursor1))
	  {
	  	 $ROLL_NAME=$ROW["ROLL_NAME"];
	     $STATUS=$ROW["STATUS"];	  	 
	  }

    if($RMS_FILE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
    
   if($RMS_FILE_COUNT==1)
   {
?>
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small" align="center">
	<tr><td align="right"><?=sprintf(_("��%d������"), $COUNT_NUM)?></td></tr>
</table>
<table class="TableList"  width="95%">
  <tr class="TableHeader">
	  <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"></td>    
      <td nowrap align="center"><?=_("�ļ���")?></td>
      <td nowrap align="center"><?=_("�ļ�����")?></td>
      <td nowrap align="center"><?=_("�ܼ�")?></td>
      <td nowrap align="center"><?=_("���ĵ�λ")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
   }
?>
    <tr class="<?=$TableLine?>">
	  <td nowrap align="center">
<?
if($STATUS!=1)
{
?> 	  	
	  	<input type="checkbox" name="file_select" value="<?=$FILE_ID?>" onClick="check_one(self);">
<?
}
else
   echo "&nbsp;";
?>    	  	
	  	</td>
      <td align="center">
      <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
      </td>
      <td nowrap align="center"><?=$FILE_TITLE?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$SEND_UNIT?></td>
      <td nowrap align="center"><?=$SEND_DATE?></td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center">
<?
if($STATUS!=1)
{
?>  
      <a href="modify.php?FILE_ID=<?=$FILE_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�޸�")?></a>
      <a href="javascript:delete_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("����")?></a>
<?
}
else
   echo _("�����ѷ�");
?>        
      </td>
    </tr>
<?
 }
if($RMS_FILE_COUNT==0)
{
   Message("",_("�޷����������ļ�"));
   Button_Back();
   exit;
}
else
{
?>
<tr class="TableControl">
<td colspan="9">
<b><?=_("�ļ�������")?></b>
	
	<?=_("�������")?><select name=ROLL_ID onChange="change_roll();" class="SmallSelect">
		<option value=""><?=_("��ѡ�񰸾�")?></option>
	<?
  $query = "SELECT * from RMS_ROLL where STATUS=0";
	$cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
	{	
		$ROLL_ID=$ROW["ROLL_ID"];
		$ROLL_CODE=$ROW["ROLL_CODE"];
		$ROLL_NAME=$ROW["ROLL_NAME"];
	?>
		<option value=<?=$ROLL_ID?>><?=$ROLL_CODE?> - <?=$ROLL_NAME?></option>
	<?
	 }	
	?>
	</select>


    <input type="button"  value="<?=_("��������")?>" class="SmallButton" onClick="delete_all()" title="<?=_("������ѡ���ļ�")?>">&nbsp;
    <input type="button"  value="<?=_("��������")?>" class="SmallButton" onClick="export_all()" title="<?=_("��������")?>">
</td>
</tr>
</table>
<?
Button_Back();
}

?>
</body>

</html>
