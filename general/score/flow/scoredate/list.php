<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
?>
<script>

function show_reader(GROUP_ID,FLOW_ID,ANONYMITY)
{
 URL="query.php?GROUP_ID="+GROUP_ID+"&FLOW_ID=" + FLOW_ID+"&ANONYMITY="+ANONYMITY;
 myleft=(screen.availWidth-800)/2;
 window.open(URL,"read_vote","height=500,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function show_static(GROUP_ID,FLOW_ID)
{
 URL="statics.php?GROUP_ID="+GROUP_ID+"&FLOW_ID=" + FLOW_ID;
 myleft=(screen.availWidth-500)/2-100;
 window.open(URL,"read_vote","height=600,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
function show_graphic(GROUP_ID,FLOW_ID)
{
 URL="index1.php?GROUP_ID="+GROUP_ID+"&FLOW_ID=" + FLOW_ID;
 myleft=(screen.availWidth-500)/2-100;
 window.open(URL,"read_vote","height=600,width=800,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function show_reader1(GROUP_ID)
{
 URL="show_reader.php?GROUP_ID="+GROUP_ID;
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

function show_man(FLOW_ID,FLAG)
{
 URL="show_man.php?FLOW_ID="+FLOW_ID+"&type="+FLAG;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>
<?
$HTML_PAGE_TITLE = _("�������ݲ�ѯ");
include_once("inc/header.inc.php");
?>





<body class="bodycolor">

<?
//-----------�ϳ�SQL���-----------
 $CUR_DATE=date("Y-m-d",time());
if($FLOWTITLE!="")
{
     if($WHERE_STR=="")
         $WHERE_STR.=" where FLOW_TITLE like '%".$FLOWTITLE."%'";
     else
         $WHERE_STR.=" and FLOW_TITLE like '%".$FLOWTITLE."%'";
}
if($SECRET_TO_ID!="")
{
	 if($WHERE_STR=="")
   {
     	$TEMP_ARRAY=explode(",",$SECRET_TO_ID);
      $ARRAY_COUNT=sizeof($TEMP_ARRAY);
	    if($TEMP_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
      $WHERE_STR.=" where (find_in_set('$TEMP_ARRAY[0]', RANKMAN)";
      for($I=1;$I<$ARRAY_COUNT;$I++)
        	$WHERE_STR.=" or find_in_set('$TEMP_ARRAY[$I]', RANKMAN)";
      $WHERE_STR.=")";

		}
	  else
	 	{
	 	  $TEMP_ARRAY=explode(",",$SECRET_TO_ID);
      $ARRAY_COUNT=sizeof($TEMP_ARRAY);
	    if($TEMP_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
      $WHERE_STR.="  and (find_in_set('$TEMP_ARRAY[0]', RANKMAN)";
      for($I=1;$I<$ARRAY_COUNT;$I++)
          $WHERE_STR.=" or find_in_set('$TEMP_ARRAY[$I]', RANKMAN)";

       $WHERE_STR.=")";
	 	}
}

if($PARTICIPANT_TO_ID!="")
{
	 if($WHERE_STR=="")
     {
     	$TEMP_ARRAY=explode(",",$PARTICIPANT_TO_ID);
      $ARRAY_COUNT=sizeof($TEMP_ARRAY);
	    if($TEMP_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
      $WHERE_STR.=" where (find_in_set('$TEMP_ARRAY[0]', PARTICIPANT)";
      for($I=1;$I<$ARRAY_COUNT;$I++)
          $WHERE_STR.=" or find_in_set('$TEMP_ARRAY[$I]', PARTICIPANT)";
        
        $WHERE_STR.=")";

		  }
	 else
	 	{
	 	  $TEMP_ARRAY=explode(",",$PARTICIPANT_TO_ID);
      $ARRAY_COUNT=sizeof($TEMP_ARRAY);
	    if($TEMP_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
      $WHERE_STR.="  and (find_in_set('$TEMP_ARRAY[0]', PARTICIPANT)";
      for($I=1;$I<$ARRAY_COUNT;$I++)
        {  $WHERE_STR.=" or find_in_set('$TEMP_ARRAY[$I]', PARTICIPANT)";
        }
       $WHERE_STR.=")";

	 	}
}

if($GROUP!="")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where GROUP_ID='$GROUP'";
	 else
		 $WHERE_STR.=" and GROUP_ID='$GROUP'";
}

if($BEGIN_FROM_DATE!="")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where BEGIN_DATE>='$BEGIN_FROM_DATE'";
	 else
		 $WHERE_STR.=" and BEGIN_DATE>='$BEGIN_FROM_DATE'";
}
 
if($BEGIN_TO_DATE!="")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where BEGIN_DATE<='$BEGIN_TO_DATE'";
	 else
		 $WHERE_STR.=" and BEGIN_DATE<='$BEGIN_TO_DATE'";
}

if($END_FROM_DATE!="")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where END_DATE>='$END_FROM_DATE'";
	 else
		 $WHERE_STR.=" and END_DATE>='$END_FROM_DATE'";
}

if($END_TO_DATE!="")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where END_DATE<='$END_TO_DATE'";
	 else
		 $WHERE_STR.=" and END_DATE<='$END_TO_DATE'";
}

if($c1=="2")
{
	 if($WHERE_STR=="")
		 $WHERE_STR.=" where END_DATE<='$CUR_DATE' and  END_DATE<>'0000-00-00'";
	 else
		 $WHERE_STR.=" and END_DATE<='$CUR_DATE' and  END_DATE<>'0000-00-00'";
}

if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
 		if($WHERE_STR=="")
 				$WHERE_STR.=" where CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID='' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER_ID)";
 		else
 				$WHERE_STR.=" and (CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID='' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER_ID))";	
}
$query="SELECT count(*) from SCORE_FLOW".$WHERE_STR;
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $VOTE_COUNT=$ROW[0];

if($VOTE_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3">&nbsp;&nbsp;<?=_("���������ѯ���")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("�����������Ŀ�������"));
   Button_Back();
   exit;
}

 $PER_PAGE=5;
 $PAGES=10;
 $PAGE_COUNT=ceil($VOTE_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3">&nbsp;&nbsp;<?=_("���������ѯ���")?></span><br>
    </td>
<?
    $MSG_COUNT = sprintf(_("��%s��"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");
?>
    <td align="right" valign="bottom" class="small1"><?=$MSG_COUNT?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="list.php?CUR_PAGE=1&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="list.php?CUR_PAGE=<?=$PAGE_COUNT?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
   $PAGE_UP = sprintf(_("��%dҳ"),$PAGES);
?>
       <a class="A1" href="list.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=$PAGE_UP?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="list.php?CUR_PAGE=<?=$I?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=$I?></a>]&nbsp;
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
       <a class="A1" href="list.php?CUR_PAGE=<?=$I?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=$PAGE_DOWN?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="list.php?CUR_PAGE=<?=$CUR_PAGE-1?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=_("��һҳ")?></a>&nbsp;
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
       <a class="A1" href="list.php?CUR_PAGE=<?=$CUR_PAGE+1?>&FLOWTITLE=<?=$FLOWTITLE?>&SECRET_TO_ID=<?=$SECRET_TO_ID?>&TO_ID=<?=$TO_ID?>&GROUP=<?=$GROUP?>&BEGIN_FROM_DATE=<?=$BEGIN_FROM_DATE?>&BEGIN_TO_DATE=<?=$BEGIN_TO_DATE?>&END_FROM_DATE=<?=$END_FROM_DATE?>&END_TO_DATE=<?=$END_TO_DATE?>&c1=<?=$c1?>"><?=_("��һҳ")?></a>&nbsp;
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
<table width="95%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("������������")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("����ָ�꼯")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��Ч����")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("��ֹ����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>

<?
 //============================ ��ʾ�ѷ����Ŀ������� =======================================
 $query="SELECT * from SCORE_FLOW".$WHERE_STR." order by FLOW_ID desc";
 $cursor= exequery(TD::conn(),$query);
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
    $BEGIN_DATE1=$ROW["BEGIN_DATE"];
    $END_DATE1=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $PARTICIPANT =$ROW["PARTICIPANT"];
    $GROUP_ID=$ROW["GROUP_ID"];
    $ANONYMITY=$ROW["ANONYMITY"];

    if($ANONYMITY=="0")
       $ANONYMITY_DESC=_("������");
    else
       $ANONYMITY_DESC=_("����");
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
    if($VOTE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$FLOW_TITLE?></td>
     <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','1');" title="<?=_("����鿴���еĿ�����Ա")?>"><?=$RAN_NAME?></a></td>
      <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','0');" title="<?=_("����鿴���еı�������Ա")?>"><?=$PARTI_NAME?></a></td>
      <td align="center"><a href="javascript:show_reader1('<?=$GROUP_ID?>');" title="<?=_("����鿴������Ŀ")?>"><?=$GROUP_NAME?></a></td>
      <td align="center"><?=$ANONYMITY_DESC?></td>
      <td nowrap align="center"><?=$BEGIN_DATE1?></td>
      <td nowrap align="center"><?=$END_DATE1?></td>


      <td nowrap align="center">
      <a href="javascript:show_reader('<?=$GROUP_ID?>','<?=$FLOW_ID?>','<?=$ANONYMITY?>');"><?=_("����")?></a>
      <a href="javascript:show_static('<?=$GROUP_ID?>','<?=$FLOW_ID?>');"><?=_("��ֵͳ��")?></a>
      <a href="javascript:show_graphic('<?=$GROUP_ID?>','<?=$FLOW_ID?>');"><?=_("ͼ�λ�����")?></a>
      </td>
       <td nowrap align="center">
      <a href="excel_report.php?FLOW_ID=<?=$FLOW_ID?>&GROUP_ID=<?=$GROUP_ID?>&FLOW_TITLE=<?=$FLOW_TITLE?>" target="_blank"><?=_("�ܷ�")?></a>
      <a href="excel_detail.php?FLOW_ID=<?=$FLOW_ID?>&GROUP_ID=<?=$GROUP_ID?>&FLOW_TITLE=<?=$FLOW_TITLE?>" target="_blank"><?=_("������ϸ")?></a>
      </td>
    </tr>
<?
 }
?>

<tfoot class="TableFooter">
<td colspan="10" align="center">
    <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</td>
</tfoot>

</table>
</body>

</html>
