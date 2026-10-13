<?php
include 'common.php';
$access=ip_access($db);
add_rec($access,$db);
page_access($access,$db);
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: max-age=0");
header("Content-Type: text/html; charset=windows-1251");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8, chrome=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" lang="ru" xml:lang="ru" />
		<meta http-equiv="Content-Language" content="ru-ru" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="ImageToolbar" content="no" />
    	<title>УФК 3700 - Главная</title>
	    <link rel="stylesheet" type="text/css" media="all" href="css/menu_v20130613.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/styles_v20221116.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/forms_v20150107.css" />
    	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
    	<script type="text/javascript" language="javascript" src="js/jquery_v20130528.js"></script>
    	<script type="text/javascript" language="javascript" src="js/menu_v20130603.js" defer="defer"></script>
    	<script type="text/javascript" language="javascript" src="js/main_v20230116.js" defer="defer"></script>
	</head>
	<body>
		<?php
		print_header($access,$db);
		?>
		<div id="main">
			<!--<h2><img class="img_fl" src="img/fl_help.png" alt="" height="28" width="38" />Об Управлении<img class="img_fe" src="img/fl_end.png" alt="" height="16" width="34" /></h2>-->
			<h2><span>Об Управлении</span><img class="img_fl" src="img/fl_main.png" alt="" height="28" width="24" /><img src="img/head_main_v20130519.png" alt="Об Управлении" width="160" height="29" /><img class="img_fe" src="img/fl_end.png" alt="" height="16" width="34" /></h2>
			<table align="center" cellpadding="0" cellspacing="0" border="0" width="98%" class="frm_l">
				<tr>
					<td class="frm_r">
						<h3>Основные страницы сайта</h3>
						<ul class="mfolder">
							<!--<li><a href="index.php?ufk=37" class="und"><span>Телефонный справочник&thinsp;&ndash;&thinsp;УФК</span></a></li>-->
							<!--<li><a href="ofk.php" class="und"><span>Телефонный справочник&thinsp;&ndash;&thinsp;Отделения</span></a></li>-->
							<li><a href="depsec.php?doc=0" class="und"><span>Документы на нового сотрудника</span></a></li>
							<li><a href="manuals.php" class="und"><span>Отдел информационных систем</span></a></li>
							<!--<li><a href="library.php" class="und"><span>Библиотека казначея</span></a></li>-->
							<li><a href="depkadr.php" class="und"><span>Отдел ГГСиК</span></a></li>
							<li><a href="depadm.php" class="und"><span>Административно-финансовый отдел</span></a></li>
							<li><a href="depsec.php?doc=1" class="und"><span>Отдел РСиБИ</span></a></li>
							<!--<li><a href="depaud.php" class="und"><span>Отдел ВКиА</span></a></li>-->
							<li><a href="konkurs.php" class="und"><span>Фотоконкурсы Управления</span></a></li>
							<li><a href="veterans.php" class="und"><span>Акция &ldquo;Мы-наследники Победы&rdquo;</span></a></li>
							<?php
							if($access>89999){ print "<li><a href=\"ubp.php\" class=\"und\"><span>Телефонный справочник&thinsp;&ndash;&thinsp;клиенты</span></a></li>"; }
							?>
							<li><a href="search.php" class="und"><span>Поиск по телефонной книге</span></a></li>
							<li><a href="calendar.php" class="und"><span>Календарь</span></a></li>
							<li id="polls_menu" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>Опросы</span></li>
							<div id="polls_block" style="margin-left:1em; display:none">
								<li><a href="#" onclick="checkAuth('polls_manager.php'); return false;" class="und"><span>Менеджер опросов</span></a></li>
								<?php
								$polls_dir = __DIR__ . '/polls/';
								if (is_dir($polls_dir)) {
									$files = glob($polls_dir . '*.php');
									foreach ($files as $file) {
										$filename = basename($file);
										if ($filename === 'index.php') continue;
										if ($filename === 'cnt.php') continue;
										$id = pathinfo($filename, PATHINFO_FILENAME);
										$json_file = $polls_dir . $id . '.json';
										if (file_exists($json_file)) {
											$json_content = file_get_contents($json_file);
											$poll = json_decode($json_content, true);
											$title = htmlspecialchars($poll['title_short'] ?? $id);
											$title = iconv('UTF-8', 'windows-1251//IGNORE', $title);
										} else {
											$title = $id;
										}
										echo "<li><a href=\"#\" onclick=\"checkAuth('polls/{$filename}')\" class=\"und\"><span>{$title}</span></a></li>";
									}
								}
								?>
							</div>
							
							<style>
								#polls_block li {
									white-space: normal !important;
									word-wrap: break-word;
									word-break: break-word;
									max-width: 300px;
								}
								#polls_block a {
									display: block;
									overflow-wrap: break-word;
									padding: 2px 0;
								}
							</style>
							
							<?php
							if($access>89999){ print "<li><a href=\"certif.php\" class=\"und\"><span>Ведение базы данных сертификатов</span></a></li>"; }
							?>
							<!--<li><a href="help.php" class="und"><span>Справка</span></a></li>-->
						</ul>
						<h3 style="margin-top:-0.5em;">Ресурсы Управления</h3>
						<ul class="mfolder">
							<li><a href="http://10.37.1.44/phpbb3/" class="und"><span>Форум УФК по Калужской области</span></a></li>
							<li><a href="http://a3700w12.ufk37.roskazna.local:7797/" class="und"><span>АСФК</span></a></li>
							<li><a href="http://a2700w12.ufk27.roskazna.local:7797/" class="und"><span>АСФК (Брянск)</span></a></li>
							<li><a href="http://a3700w12.ufk37.roskazna.local:7897/" class="und"><span>АСФК ЦОД ДУБНА ТЕСТ</span></a></li>
							<?php
							//if(($access > 89999)||(($access%10000) < 3701)||(($access%10000) > 3726)){ print "<li><a href=\"http://a3700p02.ufk37.roskazna.local:8010\" class=\"und\"><span>Тестовая база АСФК</span></a></li>"; }
							?>
							<li><a href="http://s3700w03.ufk37.roskazna.local:28081/" class="und"><span>СУФД</span></a></li>
							<!--
							<li><a href="http://10.37.205.13/sm7/ess.do?&essuser=true" class="und"><span>Система управления эксплуатацией</span></a></li>
							<li><a href="http://10.128.50.12/user/" class="und"><span>Портал самообслуживания</span></a></li>
							<li><a href="http://10.90.37.21:8080/WebHandler/" class="und"><span>РЦР – WebHandler</span></a></li>
							<li><a href="https://10.128.4.28/WH37/" class="und"><span>РЦР – WebHandler</span></a></li>
							<li><a href="http://10.37.1.40:8080" class="und"><span>Система &ldquo;Законодательство России&rdquo;</span></a></li>
							-->
							<?php
							//if(($access > 89999)||(($access%10000) < 3701)||(($access%10000) > 3726)){ print "<li><a href=\"http://10.37.8.23:38080/\" class=\"und\"><span>АРМ&nbsp;ОРФК</span></a></li>"; }
							if(($access > 89999)||(($access%10000) < 3701)||(($access%10000) > 3726)){ print "<li><a href=\"http://a3700w12.ufk37.roskazna.local:38081/\" class=\"und\"><span>АРМ&nbsp;ОРФК</span></a></li>"; }
							?>
							<li><a href="https://s0006ldzweb03.fsfk.local/" class="und"><span>АСД LanDocs</span></a></li>
							<li><a href="https://s0006ldztweb03.fsfk.local" class="und"><span>АСД LanDocs (Тест)</span></a></li>
							<li><a href="http://10.37.1.54:85/" class="und"><span>Тестирование персонала УФК</span></a></li>
							<li><a href="https://s3700garantweb.fsfk.local/" class="und"><span>ИПС Гарант</span></a></li>
							<li><a href="https://s3700conspweb.fsfk.local/cons/" class="und"><span>ИПС КонсультантПлюс</span></a></li>
							<li><a href="https://mail.fsfk.local/owa" class="und"><span>Почта Outlook</span></a></li>
						</ul>
						<h3 style="margin-top:-0.5em;">Ресурсы ФК</h3>
						<ul class="mfolder">
							<!--<li><a href="http://portal-fk/default.aspx/" class="und"><span>Портал ФК</span></a></li>-->
							<li><a href="https://kaluga.roskazna.gov.ru/" class="und"><span>Официальный сайт</span></a></li>
							<li><a href="https://vp.fsfk.local" class="und"><span>Ведомственный портал</span></a></li>
							<li><a href="https://vp-pravo.cert.roskazna.ru/" class="und"><span>Правовая работа</span></a></li>
							<li><a href="https://disk.fsfk.local/s/DyHqzSj6PHdjYeQ" class="und"><span>Телефонный справочник ФК, МОУ, ЦОКР</span></a></li>
							<li id="aksiok" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>Аксиок</span></li>
							<div id="aksiok_block" style="margin-left:1em; display:none">
							<!--<li><a href="http://10.128.11.42/" class="und"><span>Аксиок ЗиК</span></a></li>-->
							<li><a href="https://aksiok.cert.roskazna.ru/ " class="und"><span>ИС «АКСИОК»</span></a></li>
							<li><a href="http://aksiok.plan.fsfk.local" class="und"><span>Аксиок Планирование</span></a></li>
							<!--<li><a href="https://eoi.cert.roskazna.ru/dcs/" class="und"><span>Аксиок Куб</span></a></li>-->
							<!--<li><a href="https://cert.roskazna.ru/aksiok" class="und"><span>Аксиок УиО УНФА</span></a></li>-->
							</div>
							<li id="pupe" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>СУЭ&nbsp;ФК</span></li>
							<div id="pupe_block" style="margin-left:1em; display:none">
							<li><a href="https://sm-sue.fsfk.local/portal/" class="und"><span>СУЭ ФК (пользователь)</span></a></li>
							<li><a href="https://sm-sue.fsfk.local/sd/operator/index.jsp" class="und"><span>СУЭ ФК (специалист)</span></a></li>
							<li><a href="http://s9500pupeblns04.fsfk.local/user" class="und"><span>ПУПЭ Портал самообслуживания</span></a></li>
							<li><a href="http://s9500pupeblns04.fsfk.local/spec" class="und"><span>ПУПЭ Портал специалиста</span></a></li>
							<!--<li><a href="http://s9500pupeblns04.fsfk.local/spec/instr" class="und"><span>Инструкции</span></a></li>-->
							<!--<li><a href="http://sm-sue.fsfk.local" class="und"><span>СУЭ ФК</span></a></li>-->
							<li><a href="https://peo.portal-fk.fsfk.local/course/view.php?id=147l" class="und"><span>СУЭ ФК - Обучающие материалы</span></a></li>
							<li><a href="data/it/isue_d.pdf" class="und" target="_blank"><span>СУЭ ФК Инструкция диспетчера и эксперта</span></a></li>
							<li><a href="data/it/isue_p.pdf" class="und" target="_blank"><span>СУЭ ФК Инструкция пользователя</span></a></li>
							<li><a href="data/it/isue_s.pdf" class="und" target="_blank"><span>СУЭ ФК Инструкция специалиста</span></a></li>
							</div>
							<!--<li><a href="http://10.74.205.15/sm7/ess.do?thread=0&lang=ru&essuser=true" class="und"><span>СУЭ ФК</span></a></li>-->
							<li id="spto" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>СПТО и КПЭ</span></li>
							<div id="spto_block" style="margin-left:1em; display:none">
							<li><a href="http://spto2.moufk.local:8050/" class="und"><span>Сервер СПТО&nbsp;СБ</span></a></li>
							<li><a href="http://spto1.moufk.local:88/ucon.service/jsp/index.jsp" class="und"><span>Сервер СПТО&nbsp;УК</span></a></li>
							<li><a href="http://kpe.fsfk.local/" class="und"><span>Сервер КПЭ</span></a></li>
							</div>
							<li id="vrs" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>Подсистема&nbsp;ВРС</span></li>
							<div id="vrs_block" style="margin-left:1em; display:none">
							<li><a href="https://fzs.roskazna.ru/" class="und"><span>Портал Заявителя (ФЗС)</span></a></li>
							<li><a href="https://vrs.cert.roskazna.ru/" class="und"><span>Закрытая часть</span></a></li>
							</div>
							<li><a href="https://dsp.ld.roskazna.ru" class="und"><span>Ландокс ДСП</span></a></li>
							<li><a href="https://aokz.cert.roskazna.ru" class="und"><span>АОКЗ</span></a></li>
							<!--<li><a href="http://aksiok-webtest.fsfk.local:8009/" class="und"><span>Сайт ППИ (тестирование Aksiok)</span></a></li>-->
							<!--<li><a href="http://10.132.3.26:81/sd/Account/Login" class="und"><span>Система сбора произвольных данных</span></a></li>-->
							<!--<li><a href="http://10.132.3.25/ias-spto" class="und"><span>ИАП для просмотра данных</span></a></li>-->
							<li><a href="http://10.141.145.22:8080/gisgmp/" class="und"><span>ГИС ГМП</span></a></li>
							<li><a href="https://armgisgmp.cert.roskazna.ru" class="und"><span>ГИС ГМП (новая)</span></a></li>
							
							<li id="skiao" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>СКИАО</span></li>
							<div id="skiao_block" style="margin-left:1em; display:none">
							<li><a href="https://skiao.cert.roskazna.ru" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>СКИАО</span></a></li>
							<li><a href="https://skiao.cert.roskazna.ru/pravo/" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>СКИАО Правовая работа</span></a></li>
							<li><a href="https://eoi.cert.roskazna.ru/skiao/sd/Monitoring" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>Мониторинг</span></a></li>
							<li><a href="https://eoi.cert.roskazna.ru/skiao/ggs" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>Показатели ГГС</span></a></li>
							<li><a href="https://skiao.cert.roskazna.ru/sd/RunGatherTofk" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>ПСПД</span></a></li>
							</div>
							
							<!--<li><a href="https://skiao.cert.roskazna.ru" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>СКИАО</span></a></li>-->

							<!--<li><a href="http://10.132.3.26:81/SKIAO/" title="Системы комплексного информационно-аналитического обеспечения деятельности органов Федерального казначейства" class="und"><span>СКИАО</span></a></li>-->
							<li><a href="http://10.128.1.220:8889/sufdclient/index.zul" class="und"><span>Свод показателей БО по СГ</span></a></li>
							<li><a href="http://10.128.1.206:83" class="und"><span>Свод-СМАРТ</span></a></li>
							<li><a href="https://eb.cert.roskazna.ru/PFKVGFK/application" class="und"><span>АСП-Ревизор</span></a></li>
							<li><a href="http://10.133.28.13:58080/analytics" class="und"><span>Система функционального<br />мониторинга ГИС ЖКХ</span></a></li>
							<li><a href="https://lk.zakupki.gov.ru/sso/secure" class="und"><span>Личный кабинет органа контроля</span></a></li>
							<!--<li><a href="http://lk.budget.gov.ru/udu-webcenter" class="und"><span>Электронный бюджет</span></a></li>
							<li><a href="http://lk.budget.gov.ru/" class="und"><span>Электронный бюджет-2001</span></a></li>
							<li><a href="http://lk2012.budget.gov.ru/" class="und"><span>Электронный бюджет-2012</span></a></li>-->
							<li id="ebd" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>Электронный бюджет</span></li>							
							<div id="ebd_block" style="margin-left:1em; display:none">
							<!--<li><a href="http://lk.budget.gov.ru/" class="und"><span>Электронный бюджет-2001</span></a></li>-->
							<!--<li><a href="https://lk.budget.gov.ru/" class="und"><span>Электронный бюджет</span></a></li>-->
							<li><a href="https://eb.cert.roskazna.ru/" class="und"><span>Электронный бюджет через ПОИБ СОБИ</span></a></li>
							<li><a href="https://eb.cert.roskazna.ru/epbs/" class="und"><span>Электронный бюджет ЕПБС</span></a></li>
							<!--<li><a href="https://lk.budget.gov.ru/etd" class="und"><span>Электронный бюджет 2</span></a></li>-->
							<!--<li><a href="https://lk2012.budget.gov.ru/" class="und"><span>Электронный бюджет (2012)</span></a></li>-->
							<!--<li><a href="https://lk-skp-gr.budget.gov.ru/etd/" class="und"><span>Электронный бюджет (тестовый)</span></a></li>-->
							<!--<li><a href="https://ebskpgr.cert-dev.roskazna.ru/" class="und"><span>Электронный бюджет ПОИБ СОБИ (тестовый)</span></a></li>-->
							<li><a href="https://ebzoggr.cert-test.roskazna.ru/" class="und"><span>Электронный бюджет (тестовый)</span></a></li>
								<li id="ebm" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>Модули</span></li>
								<div id="ebm_block" style="margin-left:1em; display:none">
								<li><a href="https://eb.cert.roskazna.ru/EXP/" class="und"><span>ПУР</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/EXP05FB/" class="und"><span>ПУР&nbsp;05</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/INC/" class="und"><span>АУ&nbsp;БУ</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/ARP/" class="und"><span>ПУиО</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/TSE/" class="und"><span>ПУР&nbsp;КС</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/REF/" class="und"><span>НСИ</span></a></li>
								<li><a href="https://eb.cert.roskazna.ru/FAMABS/" class="und"><span>ПУДС</span></a></li>
								<li><a href="https://rkkp.cert.roskazna.ru/" class="und"><span>ПУДС&nbsp;касплан</span></a></li>
								<li><a href="https://pfk-ap.cert.roskazna.ru/" class="und"><span>ПФК – Аналитические полномочия</span></a></li>
								<!--<li><a href="https://eb.cert.roskazna.ru/piao" class="und"><span>ПИАО</span></a></li>-->
								</div>
							<!--<li><a href="http://lk.budget.gov.ru/piao/" class="und"><span>ПИАО-2001</span></a></li>-->
							<li><a href="https://eb.cert.roskazna.ru/piao/" class="und"><span>ПИАО</span></a></li>
							<li><a href="http://puds.budget.gov.ru/OA_HTML/AppsLogin" class="und"><span>ПУДС ОЕБС</span></a></li>
							<li><a href="http://lk.budget.gov.ru/dcs" class="und"><span>Подсистема "КУБ"</span></a></li>
							<!--<li><a href="https://rkkp.cert.roskazna.ru" class="und"><span>ПУДС КасПлан</span></a></li>-->
							<li><a href="https://eb.cert.roskazna.ru/PFKAP/application" class="und"><span>ПФК – Аналитические полномочия</span></a></li>
							</div>						
							<!--<li><a href="https://buh.budget.gov.ru/buh" class="und"><span>ЭБ 1С</span></a></li>
							<li><a href="https://buh2001.budget.gov.ru/buh2001/" class="und"><span>1С ГОСТ 2001</span></a></li>
							<li><a href="https://buh2012.budget.gov.ru/buh2012/" class="und"><span>1С ГОСТ 2012</span></a></li>-->
							<li id="b1c" style="background:url(img/m_cfolder1.png) left top no-repeat; cursor:url(img/link.cur), pointer"><span>1С Бухгалтерия</span></li>
							<div id="b1c_block" style="margin-left:1em; display:none">
							<!--<li><a href="https://buh2001.budget.gov.ru/buh2001/" class="und"><span>1С ГОСТ 2001</span></a></li>-->
							<li><a href="https://buh2012.budget.gov.ru/buh2012/" class="und"><span>1С ГОСТ 2012</span></a></li>
							<!--<li><a href="https://buh2001.budget.gov.ru/buh2001/vis_akbu/1455?oida-" class="und"><span>1С МАУ 2001</span></a></li>-->
							<li><a href="https://buh2012.budget.gov.ru/buh2012/vis_akbu/1455/ru_RU/" class="und"><span>1С МАУ 2012</span></a></li>
							</div>
							<!--<li><a href="http://10.95.3.68" class="und"><span>Информационно-образовательный<br />портал Казначейства России</span></a></li>-->
							<li><a href="https://10.141.129.6/#/signin" class="und"><span>ПУДС КБРН</span></a></li>
							<li><a href="https://peo.portal-fk.fsfk.local" class="und"><span>Электронное обучение</span></a></li>
							<li><a href="http://10.128.1.215/bmskp/index.php" class="und"><span>Бюджетный мониторинг</span></a></li>
							<!--<li><a href=" http://s9500ppmdweb01" class="und"><span>ППМД ФК</span></a></li>-->
							<li><a href="https://ppmd.fsfk.local/" class="und"><span>ППМД СУЭ ФК </span></a></li>
							<li><a href="https://sobi.esia.roskazna.ru" class="und"><span>ПОИБ СОБИ ФК</span></a></li>
							<li><a href="https://sobi.cert-dev.roskazna.ru" class="und"><span>ПОИБ СОБИ ФК (тест)</span></a></li>
							<li><a href="https://lk.finance.gov.ru/" class="und"><span>ЕБП ФИНАНСЫ</span></a></li>
							<li><a href="https://zakupki.gov.ru/epz/main/public/home.html" class="und"><span>ЕИС</span></a></li>
							<li><a href="https://bus.gov.ru/" class="und"><span>ГМУ</span></a></li>
							<li><a href="https://cloud.fsfk.local" class="und"><span>FAP</span></a></li>
							<li><a href="https://s9500infohub02.fsfk.local/armoks/" class="und"><span>АРМ ОКС</span></a></li>
							<li><a href="https://ufkdisk.fsfk.local/" class="und"><span>Облачное хранилище</span></a></li>
							<li><a href="https://agent-navigator.fsfk.local:4000/chat" class="und"><span>Платформа для работы с ИИ</span></a></li>
						</ul>
					</td>
					<td style="width:90%;vertical-align:top;border-style:none;padding-top:10px;">
						<h3 class="hlp">Об Управлении</h3>
						<?php
						//if(date('d.m')=='08.12') {print "<img src=\"img/kaznacheystvo_812.jpg\" alt=\"День создания ФК\" width=\"400\" height=\"294\" hspace=\"5\" vspace=\"5\" align=\"center\" /><p></p>\n";}
						if((date('d.m')=='08.12')&& (access_pm($db)<=3)) {print "<img src=\"img/30_tofk-002.png\" alt=\"День создания ФК\" width=\"493\" height=\"357\" hspace=\"5\" vspace=\"5\" align=\"center\" /><p></p>\n";}
						if((date('d.m')=='22.02')&& (access_pm($db)<=3)) {print "<img src=\"img/23_230221.jpg\" alt=\"С Днем Защитника Отечества\" width=\"512\" height=\"320\" hspace=\"5\" vspace=\"5\" align=\"center\" /><p></p>\n";}
						//if(date('d.m')=='05.03') {print "<div id=\"mart\" style=\"position:absolute; float:left; top:120px; z-index:100; cursor:url(img/link.cur), pointer;\"><img src=\"img/8_2018.jpg\" alt=\"С праздником 8-го марта!\" style=\"margin-left:50px;\" width=\"750\" height=\"643\" hspace=\"5\" vspace=\"5\" align=\"center\" /></div>\n";}
						if((date('d.m')=='05.03')&& (access_pm($db)<=3)) {print "<div id=\"mart\" style=\"position:absolute; float:left; top:120px; z-index:100; cursor:url(img/link.cur), pointer;\"><img src=\"img/8_2022.jpg\" alt=\"С праздником 8-го марта!\" style=\"margin-left:50px;\" width=\"650\" height=\"650\" hspace=\"5\" vspace=\"5\" align=\"center\" /></div>\n";}
						if((date('d.m')=='30.12') && (access_pm($db)<=3)) {print "<div id=\"mart\" style=\"position:absolute; float:left; top:120px; z-index:100; cursor:url(img/link.cur), pointer;\"><img src=\"img/photo_2022-12-29_17-23-18.jpg\" alt=\"С Новым Годом!\" style=\"margin-left:50px;\" width=\"700\" height=\"700\" hspace=\"5\" vspace=\"5\" align=\"center\" /></div>\n";}
						if((date('d.m')=='23.10') && (access_pm($db)<=3)) {print "<div id=\"mart\" style=\"position:absolute; float:left; top:120px; z-index:100; cursor:url(img/link.cur), pointer;\"><img src=\"img/Picsart_23-10-20_11-11-13-005.jpg\" alt=\"100 лет контрольно-ревизионных органов\" style=\"margin-left:50px;\" width=\"700\" height=\"393\" hspace=\"5\" vspace=\"5\" align=\"center\" /></div>\n";}
						?>
						<img src="img/ufk_building1.jpg" alt="Здание УФК по Калужской области" width="200" height="150" hspace="5" vspace="5" align="left" />
						<p class="n_txt">Указом Президента Российской Федерации №&thinsp;1556 от 8&thinsp;декабря&thinsp;1992&thinsp;года и Постановлением Правительства России №&thinsp;864 от 27&thinsp;августа&thinsp;1993 года в составе Министерства финансов РФ было создано Федеральное казначейство. Органы федерального казначейства как система были созданы для того, чтобы управлять денежными средствами на счетах казначейства и осуществлять предварительный и текущий контроль за целевым использованием средств бюджета, начиная с доведения лимитов бюджетных обязательств и заканчивая кассовым расходом.</p>
						<p class="n_txt">Управление федерального казначейства Министерства финансов Российской Федерации по Калужской области было создано Приказом Министерства финансов Российской Федерации №&thinsp;341 от 5&thinsp;апреля&thinsp;1994&thinsp;года. Руководителем Управления был утвержден Виктор&thinsp;Хрисанфович&thinsp;Квасов.</p>
						<p class="n_txt">С 20 декабря 2005 года по 15 июня 2006 года, после перевода Квасова В.Х. на другую работу, обязанности Руководителя Управления исполняла Татьяна Федоровна Демичева.</p>
						<p class="n_txt">С 16 июня 2006 года Руководителем Управления Федерального казначейства по Калужской области назначен Николай Петрович Хвостенко.</p>
						<p class="n_txt">Управление Федерального казначейства по Калужской области является составной частью единой централизованной системы органов федерального казначейства.</p>
						<p class="n_txt">Управление Федерального казначейства по Калужской области расположено по адресу: 248009, г.&thinsp;Калуга, Грабцевское шоссе, д.&thinsp;39</p>
						<h3 class="hlp" style="font-style:italic;"><a href="data/svl_22_3.docx" class="und"><span>Летопись Управления</span></a></h3>
						<!--
						<h3 class="hlp">Организационная структура Управления</h3>
						<img src="img/ufk_structure4_v20220124.png" alt="Организационная структура УФК по Калужской области" width="800" height="465" hspace="5" vspace="5" style="margin:5px auto;" />
						<p class="n_txt"></p>
						-->
						<h3 class="hlp">Доска почета Управления</h3>
						<!--
						<div style="float:left;margin-left:10px;width:107;height:142;background:url(../img/frame.png) left top no-repeat;z-index:5"><img src="img/person/boyarova.jpg" height="142" width="107" align="left" style="z-index:3" /></div>
						-->
						<?php
						//$t1=microtime(true);
						$r_m=$db->query('SELECT MAX(`fav`) FROM `ufk`');
						$max_fav=$r_m->fetch_array();
						$max_fav=$max_fav[0];
						$r_m->free();
						//-------
						$q_h="SELECT `Name`, `Region`, `ofk`, `FamilyName`, `FirstName`, `SecondName`, `Dep_Name`, `Staff_Name`, `photo` FROM `ufk` INNER JOIN `departament` ON (`ufk`.`id_d`=`departament`.`ID_D`) INNER JOIN `staff` ON (`ufk`.`id_s`=`staff`.`ID_S`) LEFT JOIN `ofk` ON (`ufk`.`ofk`=`ofk`.`ID_O`) WHERE `fav`=".$max_fav." ORDER BY `FamilyName`, `FirstName`";
						$r_h=$db->query($q_h);
						for($i=0; $i < $r_h->num_rows; $i++)
						{
							$rec=$r_h->fetch_array();
							if(($i % 2) == 0)
							{
								print "<div style=\"width:100%;\">";
								print "<div style=\"width:45%; float:left;\">";
								print "<div style=\"width:100%; height:144px; display: flex; justify-content: center;\">";
								print "<img src=\"img/frame.png\" height=\"142\" width=\"107\" alt=\"".$rec['FamilyName']." ".$rec['FirstName']." ".$rec['SecondName']."\" align=\"left\" style=\"background:url(./img/person/".$rec['photo'].") left top no-repeat\" />";
								print "</div>";
								print "<div style=\"width:100%;height:100px;text-align:ceter\">";
								print "<p class=\"m1_txt\"><b>".$rec['FamilyName']."</b> ".$rec['FirstName']." ".$rec['SecondName']."</p>\n<p class=\"m_txt\">".$rec['Staff_Name']."</p>\n";
								if($rec['ofk']==3700) { print "<p class=\"m_txt\">".$rec['Dep_Name']."</p>"; }
								else
								{
									print "<p class=\"m_txt\">Отдел №&thinsp;".ltrim(substr($rec['ofk'],2,2),'0')." (по ";
									if($rec['Region']!='району') { print $rec['Name'].")</p>\n"; }
									else { print $rec['Name']." району)</p>\n"; }
								}
								print "</div>";
								print "</div>";
							}
							else
							{
								print "<div style=\"width:45%; float:right;\">";
								print "<div style=\"width:100%;height:144px; display: flex; justify-content: center;\">";
								print "<img src=\"img/frame.png\" height=\"142\" width=\"107\" alt=\"".$rec['FamilyName']." ".$rec['FirstName']." ".$rec['SecondName']."\" align=\"left\" style=\"background:url(./img/person/".$rec['photo'].") left top no-repeat\" />";
								print "</div>";
								print "<div style=\"width:100%;height:100px\">";
								print "<p class=\"m1_txt\"><b>".$rec['FamilyName']."</b> ".$rec['FirstName']." ".$rec['SecondName']."</p>\n<p class=\"m_txt\">".$rec['Staff_Name']."</p>\n";
								if($rec['ofk']==3700) { print "<p class=\"m_txt\">".$rec['Dep_Name']."</p>"; }
								else
								{
									print "<p class=\"m_txt\">Отдел №&thinsp;".ltrim(substr($rec['ofk'],2,2),'0')." (по ";
									if($rec['Region']!='району') { print $rec['Name'].")</p>\n"; }
									else { print $rec['Name']." району)</p>\n"; }
								}
								print "</div>";
								print "</div>";
								print "</div>";
							}
						}
						//-------
						$r_h->free();
						//$t2=microtime(true);
						?>
						<p class="n_txt"></p>
					<?php
					//$t3=microtime(true);
					?>
					</td>
				</tr>
			</table>
		</div>

		<script>
			function checkAuth(url) {
				// Простая проверка: если в заголовках нет AUTH_USER — значит, аутентификация не пройдена
				fetch('check_auth.php')
					.then(response => response.json())
					.then(data => {
						if (data.authenticated) {
							window.location.href = url;
						} else {
							alert(
								'Не удалось определить вашу учётную запись.\n\n' +
								'Возможно, вы используете Firefox.\n\n' +
								'Рекомендуется использовать Google Chrome или Яндекс.Браузер.');
						}
					})
					.catch(() => {
						alert('Ошибка проверки доступа. Используйте Chromium или Yandex браузеры.');
					});
			}
		</script>

		<?php
		footer($access,$db);
		//print "<p>Time1=".(1000*($t2-$t1))." ms, Time2=".(1000*($t3-$t2))."</p>";
		?>
	</body>
</html>