<?php

namespace App\Controllers;

use App\Libraries\Config;
use App\Libraries\Session;
use App\Libraries\Input;
use App\Libraries\Csrf;
use App\Libraries\View;
use App\Libraries\Auth;

use App\Models\SettingModel;

class BaseController
{
    public $config;
    public $session;
    public $input;
    public $csrf;
    public $url;
    public $view;
    public $auth;

    public $inject;

    public $settingModel;

    public function __construct()
    {
        $this->config = new Config;
        $this->session = new Session;
        $this->input = new Input;
        $this->csrf = new Csrf;
        $this->settingModel = new SettingModel();

        foreach ($this->settingModel->getAllSettings() as $setting) {
            $this->inject['settings'][$setting->item_key] = $setting->item_val;
        }

        $this->view = new View($this);
        $this->view->setLayout('default');
        $this->auth = new Auth;

        if($this->auth->isLoggedIn()) {
            $this->inject['user'] = $this->auth->user();
        }
    }

    public function errorView()
    {
        $this->inject['meta'] = [
            'title' => $this->inject['settings']['site_title'],
            'keywords' => $this->inject['settings']['site_keywords'],
            'description' => $this->inject['settings']['site_description']
        ];
        $this->view->render('errors/404', $this->inject);
    }
}
