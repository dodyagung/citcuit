<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;

class NonAPIController extends Controller {

    private $view_prefix = 'non_api.';

    public function getAbout() {
        return view($this->view_prefix . 'about');
    }

}
