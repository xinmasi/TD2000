<?
include_once("inc/auth.inc.php");
include_once("../manage/check_cfg.php");

//---------------- 检查折旧相关参数是否配置好 ----------------------------
check_assetcfg();

$HTML_PAGE_TITLE = _("固定资产折旧");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("固定资产折旧")?></span>
    </td>
  </tr>
</table>
<br>
<?
//---------------- 检查当前时间是否可以执行折旧操作 ----------------------------
check_dpct();

//---------------- 查找折旧方式 ------------------------------------------------
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $BAL_SORT=$ROW["BAL_SORT"];
    $DPCT_SORT=$ROW["DPCT_SORT"];
 }
 
$DPCT_COUNT=0;
//---------------- 检查折旧子集里的折旧信息，如果有需要折旧的则折旧之 ----------
$query = "SELECT * FROM (SELECT * FROM `CP_DPCT_SUB` ORDER BY PEPRE_DATE DESC ) `TMP_TABLE` GROUP BY CPTL_ID ORDER BY `PEPRE_DATE` DESC";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $CPTL_ID=$ROW["CPTL_ID"];
   $PEPRE_DATE=$ROW["PEPRE_DATE"];
   
   $MON_NUM=check_depre_date($PEPRE_DATE);
   if($MON_NUM<=1)
      continue;
   
   $query1 = "SELECT * from CP_CPTL_INFO where CPTL_ID='$CPTL_ID' and CPTL_KIND='01' and DCR_PRCS_ID=0 and FINISH_FLAG='0'";
   $cursor1 = exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
      $CPTL_VAL=$ROW1["CPTL_VAL"];
      $CPTL_BAL=$ROW1["CPTL_BAL"];
      $MON_DPCT=$ROW1["MON_DPCT"];
      $SUM_DPCT=$ROW1["SUM_DPCT"];
      
      //-------- 计算剩余价值 -------------
      if($BAL_SORT=="01")
         $LEFT_VALUE=$CPTL_VAL-$CPTL_BAL-$SUM_DPCT;
      else
         $LEFT_VALUE=$CPTL_VAL-$CPTL_VAL*$CPTL_BAL/100-$SUM_DPCT;
         
      if($LEFT_VALUE<=0)
         continue;
      $FROM_ARRAY=explode("-",$PEPRE_DATE);
   	  $FROM_YY=$FROM_ARRAY[0];
   	  $FROM_MM=$FROM_ARRAY[1]+1;
   	  if($FROM_MM>12)
   	  {
   	     $FROM_YY+=1;
   	     $FROM_MM=$FROM_MM%12;
   	  }
      insert_dpct($DPCT_SORT,$FROM_YY,$FROM_MM,$MON_NUM,$MON_DPCT,$LEFT_VALUE,$SUM_DPCT,$CPTL_ID);
      $DPCT_COUNT++;
   }
}

//---------------- 检查资产表里从未折旧过的资产,符合条件则折旧之 ----------
$query = "SELECT * from CP_CPTL_INFO where CPTL_KIND='01' and DCR_PRCS_ID=0 and FINISH_FLAG='0' and FROM_YYMM!='0000-00-00'";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $CPTL_ID=$ROW["CPTL_ID"];
   
   $query1 = "SELECT * from CP_DPCT_SUB where CPTL_ID='$CPTL_ID'";
   $cursor1 = exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      continue;
      
   $CPTL_VAL=$ROW["CPTL_VAL"];
   $CPTL_BAL=$ROW["CPTL_BAL"];
   $MON_DPCT=$ROW["MON_DPCT"];
   $SUM_DPCT=$ROW["SUM_DPCT"];
   $FROM_YYMM=$ROW["FROM_YYMM"];
   
   //-------- 检查启用日期 -------------
   $MON_NUM=check_depre_date($FROM_YYMM);
   if($MON_NUM<=0)
      continue;
   //-------- 计算剩余价值 -------------
   if($BAL_SORT=="01")
      $LEFT_VALUE=$CPTL_VAL-$CPTL_BAL-$SUM_DPCT;
   else
      $LEFT_VALUE=$CPTL_VAL-$CPTL_VAL*$CPTL_BAL/100-$SUM_DPCT;
         
   if($LEFT_VALUE<=0)
   {
   	  $query="update CP_CPTL_INFO set FINISH_FLAG='1' where CPTL_ID='$CPTL_ID'";
      exequery(TD::conn(),$query);
      continue;
   }
   $FROM_ARRAY=explode("-",$FROM_YYMM);
   $FROM_YY=$FROM_ARRAY[0];
   $FROM_MM=$FROM_ARRAY[1];
   insert_dpct($DPCT_SORT,$FROM_YY,$FROM_MM,$MON_NUM,$MON_DPCT,$LEFT_VALUE,$SUM_DPCT,$CPTL_ID);
   $DPCT_COUNT++;
}

//---------------- 折旧完成,显示折旧信息 ----------------------------------
if($DPCT_COUNT>0)
{
	 $MSG = sprintf(_("折旧完成，总共%d件资产折旧!"), $DPCT_COUNT);
	 Message("",$MSG);
   Button_Back();
}
else
{
   Message("",_("无可折旧的资产"));
   Button_Back();
}
?>


</body>
</html>