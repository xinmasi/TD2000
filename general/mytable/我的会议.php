<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="87";
$MODULE_DESC=_("�ҵĻ���");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'meeting';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("ȫ��").'" class="all_more" onclick="view_more(\'meeting\',\''._("�ҵĻ���").'\',\'/general/meeting/query/\');">'._("ȫ��").'&nbsp;</a>';
	
$MODULE_TYPE .='<a href="javascript:get_meeting(\'0\');">'._("���ܻ���").'</a>  '; 
$MODULE_TYPE .='<a href="javascript:get_meeting(\'1\');">'._("δ���������").'</a>'; 
  
$CUR_DATE=date("Y-m-d",time());
$WEEK_BEGIN=date("Y-m-d",(strtotime($CUR_DATE)-(date("w",strtotime($CUR_DATE)))*24*3600))." 00:00:00";
$WEEK_END=date("Y-m-d",(strtotime($CUR_DATE)+(6-date("w",strtotime($CUR_DATE)))*24*3600))." 23:59:59"; 
$DATE_STR="((M_START<='$WEEK_END' and M_START>='$WEEK_BEGIN') or (M_END<='$WEEK_END' and M_END>='$WEEK_BEGIN') or (M_START<='$WEEK_BEGIN' and M_END>='$WEEK_END') or (M_START<='$WEEK_BEGIN' and M_END is null))";

$MODULE_BODY.= "<ul>";

$COUNT=0;
$query = "SELECT * from MEETING where (M_STATUS='1' or M_STATUS='2') and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) and ".$DATE_STR." order by M_START limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;

   $M_ID=$ROW["M_ID"];
   $M_NAME=$ROW["M_NAME"];
   $M_START=$ROW["M_START"];
   $M_END=$ROW["M_END"];
   
   $TEM_M_START=substr($M_START,5,11);
   $TEM_M_END=substr($M_END,5,11);

   $M_NAME_TITLE="";
   if(strlen($M_NAME) > 40)
   {
      $M_NAME_TITLE=td_htmlspecialchars($M_NAME);
      $M_NAME=csubstr($M_NAME, 0, 40)."...";
   }
   $M_NAME=td_htmlspecialchars($M_NAME);
   
   $MODULE_BODY.='<li><a href="javascript:open_meeting('.$M_ID.');" title="'.$M_NAME_TITLE.'">'.$M_NAME.'</a>&nbsp;('.$TEM_M_START.' - '.$TEM_M_END.')</li>';
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("�������޻���")."</li>";

$MODULE_BODY.= "</ul>";

$MODULE_BODY.='<script>
function open_meeting(M_ID)
{
   URL="/general/meeting/query/meeting_detail.php?M_ID="+M_ID;
   myleft=(screen.availWidth-600)/2;
   mytop=100
   mywidth=600;
   myheight=500;
   window.open(URL,"read_meeting","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function get_meeting(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("�����У����Ժ򡭡�").'\';
      _get("meeting.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE_ID="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("��ȡ���ݴ��󣬴��룺").'"+req.status);
   }
}
</script>';
}
?>