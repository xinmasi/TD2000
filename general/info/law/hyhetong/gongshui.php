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
      <div align="center"><?=_("供水供电供热")?></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="gongshui/gongshuiht00.php"><?=_("补偿贸易供电合同")?></a></li>
        <li><a href="gongshui/gongshuiht01.php"><?=_("计划供用电经济责任合同书")?></a></li>
        <li><a href="gongshui/gongshuiht02.php"><?=_("供用电合同（一）")?></a></li>
        <li><a href="gongshui/gongshuiht03.php"><?=_("供用电合同（二）")?></a></li>
        <li><a href="gongshui/gongshuiht04.php"><?=_("供用电合同（三）")?></a></li>
        <li><a href="gongshui/gongshuiht05.php"><?=_("供用电合同（四）")?></a></li>
        <li><a href="gongshui/gongshuiht06.php"><?=_("供用水合同")?></a></li>
        <li><a href="gongshui/gongshuiht07.php"><?=_("供用热力合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>