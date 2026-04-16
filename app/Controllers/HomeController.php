<?php

namespace App\Controllers;

use App\Models\Meal;

class HomeController extends BaseController
{
    public function index()
    {
        $config = require APP_PATH . '/Config/app.php';
        
        $mealModel = new Meal();
        $featuredDishes = array_slice($mealModel->getAllAvailable(), 0, 3);

        $this->render('home/index', array(
            'title' => $config['name'],
            'featuredDishes' => $featuredDishes,
            'environment' => $config['env'],
        ));
    }
}
