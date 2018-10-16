<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$PAGE_SIZE=15;

$HTML_PAGE_TITLE = _("图书管理");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script Language="JavaScript">
function delete_book(BOOK_ID)
{
	msg='<?=_("确认要删除该图书吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$PAGE_START?>&BOOK_ID=" + BOOK_ID;
		window.location=URL;
	}
}

function set_page()
{
	var PAGE_NUM = document.getElementById("PAGE_NUM").value;
	var PAGE_START=(PAGE_NUM-1)*<?=$PAGE_SIZE?>+1;
	location="list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START="+PAGE_START;
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='book_select']").prop("checked",true);
        }
        else
        {
            jQuery("[name='book_select']").prop("checked",false);
        }    
    });
    jQuery("input[name='book_select']").click(function(){
        jQuery("#allbox_for").prop("checked",false);
    })
})
function get_checked()
{
    checked_str="";
    jQuery("input[name='book_select']:checkbox").each(function(){
        if(jQuery(this).is(":checked"))
        {
            checked_str +=jQuery(this).val()+',';
        }
    })
    return checked_str;
}

function delete_check_book()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要删除图书记录，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选图书记录吗？")?>';
  if(window.confirm(msg))
  {
     url="delete_check.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$PAGE_START?>&DELETE_STR=" + delete_str;     
     location=url;
  }
}
</script>


<body class="bodycolor" >
<?
$query = "SELECT MANAGE_DEPT_ID from BOOK_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER_ID)";
$cursor= exequery(TD::conn(),$query);
$MANAGE_PRIV = 0;
$MANAGE_DEPT_ID_STR = "";
while($ROW=mysql_fetch_array($cursor))
{
	 $MANAGE_DEPT_ID = $ROW["MANAGE_DEPT_ID"];
	 
	 if($MANAGE_DEPT_ID=="ALL_DEPT")
	 {
	    $MANAGE_PRIV = 1;
	    $MANAGE_DEPT_ID_STR ="";
	    break;
	 }
	 $MANAGE_DEPT_ID_STR.=$ROW["MANAGE_DEPT_ID"];
}
	 

//-----------先组织SQL语句-----------
$WHERE_STR=" where 1=1";
if($BOOK_NAME!="")
  $WHERE_STR.=" and BOOK_NAME like '%".$BOOK_NAME."%'";
  
if($BOOK_NO!="")
  $WHERE_STR.=" and b.BOOK_NO like '%".$BOOK_NO."%'";

/*if($LEND!="")
  $WHERE_STR.=" and LEND='$LEND'";*/

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

$LOGIN_DEPT_ID=td_trim($_SESSION["LOGIN_DEPT_ID"]);
$LOGIN_USER_ID=td_trim($_SESSION["LOGIN_USER_ID"]);
$LOGIN_PRIV_ID=td_trim($_SESSION["$LOGIN_USER_PRIV"]);

//if($LOGIN_USER_PRIV!=1)  
   //$WHERE_STR.=" and (OPEN='0' or OPEN='' or OPEN='1' or find_in_set('$LOGIN_DEPT_ID',OPEN) or find_in_set('$LOGIN_USER_ID',OPEN) or find_in_set('$LOGIN_PRIV_ID',OPEN) or OPEN='ALL_DEPT' or OPEN='ALL_DEPT;;' or BORR_PERSON='$LOGIN_USER_NAME')";
if($LEND!=""){
	if($LEND == "1"){
		$query="select DISTINCT b.* from BOOK_MANAGE as a , BOOK_INFO as b ".$WHERE_STR." and  b.BOOK_NO=a.BOOK_NO and a.BOOK_STATUS='0' ";
		$ty="true";
	}else if($LEND == "0"){
		$query="select * from BOOK_INFO as b ".$WHERE_STR." order by ".$ORDER_FIELD;
		$ty = "fales";
	}
}else{
      $query="SELECT DEPT,OPEN,BORR_PERSON from BOOK_INFO as b ".$WHERE_STR." order by ".$ORDER_FIELD;
}
//$query="SELECT DEPT,OPEN,BORR_PERSON from BOOK_INFO ".$WHERE_STR." order by '$ORDER_FIELD' ";
$cursor= exequery(TD::conn(),$query);
$BOOK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{

  $DEPT1=$ROW["DEPT"];
  $OPEN1=$ROW["OPEN"];
  $BORR_PERSON1=$ROW["BORR_PERSON"];
  $OPEN_ARR=explode(";", $OPEN1);
  if ($_SESSION["LOGIN_USER_PRIV"]!=1)
  {
  	  if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT" && $_SESSION["LOGIN_USER_ID"]!=$BORR_PERSON1)
  	     continue;
  }
      
  if($OPEN1=="0" && $DEPT1!=$_SESSION["LOGIN_DEPT_ID"] && $_SESSION["LOGIN_USER_NAME"]!=$BORR_PERSON1)
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

$query1=str_replace("DEPT,OPEN,BORR_PERSON","*",$query);
$cursor1 = exequery(TD::conn(), $query1);

if($BOOK_COUNT==0)
{
   Message(_("提示"),_("没有符合条件的图书"));
?>
<br>
<div align="center">
<input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';">
</div>
<?
   exit;
}

$MSG1 = sprintf(_("当前为第%s至%s条(第%d页，共%d页，每页最多%d条)"), "<b>".$PAGE_START."</b>","<b>".$PAGE_END."</b>",$PAGE_NUM,$PAGE_TOTAL,$PAGE_SIZE);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("图书查询结果")?> </span><br>
  </td>
  <td valign="bottom">
  <span class="small1"><?=$MSG1?></small>
  </td>
  </tr>
</table>

<table class="TableList" width="95%" align="center">
<tr class="TableHeader">
	  <td nowrap align="center"><?=_("选择")?></td>
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("书名")?></td>
    <td nowrap align="center"><?=_("编号")?></td>
    <td nowrap align="center"><?=_("类别")?></td>
    <td nowrap align="center"><?=_("作者")?></td>
    <td nowrap align="center"><?=_("出版社")?></td>
    <td nowrap align="center"><?=_("存放地点")?></td>
    <?
    if($_SESSION["LOGIN_USER_NAME"]==$BORR_PERSON1 || $_SESSION["LOGIN_USER_PRIV"]=='1' || $MANAGE_DEPT_ID_STR!="" && find_id($MANAGE_DEPT_ID_STR,$DEPT1) || $MANAGE_PRIV==1)
    {
    ?>
    <td nowrap align="center"><?=_("借阅范围")?></td>
    <?
    }
    ?>
    <td nowrap align="center"><?=_("借阅情况")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
</tr>

<?
$BOOK_COUNT = 0;
$CHECK_COUNT = 0;
while($ROW=mysql_fetch_array($cursor1))
{
  $DEPT1=$ROW["DEPT"];
  $OPEN1=$ROW["OPEN"];
  $BORR_PERSON1=$ROW["BORR_PERSON"];
  $OPEN_ARR=explode(";", $OPEN1);
  if ($_SESSION["LOGIN_USER_PRIV"]!=1)
  {
  	if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT" && $_SESSION["LOGIN_USER_ID"]!=$BORR_PERSON1)
  	   continue;
  }
      
  if($OPEN1=="0" && $DEPT1!=$_SESSION["LOGIN_DEPT_ID"] && $_SESSION["LOGIN_USER_NAME"]!=$BORR_PERSON1)
     continue;

  $BOOK_COUNT++;

  if($BOOK_COUNT < $PAGE_START)
     continue;
  else if($BOOK_COUNT > $PAGE_END)
     break;

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
  $DEPT1=$ROW["DEPT"];
  $BOOK_NO=$ROW["BOOK_NO"];
  //$OPEN1=$ROW["OPEN"];
  
  /*if($LEND1=="1")
  	$LEND_DESC=_("已借出");
  	else
  	$LEND_DESC=_("未借出");*/
  
if($ty == "true"){
	$sql = "select COUNT(BOOK_NO) from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";

	$cursor4 = exequery(TD::conn(),$sql);
	while($row1 = mysql_fetch_assoc($cursor4) ){
			$LEND_DESC =_("已借出").$row1['COUNT(BOOK_NO)']._("册");	
			$t = "N";
	}  
  	}else if($ty == "fales"){
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";
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
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' AND BOOK_STATUS ='0'";
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
		$sql = "select COUNT(BOOK_NO),BOOK_NO from BOOK_MANAGE where BOOK_NO = '$BOOK_NO' and BOOK_STATUS = '0'";

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

  $query2 = "SELECT TYPE_NAME from BOOK_TYPE where TYPE_ID='$TYPE_ID1'";
  $cursor2=exequery(TD::conn(),$query2);
  if($ROW=mysql_fetch_array($cursor2))
  	$TYPE_NAME=$ROW["TYPE_NAME"];

  $query3 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT1'";
  $cursor3=exequery(TD::conn(),$query3);
  if($ROW=mysql_fetch_array($cursor3))
  	$DEPT_NAME=$ROW["DEPT_NAME"];

  if($BOOK_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>

  <tr class="<?=$TableLine?>">
  	<td nowrap align="center">&nbsp;
<?
if($_SESSION["LOGIN_USER_NAME"]==$BORR_PERSON1 || $_SESSION["LOGIN_USER_PRIV"]=='1' || $MANAGE_DEPT_ID_STR!="" && find_id($MANAGE_DEPT_ID_STR,$DEPT1) || $MANAGE_PRIV==1)
{
	 $CHECK_COUNT++;
?>  		
  		<input type="checkbox" name="book_select" value="<?=$BOOK_ID?>" >
<?
}
?>
  	</td>
    <td nowrap align="center"><?=$DEPT_NAME?></td>
    <td nowrap align="center"><?=$BOOK_NAME1?></td>
    <td nowrap align="center"><?=$BOOK_NO?></td>
    <td nowrap align="center"><?=$TYPE_NAME?></td>
    <td nowrap align="center"><?=$AUTHOR1?></td>
    <td nowrap align="center"><?=$PUB_HOUSE1?></td>
    <td nowrap align="center"><?=$AREA1?></td>
    <?
    if($_SESSION["LOGIN_USER_NAME"]==$BORR_PERSON1 || $_SESSION["LOGIN_USER_PRIV"]=='1' || $MANAGE_DEPT_ID_STR!="" && find_id($MANAGE_DEPT_ID_STR,$DEPT1) || $MANAGE_PRIV==1)
    {
    ?> 
    <td align="center" width="40%"><?=td_trim(DeptNameChange($OPEN1))?></td>
    <?
    }
    ?>
    <td nowrap align="center"><?=$LEND_DESC?></td>
    <td nowrap align="center" width="80">
<?
if($_SESSION["LOGIN_USER_NAME"]==$BORR_PERSON1 || $_SESSION["LOGIN_USER_PRIV"]=='1' || $MANAGE_DEPT_ID_STR!="" && find_id($MANAGE_DEPT_ID_STR,$DEPT1) || $MANAGE_PRIV==1)
{
?>    	
      <a href="edit.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$PAGE_START?>&BOOK_ID=<?=$BOOK_ID?>"> <?=_("编辑")?></a>&nbsp;&nbsp;
      <a href="javascript:delete_book(<?=$BOOK_ID?>);"> <?=_("删除")?> </a>
<?
}
?>
    </td>
  </tr>

<?
}

?>
  <tr class="TableControl">
  <td align="left" colspan="3">
<?
if($CHECK_COUNT>0)
{
?>
  	<input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("全选")?></label>&nbsp;
    <a href="javascript:delete_check_book();" title="<?=_("删除所选图书")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>
<?
}
?>
    &nbsp;  	
  </td>
  <td colspan="8" align="right">   
     <input type="button"  value="<?=_("首页")?>" class="SmallButton"  <?if($PAGE_START==1)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&LEND=<?=$LEND?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("上一页")?>" class="SmallButton" <?if($PAGE_START==1)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>&LEND=<?=$LEND?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("下一页")?>" class="SmallButton" <?if($PAGE_END>=$BOOK_COUNT)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=($PAGE_END+1)?>&LEND=<?=$LEND?>'"> &nbsp;&nbsp;
     <input type="button"  value="<?=_("末页")?>" class="SmallButton"  <?if($PAGE_END>=$BOOK_COUNT)echo "disabled";?> onClick="location='list.php?TYPE_ID=<?=$TYPE_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&AREA=<?=$AREA?>&DEPT_ID=<?=$DEPT_ID?>&ORDER_FIELD=<?=$ORDER_FIELD?>&PAGE_START=<?=$LAST_PAGE_START?>&LEND=<?=$LEND?>'"> &nbsp;&nbsp;
     <?=_("页数")?>
     <input type="text" name="PAGE_NUM" id="PAGE_NUM" value="<?=$PAGE_NUM?>" class="SmallInput" size="2"> <input type="button"  value="<?=_("转到")?>" class="SmallButton" onClick="set_page();" title="<?=_("转到指定的页面")?>">&nbsp;&nbsp;
  </td>
  </tr>
</table>

<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';">
</div>

</body>
</html>
<?
function DeptNameChange($OPEN)
{
	
	$OPEN1=explode(";", $OPEN);
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

   /*if($OPEN == "ALL_DEPT")
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
 		$DEPT_STR=substr($DEPT_STR,0,-1);*/
	return $DEPT_STR;
		
}

?>