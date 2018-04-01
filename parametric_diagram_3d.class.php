<?php
///////////////////////////////////////////////////////////////////////////////////////////
/////////// Author    : Reza Salehi
///////////	Contact   : zaalion@yahoo.com
/////////// Copyright : free for non-commercial use . 
///////////////////////////////////////////////////////////////////////////////////////////

	class parametric_diagram_3d
		{
		
		//---- class properties.
		var $dimx;
		var $dimy;
		var $br;
		var $bg;
		var $bb;
		var $ar;
		var $ag;
		var $ab;
		var $function;
		var $step;
		var $hasdrid;
		var $x_points;
		var $y_points;
		var $z_points;
				
		//---- CONSTRUCTOR.
		function parametric_diagram_3d($t_start, $t_end, $dimx, $dimy, $br, $bg, $bb, $ar, $ag, $ab, $function_x, $function_y, $function_z, $step, $hasgrid, $render)
			{
			$this->dimx=(int)$dimx;
			$this->dimy=(int)$dimy;
			$this->br=(int)$br;
			$this->bg=(int)$bg;
			$this->bb=(int)$bb;
			$this->ar=(int)$ar;
			$this->ag=(int)$ag;
			$this->ab=(int)$ab;
			$this->function_x=$function_x;
			$this->function_y=$function_y;
			$this->function_z=$function_z;
			$this->step=(real)$step;
			$this->hasgrid=$hasgrid;
			$this->t_start=$t_start;
			$this->t_end=$t_end;
			$this->render=$render;
			}
			
		//---- some validations.
		function doler()
			{
			$this->function_x=str_replace('tan','@',$this->function_x);
			$this->function_x=str_replace('atan','@',$this->function_x);
			$this->function_x=str_replace('cot','@',$this->function_x);
			$this->function_y=str_replace('tan','@',$this->function_y);
			$this->function_y=str_replace('atan','@',$this->function_y);
			$this->function_y=str_replace('cot','@',$this->function_y);
			$this->function_z=str_replace('tan','@',$this->function_z);
			$this->function_z=str_replace('atan','@',$this->function_z);
			$this->function_z=str_replace('cot','@',$this->function_z);
			$this->function_x=str_replace('t','$t',$this->function_x);
			$this->function_y=str_replace('t','$t',$this->function_y);
			$this->function_z=str_replace('t','$t',$this->function_z);
			$this->function_x=str_replace('@','tan',$this->function_x);
			$this->function_x=str_replace('@','atan',$this->function_x);
			$this->function_x=str_replace('@','cot',$this->function_x);
			$this->function_y=str_replace('@','tan',$this->function_y);
			$this->function_y=str_replace('@','atan',$this->function_y);
			$this->function_y=str_replace('@','cot',$this->function_y);
			$this->function_z=str_replace('tan','@',$this->function_z);
			$this->function_z=str_replace('atan','@',$this->function_z);
			$this->function_z=str_replace('cot','@',$this->function_z);
			}
		function validate()
			{
			if(substr_count($this->function_x,'(')!=substr_count($this->function_x,')') || substr_count($this->function_y,'(')!=substr_count($this->function_y,')') || (substr_count($this->function_x,'x')>0 || substr_count($this->function_y,'x')>0))
				{
				header("Location: index.html");
				die();
				}
			}
		function zscale()
			{
			return(1);
			}
		function yscale()
			{
			if((substr_count($this->function_y,'sin')>0)||(substr_count($this->function_y,'cos')>0))
				return(50);
			else
				return(1);
			}
		function xscale()
			{
			if((substr_count($this->function_x,'sin')>0)||(substr_count($this->function_x,'cos')>0))
				return(50);
			else
				return(1);
			}
		//----main function.
		function draw()
			{
			header("Content-type: image/jpeg");
			$image=imagecreate($this->dimx,$this->dimy+20);
			$col=imagecolorallocate($image,$this->br,$this->bg,$this->bb);
			$col1=imagecolorallocate($image,$this->ar,$this->ag,$this->ab);
			$grcol=imagecolorallocate($image,8,100,8);
			$this->validate();
			$this->doler();
			if($this->hasgrid=='1')
				{
				//---- vertical grids.
				for($i=0;$i<$this->dimx;$i+=10)
					imageline($image,$i,0,$i,$this->dimy,$grcol);
				//---- horizontal grids.
				for($i=0;$i<$this->dimy;$i+=10)
					imageline($image,0,$i,$this->dimx,$i,$grcol);
				}
			imageline($image, $this->dimx/2, 0, $this->dimx/2, $this->dimy, $col1);
			imageline($image, 0, $this->dimy/2, $this->dimx, $this->dimy/2, $col1);
			//----
			$j=0;
			$colx=$col1;
				for($t=$this->t_start; $t<$this->t_end; $t+=$this->step)
				{
					eval('$this->x_points[$j]='.$this->xscale().'*'.$this->function_x.';');
					eval('$this->y_points[$j]='.$this->yscale().'*'.$this->function_y.';');
					eval('$this->z_points[$j]='.$this->zscale().'*'.$this->function_z.';');
					if($this->render=='1')
						$colx=imagecolorallocate($image,60,abs($t%255),30);
					imagearc($image, $this->x_points[$j]+$this->dimx/2, ($this->y_points[$j]*(-1))+$this->dimy/2, $this->z_points[$j], $this->z_points[$j], 0, 360, $colx);
					$j++;	
				}
			//----
			imagefilledrectangle ($image, 5, $this->dimy-30, $this->dimx/2, $this->dimy+10, $col);
			//imagerectangle ($image, 5, 100, 100, 400, $col);
			imagestring ($image, 2, 10, $this->dimy-28, "x(t) = ".str_replace('$t','t',$this->function_x), $col1);
			imagestring ($image, 2, 10, $this->dimy-15, "y(t) = ".str_replace('$t','t',$this->function_y), $col1);
			imagestring ($image, 2, 10, $this->dimy-3 , "z(t)= ".str_replace('$t','t',$this->function_z), $col1);
			imagejpeg($image,"",100);
			}	
		}				
?>