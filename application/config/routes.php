<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//funcion para el usuario
$route['login'] = 'Usuarios/login';
$route['updatenameavatar']="Usuarios/updatenameavatar";


//funcion para el panel
$route['panel'] = 'General/panel';
$route['updatedatos'] = 'General/updatedatos';
