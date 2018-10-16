<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("案卷管理");
include_once("inc/header.inc.php");
?>



<script>

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
function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
function open_roll(ROLL_ID)
{
 URL="./read_roll.php?ROLL_ID="+ROLL_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=400;
 window.open(URL,"read_roll","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_roll(ROLL_ID,CUR_PAGE)
{
 msg='<?=_("确认要删除该项案卷吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ROLL_ID=" + ROLL_ID + "&CUR_PAGE=" + CUR_PAGE;
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
     alert("<?=_("要删除案卷，请至少选择其中一个。")?>");
     return;
  }


 msg='<?=_("确认要删除已选中的案卷吗？")?>';
 if(window.confirm(msg))
 {
  url="./delete_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
  window.location=url;
 }
}

function re_roll(ROLL_ID)
{
 URL="/general/roll/show/re_roll.php?ROLL_ID="+ROLL_ID+"&MANAGE=1";
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_roll","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&ROOM_ID0="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
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
</script>


<body class="bodycolor">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1") {
     $query = "SELECT count(*) from RMS_ROLL where 1=1";
 }else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
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
    if($user_id!='')
    {
        $query = "SELECT count(*) from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where find_in_set(RMS_ROLL.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."')";
    }
    else
    {
        $query = "SELECT count(*) from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."'";
    }
 }
 else
 {
    $query = "SELECT count(*) from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."'";
 }
 if($ROOM_ID0!='')
	$query.=" and RMS_ROLL.ROOM_ID='$ROOM_ID0'";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_ROLL_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_ROLL_COUNT=$ROW[0];

 if($RMS_ROLL_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理案卷")?></span>&nbsp;
       <select name="ROOM_ID0" class="BigSelect" onChange="change_type(this.value);">
          <option value=""><?=_("所有卷库")?></option>
<?
	$query_str = '';
	$DEPT_ID   = '';
	$DEPT_ID   = get_dept_parent_all($_SESSION['LOGIN_DEPT_ID']);
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
    {
        $dept_id1 = get_manage_dept_ids($_SESSION['LOGIN_UID']);
        if($dept_id1)
        {
            $dept_str = $dept_id1;
        }
        else
        {
            $dept_str = $_SESSION["LOGIN_DEPT_ID"];
        }
        $query_str.= ' where DEPT_ID =0 or FIND_IN_SET (DEPT_ID,"'.$dept_str.'") ';
    }
    else
    {
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
        {
            $query_str.=' where DEPT_ID =0 or DEPT_ID in ('.$DEPT_ID.$_SESSION['LOGIN_DEPT_ID'].')';
        }
        if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION['LOGIN_DEPT_ID_OTHER']!="")
        {
            $query_str.= 'or FIND_IN_SET (DEPT_ID,"'.($_SESSION['LOGIN_DEPT_ID_OTHER']).'") ';
        }
    }
    $query1  = 'select * from RMS_ROLL_ROOM '.$query_str.' order by ROOM_CODE desc';
    $cursor1 = exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
       $ROOM_ID   = $ROW["ROOM_ID"];
       $ROOM_NAME = $ROW["ROOM_NAME"];
?>
          <option value="<?=$ROOM_ID?>" <?if($ROOM_ID0==$ROOM_ID) echo " selected";?>><?=$ROOM_NAME?></option>
<?
	}
?>
       </select>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("无已发布的案卷"));
   exit;
 }

 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_ROLL_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理案卷")?></span>&nbsp;
       <select name="ROOM_ID0" class="BigSelect" onChange="change_type(this.value);">
          <option value=""><?=_("所有卷库")?></option>
	<?
    $query_str = '';
	$DEPT_ID   = '';
	$DEPT_ID   = get_dept_parent_all($_SESSION['LOGIN_DEPT_ID']);
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
    {
        $dept_id2 = get_manage_dept_ids($_SESSION['LOGIN_UID']);
        if($dept_id2)
        {
            $dept_str = $dept_id2;
        }
        else
        {
            $dept_str = $_SESSION["LOGIN_DEPT_ID"];
        }
        $query_str.=' where DEPT_ID =0 or FIND_IN_SET (DEPT_ID,"'.$dept_str.'") ';
    }
    else
    {
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
        {
            $query_str.=' where DEPT_ID =0 or DEPT_ID in ('.$DEPT_ID.$_SESSION['LOGIN_DEPT_ID'].')';
        }
        if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION['LOGIN_DEPT_ID_OTHER']!="")
        {
            $query_str.= 'or FIND_IN_SET (DEPT_ID,"'.($_SESSION['LOGIN_DEPT_ID_OTHER']).'") ';
        }
    }
    $query1  = 'select * from RMS_ROLL_ROOM '.$query_str.' order by ROOM_CODE desc';
    $cursor1 = exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
       $ROOM_ID   = $ROW["ROOM_ID"];
       $ROOM_NAME = $ROW["ROOM_NAME"];
	?>
          <option value="<?=$ROOM_ID?>" <?if($ROOM_ID0==$ROOM_ID) echo " selected";?>><?=$ROOM_NAME?></option>
	<?
	}
	?>
       </select>
    </td>

    <td align="right" valign="bottom" class="small1"><?=sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$RMS_ROLL_COUNT."</span>&nbsp;")?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&ROOM_ID0=<?=$ROOM_ID0?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("上%d页"),$PAGES)?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&ROOM_ID0=<?=$ROOM_ID0?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("下%d页"),$PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("上一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("上一页")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>&ROOM_ID0=<?=$ROOM_ID0?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("下一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("下一页")?>&nbsp;
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
   $FIELD="ROLL_CODE";
 //============================ 显示已发布案卷 =======================================
if($_SESSION["LOGIN_USER_PRIV_TYPE"]=="1") {//oa 管理员
     $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where 1=1";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
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
    if($user_id!='')
    {
        if($ROOM_ID0!="")
            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ROOM_ID='$ROOM_ID0' and (find_in_set(RMS_ROLL.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."'))";
        else
            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
    }
    else
    {
        if($ROOM_ID0!="")
            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ROOM_ID='$ROOM_ID0' and (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
        else
            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
    }
}
else
{
   if($ROOM_ID0!="")
       $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ROOM_ID='$ROOM_ID0' and (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
   else
       $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";    
}

if($ROOM_ID0!="")
   $query .= " and RMS_ROLL.ROOM_ID='$ROOM_ID0'";
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
  <tr class="TableHeader">
	  <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"></td>
      <td nowrap align="center" onClick="order_by('ROLL_CODE','<?if($FIELD=="ROLL_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷号")?></u><?if($FIELD=="ROLL_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('ROLL_NAME','<?if($FIELD=="ROLL_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷名称")?></u><?if($FIELD=="ROLL_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('ROOM_NAME','<?if($FIELD=="ROOM_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("所属卷库")?></u><?if($FIELD=="ROOM_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('CATEGORY_NO','<?if($FIELD=="CATEGORY_NO"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("全宗号")?></u><?if($FIELD=="CATEGORY_NO"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('CERTIFICATE_KIND','<?if($FIELD=="CERTIFICATE_KIND") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("凭证类别")?></u><?if($FIELD=="CERTIFICATE_KIND") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('SECRET','<?if($FIELD=="SECRET"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷密级")?></u><?if($FIELD=="SECRET"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('STATUS','<?if($FIELD=="STATUS") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷状态")?></u><?if($FIELD=="STATUS") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_ROLL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_ROLL_COUNT++;

    if($RMS_ROLL_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_ROLL_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $ROLL_ID=$ROW["ROLL_ID"];
    $ROLL_CODE=$ROW["ROLL_CODE"];
    $ROLL_NAME=$ROW["ROLL_NAME"];
    $ROOM_NAME=$ROW["ROOM_NAME"];
    $ADD_USER=$ROW["ADD_USER"];
    $CATEGORY_NO=$ROW["CATEGORY_NO"];
    $CERTIFICATE_KIND=$ROW["CERTIFICATE_KIND"];
    $SECRET=$ROW["SECRET"];
    $STATUS=$ROW["STATUS"];

	if($STATUS==0)
		$STATUS_DESC=_("未封卷");
	else
		$STATUS_DESC=_("已封卷");

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $CERTIFICATE_KIND=get_code_name($CERTIFICATE_KIND,"RMS_CERTIFICATE_KIND");
    $DEPT_NAME=dept_long_name($DEPT_ID);

    if($RMS_ROLL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
	  <td nowrap align="center"><input type="checkbox" name="file_select" value="<?=$ROLL_ID?>" onClick="check_one(self);"></td>
      <td align="center">
      <a href="javascript:open_roll('<?=$ROLL_ID?>');"><?=$ROLL_CODE?></a>
      </td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center"><?=$ROOM_NAME?></td>
      <td nowrap align="center"><?=$CATEGORY_NO?></td>
      <td nowrap align="center"><?=$CERTIFICATE_KIND?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center">
      <a href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("查看文件")?></a>
      <a href="status.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("拆卷/封卷")?></a>
      <a href="modify.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("修改")?></a>
      <a href="javascript:delete_roll('<?=$ROLL_ID?>','<?=$CUR_PAGE?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }
?>

<tr class="TableControl">
<td colspan="9" align="center">
    <input type="button"  value="<?=_("批量删除")?>" class="SmallButton" onClick="delete_all()" title="<?=_("删除所选中的案卷")?>">
</td>
</tr>

</table>
</body>
</html>
