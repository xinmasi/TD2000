<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>



<script>

function show_reader(GROUP_ID)
{
 URL="show_reader.php?GROUP_ID="+GROUP_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function show_desc(FLOW_ID)
{
 URL="show_desc.php?FLOW_ID="+FLOW_ID;
 myleft=(screen.availWidth-50)/2;
 window.open(URL,"read_vote","height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function show_man(FLOW_ID,FLAG)
{
 URL="show_man.php?FLOW_ID="+FLOW_ID+"&type="+FLAG;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_vote(FLOW_ID,CUR_PAGE)
{
 msg='<?=_("ȷ��Ҫɾ���ÿ������񣿿������ݽ���ɾ���Ҳ��ɻָ�����")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FLOW_ID=" + FLOW_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}
function show_labor(FLOW_ID)
{
  URL="show_labor.php?FLOW_ID="+FLOW_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"read_score","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="bodycolor">

<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
 	 $query="select count(*) from SCORE_FLOW";
else
	 $query = "SELECT count(*) from SCORE_FLOW where CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID=''";
$cursor= exequery(TD::conn(),$query, $connstatus);
$VOTE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $VOTE_COUNT=$ROW[0];

if($VOTE_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3">&nbsp;&nbsp;<?=_("�����ѷ����Ŀ�������")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("���ѷ����Ŀ�������"));
   exit;
}

$PER_PAGE=15;
$PAGES=10;
$PAGE_COUNT=ceil($VOTE_COUNT/$PER_PAGE);

if($CUR_PAGE<=0 || $CUR_PAGE=="")
  $CUR_PAGE=1;
if($CUR_PAGE>$PAGE_COUNT)
  $CUR_PAGE=$PAGE_COUNT;
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" align="absmiddle" WIDTH="18" HEIGHT="18"><span class="big3">&nbsp;&nbsp;<?=_("�����ѷ����Ŀ�������")?></span><br>
    </td>
<?
   $COUNT_MSG = sprintf(_("��%s��"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");
?>
    <td align="right" valign="bottom" class="small1"><?=$COUNT_MSG?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
   $PAGE_UP = sprintf(_("��%dҳ"),$PAGES);
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>"><?=$PAGE_UP?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
   if($I>$PAGE_COUNT)
      break;

   if($I==$CUR_PAGE)
   {
?>
       [<?=$I?>]&nbsp;
<?
   }
   else
   {
?>
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
   $PAGE_DOWN = sprintf(_("��%dҳ"),$PAGES);
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>"><?=$PAGE_DOWN?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}
?>
       &nbsp;
    </td>
    </tr>
</table>

<br>
<table width="98%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("������������")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����ָ�꼯")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��Ч����")?></td>
      <td nowrap align="center"><?=_("��ֹ����")?></td>

      <td nowrap align="center"><?=_("״̬")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>

<?
 //============================ ��ʾ�ѷ����������� =======================================
$CUR_DATE=date("Y-m-d",time());
if($_SESSION["LOGIN_USER_PRIV"]==1)
 		$query = "SELECT * from SCORE_FLOW order by SEND_TIME desc";
else
 		$query = "SELECT * from SCORE_FLOW where CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID='' order by SEND_TIME desc";

 $cursor= exequery(TD::conn(),$query, $connstatus);
 $VOTE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $VOTE_COUNT++;

    if($VOTE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($VOTE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;
    $FLOW_ID=$ROW["FLOW_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $PARTICIPANT =$ROW["PARTICIPANT"];
    $GROUP_ID=$ROW["GROUP_ID"];
    $ANONYMITY=$ROW["ANONYMITY"]; 
    $CREATE_USER_ID =$ROW["CREATE_USER_ID"];

    if($ANONYMITY=="0")
       $ANONYMITY_DESC=_("������");
    else
       $ANONYMITY_DESC=_("����");

    if($END_DATE=="0000-00-00")
       $END_DATE="";
      $RAN_NAME="";
      $TOK=strtok($RANKMAN,",");
      while($TOK!="")
      {
        if($RAN_NAME!="")
           $RAN_NAME.=_("��");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $RAN_NAME.=$ROW["USER_NAME"];
           $RAN_NAME.="....";
        break;
        $TOK=strtok(",");
      }
      $PARTI_NAME="";
      $TOK=strtok($PARTICIPANT,",");
      while($TOK!="")
      {
        if($PARTI_NAME!="")
           $PARTI_NAME.=_("��");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $PARTI_NAME.=$ROW["USER_NAME"];
           $PARTI_NAME.="....";
           break;
        $TOK=strtok(",");
      }

       $query1="select * from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
           $GROUP_NAME=$ROW["GROUP_NAME"];
       else
       		$GROUP_NAME="<font color='red'>"._("ָ�꼯��ɾ���򲻴���")."</font>";
    if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
    {
       $VOTE_STATUS=1;
       $VOTE_STATUS_STR=_("����Ч");
    }
    else
    {
       $VOTE_STATUS=2;
       $VOTE_STATUS_STR="<font color='#00AA00'><b>"._("��Ч")."</font>";
    }


    if($END_DATE!="")
    {
      if(compare_date($CUR_DATE,$END_DATE)>0)
      {
         $VOTE_STATUS=3;
         $VOTE_STATUS_STR="<font color='#FF0000'><b>"._("��ֹ")."</font>";
      }
    }

    if($VOTE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$FLOW_TITLE?></td>
      <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','1');" title="<?=_("����鿴���еĿ�����Ա")?>"><?=$RAN_NAME?></a></td>
      <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','0');" title="<?=_("����鿴���еı�������Ա")?>"><?=$PARTI_NAME?></a></td>
      <td align="center"><a href="javascript:show_reader('<?=$GROUP_ID?>');" title="<?=_("����鿴������Ŀ")?>"><?=$GROUP_NAME?></a></td>
      <td align="center"><?=$ANONYMITY_DESC?></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></td>

      <td nowrap align="center"><?=$VOTE_STATUS_STR?></td>
<?
			if($_SESSION["LOGIN_UID"]==1||$CREATE_USER_ID==$_SESSION["LOGIN_USER_ID"])
			{
?>
	      <td nowrap align="center">
	      	  <a href="copy.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("��¡")?></a>
	      	  <a  href="javascript:show_labor('<?=$FLOW_ID?>');" title="<?=_("�������")?>"> <?=_("�������")?></a>
	      	  <a href="new.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�޸�")?></a>
			  <a href="javascript:delete_vote('<?=$FLOW_ID?>','<?=$CUR_PAGE?>');"> <?=_("ɾ��")?></a>
	<?
			      if($VOTE_STATUS==1)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=1&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("������Ч")?></a>
	<?
			      }
			      else if($VOTE_STATUS==2)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=2&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("������ֹ")?></a>
	<?
			      }
			      else if($VOTE_STATUS==3)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=3&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�ָ���Ч")?></a>
	<?
	      		}
	?>
	      </td>
<?
		  }
		  else
		  {
?>
	      <td nowrap align="center">
	      	  <a href="show.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�鿴")?></a>
	     	</td>
<?
			}
?>
    </tr>
<?
 }
?>



</table>
</body>

</html>
