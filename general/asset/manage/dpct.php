<?
include_once("inc/auth.inc.php");
include_once("../manage/check_cfg.php");

//---------------- ����۾���ز����Ƿ����ú� ----------------------------
check_assetcfg();

$HTML_PAGE_TITLE = _("�̶��ʲ��۾�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("�̶��ʲ��۾�")?></span>
    </td>
  </tr>
</table>
<br>
<?
//---------------- ��鵱ǰʱ���Ƿ����ִ���۾ɲ��� ----------------------------
check_dpct();

//---------------- �����۾ɷ�ʽ ------------------------------------------------
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $BAL_SORT=$ROW["BAL_SORT"];
    $DPCT_SORT=$ROW["DPCT_SORT"];
 }
 
$DPCT_COUNT=0;
//---------------- ����۾��Ӽ�����۾���Ϣ���������Ҫ�۾ɵ����۾�֮ ----------
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
      
      //-------- ����ʣ���ֵ -------------
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

//---------------- ����ʲ������δ�۾ɹ����ʲ�,�����������۾�֮ ----------
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
   
   //-------- ����������� -------------
   $MON_NUM=check_depre_date($FROM_YYMM);
   if($MON_NUM<=0)
      continue;
   //-------- ����ʣ���ֵ -------------
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

//---------------- �۾����,��ʾ�۾���Ϣ ----------------------------------
if($DPCT_COUNT>0)
{
	 $MSG = sprintf(_("�۾���ɣ��ܹ�%d���ʲ��۾�!"), $DPCT_COUNT);
	 Message("",$MSG);
   Button_Back();
}
else
{
   Message("",_("�޿��۾ɵ��ʲ�"));
   Button_Back();
}
?>


</body>
</html>