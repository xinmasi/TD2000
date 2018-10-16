<?
include_once("inc/auth.inc.php");
ob_end_clean();

$query = "SELECT CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='RSS_TYPE' order by CODE_ORDER";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $CODE_NAME=$ROW["CODE_NAME"];
   $CODE_EXT=unserialize($ROW["CODE_EXT"]);
	 if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
		  $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
		  
   //$RSS_TYPE[]=array($ROW["CODE_NO"], $CODE_NAME);
   $SUB_TYPE[]=array($ROW["CODE_NO"], $CODE_NAME);
   
 }
 
?>
<div class="menu">
 <a class="current" href="javascript:view_content('0');" id="content_link_0"><?=_("公共网址")?></a>
 <a class="nomal" href="javascript:view_content('1');" id="content_link_1"><?=_("个人网址")?></a>
</div>
<div class="subContent">

<table class="rssList" id="rss_content_0">
  <tr>
<?
reset($SUB_TYPE);
$I=0;
foreach($SUB_TYPE as $CODE)
{
   $I++;
   list($CODE_NO, $CODE_NAME)=$CODE;
?>
  <td class="rssCatagory" valign=top><h4><?=$CODE_NAME?></h4>
  <ul>
<?
   $query = "SELECT * from URL where URL_TYPE='1' and SUB_TYPE='$CODE_NO' and USER='' order by URL_NO";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
?>
   <li><a href="javascript:add_module('<?=$ROW["URL_ID"]?>','<?=base64_encode($ROW["URL"])?>','<?=$ROW["URL_DESC"]?>')"><?=$ROW["URL_DESC"]?></a></li>
<?
   }
?>
  </ul>
 </td>
<?
   if($I%3==0)
      echo "<tr></tr>";
}
?>
  </tr>
</table>

<table class="rssList" id="rss_content_1" style="display:none;">
  <tr>
<?
$I=0;
foreach($SUB_TYPE as $CODE)
{
   $I++;
   list($CODE_NO, $CODE_NAME)=$CODE;
?>
  <td class="rssCatagory"><h4><?=$CODE_NAME?></h4>
  <ul>
<?
   $query = "SELECT * from URL where URL_TYPE=1 and SUB_TYPE='$CODE_NO' and USER='".$_SESSION["LOGIN_USER_ID"]."'  order by URL_NO";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
?>
   <li><a href="javascript:add_module('<?=$ROW["URL_ID"]?>','<?=base64_encode($ROW["URL"])?>','<?=$ROW["URL_DESC"]?>')"><?=$ROW["URL_DESC"]?></a></li>
<?
   }
?>
  </ul>
  </td>
<?
   if($I%3==0)
      echo "<tr></tr>";
}
?>
  </tr>
</table>

</div>