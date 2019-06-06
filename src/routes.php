<?php

use Slim\App;

use Service\LoginService;

return function (App $app) {

	$login = new LoginService($app);

};
