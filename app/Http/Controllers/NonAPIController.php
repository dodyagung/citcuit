<?php

namespace App\Http\Controllers;

class NonAPIController extends Controller {

    private $view_prefix = 'non_api.';

    public function getAbout() {
        return view($this->view_prefix . 'about');
    }

}
