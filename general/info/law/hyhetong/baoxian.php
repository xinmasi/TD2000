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
      <div align="center"><?=_("保险合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="baoxian/baoxianht00.php"><?=_("中国人民保险公司船舶保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht01.php"><?=_("国内船舶保险单")?></a></li>
        <li><a href="baoxian/baoxianht02.php"><?=_("中国人民保险公司机动车辆投保单")?></a></li>
        <li><a href="baoxian/baoxianht03.php"><?=_("中国人民保险公司机动车辆保险单")?></a></li>
        <li><a href="baoxian/baoxianht04.php"><?=_("中国人民保险公司财产保险单")?></a></li>
        <li><a href="baoxian/baoxianht05.php"><?=_("中国人民保险公司海洋货物运输保险单")?></a></li>
        <li><a href="baoxian/baoxianht06.php"><?=_("财产一切险投保申请书")?></a></li>
        <li><a href="baoxian/baoxianht07.php"><?=_("中国人民保险公司XXX分公司耕牛保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht08.php"><?=_("国内船舶保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht09.php"><?=_("国内货物运输险投保单")?></a></li>
        <li><a href="baoxian/baoxianht10.php"><?=_("中国人民保险公司海洋货物运输保险单")?></a></li>
        <li><a href="baoxian/baoxianht11.php"><?=_("财产一切险保险单")?></a></li>
        <li><a href="baoxian/baoxianht12.php"><?=_("国内水路、铁路货物运输保险凭证")?></a></li>
        <li><a href="baoxian/baoxianht13.php"><?=_("中国人民保险公司财产险投保申请书")?></a></li>
        <li><a href="baoxian/baoxianht14.php"><?=_("中国人民保险公司船舶保险保险单")?></a></li>
        <li><a href="baoxian/baoxianht15.php"><?=_("中国人民保险公司建筑、安装工程险投保单")?></a></li>
        <li><a href="baoxian/baoxianht16.php"><?=_("儿童保险合同")?></a></li>
        <li><a href="baoxian/baoxianht17.php"><?=_("中国人民保险公司企业财产保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht18.php"><?=_("中国人民保险公司建筑、安装工程保险单")?></a></li>
        <li><a href="baoxian/baoxianht19.php"><?=_("中国人民保险公司财产险投保申请书")?></a></li>
        <li><a href="baoxian/baoxianht20.php"><?=_("企业财产保险单")?></a></li>
        <li><a href="baoxian/baoxianht21.php"><?=_("企业财产保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht22.php"><?=_("中国人民保险公司建筑工程一切险保险单")?></a></li>
        <li><a href="baoxian/baoxianht23.php"><?=_("团体人身意外伤害保险保险单")?></a></li>
        <li><a href="baoxian/baoxianht24.php"><?=_("团体人寿保险合同")?></a></li>
        <li><a href="baoxian/baoxianht25.php"><?=_("个人人身意外伤害保险合同")?></a></li>
        <li><a href="baoxian/baoxianht26.php"><?=_("团体人身意外伤害保险投保单")?></a></li>
        <li><a href="baoxian/baoxianht27.php"><?=_("团体人身意外伤害保险条款")?></a></li>
        <li><a href="baoxian/baoxianht28.php"><?=_("中国人民保险公司XXX分公司耕牛保险保险单")?></a></li>
        <li><a href="baoxian/baoxianht29.php"><?=_("中国人民保险公司企业财产保险单")?></a></li>
        <li><a href="baoxian/baoxianht30.php"><?=_("财产保险合同格式（涉外）")?></a></li>
        <li><a href="baoxian/baoxianht31.php"><?=_("中国人民保险公司运输保险凭证")?></a></li>
        <li><a href="baoxian/baoxianht32.php"><?=_("中国人民保险公司家庭财产保险单")?></a></li>
        <li><a href="baoxian/baoxianht33.php"><?=_("团体（个人）人身意外伤害保险附加意外伤害医疗保险暂行条款")?></a></li>
        <li><a href="baoxian/baoxianht34.php"><?=_("财产保险合同")?></a></li>
        <li><a href="baoxian/baoxianht35.php"><?=_("中国人民保险公司XXX分公司建筑、安装工程保险单")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>