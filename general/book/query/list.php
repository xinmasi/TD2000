<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$PAGE_SIZE=15;

$HTML_PAGE_TITLE = _("图书查询结果");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function set_page()
{
	var PAGE_NUM = document.getElementById("PAGE_NUM").value;
	var PAGE_START=(PAGE_NUM-1)*<?=$PAGE_SIZE?>+1;
	location="list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&LEND=<?=$LEND?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START="+PAGE_START;
}

function detail(BOOK_ID,LEND_DESC)
{
	URL="detail.php?BOOK_ID="+BOOK_ID+"&LEND_DESC="+LEND_DESC;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_notify","height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="bodycolor" >
<?
//-----------先组织SQL语句-----------
$WHERE_STR=" where 1=1";

if($BOOK_NAME!="")
   $WHERE_STR.=" and BOOK_NAME like '%".$BOOK_NAME."%'";

if($BOOK_NO!="")
   $WHERE_STR.=" and b.BOOK_NO like '%".$BOOK_NO."%'";

if($AUTHOR!="")
   $WHERE_STR.=" and AUTHOR like '%".$AUTHOR."%'";

if($ISBN!="")
   $WHERE_STR.=" and ISBN like '%".$ISBN."%'";

if($PUB_HOUSE!="")
   $WHERE_STR.=" and PUB_HOUSE like '%".$PUB_HOUSE."%'";

if($AREA!="")
   $WHERE_STR.=" and AREA like '%".$AREA."%'";

if($TYPE_ID!="all")
   $WHERE_STR.=" and TYPE_ID='$TYPE_ID'";
//=========================================
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
{
    if(get_manage_dept_ids($_SESSION['LOGIN_UID']))
    {
        $dept_ids = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    }
    else
    {
        $dept_ids = $_SESSION["LOGIN_DEPT_ID"];
    }
    $WHERE_STR.=" and DEPT in (".$dept_ids.")";
}
if($LEND!=""){
	if($LEND == "1"){
		$query="select DISTINCT b.* from BOOK_MANAGE as a , BOOK_INFO as b ".$WHERE_STR." and  b.BOOK_NO=a.BOOK_NO and a.BOOK_STATUS='0' ";
		$ty="true";
	}else if($LEND == "0"){
		$query="select * from BOOK_INFO as b ".$WHERE_STR." order by ".$ORDER_FIELD;
		$ty = "fales";
	}
}else{
    $query="SELECT DEPT,OPEN from BOOK_INFO as b ".$WHERE_STR." order by ".$ORDER_FIELD;
}
$cursor= exequery(TD::conn(),$query);
$BOOK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $DEPT1=$ROW["DEPT"];
  $OPEN1=$ROW["OPEN"];
  $OPEN_ARR=explode(";", $OPEN1);
  if ($_SESSION["LOGIN_USER_PRIV"]!=1)
  {
  	if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT")
  	    continue;
  }
  
  if($OPEN1=="0" && $DEPT1!=$_SESSION["LOGIN_DEPT_ID"])
     continue;

  $BOOK_COUNT++;
}

$PAGE_TOTAL=$BOOK_COUNT/$PAGE_SIZE;
$PAGE_TOTAL=ceil($PAGE_TOTAL);

//--- 计算,末页 ---
if($BOOK_COUNT<=$PAGE_SIZE)
   $LAST_PAGE_START=1;
else if($BOOK_COUNT%$PAGE_SIZE==0)
   $LAST_PAGE_START=$BOOK_COUNT-$PAGE_SIZE+1;
else
   $LAST_PAGE_START=$BOOK_COUNT-$BOOK_COUNT%$PAGE_SIZE+1;

//--- 智能分页 ---
//-- 页首 --
if($PAGE_START=="")
   $PAGE_START=1;

if($PAGE_START>$BOOK_COUNT)
   $PAGE_START=$LAST_PAGE_START;

if($PAGE_START<1)
   $PAGE_START=1;

//-- 页尾 --
$PAGE_END=$PAGE_START+$PAGE_SIZE-1;

if($PAGE_END>$BOOK_COUNT)
   $PAGE_END=$BOOK_COUNT;

//--- 计算当前页 ---
$PAGE_NUM=($PAGE_START-1)/$PAGE_SIZE+1;

$query1=str_replace("DEPT,OPEN","*",$query);


$cursor1 = exequery(TD::conn(), $query1);

if($BOOK_COUNT==0)
{
    Message(_("提示"),_("没有符合条件的图书"));
?>
<br>
<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='search.php';">
</div>
<?
    exit;
}

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" >
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("图书查询结果")?> </span><br>
    </td>
    <td valign="bottom" align="right">
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </td>
    </tr>
</table>

<table class="TableList"  width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("书名")?></td>
      <td nowrap align="center"><?=_("编号")?></td>
      <td nowrap align="center"><?=_("类别")?></td>
      <td nowrap align="center"><?=_("作者")?></td>
      <td nowrap align="center"><?=_("出版社")?></td>
      <td nowrap align="center"><?=_("存放地点")?></td>
      <td nowrap align="center"><?=_("借阅情况")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
$BOOK_COUNT = 0;
while($ROW=mysql_fetch_array($cursor1))
{
  $DEPT1=$ROW["DEPT"];
 // $OPEN1=$ROW["OPEN"];



  $BOOK_ID=$ROW["BOOK_ID"];
  $BOOK_NAME1=$ROW["BOOK_NAME"];
  $TYPE_ID1=$ROW["TYPE_ID"];
  $AUTHOR1=$ROW["AUTHOR"];
  $ISBN1=$ROW["ISBN"];
  $PUB_HOUSE1=$ROW["PUB_HOUSE"];
  $PUB_DATE1=$ROW["PUB_DATE"];
  $AREA1=$ROW["AREA"];
  $AMT1=$ROW["AMT"];
  $PRICE1=$ROW["PRICE"];
  $BRIEF1=$ROW["BRIEF"];
  $LEND1=$ROW["LEND"];
  $BORR_PERSON1=$ROW["BORR_PERSON"];
  $MEMO1=$ROW["MEMO"];
  $OPEN1=$ROW["OPEN"];
  $BOOK_NO=$ROW["BOOK_NO"];
  
  $OPEN_ARR=explode(";", $OPEN1);
  if($ty == "true"){
	$sql = "select COUNT(BOOK_NO) from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and (BOOK_STATUS = '0' and STATUS = '0' or BOOK_STATUS = '0' and STATUS = '1' or BOOK_STATUS = '1' and STATUS = '0' or BOOK_STATUS = '1' and STATUS = '2') ";

	$cursor4 = exequery(TD::conn(),$sql);
	while($row1 = mysql_fetch_assoc($cursor4) ){
			$LEND_DESC =_("已借出").$row1['COUNT(BOOK_NO)']._("册");	
			$t = "N";
	}  
  	}else if($ty == "fales"){
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and (BOOK_STATUS = '0' and STATUS = '0' or BOOK_STATUS = '0' and STATUS = '1' or BOOK_STATUS = '1' and STATUS = '0' or BOOK_STATUS = '1' and STATUS = '2')";
		$cursor4 = exequery(TD::conn(),$sql);
		while($row1 = mysql_fetch_assoc($cursor4) ){
			if($BOOK_NO == $row1["BOOK_NO"]){
				$sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
				if($sum == 0){
					$LEND_DESC =_("已全部借出");
					$t = "N";
				}else{
					$LEND_DESC =$sum._("册未借出");	
					$t = "Y";
				}
			}else{
				$LEND_DESC=$ROW["AMT"]._("册未借出");
				$t = "Y";
			}
		} 		
	}else{
  		if($LEND1=="1"){
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' AND (BOOK_STATUS = '0' and STATUS = '0' or BOOK_STATUS = '0' and STATUS = '1' or BOOK_STATUS = '1' and STATUS = '0' or BOOK_STATUS = '1' and STATUS = '2')";
		$cursor4 = exequery(TD::conn(),$sql);
		while($row1 = mysql_fetch_assoc($cursor4) ){
			if($BOOK_NO == $row1["BOOK_NO"]){
				$sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
				if($sum == 0){
					$LEND_DESC =_("已全部借出");
					$t = "N";
				}else{
					$LEND_DESC =$sum._("册未借出");	
					$t = "Y";
				}
			}else{
				$LEND_DESC=$ROW["AMT"]._("册未借出");
				$t = "Y";
			}
		} 
  			//$LEND_DESC=_("已借出");
 		}else{
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and (BOOK_STATUS = '0' and STATUS = '0' or BOOK_STATUS = '0' and STATUS = '1' or BOOK_STATUS = '1' and STATUS = '0' or BOOK_STATUS = '1' and STATUS = '2')";

		$cursor4 = exequery(TD::conn(),$sql);
		while($row1 = mysql_fetch_assoc($cursor4) ){
			if($BOOK_NO == $row1["BOOK_NO"]){
				$sum = $ROW["AMT"]-$row1["COUNT(BOOK_NO)"];
				if($sum == 0){
					$LEND_DESC =_("已全部借出");
					$t = "N";
				}else{
					$LEND_DESC =_("已借出").$row1["COUNT(BOOK_NO)"]._("册,剩余").$sum._("册");
					$t = "Y";	
				}
			}else{
				$LEND_DESC=_("已借出0册,剩余").$ROW["AMT"]._("册");
				$t = "Y";
			}
		}
  			//$LEND_DESC=_("未借出");
 		}
  }
  if ($_SESSION["LOGIN_USER_PRIV"]!=1)
  {
  	if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT")
  	    continue;
  }
    if($OPEN1=="0" && $DEPT1!=$_SESSION["LOGIN_DEPT_ID"])
     continue;
	 if($ty == "fales"){
		 if($t != "N"){
			 $BOOK_COUNT++;
			 $count++;
		 }
	 }else{
  		$BOOK_COUNT++;
	 }
  
 
  if($BOOK_COUNT<$PAGE_START)
     continue;
  else if($BOOK_COUNT>$PAGE_END)
     break;

  $query2 = "select TYPE_NAME from BOOK_TYPE where TYPE_ID='$TYPE_ID1'";
  $cursor2=exequery(TD::conn(),$query2);

  if($ROW=mysql_fetch_array($cursor2))
  $TYPE_NAME=$ROW["TYPE_NAME"];

  $query3 = "select DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT1'";
  $cursor3=exequery(TD::conn(),$query3);
  if($ROW=mysql_fetch_array($cursor3))
     $DEPT_NAME=$ROW["DEPT_NAME"];

  $query3 = "select BOOK_STATUS,STATUS from BOOK_MANAGE where BOOK_NO='$BOOK_NO'";
  $cursor3=exequery(TD::conn(),$query3);
  $COUNT=0;
  while($ROW3=mysql_fetch_array($cursor3))
  {
  	$BOOK_STATUS3=$ROW3["BOOK_STATUS"];
  	$STATUS3=$ROW3["STATUS"];

  	if(($BOOK_STATUS3==0 && $STATUS3==0) || ($BOOK_STATUS3==0 && $STATUS3==1)|| ($BOOK_STATUS3==1 && $STATUS3==0)|| ($BOOK_STATUS3==1 && $STATUS3==2))
  	  $COUNT++;
  }
  if($BOOK_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>

  <tr class="<?=$TableLine?>">
    <td nowrap align="center"><?=$DEPT_NAME?></td>
    <td nowrap align="center"><?=$BOOK_NAME1?></td>
    <td nowrap align="center"><?=$BOOK_NO?></td>
    <td nowrap align="center"><?=$TYPE_NAME?></td>
    <td nowrap align="center"><?=$AUTHOR1?></td>
    <td nowrap align="center"><?=$PUB_HOUSE1?></td>
    <td nowrap align="center"><?=$AREA1?></td>
    <td nowrap align="center"><?=$LEND_DESC?></td>
    <td nowrap align="center" width="80">
<?
if($COUNT < $AMT1)
{
	if($t!="N"){
?>
      <a href="#" onClick="window.open('new.php?BOOK_ID=<?=$BOOK_ID?>&BOOK_NO=<?=$BOOK_NO?>','','height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=220,top=180,resizable=yes');"><?=_("借阅")?> </a>
<?
	}
}
?>
      <a href="javascript:detail('<?=$BOOK_ID?>','<?=$LEND_DESC?>');"><?=_("详情")?> </a>
    </td>
  </tr>

<?
}//while
/*if($ty == "fales"){	
	$PAGE_END=abs(($ROW["AMT"]-$count));	
}else{
	$PAGE_END;	
}*/
$MSG1 = sprintf(_("当前为第%s至%s条(第%d页，共%d页，每页最多%d条)"), "<b>".$PAGE_START."</b>","<b>".$PAGE_END."</b>",$PAGE_NUM,$PAGE_TOTAL,$PAGE_SIZE);
?>
<tr class="TableControl">
<td colspan="9" ><span class="small1" ><?=$MSG1?></span>
   <span style="float:right;"><input type="button"  value="<?=_("首页")?>" class="SmallButton"  <?if($PAGE_START==1)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&LEND=<?=$LEND?>&ORDER_FIELD=<?=$ORDER_FIELD?>'"> &nbsp;&nbsp;
   <input type="button"  value="<?=_("上一页")?>" class="SmallButton" <?if($PAGE_START==1)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>&LEND=<?=$LEND?>&ORDER_FIELD=<?=$ORDER_FIELD?>'"> &nbsp;&nbsp;
   <input type="button"  value="<?=_("下一页")?>" class="SmallButton" <?if($PAGE_END>=$BOOK_COUNT)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&PAGE_START=<?=($PAGE_END+1)?>&LEND=<?=$LEND?>&ORDER_FIELD=<?=$ORDER_FIELD?>'"> &nbsp;&nbsp;
   <input type="button"  value="<?=_("末页")?>" class="SmallButton"  <?if($PAGE_END>=$BOOK_COUNT)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&PAGE_START=<?=$LAST_PAGE_START?>&LEND=<?=$LEND?>&ORDER_FIELD=<?=$ORDER_FIELD?>'"> &nbsp;&nbsp;
   <?=_("页数")?>
   <input type="text" name="PAGE_NUM" id="PAGE_NUM" value="<?=$PAGE_NUM?>" class="SmallInput" size="2"> <input type="button"  value="<?=_("转到")?>" class="SmallButton" onClick="set_page();" title="<?=_("转到指定的页面")?>">&nbsp;&nbsp;
   </span>
</td>
</tr>
</table>

<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='search.php';">
</div>

</body>
</html>
<?
function DeptNameChange($OPEN)
{
   if($OPEN == "ALL_DEPT")
   {
      $DEPT_STR = _("全体部门");
      return $DEPT_STR;
   }
		$OPEN_ARRAY=explode(",",$OPEN);
	$DEPT_STR="";
	for($I=0;$I<count($OPEN_ARRAY);$I++)
	{
		 if($OPEN_ARRAY[$I]=="")
		 	 continue;
		 $query="select DEPT_NAME from DEPARTMENT where DEPT_ID='".$OPEN_ARRAY[$I]."'";
    	 $cursor=exequery(TD::conn(),$query);
    	 $num=mysql_num_rows($cursor);
    	 if($num>0)
    	 {
	   	 if($ROW=mysql_fetch_array($cursor))
	      	 $DEPT_STR.=$ROW["DEPT_NAME"].",";
       }
	}
	if(substr($DEPT_STR,-1)==",")
 		$DEPT_STR=substr($DEPT_STR,0,-1);

	return $DEPT_STR;

}