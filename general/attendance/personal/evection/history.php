<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������ʷ��¼");
include_once("inc/header.inc.php");
?>




<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="bodycolor attendance">

<h5 class="attendance-title"><span class="big3"> <?=_("����Ǽ�")?></span></h5><br>

<br>
<div align="center">
<?
 //---- ������ʷ��¼ -----
 $EVECTION_COUNT=0;

 $query = "SELECT * from ATTEND_EVECTION where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='2' order by EVECTION_DATE1 desc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $EVECTION_COUNT++;

    $EVECTION_ID=$ROW["EVECTION_ID"];
    $EVECTION_DEST=$ROW["EVECTION_DEST"];
    $REASON=$ROW["REASON"];    
    $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
    $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
    $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
    $EVECTION_DATE2=strtok($EVECTION_DATE2," ");   
    $LEADER_ID=$ROW["LEADER_ID"];
    $STATUS=$ROW["STATUS"];    
    $ALLOW=$ROW["ALLOW"];   
    
    if($ALLOW=="1"&&$STATUS=="2")
       $ALLOW_DESC=_("�ѹ���"); 
    
    $USER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_NAME=$ROW["USER_NAME"];

    if($EVECTION_COUNT==1)
    {
?>

    <table class="table table-bordered" width="95%">
        <tr class="">
            <th nowrap align="center"><?=_("�������")?></th>
            <th nowrap align="center"><?=_("������Ա")?></th>
            <th nowrap align="center"><?=_("����")?></th>      
            <th nowrap align="center"><?=_("��ʼ����")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></th>
            <th nowrap align="center"><?=_("��������")?></th>
            <th nowrap align="center"><?=_("״̬")?></th> 
      <!--      <td nowrap align="center"><?=_("����")?></td>-->           
        </tr>
<?
    }

    if($LEAVE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="">
      <td nowrap align="center"><?=$EVECTION_DEST?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$REASON?></td>      
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center" width="30%"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
<!--      <td nowrap align="center"><a href="edit.php?EVECTION_ID=<?=$EVECTION_ID?>" title="<?=_("���ѣ��޸��ύ����Ҫ��������")?>"><?=_("�޸�")?></a></td>-->
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    
    
    </table>
<?
 }
 else
    message("",_("����ʷ��¼"));
?>

</div><br><br>
<div align="center">
  <input type="button"  value="<?=_("������ҳ")?>" class="btn" onClick="location='index.php';">
</div>
</body>
</html>