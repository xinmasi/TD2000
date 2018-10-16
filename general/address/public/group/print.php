<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("通讯簿打印");
include_once("inc/header.inc.php");
?>



<STYLE type=text/css>.HdrPaddingTop {
	PADDING-TOP: 5px
}
.HdrPaddingBttm {
	PADDING-BOTTOM: 5px
}
BODY {
	MARGIN-TOP: 2px; MARGIN-LEFT: 0px; COLOR: #000000; MARGIN-RIGHT: 0px
}
A {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
BODY {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TABLE {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TD {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TR {
	FONT-SIZE: 9pt; FONT-FAMILY: <?=_("宋体")?>,MS SONG,SimSun,tahoma,sans-serif
}
TABLE {
	BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px
}
.FF {
	COLOR: #000000
}
</STYLE>
<?
$GROUP_ID=intval($GROUP_ID);
$query = "SELECT * from ADDRESS where USER_ID='' and GROUP_ID='$GROUP_ID' order by PSN_NAME";
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
?>
<body>
<br>
<TABLE cellSpacing=0 cellPadding=0 width=640 align=center border=0>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $PSN_NAME=$ROW["PSN_NAME"];
   $ADD_DEPT=$ROW["ADD_DEPT"];
   $POST_NO_DEPT=$ROW["POST_NO_DEPT"];
   $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
   $TEL_NO_HOME=$ROW["TEL_NO_HOME"];
   $MOBIL_NO=$ROW["MOBIL_NO"];
   $FAX_NO_DEPT=$ROW["FAX_NO_DEPT"];
   $EMAIL=$ROW["EMAIL"];
   if($COUNT%2==1)
   {
?>
  <TR>
<?
   }
?>
    <TD class=HdrPaddingBttm width="49%">
      <TABLE cellSpacing=0 cellPadding=0 width=100% align=center border=0>
        <tr><td height="20" valign="BOTTOM" style="font-size:15;font-weight:bold;background:#CDCDCD;">&nbsp;<?=$PSN_NAME?></td></tr>
        <tr><td height="3"></td></tr>
        <tr><td><?=$ADD_DEPT?></td></tr>
        <tr><td><?=$POST_NO_DEPT?></td></tr>
        <tr><td><?=_("商务电话：")?>&nbsp;&nbsp;<?=$TEL_NO_DEPT?></td></tr>
        <tr><td><?=_("住宅电话：")?>&nbsp;&nbsp;<?=$TEL_NO_HOME?></td></tr>
        <tr><td><?=_("移动电话：")?>&nbsp;&nbsp;<?=$MOBIL_NO?></td></tr>
        <tr><td><?=_("商务传真：")?>&nbsp;&nbsp;<?=$FAX_NO_DEPT?></td></tr>
        <tr><td><?=_("电子邮件：")?>&nbsp;&nbsp;<?=$EMAIL?></td></tr>
      </TABLE>
    </TD>
    <TD class=HdrPaddingBttm width="2%"></TD>
<?
   if($COUNT%2==0)
   {
?>
  </TR>
  <TR>
    <TD class=HdrPaddingBttm colspan="3" height="10"></TD>
  </TR>
<?
   }
}
if($COUNT%2==1)
{
?>
    <TD class=HdrPaddingBttm width="49%">&nbsp;</TD>
  </TR>
<?
}
?>
</TABLE>
</body>

</html>
