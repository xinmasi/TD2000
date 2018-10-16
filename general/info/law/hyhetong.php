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
      <div align="center"><?=_("行业合同样板库")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="hyhetong/banquan.php"><?=_("版权与著作权")?></a></li>
        <li><a href="hyhetong/baoxian.php"><?=_("保险合同")?></a></li>
        <li><a href="hyhetong/cangchu.php"><?=_("仓储保管")?></a></li>
        <li><a href="hyhetong/chengbao.php"><?=_("承包经营")?></a></li>
        <li><a href="hyhetong/danbao.php"><?=_("担保")?></a></li>
        <li><a href="hyhetong/fangdichan.php"><?=_("房地产合同")?></a></li>
        <li><a href="hyhetong/fuwu.php"><?=_("服务行业")?></a></li>
        <li><a href="hyhetong/gongshui.php"><?=_("供水供电供热")?></a></li>
        <li><a href="hyhetong/gouxiao.php"><?=_("购销合同")?></a></li>
        <li><a href="hyhetong/jishuzr.php"><?=_("国际技术转让")?></a></li>
        <li><a href="hyhetong/hulianwang.php"><?=_("互联网")?></a></li>
        <li><a href="hyhetong/jisuanji.php"><?=_("计算机软件")?></a></li>
        <li><a href="hyhetong/jiagong.php"><?=_("加工承揽")?></a></li>
        <li><a href="hyhetong/jianzhu.php"><?=_("建筑安装工程")?></a></li>
        <li><a href="hyhetong/jiedai.php"><?=_("借款贷款")?></a></li>
        <li><a href="hyhetong/jinrong.php"><?=_("金融票据")?></a></li>
        <li><a href="hyhetong/jinchukou.php"><?=_("进出口合同")?></a></li>
        <li><a href="hyhetong/laodong.php"><?=_("劳动合同")?></a></li>
        <li><a href="hyhetong/lianying.php"><?=_("联营合同")?></a></li>
        <li><a href="hyhetong/qihuo.php"><?=_("期货证券")?></a></li>
        <li><a href="hyhetong/shouyang.php"><?=_("收养领养协议")?></a></li>
        <li><a href="hyhetong/tudi.php"><?=_("土地合同")?></a></li>
        <li><a href="hyhetong/xintuo.php"><?=_("信托投资")?></a></li>
        <li><a href="hyhetong/yanchu.php"><?=_("演出经纪")?></a></li>
        <li><a href="hyhetong/yunshu.php"><?=_("运输合同")?></a></li>
        <li><a href="hyhetong/zengyu.php"><?=_("赠予捐募")?></a></li>
        <li><a href="hyhetong/zhaobiao.php"><?=_("招标投标")?></a></li>
        <li><a href="hyhetong/zhongwaihz.php"><?=_("中外合资合同")?></a></li>
        <li><a href="hyhetong/zhuanli.php"><?=_("专利合同")?></a></li>
        <li><a href="hyhetong/zulin.php"><?=_("租赁合同")?></a></li>
      </ol>
    </td>
  </tr>
</table>

<br><center><input type="button" class="BigButton" value="<?=_("回主目录")?>" onclick="location='index.php';"></center><br>

</body>
</html>