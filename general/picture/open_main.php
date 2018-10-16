<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Í¼Æ¬¹ÜÀí");
include_once("inc/header.inc.php");
?>


<script>
function bbimg(o)
{
  var zoom=parseInt(o.style.zoom, 10)||100;
  zoom+=event.wheelDelta/12;

  if(zoom>0)
     o.style.zoom=zoom+'%';
  return false;
}
</script>

<body topmargin=3 style="background-color:gray" leftmargin=0>
<table id="pictable" border="0" width=100% height=100% title="<?=_("Êó±ê¹öÂÖËõ·Å£¬µã»÷Í¼Æ¬·­Ò³")?>" topmargin=3 cellpadding=0 cellspacing=0 onmousewheel="return bbimg(this)" onClick="parent.open_control.open_pic(1);">
<tr>
	<td align="center" valign="center" class=big height=20>
		<font color="white"><b><div id="file_name"></div></b></font>
  </td>
</tr>
<tr>
	<td align="center" valign="center">
    <div id="div_image"><font color=white><?=_("ÕýÔÚ¼ÓÔØÍ¼Æ¬")?>...</font></div>
  </td>
</tr>
</table>
</body>
</html>