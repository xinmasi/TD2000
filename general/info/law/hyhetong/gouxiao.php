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
      <div align="center"><?=_("购销合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="gouxiao/maimaiht03.php"><?=_("农副产品购销合同（一）")?></a></li>
        <li><a href="gouxiao/maimaiht18.php"><?=_("农副产品购销合同（二）")?></a></li>
        <li><a href="gouxiao/maimaiht19.php"><?=_("农副产品定购合同")?></a></li>
        <li><a href="gouxiao/maimaiht00.php"><?=_("商品房购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht01.php"><?=_("成交确认书（远洋）")?></a></li>
        <li><a href="gouxiao/maimaiht02.php"><?=_("柑桔购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht04.php"><?=_("粮食定购合同")?></a></li>
        <li><a href="gouxiao/maimaiht05.php"><?=_("（现汇）外贸合同书（之一）")?></a></li>
        <li><a href="gouxiao/maimaiht06.php"><?=_("（现汇）外贸合同书（之二）")?></a></li>
        <li><a href="gouxiao/maimaiht07.php"><?=_("（现汇）外贸合同书（之三）")?></a></li>
        <li><a href="gouxiao/maimaiht08.php"><?=_("工矿产品购销合同（一）")?></a></li>
        <li><a href="gouxiao/maimaiht40.php"><?=_("工矿产品购销合同（二）")?></a></li>
        <li><a href="gouxiao/maimaiht59.php"><?=_("工矿产品购销合同（三）")?></a></li>
        <li><a href="gouxiao/maimaiht09.php"><?=_("工矿产品订货合同")?></a></li>
        <li><a href="gouxiao/maimaiht10.php"><?=_("工矿产品供应调拨合同")?></a></li>
        <li><a href="gouxiao/maimaiht11.php"><?=_("锰产品订货合同")?></a></li>
        <li><a href="gouxiao/maimaiht12.php"><?=_("汽车电机电器产品购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht13.php"><?=_("购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht14.php"><?=_("供应合同")?></a></li>
        <li><a href="gouxiao/maimaiht15.php"><?=_("销售合同")?></a></li>
        <li><a href="gouxiao/maimaiht37.php"><?=_("购货合同")?></a></li>
        <li><a href="gouxiao/maimaiht38.php"><?=_("售购合同")?></a></li>
        <li><a href="gouxiao/maimaiht34.php"><?=_("售货合同")?></a></li>
        <li><a href="gouxiao/maimaiht16.php"><?=_("茶叶购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht17.php"><?=_("粮食批发市场粮油交易合同")?></a></li>
        <li><a href="gouxiao/maimaiht20.php"><?=_("农作物种子预约生产合同")?></a></li>
        <li><a href="gouxiao/maimaiht21.php"><?=_("农作物种子购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht22.php"><?=_("买卖合同（一）")?></a></li>
        <li><a href="gouxiao/maimaiht60.php"><?=_("买卖合同（二）")?></a></li>
        <li><a href="gouxiao/maimaiht23.php"><?=_("买卖合同（三）")?></a></li>
        <li><a href="gouxiao/maimaiht62.php"><?=_("买卖合同（四）")?></a></li>
        <li><a href="gouxiao/maimaiht63.php"><?=_("买卖合同（五）")?></a></li>
        <li><a href="gouxiao/maimaiht39.php"><?=_("买卖合同（六）")?></a></li>
        <li><a href="gouxiao/maimaiht25.php"><?=_("买卖合同（七）")?></a></li>
        <li><a href="gouxiao/maimaiht24.php"><?=_("化肥、化学农药、农膜商品购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht27.php"><?=_("销售确认书（一）")?></a></li>
        <li><a href="gouxiao/maimaiht28.php"><?=_("销售确认书（二）")?></a></li>
        <li><a href="gouxiao/maimaiht33.php"><?=_("销售确认书（三）")?></a></li>
        <li><a href="gouxiao/maimaiht30.php"><?=_("凭规格销售合同")?></a></li>
        <li><a href="gouxiao/maimaiht31.php"><?=_("木材购销（订货）合同")?></a></li>
        <li><a href="gouxiao/maimaiht32.php"><?=_("民用爆破器材购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht35.php"><?=_("棉花定购合同")?></a></li>
        <li><a href="gouxiao/maimaiht36.php"><?=_("烟草买卖合同")?></a></li>
        <li><a href="gouxiao/maimaiht41.php"><?=_("钢铁产品订货合同")?></a></li>
        <li><a href="gouxiao/maimaiht42.php"><?=_("煤矿机电产品购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht43.php"><?=_("羊角大椒干购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht44.php"><?=_("铝制品买卖合同")?></a></li>
        <li><a href="gouxiao/maimaiht45.php"><?=_("棉花定购合同（１－２）")?></a></li>
        <li><a href="gouxiao/maimaiht46.php"><?=_("水果定购合同")?></a></li>
        <li><a href="gouxiao/maimaiht47.php"><?=_("五金交电家电化工商品购销合同（一）")?></a></li>
        <li><a href="gouxiao/maimaiht26.php"><?=_("五金交电家电化工商品购销合同（二）")?></a></li>
        <li><a href="gouxiao/maimaiht48.php"><?=_("生猪、鲜蛋、菜牛、菜羊、家禽购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht49.php"><?=_("蔬菜定购合同")?></a></li>
        <li><a href="gouxiao/maimaiht50.php"><?=_("针纺织品购销分合同及台帐明细表")?></a></li>
        <li><a href="gouxiao/maimaiht51.php"><?=_("汽车产品供需合同")?></a></li>
        <li><a href="gouxiao/maimaiht53.php"><?=_("凭规格销售合同")?></a></li>
        <li><a href="gouxiao/maimaiht54.php"><?=_("印刷品订货合同")?></a></li>
        <li><a href="gouxiao/maimaiht55.php"><?=_("木材购销(订货)合同")?></a></li>
        <li><a href="gouxiao/maimaiht56.php"><?=_("建材订货合同")?></a></li>
        <li><a href="gouxiao/maimaiht57.php"><?=_("水果买卖合同")?></a></li>
        <li><a href="gouxiao/maimaiht58.php"><?=_("建造船舶合同")?></a></li>
        <li><a href="gouxiao/maimaiht64.php"><?=_("鲜蛋购销合同")?></a></li>
        <li><a href="gouxiao/maimaiht65.php"><?=_("禽畜产品买卖合同")?></a></li>
        <li><a href="gouxiao/maimaiht66.php"><?=_("茶叶订购合同")?></a></li>
        <li><a href="gouxiao/maimaiht67.php"><?=_("烟叶订购合同")?></a></li>
        <li><a href="gouxiao/maimaiht68.php"><?=_("西瓜产销合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>