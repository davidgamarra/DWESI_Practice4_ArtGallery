<?php

class Controller {
	static function handle(){
		$action = Request::req("action");
		$target = Request::req("target");

		$metodo = $action.ucfirst($target);
		if(method_exists(get_class(), $metodo)){
			self::$metodo();
		} else {
			self::viewIndex();
		}
	}
	
	private static function viewIndex(){
		$db = new DataBase();
		$manager = new ManageUser($db);
		$managerArt = new ManageArt($db);
		$sesion = new Session();
		$nav = new Template('./template/_navvisit.html');
		if($sesion->isLogged()){
			$usuario = $manager->get($sesion->getUser());
			if($usuario->getAdmin() == 1){
				$nav = new Template('./template/_navadmin.html');
			} else {
				$nav = new Template('./template/_navuser.html');
			}
		}
		$todosAutores = $manager->getList();
		$autores = "";
		$i = 1;
		foreach ($todosAutores as $autor) {
			$timeline = "";
			$portfolio = "";
			$item = "";
			$portfolioTemplate = "";
			$portfolioItem = "";
			
			switch($autor->getStyle()){
				case "escultura": 
					$portfolioTemplate = "./template/_escultor.html";
					$portfolioItem = "./template/_escultor_item.html";
					break;
				case "pintura": 
					$portfolioTemplate = "./template/_pintura.html";
					$portfolioItem = "./template/_pintura_item.html";
					break;
				case "fotografia": 
					$portfolioTemplate = "./template/_fotografia.html";
					$portfolioItem = "./template/_fotografia_item.html";
					break;
				default: 
					break;
			}
			
			$todosArt = $managerArt->getList($autor->getEmail());
			foreach($todosArt as $art) {
				$argsart = array(
					"title" => $art->getTitle(),
					"image" => $art->getImage(),
					"cdate" => $art->getCdate()
				);
				$itemTemp = new Template($portfolioItem, $argsart);
				$item .= $itemTemp->get();
			}
			
			$porTemp = new Template($portfolioTemplate, array("items" => $item));
			$portfolio = $porTemp->get();
		    
	    	if($i%2 == 0){
	    		$timeline = 'class="timeline-inverted"';
	    	}
	    	$argsaut = array(
				"alias" => $autor->getAlias(),
				"image" => $autor->getImage(),
				"description" => $autor->getDescription(),
				"inverted" => $timeline,
				"portfolio" => $portfolio
			);
			$templateAutor = new Template('./template/_author.html', $argsaut);
			$autores .= $templateAutor->get();
			$i++;
		    
		}
		$args = array(
			"linksnav" => $nav->get(),
			"autores" => $autores
		);
		$template = new Template('./template/_index.html', $args);
		$template->show();
		$db->close();
	}
	
	private static function viewAdmin(){
		$db = new DataBase();
		$manager = new ManageUser($db);
		$managerArt = new ManageArt($db);
		$sesion = new Session();
		$todosAutores = $manager->getListAdmin();
		$autores = "";
		$i = 1;
		foreach ($todosAutores as $autor) {
			$timeline = "";
			$portfolio = "";
			$item = "";
			$portfolioTemplate = "";
			$portfolioItem = "";
			
			switch($autor->getStyle()){
				case "escultura": 
					$portfolioTemplate = "./template/_escultor.html";
					$portfolioItem = "./template/_escultor_item.html";
					break;
				case "pintura": 
					$portfolioTemplate = "./template/_pintura.html";
					$portfolioItem = "./template/_pintura_item.html";
					break;
				case "fotografia": 
					$portfolioTemplate = "./template/_fotografia.html";
					$portfolioItem = "./template/_fotografia_item.html";
					break;
				default: 
					break;
			}
			
			$todosArt = $managerArt->getList($autor->getEmail());
			foreach($todosArt as $art) {
				$argsart = array(
					"title" => $art->getTitle(),
					"image" => $art->getImage(),
					"cdate" => $art->getCdate()
				);
				$itemTemp = new Template($portfolioItem, $argsart);
				$item .= $itemTemp->get();
			}
			
			$porTemp = new Template($portfolioTemplate, array("items" => $item));
			$portfolio = $porTemp->get();
		    
	    	if($i%2 == 0){
	    		$timeline = 'class="timeline-inverted"';
	    	}
	    	$argsaut = array(
				"alias" => $autor->getAlias(),
				"image" => $autor->getImage(),
				"description" => $autor->getDescription(),
				"inverted" => $timeline,
				"email" => $autor->getEmail()
			);
			$templateAutor = new Template('./template/_authoradmin.html', $argsaut);
			$autores .= $templateAutor->get();
			$i++;
		    
		}
		$args = array(
			"autores" => $autores
		);
		$template = new Template('./template/_admin.html', $args);
		$template->show();
		$db->close();
	}
	
	private static function viewLogin($mensaje = ""){
		$args = array(
			"mensaje" => $mensaje
		);
		$template = new Template('./template/_login.html', $args);
		$template->show();
	}
	
	private static function viewRegister($mensaje = ""){
		$args = array(
			"mensaje" => $mensaje
		);
		$template = new Template('./template/_register.html', $args);
		$template->show();
	}
	
	private static function viewProfile(){
		$sesion = new Session();
		if($sesion->isLogged()){
			$db = new DataBase();
			$manager = new ManageUser($db);
			$usuario = $manager->get($sesion->getUser());
			$args = array(
				"email" => $usuario->getEmail(),
				"alias" => $usuario->getAlias(),
				"description" => $usuario->getDescription(),
				$usuario->getStyle() => "selected"
			);
			$template = new Template('./template/_profile.html', $args);
			$template->show();
			$db->close();
		} else {
			$template = new Template('./template/_index.html');
			$template->show();
		}
	}
	
	private static function insertUser(){
		$db = new DataBase();
		$manager = new ManageUser($db);
		
		$email = Request::post("email");
		$pass1 = Request::post("pass");
		$pass2 = Request::post("rpass");
		
		$disponible = $manager->get($email);
		
		if($pass1 === $pass2 && $disponible->getEmail() === null){
			$usuario = new User($email, $pass1, explode("@", $email)[0], "./resources/no_image.jpg", "pintura", "Tu descripción aquí", 2);
			$manager->insert($usuario);
			self::viewLogin();
		} else {
			self::viewRegister("Algún campo es incorrecto");
		}
		$db->close();
	}
	
	private static function editUser(){
		$sesion = new Session();
		if($sesion->isLogged()){
			$db = new DataBase();
			$manager = new ManageUser($db);
			
			$newemail = Request::post("newemail");
			$pass = Request::post("pass");

			$email = Request::post("email");
			$alias = Request::post("alias");
			$style = Request::post("style");
			$publicar = Request::post("publicar");
			$description = Request::post("description");
			
			$usuario = $manager->get($email);
			if($newemail != null){
				$usuario->setEmail($newemail);
			}
			if($pass != null){
				$usuario->setPass($pass);
			}
			$usuario->setAlias($alias);
			$usuario->setStyle($style);
			$usuario->setDescription($description);
			if($publicar === "0"){
				$usuario->setAdmin(0);
			} else {
				$usuario->setAdmin(2);
			}
			
			$photo = new FileUpload("image");
			if($photo->getError() === false){ 
			    $usuario->setImage("./resources/users/".$usuario->getAlias().".jpg");
			    
			    $photo->setDestination("./resources/users/");
			    $photo->setName($usuario->getAlias());
			    $photo->upload();
			}

			$manager->setEmail($usuario, $email);
			$db->close();
		}
		self::viewIndex();
	}
	
	private static function editadminUser(){
		$sesion = new Session();
		if($sesion->isLogged()){
			$db = new DataBase();
			$manager = new ManageUser($db);
			$usuario = $manager->get(Request::get("email"));
			$args = array(
				"email" => $usuario->getEmail(),
				"alias" => $usuario->getAlias(),
				"description" => $usuario->getDescription(),
				$usuario->getStyle() => "selected"
			);
			$template = new Template('./template/_profileadmin.html', $args);
			$template->show();
			$db->close();
		} else {
			$template = new Template('./template/_index.html');
			$template->show();
		}
	}
	
	private static function insertArt(){
		$sesion = new Session();
		if($sesion->isLogged()){
			$db = new DataBase();
			$manager = new ManageArt($db);
			$managerUser = new ManageUser($db);
			
			$email = Request::post("email");
			$title = Request::post("title");
			
			$usuario = $managerUser->get($email);
			$art = new Art();
			$art->setEmail($email);
			$art->setTitle($title);
			$art->setCdate(date('Y-m-d G:i:s'));
			
			$fecha = date('_Y_m_d_G_i_s');
			
			$photo = new FileUpload("image");
			if($photo->getError() === false){ 
			    $art->setImage("./resources/art/".$usuario->getAlias().$fecha.".jpg");
			    
			    $photo->setDestination("./resources/art/");
			    $photo->setName($usuario->getAlias().$fecha);
			    $photo->upload();
			}

			$manager->insert($art);
			$db->close();
		}
		self::viewProfile();
	}
	
	private static function loginUser(){
		$db = new DataBase();
		$manager = new ManageUser($db);
		
		$user = Request::post("email");
		$pass = Request::post("pass");
		
		$usuario = $manager->get($user);
		$sesion = new Session();
		
		if($usuario !== null && $usuario->getPass() === sha1($pass)){
			$sesion->setUser($user);
			self::viewIndex();
		} else {
			$sesion->destroy();
			self::viewLogin("Login incorrecto");
		}
		$db->close();
	}
	
	private static function logoutUser(){
		$sesion = new Session();
		$sesion->destroy();
		self::viewIndex();
	}
		
}