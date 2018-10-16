<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("地址簿");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/layout_left.css">
<?
if(MYOA_IS_UN && find_id("zh-TW,en,", MYOA_LANG_COOKIE) && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/un_<?=MYOA_LANG_COOKIE?>.css" />
<?
}
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script language="JavaScript" src="<?=MYOA_JS_SERVER?>/static/js/hover_tr.js"></script>

<script>
function goto(url)
{
   document.getElementById('address_main').src = url;
}

jQuery.noConflict();
(function($){
   $(window).resize(function(){
      $('#address_main').height(Math.max($(window).height(), $(address_main.document.body).attr('scrollHeight')+10));
   });
   
   $(document).ready(function(){
      $('a[index]').click(function(){
         $(this).toggleClass('header-active');
         $('#container_'+$(this).attr('index')).toggle();
      });
   });
})(jQuery);
</script>
<body class="bodycolor">
<?
$linkman_out="";
$linkman_out='<table class="TableBlock trHover" width="100%" align="center">
   <tr class="TableData" align="center">
     <td nowrap onclick="goto(\'address/?GROUP_ID=0\')" style="cursor:pointer;">默认</td>
   </tr>';

$query = "select * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
$cursor= exequery(TD::conn(),$query);
$GROUP_COUNT=1;
while($ROW=mysql_fetch_array($cursor))
{
	 $PRIV_DEPT=$ROW["PRIV_DEPT"];
	 $PRIV_ROLE=$ROW["PRIV_ROLE"];
	 $PRIV_USER=$ROW["PRIV_USER"];

	 if($PRIV_DEPT!="ALL_DEPT")
	 {
	    if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) && !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" && !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
	    {
	       continue;
	    }
   }
	 $GROUP_COUNT++;

   $GROUP_ID=$ROW["GROUP_ID"];
   $GROUP_NAME=$ROW["GROUP_NAME"];

   $GROUP_ID_STR.=$GROUP_ID.",";
   
   $linkman_out.='<tr class="TableData" align="center"><td  onclick="goto(\'address/?GROUP_ID='.$GROUP_ID.'\')" style="cursor:pointer;">'.$GROUP_NAME.'</td></tr>';
}
$GROUP_ID_STR=$GROUP_ID_STR."0,";

$linkman_out.='</table>';

$lastname_out="";
$lastname_out='<table class="TableBlock trHover" width="100%" align="center">';

include_once('inc/name.php');

for($i=ord('A'); $i<=ord('Z'); ++$i) {
	$name[chr($i)]=array();
	$nidx[chr($i)]=array();
}
$name['other']=array();
$nidx['other']=array();

$sql="select * from ADDRESS where USER_ID='' order by PSN_NAME";
$result=exequery(TD::conn(), $sql);
while($row=mysql_fetch_array($result))
{
	$GROUP_ID=$row["GROUP_ID"];
	if(!find_id($GROUP_ID_STR,$GROUP_ID))
	   continue;

	$s=$row['PSN_NAME'];
  $idx='other';
	if(ord($s[0])>=128)
	{
		$FirstName=substr($s, 0, MYOA_MB_CHAR_LEN);
		foreach($mb as $key => $s)
		{
			if(strpos($s, $FirstName))
			{
				//$row['PSN_NAME'].=strpos($s, $FirstName);
				$idx=strtoupper($key);
				break;
			}
		}
	}
	else
	{
		$FirstName=strtoupper($s[0]);
		if($FirstName>='A' && $FirstName<='Z')
			 $idx=$FirstName;
		else
		   $idx='other';
	}
	if(!in_array($FirstName, $nidx[$idx]))
		 array_push($nidx[$idx], $FirstName);
	array_push($name[$idx], $row);
}

$INDEX=0;
if(mysql_num_rows($result)<1)
   $lastname_out.='<tr class="TableData" align="center"><td nowrap >'._("无记录").'</td></tr>';	
else
foreach($name as $key => $r)
{
	if(count($name[$key])>0)
	{
	   $INDEX++;
	   if($key=='other')
	      $TABLE_STR="<b>"._("其它")."</b>(".count($name[$key]).") - ".implode(', ', $nidx[$key]);
	   else
	      $TABLE_STR="<b>".$key."</b>(".count($name[$key]).") - ".implode(', ', $nidx[$key]);

       $ID_STR="";
       foreach($r as $ROW)
       {
          $ADD_ID=$ROW["ADD_ID"];
          $ID_STR.=$ADD_ID.",";
       }
        
      $lastname_out.='<tr class="TableData" align="left"><td onclick="goto(\'address/idx_search.php?ID_STR='.$ID_STR.'&TABLE_STR='.$TABLE_STR.'\')" style="cursor:pointer;">'.$TABLE_STR.'</td></tr>';  
   }
 }
 
$lastname_out.='</table>';
?>
<table width="100%">
   <tr>
      <td id="left">
         <div id="left_top" class="PageHeader address_icon"></div>
         <table class="BlockTop">
            <tr>
               <td class="left"></td>
               <td class="center">
                  <a href="javascript:;" index="1" class="header header-active"><?=_("联系人分组")?></a>
               </td>
               <td class="right"></td>
            </tr>
         </table>
         <div class="container" id="container_1" style="OVERFLOW-Y:auto;scrollbar-face-color: #FFFFFF; scrollbar-shadow-color: #D2E5F4; scrollbar-highlight-color: #D2E5F4; scrollbar-3dlight-color: #FFFFFF; scrollbar-darkshadow-color: #FFFFFF; scrollbar-track-color: #FFFFFF; scrollbar-arrow-color: #D2E5F4">
            <?=$linkman_out?>
         </div>
         <div class="head"><a href="javascript:;" index="2" class="header"><?=_("按姓氏分组")?></a></div>
         <div class="container" id="container_2" style="display:none;">
            <?=$lastname_out?>
         </div>
         <div class="head"><a href="address/search.php"" class="header" target="address_main"><?=_("查找（关键字）")?></a></div>
         <table class="BlockBottom">
            <tr>
               <td class="left"></td>
               <td class="center">
                  <a href="group" class="header" target="address_main"><?=_("管理分组")?></a>
               </td>
               <td class="right"></td>
            </tr>
         </table>
      </td>
      <td id="right">
         <iframe id="address_main" name="address_main" src="address/?GROUP_ID=0" onload="jQuery(window).triggerHandler('resize');" border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" style="width:100%;height:100%;"></iframe>
      </td>
   </tr>
</table>
</body>
</html>
