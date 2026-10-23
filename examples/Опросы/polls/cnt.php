<?php	
	//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Cache-Control: max-age=0");
	header("Pragma: no-cache");
	header("Content-type: image/png".chr(10).chr(10));
	$hit_day			=trim($_GET['u_d']);
	$host_day		=trim($_GET['a_d']);
	$hit_month		=trim($_GET['u_m']);
	$host_month	=trim($_GET['a_m']);
	//start position
	$start_x=48;
	$start_y=14;
	//output position
	$x_d=0;
	$y_d=0;
	$x_m=0;
	$y_m=0;
	$img=ImageCreateFromPng('./img/counter.png');
	$font_path = dirname(__FILE__)."/data/timesbd.ttf";
	$txt_color=ImageColorAllocate($img,0,0,200);
	$shadow_color=ImageColorAllocate($img,140,195,225);
	//for hits
	$box=ImageTTFBBox(10, 0, $font_path, $hit_day." ");
	$hid_sx=$box[2]-$box[0];
	$box=ImageTTFBBox(10, 0, $font_path, $hit_month." ");
	$him_sx=$box[2]-$box[0];
	$y_d=$start_y;
	$y_m=$start_y+12;
	if($hid_sx==$him_sx)
	{
		$x_d=$start_x;
		$x_m=$start_x;
	}
	else
	{
		$dx=abs($hid_sx-$him_sx);
		if($hid_sx>$him_sx)
		{
			$x_d=$start_x;
			$x_m=$start_x+$dx;
		}
		else
		{
			$x_d=$start_x+$dx;
			$x_m=$start_x;
		}
	}
	ImageTTFText($img, 10, 0, $x_d + 1, $y_d + 1, $shadow_color, $font_path, $hit_day." ");
	ImageTTFText($img, 10, 0, $x_m + 1, $y_m + 1, $shadow_color, $font_path, $hit_month." ");
	ImageTTFText($img, 10, 0, $x_d, $y_d, $txt_color, $font_path, $hit_day." ");
	ImageTTFText($img, 10, 0, $x_m, $y_m, $txt_color, $font_path, $hit_month." ");
	//for hosts
	//$dx1=max($hid_sx,$him_sx)+1;
	if(max($host_day, $host_month) >= 10000) { $dx1=max($hid_sx,$him_sx)+1; }
	else
	{
		if(max($host_day, $host_month) >= 1000) { $dx1=max($hid_sx,$him_sx)+3; }
		else { $dx1=max($hid_sx,$him_sx)+5; }
	}
	$box=ImageTTFBBox(10, 0, $font_path, $host_day);
	$hod_sx=$box[2]-$box[0];
	$box=ImageTTFBBox(10, 0, $font_path, $host_month);
	$hom_sx=$box[2]-$box[0];
	if($hod_sx==$hom_sx)
	{
		$x_d=$start_x+$dx1;
		$x_m=$start_x+$dx1;
	}
	else
	{
		$dx=abs($hod_sx-$hom_sx);
		if($hod_sx>$hom_sx)
		{
			$x_d=$start_x+$dx1;
			$x_m=$start_x+$dx1+$dx;
		}
		else
		{
			$x_d=$start_x+$dx1+$dx;
			$x_m=$start_x+$dx1;
		}
	}
	ImageTTFText($img, 10, 0, $x_d + 1, $y_d + 1, $shadow_color, $font_path, $host_day);
	ImageTTFText($img, 10, 0, $x_m + 1, $y_m + 1, $shadow_color, $font_path, $host_month);
	ImageTTFText($img, 10, 0, $x_d, $y_d, $txt_color, $font_path, $host_day);
	ImageTTFText($img, 10, 0, $x_m, $y_m, $txt_color, $font_path, $host_month);
	ImagePNG($img,NULL,9,PNG_ALL_FILTERS);
	ImageDestroy($img);
?>