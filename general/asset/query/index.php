<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
if(!isset($TYPE))
   $TYPE="0";
if(!isset($PAGE_SIZE))
   $PAGE_SIZE =10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("�̶��ʲ���ѯ");
include_once("inc/header.inc.php");
?>


<script>
function openWindow(url, width, height, target, params){

    var left = (screen.availWidth  - width)/2;
    var top  = (screen.availHeight - height)/2;
    
    
    var paramStr = "";
    if(typeof params == "undefined" || params == null){
        paramStr = "toolbar="     + "no,"
                 + "location="    + "no,"
                 + "status="      + "no,"
                 + "directories=" + "no,"
                 + "menubar="     + "no,"
                 + "scrollbars="  + "yes,"
                 + "resizable="   + "yes,"
                 + "left="        + left  + ", "
                 + "top="         + top   + ", "
                 + "width="       + width + ", "
                 + "height="      + height;
    }else{
        paramStr = "toolbar="     + ((typeof params['toolbar']  == "undefined" || params['toolbar']  == "") ? "no" : params['toolbar'])  + ","
                 + "location="    + ((typeof params['location'] == "undefined" || params['location'] == "") ? "no" : params['location']) + ","
                 + "status="      + ((typeof params['status']   == "undefined" || params['status']   == "") ? "no" : params['status'])   + ","
                 + "directories=" + ((typeof params['directories'] == "undefined" || params['directories'] == "") ? "no" : params['directories']) + ","
                 + "menubar="     + ((typeof params['menubar'] == "undefined"  || params['menubar'] == "") ? "no" : params['menubar']) + ","
                 + "scrollbars="  + ((typeof params['scrollbars'] == "undefined" || params['scrollbars'] == "") ? "yes" : params['menubar']) + ","
                 + "resizable="   + ((typeof params['resizable'] == "undefined"  || params['resizable'] == "") ? "yes" : params['resizable']) + ","
                 + "left="        + left  + ", "
                 + "top="         + top   + ", "
                 + "width="       + width + ", "
                 + "height="      + height;
    }
    if(typeof target != 'undefined'){
        window.open(url, target, paramStr);
    }else{
        window.open(url, "", paramStr);
    }
}
function ad_query()
{
  URL="ad_query.php";
  window.location=URL;
}
function cptl_detail(CPTL_ID)
{
	URL="cptl_detail.php?CPTL_ID="+CPTL_ID;
	myleft=screen.availWidth/2-300;
	window.open(URL,"read_notify","height=470,width=600,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function dpct_detail(CPTL_ID)
{
	URL="dpct_detail.php?CPTL_ID="+CPTL_ID;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_notify","height=400,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function order_by(field,asc_desc) {
	URL="index.php?CPTL_NO=<?=$CPTL_NO?>&CPTL_NAME=<?=urlencode($CPTL_NAME)?>&TYPE_ID=<?=$TYPE_ID?>&DEPT_ID=<?=$DEPT_ID?>&CPTL_KIND=<?=$CPTL_KIND?>&PRCS_ID=<?=$PRCS_ID?>&DCR_PRCS_ID=<?=$DCR_PRCS_ID?>&FINISH_FLAG=<?=$FINISH_FLAG?>&CPTL_VAL_MIN=<?=$CPTL_VAL_MIN?>&CPTL_VAL_MAX=<?=$CPTL_VAL_MAX?>&CREATE_DATE_MIN=<?=$CREATE_DATE_MIN?>&CREATE_DATE_MAX=<?=$CREATE_DATE_MAX?>&DCR_DATE_MIN=<?=$DCR_DATE_MIN?>&DCR_DATE_MAX=<?=$DCR_DATE_MAX?>&FROM_YYMM_MIN=<?=$FROM_YYMM_MIN?>&FROM_YYMM_MAX=<?=$FROM_YYMM_MAX?>&TO_NAME=<?=$TO_NAME?>&ORDER_FIELD="+field+"&ASC_DESC="+asc_desc;;
	window.location=URL;
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" width="24" height="24"><span class="big3"> <?=_("�̶��ʲ���ѯ���")?></span></td>
	</tr>
</table>

<?
$CUR_DATE=date("Y-m-d",time());
  //----------- �Ϸ���У�� ---------
if($CREATE_DATE_MIN!="")
{
	$TIME_OK=is_date($CREATE_DATE_MIN);
	if(!$TIME_OK)
	{
		 Message(_("����"),sprintf(_("�������ڵĿ�ʼ���ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
   	 Button_Back();
  		 exit;
 	}
}
if($CREATE_DATE_MAX!="")
{
	$TIME_OK=is_date($CREATE_DATE_MAX);
 	if(!$TIME_OK)
 	{
 		Message(_("����"),sprintf(_("�������ڵĽ������ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
   	Button_Back();
		exit;
	}
}
if($DCR_DATE_MIN!="")
{
	$TIME_OK=is_date($DCR_DATE_MIN);
	if(!$TIME_OK)
	{
		Message(_("����"),sprintf(_("�������ڵĿ�ʼ���ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
   	Button_Back();
   	exit;
	}
}
if($DCR_DATE_MAX!="")
{
	$TIME_OK=is_date($DCR_DATE_MAX);
	if(!$TIME_OK)
	{
		Message(_("����"),sprintf(_("�������ڵĽ������ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
		Button_Back();
		exit;
	}
}
if($FROM_YYMM_MIN!="")
{
	$TIME_OK=is_date($FROM_YYMM_MIN);
	if(!$TIME_OK)
	{
		Message(_("����"),sprintf(_("�������ڵĿ�ʼ���ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
      Button_Back();
      exit;
	}
}
if($FROM_YYMM_MAX!="")
{
	$TIME_OK=is_date($FROM_YYMM_MAX);
	if(!$TIME_OK)
	{
		Message(_("����"),sprintf(_("�������ڵĽ������ڸ�ʽ���ԣ�Ӧ���� %s"),$CUR_DATE));
      Button_Back();
      exit;
   }
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($CPTL_NO!="")
	$CONDITION_STR.=" and CPTL_NO like '%".$CPTL_NO."%'";
if($CPTL_NAME!="")
	$CONDITION_STR.=" and CPTL_NAME like '%".$CPTL_NAME."%'";
if($TYPE_ID!="")
	$CONDITION_STR.=" and CP_CPTL_INFO.TYPE_ID='$TYPE_ID'";
if($DEPT_ID!="")
	$CONDITION_STR.=" and CP_CPTL_INFO.DEPT_ID='$DEPT_ID'";
if($CPTL_KIND!="")
	$CONDITION_STR.=" and CPTL_KIND='$CPTL_KIND'";
if($PRCS_ID!="")
	$CONDITION_STR.=" and PRCS_ID='$PRCS_ID'";
if($DCR_PRCS_ID!="")
	$CONDITION_STR.=" and DCR_PRCS_ID='$DCR_PRCS_ID'";
if($FINISH_FLAG!="")
	$CONDITION_STR.=" and FINISH_FLAG='$FINISH_FLAG'";
if($CPTL_VAL_MIN!="")
	$CONDITION_STR.=" and CPTL_VAL>='$CPTL_VAL_MIN'";
if($CPTL_VAL_MAX!="")
	$CONDITION_STR.=" and CPTL_VAL<='$CPTL_VAL_MAX'";
if($CPTL_BAL_MIN!="")
	$CONDITION_STR.=" and CPTL_BAL>='$CPTL_BAL_MIN'";
if($CPTL_BAL_MAX!="")
	$CONDITION_STR.=" and CPTL_BAL<='$CPTL_BAL_MAX'";
if($CREATE_DATE_MIN!="")
	$CONDITION_STR.=" and CREATE_DATE>='$CREATE_DATE_MIN'";
if($CREATE_DATE_MAX!="")
	$CONDITION_STR.=" and CREATE_DATE<='$CREATE_DATE_MAX'";
if($DCR_DATE_MIN!="")
	$CONDITION_STR.=" and DCR_DATE>='$DCR_DATE_MIN'";
if($DCR_DATE_MAX!="")
	$CONDITION_STR.=" and DCR_DATE<='$DCR_DATE_MAX'";
if($FROM_YYMM_MIN!="")
	$CONDITION_STR.=" and FROM_YYMM>='$FROM_YYMM_MIN'";
if($FROM_YYMM_MAX!="")
	$CONDITION_STR.=" and FROM_YYMM<='$FROM_YYMM_MAX'";
//...........
$keeper_name = $TO_NAME;
$keeper_id = "";
if($TO_NAME!="")
{
    $query1="select USER_ID from USER where USER_NAME='$TO_NAME'";
    $cursor1= exequery(TD::conn(),$query1);
    $NUM=mysql_num_rows($cursor1);
    if($ROWs=mysql_fetch_array($cursor1))
    {
        $keeper_id = $ROWs['USER_ID'];
    }
}
//...................
if($keeper_name!="" && $keeper_id!="")
{
   $CONDITION_STR.=" and (KEEPER like '%".$keeper_name."%' or KEEPER like '%".$keeper_id."%') ";
}
else if($keeper_name!="")
{
   $CONDITION_STR.=" and KEEPER like '%".$keeper_name."%' ";
}
if($REMARK!="")
	$CONDITION_STR.=" and CP_CPTL_INFO.REMARK like '%".$REMARK."%'";

$WHERE_CLAUSE = " where 1= 1 " . $CONDITION_STR;
$ORDER_CLAUSE = " order by CPTL_ID desc ";

$ORDER_STR = " order by CPTL_ID "; 

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

if($ORDER_FIELD=="DEPT_ID") {
	$ORDER_STR = " order by DEPT_ID";
}

if($ORDER_FIELD=="TYPE_ID") {
	$ORDER_STR = " order by TYPE_ID";
}

if($ASC_DESC=="")
   $ASC_DESC="1";

if($ASC_DESC=="1")
   $ORDER_STR .= " DESC";
else
   $ORDER_STR .= " ASC";

$LIMIT_CLAUSE = " limit $start,$PAGE_SIZE";

?>
<fieldset style="width:90%;padding-bottom:5px;" align="center">

  <legend class="small" align=left>
      <b><?=_("��ݲ���")?></b>
  </legend>
<table  class="TableList" align="right" width="100%">
  <form action="index.php"  method="post" name="form1">
  <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ���ţ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NO" size="20" maxlength="100" class="BigInput" value="<?=$CPTL_NO?>">
      </td>

	  <td nowrap class="TableData" width="100"> <?=_("�ʲ����ƣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="30" maxlength="200" class="BigInput" value="<?=$CPTL_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value="" <?if($TYPE_ID=="")echo "selected"; ?> ><?=_("�������")?></option>
        <option value="0" <?if($TYPE_ID=="0")echo "selected";?>><?=_("δָ�����")?></option>
<?
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $QUERY_TYPE_ID=$ROW["TYPE_ID"];
    $QUERY_TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$QUERY_TYPE_ID?>" <? if($TYPE_ID==$QUERY_TYPE_ID) echo "selected"; ?>><?=$QUERY_TYPE_NAME?></option>
<?
 }
?>
	  </select>
      </td>
	   <td nowrap class="TableData" width="100"> <?=_("�������ţ�")?></td>
      <td class="TableData">
      <select name="DEPT_ID" class="BigSelect">
        <option value=""><?=_("���в���")?></option>
        <option value="0"><?=_("δָ������")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,0);
?>
      </select>
      </td>
    </tr>
   <tr>
 	<td nowrap class="TableData"  colspan="8" >
 	&nbsp;&nbsp;<input value="<?=_("���ٲ�ѯ")?>" type="submit" class="SmallButton" title="<?=_("���ٲ�ѯ")?>" name="button">
  <input value="<?=_("�߼���ѯ")?>" type="button" class="SmallButton" title="<?=_("ģ����ѯ")?>" name="button1" onClick="ad_query();">
  <input type="button" value="<?=_("����")?>" class="SmallButton"        onclick="openWindow('export/conditional.php?WHERE_CLAUSE=<?=urlencode($WHERE_CLAUSE)?>&ORDER_CLAUSE=<?=urlencode($ORDER_STR)?>&LIMIT_CLAUSE=<?=urlencode($LIMIT_CLAUSE)?>', '600','400', 'theParam');"/>
  </td>
 </tr>
 </form>
</table>
</fieldset>
<?
if(!isset($TOTAL_ITEMS))
{
	$CPTL_VAL_COUNT=0;
	$TOTAL_ITEMS=0;
	$query = "SELECT CPTL_VAL from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")." order by DEPT_ID,CREATE_DATE,CPTL_NO";
	$cursor= exequery(TD::conn(),$query);
	$TOTAL_ITEMS=0;
	while($ROW=mysql_fetch_array($cursor))
	{
		$CPTL_VAL=$ROW["CPTL_VAL"];
 		$TOTAL_ITEMS++;
 		$CPTL_VAL_COUNT+=$CPTL_VAL;
 		//echo $CPTL_VAL.'<br>'.$CPTL_VAL_COUNT;
 	}
}
$query="select * from CP_CPTL_INFO where 1=1 ".$CONDITION_STR.field_where_str("CP_CPTL_INFO",$_POST,"CPTL_ID")."".$ORDER_STR;
$query .= " limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$CPTL_COUNT=0;
$CPTL_VAL_COUNT_CUR=0;
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small" align="center">
	<tr>
<?
 $THE_FOUR_VAR = "CPTL_VAL_COUNT=$CPTL_VAL_COUNT&CPTL_NO=$CPTL_NO&CPTL_NAME=".urlencode($CPTL_NAME)."&TYPE_ID=$TYPE_ID&DEPT_ID=$DEPT_ID&CPTL_KIND=$CPTL_KIND&PRCS_ID=$PRCS_ID&DCR_PRCS_ID=$DCR_PRCS_ID&FINISH_FLAG=$FINISH_FLAG&CPTL_VAL_MIN=$CPTL_VAL_MIN&CPTL_VAL_MAX=$CPTL_VAL_MAX&CREATE_DATE_MIN=$CREATE_DATE_MIN&CREATE_DATE_MAX=$CREATE_DATE_MAX&DCR_DATE_MIN=$DCR_DATE_MIN&DCR_DATE_MAX=$DCR_DATE_MAX&FROM_YYMM_MIN=$FROM_YYMM_MIN&FROM_YYMM_MAX=$FROM_YYMM_MAX&TO_NAME=$TO_NAME&REMARK=$REMARK&"."start";  
// $THE_FOUR_VAR = "DEPT_ID=$DEPT_ID&TYPE_ID=$TYPE_ID&CPTL_NO=$CPTL_NO&CPTL_NAME=".urlencode($CPTL_NAME)."&"."start";   	
?>			
   	<td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE,$THE_FOUR_VAR)?></td>
  </tr>
</table>
<? 
while($ROW=mysql_fetch_array($cursor))
{
	$CPTL_COUNT++;
	$CPTL_ID=$ROW["CPTL_ID"];
	$CPTL_NO=$ROW["CPTL_NO"];
	$CPTL_NAME=$ROW["CPTL_NAME"];
	$DEPT_ID=$ROW["DEPT_ID"];
	$CPTL_VAL=$ROW["CPTL_VAL"];
	$CPTL_BAL=$ROW["CPTL_BAL"];
	$DPCT_YY=$ROW["DPCT_YY"];
	$MON_DPCT=$ROW["MON_DPCT"];
	$SUM_DPCT=$ROW["SUM_DPCT"];
	$CPTL_KIND=$ROW["CPTL_KIND"];
	$PRCS_ID=$ROW["PRCS_ID"];
	$FINISH_FLAG=$ROW["FINISH_FLAG"];
	$CREATE_DATE=$ROW["CREATE_DATE"];
	$TYPE_ID=$ROW["TYPE_ID"];
	$FROM_YYMM=$ROW["FROM_YYMM"];
	$CPTL_VAL_COUNT_CUR+=$CPTL_VAL;
	if($CPTL_KIND=="01")
		$CPTL_KIND_DESC=_("�ʲ�");
	else if($CPTL_KIND=="02")
		$CPTL_KIND_DESC=_("����");

   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];

		if($TYPE_ID!="" &&$TYPE_ID!=0)
		{
			$query2 = "SELECT * from CP_ASSET_TYPE where TYPE_ID = '$TYPE_ID'";
			$cursor2=exequery(TD::conn(),$query2);
			if($ROW2=mysql_fetch_array($cursor2))
			$TYPE_NAME=$ROW2["TYPE_NAME"];
		}
		else{
			$TYPE_NAME="";
		}
		
		if($DEPT_ID!="" &&$DEPT_ID!=0)
		{
			$query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
			$cursor1= exequery(TD::conn(),$query1);
			if($ROW1=mysql_fetch_array($cursor1))
			$DEPT_NAME=$ROW1["DEPT_NAME"];
		}
		else{
		  $DEPT_NAME="";
		}
   if($CPTL_COUNT==1)
   {
?>
<table class="TableList" width="90%" align="center">
	<tr class="TableHeader">
		<td nowrap align="center"><?=_("�ʲ����")?></td>
		<td nowrap align="center"><?=_("�ʲ�����")?></td>
		<td nowrap align="center"><a href="#" onclick="order_by('DEPT_ID','<?if($ORDER_FIELD=="DEPT_ID"||$ORDER_FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');"><u><?=_("��������")?></u><?if($ORDER_FIELD=="DEPT_ID"||$ORDER_FIELD=="") echo $ORDER_IMG;?></td>
		<td nowrap align="center"><a href="#" onclick="order_by('TYPE_ID','<?if($ORDER_FIELD=="TYPE_ID"||$ORDER_FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');"><u><?=_("�ʲ����")?></u><?if($ORDER_FIELD=="TYPE_ID"||$ORDER_FIELD=="") echo $ORDER_IMG;?></td>
		<td nowrap align="center"><?=_("�ʲ�����")?></td>
		<td nowrap align="center"><?=_("�ʲ�ԭֵ")?></td>
		<td nowrap align="center"><?=_("��������")?></td>
		<td nowrap align="center"><?=_("��������")?></td>
		<td nowrap align="center"><?=_("����")?></td>
   </tr>
<?
   }
   if($CPTL_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
	<tr class="<?=$TableLine?>">
	   <td nowrap align="center"><?=$CPTL_NO?></td>
	   <td><a href="javascript:cptl_detail('<?=$CPTL_ID?>');"><?=$CPTL_NAME?></a></td>
	   <td nowrap align="center"><?=$DEPT_NAME?></td>
		 <td nowrap align="center"><?=$TYPE_NAME?></td>
	   <td nowrap align="center"><?=$CPTL_KIND_DESC?></td>
	   <td nowrap align="center"><?=$CPTL_VAL?></a></td>
	   <td nowrap align="left"><?=$PRCS_LONG_DESC?></td>
	   <td nowrap align="center"><?=$CREATE_DATE == "0000-00-00"?"":$CREATE_DATE?></td>
	   <td nowrap align="center">
	   <a href="javascript:cptl_detail('<?=$CPTL_ID?>');"> <?=_("����")?></a>
	   <a href="javascript:dpct_detail('<?=$CPTL_ID?>');"> <?=_("�۾ɼ�¼")?></a>
	   </td>
	 </tr>
<?
}
if($CPTL_COUNT==0)
{
   Message("",_("�޷��������Ĺ̶��ʲ���Ϣ"));
   exit;
}
else
{
	$MSG = sprintf(_("����%d���ʲ�"), $CPTL_COUNT);
?>
	<tr class="TableControl">
		<td nowrap align="center"><?=_("�ϼƣ�")?></td>
		<td nowrap colspan="1"></td>
		<td nowrap align="center" colspan="3"><?=sprintf(_("���������Ĺ̶��ʲ����У�%s� �ʲ�ԭֵ���ƣ�%s"), $TOTAL_ITEMS, $CPTL_VAL_COUNT)?>&nbsp;&nbsp;&nbsp;<?=sprintf(_("��ҳ�ʲ�ԭֵ���ƣ�%s"), $CPTL_VAL_COUNT_CUR)?></a></td>
		<td nowrap colspan="2"></td>
		<td nowrap align="center"><?=$CPTL_VAL_COUNT?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$MSG?></a></td>
		<td nowrap colspan="3"</td>
	</tr>
</table>
<?
}
?>
</body>
</html>
