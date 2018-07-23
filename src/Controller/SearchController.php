<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OkamiChen\TmsCredit\Controller;

use App\Http\Controllers\Controller;
use OkamiChen\TmsCredit\Entity\Credit;

/**
 * Description of SearchController
 * @date 2018-7-19 18:53:50
 * @author dehua
 */
class SearchController extends Controller {
    
    /**
     * 手机号
     * @return type
     */
    public function card(){
        $q = request('q');
        $rows   = Credit::where('no', 'like', "%$q%")
                ->paginate();

        if(count($rows)){
            $items   = $rows->items();
        
            foreach ($items as $key => $item) {
                $item['text']   = $item['name'] . ' | ' .$item['no'];
            }
            $rows->setCollection(collect($items));            
        }
        return $rows;
    }
}
