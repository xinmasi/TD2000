<?
include_once("inc/auth.inc.php");

//---------------- 检查折旧相关参数是否配置好 ----------------------------
function check_assetcfg()
{
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $BAL_SORT=$ROW["BAL_SORT"];
    $DPCT_SORT=$ROW["DPCT_SORT"];
 }
 else
 {

$HTML_PAGE_TITLE = _("增加固定资产");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
    Message("",_("未配置参数，请先到“参数设置”模块中设置参数"));
?>
</body>
</html>
<?
    exit;
 }
}

//---------------- 检查当前时间是否可以执行折旧操作 ----------------------------
function check_dpct()
{
  $query = "SELECT * from CP_ASSETCFG";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $DPCT_SORT=$ROW["DPCT_SORT"];
  $CUR_DATE=getdate();
  $CUR_MON=$CUR_DATE["mon"];

  if($DPCT_SORT=="S"&&$CUR_MON%3>0)
  {
     Message("",_("只能在3、6、9、12月进行折旧"));
     exit;
  }
  else if($DPCT_SORT=="Y"&&$CUR_MON%12>0)
  {
     Message("",_("只能在12月进行折旧"));
     exit;
  }
}

function check_depre_date($DEPRE_DATE)
{
  $CUR_DATE=date("Y-m-d",time());
  $CUR_DATE_ARRAY=explode("-",$CUR_DATE);
  $DEPRE_DATE_ARRAY=explode("-",$DEPRE_DATE);

  return ($CUR_DATE_ARRAY[0]-$DEPRE_DATE_ARRAY[0])*12+($CUR_DATE_ARRAY[1]-$DEPRE_DATE_ARRAY[1])+1;
}

function insert_dpct($DPCT_SORT,$FROM_YY,$FROM_MM,$MON_NUM,$MON_DPCT,$LEFT_VALUE,$SUM_DPCT,$CPTL_ID)
{
   $PEPRE_DATE=date("Y-m-d",time());

   if($DPCT_SORT=="M")
   	  $NUM=1;
   else if($DPCT_SORT=="S")
   	  $NUM=3;
   else if($DPCT_SORT=="Y")
   	  $NUM=12;

   $DPCT_COUNT=($MON_NUM-$MON_NUM%$NUM)/ $NUM;
   if($MON_NUM%$NUM>0)
   	  $DPCT_COUNT++;
   for($I=0;$I< $DPCT_COUNT;$I++)
   {
   	  if($LEFT_VALUE<=0)
   	   	 break;

   	  $TO_YY=$FROM_YY;
   	  $TO_MM=$FROM_MM+$NUM-1;
   	  if($MON_DPCT*$NUM<=$LEFT_VALUE)
   	  {
   	     $DEPRE_AMT=$MON_DPCT*$NUM;
   	     if($I==0&&$MON_NUM%$NUM>0)
   	     {
   	        $DEPRE_AMT=$MON_DPCT*($MON_NUM%$NUM);
   	        $TO_MM=$FROM_MM+$MON_NUM%$NUM-1;
   	     }
   	     if($NUM==1)
   	        $TO_MM=$FROM_MM+$MON_NUM%$NUM;
   	  }
   	  else
   	  {
   	     $DEPRE_AMT=$LEFT_VALUE;
   	     if($I==0&&$MON_NUM%$NUM>0&&$MON_DPCT*($MON_NUM%$NUM)< $LEFT_VALUE)
   	     {
   	        $DEPRE_AMT=$MON_DPCT*($MON_NUM%$NUM);
   	        $TO_MM=$FROM_MM+$MON_NUM%$NUM-1;
   	     }
   	     if($NUM==1)
   	        $TO_MM=$FROM_MM+$MON_NUM%$NUM;
   	  }
   	  if($TO_MM>12)
   	  {
   	     $TO_YY+=1;
   	     $TO_MM=$TO_MM%12;
   	  }

   	  $LEFT_VALUE=$LEFT_VALUE-$DEPRE_AMT;
   	  if(strlen($FROM_MM)==1)
   	     $FROM_MM="0".$FROM_MM;
   	  if(strlen($TO_MM)==1)
   	     $TO_MM="0".$TO_MM;
   	  $FROM_YYMM=$FROM_YY."-".$FROM_MM;
   	  $TO_YYMM=$TO_YY."-".$TO_MM;
   	  $query="insert into CP_DPCT_SUB (CPTL_ID,PEPRE_DATE,FROM_YYMM,TO_YYMM,DEPRE_AMT) values('$CPTL_ID','$PEPRE_DATE','$FROM_YYMM','$TO_YYMM','$DEPRE_AMT')";
   	  exequery(TD::conn(),$query);
   	  $SUM_DPCT+=$DEPRE_AMT;
   	  $FROM_YY=$TO_YY;
   	  $FROM_MM=$TO_MM+1;
   	  if($FROM_MM>12)
   	  {
   	     $FROM_YY+=1;
   	     $FROM_MM=$FROM_MM%12;
   	  }
    }
    $CPTL_ID=intval($CPTL_ID);
    $query="update CP_CPTL_INFO set SUM_DPCT='$SUM_DPCT' where CPTL_ID='$CPTL_ID'";
    exequery(TD::conn(),$query);
}
?>