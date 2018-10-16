<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("世界时间");
include_once("inc/header.inc.php");
?>
<SCRIPT>
<?
$CUR_YEAR=date("Y",time());
$CUR_MON=date("m",time())-1;
$CUR_DAY=date("d",time());
$CUR_HOUR = date('H');
$CUR_MINITE = date('i');
$CUR_SECOND = date('s');

$TIME_STR="$CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND";
?>
var OA_TIME = new Date(<?=$TIME_STR?>);
var Today = new Date();
var tY = Today.getFullYear();
var tM = Today.getMonth();
var tD = Today.getDate();

//世界时间资料
var timeData = {
"Asia               <?=_("亚洲")?>": {   //----------------------------------------------
"Brunei             <?=_("文莱")?>    ":["+0800","","<?=_("斯里巴加湾市")?>"],
"Burma              <?=_("缅甸")?>    ":["+0630","","<?=_("仰光")?>"],
"Cambodia           <?=_("柬埔寨")?>  ":["+0700","","<?=_("金边")?>"],
"China              <?=_("中国")?>    ":["+0800","","<?=_("北京、重庆、上海、天津")?>"],
"Hong kong,Macau    <?=_("香港·澳门")?>":["+0800","","<?=_("香港、澳门特区")?>"],
"Indonesia          <?=_("印尼")?>    ":["+0700","","<?=_("雅加达")?>"],
"Japan              <?=_("日本")?>    ":["+0900","","<?=_("东京、大阪、札幌")?>"],
"Korea              <?=_("韩国")?>    ":["+0900","","<?=_("汉城")?>"],
"Laos               <?=_("老挝")?>    ":["+0700","","<?=_("万象")?>"],
"Malaysia           <?=_("马来西亚")?>":["+0800","","<?=_("吉隆坡")?>"],
"Mongolia           <?=_("蒙古")?>    ":["+0800","03L03|09L03","<?=_("乌兰巴托、库伦")?>"],
"Philippines        <?=_("菲律宾")?>  ":["+0800","04F53|10F53","<?=_("马尼拉")?>"],
"Russia(Anadyr)     <?=_("俄罗斯")?>  ":["+1300","03L03|10L03","<?=_("阿纳德尔河")?>"],
"Russia(Kamchatka)  <?=_("俄罗斯")?>  ":["+1200","03L03|10L03","<?=_("堪察加半岛")?>"],
"Russia(Magadan)    <?=_("俄罗斯")?>  ":["+1100","03L03|10L03","<?=_("马加丹")?>"],
"Russia(Vladivostok)<?=_("俄罗斯")?>  ":["+1000","03L03|10L03","<?=_("符拉迪沃斯托克(海参崴)")?>"],
"Russia(Yakutsk)    <?=_("俄罗斯")?>  ":["+0900","03L03|10L03","<?=_("雅库茨克")?>"],
"Singapore          <?=_("新加坡")?>  ":["+0800","","<?=_("新加坡")?>"],
"TaiPei             <?=_("中国台北")?>":["+0800","","<?=_("台北、高雄")?>"],
"Thailand           <?=_("泰国")?>    ":["+0700","","<?=_("曼谷")?>"],
"Urumchi            <?=_("中国乌鲁木齐")?>":["+0700","","<?=_("乌鲁木齐")?>"],
"Vietnam            <?=_("越南")?>    ":["+0700","","<?=_("河内")?>"]
},
"ME, India pen.     <?=_("中东、印度半岛")?>": {   //------------------------------------
"Afghanistan        <?=_("阿富汗")?>  ":["+0430","","<?=_("喀布尔")?>"],
"Arab Emirates      <?=_("阿拉伯联合酋长国")?>":["+0400","","<?=_("阿布扎比")?>"],
"Bahrain            <?=_("巴林")?>    ":["+0300","","<?=_("麦纳麦")?>"],
"Bangladesh         <?=_("孟加拉")?>  ":["+0600","","<?=_("达卡")?>"],
"Bhutan             <?=_("不丹")?>    ":["+0600","","<?=_("廷布")?>"],
"Cyprus             <?=_("塞浦路斯")?>":["+0200","","<?=_("尼科西亚")?>"],
"Georgia            <?=_("乔治亚")?>  ":["+0500","","<?=_("第比利斯")?>"],
"India              <?=_("印度")?>    ":["+0530","","<?=_("新德里、孟买、加尔各答")?>"],
"Iran               <?=_("伊朗")?>    ":["+0330","04 13|10 13","<?=_("德黑兰")?>"],
"Iraq               <?=_("伊拉克")?>  ":["+0300","04 13|10 13","<?=_("巴格达")?>"],
"Israel             <?=_("以色列·巴勒斯坦")?>":["+0200","04F53|09F53","<?=_("耶路撒冷")?>"],
"Jordan             <?=_("约旦")?>    ":["+0200","","<?=_("安曼")?>"],
"Kuwait             <?=_("科威特")?>  ":["+0300","","<?=_("科威特城")?>"],
"Lebanon            <?=_("黎巴嫩")?>  ":["+0200","03L03|10L03","<?=_("贝鲁特")?>"],
"Maldives           <?=_("马尔代夫")?>":["+0500","","<?=_("马累")?>"],
"Nepal              <?=_("尼泊尔")?>  ":["+0545","","<?=_("加德满都")?>"],
"Oman               <?=_("阿曼")?>    ":["+0400","","<?=_("马斯喀特")?>"],
"Pakistan           <?=_("巴基斯坦")?>":["+0500","","<?=_("卡拉奇、伊斯兰堡")?>"],
"Qatar              <?=_("卡塔尔")?>  ":["+0300","","<?=_("多哈")?>"],
"Saudi Arabia       <?=_("沙特阿拉伯")?>":["+0300","","<?=_("利雅得")?>"],
"Sri Lanka          <?=_("斯里兰卡")?>":["+0600","","<?=_("科伦坡")?>"],
"Syria              <?=_("叙利亚")?>  ":["+0200","04 13|10 13","<?=_("大马士革")?>"],
"Tajikistan         <?=_("塔吉克斯坦")?>":["+0500","","<?=_("杜尚别")?>"],
"Turkey             <?=_("土耳其")?>  ":["+0200","","<?=_("伊斯坦堡")?>"],
"Turkmenistan       <?=_("土库曼斯坦")?>":["+0500","","<?=_("阿什哈巴德")?>"],
"Uzbekistan         <?=_("乌兹别克斯坦")?>":["+0500","","<?=_("塔什干")?>"],
"Yemen              <?=_("也门")?>    ":["+0300","","<?=_("萨那")?>"]
},
"North Europe       <?=_("北欧")?>": {   //----------------------------------------------
"Denmark            <?=_("丹麦")?>":["+0100","04F03|10L03","<?=_("哥本哈根")?>"],
"Finland            <?=_("芬兰")?>":["+0200","03L01|10L01","<?=_("赫尔辛基")?>"],
"Iceland            <?=_("冰岛")?>":["+0000","","<?=_("雷克雅未克")?>"],
"Norwegian          <?=_("挪威")?>":["+0100","","<?=_("奥斯陆")?>"],
"Sweden             <?=_("瑞典")?>":["+0100","03L01|10L01","<?=_("斯德哥尔摩")?>"]
},
"Eastern Europe     <?=_("中欧、东欧")?>": {   //----------------------------------------
"Armenia            <?=_("亚美尼亚")?>":["+0400","","<?=_("埃里温")?>"],
"Austria            <?=_("奥地利")?>  ":["+0100","03L01|10L01","<?=_("维也纳")?>"],
"Azerbaijan         <?=_("阿塞拜疆")?>":["+0400","","<?=_("巴库")?>"],
"Czech              <?=_("捷克")?>    ":["+0100","","<?=_("布拉格")?>"],
"Estonia            <?=_("爱沙尼亚")?>":["+0200","","<?=_("塔林")?>"],
"Germany            <?=_("德国")?>    ":["+0100","03L01|10L01","<?=_("柏林、波恩")?>"],
"Hungarian          <?=_("匈牙利")?>  ":["+0100","","<?=_("布达佩斯")?>"],
"Kazakhstan(Astana) <?=_("哈萨克斯坦")?>":["+0600","","<?=_("阿斯塔纳、阿拉木图")?>"],
"Kazakhstan(Aqtobe) <?=_("哈萨克斯坦")?>":["+0500","","<?=_("阿克托别")?>"],
"Kazakhstan(Aqtau)  <?=_("哈萨克斯坦")?>":["+0400","","<?=_("阿克图")?>"],
"Kirghizia          <?=_("吉尔吉斯")?>":["+0500","","<?=_("比斯凯克")?>"],
"Latvia             <?=_("拉脱维亚")?>":["+0200","","<?=_("里加")?>"],
"Lithuania          <?=_("立陶宛")?>  ":["+0200","","<?=_("维尔纽斯")?>"],
"Moldova            <?=_("摩尔多瓦")?>":["+0200","","<?=_("基希纳乌")?>"],
"Poland             <?=_("波兰")?>    ":["+0100","","<?=_("华沙")?>"],
"Rumania            <?=_("罗马尼亚")?>":["+0200","","<?=_("布加勒斯特")?>"],
"Russia(Moscow)     <?=_("俄罗斯")?>  ":["+0300","03L03|10L03","<?=_("莫斯科")?>"],
"Russia(Volgograd)  <?=_("俄罗斯")?>  ":["+0300","03L03|10L03","<?=_("伏尔加格勒")?>"],
"Slovakia           <?=_("斯洛伐克")?>":["+0100","","<?=_("布拉迪斯拉发")?>"],
"Switzerland        <?=_("瑞士")?>    ":["+0100","","<?=_("苏黎世")?>"],
"Ukraine            <?=_("乌克兰")?>  ":["+0200","","<?=_("基辅")?>"],
"Ukraine(Simferopol)<?=_("乌克兰")?>  ":["+0300","","<?=_("辛菲罗波尔")?>"],
"Belarus            <?=_("白俄罗斯")?>":["+0200","03L03|10L03","<?=_("明斯克")?>"]
},
"Western Europe     <?=_("西欧")?>": {   //----------------------------------------------
"Belgium            <?=_("比利时")?> ":["+0100","03L01|10L01","<?=_("布鲁塞尔")?>"],
"France             <?=_("法国")?>   ":["+0100","03L01|10L01","<?=_("巴黎")?>"],
"Ireland            <?=_("爱尔兰")?> ":["+0000","03L01|10L01","<?=_("都柏林")?>"],
"Monaco             <?=_("摩纳哥")?> ":["+0100","","<?=_("摩纳哥市")?>"],
"Netherlands        <?=_("荷兰")?>   ":["+0100","03L01|10L01","<?=_("阿姆斯特丹")?>"],
"Luxembourg         <?=_("卢森堡")?> ":["+0100","03L01|10L01","<?=_("卢森堡市")?>"],
"United Kingdom     <?=_("英国")?>   ":["+0000","03L01|10L01","<?=_("伦敦、爱丁堡")?>"]
},
"South Europe       <?=_("南欧")?>": { //------------------------------------------------
"Albania            <?=_("阿尔巴尼亚")?>":["+0100","","<?=_("地拉那")?>"],
"Bulgaria           <?=_("保加利亚")?>":["+0200","","<?=_("索菲亚")?>"],
"Greece             <?=_("希腊")?>    ":["+0200","03L01|10L01","<?=_("雅典")?>"],
"Holy See           <?=_("罗马教廷")?>":["+0100","","<?=_("梵蒂冈")?>"],
"Italy              <?=_("意大利")?>  ":["+0100","03L01|10L01","<?=_("罗马")?>"],
"Malta              <?=_("马耳他")?>  ":["+0100","","<?=_("瓦莱塔")?>"],
"Portugal           <?=_("葡萄牙")?>  ":["+0000","03L01|10L01","<?=_("里斯本")?>"],
"San Marino         <?=_("圣马利诺")?>":["+0100","","<?=_("圣马利诺")?>"],
"Span               <?=_("西班牙")?>  ":["+0100","03L01|10L01","<?=_("马德里")?>"],
"Slovenia           <?=_("斯洛文尼亚")?>":["+0100","","<?=_("卢布尔雅那")?>"],
"Yugoslavia         <?=_("南斯拉夫(塞尔维亚)")?>":["+0100","","<?=_("贝尔格莱德")?>"]
},
"North America      <?=_("北美洲")?>": {   //--------------------------------------------
"Canada(NST)        <?=_("加拿大")?>":["-0330","04F02|10L02","<?=_("纽芬兰、圣约翰、古斯湾")?>"],
"Canada(AST)        <?=_("加拿大")?>":["-0400","04F02|10L02","<?=_("冰河湾、Pangnirtung")?>"],
"Canada(EST)        <?=_("加拿大")?>":["-0500","04F02|10L02","<?=_("蒙特罗")?>"],
"Canada(CST)        <?=_("加拿大")?>":["-0600","04F02|10L02","<?=_("雷迦納、雨河鎮、Swift Current")?>"],
"Canada(MST)        <?=_("加拿大")?>":["-0700","04F02|10L02","<?=_("印奴维特港湾、埃德蒙顿、道森河")?>"],
"Canada(PST)        <?=_("加拿大")?>":["-0800","04F02|10L02","<?=_("温哥华")?>"],
"US(Eastern)        <?=_("美国(东岸)")?>":["-0500","04F02|10L02","<?=_("华盛顿、纽约")?>"],
"US(Indiana)        <?=_("美国")?>      ":["-0500","","<?=_("印第安纳")?>"],
"US(Central)        <?=_("美国(中部)")?>":["-0600","04F02|10L02","<?=_("芝加哥")?>"],
"US(Mountain)       <?=_("美国(山区)")?>":["-0700","04F02|10L02","<?=_("丹佛")?>"],
"US(Arizona)        <?=_("美国")?>      ":["-0700","","<?=_("亚历桑那")?>"],
"US(Pacific)        <?=_("美国(西岸)")?>":["-0800","04F02|10L02","<?=_("旧金山、洛杉矶")?>"],
"US(Alaska)         <?=_("美国")?>      ":["-0900","","<?=_("阿拉斯加、朱诺")?>"]
},
"South America      <?=_("中南美洲")?>": {   //------------------------------------------
"Antigua & Barbuda  <?=_("安提瓜岛及巴布达岛")?>":["-0400","","<?=_("圣约翰")?>"],
"Argentina          <?=_("阿根廷")?>  ":["-0300","","<?=_("布宜诺斯艾利斯")?>"],
"Bahamas            <?=_("巴哈马")?>  ":["-0500","","<?=_("拿骚")?>"],
"Barbados           <?=_("巴巴多斯岛")?>":["-0400","","<?=_("布里奇顿(桥镇)")?>"],
"Belize             <?=_("贝里斯")?>  ":["-0600","","<?=_("贝里斯")?>"],
"Bolivia            <?=_("玻利维亚")?>":["-0400","","<?=_("拉巴斯")?>"],
"Brazil(AST)        <?=_("巴西")?>    ":["-0500","10F03|02L03","Porto Acre"],
"Brazil(EST)        <?=_("巴西")?>    ":["-0300","10F03|02L03","<?=_("巴西利亚、里约热内卢")?>"],
"Brazil(FST)        <?=_("巴西")?>    ":["-0200","10F03|02L03","<?=_("诺罗纳")?>"],
"Brazil(WST)        <?=_("巴西")?>    ":["-0400","10F03|02L03","<?=_("库亚巴")?>"],
"Chilean            <?=_("智利")?>    ":["-0500","10F03|03F03","Hanga Roa"],
"Chilean            <?=_("智利")?>    ":["-0300","10F03|03F03","<?=_("圣地亚哥")?>"],
"Colombia           <?=_("哥伦比亚")?>":["-0500","","<?=_("波哥大")?>"],
"Costa Rica         <?=_("哥斯达黎加")?>":["-0600","","<?=_("圣何塞")?>"],
"Cuba               <?=_("古巴")?>    ":["-0500","04 13|10L03","<?=_("哈瓦那")?>"],
"Dominican          <?=_("多米尼加")?>":["-0400","","<?=_("圣多明各、罗梭")?>"],
"Ecuador            <?=_("厄瓜多尔")?>":["-0500","","<?=_("基多")?>"],
"El Salvador        <?=_("萨尔瓦多")?>":["-0600","","<?=_("圣萨尔瓦多")?>"],
"Falklands          <?=_("福克兰群岛")?>":["-0300","09F03|04F03","<?=_("史丹利")?>"],
"Guatemala          <?=_("危地马拉")?>":["-0600","","<?=_("危地马拉城")?>"],
"Haiti              <?=_("海地")?>    ":["-0500","","<?=_("太子港")?>"],
"Honduras           <?=_("洪都拉斯")?>":["-0600","","<?=_("特古西加尔巴")?>"],
"Jamaica            <?=_("牙买加")?>  ":["-0500","","<?=_("金斯敦")?>"],
"Mexico(Mazatlan)   <?=_("墨西哥")?>  ":["-0700","","<?=_("马萨特兰")?>"],
"Mexico(<?=_("首都")?>)       <?=_("墨西哥")?>  ":["-0600","","<?=_("墨西哥城")?>"],
"Mexico(<?=_("蒂华纳")?>)     <?=_("墨西哥")?>  ":["-0800","","<?=_("蒂华纳")?>"],
"Nicaragua          <?=_("尼加拉瓜")?>":["-0500","","<?=_("马那瓜")?>"],
"Panama             <?=_("巴拿马")?>  ":["-0500","","<?=_("巴拿马市")?>"],
"Paraguay           <?=_("巴拉圭")?>  ":["-0400","10F03|02L03","<?=_("亚松森")?>"],
"Peru               <?=_("秘鲁")?>    ":["-0500","","<?=_("利马")?>"],
"Saint Kitts & Nevis <?=_("圣基茨和尼维斯")?>":["-0400","","<?=_("巴斯特尔(Basseterre)")?>"],
"St. Lucia          <?=_("圣卢西亚")?>":["-0400","","<?=_("卡斯特里")?>"],
"St. Vincent & Grenadines <?=_("圣文森特和格林纳丁斯")?>":["-0400","","<?=_("金斯敦")?>"],
"Suriname           <?=_("苏里南")?>":["-0300","","<?=_("帕拉马里博(Paramaribo)")?>"],
"Trinidad & Tobago  <?=_("特立尼达和多巴哥")?>":["-0400","","<?=_("西班牙港")?>"],
"Uruguay            <?=_("乌拉圭")?>  ":["-0300","","<?=_("蒙得维的亚")?>"],
"Venezuela          <?=_("委内瑞拉")?>":["-0400","","<?=_("加拉加斯")?>"]
},
"Africa             <?=_("非洲")?>": {   //----------------------------------------------
"Algeria            <?=_("阿尔及利亚")?>":["+0100","","<?=_("阿尔及尔")?>"],
"Angola             <?=_("安哥拉")?>  ":["+0100","","<?=_("罗安达")?>"],
"Benin              <?=_("贝南")?>    ":["+0100","","<?=_("新港")?>"],
"Botswana           <?=_("博茨瓦纳")?>":["+0200","","<?=_("哈博罗内")?>"],
"Burundi            <?=_("布隆迪")?>  ":["+0200","","<?=_("布琼布拉")?>"],
"Cameroon           <?=_("喀麦隆")?>  ":["+0100","","<?=_("雅温得")?>"],
"Cape Verde         <?=_("佛德角")?>  ":["-0100","","<?=_("普拉亚")?>"],
"Central African    <?=_("中非共和国")?>":["+0100","","<?=_("班吉")?>"],
"Chad               <?=_("乍得")?>    ":["+0100","","<?=_("恩贾梅纳市")?>"],
"Congo              <?=_("刚果(布)")?>":["+0100","","<?=_("布拉柴维尔")?>"],
"Djibouti           <?=_("吉布提")?>  ":["+0300","","<?=_("吉布提")?>"],
"Egypt              <?=_("埃及")?>    ":["+0200","04L53|09L43","<?=_("开罗")?>"],
"Equatorial Guinea  <?=_("赤道几内亚")?>":["+0100","","<?=_("马博托")?>"],
"Ethiopia           <?=_("埃塞俄比亚")?>":["+0300","","<?=_("亚的斯亚贝巴")?>"],
"Gabon              <?=_("加蓬")?>    ":["+0100","","<?=_("利伯维尔")?>"],
"Gambia             <?=_("冈比亚")?>  ":["+0000","","<?=_("班珠尔")?>"],
"Ghana              <?=_("加纳")?>    ":["+0000","","<?=_("阿克拉")?>"],
"Guinea             <?=_("几内亚")?>  ":["+0000","","<?=_("科纳克里")?>"],
"Ivory Coast        <?=_("象牙海岸")?>":["+0000","","<?=_("阿比让、雅穆索戈")?>"],
"Kenya              <?=_("肯尼亚")?>  ":["+0300","","<?=_("内罗毕")?>"],
"Lesotho            <?=_("莱索托")?>  ":["+0200","","<?=_("马塞卢")?>"],
"Liberia            <?=_("利比里亚")?>":["+0000","","<?=_("蒙罗维亚")?>"],
"Madagascar         <?=_("马达加斯加")?>":["+0300","","<?=_("塔那那利佛")?>"],
"Malawi             <?=_("马拉维")?>  ":["+0200","","<?=_("利隆圭")?>"],
"Mali               <?=_("马里")?>    ":["+0000","","<?=_("巴马科")?>"],
"Mauritania         <?=_("毛里塔尼亚")?>":["+0000","","<?=_("努瓦克肖特")?>"],
"Mauritius          <?=_("毛里求斯")?>":["+0400","","<?=_("路易港")?>"],
"Morocco            <?=_("摩洛哥")?>  ":["+0000","","<?=_("卡萨布兰卡")?>"],
"Mozambique         <?=_("莫桑比克")?>":["+0200","","<?=_("马普托")?>"],
"Namibia            <?=_("纳米比亚")?>":["+0200","09F03|04F03","<?=_("温得和克")?>"],
"Niger              <?=_("尼日尔")?>  ":["+0100","","<?=_("尼亚美")?>"],
"Nigeria            <?=_("尼日利亚")?>":["+0100","","<?=_("阿布贾")?>"],
"Rwanda             <?=_("卢旺达")?>  ":["+0200","","<?=_("基加利")?>"],
"Sao Tome           <?=_("圣多美")?>  ":["+0000","","<?=_("圣多美")?>"],
"Senegal            <?=_("塞内加尔")?>":["+0000","","<?=_("达卡尔")?>"],
"Sierra Leone       <?=_("狮子山国")?>":["+0000","","<?=_("自由城")?>"],
"Somalia            <?=_("索马里")?>  ":["+0300","","<?=_("摩加迪沙")?>"],
"South Africa       <?=_("南非")?>    ":["+0200","","<?=_("开普敦、普利托里亚")?>"],
"Sudan              <?=_("苏丹")?>    ":["+0200","","<?=_("喀土穆")?>"],
"Tanzania           <?=_("坦桑尼亚")?>":["+0300","","<?=_("达累斯萨拉姆")?>"],
"Togo               <?=_("多哥")?>    ":["+0000","","<?=_("洛美隆")?>"],
"Tunisia            <?=_("突尼斯")?>  ":["+0100","","<?=_("突尼斯市")?>"],
"Uganda             <?=_("乌干达")?>  ":["+0300","","<?=_("坎帕拉")?>"],
"Zaire              <?=_("扎伊尔(刚果金)")?>  ":["+0100","","<?=_("金沙萨")?>"],
"Zambia             <?=_("赞比亚")?>  ":["+0200","","<?=_("卢萨卡")?>"],
"Zimbabwe           <?=_("津巴布韦")?>":["+0200","","<?=_("哈拉雷")?>"]
},
"Oceania            <?=_("大洋洲")?>": { //----------------------------------------------
"American Samoa(US) <?=_("美属萨摩亚(美)")?>":["-1100","","<?=_("帕果帕果港")?>"],
"Aus.(Adelaide)     <?=_("澳大利亚")?>  ":["+0930","10F03|03F03","<?=_("阿得雷德")?>"],
"Aus.(Brisbane)     <?=_("澳大利亚")?>  ":["+1000","10F03|03F03","<?=_("布里斯班")?>"],
"Aus.(Darwin)       <?=_("澳大利亚")?>  ":["+0930","10F03|03F03","<?=_("达尔文")?>"],
"Aus.(Hobart)       <?=_("澳大利亚")?>  ":["+1000","10F03|03F03","<?=_("荷伯特")?>"],
"Aus.(Perth)        <?=_("澳大利亚")?>  ":["+0800","10F03|03F03","<?=_("佩思")?>"],
"Aus.(Sydney)       <?=_("澳大利亚")?>  ":["+1000","10F03|03F03","<?=_("悉尼")?>"],
"Cook Islands(NZ)   <?=_("库克群岛(新西兰)")?>  ":["-1000","","<?=_("阿瓦鲁阿")?>"],
"Eniwetok           <?=_("埃尼威托克岛")?>":["-1200","","<?=_("埃尼威托克岛")?>"],
"Fiji               <?=_("斐济")?>      ":["+1200","11F03|02L03","<?=_("苏瓦")?>"],
"Guam               <?=_("关岛")?>      ":["+1000","","<?=_("阿加尼亚")?>"],
"Hawaii(US)         <?=_("夏威夷(美)")?>":["-1000","","<?=_("檀香山")?>"],
"Kiribati           <?=_("基里巴斯")?>  ":["+1100","","<?=_("塔拉瓦")?>"],
//"Mariana Islands    塞班岛    ":["","","塞班岛"],
"Marshall Is.       <?=_("马绍尔群岛")?>":["+1200","","<?=_("马朱罗")?>"],
"Micronesia         <?=_("密克罗尼西亚联邦")?>":["+1000","","<?=_("帕利基尔(Palikir)")?>"],
"Midway Is.(US)     <?=_("中途岛(美)")?>":["-1100","","<?=_("中途岛")?>"],
"Nauru Rep.         <?=_("瑙鲁共和国")?>":["+1200","","<?=_("亚伦")?>"],
"New Calednia(FR)   <?=_("新克里多尼亚(法)")?>":["+1100","","<?=_("努美阿")?>"],
"New Zealand        <?=_("新西兰")?>    ":["+1200","10F03|04F63","<?=_("奥克兰")?>"],
"New Zealand(CHADT) <?=_("新西兰")?>    ":["+1245","10F03|04F63","<?=_("惠灵顿")?>"],
"Niue(NZ)           <?=_("纽埃(新)")?>      ":["-1100","","<?=_("阿洛菲(Alofi)")?>"],
"Nor. Mariana Is.   <?=_("北马里亚纳群岛(美)")?>":["+1000","","<?=_("塞班岛")?>"],
"Palau              <?=_("帕劳群岛(帛琉群岛)")?>      ":["+0900","","<?=_("科罗尔")?>"],
"Papua New Guinea   <?=_("巴布亚新几内亚")?>":["+1000","","<?=_("莫尔斯比港")?>"],
"Pitcairn Is.(UK)   <?=_("皮特克恩群岛(英)")?>":["-0830","","<?=_("亚当斯敦")?>"],
"Polynesia(FR)      <?=_("玻利尼西亚(法)")?>":["-1000","","<?=_("巴比蒂、塔希提")?>"],
"Solomon Is.        <?=_("所罗门群岛")?>":["+1100","","<?=_("霍尼亚拉")?>"],
"Tahiti             <?=_("塔希提")?>  ":["-1000","","<?=_("帕佩特")?>"],
"Tokelau(NZ)        <?=_("托克劳群岛(新)")?>    ":["-1100","","<?=_("努库诺努、法考福、阿塔富")?>"],
"Tonga              <?=_("汤加")?>    ":["+1300","10F63|04F63","<?=_("努库阿洛法")?>"],
"Tuvalu             <?=_("图瓦卢")?>  ":["+1200","","<?=_("富纳富提")?>"],
"Western Samoa      <?=_("西萨摩亚")?>":["-1100","","<?=_("阿皮亚")?>"],
"<?=_("国际换日线")?>                   ":["-1200","","<?=_("国际换日线")?>"]
}
};

var nStr1 = new Array('<?=_("日")?>','<?=_("一")?>','<?=_("二")?>','<?=_("三")?>','<?=_("四")?>','<?=_("五")?>','<?=_("六")?>','<?=_("七")?>','<?=_("八")?>','<?=_("九")?>','<?=_("十")?>');

/*****************************************************************************
                                  世界时间计算
*****************************************************************************/
var OneHour = 60*60*1000;
var OneDay = OneHour*24;
var TimezoneOffset = Today.getTimezoneOffset()*60*1000;

function showUTC(objD) {
   var dn,s;
   var hh = objD.getUTCHours();
   var mm = objD.getUTCMinutes();
   var ss = objD.getUTCSeconds();
   s = objD.getUTCFullYear() + "<?=_("年")?>" + (objD.getUTCMonth() + 1) + "<?=_("月")?>" + objD.getUTCDate() +"<?=_("日")?> ("+ nStr1[objD.getUTCDay()] +")";

   if(hh>12) { hh = hh-12; dn = '<?=_("下午")?>'; }
   else dn = '<?=_("上午")?>';

   if(hh<10) hh = '0' + hh;
   if(mm<10) mm = '0' + mm;
   if(ss<10) ss = '0' + ss;

   s += " " + dn + ' ' + hh + ":" + mm + ":" + ss;
   return(s);
}

function showLocale(objD) {
   var dn,s;
   var hh = objD.getHours();
   var mm = objD.getMinutes();
   var ss = objD.getSeconds();
   s = objD.getFullYear() + "<?=_("年")?>" + (objD.getMonth() + 1) + "<?=_("月")?>" + objD.getDate() +"<?=_("日")?> ("+ nStr1[objD.getDay()] +")";

   if(hh>12) { hh = hh-12; dn = '<?=_("下午")?>'; }
   else dn = '<?=_("上午")?>';

   if(hh<10) hh = '0' + hh;
   if(mm<10) mm = '0' + mm;
   if(ss<10) ss = '0' + ss;

   s += " " + dn + ' ' + hh + ":" + mm + ":" + ss;
   return(s);
}

//传入时差字串, 返回偏移之正负毫秒
function parseOffset(s) {
   var sign,hh,mm,v;
   sign = s.substr(0,1)=='-'?-1:1;
   hh = Math.floor(s.substr(1,2));
   mm = Math.floor(s.substr(3,2));
   v = sign*(hh*60+mm)*60*1000;
   return(v);
}

//返回UTC日期控件 (年,月-1,第几个星期几,几点)
function getWeekDay(y,m,nd,w,h){
   var d,d2,w1;
   if(nd>0){
      d = new Date(Date.UTC(y, m, 1));
      w1 = d.getUTCDay();
      d2 = new Date( d.getTime() + ((w<w1? w+7-w1 : w-w1 )+(nd-1)*7   )*OneDay + h*OneHour);
   }
   else {
      nd = Math.abs(nd);
      d = new Date( Date.UTC(y, m+1, 1)  - OneDay );
      w1 = d.getUTCDay();
      d2 = new Date( d.getTime() + (  (w>w1? w-7-w1 : w-w1 )-(nd-1)*7   )*OneDay + h*OneHour);
   }
   return(d2);
}

//传入某时间值, 日光节约字串 返回 true 或 false
function isDaylightSaving(d,strDS) {

   if(strDS == '') return(false);

   var m1,n1,w1,t1;
   var m2,n2,w2,t2;
   with (Math){
      m1 = floor(strDS.substr(0,2))-1;
      w1 = floor(strDS.substr(3,1));
      t1 = floor(strDS.substr(4,1));
      m2 = floor(strDS.substr(6,2))-1;
      w2 = floor(strDS.substr(9,1));
      t2 = floor(strDS.substr(10,1));
   }

   switch(strDS.substr(2,1)){
      case 'F': n1=1; break;
      case 'L': n1=-1; break;
      default : n1=0; break;
   }

   switch(strDS.substr(8,1)){
      case 'F': n2=1; break;
      case 'L': n2=-1; break;
      default : n2=0; break;
   }


   var d1, d2, re;

   if(n1==0)
      d1 = new Date(Date.UTC(d.getUTCFullYear(), m1, Math.floor(strDS.substr(2,2)),t1));
   else
      d1 = getWeekDay(d.getUTCFullYear(),m1,n1,w1,t1);

   if(n2==0)
      d2 = new Date(Date.UTC(d.getUTCFullYear(), m2, Math.floor(strDS.substr(8,2)),t2));
   else
      d2 = getWeekDay(d.getUTCFullYear(),m2,n2,w2,t2);

   if(d2>d1)
      re = (d>d1 && d<d2)? true: false;
   else
      re = (d>d1 || d<d2)? true: false;

   return(re);
}

var isDS = false;
var UTC_TIME = new Date(<?=$TIME_STR?>);
//计算全球时间
function getGlobeTime() {
   var d,s;
   d = new Date();

   d.setTime(OA_TIME.getTime()+parseOffset(objTimeZone[0]));

   isDS=isDaylightSaving(d,objTimeZone[1]);
   if(isDS) d.setTime(d.getTime()+OneHour);
   return(showUTC(d));
}

var objTimeZone;
var objContinentMenu;
var objCountryMenu;

function tick() {
   LocalTime.innerHTML = showLocale(OA_TIME);
   GlobeTime.innerHTML = getGlobeTime();
   OA_TIME.setSeconds(OA_TIME.getSeconds()+1);
   window.setTimeout("tick()", 1000);
}

//指定自定索引时区
function setTZ(a,c){
   objContinentMenu.options[a].selected=true;
   chContinent();
   objCountryMenu.options[c].selected=true;
   chCountry();
}

//变更区域
function chContinent() {
   var key,i;
   continent = objContinentMenu.options[objContinentMenu.selectedIndex].value;
   for (var i = objCountryMenu.options.length-1; i >= 0; i--)
      objCountryMenu[0]=null;

   for (key in timeData[continent])
   {
      var option = new Option();
      option.value = key;
      option.text = key;
      objCountryMenu.options[objCountryMenu.options.length]=option;
   }
   objCountryMenu.options[0].selected=true;
   chCountry();
}

//变更国家
function chCountry() {
   var txtContinent = objContinentMenu.options[objContinentMenu.selectedIndex].value;
   var txtCountry = objCountryMenu.options[objCountryMenu.selectedIndex].value;

   objTimeZone = timeData[txtContinent][txtCountry];

   getGlobeTime();

   //地图位移
   City.innerHTML = (isDS==true?"<SPAN STYLE='font-size:12pt;font-family:Wingdings; color:Red;'>R</span> ":'') + objTimeZone[2]; //首都
   var pos = Math.floor(objTimeZone[0].substr(0,3));
   if(pos<0) pos+=24;
   pos*=-10;
   document.getElementById("world").style.left = pos;

}

function setCookie(name,value) {
   var today = new Date();
   var expires = new Date();
   expires.setTime(today.getTime() + 1000*60*60*24*365);
   document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString();
}

function getCookie(Name) {
   var search = Name + "=";
   if(document.cookie.length > 0) {
      offset = document.cookie.indexOf(search);
      if(offset != -1) {
         offset += search.length;
         end = document.cookie.indexOf(";", offset);
         if(end == -1) end = document.cookie.length;
         return unescape(document.cookie.substring(offset, end));
      }
      else return('');
   }
   else return('');
}

///////////////////////////////////////////////////////////////////////////

function initialize() {
   var key;

   //时间
   var map = document.getElementById("map");
//   map.filters.Light.Clear();
//   map.filters.Light.addAmbient(255,255,255,60);
//   map.filters.Light.addCone(120, 60, 80, 120, 60, 255,255,255,120,60);

   objContinentMenu=document.WorldClock.continentMenu;
   objCountryMenu=document.WorldClock.countryMenu;

   for (key in timeData)
   {
      var option = new Option();
      option.value = key;
      option.text = key;
      objContinentMenu[objContinentMenu.length]=option;
   }


   var TZ1 = getCookie('TZ1');
   var TZ2 = getCookie('TZ2');


   if(TZ1=='') {TZ1=0; TZ2=3;}
   setTZ(TZ1,TZ2);

   tick();  
}

function terminate() {
   setCookie("TZ1",objContinentMenu.selectedIndex);
   setCookie("TZ2",objCountryMenu.selectedIndex);
}
</SCRIPT>

<STYLE>.todyaColor {
	BACKGROUND-COLOR: aqua
}
</STYLE>

<BODY class="bodycolor" onload="initialize()" onunload="terminate()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/world_time.gif" WIDTH="22" HEIGHT="22"><span class="big3"> <?=_("世界时间")?></span>
    </td>
  </tr>
</table>
<br>

<TABLE border="0" align="center" class="TableData">
  <TR>
    <FORM name=WorldClock>
    <TD vAlign=top align=middle width=240>
    <FONT style="FONT-SIZE: 9pt" size=2><?=_("本地时间")?></FONT>
    <BR>
    <SPAN id=LocalTime style="FONT-SIZE: 11pt; COLOR: #000080; FONT-FAMILY: Arial"><?=_("0000年0月0日(　)午 00:00:00")?></SPAN>
    <P>

    <SPAN id=City style="FONT-SIZE: 9pt; WIDTH: 150px;"><?=_("中国")?></SPAN>
    <BR>
    <SPAN id=GlobeTime style="FONT-SIZE: 11pt; COLOR: #000080; FONT-FAMILY: Arial"><?=_("0000年0月0日(　)午 00:00:00")?></SPAN>
    <BR>

    <TABLE style="FONT-SIZE: 10pt; FONT-FAMILY: Wingdings">
      <TR>
       <TD align=middle>&Uacute;

          <DIV id=map style="FILTER: Light; OVERFLOW: hidden; WIDTH: 240px; HEIGHT: 120px; BACKGROUND-COLOR: mediumblue">
            <FONT id=world style="FONT-SIZE: 185px; LEFT: 0px; COLOR: green; FONT-FAMILY: Webdings; POSITION: relative; TOP: -26px"><?=_("")?></FONT>
          </DIV>&Ugrave;
        </TD>
      </TR>
    </TABLE>

    <BR>
    <SELECT style="FONT: 9pt; WIDTH: 240px; BACKGROUND-COLOR: #e0e0ff" onchange=chContinent() name=continentMenu></SELECT>
    <BR>
    <SELECT style="FONT: 9pt; WIDTH: 240px; BACKGROUND-COLOR: #e0e0ff" onchange=chCountry() name=countryMenu></SELECT>
  
   </TD>
   </FORM>
  </tr>
</table>

</BODY>
</HTML>
