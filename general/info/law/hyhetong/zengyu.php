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
      <div align="center"><?=_("赠予捐募")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zengyu/zengyuht00.php"><?=_("赠与合同（企业类附义务）")?></a></li>
        <li><a href="zengyu/zengyuht01.php"><?=_("赠与合同（公民类附义务)")?></a></li>
        <li><a href="zengyu/zengyuht02.php"><?=_("赠与合同（公民类）")?></a></li>
        <li><a href="zengyu/zengyuht03.php"><?=_("赠与合同（企业类)")?></a></li>
        <li><a href="zengyu/zengyuht04.php"><?=_("遗赠协议")?></a></li>
        <li><a href="zengyu/zengyuht05.php"><?=_("赠与合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>