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
      <div align="center"><?=_("土地合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="tudi/tudiht00.php"><?=_("外商投资企业土地使用合同（划拨土地使用权合同）")?></a></li>
        <li><a href="tudi/tudiht01.php"><?=_("国有土地使用权出让合同（成片开发土地出让合同）")?></a></li>
        <li><a href="tudi/tudiht02.php"><?=_("国有土地使用权出让合同（划拨土地使用权补办出让合同）")?></a></li>
        <li><a href="tudi/tudiht03.php"><?=_("国有土地使用权出让合同（一）（宗地出让合同）")?></a></li>
        <li><a href="tudi/tudiht04.php"><?=_("国有土地使用权出让合同（二）（宗地出让合同）")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>