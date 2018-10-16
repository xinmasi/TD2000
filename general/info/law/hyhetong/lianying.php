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
      <div align="center"><?=_("联营合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="lianying/lianyinht01.php"><?=_("法人型联营协议书")?></a></li>
        <li><a href="lianying/lianyinht02.php"><?=_("合伙合同")?></a></li>
        <li><a href="lianying/lianyinht03.php"><?=_("合伙协议")?></a></li>
        <li><a href="lianying/lianyinht04.php"><?=_("合伙型联营合同")?></a></li>
        <li><a href="lianying/lianyinht05.php"><?=_("料瓶联合经营合同")?></a></li>
        <li><a href="lianying/lianyinht06.php"><?=_("协作型联营协议书")?></a></li>
        <li><a href="lianying/lianyinht07.php"><?=_("合伙型联营合同书")?></a></li>
        <li><a href="lianying/lianyinht08.php"><?=_("联营合同")?></a></li>
        <li><a href="lianying/lianyinht09.php"><?=_("联营合同（松散型）")?></a></li>
        <li><a href="lianying/lianyinht10.php"><?=_("联营合同（紧密型）")?></a></li>
        <li><a href="lianying/lianyinht11.php"><?=_("联营合同（半紧密型）")?></a></li>
        <li><a href="lianying/lianyinht12.php"><?=_("房地产开发合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>