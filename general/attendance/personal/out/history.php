<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ʷ��¼");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="bodycolor attendance">
    <h5 class="attendance-title"><span class="big3"> <?=_("�����ʷ��¼")?></span></h5><br>
<br>
<div align="center">
<?
 //---- �����ʷ��¼���ѹ����� -----
 $OUT_COUNT=0;
 $query = "SELECT * from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' order by SUBMIT_TIME desc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $OUT_COUNT++;

    $CREATE_DATE=$ROW["CREATE_DATE"];
    $OUT_ID=$ROW["OUT_ID"];    
    $OUT_TIME1=$ROW["OUT_TIME1"];
    $OUT_TIME2=$ROW["OUT_TIME2"];
    $OUT_TYPE=$ROW["OUT_TYPE"];
    $ALLOW=$ROW["ALLOW"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $REASON=$ROW["REASON"];

    $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
    $SUBMIT_TIME1=substr($SUBMIT_TIME,0,10);

    $USER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_NAME=$ROW["USER_NAME"];

    $OUT_TYPE=str_replace("<","&lt",$OUT_TYPE);
    $OUT_TYPE=str_replace(">","&gt",$OUT_TYPE);
    $OUT_TYPE=stripslashes($OUT_TYPE);

    if($ALLOW=="1")
       $ALLOW_DESC=_("�ѹ���");
    
    if($OUT_COUNT==1)
    {
?>

    <table class="table table-bordered">
        <tr class="">
            <th nowrap align="center"><?=_("����ʱ��")?></th>
            <th nowrap align="center"><?=_("������Ա")?></th>
            <th nowrap align="center"><?=_("���ԭ��")?></th>
            <th nowrap align="center"><?=_("��ʼʱ��")?></th>
            <th nowrap align="center"><?=_("����ʱ��")?></th>
            <th nowrap align="center"><?=_("״̬")?></th>
        </tr>
<?
    }

    if($OUT_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    
    <tr>
      <td nowrap align="center"><?=$CREATE_DATE?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$SUBMIT_TIME1?> <?=$OUT_TIME2?></td>
      <td nowrap align="center" title="<?if($ALLOW==2) echo _("ԭ��")."\n".$REASON?>"><?=$ALLOW_DESC?>
<!--      	<a href="edit.php?OUT_ID=<?=$OUT_ID?>" title="<?=_("���ѣ��޸��ύ����Ҫ��������")?>"><?=_("�޸�")?></a>-->
      </td>
    </tr>
<?
 }

 if($OUT_COUNT>0)
 {
?>
   
    </table>
<?
 }
 else
    message("",_("����ʷ��¼"));
?>

</div>
<br><br>
<div align="center">
  <input type="button"  value="<?=_("������ҳ")?>" class="btn" onClick="location='index.php';">
</div>
</body>
</html>