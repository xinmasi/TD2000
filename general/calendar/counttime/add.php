<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("��ʱ����");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?

$CUR_DATE=date("Y-m-d",time());
if($YEAR=="on")
{
    $ANNUAL=1;
}
else
{
    $ANNUAL=0;
}
$COUNT_TYPE=0;//����ʱ
if($ROW_ID=="") //��Ӽ�ʱ��
{
  if($COUNT_TYPE=="0") //����ʱ
  {
  	$query="insert into COUNTDOWN (ORDER_NO,TO_USER,CONTENT,BEGIN_TIME,END_TIME,COUNT_TYPE,ISPRIVATE,STYLE,ANNUAL) values ('$ORDER_NO','".$_SESSION["LOGIN_USER_ID"]."','$CONTENT','$CUR_DATE','$END_TIME_D','$COUNT_TYPE','1','$STYLE','$ANNUAL');";
  }
  else if($COUNT_TYPE=="1") //����ʱ
  {
  	$query="insert into COUNTDOWN (ORDER_NO,TO_DEPT,TO_PRIV,TO_USER,CONTENT,END_TIME,COUNT_TYPE,BEGIN_TIME,STYLE) values ('$ORDER_NO','$TO_DEPT','$TO_PRIV','$TO_USER','$CONTENT','$END_TIME_Z','$COUNT_TYPE','$BEGIN_TIME','$STYLE')";
  }
}
else
{
	if($COUNT_TYPE=="0") //����ʱ
	{
  	$query="update COUNTDOWN set ORDER_NO='$ORDER_NO',TO_USER='".$_SESSION["LOGIN_USER_ID"]."',CONTENT='$CONTENT',END_TIME='$END_TIME_D',COUNT_TYPE='$COUNT_TYPE',STYLE='$STYLE',ANNUAL='$ANNUAL' where ROW_ID='$ROW_ID';";
  }
  else if($COUNT_TYPE=="1") //����ʱ
  {
  	$query="update COUNTDOWN set ORDER_NO='$ORDER_NO',TO_DEPT='$TO_DEPT',TO_PRIV='$TO_PRIV',TO_USER='$TO_USER',CONTENT='$CONTENT',END_TIME='$END_TIME_Z',COUNT_TYPE='$COUNT_TYPE',BEGIN_TIME='$BEGIN_TIME',STYLE='$STYLE' where ROW_ID='$ROW_ID';";
  }
}
exequery(TD::conn(), $query);
if($COUNT_TYPE==0)
   Message("",_("����ʱ���ñ���ɹ�"));  
else 
   Message("",_("����ʱ���ñ���ɹ�"));
?>
<div align="center">
  <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?IS_MAIN=1'">
</div>
</body>
</html>
