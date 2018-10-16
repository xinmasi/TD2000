<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$sql1="select USER_NAME from user where USER_ID='$USER_ID'";
$re1=exequery(TD::conn(),$sql1);
if ($ROW2=mysql_fetch_array($re1)){
	$user_name=$ROW2['USER_NAME'];
}


$HTML_PAGE_TITLE = $user_name;
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor" >
<div style="margin-top:50px;">
<?
$sql="select count(*) from WORK_PERSON where PLAN_ID='$PLAN_ID' and PUSER_ID='$USER_ID'";
$re=exequery(TD::conn(),$sql);
if ($ROW1=mysql_fetch_array($re)){
	$num=$ROW1[0];
}

if ($num==0){
	Message("",$user_name._("无计划任务"));

	?>
	<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">	</center>
<?php }
else{

$query = "SELECT AUTO_PERSON,PBEGEI_DATE,PEND_DATE,PPLAN_CONTENT,PUSE_RESOURCE,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_PERSON where PLAN_ID='$PLAN_ID' and PUSER_ID='$USER_ID'";

$cursor= exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $COUNT++;

  $AUTO_PERSON=$ROW["AUTO_PERSON"];
  $PBEGEI_DATE=$ROW["PBEGEI_DATE"];
  $PEND_DATE=$ROW["PEND_DATE"];
  $ATTACHMENT_ID2=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME2=$ROW["ATTACHMENT_NAME"]; 	 	   
  
  $PPLAN_CONTENT=str_replace("\n","<br>",$ROW["PPLAN_CONTENT"]);
  $PUSE_RESOURCE=str_replace("\n","<br>",$ROW["PUSE_RESOURCE"]);

  if($PEND_DATE=="0000-00-00")
     $PEND_DATE="";
 if($COUNT==1)
	{
?>
<p class="big3"> <?=$user_name?><?=_("计划任务")?></p>
<table  class="TableList" border="0" cellspacing="0" width="100%" class="small" >
   <tr class="TableHeader">
     <td nowrap align="center" width="15%" style="border:1px solid #B1CCF2;"><?=_("开始时间")?></td>
     <td nowrap align="center" width="15%" style="border:1px solid #B1CCF2;"><?=_("结束时间")?></td>
     <td align="center" width="30%" style="border:1px solid #B1CCF2;"><?=_("计划任务")?></td>
     <td nowrap align="center" style="border:1px solid #B1CCF2;"><?=_("附件")?></td>     
     <td align="center" width="30%" style="border:1px solid #B1CCF2;"><?=_("相关资源")?></td>
   </tr>
<?
  }

  if($COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";

?>    
    <tr class="<?=$TableLine?>">
     <td nowrap align="center" width="100"><?=$PBEGEI_DATE?></td>
  	 <td nowrap align="center" width="100"><?=$PEND_DATE?></td>
     <td align="left"><?=$PPLAN_CONTENT?></td>
     <td align="left"><?=attach_link($ATTACHMENT_ID2,$ATTACHMENT_NAME2,0,1,1)?></td>
     <td align="left"><?=$PUSE_RESOURCE?></td>
</tr> 
<?php }

?>
<? if($COUNT!=0)
{
?>
</table>
<?
}
?>
<br/>
  <center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">	</center>
  
<?}?>
</div>
</body>
</html>
