<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-04-11 ���ӷ�������ѯ�ж�
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

  $query="select * from AFFAIR where AFF_ID='$AFF_ID'";
  $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
  if($ROW=mysql_fetch_array($cursor))
  {
    $USER_ID=$ROW["USER_ID"];
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
    $END_TIME=$ROW["END_TIME"];
    if($END_TIME!=0)
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
     
    if($MANAGER_ID!=""&&substr($MANAGER_ID,-1)!=",")
       $MANAGER_ID.=",";
    $MANAGER_ID_NAME=GetUserNameById($MANAGER_ID);  
    
    if(substr($MANAGER_ID_NAME,-1)==",")
       $MANAGER_ID_NAME=substr($MANAGER_ID_NAME,0,-1);        
    
    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    if($TYPE=="2")
       $AFF_TIME=_("ÿ�� ").$REMIND_TIME;
    elseif($TYPE=="3")
    {
       if($REMIND_DATE=="1")
          $REMIND_DATE=_("һ");
       elseif($REMIND_DATE=="2")
          $REMIND_DATE=_("��");
       elseif($REMIND_DATE=="3")
          $REMIND_DATE=_("��");
       elseif($REMIND_DATE=="4")
          $REMIND_DATE=_("��");
       elseif($REMIND_DATE=="5")
          $REMIND_DATE=_("��");
       elseif($REMIND_DATE=="6")
          $REMIND_DATE=_("��");
       elseif($REMIND_DATE=="0")
          $REMIND_DATE=_("��");
       $AFF_TIME=_("ÿ��").$REMIND_DATE." ".$REMIND_TIME;
    }
    elseif($TYPE=="4")
       $AFF_TIME=_("ÿ��").$REMIND_DATE._("�� ").$REMIND_TIME;
    elseif($TYPE=="5")
       $AFF_TIME=_("ÿ��").str_replace("-",_("��"),$REMIND_DATE)._("�� ").$REMIND_TIME;
    
    $CONTENT=td_htmlspecialchars($CONTENT);

    $TITLE=csubstr($CONTENT,0,10);
    
    $BEGIN_TIME=csubstr($BEGIN_TIME,0,10);
    $END_TIME=csubstr($END_TIME,0,10);
  }
  
$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">
<script>
function del_aff_new(AFF_ID,from)
{
   var url='delete.php?AFF_ID='+AFF_ID+'&FROM='+from;
   if(window.confirm("<?=_("ɾ���󽫲��ɻָ���������������ŵ����񽫻�����ɾ����ȷ��ɾ����")?>"))
      location=url;
}
</script>

<body bgcolor="#FFFFCC" topmargin="5" style="background:none;">

<div id="main">
<div class="calendar-note-border-color" style="height:4px;"></div>
<div class="calendar-note-head">
<h1><?=nl2br($CONTENT)?></h1>
</div>
<div class="calendar-note-content">
<div class="calendar-note">
<label class="calendar-note-font"><?=_("��ʼ���ڣ�")?></label>
<span style="color: #427297;font-family: arial; "><?=$BEGIN_TIME?></span></div>
<div class="calendar-note">
<label class="calendar-note-font"><?=_("�������ڣ�")?></label>
<span style="color: #427297;font-family: arial; "><?=$END_TIME?></span>
</div>
<div class="calendar-note">
<label class="calendar-note-font"><?=_("�ظ�ʱ�䣺")?></label>
<span style="color: #427297;font-family: arial; "><?=$AFF_TIME?></span>
</div>
<div class="calendar-note" style="min-height:150px"><lable class="calendar-note-font"><?=_("�ճ����ݣ�")?></lable><div><?=$CONTENT?></div></div>
</div>
<?if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]) echo "<div class='calendar-note'>
<label class='calendar-note-font'>"._("�����ˣ�")."</label><span style='color: #427297;font-family: arial;'>".$MANAGER_ID_NAME."</span></div><br>"; ?>

<?
if($FROM==1 &&($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]||($MANAGER_ID==""&&$USER_ID==$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1))
{
?>
<br>
<br>
<br>
<center>
<input type="button" value="<?=_("�޸�")?>"  onclick="window.open('new_affair.php?AFF_ID=<?=$AFF_ID?>&FROM=<?=$FROM?>','oa_sub_window','height=355,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes')" class="BigButtonA">&nbsp;&nbsp;	
<input type="button" value="<?=_("ɾ��")?>"  onclick="del_aff_new(<?=$AFF_ID?>,1)" class="BigButtonA">	
</center>	
<?	
}
?>
</div>
</body>
</html>
