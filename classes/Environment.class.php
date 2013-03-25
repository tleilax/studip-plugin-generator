<?php
class Environment {
	public function get_bootstrap() { return ''; }
	public function get_constructor_template() { return ''; }
	public function get_initialize_template() { return ''; }
	public function get_main_function_template() { return ''; }
}

class EnvironmentException extends Exception {}