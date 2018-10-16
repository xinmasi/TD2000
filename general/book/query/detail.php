<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("图书详细资料");
include_once("inc/header.inc.php");
?>




<?
$BOOK_ID=intval($BOOK_ID);
 $query = "SELECT * from BOOK_INFO where BOOK_ID='$BOOK_ID'";

 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $BOOK_NAME=$ROW["BOOK_NAME"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $AUTHOR=$ROW["AUTHOR"];
    $ISBN=$ROW["ISBN"];
    $PUB_HOUSE=$ROW["PUB_HOUSE"];
    $PUB_DATE=$ROW["PUB_DATE"];
    $AREA=$ROW["AREA"];
    $AMT=$ROW["AMT"];
    $PRICE=$ROW["PRICE"];
    $BRIEF=$ROW["BRIEF"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];


    $OPEN=$ROW["OPEN"];
    $LEND=$ROW["LEND"];
    $BORR_PERSON=$ROW["BORR_PERSON"];
    $MEMO=$ROW["MEMO"];
    $DEPT=$ROW["DEPT"];
    $BOOK_NO=$ROW["BOOK_NO"];

    $BOOK_NAME=str_replace("<","&lt",$BOOK_NAME);
    $BOOK_NAME=str_replace(">","&gt",$BOOK_NAME);
    $BOOK_NAME=stripslashes($BOOK_NAME);

    $AUTHOR=str_replace("<","&lt",$AUTHOR);
    $AUTHOR=str_replace(">","&gt",$AUTHOR);
    $AUTHOR=stripslashes($AUTHOR);

    $ISBN=str_replace("<","&lt",$ISBN);
    $ISBN=str_replace(">","&gt",$ISBN);
    $ISBN=stripslashes($ISBN);

    $PUB_HOUSE=str_replace("<","&lt",$PUB_HOUSE);
    $PUB_HOUSE=str_replace(">","&gt",$PUB_HOUSE);
    $PUB_HOUSE=stripslashes($PUB_HOUSE);

    $PUB_DATE=str_replace("<","&lt",$PUB_DATE);
    $PUB_DATE=str_replace(">","&gt",$PUB_DATE);
    $PUB_DATE=stripslashes($PUB_DATE);

    $AREA=str_replace("<","&lt",$AREA);
    $AREA=str_replace(">","&gt",$AREA);
    $AREA=stripslashes($AREA);

    $AMT=str_replace("<","&lt",$AMT);
    $AMT=str_replace(">","&gt",$AMT);
    $AMT=stripslashes($AMT);

    $PRICE=str_replace("<","&lt",$PRICE);
    $PRICE=str_replace(">","&gt",$PRICE);
    $PRICE=stripslashes($PRICE);

    $BRIEF=str_replace("<","&lt",$BRIEF);
    $BRIEF=str_replace(">","&gt",$BRIEF);
    $BRIEF=stripslashes($BRIEF);

    $BRIEF=str_replace("  ","&nbsp;&nbsp;",$BRIEF);
    $BRIEF=str_replace("\n","<br>",$BRIEF);

    $BORR_PERSON=str_replace("<","&lt",$BORR_PERSON);
    $BORR_PERSON=str_replace(">","&gt",$BORR_PERSON);
    $BORR_PERSON=stripslashes($BORR_PERSON);

    $MEMO=str_replace("<","&lt",$MEMO);
    $MEMO=str_replace(">","&gt",$MEMO);
    $MEMO=stripslashes($MEMO);

 }

 $query = "SELECT TYPE_NAME from BOOK_INFO A,BOOK_TYPE B where A.TYPE_ID=B.TYPE_ID and A.BOOK_ID='$BOOK_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_NAME=$ROW["TYPE_NAME"];
 }

?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("图书详细信息")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock"  width="450" align="center">
  <form name="form1">
   <tr>
    <td nowrap class="TableData" width="70"><?=_("部门：")?></td>
    <td nowrap class="TableData"  width="280">
<?
   $query = "SELECT B.DEPT_NAME from BOOK_INFO A,DEPARTMENT B where A.DEPT=B.DEPT_ID and A.DEPT='$DEPT'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DEPT_NAME=$ROW[0];
?>
      <?=$DEPT_NAME?>&nbsp;
    </td>
    <td class="TableData" rowspan="6" width="100">
<?     
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("暂无封面")."</center>";
   else{
	  $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
      <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='100' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>
<?
   }
?>
   </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("书名：")?></td>
    <td nowrap class="TableData"><?=$BOOK_NAME?>&nbsp;</td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("编号：")?></td>
    <td nowrap class="TableData">
    <?=$BOOK_NO?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("图书类别：")?></td>
    <td nowrap class="TableData">
    <?=$TYPE_NAME?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("作者：")?></td>
    <td nowrap class="TableData">
    <?=$AUTHOR?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ISBN号：")?></td>
    <td nowrap class="TableData">
    <?=$ISBN?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("出版社：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$PUB_HOUSE?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("出版日期：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$PUB_DATE?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("存放地点：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$AREA?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("数量：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$AMT?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("借书待批：")?></td>
    <td class="TableData" colspan="2">
<?
$query = "SELECT * from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and (BOOK_STATUS='0' and  STATUS='0')";
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;

   $BUSER_ID=$ROW["BUSER_ID"];
   $BORROW_DATE=$ROW["BORROW_DATE"];
   $RETURN_DATE=$ROW["RETURN_DATE"];

   $query1 = "SELECT * from USER where USER_ID='$BUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME=$ROW1["USER_NAME"];

   if($COUNT==1)
   {
?>
<table class="TableList"  width="100%"  align="center" >
   <tr align="center">
     <td nowrap class="TableData"><?=_("借书人")?></td>
     <td nowrap class="TableData"><?=_("借书日期")?></td>
     <td nowrap class="TableData"><?=_("归还日期")?></td>
     <td nowrap class="TableData"><?=_("数量")?></td>
   </tr>
<?
   }
?>
   <tr align="center">
     <td nowrap class="TableData"><?=$USER_NAME?></td>
     <td nowrap class="TableData"><?=$BORROW_DATE?></td>
     <td nowrap class="TableData"><?=$RETURN_DATE?></td>
     <td nowrap class="TableData">1</td>
   </tr>
<?
}//while

if($COUNT>0)
   echo "</table>";
else
   echo _("无记录")
?>
   <tr>
    <td nowrap class="TableData"><?=_("未还记录：")?></td>
    <td class="TableData" colspan="2">
<?
$query = "SELECT * from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and ((BOOK_STATUS='0' and  STATUS='1') or (BOOK_STATUS='1'and (STATUS='0' or STATUS='2')))";
$cursor= exequery(TD::conn(),$query);
$LEND_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEND_COUNT++;

   $BUSER_ID=$ROW["BUSER_ID"];
   $BORROW_DATE=$ROW["BORROW_DATE"];
   $RETURN_DATE=$ROW["RETURN_DATE"];

   $query1 = "SELECT * from USER where USER_ID='$BUSER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME=$ROW1["USER_NAME"];

   if($LEND_COUNT==1)
   {
?>
<table class="TableList" width="100%" align="center" >
   <tr align="center">
     <td nowrap class="TableData"><?=_("借书人")?></td>
     <td nowrap class="TableData"><?=_("借书日期")?></td>
     <td nowrap class="TableData"><?=_("归还日期")?></td>
     <td nowrap class="TableData"><?=_("数量")?></td>
   </tr>
<?
   }
?>
   <tr align="center">
     <td nowrap class="TableData"><?=$USER_NAME?></td>
     <td nowrap class="TableData"><?=$BORROW_DATE?></td>
     <td nowrap class="TableData"><?=$RETURN_DATE?></td>
     <td nowrap class="TableData">1</td>
   </tr>
<?
}//while

if($LEND_COUNT>0)
   echo "</table>";
else
   echo _("无记录")
?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("价格：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$PRICE?>&nbsp;<?=_("元")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("内容简介：")?></td>
    <td class="TableData" colspan="2">
    <?=$BRIEF?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("借阅范围：")?></td>
    <td nowrap class="TableData" colspan="2" width="300">
<?
/*if($OPEN=="ALL_DEPT" || $OPEN=="1")
   echo $TO_NAME=_("全体部门");
else
   $TO_NAME=GetDeptNameById($OPEN); 
   $arr = explode(",",$TO_NAME);
   $num = count($arr);
   for($i=0;$i<$num-1;$i++){
		if($i!=$num-2){
			echo $arr[$i].","; 
		}else{
			echo $arr[$i];   
		} 
   }
   */
   $OPEN1=explode(";", $OPEN);
   //print_r($OPEN1);
	$OPEN_DEPT=td_trim($OPEN1[0]);
	if($OPEN_DEPT=="ALL_DEPT" || $OPEN_DEPT=="1")
	  $TO_NAME=_("全体部门");
	else{
		$TO_NAME=GetDeptNameById($OPEN_DEPT);
		if($TO_NAME==","){
			$TO_NAME="";
		}
	}
	$COPY_TO_NAME=GetUserNameById($OPEN1[1]);
	if($COPY_TO_NAME==","){
		$COPY_TO_NAME="";
	}
	$PRIV_NAME=GetPrivNameById($OPEN1[2]);
	if($PRIV_NAME==","){
		$PRIV_NAME="";
	}

	$TO_NAME=td_trim($TO_NAME);
	$DEPT_STR=$TO_NAME.",".td_trim($COPY_TO_NAME).",".$PRIV_NAME;
	echo td_trim($DEPT_STR);
?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("借阅情况：")?></td>
    <td nowrap class="TableData" colspan="2">
<?=$LEND_DESC?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("新建人：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$BORR_PERSON?>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("备注：")?></td>
    <td nowrap class="TableData" colspan="2">
    <?=$MEMO?>&nbsp;
    </td>
   </tr>
   <tr align="center" class="TableControl">
     <td colspan="3">
       <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
     </td>
   </tr>
  </form>
</table>

</body>
</html>