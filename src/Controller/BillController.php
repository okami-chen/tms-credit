<?php

namespace OkamiChen\TmsCredit\Controller;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use OkamiChen\TmsCredit\Entity\Bill;
use Encore\Admin\Grid\Filter;
use OkamiChen\TmsCredit\Entity\Credit;
use Carbon\Carbon;

class BillController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('账单管理');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('账单管理');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('账单管理');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Bill::class, function (Grid $grid) {

            $grid->id('编号')->sortable();
            $grid->column('card.bank','银行');
            $grid->column('card.title','名称');
            $grid->column('card.no','卡号');
            $grid->column('bill_amount', '应还')->display(function($text){
                return number_format($text, 2);
            });
            $grid->column('total_amount', '额度')->display(function($text){
                return number_format($text, 2);
            });
            $grid->column('statement_date','账单')->sortable();
            $grid->column('repayment_date','还款')->sortable();
            $grid->column('test', '免息')->display(function($text){
                $carbon = new Carbon();
                $time = new Carbon($this->repayment_date);
                return sprintf('%02d', $time->diffInDays($carbon));
            });
            $grid->created_at('创建');
            $grid->updated_at('更新');
            
            $grid->filter(function(Filter $filter){
                $filter->disableIdFilter();
                $filter->gt('statement_date', '账单')->date();
                $filter->gt('repayment_date', '还款')->date();
            });
            
            $grid->model()->orderBy('repayment_date','asc');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Bill::class, function (Form $form) {

            $form->display('id', '编号');
            //卡片
            $ajax  = route('tms.service.credit.search');
            $form->select('card_id', '卡片')->options(function ($id) {
                $card = Credit::find($id);
                if ($card) {
                    return [$card->id => $card->title .' | '.$card->no];
                }
            })->ajax($ajax);
            $form->text('bill_amount', '应还');
            $form->text('total_amount','总额度');
            $form->date('statement_date', '账单日');
            $form->date('repayment_date','还款日');
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
