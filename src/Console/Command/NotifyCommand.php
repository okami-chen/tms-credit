<?php

namespace OkamiChen\TmsCredit\Console\Command;

use Illuminate\Console\Command;
use OkamiChen\TmsCredit\Entity\Bill;

class NotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tms:credit:bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '信用卡账单提醒';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //账单提醒
        $this->processStatement();
        //还款提醒
        $this->processRepayment();
    }
    
    /**
     * 账单信息
     */
    protected function processStatement(){
        $date     = new \Carbon\Carbon();
        $date->addDay(-3);
        $notify     = $date->toDateString();
        
        $rows       = Bill::where(['statement_date'=>$notify])->get();
        $cnt        = count($rows);
        if(!$cnt){
            return true;
        }
        
        foreach ($rows as $key => $row) {

            $content    = [
                '银行：'.$row->card->bank,
                '卡片：'.$row->card->no,
                '额度：'. number_format($row->total_amount, 2),
                '应还：'. number_format($row->bill_amount, 2),
                '通知：'.'请注意更新应还金额',
            ];
            ding(implode("\r\n", $content));
        }
    }
    
    /**
     * 还款信息
     * @return boolean
     */
    protected function processRepayment(){
        $date     = new \Carbon\Carbon();
        $date->addDay(2);
        $notify     = $date->toDateString();
        
        $rows       = Bill::where(['repayment_date'=>$notify])->get();
        $cnt        = count($rows);
        if(!$cnt){
            return true;
        }
        
        foreach ($rows as $key => $row) {
            //已还账单不提醒
            if($row->bill_amount == 0){
                continue;
            }
            $content    = [
                '银行：'.$row->card->bank,
                '卡片：'.$row->card->no,
                '额度：'. number_format($row->total_amount, 2),
                '应还：'. number_format($row->bill_amount, 2),
                '还款：'.$row->repayment_date,
            ];
            ding(implode("\r\n", $content));
        }
    }
}
