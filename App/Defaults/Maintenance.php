<?php
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Class maintenance
 * Holds the function that will load maintenance page on the class that is defined on the Settings Library
 * $this->html calls the maintenance template
 * $this->html->assign assigns value for variable DEFAULT_PATH
 */

class maintenance{
	public function maintenance_page(){
		$template = file_get_contents(DEFAULT_URL.'view/defaults/maintenance.tpl');
      $template = str_replace('{MAINTENANCE_PATH}',DEFAULT_URL,$template);
      echo $template;
	}
}
	