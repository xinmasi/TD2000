<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("exportExcel.func.php");

ob_end_clean();

$POSTFIX = _("，");

function ParseItemName($ITEM_NAME, $ITEM_ID, $COUNT=1) {

   $POS = strpos($ITEM_NAME, "{");
   if ($POS === false)
      return $ITEM_NAME;

   if (substr($ITEM_NAME, $POS, 6) == "{text}")
      return substr($ITEM_NAME, 0, $POS) . ParseItemName(substr($ITEM_NAME, $POS + 6), $ITEM_ID, $COUNT);
   if (substr($ITEM_NAME, $POS, 8) == "{number}")
      return substr($ITEM_NAME, 0, $POS) . ParseItemName(substr($ITEM_NAME, $POS + 8), $ITEM_ID, $COUNT);
   if (substr($ITEM_NAME, $POS, 10) == "{textarea}")
      return substr($ITEM_NAME, 0, $POS) . ParseItemName(substr($ITEM_NAME, $POS + 10), $ITEM_ID, $COUNT);

   return substr($ITEM_NAME, 0, $POS + 1) . ParseItemName(substr($ITEM_NAME, $POS + 1), $ITEM_ID);
}

require_once 'inc/PHPExcel/PHPExcel.php'; //包含类
require_once 'inc/PHPExcel/PHPExcel/Writer/Excel5.php'; //包含excel5读功能的类
require_once 'inc/PHPExcel/PHPExcel/IOFactory.php';

//包含excel5读功能的类

function change_encode($in) {    //转换编码
   return iconv(MYOA_DB_CHARSET, "UTF-8", $in);
}

$subject = _("投票结果");
$Creator = "TD";



/* * **********************内容*************************** */


$objExcel = createPHPExcelObj();        //创建对象
$objWriter = new PHPExcel_Writer_Excel5($objExcel);
$objExcel->setActiveSheetIndex(0);        //设置所在页面
$objActSheet = setProperties($objExcel, $Creator, $subject); //设置属性

$query = "SELECT SUBJECT,FROM_ID,TO_ID,PRIV_ID,USER_ID,READERS,BEGIN_DATE from vote_title where VOTE_ID ='$VOTE_ID '";

$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor)) {
   $SUBJECT = $ROW["SUBJECT"];
   $FROM_ID = $ROW["FROM_ID"];
   $TO_ID = $ROW["TO_ID"];
   $PRIV_ID = $ROW["PRIV_ID"];
   $USER_ID_TO = $ROW["USER_ID"];
   $READERS = $ROW["READERS"];
   $BEGIN_DATE = $ROW["BEGIN_DATE"];
   $BEGIN_DATE = strtok($BEGIN_DATE, " ");

   $query1 = "SELECT * from USER where USER_ID='$FROM_ID'";
   $cursor1 = exequery(TD::conn(), $query1);
   if ($ROW = mysql_fetch_array($cursor1)) {
      $FROM_NAME = $ROW["USER_NAME"];
      $DEPT_ID = $ROW["DEPT_ID"];
      $DEPT_NAME = dept_long_name($DEPT_ID);
   }

   $READ_COUNT = 0;
   $READER_ARRAY = explode(',', td_trim($READERS));
   foreach ($READER_ARRAY as $READER) {
      if ($READER != "")
         $READ_COUNT++;
   }

   if ($TO_ID != "ALL_DEPT") {
      $DEPT_OTHER_SQL = "";
      $TO_ID_ARRAY = explode(",", $TO_ID);
      foreach ($TO_ID_ARRAY as $ID) {
         if ($ID != "")
            $DEPT_OTHER_SQL .= " or find_in_set('$ID',DEPT_ID_OTHER)";
      }
      $query = "select count(*) from USER where find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(DEPT_ID,'$TO_ID')" . $DEPT_OTHER_SQL . " or find_in_set(USER_ID,'$USER_ID_TO') and NOT_LOGIN=0";
   }
   else {
      $query = "select count(*) from USER where NOT_LOGIN=0";
   }
   $cursor = exequery(TD::conn(), $query);
   if ($ROW = mysql_fetch_array($cursor))
      $ALL_COUNT = $ROW[0];

   $UN_READ_COUNT = $ALL_COUNT - $READ_COUNT;
}

if ($TO_ID != "ALL_DEPT") {
   $TOK = strtok($PRIV_ID, ",");
   while ($TOK != "") {
      $query1 = "SELECT DEPT_ID from USER where USER_PRIV='$TOK' and NOT_LOGIN=0";
      $cursor1 = exequery(TD::conn(), $query1);
      while ($ROW = mysql_fetch_array($cursor1)) {
         $DEPT_ID = $ROW["DEPT_ID"];
         if (!find_id($TO_ID, $DEPT_ID))
            $TO_ID.=$DEPT_ID . ",";
      }

      $TOK = strtok(",");
   }
}

if ($TO_ID != "ALL_DEPT") {
   $TOK = strtok($USER_ID_TO, ",");
   while ($TOK != "") {
      $query1 = "SELECT DEPT_ID from USER where USER_ID='$TOK'";
      $cursor1 = exequery(TD::conn(), $query1);
      if ($ROW = mysql_fetch_array($cursor1)) {
         $DEPT_ID = $ROW["DEPT_ID"];
         if (!find_id($TO_ID, $DEPT_ID))
            $TO_ID.=$DEPT_ID . ",";
      }
      $TOK = strtok(",");
   }
}

//------------------统计投票人数  end-------------------------------
 $OLD_VOTE_ID=$VOTE_ID;
 $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
 if($_SESSION["LOGIN_USER_PRIV"]!="1")
    $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $SUBJECT=$ROW["SUBJECT"];
    $ANONYMITY=$ROW["ANONYMITY"];
    $READERS=$ROW["READERS"];
    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    $CONTENT=nl2br($CONTENT);
	 $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
	 $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
 }
 else
    exit;

 if($ATTACHMENT_NAME!="")
 {
     $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
     $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
     for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
     {
        if($ATTACHMENT_ID_ARRAY[$I]=="")
           continue;

        if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
	       $IMAGE_COUNT++;
     }
 }


/* * *********************表格样式************************ */
$styleTable = $objActSheet->getStyle("A:C");
$fontTable = $styleTable->getFont();
$fontTable->setName(change_encode(_("微软雅黑")));
$objActSheet->getColumnDimension("A")->setWidth(50);
$objActSheet->getColumnDimension("B")->setWidth(10);
$objActSheet->getColumnDimension("C")->setWidth(50);
//标题
$objActSheet->mergeCells("A1:C1");                                      //合并标题单元格
$styleA1 = $objActSheet->getStyle('A1');
$fontA1 = $styleA1->getFont();
$fontA1->setName(change_encode(_("微软雅黑")));
$fontA1->setSize('12');
$objAlignA1 = $styleA1->getAlignment();
$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objActSheet->setCellValue("A1", change_encode($SUBJECT));

//内容
$objActSheet->mergeCells("A2:C2");
$objActSheet->setCellValue("A2", change_encode($CONTENT));

$objActSheet->setCellValue("A3", change_encode(_("选项")));
$objActSheet->setCellValue("B3", change_encode(_("票数")));
$objActSheet->setCellValue("C3", change_encode(_("投票人")));


$count = 4;
$query = "SELECT count(*) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor))
   $ITEM_COUNT = $ROW[0];

if ($ITEM_COUNT > 0) {
   $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
   $cursor2 = exequery(TD::conn(), $query);

   $ITEM_COUNT = 0;
   if ($ROW2 = mysql_fetch_array($cursor2)) {
      $ITEM_COUNT++;
      $VOTE_ID = $ROW2["VOTE_ID"];
      $TYPE = $ROW2["TYPE"];
      $SUBJECT = $ITEM_COUNT . _("、") . $ROW2["SUBJECT"];

      $objActSheet->mergeCells("A$count:C$count");
      $objActSheet->setCellValue("A$count", change_encode($SUBJECT));
      $count = $count + 1;


      if ($TYPE == 0 || $TYPE == 1) {
         $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
         $cursor = exequery(TD::conn(), $query);
         if ($ROW = mysql_fetch_array($cursor)) {
            $SUM_COUNT = $ROW[0];
            $MAX_COUNT = $ROW[1];
         }
         if ($SUM_COUNT == 0 || $SUM_COUNT == "")
            $SUM_COUNT = 1;
         if ($MAX_COUNT == 0 || $MAX_COUNT == "")
            $MAX_COUNT = 1;

         $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
         $cursor = exequery(TD::conn(), $query);
         $NO = 0;
         while ($ROW = mysql_fetch_array($cursor)) 
         {
            $ITEM_ID = $ROW["ITEM_ID"];
            if ($NO >= 26)
               $ITEM_NAME = chr($NO % 26 + 65) . floor($NO / 26) . _("、") . $ROW["ITEM_NAME"];
            else
               $ITEM_NAME=chr($NO % 26 + 65) . _("、") . $ROW["ITEM_NAME"];
            $VOTE_COUNT = $ROW["VOTE_COUNT"];
            $VOTE_USER = $ROW["VOTE_USER"];
            $NO++;

            $ITEM_NAME = ParseItemName($ITEM_NAME, $ITEM_ID);

            $VOTE_USER_NAME = "";
            if ($ANONYMITY == "0") {
               $query = "SELECT * from USER where find_in_set(USER_ID,'$VOTE_USER')";
               $cursor1 = exequery(TD::conn(), $query);
               while ($ROW1 = mysql_fetch_array($cursor1))
                  $VOTE_USER_NAME.=$ROW1["USER_NAME"] . $POSTFIX;
               $VOTE_USER_NAME = substr($VOTE_USER_NAME, 0, -strlen($POSTFIX));
            }
            $objActSheet->setCellValue("A$count", change_encode($ITEM_NAME));
            $objActSheet->setCellValue("B$count", change_encode($VOTE_COUNT));
            $objActSheet->setCellValue("C$count", change_encode($VOTE_USER_NAME));

            $count++;
         }
         
      }
      else //文本类型
      {
       $TEXT_STR="";
       $TEXT_COUNT=0;
       $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
       $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
       {
         $USER_ID=$ROW["USER_ID"];
         $FIELD_DATA=$ROW["FIELD_DATA"];
         $USER_NAME="";
         if($ANONYMITY==0)
         {
            $query = "SELECT * from USER where USER_ID='$USER_ID'";
            $cursor1= exequery(TD::conn(),$query);
            if($ROW1=mysql_fetch_array($cursor1))
               $USER_NAME=$ROW1["USER_NAME"];         
         }
         $TEXT_COUNT++;
         $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
         $FIELD_DATA=nl2br($FIELD_DATA);
         
         if($USER_NAME!="")
           $TEXT_STR.= _("【").$USER_NAME._("】").$FIELD_DATA."";
        }
            $objActSheet->setCellValue("A$count",change_encode('') );
            $objActSheet->setCellValue("B$count", change_encode("'" .$TEXT_COUNT));
            $objActSheet->setCellValue("C$count", change_encode($TEXT_STR));
          $count++;
     }
   }
}

$query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID' order by VOTE_NO,SEND_TIME";
$cursor2 = exequery(TD::conn(), $query);
while ($ROW2 = mysql_fetch_array($cursor2)) {
   $ITEM_COUNT++;
   $VOTE_ID = $ROW2["VOTE_ID"];
   $TYPE = $ROW2["TYPE"];
   $SUBJECT = $ITEM_COUNT . _("、") . $ROW2["SUBJECT"];


   $objActSheet->mergeCells("A$count:C$count");
   $objActSheet->setCellValue("A$count", change_encode($SUBJECT));
   $count = $count + 1;


   if ($TYPE == 0 || $TYPE == 1) {
      $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
      $cursor = exequery(TD::conn(), $query);
      if ($ROW = mysql_fetch_array($cursor)) {
         $SUM_COUNT = $ROW[0];
         $MAX_COUNT = $ROW[1];
      }
      if ($SUM_COUNT == 0 || $SUM_COUNT == "")
         $SUM_COUNT = 1;
      if ($MAX_COUNT == 0 || $MAX_COUNT == "")
         $MAX_COUNT = 1;

      $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
      $cursor = exequery(TD::conn(), $query);
      $NO = 0;
      while ($ROW = mysql_fetch_array($cursor)) {
         $ITEM_ID = $ROW["ITEM_ID"];
         if ($NO >= 26)
            $ITEM_NAME = chr($NO % 26 + 65) . floor($NO / 26) . _("、") . $ROW["ITEM_NAME"];
         else
            $ITEM_NAME=chr($NO % 26 + 65) . _("、") . $ROW["ITEM_NAME"];
         $VOTE_COUNT = $ROW["VOTE_COUNT"];
         $VOTE_USER = $ROW["VOTE_USER"];
         $NO++;

         $ITEM_NAME = ParseItemName($ITEM_NAME, $ITEM_ID);

         $VOTE_USER_NAME = "";
         if ($ANONYMITY == "0") {
            $query = "SELECT * from USER where find_in_set(USER_ID,'$VOTE_USER')";
            $cursor1 = exequery(TD::conn(), $query);
            while ($ROW1 = mysql_fetch_array($cursor1))
               $VOTE_USER_NAME.=$ROW1["USER_NAME"] . $POSTFIX;
            $VOTE_USER_NAME = substr($VOTE_USER_NAME, 0, -strlen($POSTFIX));
         }


         $objActSheet->setCellValue("A$count", change_encode($ITEM_NAME));
         $objActSheet->setCellValue("B$count", change_encode($VOTE_COUNT));
         $objActSheet->setCellValue("C$count", change_encode($VOTE_USER_NAME));

         $count++;
      }
   }
   else //文本类型
   {
       $TEXT_STR="";
       $TEXT_COUNT=0;
       $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
       $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
       {
         $USER_ID=$ROW["USER_ID"];
         $FIELD_DATA=$ROW["FIELD_DATA"];
         $USER_NAME="";
         if($ANONYMITY==0)
         {
            $query = "SELECT * from USER where USER_ID='$USER_ID'";
            $cursor1= exequery(TD::conn(),$query);
            if($ROW1=mysql_fetch_array($cursor1))
               $USER_NAME=$ROW1["USER_NAME"];         
         }
         $TEXT_COUNT++;
         $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
         $FIELD_DATA=nl2br($FIELD_DATA);
         
         if($USER_NAME!="")
          $TEXT_STR.= _("【").$USER_NAME._("】").$FIELD_DATA."";
       }
            $objActSheet->setCellValue("A$count", change_encode(''));
            $objActSheet->setCellValue("B$count", change_encode("'" .$TEXT_COUNT));
            $objActSheet->setCellValue("C$count", change_encode($TEXT_STR));
          $count++;
    }
}


$New_name = date("Y-m-d H:i:s", time());
$outputFileName = "report_" . $New_name . ".xls";


header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; ".get_attachment_filename($outputFileName));
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>