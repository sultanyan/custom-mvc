<?php 
namespace App\Controllers;
use \Core\View;

	class Home extends \Core\Controller{
		public function indexAction(){
			View::render("Home/index.php", [
				'name' => 'Developer',
				'platforms' => ['Linux', 'OS-X', 'Windows']
				]);
		}

		protected function before(){

		}

		protected function after(){

		}
	}