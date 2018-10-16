<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("运输合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="yunshu/yunshu00.php"><?=_("汽车货物运单")?></a></li>
        <li><a href="yunshu/yunshu01.php"><?=_("汽车货物托运计划表")?></a></li>
        <li><a href="yunshu/yunshu02.php"><?=_("汽车运输货票")?></a></li>
        <li><a href="yunshu/yunshu03.php"><?=_("光船租赁合同")?></a></li>
        <li><a href="yunshu/yunshu04.php"><?=_("海上运输合同")?></a></li>
        <li><a href="yunshu/yunshu05.php"><?=_("包船运输合同")?></a></li>
        <li><a href="yunshu/yunshu06.php"><?=_("航次租船合同")?></a></li>
        <li><a href="yunshu/yunshu07.php"><?=_("水水货物联运合同")?></a></li>
        <li><a href="yunshu/yunshu08.php"><?=_("港务管理局海江河联运货物水运合同登记单")?></a></li>
        <li><a href="yunshu/yunshu09.php"><?=_("水陆联运货物运单")?></a></li>
        <li><a href="yunshu/yunshu10.php"><?=_("铁路货物运输合同")?></a></li>
        <li><a href="yunshu/yunshu11.php"><?=_("货物运单")?></a></li>
        <li><a href="yunshu/yunshu12.php"><?=_("陆上货物运输托运合同")?></a></li>
        <li><a href="yunshu/yunshu13.php"><?=_("货物运输合同")?></a></li>
        <li><a href="yunshu/yunshu14.php"><?=_("运输合同")?></a></li>
        <li><a href="yunshu/yunshu15.php"><?=_("定期租船合同（一）")?></a></li>
        <li><a href="yunshu/yunshu16.php"><?=_("定期租船合同（二）")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>