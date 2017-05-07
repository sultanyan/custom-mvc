<?php 
namespace App\Controllers;

	class Posts extends \Core\Controller{
		public function indexAction(){
			echo "Hello from index action within the Posts controller... Develop me :) ";
			echo '<p> Query string parameters are: <pre>'.
					htmlspecialchars(print_r($_GET, true)) . '</pre> </p>';
		}

		public function addNewAction(){
			echo "Hello from addNew action within the Posts controller... Develop me :) ";
		}

		public function editAction(){
			echo "Hello from edit action within the Posts controller... Develop me :) ";
			echo '<p> Query string parameters are: <pre>'.
					htmlspecialchars(print_r($this->route_params, true)) . '</pre> </p>';
		}
	}