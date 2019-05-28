<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

use Repo\GenericRepo;
use Model\LoginModel;

return function (App $app) {
    //$container = $app->getContainer();
    
    // Autenticacao
	$app->post('/login', function (Request $request, Response $response, array $args) {
 
		$input = $request->getParsedBody();
		
		$teste = new GenericRepo();
		$teste2 = new LoginModel($input);
		
		$where = "`email` = '" . $teste2->getEmail() . "'";
        
        $res = $teste->encontrar("*", $teste2->getTable(), $where, $this);
	 
		// verify email address.
		if(!$res) {
			return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
		}
	 
		// verify password.
		if (!password_verify($teste2->getPassword(), $res['password'])) {
			return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  
		}
	 
		$settings = $this->get('settings'); // get settings array.
		
		$token = JWT::encode(['id' => $res['id'], 'email' => $res['email']], $settings['jwt']['secret'], "HS256");
	 
		return $this->response->withJson(['token' => $token]);
	 
	});
	
	$app->post('/register', function (Request $request, Response $response, array $args) {
		$input = $request->getParsedBody();
        $teste = new GenericRepo();
		$teste2 = new LoginModel($input);    
        $res = $teste->inserir($teste2->getValues(), $teste2->getTable(), $this);
        return var_dump($res);
	});
	
	$app->group('/api', function(\Slim\App $app) {
 
		$app->get('/user',function(Request $request, Response $response, array $args) {
			return $request->getAttribute('decoded_token_data');
		});
        
        $app->group('/auth', function(\Slim\App $app){        
            $app->get('', function (Request $request, Response $response, array $args) {
				$input = $request->getParsedBody();   
				$teste = new GenericRepo();
				$teste2 = new LoginModel($input);
				
				$where = "`id` = '" . $request->getAttribute('decoded_token_data')['id'] . "'";
				
				$res = $teste->encontrar("*", $teste2->getTable(), $where, $this);
					
				return var_dump($res);
        	});
			$app->put('', function (Request $request, Response $response, array $args) {
				$input = $request->getParsedBody();   
				$teste = new GenericRepo();
				$teste2 = new LoginModel($input);
				
				$where = "`id` = '" . $teste2->getId() . "'";
				
				$res = $teste->atualizar($teste2->getValues(), $teste2->getTable(), $where, $this);
					
				return var_dump($res);
        	});
        });
        
	});
	
	
};
