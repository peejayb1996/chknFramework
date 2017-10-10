<?php

class Session{
	function put($name,$value){
		$_SESSION[$name] = $value;
	}

	function get($name){
		return $_SESSION[$name];
		echo 1;
	}

}