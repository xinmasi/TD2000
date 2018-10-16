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
      <div align="center"><?=_("加工承揽")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="jiagong/jiagonght01.php"><?=_("加工承揽合同")?></a></li>
        <li><a href="jiagong/jiagonght13.php"><?=_("加工承揽合同(附加工承揽合同样本)")?></a></li>
        <li><a href="jiagong/jiagonght02.php"><?=_("加工、订货合同（一）")?></a></li>
        <li><a href="jiagong/jiagonght03.php"><?=_("加工、订货合同（二）")?></a></li>
        <li><a href="jiagong/jiagonght04.php"><?=_("半成品化验与成品检验合同(二级)")?></a></li>
        <li><a href="jiagong/jiagonght18.php"><?=_("承揽合同")?></a></li>
        <li><a href="jiagong/jiagonght08.php"><?=_("承揽合同（简）")?></a></li>
        <li><a href="jiagong/jiagonght06.php"><?=_("承揽合同（简一）")?></a></li>
        <li><a href="jiagong/jiagonght15.php"><?=_("承揽合同（简二）")?></a></li>
        <li><a href="jiagong/jiagonght19.php"><?=_("承揽合同（简三）")?></a></li>
        <li><a href="jiagong/jiagonght05.php"><?=_("承揽合同（条款）")?></a></li>
        <li><a href="jiagong/jiagonght12.php"><?=_("承揽合同（含运输）")?></a></li>
        <li><a href="jiagong/jiagonght07.php"><?=_("承揽合同（半成品）")?></a></li>
        <li><a href="jiagong/jiagonght14.php"><?=_("综架承揽合同")?></a></li>
        <li><a href="jiagong/jiagonght00.php"><?=_("来料加工协议书（轻工）")?></a></li>
        <li><a href="jiagong/jiagonght09.php"><?=_("来件装配协议书（轻工）")?></a></li>
        <li><a href="jiagong/jiagonght10.php"><?=_("中外来料加工（或来件装配）合同")?></a></li>
        <li><a href="jiagong/jiagonght11.php"><?=_("加工定作合同")?></a></li>
        <li><a href="jiagong/jiagonght16.php"><?=_("设备大中修工程合同(二级)")?></a></li>
        <li><a href="jiagong/jiagonght17.php"><?=_("家具定作合同")?></a></li>
        <li><a href="jiagong/jiagonght20.php"><?=_("来料加工和来件装配合同")?></a></li>
        <li><a href="jiagong/jiagonght21.php"><?=_("印刷品订货合同")?></a></li>
        <li><a href="jiagong/jiagonght22.php"><?=_("修缮修理合同")?></a></li>
        <li><a href="jiagong/jiagonght23.php"><?=_("汽车维修合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>