<?php

if (!function_exists('echo_br')) {

	function echo_br($how_much=null){
		if($how_much>1)
		{
			for ($i=0; $i <$how_much ; $i++) { 
				echo "<br>";
	
	
			}
		}
		echo "<br>";
	}
}

if (!function_exists('append_br')) {
	

    function append_br($how_much=null){
        if($how_much>1)
        {
            for ($i=0; $i <$how_much ; $i++) { 
                return "<br>";


            }
        }
        return "<br>";
    }
}
