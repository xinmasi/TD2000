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
      <div align="center"><?=_("承包经营")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="chengbao/chenbaoht00.php"><?=_("机动车辆承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht01.php"><?=_("工副业承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht02.php"><?=_("减亏（补贴）包干合同")?></a></li>
        <li><a href="chengbao/chenbaoht03.php"><?=_("渔业承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht04.php"><?=_("土地联产经营承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht05.php"><?=_("农副业承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht06.php"><?=_("畜牧业承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht07.php"><?=_("林业承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht08.php"><?=_("树苗栽培承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht09.php"><?=_("畜牧饲养承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht10.php"><?=_("企业承包经营合同")?></a></li>
        <li><a href="chengbao/chenbaoht11.php"><?=_("果园经营管理承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht12.php"><?=_("树木苗圃经营承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht13.php"><?=_("山林防护管理承包合同")?></a></li>
        <li><a href="chengbao/chenbaoht14.php"><?=_("企业招标承包经营合同")?></a></li>
        <li><a href="chengbao/chenbaoht15.php"><?=_("上缴利润递增包干合同")?></a></li>
        <li><a href="chengbao/chenbaoht16.php"><?=_("缴利润基数包干，超收分成合同")?></a></li>
        <li><a href="chengbao/chenbaoht17.php"><?=_("上交利润定额包干合同")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>