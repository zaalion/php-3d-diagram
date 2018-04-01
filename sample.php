<?php
	set_time_limit(120);
	include("parametric_diagram_3d.class.php");
	//new function_diagram(horizental dimention, vertical dimention, background color_red_, background color_green_, 
	//						background color_blue_,  arc color_red_, arc color_green_, arc color_blue_, function_x, 
	//						function_y, function_z, calculation step, parameter to determine grid drawing, is_render);
	//---- a test, gets these parameters from index.html as a form . 
	$show=new parametric_diagram_3d($_POST["t_start"],$_POST["t_end"],$_POST["x"],$_POST["y"],$_POST["br"],$_POST["bg"],$_POST["bb"],255,255,255,$_POST["eqx"],$_POST["eqy"], $_POST["eqz"], $_POST["step"],$_POST["hasgrid"], $_POST["render"]);
	//---- call draw mwthod of created object to get the diagram as a jbg file.
	$show->draw();	
?>