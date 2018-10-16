<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("增加图书");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$TYPE_ID =intval($TYPE_ID );
$DEPT_ID=intval($DEPT_ID);
//------------------------ 合法性检验 ----------------------
$query="select * from BOOK_INFO where TYPE_ID='$TYPE_ID' and DEPT='$DEPT_ID' and BOOK_NAME='$BOOK_NAME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$MSG = sprintf(_("图书 《%s》 已存在"), $BOOK_NAME);
  Message(_("错误"),$MSG);
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?DEPT_ID=<?=$DEPT_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&TYPE_ID=<?=$TYPE_ID?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&PUB_DATE=<?=$PUB_DATE?>&AREA=<?=$AREA?>&AMT=<?=$AMT?>&PRICE=<?=$PRICE?>&BRIEF=<?=$BRIEF?>&OPEN=<?=$TO_ID?>&LEND=<?=$LEND?>&BORR_PERSON=<?=$BORR_PERSON?>&MEMO=<?=$MEMO?>&BOOK_NO=<?=$BOOK_NO?>'">
</div>

<?
  exit;
}

$query="select * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$MSG = sprintf(_("图书编号 《%s》 已存在"), $BOOK_NO);
  Message(_("错误"),$MSG);
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?DEPT_ID=<?=$DEPT_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&TYPE_ID=<?=$TYPE_ID?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&PUB_DATE=<?=$PUB_DATE?>&AREA=<?=$AREA?>&AMT=<?=$AMT?>&PRICE=<?=$PRICE?>&BRIEF=<?=$BRIEF?>&OPEN=<?=$TO_ID?>&LEND=<?=$LEND?>&BORR_PERSON=<?=$BORR_PERSON?>&MEMO=<?=$MEMO?>&BOOK_NO=<?=$BOOK_NO?>'">
</div>

<?
  exit;
}

if($PUB_DATE!=""&&$PUB_DATE!="0000-00-00")
{
  $TIME_OK=is_date($PUB_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("出版日期格式不对，应形如 1999-1-2"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?DEPT_ID=<?=$DEPT_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&TYPE_ID=<?=$TYPE_ID?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&PUB_DATE=<?=$PUB_DATE?>&AREA=<?=$AREA?>&AMT=<?=$AMT?>&PRICE=<?=$PRICE?>&BRIEF=<?=$BRIEF?>&OPEN=<?=$TO_ID?>&LEND=<?=$LEND?>&BORR_PERSON=<?=$BORR_PERSON?>&MEMO=<?=$MEMO?>&BOOK_NO=<?=$BOOK_NO?>'">
</div>

<?
    exit;
  }
}

if($AMT!="")
{
  $TIME_OK=is_number($AMT);

  if(!$TIME_OK)
  { Message(_("错误"),_("数量错误"));
?>

<br>
<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?DEPT_ID=<?=$DEPT_ID?>&BOOK_NAME=<?=$BOOK_NAME?>&TYPE_ID=<?=$TYPE_ID?>&AUTHOR=<?=$AUTHOR?>&ISBN=<?=$ISBN?>&PUB_HOUSE=<?=$PUB_HOUSE?>&PUB_DATE=<?=$PUB_DATE?>&AREA=<?=$AREA?>&AMT=<?=$AMT?>&PRICE=<?=$PRICE?>&BRIEF=<?=$BRIEF?>&OPEN=<?=$TO_ID?>&LEND=<?=$LEND?>&BORR_PERSON=<?=$BORR_PERSON?>&MEMO=<?=$MEMO?>&BOOK_NO=<?=$BOOK_NO?>'">
</div>

<?
    exit;
  }
}

//--------- 上传附件 ----------
if(count($_FILES)>=1)
{
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME=trim($ATTACHMENTS["NAME"],"*");
}

//----------------------------------------------------------


//连接字符串，选择部门，人员，角色三种同事存放在一个字段中
/*if($COPY_TO_ID!="")
$TO_ID=$TO_ID.";".$COPY_TO_ID;
if($PRIV_ID!="")
$TO_ID.=";".$PRIV_ID;*/

 $TO_ID=$TO_ID.";".$COPY_TO_ID.";".$PRIV_ID;

$query="INSERT into BOOK_INFO(BOOK_NAME,TYPE_ID,AUTHOR,ISBN,PUB_HOUSE,PUB_DATE,AREA,PRICE,BRIEF,AMT,OPEN,LEND,BORR_PERSON,MEMO,DEPT,BOOK_NO,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$BOOK_NAME','$TYPE_ID','$AUTHOR','$ISBN','$PUB_HOUSE','$PUB_DATE','$AREA','$PRICE','$BRIEF','$AMT','$TO_ID','$LEND','$BORR_PERSON','$MEMO','$DEPT_ID','$BOOK_NO','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
exequery(TD::conn(),$query);
Message("",_("图书添加成功"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center> 
</body>
</html>
