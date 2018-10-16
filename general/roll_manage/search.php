<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("案卷查询");
include_once("inc/header.inc.php");

?>


<script>
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
 msg='<?=_("确认要删除该项案卷么？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ROLL_ID=" + ROLL_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}


function delete_all()
{
 msg='<?=_("确认要删除所有案卷么？")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
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
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&ROOM_ID0="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
</script>

<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
  //----------- 合法性校验 ---------
  if($BEGIN_DATE0!="")
  {
    $TIME_OK=is_date($BEGIN_DATE0);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
  }

  if($BEGIN_DATE1!="")
  {
    $TIME_OK=is_date($BEGIN_DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
  }
  if($END_DATE0!="")
  {
    $TIME_OK=is_date($END_DATE0);

    if(!$TIME_OK)
    { Message(_("错误"),_("终止日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
  }

  if($END_DATE1!="")
  {
    $TIME_OK=is_date($END_DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("终止日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
  }
 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";
 if($ROLL_CODE!="")
    $CONDITION_STR.=" and ROLL_CODE like '%".$ROLL_CODE."%'";
 if($ROLL_NAME!="")
    $CONDITION_STR.=" and ROLL_NAME like '%".$ROLL_NAME."%'";
 if($ROOM_ID!="")
    $CONDITION_STR.=" and RMS_ROLL.ROOM_ID='$ROOM_ID'";
 if($YEARS!="")
    $CONDITION_STR.=" and YEARS like '%".$YEARS."%'";
	
 if($BEGIN_DATE0!="")
    $CONDITION_STR.=" and BEGIN_DATE>='$BEGIN_DATE0'";
 if($BEGIN_DATE1!="")
    $CONDITION_STR.=" and BEGIN_DATE<='$BEGIN_DATE1'";
 if($END_DATE0!="")
    $CONDITION_STR.=" and RMS_ROLL.END_DATE>=$END_DATE0";
 if($END_DATE1!="")
    $CONDITION_STR.=" and RMS_ROLL.END_DATE<=$END_DATE1";
 if($DEADLINE0!="")
    $CONDITION_STR.=" and RMS_ROLL.DEADLINE>=$DEADLINE0";
 if($DEADLINE1!="")
    $CONDITION_STR.=" and RMS_ROLL.DEADLINE<=$DEADLINE1";
 if($SECRET!="")
    $CONDITION_STR.=" and RMS_ROLL.SECRET='$SECRET'";
 if($CERTIFICATE_KIND!="")
    $CONDITION_STR.=" and RMS_ROLL.CERTIFICATE_KIND='$CERTIFICATE_KIND'";
 if($CATEGORY_NO!="")
    $CONDITION_STR.=" and RMS_ROLL.CATEGORY_NO='$CATEGORY_NO'";
 if($CATALOG_NO!="")
    $CONDITION_STR.=" and RMS_ROLL.CATALOG_NO='$CATALOG_NO'";
 if($ARCHIVE_NO!="")
    $CONDITION_STR.=" and RMS_ROLL.ARCHIVE_NO='$ARCHIVE_NO'";
 if($BOX_NO!="")
    $CONDITION_STR.=" and RMS_ROLL.BOX_NO='$BOX_NO'";
 if($MICRO_NO!="")
    $CONDITION_STR.=" and RMS_ROLL.MICRO_NO='$MICRO_NO'";
 if($CERTIFICATE_START0!="")
    $CONDITION_STR.=" and RMS_ROLL.CERTIFICATE_START>=$CERTIFICATE_START0";
 if($CERTIFICATE_START1!="")
    $CONDITION_STR.=" and RMS_ROLL.CERTIFICATE_START<=$CERTIFICATE_START1";
 if($CERTIFICATE_END0!="")
    $CONDITION_STR.=" and RMS_ROLL.CERTIFICATE_END>=$CERTIFICATE_END0";
 if($CERTIFICATE_END1!="")
    $CONDITION_STR.=" and RMS_ROLL.CERTIFICATE_END<=$CERTIFICATE_END1";
 if($ROLL_PAGE0!="")
    $CONDITION_STR.=" and RMS_ROLL.ROLL_PAGE>=$ROLL_PAGE0";
 if($ROLL_PAGE1!="")
    $CONDITION_STR.=" and RMS_ROLL.ROLL_PAGE<=$ROLL_PAGE1";
 if($DEPT_ID!="")
    $CONDITION_STR.=" and RMS_ROLL.DEPT_ID='$DEPT_ID'";
 if($REMARK!="")
    $CONDITION_STR.=" and RMS_ROLL.REMARK like '%$REMARK%'";
	
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("案卷查询结果")?></span><br>
    </td>
  </tr>
</table>

<?
if($_SESSION["LOGIN_USER_PRIV"]=="1") {
    $query = "SELECT * from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where 1=1";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
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
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT * from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (find_in_set(RMS_ROLL.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."'))";
    }else{
        $query = "SELECT * from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
    }
}
else
{
    $query = "SELECT * from RMS_ROLL left outer join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."')";
}
$query.=$CONDITION_STR." order by ROLL_CODE desc";
$cursor=exequery(TD::conn(),$query);
$RMS_ROLL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $RMS_ROLL_COUNT++;
    $ROLL_ID=$ROW["ROLL_ID"];
    $ROLL_CODE=$ROW["ROLL_CODE"];
    $ROLL_NAME=$ROW["ROLL_NAME"];
    $ROOM_NAME=$ROW["ROOM_NAME"];
    $ADD_USER=$ROW["ADD_USER"];
    $CATEGORY_NO=$ROW["CATEGORY_NO"];
    $CERTIFICATE_KIND=$ROW["CERTIFICATE_KIND"];
    $SECRET=$ROW["SECRET"];
    $STATUS=$ROW["STATUS"];
    $BORROW=$ROW["BORROW"];
	if($STATUS==0)
		$STATUS_DESC=_("未封卷");
	else
		$STATUS_DESC=_("已封卷");
	if($BORROW==0)
		$BORROW_DESC=_("已公开");
	else
		$BORROW_DESC=_("需审批");
    
    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $CERTIFICATE_KIND=get_code_name($CERTIFICATE_KIND,"RMS_CERTIFICATE_KIND");

    if($RMS_ROLL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
    
   if($RMS_ROLL_COUNT==1)
   {
?>
<table class="TableList" width="95%">
  <tr class="TableHeader">
      <td nowrap align="center"><u><?=_("案卷号")?></u><?=$ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("案卷名称")?></td>
      <td nowrap align="center"><?=_("所属卷库")?></td>
      <td nowrap align="center"><?=_("全宗号")?></td>
      <td nowrap align="center"><?=_("凭证类别")?></td>
      <td nowrap align="center"><?=_("案卷密级")?></td>
      <td nowrap align="center"><?=_("案卷状态")?></td>
      <td nowrap align="center"><?=_("借阅")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
   if($RMS_ROLL_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
 <tr class="<?=$TableLine?>">
      <td align="center">
      <a href="javascript:open_roll('<?=$ROLL_ID?>');"><?=$ROLL_CODE?></a>
      </td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center"><?=$ROOM_NAME?></td>
      <td nowrap align="center"><?=$CATEGORY_NO?></td>
      <td nowrap align="center"><?=$CERTIFICATE_KIND?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center"><?=$BORROW_DESC?></td>
      <td nowrap align="center">
      <a href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("查看文件")?></a>
      <a href="status.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("拆卷/封卷")?></a>
      <a href="modify.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("修改")?></a>
      <a href="javascript:delete_roll('<?=$ROLL_ID?>','<?=$CUR_PAGE?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
}

if($RMS_ROLL_COUNT==0)
{
   Message("",_("无符合条件的案卷"));
   Button_Back();
   exit;
}
else
{
?>
</table>
<?
Button_Back();
}

?>
</body>

</html>
