<?php

/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 *
 * Class Loader
 * This class controls the url set by the user
 * It will divide the url into 4 parts (host/class_name/function_name/parameter)
 * It will load all the Libraries needed on a specific class
 * This class also holds the maintenance class that will be loaded once a class who wish to execute is defined as maintenance(lib/Settings.php)
 * This class also holds the error class that will be loaded once an error on loading class,method and pages occur.
 *
 * Note: This class must be left off-hand. This class is the core class of this framework. Any changes on this class will cause fatal error.
 */

class Loader{
	protected $controller;
	protected $_url;
	//error handler function

	public function Url_Controller(){
		session_start();
      $url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
	   $url = filter_var($url, FILTER_SANITIZE_URL);
	   $this->_url = explode('/', $url);
	   $this->load_app();
		$this->controller =  $this->_url[0].'Controller';
		$this->load_library();
		$this->load_page_controller($this->controller);
		$this->load_default();
		$this->load_helper();
		$maintenance = (explode(',',MAINTENANCE));

		if(class_exists($this->controller)){

			for($x=0;$x<count($maintenance);$x++){if($this->controller == $maintenance[$x]){$this->maintenance();exit;}}
			$page = new $this->controller;
			$url_count = count($this->_url);
				if(method_exists($this->controller,$this->_url[0])){
                  if($url_count == 1){
                  	$page_url = $this->_url[0];
							$page->$page_url();
                  }elseif($url_count == 2){
                  	$page_url = $this->_url[1];
							if(method_exists($this->controller,$this->_url[1])){
								$page->$page_url();
							}else{
								$this->chknError();
							}
                  }else{
                  	$page_url = $this->_url[1];
                  	$get = array();
                  	$y=0;
                  	for($x=2;$x<$url_count;$x++){
                  		$get[$y] = $this->_url[$x];
                  		$y++;
                  	}
                  	$request["get"] = $get;
                  	$post = array();

                  	foreach ($_REQUEST as $key => $value) {
                  		if($key != 'url'){
                  			$post[$key] = $value;
                  		}
                  	}
                  	$request["message"] = 'CHKN Framework Request';
                  	$request["post"] = $post;
							if(method_exists($this->controller,$this->_url[1])){
								$page->$page_url($request);
							}else{
								$this->chknError();
							}
                  }
				}else{
					$default = new index;
					$default->index_page();
				}
		}else{
            /**
             * Load error if no class found
             */
			$this->chknError();	
		}
    }

    protected function load_app(){
    	foreach(glob("App/App/*.php") as $filename){
         if($filename != 'App/App/App_Loader.php'){
				include $filename;
			}
     }
    	$app = new App;
    }

    /**
     * Loads all the library
     * DOMPDF - Generates and Converts HTML into PDF Document
     * PHPExcel - Generates an Excel File
     * ReCaptcha - Creates a picture captcha for form security
     */
	protected function load_library(){
		foreach (glob("App/*.php") as $filename){
			if($filename != 'App/Loader.php'){
				include $filename;
			}
      }

      if(file_exists('App/Template/CHKNTemplate.php')){
      	include 'App/Template/CHKNTemplate.php';
      }
    }
    /**
     * Load the global_helper class
     */
	protected function load_helper(){
        foreach(glob("helpers/*.php") as $filename){
            include $filename;
        }
	}
    /**
     * @param $controller
     * Loads a controller which name is base on the value passed by this function
     */
	protected function load_page_controller($controller){
		if(file_exists('controller/'.$controller.'.php')){
			require_once('controller/'.$controller.'.php');
		}
	}
    /**
     * Loads all the default pages of the Framework
     * index - Load the default class that has the highest priority
     * error - Load the error class that notify the user that there is problem accessing a specific class or method or page
     * maintenance - Load the maintenance class that notify the user that the accessed page is under construction or maintenance
     */
	protected function load_default(){
		if(file_exists('controller/index.php')){
			require_once('controller/index.php');
		}
		if(file_exists('App/Defaults/error.php')){
			require_once('App/Defaults/error.php');
		}
		if(file_exists('App/Defaults/maintenance.php')){
			require_once('App/Defaults/maintenance.php');
		}
	}


	protected function chknError(){
		$error = new chknError();
		$error->error_page();
	}
	
	protected function maintenance(){
		$maintenance = new maintenance();
		$maintenance->maintenance_page();
	}
}