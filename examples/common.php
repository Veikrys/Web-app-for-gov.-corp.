<?php
include 'ofk_db.php';
//setlocale(LC_ALL, "nb_NO.utf8");
setlocale(LC_ALL, 'ru_RU.CP1251', 'ru_RU.cp1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
//setlocale(LC_ALL, "ru_RU.utf8");
//print setlocale(LC_ALL, '0');
//database connect
$db=new mysqli('p:localhost',$db_user,$db_password,$db_name);
$db->query('SET NAMES cp1251');
//"grey" numbers: 1 - visible, 0 - hidden
$grey_number=0;
//
function get_browser_db($database)
{
	$u_a=$_SERVER['HTTP_USER_AGENT'];
	$uaps=preg_split('/([^[:alpha:]*])+/', $u_a, NULL, PREG_SPLIT_NO_EMPTY);
	$n=count($uaps);
	if($n > 10) {$s=10;}
	else {$s=$n;}
	while($s > 0)
	{
		$uap='';
		for($j=0; $j < $s; $j++)
		{
			//$uap.='%'.$uaps[$j];
			if(strlen($uaps[$j])>2){ $uap.='%'.$uaps[$j]; }
		}
		$uap.='%';
		$q_n="SELECT COUNT(*) AS n FROM `browscap` WHERE `UserAgent` LIKE '".$uap."'";
		$r_n=$database->query($q_n);
		$cnt=$r_n->fetch_array();
		$cnt=$cnt['n'];
		$r_n->free();
		if($cnt > 0) break;
		$s--;
	}
	$q_s="SELECT `ID_BR`, `UserAgent` FROM `browscap` WHERE `UserAgent` LIKE '".$uap."'";
	$r_s=$database->query($q_s);
	$n_s=$r_s->num_rows;
	$br_id=0;
	$br_ln=0;
	for($i=0; $i < $n_s; $i++)
	{
		$rec=$r_s->fetch_array();
		$keyEreg='%^'.str_replace(array('\\','.','?','*','^','$','[',']','|','(',')','+','{','}','%'), array('\\\\','\\.','.','.*','\\^','\\$','\\[','\\]','\\|','\\(','\\)','\\+','\\{','\\}','\\%'),  $rec['UserAgent']).'$%i';
		if (preg_match($keyEreg, $u_a))
		{
			//$br_id=$rec['ID_BR'];
			//break;
			if($br_ln < strlen($rec['UserAgent']))
			{
				$br_id=$rec['ID_BR'];
				$br_ln=strlen($rec['UserAgent']);
			}
		}
	}
	$r_s->free();
	$q_b="SELECT `UserAgent`, `Comment`, `Browser`, `Version`, `MajorVer`, `MinorVer`, `Platform`, `Platform_Version`, `Alpha`, `Beta`, `Win16`, `Win32`, `Win64`, `Frames`, `IFrames`, `Tables`, `Cookies`, `BackgroundSounds`, `JavaScript`, `VBScript`, `JavaApplets`, `ActiveXControls`, `isMobileDevice`, `isSyndicationReader`, `Crawler`, `CssVersion`, `AolVersion` FROM `browscap` WHERE `ID_BR`=".$br_id;
	$r_b=$database->query($q_b);
	return $r_b->fetch_array();
}
//
function ip_access($database)
{
	$res=0;
	$policy=1;//0 - deny anonimous access, 1 - allow anonimous access
	//$ip=ip2long($_SERVER['REMOTE_ADDR']);
	$ip=sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
	$m_ip=md5($ip);
	//print $_SERVER['REMOTE_ADDR'].', '.$ip.', '.$m_ip;
	$r1=$database->query("SELECT `level`,`workstation` FROM `access_list` WHERE `m_ip`=\"$m_ip\"");
	/*
	if($r1!=FALSE)
	{
		print "<p>r1-true</p>";
		$access_desc=$r1->fetch_array();
		print "<p>".$access_desc['level']."</p>";
		$res+=10000*$access_desc['level'];
	}
	*/
	if($r1->num_rows==1)
	{
		$access_desc=$r1->fetch_array();
		$res+=10000*$access_desc['level'];
	}
	else
	{
		if($policy==1) { $res=10000; }
	}
	$r1->free();
	/*
	if(($ip >= 170196993)&&($ip <= 170206463))//ufk - 3700 or ofk - 37xx
	*/
	if(($ip >= 170196993)&&($ip <= 170262527))//ufk - 3700 or ofk - 37xx
	{
		$res+=3700;
		$start_ip	=170199809;
		$end_ip	=170200063;
		for($i=1; $i < 27; $i++)
		{
			if(($ip >= $start_ip)&&($ip <= $end_ip))
			{
				$res+=$i;
				break;
			}
			$start_ip+=256;
			$end_ip=$start_ip+254;
		}
	}
	else { $res+=9999; }
	return $res;
}
//
function ip_group($database)
{
	$res=0;
	//$ip=ip2long($_SERVER['REMOTE_ADDR']);
	$ip=sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
	$m_ip=md5($ip);
	$r1=$database->query("SELECT `access_group`.`ID_AG` AS a_g FROM `access_group` INNER JOIN `access_gl` ON (`access_group`.`ID_AG`=`access_gl`.`id_ag`) INNER JOIN `access_list` ON (`access_list`.`ID_A`=`access_gl`.`id_al`)WHERE `access_list`.`m_ip`='".$m_ip."'");
	if($r1->num_rows > 0)
	{
		$grp=$r1->fetch_array();
		$res=$grp['a_g'];
	}
	return $res;
}
//
//
function add_rec($accss,$database)
{
	//Add info
	//$ip			=ip2long($_SERVER['REMOTE_ADDR']);
	//$page		=ltrim($_SERVER['PHP_SELF'],'/');
	$ip=sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
	$page		=basename($_SERVER['PHP_SELF']);
	$time		=strftime("%Y-%m-%d %H:%M:%S",$_SERVER['REQUEST_TIME']);
	if(array_key_exists('REMOTE_HOST',$_SERVER)){ $host			=$_SERVER['REMOTE_HOST']; }
	else{ $host=""; }
	if(array_key_exists('HTTP_REFERER',$_SERVER)){ $referer	=$_SERVER['HTTP_REFERER']; }
	else { $referer=""; }
	/*
	$br=get_browser(NULL,true);
	$br_name	=$br['browser'];
	$br_ver		=$br['version'];
	$br_os			=$br['platform'];
	*/
	$br=get_browser_db($database);
	$br_name	=$br['Browser'];
	$br_ver		=$br['Version'];
	$br_os			=$br['Platform'];
	if((($br_name=='')||($br_name=='Default Browser'))&&(array_key_exists('HTTP_USER_AGENT', $_SERVER)))
	{
		$ua=trim($_SERVER['HTTP_USER_AGENT']);
		$q="INSERT INTO `access`(`client_ip`, `page_name`, `request_time`, `host_name`, `referer`, `browser`, `version`, `platform`,`level_access`,`user_agent`) VALUES (";
		$q.="\"".$ip."\",\"".$page."\",\"".$time."\",\"".$host."\",\"".$referer."\",\"".$br_name."\",\"".$br_ver."\",\"".$br_os."\",".$accss.",\"".$ua."\")";
	}
	else
	{
		$q="INSERT INTO `access`(`client_ip`, `page_name`, `request_time`, `host_name`, `referer`, `browser`, `version`, `platform`,`level_access`) VALUES (";
		$q.="\"".$ip."\",\"".$page."\",\"".$time."\",\"".$host."\",\"".$referer."\",\"".$br_name."\",\"".$br_ver."\",\"".$br_os."\",".$accss.")";
	}
	$database->query($q);
}
function page_access($accss,$database)
{
	//$page=substr($_SERVER['PHP_SELF'],1);
	$page=basename($_SERVER['PHP_SELF']);
	$q="SELECT `file_access`, `md5_check`, `md5_file` FROM `pages` WHERE `file_name`='".$page."'";
	$r=$database->query($q);
	if(($r->num_rows)==1)
	{
		$rec=$r->fetch_array();
		if((10000*$rec['file_access'])<=$accss)
		{
			//check access "ofk-page" list from `pages_ofk` table in database 
			if($rec['md5_check'])
			{
				if(md5_file($page)!=$rec['md5_file']){header("Location: http://10.37.1.41/deny.php?t=1");}	//hash control fail				
			}
		}
		else{header("Location: http://10.37.1.41/deny.php");}																		//access level fail
	}
	else{header("Location: http://10.37.1.41/deny.php?t=2");}																	//database record not exists
	$r->free();
}
function print_header($accss,$database)
{
	//$f_name=ltrim($_SERVER['PHP_SELF'],'/');
	$f_name=basename($_SERVER['PHP_SELF']);
	$menu_item=0;
	switch($f_name)
	{
		case "index.php":
			$menu_item=0;
			break;
		case "error.php":
			$menu_item=0;
			break;
		case "deny.php":
			$menu_item=0;
			break;
		case "statistics.php":
			$menu_item=0;
			break;
		case "ofk.php":
			$menu_item=1;
			break;
		case "ubp.php":
			$menu_item=2;
			break;
		case "docs.php":
			$menu_item=3;
			break;
		case "budget.php":
			$menu_item=3;
			break;
		case "search.php":
			$menu_item=3;
			break;
		case "certif.php":
			$menu_item=3;
			break;
		case "arms.php":
			$menu_item=3;
			break;
		case "calendar.php":
			$menu_item=3;
			break;
		case "phonetraf.php":
			$menu_item=3;
			break;
		case "admin.php":
			$menu_item=3;
			break;
		case "tarusap.php":
			$menu_item=4;
			break;
		case "tarusau.php":
			$menu_item=5;
			break;
		case "help.php":
			$menu_item=6;
			break;
		case "main.php":
			$menu_item=7;
			break;
		case "manuals.php":
			$menu_item=3;
			break;
		case "library.php":
			$menu_item=3;
			break;
		case "depkadr.php":
			$menu_item=3;
			break;
		case "depadm.php":
			$menu_item=3;
			break;
		case "depsec.php":
			$menu_item=3;
			break;
		case "konkurs.php":
			$menu_item=3;
			break;
		case "cafk.php":
			$menu_item=0;
			break;
		case "depaud.php":
			$menu_item=3;
			break;
	}
	print "<div id=\"header\">\n";
	//print "<img src=\"img/logoFK.png\" height=\"110\" width=\"99\" alt=\"Федеральное казначейство\" style=\"float:left;margin-bottom:5px;margin-top:5px;margin-left:10px\" />\n";
	print "<img src=\"img/logoFK1.png\" height=\"120\" width=\"107\" alt=\"Федеральное казначейство\" style=\"float:left;margin-bottom:1px;margin-top:1px;margin-left:6px\" />\n";
	//print "<img src=\"img/logo_ofk2.png\" alt=\"ОФК по Тарусскому району\" height=\"46\" width=\"500\" style=\"margin-top:22px;margin-bottom:0px;margin-left:3px\" />\n";
	print "<img src=\"img/logo_ofk4.png\" alt=\"ОФК по Тарусскому району\" height=\"68\" width=\"534\" style=\"margin-top:16px;margin-bottom:0px;margin-left:3px\" />\n";
	print "<span id=\"date\">";
	print strftime("%d&nbsp;");
	$month=lcfirst(strftime("%B"));
	print "<i>".preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month)."</i>";
	print strftime("&nbsp;%Y&nbsp;г,&nbsp;<i>%A.</i>\n");
	print "</span>\n";
	print "<span id=\"time\">";
	print strftime("<span id=\"time_h\">%H</span>&nbsp;<i>ч.</i>&nbsp;<span id=\"time_m\">%M</span>&nbsp;<i>мин.</i>\n");
	print "</span>\n";
	print "</div>\n";
	//
	print "<div id=\"menu\">\n";
	print "<ul class=\"menu\">\n";
	if($menu_item==7) {print "<li id=\"fst\" class=\"current\"><a href=\"https://s3700web01.fsfk.local/main.php\"><span>Главная</span></a></li>\n";}
	else 							{print "<li id=\"fst\"><a href=\"https://s3700web01.fsfk.local/main.php\"><span>Главная</span></a></li>\n";}
	//print "<li id=\"ufk\"><a href=\"index.php\"><span>Управление</span></a></li>\n";
	//print "<li id=\"ufk\"><a href=\"index.php\"><span>Управление</span></a>\n<div>\n<ul>\n";
	//if($menu_item==0) {print "<li id=\"ufk\" class=\"current\"><a href=\"index.php\"><span>Управление</span></a>\n<div>\n<ul>\n";}
	//else							{print "<li id=\"ufk\"><a href=\"index.php\"><span>Управление</span></a>\n<div>\n<ul>\n";}
	//print "<li class=\"jvl1\"><a href=\"index.php\"><span>УФК по Калужской области&thinsp;(3700)</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"index.php?ufk=96\"><span>Межрегиональное операционное УФК&thinsp;(9600)</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"cafk.php\"><span>Центральный аппарат ФК&thinsp;(9500)</span></a></li>\n";
	//print "</ul>\n</div>\n</li>\n";
	if($accss>89999)
	{
		if($menu_item==0) {print "<li id=\"ufk\" class=\"current\"><a href=\"https://s3700web01.fsfk.local/index.php\"><span>Управление</span></a></li>\n";}
		else							{print "<li id=\"ufk\"><a href=\"https://s3700web01.fsfk.local/index.php\"><span>Управление</span></a></li>\n";}
	}
	/*
	if($menu_item==1)	{print "<li id=\"ofk\" class=\"current\"><a href=\"ofk.php\" class=\"parent\"><span>Отделения</span></a>\n";}
	else								{print "<li id=\"ofk\"><a href=\"ofk.php\" class=\"parent\"><span>Отделения</span></a>\n";}
	print "<div style=\"width:400px;background: url(../img/submenu-top1_v20210604.png) no-repeat 0px 0;\">\n<ul style=\"width:400px;background: url(../img/submenu-bottom1_v20210604.png) no-repeat 0px bottom;\">\n";
	print "<li class=\"jvl1\"><a href=\"ofk.php?ofk=0\"><span>Все территориальные Отделы</span></a></li>\n";
	$r1=$database->query("SELECT `ID_O`, `Name`,`Region`, `Num` FROM `ofk` WHERE `ID_O`>3700 AND `ID_O`<=3726 AND `ID_O` NOT IN (3708, 3712, 3714, 3719, 3720, 3721, 3722, 3723) AND `Linked` IS NULL ORDER BY `ID_O`");
	$n1=$r1->num_rows;
	for($j=1; $j <= $n1; $j++)
	{
		$record=$r1->fetch_array();
		$r2=$database->query("SELECT `ID_O`, substr(`City`, 4) AS S_Name FROM `ofk` WHERE `Linked`=".$record['ID_O']." ORDER BY `ID_O`");
		$add_tofk=NULL;
		$n2=$r2->num_rows;
		for($k=1; $k <= $n2; $k++)
		{
			$rec2=$r2->fetch_array();
			$add_tofk.=$rec2['ID_O'].", "; 
		}
		$r2->free();
		//$add_tofk="(".substr($add_tofk, 0, -2).")";
		$add_tofk=substr($add_tofk, 0, -2);
		print "<li class=\"jvl1\"><a href=\"ofk.php?ofk=".$record['Num']."\"><span>";
		//$ofk_code=3700+$record['Num'];
		$ofk_code=$record['ID_O'];
		//print 'Отдел&thinsp;№ '.$j.' по '.$record['Name'];
		print 'Отдел&thinsp;№ '.$record['Num'].' по '.$record['Name'];
		if($record['Region']!=NULL) { print " ".$record['Region']; }
	        //print "&thinsp;".$add_tofk."&thinsp;($ofk_code)</span></a></li>\n";
		if($add_tofk==NULL)
		{
			print "&thinsp;(".$ofk_code.")</span></a></li>\n";
		}
		else
		{
			print "&thinsp;(".$ofk_code.", ".$add_tofk.")</span></a></li>\n";
		}
	}
	$r1->free();
	print "</ul>\n</div>\n</li>\n";
	*/
	/*
	if(($accss > 79999)||($accss%10000)==3718)
	{
		if($menu_item==2)	{print "<li id=\"ubp\" class=\"current\"><a href=\"ubp.php\"><span>Клиенты</span></a></li>\n";}
		else								{print "<li id=\"ubp\"><a href=\"ubp.php\"><span>Клиенты</span></a></li>\n";}
	}
	*/
	/*
	if($menu_item==2)	{print "<li id=\"ubp\" class=\"current\"><a href=\"ubp.php\"><span>Клиенты</span></a></li>\n";}
	else								{print "<li id=\"ubp\"><a href=\"ubp.php\"><span>Клиенты</span></a></li>\n";}
	*/
	if($menu_item==3)	{print "<li id=\"doc\" class=\"current\"><a href=\"https://s3700web01.fsfk.local/docs.php\" class=\"parent\"><span>Ресурсы</span></a>\n";}
	else								{print "<li id=\"doc\"><a href=\"https://s3700web01.fsfk.local/docs.php\" class=\"parent\"><span>Ресурсы</span></a>\n";}
	print "<div>\n<ul>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/docs.php?doc=0\"><span>Правила поведения госслужащих</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"manuals.php\"><span>Руководства и инструкции</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/manuals.php\"><span>Отдел информационных систем</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"library.php\"><span>Библиотека казначея</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/depkadr.php\"><span>Отдел ГГСиК</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/depadm.php\"><span>Административно-финансовый отдел</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/depsec.php\"><span>Отдел РСиБИ</span></a></li>\n"; 
	//print "<li class=\"jvl1\"><a href=\"depaud.php\"><span>Отдел ВКиА</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/konkurs.php\"><span>Фотоконкурсы Управления</span></a></li>\n"; 
	//print "<li class=\"jvl1\"><a href=\"docs.php?doc=1\"><span>Функциональные коды АТС</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"docs.php?doc=3\"><span>Коды программы АСФК</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"budget.php\"><span>Справочник бюджетов</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/search.php\"><span>Поиск по телефонной книге</span></a></li>\n";
	//
	if(($accss > 59999)||(($accss%10000)==3718)||(($accss%10000)==3720)||(($accss%10000)==3722))
	{
		print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/certif.php\"><span>Данные по сертификатам ЭЦП</span></a></li>\n";
	}
	//
	//print "<li class=\"jvl1\"><a href=\"certif.php\"><span>Данные по сертификатам ЭЦП</span></a></li>\n";
	//print "<li class=\"jvl1\"><a href=\"arms.php\"><span>Технические паспорта</span></a></li>\n";
	print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/calendar.php\"><span>Календарь</span></a></li>\n";
	if($accss>79999)
	{
		print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/phonetraf.php\"><span>Отчеты по телефонным переговорам</span></a></li>\n";
	}
	if($accss>89999)
	{
		print "<li class=\"jvl1\"><a href=\"https://s3700web01.fsfk.local/admin.php\"><span>Интерфейс администратора</span></a></li>\n";
	}
	//External links
	print "<li class=\"jvl2\"><a href=\"http://10.37.1.44/\"><span>Форум УФК по Калужской области</span></a></li>\n";
	print "<li class=\"jvl2\"><a href=\"http://a3700w12.ufk37.roskazna.local:7797/\"><span>АСФК</span></a></li>\n";
	//if(($accss > 89999)||(($accss%10000) < 3701)||(($accss%10000) > 3726)){ print "<li class=\"jvl2\"><a href=\"http://a3700p02.ufk37.roskazna.local:8010\"><span>Тестовая база АСФК</span></a></li>\n"; }
	print "<li class=\"jvl2\"><a href=\"http://10.37.200.12:28081\"><span>СУФД</span></a></li>\n";
	//print "<li class=\"jvl2\"><a href=\"http://10.37.1.61/Exchange/\"><span>Почта Exchange</span></a></li>\n";
	print "<li class=\"jvl2\"><a href=\"http://10.128.50.12/user/index.do\"><span>ПУПЭ Портал самообслуживания</span></a></li>\n";
	//print "<li class=\"jvl2\"><a href=\"http://10.37.1.40:8080\"><span>Правовая система &ldquo;Законодательство России&rdquo;</span></a></li>\n";
	//if(($accss > 89999)||(($accss%10000) < 3701)||(($accss%10000) > 3726)){ print "<li class=\"jvl2\"><a href=\"http://10.128.11.12:8008/\"><span>Сервер Aksiok</span></a></li>\n"; }
	/*
	if($accss>89999)
	{
		print "<li class=\"jvl2\"><a href=\"ftp.php\"><span>FTP-сервер Управления</span></a></li>\n";
	}
	*/
	//print "<li class=\"jvl2\"><a href=\"http://10.37.1.62\"><span>Правовая система &ldquo;Гарант&rdquo;</span></a></li>\n";
	print "</ul>\n</div>\n</li>\n";
	if($accss>89999)
	{
		if($menu_item==4)	{print "<li id=\"prs\" class=\"current\"><a href=\"tarusap.php\" class=\"parent\"><span>Частные лица</span></a>\n";}
		else								{print "<li id=\"prs\"><a href=\"tarusap.php\" class=\"parent\"><span>Частные лица</span></a>\n";}
		print "<div>\n<ul>\n";
		for($i=0;$i<=32;$i++)
		{
			if(($i<10)||(($i >10)&&($i<27))||($i >29))
			{
				if($i==0)
				{
					print "<li class=\"jvl1\"><a href=\"tarusap.php?let=33\"><span>Все абоненты";
				}
				else
				{
					$l=pack('c*',$i+191);
					print "<li class=\"jvl1\"><a href=\"tarusap.php?let=$i\"><span>Список абонентов на букву &ldquo;$l&rdquo;";
				}
				print "</span></a></li>\n";
			}
		}
		print "</ul>\n</div>\n</li>\n";
	}
	if(($accss > 79999)||(($accss%10000)==3718))
	{
		if($menu_item==5)	{print "<li id=\"org\" class=\"current\"><a href=\"tarusau.php\"><span>Организации</span></a></li>\n";}
		else								{print "<li id=\"org\"><a href=\"tarusau.php\"><span>Организации</span></a></li>\n";}
	}
	if($menu_item==6)	{print "<li id=\"hlp\" class=\"last current\"><a href=\"https://s3700web01.fsfk.local/help.php\"><span>Справка</span></a></li>\n";}
	else								{print "<li id=\"hlp\" class=\"last\"><a href=\"https://s3700web01.fsfk.local/help.php\"><span>Справка</span></a></li>\n";}
	print "</ul>\n";
	print "</div>\n";
}
function cnt($database)
{
	$q_cnt="SELECT `client_ip`, COUNT(*) FROM `access` WHERE `time`>\"".strftime("%Y-%m-%d")." 00:00:00\" AND `time`<\"".strftime("%Y-%m-%d")." 23:59:59\" GROUP BY `client_ip`";
	$r_cnt=$database->query($q_cnt);
	$n=$r_cnt->num_rows;
	$result=array($n,0,0,0);
	for($i=0; $i < $n; $i++)
	{
		$lst=$r_cnt->fetch_array();
		$result[1]+=$lst[1];
	}
	$r_cnt->free();
	$q_cnt="SELECT `client_ip`, COUNT(*) FROM `access` WHERE `time`>\"".strftime("%Y-%m")."-01 00:00:00\" AND `time`<\"".strftime("%Y-%m-%d")." 23:59:59\" GROUP BY `client_ip`";
	$r_cnt=$database->query($q_cnt);
	$n=$r_cnt->num_rows;
	$result[2]=$n;
	for($i=0; $i < $n; $i++)
	{
		$lst=$r_cnt->fetch_array();
		$result[3]+=$lst[1];
	}
	$r_cnt->free();
	return $result;
}
/*
function footer($accss,$database)
{
	$s_version="P.III.6.II.2";
	$d_c=cnt($database);
	print "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"99%\" class=\"footer\" id=\"layout\" summary=\"\">\n";
	print "<tr><td colspan=\"2\" class=\"footer_1\"></td></tr>\n";
	print "<tr>\n<td rowspan=\"3\" class=\"footer_2\" style=\"text-align:left;padding-top:5px;\">";
	if($accss>49999) { print "<a href=\"statistics.php\"><img src=\"cnt.php?u_d=$d_c[0]&amp;a_d=$d_c[1]&amp;u_m=$d_c[2]&amp;a_m=$d_c[3]\" border=\"0\" alt=\"Статистика\" height=\"30\" width=\"110\" /></a>"; }
	else { print "<img src=\"cnt.php?u_d=$d_c[0]&amp;a_d=$d_c[1]&amp;u_m=$d_c[2]&amp;a_m=$d_c[3]\" border=\"0\" alt=\"Статистика\" height=\"30\" width=\"110\" />"; }
	print "<img src=\"img/valid-xhtml10-blue.png\" border=\"0\" alt=\"Valid XHTML 1.0 by W3.org validator\" height=\"31\" width=\"88\" style=\"margin-left:25px\" />";
	print "<img src=\"img/valid-css2-blue.png\" border=\"0\" alt=\"Valid CSS 2.0 by W3.org validator\" height=\"31\" width=\"88\" style=\"margin-left:2px\" />";
	print "</td><td class=\"footer_2\">Разработка и оформление&thinsp;&ndash;&thinsp;Алексей Никулин</td>\n";
	print "</tr>\n";
	print "<tr><td class=\"footer_3\"><b>Отделение по Тарусскому району УФК по Калужской области,</b> 2010&thinsp;&ndash;&thinsp;2012.</td></tr>\n";
	//print "<tr><td class=\"footer_3\"><b>Отдел № 18 УФК по Калужской области</b></td></tr>\n";
	if((substr($accss,1,4)>3726)||(substr($accss,1,4)<3700)) {print "<tr><td class=\"footer_3\">ofk3718@ufk37.roskazna.local, 0-(3718)-2021</td></tr>\n";}
	else {print "<tr><td class=\"footer_3\">ofk3718@ufk37.roskazna.local, 7-(18)-2021</td></tr>\n";}
	print "<tr><td colspan=\"2\" class=\"footer_4\"><i>Версия</i> $s_version</td></tr>\n";
	
	//print "<tr><td class=\"footer_4\"><i>Версия</i> $s_version</td>";
	//if((substr($accss,1,4)>3726)||(substr($accss,1,4)<3700)) {print "<td class=\"footer_3\">ofk3718@ufk37.roskazna.local, 0-(3718)-2021</td></tr>\n";}
	//else {print "<td class=\"footer_3\">ofk3718@ufk37.roskazna.local, 7-(18)-2021</td></tr>\n";}
	
	print "</table>\n";
	//print "<div id=\"copyright\">Copyright &copy; 2010 <a href=\"http://apycom.com/\">Apycom jQuery Menus</a></div>\n";
}
*/
function footer($accss,$database)
{
	$s_version="P.IV.2.III.0";
	$d_c=cnt($database);
	print "<div id=\"footer\">\n";
	print "<div class=\"ft_1\"></div>\n";
	if($accss>49999) { print "<a href=\"statistics.php\"><img src=\"cnt.php?u_d=$d_c[0]&amp;a_d=$d_c[1]&amp;u_m=$d_c[2]&amp;a_m=$d_c[3]\" border=\"0\" alt=\"Статистика\" height=\"30\" width=\"110\" style=\"padding-left:5px\" /></a>\n"; }
	else { print "<img src=\"cnt.php?u_d=$d_c[0]&amp;a_d=$d_c[1]&amp;u_m=$d_c[2]&amp;a_m=$d_c[3]\" border=\"0\" alt=\"Статистика\" height=\"30\" width=\"110\" style=\"padding-left:5px\" />\n"; }
	print "<img src=\"img/valid-xhtml10-blue.png\" border=\"0\" alt=\"Valid XHTML 1.0 by W3.org validator\" height=\"31\" width=\"88\" style=\"margin-left:25px\" />\n";
	print "<img src=\"img/valid-css2-blue.png\" border=\"0\" alt=\"Valid CSS 2.0 by W3.org validator\" height=\"31\" width=\"88\" style=\"margin-left:2px\" />\n";
	print "<div class=\"ft_2\">\n";
	print "Разработка и оформление&thinsp;&ndash;&thinsp;Алексей Никулин.\n";
	print "<div class=\"ft_3\">\n<b>Отделение по Тарусскому району УФК по Калужской области,</b> 2010&thinsp;&ndash;&thinsp;2012.</div>\n";
	//print "<div class=\"ft_3\">\n<b>Отделение по Тарусскому району УФК по Калужской области,</b> 2010&thinsp;&ndash;&thinsp;2012.&nbsp;<b>УФК&nbsp;по&nbsp;Калужской&nbsp;области (Отдел&nbsp;№&nbsp;18)</b>,&nbsp;2012&thinsp;&ndash;&thinsp;2013.</div>\n";
	if((substr($accss,1,4)>3726)||(substr($accss,1,4)<3700)) {print "<div class=\"ft_3\"><a href=\"mailto:NikulinAV@ufk37.roskazna.local\" class=\"und\"><span style=\"color:#8590F0\">NikulinAV@ufk37.roskazna.local</span></a>, 0-(3718)-2021</div>\n";}
	else {print "<div class=\"ft_3\"><a href=\"mailto:NikulinAV@ufk37.roskazna.local\" class=\"und\"><span style=\"color:#8590F0\">NikulinAV@ufk37.roskazna.local</span></a>, 7-(18)-2021</div>\n";}
	print "</div>\n";
	print "<div class=\"ft_4\"><i>Версия</i> $s_version</div>\n";
	print "</div>\n";
	//
	print "<div id=\"scroll_up\"><img src=\"img/menu_up.png\" height=\"33\" width=\"48\" alt=\"Вверх\" title=\"Вверх\" /></div>";
}

function removews($buffer)
{
	$ends   = array("\r\n", "\n", "\r", "\t");
	return (str_replace($ends, '', $buffer));
}

function format_phone($ph)
{
	$phone=NULL;
	switch(strlen($ph))
	{
		case 5:
			$phone=substr($ph,0,1)."-".substr($ph,1,2)."-".substr($ph,3,2);
			break;
		case 6:
			$phone=substr($ph,0,2)."-".substr($ph,2,2)."-".substr($ph,4,2);
			break;
		case 7:
			$phone=substr($ph,0,3)."-".substr($ph,3,2)."-".substr($ph,5,2);
			break;
	}
	return $phone;
}

function chunk_out($buffer)
{
	$chunk_size=ob_get_length();
	return dechex($chunk_size)."\n".$buffer;
}

function access_pm($database)
{
	$ip=sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
	$cnt_main=0;
	$r1=$database->query("SELECT count(*) AS CNM FROM `access` WHERE `time`>='2023-10-23 07:30:00' AND `client_ip`=".$ip." AND `page_name`='main.php'");
	if($r1->num_rows==1)
	{
		$rec=$r1->fetch_array();
		$cnt_main = $rec['CNM'];
	}
	return $cnt_main;
}
?>