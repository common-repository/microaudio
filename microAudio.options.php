<?php
// uAudio Options

class microAudioError {

	public $error;
	public $date;
	public $trace;
	public $level;
	
	function __construct($error_string, $level) {
		$this->error = $error_string;
		$this->level = $level;
		$this->date = date('U');
		$this->trace = debug_backtrace();	
	}
}

class microAudioOptions {
	
	protected $options = array(
						'autostart',
						'autoconfig',
						'enable_widget',
						'widget_title',
						'include_jquery',
						'download',
						'widget_title',
						'key',
						'location',
						'debug',
						);
	
	private $name = 'ma_options';
	
	protected $data;
	
	private static $instance;
						
	private function __construct () {
		date_default_timezone_set("America/Los_Angeles");
		$this->data = get_option('ma_options');
		foreach($this->options as $option) {
			if($this->data[$option] == null)
				$this->data[$option] = "";
		}
	}
	
	public static function getInstance() {
		if(!isset($instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return $instance;
	}
	
	function __get($name) {
		if($name == "true") return "true";
		if($name == "false") return "false";
		if(in_array($name, $this->options)) {
			return $this->data[$name] == null ? "" : $this->data[$name];
		} else {
			$this->log_error("Tried to retrieve unknown key $name from options.");
			return null;
		}
	}
	
	function __set($name, $value) {
		if(in_array($name, $this->options)) {
			$this->data[$name] = $value;
			$this->save();
		} else {
			$this->log_error("Tried to set unknown key $name:$value in options.");	
		}	
	}
	
	function save() {
		if(!update_option($this->name, $this->data)) add_option($this->name, $this->data);
	}
	
	function log_error($message,$level=1) {
		$this->data['errors'][] = new microAudioError($message, $level);
		$this->save();
	}
	
	function log_message($message) {
		$this->log_error($message, 3);
	}
	
	function errors() {
		return $this->data['errors'];	
	}
	
	function clear_errors() {
		$this->data['errors'] = array();
		$this->save();
	}
	
	function hasErrors() {
		if(count($this->data['errors']) > 0)	return true;
		return false;
	}
}