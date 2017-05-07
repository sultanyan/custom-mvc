<?php 
namespace Core;
	class View{
		public static function render($view, $args = []){
			extract($args, EXTR_SKIP);
			$file = "../App/Views/$view";
			if (is_readable($file)) {
				require_once $file;
			}else {
				throw new \Exception("$file not found");
			}
		}
	}
