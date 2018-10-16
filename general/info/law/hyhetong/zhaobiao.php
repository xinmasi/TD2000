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
      <div align="center"><?=_("招标投标")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zhaobiao/zhaobiaoht01.php"><?=_("国际采购招标合同")?></a></li>
        <li><a href="zhaobiao/zhaobiaoht02.php"><?=_("土木建筑工程投标书")?></a></li>
        <li><a href="zhaobiao/zhaobiaoht03.php"><?=_("国际土木工程招投标")?></a></li>
        <li><a href="zhaobiao/zhaobiaoht04.php"><?=_("国际工程招标说明书格式")?></a></li>
        <li><a href="zhaobiao/zhaobiaoht05.php"><?=_("建筑安装工程投标书（标函）")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>