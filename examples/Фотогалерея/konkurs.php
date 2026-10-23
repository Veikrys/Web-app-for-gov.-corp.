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
if(array_key_exists('group',$_GET)) { $group=$_GET['group']; }
else { $group=30; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8, chrome=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" lang="ru" xml:lang="ru" />
		<meta http-equiv="Content-Language" content="ru-ru" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="ImageToolbar" content="no" />
    	<title>УФК 3700 - Конкурс</title>
	    <link rel="stylesheet" type="text/css" media="all" href="css/menu_v20130613.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/styles_v20150802.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/tables_v20140429.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/forms_v20150107.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="js/ui_theme/jquery-ui_v20130212.css" />
    	<link rel="stylesheet" type="text/css" media="all" href="css/magnific-popup_v20211214.css" />
    	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
    	<script type="text/javascript" language="javascript" src="js/jquery_v20130528.js"></script>
    	<script type="text/javascript" language="javascript" src="js/ui_core_v20130212.js"></script>
    	<script type="text/javascript" language="javascript" src="js/ui_widget_v20130212.js"></script>
    	<script type="text/javascript" language="javascript" src="js/ui_position_v20130212.js"></script>
    	<script type="text/javascript" language="javascript" src="js/ui_dialog_v20120430.js"></script>
    	<script type="text/javascript" language="javascript" src="js/ui_button_v20120430.js"></script>
    	<script type="text/javascript" language="javascript" src="js/magnific-popup_v20211214.js" defer="defer"></script>
    	<script type="text/javascript" language="javascript" src="js/menu_v20130603.js" defer="defer"></script>
    	<script type="text/javascript" language="javascript" src="js/main_v20130603.js" defer="defer"></script>
    	<script type="text/javascript" language="javascript" src="js/vote_v20140316.js" defer="defer"></script>
	</head>
	<body>
		<?php
		print_header($access,$db);
		?>
		<div id="main">
			<!--<h2><img class="img_fl" src="img/fl_help.png" alt="" height="28" width="38" />Об Управлении<img class="img_fe" src="img/fl_end.png" alt="" height="16" width="34" /></h2>-->
			<h2><span>Конкурс фотографий</span><img class="img_fl" src="img/fl_konk.png" alt="" height="28" width="41" /><img src="img/head_konk_v20220227.png" alt="Конкурс фотографий" width="230" height="29" /><img class="img_fe" src="img/fl_end.png" alt="" height="16" width="34" /></h2>
			<table align="center" cellpadding="0" cellspacing="0" border="0" width="98%" class="frm_l">
				<tr>
					<td class="frm_r">
						<h3>Страницы конкурса</h3>
						<ul class="pfolder">
						<?php
						$q_kl = "SELECT `konkurs`.`ID_K` AS k_idk, `name`, COUNT(DISTINCT `photo20`.`ofk`) AS k_phs FROM `konkurs` LEFT JOIN `photo20` ON (`konkurs`.`ID_K`=`photo20`.`id_k`) WHERE `active`>0 GROUP BY `photo20`.`id_k`";
						$r_kl=$db->query($q_kl);
						for($k=0; $k < $r_kl->num_rows; $k++)
						{
							$rec=$r_kl->fetch_array();
							$q_ol = "SELECT DISTINCT `ofk` FROM `photo20` WHERE `id_k`=".$rec['k_idk']." ORDER BY `ofk`";
							$r_ol=$db->query($q_ol);
							if($rec['k_phs'] == 1)
							{
								$rec1=$r_ol->fetch_array();
								print "<li><a href=\"konkurs.php?group=".substr($rec1['ofk'], 2, 2)."\" class=\"und\"><span>".$rec['name']."</span></a></li>";
							}
							else
							{
								if($rec['k_idk'] == 1)
								{
									print "<li><a href=\"konkurs.php?group=30\" class=\"und\"><span>Фотографии Топ-5</span></a></li>";
									print "<li><a href=\"konkurs.php?group=0\" class=\"und\"><span>Конкурсные фотографии Управления&nbsp;<b style=\"font-size:80%\">(2)</b></span></a></li>";
									//count photos
									$q_l="SELECT `ofk`, COUNT(*) AS c_ph FROM `photo20` GROUP BY `ofk` ";
									$r_l=$db->query($q_l);
									$list_ofk=array();
									for($j=0; $j < $r_l->num_rows; $j++)
									{
										$rec2=$r_l->fetch_array();
										$list_ofk[$rec2['ofk']]=$rec2['c_ph'];
									}
									$r_l->free();
									for($i=1; $i < 27; $i++)
									{
										$ofk_code=3700+$i;
										if(array_key_exists($ofk_code, $list_ofk))
										{
											if($list_ofk[$ofk_code] > 0) {print "<li><a href=\"konkurs.php?group=".$i."\" class=\"und\"><span>Конкурсные фотографии отдела ".$ofk_code."&nbsp;<b style=\"font-size:80%\">(".$list_ofk[$ofk_code].")</b></span></a></li>\n";}
										}
									}
								}
							}
							$r_ol->free();
						}
						$r_kl->free();
						?>
						</ul>
					</td>
					<td style="width:90%;vertical-align:top;border-style:none;padding-top:10px;">
					<?php
					if($group>30)
					{
						$q_w="SELECT `photo20`.`ID_P`, `id_k`, `ofk`, `date_photo`, `file_small`, `file_full`, `description`, `width_small`, `height_small`, `width_full`, `height_full`, `Comment`, `tag`, AVG(`mark`) AS s_m, COUNT(`mark`) AS c_m, SUM(`mark`) AS n_m FROM `photo20` LEFT JOIN `photo20_vote` ON (`photo20`.`ID_P`=`photo20_vote`.`id_p`)  WHERE `ofk`=".(3700+$group)." GROUP BY `photo20`.`ID_P` ORDER BY `id_k`, `fav`, s_m DESC, `description`";
						$r_w=$db->query($q_w);
						$n_w=$r_w->num_rows;
						if($n_w > 0)
						{
							$q_kn = "SELECT DISTINCT `name`, `type` FROM `konkurs` LEFT JOIN `photo20` ON (`konkurs`.`ID_K`=`photo20`.`id_k`) WHERE `ofk`=".(3700+$group);
							$r_kn = $db->query($q_kn);
							$rec_kn = $r_kn->fetch_array();
							print "<h3 class=\"hlp\">".$rec_kn['name']."</h3>";
							print "<table align=\"left\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"50%\" class=\"pic_tbl\">\n";
							$photo_marked=array();
							$m_ip=md5(ip2long($_SERVER['REMOTE_ADDR']));
							$q_p="SELECT `id_p`, COUNT(*) AS v_n FROM `photo20_vote` WHERE `ip_md5`='".$m_ip."' ORDER BY `id_p`";
							$r_p=$db->query($q_p);
							$n_p=$r_p->num_rows;
							if($n_p > 0)
							{
								for($k=0; $k < $n_p; $k++)
								{
									$rec_p=$r_p->fetch_array();
									if($rec_p['v_n']>4)
									{
										$photo_marked[$k]=$rec_p['id_p'];
									}									
								}
							}
							$r_p->free();
							for($j=0; $j < $n_w; $j++)
							{
								$rec=$r_w->fetch_array();
								if($rec['s_m']==NULL) { $rec['s_m']=0; }
								if(in_array($rec['ID_P'], $photo_marked))
								{
									$id="r0";
									$class_mark="rate01_lock";
								}
								else
								{
									$id="r".$rec['ID_P'];
									$class_mark="rate_txt";
								}
								$text=NULL;
								if($rec['description']!=NULL) { $text.="<p class=\"pic_desc\">".$rec['description']."</p>"; }
								if($rec['Comment']!=NULL) { $text.="<p class=\"pic_comm\">".$rec['Comment']."</p>"; }
								//date
								$month=lcfirst(strftime("%B", mktime(0, 0, 0, substr($rec['date_photo'], 5, 2), substr($rec['date_photo'], 8, 2), substr($rec['date_photo'], 0, 4))));
								$month=preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month);
								print "<tr><td class=\"pic_tc1\"><a href=\"img/photo20/".$rec['file_full']."\" class=\"image-popup-zoom\"><img src=\"img/photo20/".$rec['file_small']."\" width=\"".$rec['width_small']."\" height=\"".$rec['height_small']."\" alt=\"".$rec['description']."\" id=\"p".$rec['ID_P']."\" /></a></td><td class=\"pic_tc1\"><div class=\"pic_date\">".substr($rec['date_photo'], 8, 2)."&nbsp;".$month."&nbsp;".substr($rec['date_photo'], 0, 4)."&nbsp;г.</div>\n";
								switch($rec['tag'])
								{
									case 0:
										print "<div class=\"pic_tag\">&nbsp;</div>\n";
										break;
									case 1:
										print "<div class=\"pic_tag\">Номинация: <span>Фотография.</span></div>\n";
										break;
									case 2:
										print "<div class=\"pic_tag\">Номинация: <span>Фотоколлаж.</span></div>\n";
										break;
									case 51:
										print "<div class=\"pic_tag\">1-е&nbsp;место.</span></div>\n";
										break;
									case 52:
										print "<div class=\"pic_tag\">2-е&nbsp;место.</span></div>\n";
										break;
									case 53:
										print "<div class=\"pic_tag\">3-е&nbsp;место.</span></div>\n";
										break;
								}
								if($rec['ofk']>=3750) {print "<div class=\"pic_tofk\">&nbsp;</div>\n";}
								else {print "<div class=\"pic_tofk\">ОФК&nbsp;".$rec['ofk']."</div>\n";}
								print "<div class=\"pic_txt\">".$text."</div>\n";
								print "</td></tr>\n";
								if($rec_kn['type']==5)
								{
									print "<tr><td><div class=\"rate\" id=\"".$id."\"><div style=\"width:".floor(100*$rec['s_m']/5)."%\"></div></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['s_m'], 2)." из 5 (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
								else
								{
									print "<tr><td><div class=\"rate01 ks".$rec['id_k']."\" id=\"".$id."\"></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['n_m'], 2)." (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
							}
							print "</table>\n";
						}
						$r_w->free();
					}
					else
					{
						if($group==30)
						{
							//winners
							$q_w="SELECT `photo20`.`ID_P`, `ofk`, `date_photo`, `file_small`, `file_full`, `description`, `width_small`, `height_small`, `width_full`, `height_full`, `Comment`, `tag`, `fav`, AVG(`mark`) AS s_m, COUNT(`mark`) AS c_m FROM `photo20` LEFT JOIN `photo20_vote` ON (`photo20`.`ID_P`=`photo20_vote`.`id_p`)  WHERE `fav`>0 AND `id_k`=1 GROUP BY `photo20`.`ID_P` ORDER BY `tag`, `fav`";
							$r_w=$db->query($q_w);
							$n_w=$r_w->num_rows;
							if($n_w > 0)
							{
								print "<h3 class=\"hlp\">Победители конкурса</h3>";
								print "<table align=\"left\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"50%\" class=\"pic_tbl\">\n";
								$photo_marked=array();
								$m_ip=md5(ip2long($_SERVER['REMOTE_ADDR']));
								$q_p="SELECT `id_p` FROM `photo20_vote` WHERE `ip_md5`='".$m_ip."' ORDER BY `id_p`";
								$r_p=$db->query($q_p);
								$n_p=$r_p->num_rows;
								if($n_p > 0)
								{
									for($k=0; $k < $n_p; $k++)
									{
										$rec_p=$r_p->fetch_array();
										$photo_marked[$k]=$rec_p['id_p'];
									}
								}
								$r_p->free();
								for($j=0; $j < $n_w; $j++)
								{
									$rec=$r_w->fetch_array();
									if($rec['s_m']==NULL) { $rec['s_m']=0; }
									if(in_array($rec['ID_P'], $photo_marked))
									{
										$id="r0";
										$class_mark="rate_lock";
									}
									else
									{
										$id="r".$rec['ID_P'];
										$class_mark="rate_txt";
									}
									$text=NULL;
									if($rec['description']!=NULL) { $text.="<p class=\"pic_desc\">".$rec['description']."</p>"; }
									if($rec['Comment']!=NULL) { $text.="<p class=\"pic_comm\">".$rec['Comment']."</p>"; }
									//date
									$month=lcfirst(strftime("%B", mktime(0, 0, 0, substr($rec['date_photo'], 5, 2), substr($rec['date_photo'], 8, 2), substr($rec['date_photo'], 0, 4))));
									$month=preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month);
									print "<tr><td colspan=\"2\" style=\"font-size:110%;font-weight:bold;color:#0025A0\">".$rec['fav']."-е место</td></tr>\n";
									print "<tr><td class=\"pic_tc1\"><a href=\"img/photo20/".$rec['file_full']."\" class=\"image-popup-zoom\"><img src=\"img/photo20/".$rec['file_small']."\" width=\"".$rec['width_small']."\" height=\"".$rec['height_small']."\" alt=\"".$rec['description']."\" id=\"p".$rec['ID_P']."\" /></a></td><td class=\"pic_tc1\"><div class=\"pic_date\">".substr($rec['date_photo'], 8, 2)."&nbsp;".$month."&nbsp;".substr($rec['date_photo'], 0, 4)."&nbsp;г.</div>\n";
									switch($rec['tag'])
									{
										case 0:
											print "<div class=\"pic_tag\">&nbsp;</div>\n";
											break;
										case 1:
											print "<div class=\"pic_tag\">Номинация: <span>Фотография.</span></div>\n";
											break;
										case 2:
											print "<div class=\"pic_tag\">Номинация: <span>Фотоколлаж.</span></div>\n";
											break;
									}
									print "<div class=\"pic_tofk\">ОФК&nbsp;".$rec['ofk']."</div>\n";
									print "<div class=\"pic_txt\">".$text."</div>\n";
									print "</td></tr>\n";
									print "<tr><td><div class=\"rate\" id=\"".$id."\"><div style=\"width:".floor(100*$rec['s_m']/5)."%\"></div></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['s_m'], 2)." из 5 (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
								print "</table>\n";
							}
							//the best
							$q_b="SELECT `photo20`.`ID_P`, `ofk`, `date_photo`, `file_small`, `file_full`, `description`, `width_small`, `height_small`, `width_full`, `height_full`, `Comment`, `tag`, AVG(`mark`) AS s_m, COUNT(`mark`) AS c_m FROM `photo20` LEFT JOIN `photo20_vote` ON (`photo20`.`ID_P`=`photo20_vote`.`id_p`)  WHERE `mark`>0 AND `id_k`=1 GROUP BY `photo20`.`ID_P` ORDER BY s_m DESC LIMIT 0, 5";
							//print "<p>$q_b</p>\n";
							$r_b=$db->query($q_b);
							$n_b=$r_b->num_rows;
							if($n_b > 0)
							{
								print "<h3 class=\"hlp\" style=\"clear:both;\">Зрительские симпатии</h3>";
								print "<table align=\"left\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"50%\" class=\"pic_tbl\">\n";
								$photo_marked=array();
								$m_ip=md5(ip2long($_SERVER['REMOTE_ADDR']));
								$q_p="SELECT `id_p` FROM `photo20_vote` WHERE `ip_md5`='".$m_ip."' ORDER BY `id_p`";
								$r_p=$db->query($q_p);
								$n_p=$r_p->num_rows;
								if($n_p > 0)
								{
									for($k=0; $k < $n_p; $k++)
									{
										$rec_p=$r_p->fetch_array();
										$photo_marked[$k]=$rec_p['id_p'];
									}
								}
								$r_p->free();
								for($j=0; $j < $n_b; $j++)
								{
									$rec=$r_b->fetch_array();
									if($rec['s_m']==NULL) { $rec['s_m']=0; }
									if(in_array($rec['ID_P'], $photo_marked))
									{
										$id="r0";
										$class_mark="rate_lock";
									}
									else
									{
										$id="r".$rec['ID_P'];
										$class_mark="rate_txt";
									}
									$text=NULL;
									if($rec['description']!=NULL) { $text.="<p class=\"pic_desc\">".$rec['description']."</p>"; }
									if($rec['Comment']!=NULL) { $text.="<p class=\"pic_comm\">".$rec['Comment']."</p>"; }
									//date
									$month=lcfirst(strftime("%B", mktime(0, 0, 0, substr($rec['date_photo'], 5, 2), substr($rec['date_photo'], 8, 2), substr($rec['date_photo'], 0, 4))));
									$month=preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month);
									print "<tr><td class=\"pic_tc1\"><a href=\"img/photo20/".$rec['file_full']."\" class=\"image-popup-zoom\"><img src=\"img/photo20/".$rec['file_small']."\" width=\"".$rec['width_small']."\" height=\"".$rec['height_small']."\" alt=\"".$rec['description']."\" id=\"p".$rec['ID_P']."\" /></a></td><td class=\"pic_tc1\"><div class=\"pic_date\">".substr($rec['date_photo'], 8, 2)."&nbsp;".$month."&nbsp;".substr($rec['date_photo'], 0, 4)."&nbsp;г.</div>\n";
									switch($rec['tag'])
									{
										case 0:
											print "<div class=\"pic_tag\">&nbsp;</div>\n";
											break;
										case 1:
											print "<div class=\"pic_tag\">Номинация: <span>Фотография.</span></div>\n";
											break;
										case 2:
											print "<div class=\"pic_tag\">Номинация: <span>Фотоколлаж.</span></div>\n";
											break;
									}
									print "<div class=\"pic_tofk\">ОФК&nbsp;".$rec['ofk']."</div>\n";
									print "<div class=\"pic_txt\">".$text."</div>\n";
									print "</td></tr>\n";
									print "<tr><td><div class=\"rate\" id=\"".$id."\"><div style=\"width:".floor(100*$rec['s_m']/5)."%\"></div></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['s_m'], 2)." из 5 (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
								print "</table>\n";
							}
							$r_b->free();
							//last
							$q_l="SELECT `photo20`.`ID_P`, `ofk`, `date_photo`, `file_small`, `file_full`, `description`, `width_small`, `height_small`, `width_full`, `height_full`, `Comment`, `tag`, AVG(`mark`) AS s_m, COUNT(`mark`) AS c_m FROM `photo20` LEFT JOIN `photo20_vote` ON (`photo20`.`ID_P`=`photo20_vote`.`id_p`)  WHERE `id_k`=1 GROUP BY `photo20`.`ID_P` ORDER BY `time_post` DESC LIMIT 0, 5";
							$r_l=$db->query($q_l);
							$n_l=$r_l->num_rows;
							if($n_l > 0)
							{
								print "<h3 class=\"hlp\" style=\"clear:both;\">Недавно добавленные фотографии</h3>";
								print "<table align=\"left\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"50%\" class=\"pic_tbl\">\n";
								$photo_marked=array();
								$m_ip=md5(ip2long($_SERVER['REMOTE_ADDR']));
								$q_p="SELECT `id_p` FROM `photo20_vote` WHERE `ip_md5`='".$m_ip."' ORDER BY `id_p`";
								$r_p=$db->query($q_p);
								$n_p=$r_p->num_rows;
								if($n_p > 0)
								{
									for($k=0; $k < $n_p; $k++)
									{
										$rec_p=$r_p->fetch_array();
										$photo_marked[$k]=$rec_p['id_p'];
									}
								}
								$r_p->free();
								for($j=0; $j < $n_l; $j++)
								{
									$rec=$r_l->fetch_array();
									if($rec['s_m']==NULL) { $rec['s_m']=0; }
									if(in_array($rec['ID_P'], $photo_marked))
									{
										$id="r0";
										$class_mark="rate_lock";
									}
									else
									{
										$id="r".$rec['ID_P'];
										$class_mark="rate_txt";
									}
									$text=NULL;
									if($rec['description']!=NULL) { $text.="<p class=\"pic_desc\">".$rec['description']."</p>"; }
									if($rec['Comment']!=NULL) { $text.="<p class=\"pic_comm\">".$rec['Comment']."</p>"; }
									//date
									$month=lcfirst(strftime("%B", mktime(0, 0, 0, substr($rec['date_photo'], 5, 2), substr($rec['date_photo'], 8, 2), substr($rec['date_photo'], 0, 4))));
									$month=preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month);
									print "<tr><td class=\"pic_tc1\"><a href=\"img/photo20/".$rec['file_full']."\" class=\"image-popup-zoom\"><img src=\"img/photo20/".$rec['file_small']."\" width=\"".$rec['width_small']."\" height=\"".$rec['height_small']."\" alt=\"".$rec['description']."\" id=\"p".$rec['ID_P']."\" /></a></td><td class=\"pic_tc1\"><div class=\"pic_date\">".substr($rec['date_photo'], 8, 2)."&nbsp;".$month."&nbsp;".substr($rec['date_photo'], 0, 4)."&nbsp;г.</div>\n";
									switch($rec['tag'])
									{
										case 0:
											print "<div class=\"pic_tag\">&nbsp;</div>\n";
											break;
										case 1:
											print "<div class=\"pic_tag\">Номинация: <span>Фотография.</span></div>\n";
											break;
										case 2:
											print "<div class=\"pic_tag\">Номинация: <span>Фотоколлаж.</span></div>\n";
											break;
									}
									print "<div class=\"pic_tofk\">ОФК&nbsp;".$rec['ofk']."</div>\n";
									print "<div class=\"pic_txt\">".$text."</div>\n";
									print "</td></tr>\n";
									print "<tr><td><div class=\"rate\" id=\"".$id."\"><div style=\"width:".floor(100*$rec['s_m']/5)."%\"></div></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['s_m'], 2)." из 5 (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
								print "</table>\n";
							}
							$r_l->free();
						}
						else
						{
							$q_w="SELECT `photo20`.`ID_P`, `ofk`, `date_photo`, `file_small`, `file_full`, `description`, `width_small`, `height_small`, `width_full`, `height_full`, `Comment`, `tag`, AVG(`mark`) AS s_m, COUNT(`mark`) AS c_m FROM `photo20` LEFT JOIN `photo20_vote` ON (`photo20`.`ID_P`=`photo20_vote`.`id_p`)  WHERE `ofk`=".(3700+$group)." GROUP BY `photo20`.`ID_P` ORDER BY s_m DESC";
							$r_w=$db->query($q_w);
							$n_w=$r_w->num_rows;
							if($n_w > 0)
							{
								print "<h3 class=\"hlp\">Фотографии отдела ".(3700+$group)."</h3>";
								print "<table align=\"left\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"50%\" class=\"pic_tbl\">\n";
								$photo_marked=array();
								$m_ip=md5(ip2long($_SERVER['REMOTE_ADDR']));
								$q_p="SELECT `id_p` FROM `photo20_vote` WHERE `ip_md5`='".$m_ip."' ORDER BY `id_p`";
								$r_p=$db->query($q_p);
								$n_p=$r_p->num_rows;
								if($n_p > 0)
								{
									for($k=0; $k < $n_p; $k++)
									{
										$rec_p=$r_p->fetch_array();
										$photo_marked[$k]=$rec_p['id_p'];
									}
								}
								$r_p->free();
								for($j=0; $j < $n_w; $j++)
								{
									$rec=$r_w->fetch_array();
									if($rec['s_m']==NULL) { $rec['s_m']=0; }
									if(in_array($rec['ID_P'], $photo_marked))
									{
										$id="r0";
										$class_mark="rate_lock";
									}
									else
									{
										$id="r".$rec['ID_P'];
										$class_mark="rate_txt";
									}
									$text=NULL;
									if($rec['description']!=NULL) { $text.="<p class=\"pic_desc\">".$rec['description']."</p>"; }
									if($rec['Comment']!=NULL) { $text.="<p class=\"pic_comm\">".$rec['Comment']."</p>"; }
									//date
									$month=lcfirst(strftime("%B", mktime(0, 0, 0, substr($rec['date_photo'], 5, 2), substr($rec['date_photo'], 8, 2), substr($rec['date_photo'], 0, 4))));
									$month=preg_replace(array('/(.*)ь$/', '/(.*)й$/', '/(.*)т$/'), array('$1я', '$1я', '$1та'),$month);
									print "<tr><td class=\"pic_tc1\"><a href=\"img/photo20/".$rec['file_full']."\" class=\"image-popup-zoom\"><img src=\"img/photo20/".$rec['file_small']."\" width=\"".$rec['width_small']."\" height=\"".$rec['height_small']."\" alt=\"".$rec['description']."\" id=\"p".$rec['ID_P']."\" /></a></td><td class=\"pic_tc1\"><div class=\"pic_date\">".substr($rec['date_photo'], 8, 2)."&nbsp;".$month."&nbsp;".substr($rec['date_photo'], 0, 4)."&nbsp;г.</div>\n";
									switch($rec['tag'])
									{
										case 0:
											print "<div class=\"pic_tag\">&nbsp;</div>\n";
											break;
										case 1:
											print "<div class=\"pic_tag\">Номинация: <span>Фотография.</span></div>\n";
											break;
										case 2:
											print "<div class=\"pic_tag\">Номинация: <span>Фотоколлаж.</span></div>\n";
											break;
										case 51:
											print "<div class=\"pic_tag\">1-е&nbsp;место.</span></div>\n";
											break;
										case 52:
											print "<div class=\"pic_tag\">2-е&nbsp;место.</span></div>\n";
											break;
										case 53:
											print "<div class=\"pic_tag\">3-е&nbsp;место.</span></div>\n";
											break;
									}
									if($rec['ofk']>=3750) {print "<div class=\"pic_tofk\">&nbsp;</div>\n";}
									else {print "<div class=\"pic_tofk\">ОФК&nbsp;".$rec['ofk']."</div>\n";}
									print "<div class=\"pic_txt\">".$text."</div>\n";
									print "</td></tr>\n";
									print "<tr><td><div class=\"rate\" id=\"".$id."\"><div style=\"width:".floor(100*$rec['s_m']/5)."%\"></div></div></td><td><div class=\"".$class_mark."\" id=\"t".$rec['ID_P']."\">".round($rec['s_m'], 2)." из 5 (Оценок: ".$rec['c_m'].")</div></td></tr>\n";
								}
								print "</table>\n";
							}
							$r_w->free();
						}
					}
					?>
					</td>
				</tr>
			</table>
		</div>
		<div id="dialog_confirm">&nbsp;</div>
		<div id="dialog_info">&nbsp;</div>
		<div id="dialog_img">&nbsp;</div>
		<?php
		footer($access,$db);
		//print "<p>Time1=".(1000*($t2-$t1))." ms, Time2=".(1000*($t3-$t2))."</p>";
		?>
	</body>
</html>