<?php

namespace OkamiChen\TmsCredit\Controller;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use OkamiChen\TmsCredit\Entity\Credit;
use Encore\Admin\Grid\Filter;
use Illuminate\Encryption\Encrypter;

class CreditController extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index() {
        return Admin::content(function (Content $content) {

                    $content->header('信用卡管理');
                    $content->description('卡片列表');

                    $content->body($this->grid());
                });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id) {
        return Admin::content(function (Content $content) use ($id) {

                    $content->header('信用卡管理');
                    $content->description('编辑');

                    $content->body($this->form()->edit($id));
                });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create() {
        return Admin::content(function (Content $content) {

                    $content->header('信用卡管理');
                    $content->description('新增');

                    $content->body($this->form());
                });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid() {
        return Admin::grid(Credit::class, function (Grid $grid) {

            $grid->column('id', '编号')->sortable();
            $grid->column('bank', '银行')->sortable();
            $grid->column('name', '持卡人')->display(function($name) {
                return '**' . mb_substr($name, -1);
            });
            $grid->column('brand', '品牌')->sortable();
            $grid->column('title', '名称');
            $grid->column('no', '卡号');


            if (request('ppkey', null)) {
                $key = request('ppkey', null);
                $key = hash_hmac('md5', $key, md5($key));
                $hash = new Encrypter($key, 'AES-256-CBC');

                $grid->column('expire', '有效期')->display(function($expire) use($hash) {
                    try {
                        return $hash->decrypt($expire);
                    } catch (\Exception $ex) {
                        return '';
                    }
                    
                });

                $grid->column('code', '校验码')->display(function($code) use($hash) {
                    try {
                        return $hash->decrypt($code);
                    } catch (\Exception $ex) {
                        return '';
                    }
                    
                });
            }
            $grid->column('remark', '备注');

            $grid->filter(function(Filter $filter) {
                $filter->disableIdFilter();
                $filter->equal('bank', '银行')->select($this->getBanks());
                $filter->equal('brand', '品牌')->select($this->getBrands());
                $filter->equal('name', '持卡')->select($this->getNames());
                $filter->like('no', '卡号');
                $filter->like('remark', '备注');
                $filter->useModal();
            });

            $grid->paginate(50);

            $grid->disableExport();
            $grid->model()->orderBy('bank', 'asc')->orderBy('title', 'asc');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form() {
        return Admin::form(Credit::class, function (Form $form) {
                    if ($form->model()->id) {
                        $form->display('id', 'ID');
                    }
                    $options = $this->getBanks();

                    $brands = $this->getBrands();
                    $form->select('bank', '银行')->options($options)->rules('required');
                    $form->select('brand', '品牌')->options($brands)->rules('required');
                    $form->text('name', '持卡人')->rules('required');
                    $form->text('title', '名称')->rules('required');
                    $form->text('no', '卡号')->rules('required');
                    $form->text('expire', '有效期')->rules('required');
                    $form->text('code', '校验码')->rules('required');
                    $form->text('remark','备注');
                    $form->password('key', '密钥');
                });
    }

    protected function getBrands() {
        return [
            '银联' => '银联',
            '维萨' => '维萨',
            '运通' => '运通',
            '大莱' => '大莱',
            '日本' => '日本',
            '万事达' => '万事达',
        ];
    }

    protected function getBanks() {
        return [
            '工行' => '工行',
            '浦发' => '浦发',
            '招行' => '招行',
            '华夏' => '华夏',
            '中信' => '中信',
            '中行' => '中行',
            '农行' => '农行',
            '广发' => '广发',
        ];
    }

    protected function getNames() {
        return [
            '陈德华' => '陈德华',
            '陈云海' => '陈云海',
            '袁金波' => '袁金波',
            '洪婷' => '洪婷',
        ];
    }

}
