<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-04-11 ���ӷ�������ѯ�ж�

if($IS_MAIN==1)
{
   $QUERY_MASTER=true;
}
else
{
   $QUERY_MASTER="";
}

$PAGE_SIZE=20;

$HTML_PAGE_TITLE = _("������");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script Language="JavaScript">

function set_page()
{
	var PAGE_START=(parseInt(document.getElementById("PAGE_NUM").value) - 1) * parseInt("<?=$PAGE_SIZE?>") + 1;
	location="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START="+PAGE_START;
}

function set_page2()
{
	var PAGE_START=(parseInt(document.getElementById("PAGE_NUM2").value) - 1) * parseInt("<?=$PAGE_SIZE?>") + 1;
	location="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START="+PAGE_START;
}

function check_all()
{
	
	if(!document.getElementsByName("title_select"))
	{
		return;
	}
	for (i=0;i<document.getElementsByName("title_select").length;i++)
	{
		if(document.getElementsByName("allbox")[0].checked)
		{
			document.getElementsByName("title_select")[i].checked=true;
		}
		else
		{
			document.getElementsByName("title_select")[i].checked=false;
		}
	}
	
	if(i==0)
	{
		if(document.getElementsByName("allbox")[0].checked)
		{
			document.getElementsByName("title_select")[i].checked=true;
		}
		else
		{
			document.getElementsByName("title_select")[i].checked=false;
		}
	}
}

function check_one(el)
{
	if(!el.checked)
	{
		document.getElementsByName("allbox")[0].checked=false;
	}
}

function get_checked()
{
	checked_str="";
	for(i=0;i<document.getElementsByName("title_select").length;i++)
	{
		el=document.getElementsByName("title_select")[i];
		if(el.checked)
		{  
			val=el.value;
			checked_str+=val + ",";
		}
	}
	
	if(i==0)
	{
		el=document.getElementsByName("title_select")[i];
		if(el.checked)
		{  
			val=el.value;
			checked_str+=val + ",";
		}
	}
	return checked_str;

}

function delete_title()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫɾ�����⣬������ѡ������һ�")?>");
		return;
	}
	
	msg='<?=_("ȷ��Ҫɾ����ѡ������")?>';
	if(window.confirm(msg))
	{
		URL="delete.php?DELETE_STR="+ delete_str +"&BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>";
		location=URL;
	}
}

function move_comment(BOARD_ID)
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫת�����⣬������ѡ������һ�")?>");
		return;
	}
	
	URL="move.php?BOARD_ID=<?=$BOARD_ID?>&DELETE_STR="+ delete_str;
	window.open(URL,"move","height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes");
}

function top_comment(BOARD_ID)
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫ�ö����⣬������ѡ������һ�")?>");
		return;
	}
	
	URL="set_top.php?BOARD_ID=<?=$BOARD_ID?>&DELETE_STR="+ delete_str;
	window.open(URL,"top","height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes");
}

function jing_comment(BOARD_ID)
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("Ҫ�������⣬������ѡ������һ�")?>");
		return;
	}
	
	URL="set_jing.php?BOARD_ID=<?=$BOARD_ID?>&DELETE_STR="+ delete_str;
	window.open(URL,"jing","height=200,width=250,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=450,top=300,resizable=yes");
}
function show_reply(BOARD_ID,COMMENT_ID)
{
	URL="show_reply.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID="+COMMENT_ID;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_comment","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function show_reader(COMMENT_ID)
{
	URL="show_reader.php?COMMENT_ID="+COMMENT_ID;
	myleft=(screen.availWidth-500)/2;
	window.open(URL,"read_comment","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>


<body class="bodycolor">
<?
//------- ��������Ϣ -------
$query = "SELECT DEPT_ID,PRIV_ID,USER_ID,BOARD_HOSTER,BOARD_NAME,WELCOME_TEXT,ANONYMITY_YN,NEED_CHECK from BBS_BOARD where BOARD_ID='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER))";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$DEPT_ID = $ROW["DEPT_ID"];
	$PRIV_ID = $ROW["PRIV_ID"];
	$USER_ID1 = $ROW["USER_ID"];
	$BOARD_HOSTER = $ROW["BOARD_HOSTER"];
	$BOARD_NAME = $ROW["BOARD_NAME"];
	$WELCOME_TEXT = $ROW["WELCOME_TEXT"];
	$ANONYMITY_YN = $ROW["ANONYMITY_YN"];
	$NEED_CHECK = $ROW["NEED_CHECK"];//�Ƿ���Ҫ��ˣ�0������Ҫ��
	$BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
	$BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
	//   $BOARD_NAME=stripslashes($BOARD_NAME);
	$WELCOME_TEXT=str_replace("<","&lt",$WELCOME_TEXT);
	$WELCOME_TEXT=str_replace(">","&gt",$WELCOME_TEXT);
	$WELCOME_TEXT=stripslashes($WELCOME_TEXT);
}
else
{
    //----------������Ȩ�޿���---------
    exit;
}

//----------������Ȩ�޿���---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//{
//	exit;
//}

//----------��ð�������-----------
$BOARD_HOSTER_NAME="";
$TOK=strtok($BOARD_HOSTER,",");
while($TOK!="")
{
	$query1 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
	$cursor1= exequery(TD::conn(),$query1);
	
	if($ROW=mysql_fetch_array($cursor1))
	{
		$BOARD_HOSTER_NAME.=$ROW["USER_NAME"].",";
	}
	
	$TOK=strtok(",");
}

$BOARD_HOSTER_NAME=substr($BOARD_HOSTER_NAME,0,-1);

//=============================== ����������Ϣ ===================================
if($TYPE!="")
{
	if($TYPE==_("�޷���"))
	{
		$WHERE_STR=" and (TYPE='$TYPE' or TYPE='') ";
	}
	else
	{
		$WHERE_STR=" and (TYPE='$TYPE' or BOARD_ID='-1') ";
	}
}

if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
{
	$WHERE_STR.= "and (SHOW_YN='0'||USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";
}

$query = "SELECT count(BOARD_ID) from BBS_COMMENT where (BOARD_ID='$BOARD_ID' or  BOARD_ID='-1') ".$WHERE_STR." and PARENT='0' and IS_CHECK!='0' and IS_CHECK!='2'";
$cursor= exequery(TD::conn(),$query);
$COMMENT_COUNT=0;

if($ROW=mysql_fetch_array($cursor))
{
	$COMMENT_COUNT=$ROW[0];
}

if($COMMENT_COUNT==0)//�޷���
{
?>
<div>
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="title_list">
				<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"><?=_("������")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a><br>
			</td>
		</tr>
	</table>
</div>

<div id="bbs_body">
<div class="operate_bar">
<table border="0" cellspacing="1" width="100%" class="small" cellpadding="3" style="font-size:13px;">
  <tr>

   <td align="right">
<?
if($ANONYMITY_YN!="2")
{
?>
     <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("����������")?>"><?=_("����")?></a>
<?
}
?>
     <a href="user_top.php" class="buttom_a" title="<?=_("�������а�")?>"><?=_("���ְ�")?></a>
     <a href="index.php" class="buttom_a" title="<?=_("����������Ŀ¼")?>"><?=_("����������")?></a>
<?

		//oa����Ա������
if(($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])) && $NEED_CHECK==1 )
{
?>
		   <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("�������")?>"><?=_("�������")?></a>
<?
}
?>
   </td>
  </tr>
</table>
</div>
<?
  Message("",_("����������������"));
  exit;
}

//============ �������£���ʼ�����ҳ =================

//--- ������ҳ�� ---
$PAGE_TOTAL=$COMMENT_COUNT/$PAGE_SIZE;
$PAGE_TOTAL=ceil($PAGE_TOTAL);

//--- ����,ĩҳ ---
if($COMMENT_COUNT<=$PAGE_SIZE)
{
   $LAST_PAGE_START=1;
}
else if($COMMENT_COUNT%$PAGE_SIZE==0)
{
   $LAST_PAGE_START=$COMMENT_COUNT-$PAGE_SIZE+1;
}
else
{
   $LAST_PAGE_START=$COMMENT_COUNT-$COMMENT_COUNT%$PAGE_SIZE+1;
}

//--- ���ܷ�ҳ ---
//-- ҳ�� --
if($PAGE_START=="")
{
   $PAGE_START=1;
}

if($PAGE_START>$COMMENT_COUNT)
{
   $PAGE_START=$LAST_PAGE_START;
}

if($PAGE_START<1)
{
   $PAGE_START=1;
}
//-- ҳβ --
$PAGE_END=$PAGE_START+$PAGE_SIZE-1;

if($PAGE_END>$COMMENT_COUNT)
{
   $PAGE_END=$COMMENT_COUNT;
}
//--- ���㵱ǰҳ ---
$PAGE_NUM=($PAGE_START-1)/$PAGE_SIZE+1;
?>
<div>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="font-size:14px;">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"><?=_("������")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a><br>
    </td>
  </tr>
</table>
</div>

<div id="bbs_body">
<div class="operate_bar">
<table border="0" width="100%" style="font-size:13px;">
  <tr>
	  <td nowrap align="left" width="70">
	  	<span class="page_span">
	  		<A class="p_total"><?=$COMMENT_COUNT?></A>
	  		<A class="p_total"><?=$PAGE_NUM?>/<?=$PAGE_TOTAL?></A>
	    <span>
	  </td>
	  <td width="398" align="left" nowrap>
      <a href="board.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("��ҳ")?></a>
      <a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("��һҳ")?></a>
      <a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=($PAGE_END+1)?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?> ><?=_("��һҳ")?></a>
      <a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$LAST_PAGE_START?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?>><?=_("ĩҳ")?></a>
      <span style="float: left;padding-top:3px;margin-top:3px;font-size:12px;"><?=_("ҳ��")?></span>
      <input type="text" id="PAGE_NUM" name="PAGE_NUM" value="<?=$PAGE_NUM?>" class="SmallInput" size="2" style="float: left;margin-top: 3px;">
      <a href="javascript:set_page();" title="<?=_("ת��ָ����ҳ��")?>" class="buttom_a"><?=_("ת��")?></a>
       </td>
    <td align="right" width="500" nowrap>
<?
if($ANONYMITY_YN!="2")
{
?>
		 <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("����������")?>"><?=_("����")?></a>
<?
}
?>
        <a href="query.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("��������")?>"><?=_("����")?></a>
        <a href="comment_top.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("��������")?>"><?=_("��������")?></a>
        <a href="user_top.php" class="buttom_a" title="<?=_("�������а�")?>"><?=_("���ְ�")?></a>
        <a href="board.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("���ر�������Ŀ¼")?>"><?=_("���ر�������")?></a>
        <a href="index.php" class="buttom_a" title="<?=_("����������Ŀ¼")?>"><?=_("�����������б�")?></a>
		<?
		//oa����Ա������
		if(($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])) && $NEED_CHECK==1 )
		{
		?>
		   <a href="check_manage.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" title="<?=_("�������")?>"><?=_("�������")?></a>
		<?
		}
		?>
    </td>
  </tr>
</table>
</div>

<div class="title_body">
<table class="fast_replay_table1" width="100%" style="font-size:14px;">
	<tr class="title_bar">
<?
//oa����Ա������
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?>
		<td nowrap align="center"><?=_("ѡ��")?></td>
<?
}
?>
		<td nowrap align="center"><?=_("����")?></td>
		<td nowrap align="center"><?=_("����")?></td>
		<td nowrap align="center"><?=_("����")?></td>
		<td nowrap align="center"><?=_("�ֽ�")?></td>
		<td nowrap align="center"><?=_("��")?>/<?=_("��")?></td>
		<td align="center"><?=_("���ظ�")?></td>
	</tr>

<?
$query = "SELECT TYPE,COMMENT_ID,SHOW_YN,BOARD_ID,SUBJECT,CONTENT,AUTHOR_NAME_TMEP,REPLY_CONT,READ_CONT,OLD_SUBMIT_TIME,SUBMIT_TIME,AUTHOR_NAME,READEDER,JING,TOP,USER_ID,if(TOP='',0,TOP) AS TOP from BBS_COMMENT where (BOARD_ID='$BOARD_ID' or  BOARD_ID='-1') ".$WHERE_STR." and PARENT='0' and IS_CHECK!='0' and IS_CHECK!='2' order by BOARD_ID asc,TOP desc,SUBMIT_TIME desc";
$cursor = exequery(TD::conn(), $query,$QUERY_MASTER);
$COMMENT_COUNT = 0;
while($ROW=mysql_fetch_array($cursor))
{
	$COMMENT_COUNT++;
	if($COMMENT_COUNT < $PAGE_START)
	{
		continue;
	}
	else if($COMMENT_COUNT > $PAGE_END)
	{
		break;
	}
	$TYPE = $ROW["TYPE"];
	$COMMENT_ID = $ROW["COMMENT_ID"];
	$SHOW_YN = $ROW["SHOW_YN"];
	$SAVEBOARD_ID = $ROW["BOARD_ID"];
	$USER_ID = $ROW["USER_ID"];
	$TOP = $ROW["TOP"];
	$JING = $ROW["JING"];
	$READEDER = $ROW["READEDER"];
	$AUTHOR_NAME = $ROW["AUTHOR_NAME"];
	$SUBJECT = $ROW["SUBJECT"];
	if(td_trim($SUBJECT)=="")
    {
        $SUBJECT = "�ޱ���";
    }
	$SUBMIT_TIME = $ROW["SUBMIT_TIME"];
	$OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
	
	if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
	{
		$OLD_SUBMIT_TIME=$SUBMIT_TIME;
	}
	
	$READ_CONT = $ROW["READ_CONT"];
	$REPLY_CONT = $ROW["REPLY_CONT"];
	$AUTHOR_NAME_TMEP = $ROW["AUTHOR_NAME_TMEP"];
	
	$CONTENT=$ROW["CONTENT"];
	$CONTENT_SIZE=strlen($CONTENT);
	$CONTENT_SIZE=number_format($CONTENT_SIZE,0, ".",",");
	
	$AUTHOR_NAME=str_replace("<","&lt",$AUTHOR_NAME);
	$AUTHOR_NAME=str_replace(">","&gt",$AUTHOR_NAME);
	//  $AUTHOR_NAME=stripslashes($AUTHOR_NAME);
	
	$SUBJECT=str_replace("<","&lt",$SUBJECT);
	$SUBJECT=str_replace(">","&gt",$SUBJECT);
	//  $SUBJECT=stripslashes($SUBJECT);
	
	$query1 = "SELECT AVATAR,USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
	$cursor1= exequery(TD::conn(),$query1);
	if($ROW1=mysql_fetch_array($cursor1))
	{
		$AVATAR=$ROW1["AVATAR"];
		$USER_NAME=$ROW1["USER_NAME"];
		$DEPT_NAME=td_trim(GetDeptNameById($ROW1["DEPT_ID"]));
	}
	else
	{
		$USER_NAME=$USER_ID;
		$AVATAR="";
		$DEPT_NAME="";
	}
	
	$query1 = "SELECT SUBMIT_TIME,AUTHOR_NAME from BBS_COMMENT where PARENT='$COMMENT_ID' AND IS_CHECK!='0' and IS_CHECK!='2' order by SUBMIT_TIME desc limit 1";
	$cursor1= exequery(TD::conn(),$query1);
	$AUTHOR_NAME1="";
	
	if($ROW1=mysql_fetch_array($cursor1))
	{
		$SUBMIT_TIME = $ROW1["SUBMIT_TIME"];
		$AUTHOR_NAME1=$ROW1["AUTHOR_NAME"];
	}
?>
  <tr class="table_row" onMouseOver="this.style.backgroundColor='#F5FBFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
<?
//oa����Ա������
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?>
    <td width="5" nowrap class="fast_replay_td1">
    	<input type="checkbox" name="title_select" value="<?=$COMMENT_ID?>" onClick="check_one(self);">
    </td>
<?
}

if($TOP=="1")
{
	$SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
}

if($TYPE=="")
{
    $TYPE=_("�޷���");
}
?>
		<td width="50%" class="fast_replay_td1">
		<?
		if($SAVEBOARD_ID=="-1")
		{
			?>
			[<a style="color:red"><?=_("����")?></a>]
			<?
		}
		else
		{
			if($TYPE!=_("�޷���"))
			{
			?>
				[<a href="board.php?BOARD_ID=<?=$BOARD_ID?>&TYPE=<?=$TYPE?>"><?=$TYPE?></a>]
			<?
			}
		}
		?>
			<a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>"><?=$SUBJECT?><? if($SHOW_YN=="1") echo "&nbsp;<font color=red>"._("������")."</font>";?></a>
		<?

if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
{
	echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>    ";
}

if($JING==1)
{
	echo "<img src='".MYOA_STATIC_SERVER."/static/images/jing.gif' height=11 width=15>";
}
?>

      </td>
    <td width="90" nowrap class="fast_replay_td1">
<?
if($USER_NAME==$AUTHOR_NAME && $AUTHOR_NAME_TMEP!=2)
{
?>
    <img src="<?=avatar_path($AVATAR)?>" width="16" height="16" align="absmiddle">
<?
}

if($ANONYMITY_YN==0  && $USER_NAME!=$AUTHOR_NAME)
{
	 $NEWER=$USER_NAME;
?>
    <?=$USER_NAME ?></td>
<?
}
else
{
	 $NEWER=$AUTHOR_NAME;
?>
    <?=$AUTHOR_NAME ?></td>
<?
}

if($REPLY_CONT<=0)
   $REPLY_STR=$OLD_SUBMIT_TIME."<br> by ".$NEWER;
else
   $REPLY_STR=$SUBMIT_TIME."<br> by ".$AUTHOR_NAME1;
$READEDERT=td_trim(GetUserNameById($READEDER),",");
?>
	 <td align="center" class="fast_replay_td1"><?if ($ANONYMITY_YN==1&&($AUTHOR_NAME_TMEP==2 || $AUTHOR_NAME!=$USER_NAME))echo "-";else echo $DEPT_NAME;?>
      <td align="center" class="fast_replay_td1"><?=$CONTENT_SIZE?>
      <td align="center" class="fast_replay_td1"><a href="javascript:show_reply('<?=$BOARD_ID?>','<?=$COMMENT_ID?>');" title="<?=_("���ӻظ���")?>"><?=$REPLY_CONT?></a>/<a href="javascript:show_reader('<?=$COMMENT_ID?>');" title="<?=_("������Ա��")?><?=$READEDERT?>"><?=$READ_CONT?></a></td>
      <td align="center" class="fast_replay_td1"><?=$REPLY_STR?></td>
  </tr>
<?
}//while

if($COMMENT_COUNT>0)
{
?>
</table>
</div>

<div class="operate_footer operate_bar">
<table border="0" width="100%">
	<tr>
		<td>
<?
//oa����Ա������
if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
{
?>
		<input type=checkbox  id="allbox_for" onClick="check_all();" name="allbox" style="float: left;margin-top: 3px;"><label for="allbox_for" class="page_span" style="float: left;padding-top:3px;margin-top: 3px;"><?=_("ȫѡ")?></label>
		<a href="javascript:delete_title();" class="buttom_a"><?=_("ɾ������")?></a>
		<a href="javascript:move_comment(<?=$BOARD_ID?>);" class="buttom_a"><?=_("ת������")?></a>
		<a href="javascript:top_comment(<?=$BOARD_ID?>);" class="buttom_a"><?=_("�ö�")?>/<?=_("ȡ���ö�")?></a>
		<a href="javascript:jing_comment(<?=$BOARD_ID?>);" class="buttom_a"><?=_("�Ӿ�")?>/<?=_("ȡ���Ӿ�")?></a>
<?
}
?>
		</td>
	<td align="right" width="300">
		<a href="board.php?BOARD_ID=<?=$BOARD_ID?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("��ҳ")?></a>
		<a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=($PAGE_START-$PAGE_SIZE)?>" class="buttom_a" <?if($PAGE_START==1)echo "disabled";?> ><?=_("��һҳ")?></a>
		<a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=($PAGE_END+1)?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?> ><?=_("��һҳ")?></a>
		<a href="board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$LAST_PAGE_START?>" class="buttom_a" <?if($PAGE_END>=$COMMENT_COUNT)echo "disabled";?>><?=_("ĩҳ")?></a>
		<span style="float: left;padding-top:3px;margin-top:3px;font-size:12px;"><?=_("ҳ��")?></span>
		<input type="text" id="PAGE_NUM2" name="PAGE_NUM2" value="<?=$PAGE_NUM?>" class="SmallInput" size="2" style="float: left;margin-top: 3px;">
		<a href="javascript:set_page2();" title="<?=_("ת��ָ����ҳ��")?>" class="buttom_a"><?=_("ת��")?></a>
	</td>
  </tr>
</table>
</div>
</div>
<?
}
?>
</div>
</body>
</html>