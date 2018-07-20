<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OkamiChen\TmsCredit\Controller;

use Illuminate\Routing\Controller as BaseController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\Dashboard;

/**
 * Description of SecretController
 * @date 2018-7-20 13:49:13
 * @author dehua
 */
class SecretController extends BaseController {

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index() {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $content->row(view('tms-credit::credit.form'));
        });
    }
}
