<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$MENU_LEFT=array();

$target="address_main";
$linkman_out="";
$linkman_out='<div style="overflow-y:auto;overflow-x:auto;width=100%;height=100%"
><table class="TableBlock trHover" width="100%" align="center">
   <tr class="TableData" align="center">
     <td nowrap onclick="parent.'.$target.'.location=\'address/?GROUP_ID=0\'" style="cursor:pointer;">默认</td>
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
   
   $linkman_out.='<tr class="TableData" align="center"><td nowrap onclick="parent.'.$target.'.location=\'address/?GROUP_ID='.$GROUP_ID.'\'" style="cursor:pointer;">'.$GROUP_NAME.'</td></tr>';
}
$GROUP_ID_STR=$GROUP_ID_STR."0,";

$linkman_out.='</table></div>';
$module_style="display:;";  
   
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("联系人分组"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => "", "img" => "", "module" => $linkman_out, "module_style" => $module_style);

$linkman_out="";
$linkman_out='<table class="TableBlock trHover" width="100%" align="center">';

include_once('inc/mb.php');

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
   $linkman_out.='<tr class="TableData" align="center"><td nowrap >'._("无记录").'</td></tr>';	
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
        
      $linkman_out.='<tr class="TableData" align="left"><td onclick="parent.'.$target.'.location=\'address/idx_search.php?ID_STR='.$ID_STR.'&TABLE_STR='.$TABLE_STR.'\'" style="cursor:pointer;">'.$TABLE_STR.'</td></tr>';  
   }
 }
 
$linkman_out.='</table>';   
$module_style="display:none;";  

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("索引(按姓氏)"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => "", "img" => "", "module" => $linkman_out, "module_style" => $module_style);

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("查找(关键字)"), "href" => "address/search.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("管理分组"), "href" => "group/", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");

include_once("inc/menu_left.php");
?>