<?php

namespace controllers;
use utils\Session;


// Class abstraite parente des controller
abstract class Controller
{
    protected $model;
    protected $modelName;
    protected $session;

    public function __construct()
    {
        // La propriété model créer une nouvelle instance du model correspondant au controller
        $this->model = new $this->modelName();
        $this->session = new Session();
    }
}
