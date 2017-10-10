<?php
class App{
    public function __construct(){
        $this->app();
        $this->database();
        $this->user();
        $this->css();
        $this->script();
    }

    private function app(){
        define('SERVER',$_SERVER['HTTP_HOST']);
        $file = fopen("config/app.conf","r");
        $app = array();
        $string="";
        while(!feof($file)){
            $string .= fgets($file)."newLine;";
        }

        $ex = explode("newLine;",$string);
        $ex = array_filter($ex);

        $def_array = array();
        for($x=0;$x<count($ex);$x++){
            $pos = strstr($ex[$x],"/*");
            if($pos == ""){
                $vex = explode("=",$ex[$x]);
                if(isset($vex[1])){
                    $def_array[$vex[0]] = $vex[1];
                }
            }
        }

        $defines = array_filter($def_array);
        foreach ($defines as $key => $value) {
            define($key,$value);
        }
        if(LOCAL == 1){
            define ('DEFAULT_URL','http://'.SERVER.'/'.trim(ROOT_FOLDER).'/');
        }

        if(LOCAL != 1){
            define ('DEFAULT_URL','http://'.SERVER.'/');
        }

    }

    private function database(){
        $file = fopen("config/database.conf","r");
        $app = array();
        while(!feof($file)){
           $string = trim(fgets($file));

            if($string != ""){
                $ex = explode("=",$string);
              
                if($ex[0] != ""){
                    $app[$ex[0]] = $ex[1];
                }elseif($ex[0] != array()){
                    $app[$ex[0]] = $ex[1];
                }
            }
        }
        
        foreach($app as $key => $value){
            define($key,$value);
        }
    }

    private function user(){
        $file = fopen("config/user.conf","r");
        $app = array();
        while(!feof($file)){
            $string = trim(fgets($file));
            if(!strstr($string,'*')){
                if($string != ""){
                    $ex = explode("=",$string);
                    if($ex[0] != ""){
                        $app[$ex[0]] = $ex[1];
                    }elseif($ex[0] != array()){
                        $app[$ex[0]] = $ex[1];
                    }
                }
            }
        }

        foreach($app as $key => $value){
            define($key,$value);
        }
        
    }

    private function css(){
        $file = fopen("config/Vendors/stylesheets.conf","r");
        $app = "";
        while(!feof($file)){
            $string = trim(fgets($file));
            if(!strstr($string,'*')){
                if($string != ""){
                    $app .=$string.',';
                }
            }
        }

        $string = substr($app,0,strlen($app)-1);
        
        define('CSS_LIBRARY',$string);

    }

     private function script(){
      $file = fopen("config/Vendors/scripts.conf","r");
        $app = "";
        while(!feof($file)){
            $string = trim(fgets($file));
            if(!strstr($string,'*')){
                if($string != ""){
                    $app .=$string.',';
                }
            }
        }
        $string = substr($app,0,strlen($app)-1);

        define('JS_LIBRARY',$string);
        
    }
}
?>
