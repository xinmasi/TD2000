<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("提交图书修改");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($PUB_DATE!=""&&$PUB_DATE!="0000-00-00")
{
  $TIME_OK=is_date($PUB_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("出版日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($AMT!="")
{
  $TIME_OK=is_number($AMT);

  if(!$TIME_OK)
  { Message(_("错误"),_("数量错误"));
    Button_Back();
    exit;
  }
}

//--------- 上传附件 ----------
if(count($_FILES)>=1)
{
   if($ATTACHMENT_ID_OLD!=""&&$ATTACHMENT_NAME_OLD!="")
      delete_attach($ATTACHMENT_ID_OLD,$ATTACHMENT_NAME_OLD);
   
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME=trim($ATTACHMENTS["NAME"],"*");
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
$BOOK_ID=intval($BOOK_ID);
$TYPE_ID=intval($TYPE_ID);
$DEPT_ID=intval($DEPT_ID);

//echo $TO_ID;exit;

//连接字符串，选择部门，人员，角色三种同事存放在一个字段中
/*if($COPY_TO_ID!="")
$TO_ID=$TO_ID.";".$COPY_TO_ID;
echo $TO_ID;exit;
if($PRIV_ID!="")
$TO_ID.=";".$PRIV_ID;*/

 $TO_ID=$TO_ID.";".$COPY_TO_ID.";".$PRIV_ID;

$query="update BOOK_INFO set BOOK_NAME='$BOOK_NAME',TYPE_ID='$TYPE_ID',AUTHOR='$AUTHOR',ISBN='$ISBN',PUB_HOUSE='$PUB_HOUSE',PUB_DATE='$PUB_DATE',AREA='$AREA',PRICE='$PRICE',BRIEF='$BRIEF',AMT='$AMT',LEND='$LEND',BORR_PERSON='$BORR_PERSON',MEMO='$MEMO',DEPT='$DEPT_ID',BOOK_NO='$BOOK_NO',OPEN='$TO_ID',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where BOOK_ID='$BOOK_ID'";
exequery(TD::conn(),$query);

header("location: list.php?".$QUERY_LIST);
?>
</body>
</html>
