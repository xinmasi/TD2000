<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("广播电台");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_BODY.='
<SCRIPT>
function my_radio(MY_URL,DESC_STR)
{
  mytop=screen.availHeight-160;
  myleft=screen.availWidth-420;;
  URL="my_radio.php?MY_URL="+MY_URL+"&DESC_STR="+DESC_STR;
  window.open(URL,"my_radio","height=120,width=400,right=0,top="+mytop+",left="+myleft+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
}
</SCRIPT>
<table width="99%" class=small border="0" cellspacing="1" cellpadding="1">
  <tr>
  	<td class=TableContent colspan=10 align=center>'._("中央人民广播电台").'</td>
  </tr>
  <tr>
   <td width="25%"><a href=javascript:my_radio("mms://211.89.225.101/live1","'._("中国之声").'")>'._("中国之声").'</a></td>
   <td width="25%"><a href=javascript:my_radio("mms://211.89.225.101/live2","'._("经济之声").'")>'._("经济之声").'</a></td>
   <td width="25%"><a href=javascript:my_radio("mms://211.89.225.101/live3","'._("音乐之声").'")>'._("音乐之声").'</a></td>
   <td width="25%"><a href=javascript:my_radio("mms://211.89.225.101/live4","'._("都市之声").'")>'._("都市之声").'</a></td>
  </tr>
  <tr>
   <td width="20%"><a href=javascript:my_radio("mms://211.89.225.101/live5","'._("中华之声").'")>'._("中华之声").'</a></td>
   <td width="20%"><a href=javascript:my_radio("mms://211.89.225.101/live6","'._("神州之声").'")>'._("神州之声").'</a></td>
   <td width="20%"><a href=javascript:my_radio("mms://211.89.225.101/live7_p","'._("华夏之声").'")>'._("华夏之声").'</a></td>
   <td width="20%"><a href=javascript:my_radio("mms://211.89.225.101/live9","'._("文艺之声").'")>'._("文艺之声").'</a></td>
  </tr>
  <tr>
  	<td class=TableContent colspan=10 align=center>'._("英语学习").'</td>
  </tr>
  <tr>
    <td colspan=10><A href=javascript:my_radio("mms://alive.netfm.com.cn/am774","'._("北京人民广播电台—英语广播").'")>'._("北京人民广播电台—英语广播").'</A>&nbsp;&nbsp;&nbsp;
    <A href=javascript:my_radio("mms://enmms.chinabroadcast.cn/fm91.5","'._("CRI-轻松调频").'")>'._("CRI-轻松调频").'</A></td>
 </tr>
</table>';
}
?>
