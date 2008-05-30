<?php
class TDL_Standalone_captcha extends PLIB_Standalone
{
	public function run()
	{
		/*$captcha = new PLIB_GD_Captcha();
		$captcha->add_ttf_font(new PLIB_GD_Font_TTF('../Boardsolution/images/gd/','scratch.ttf'));
		$captcha->add_ttf_font(new PLIB_GD_Font_TTF('../Boardsolution/images/gd/','veramono.ttf'));
		$captcha->set_angle_difference(15);
		$captcha->create_image();*/
		
		$range = range(0,360,15);
		$rect = new PLIB_GD_Rectangle(60,60,50,40);
		list($w,$h) = $rect->get_size()->get_size();
		
		//$img = imagecreate(count($range) * ($w * 3 + 10),160);
		//$img = new PLIB_GD_Image(500,300);
		//$img = PLIB_GD_Image::load_from('/home/hrniels/Images/jasmin.jpg','jpeg');
		$img = new PLIB_GD_Image(1024,768);
		$img->set_background(PLIB_GD_Color::$BLACK);
		$g = $img->get_graphics();
		$color = new PLIB_GD_Color('ff000040');
		$colors = array(
			array(255,0,0,100),
			array(0,0,255),
			array(0,255,0,25),
			array(255,0,255,100)
		);
		
		/*$circle = new PLIB_GD_Circle(new PLIB_GD_Point(70,70),120);
		$g->draw_circle($circle,$color);
		
		$circle->translate(100,0);
		$g->fill_colorfade_circle($circle,$colors,3);
		
		$rect = new PLIB_GD_Rectangle(250,50,100,120);
		$g->fill_colorfade_rect(
			$rect,$colors,0,PLIB_GD_Graphics::POS_H_CENTER,PLIB_GD_Graphics::POS_V_CENTER
		);*/
		
		$c = new PLIB_GD_Color(255,0,0);
		//$g->draw_rect(new PLIB_GD_Rectangle(100,100,20,5),$c);
		//imagestring($img->get_image(),1,100,100,'Foo',$c->get_color($img->get_image()));
		
		$font = new PLIB_GD_Font_TTF('../Boardsolution/images/gd/veramono.ttf');
		
		$rect = $img->get_bounds_rect();
		$text = new PLIB_GD_TTFText('Das ist mein Text!!',$font,40,45);
		$myc = new PLIB_GD_Color('#ff0000');
		$pos = new PLIB_GD_BoxPosition(PLIB_GD_BoxPosition::MIDDLE,PLIB_GD_BoxPosition::MIDDLE);
		$pad = new PLIB_GD_Padding(5);
		$pad2 = new PLIB_GD_Padding(3);
		$g->draw_string_in_rect($rect,$text,PLIB_GD_Color::$GRAY,$pad,$pos);
		$rect->translate(-1,-1);
		$myc = PLIB_GD_Color::$ORANGE;
		$g->draw_string_in_rect($rect,$text,$myc,$pad,$pos);
		$g->draw_string_border($rect,$text,$myc,$pad,$pad2,$pos);
		
		$rect = new PLIB_GD_Rectangle(100,100,100,100);
		foreach(array(0,45,90,100,180,270,293) as $r)
		{
			$text = new PLIB_GD_TTFText('foobar',$font,15,$r);
			$pos = new PLIB_GD_BoxPosition(PLIB_GD_BoxPosition::LAST,PLIB_GD_BoxPosition::MIDDLE);
			$g->draw_rect($rect,$c);
			//$this->draw_bounds_rect($bounds,$pos,$color);
			$g->draw_string_in_rect($rect,$text,$c,$pad,$pos);
			$g->draw_string_border($rect,$text,$c,$pad,$pad2,$pos);
			
			$rect->translate(120,20);
		}
		
		/*$str = 'bb';
		$x = 100;
		foreach(range(0,360,15) as $r)
		{
			imagettftext(
				$img->get_image(),30,$r,$x,100,$c->get_color($img->get_image()),$font->get_font(),$str
			);
			$x += 50;
		}
	
		$x = 100;
		list($w,$h) = PLIB_GD_Utils::get_ttf_size(35,$font->get_font(),$str);
		$rect = new PLIB_GD_Rectangle(100,100 - $h,$w,$h);
		$a = 0;
		foreach(range(0,360,15) as $r)
		{
			$bounds = PLIB_GD_Utils::get_ttf_bounds(35,$r,$font->get_font(),$str);
			$pos = new PLIB_GD_Point(100 + $a * 50,100);
			$g->draw_bounds_rect($bounds,$pos,$c);
			$g->draw_rect($rect,$c,-$r,PLIB_GD_Graphics::POS_H_LEFT,PLIB_GD_Graphics::POS_V_BOTTOM);
			$rect->translate(50,0);
			$a++;
		}*/
		//$box = imagettfbbox(30,45,$font->get_font(),'Foo');
		//list($w,$h) = PLIB_GD_Utils::get_ttf_size(35,$font->get_font(),'Foo');
		//$r = new PLIB_GD_Rectangle(100,100,$w,$h);
		//$g->draw_rect($r,$color,-45,PLIB_GD_Graphics::POS_H_LEFT,PLIB_GD_Graphics::POS_V_BOTTOM);
		
		
		/*
		
		$from = new PLIB_GD_Point(40,40);
		foreach(range(0,360,15) as $r)
		{
			$rect = new PLIB_GD_Rectangle($from,new PLIB_GD_Dimension(50,70));
			$g->fill_colorfade_rect($rect,$colors,$r,'c',5);
			
			$from->translate(100,0);
		}*/
		
		/*$dist = 70;
		$cf = new PLIB_GD_ColorFade($dist,$dist,$colors);
		
		foreach(range(1,15) as $r)
		{
			$x = 60 + $r * 20;
			$y = 10;
			$h = 30;
			foreach($cf->get_colors() as $color)
			{
				imagefilledrectangle(
					$img,(int)$x,(int)$y,(int)$x + 1,(int)$y + $h,$color->get_color($img)
				);
				
				$x += -1;
				$y += 2;
			}
		}*/
		/*foreach($range as $a)
		{
			$g->fill_rect($rect,$color,$a,'c');
			list($x,$y) = $rect->get_location()->get_position();
			imagerectangle($img,$x - $w,$y - $h,$x + $w * 2,$y + $h * 2,$color->get_color($img));
			$rect->translate($w * 3 + 10,0);
			$color->brighter(10);
		}*/
		
		if(ob_get_length() == 0)
			$img->send();
		$img->destroy();
	}
}
?>