<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OkamiChen\TmsCredit\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Credit
 * @date 2018-6-5 10:41:11
 * @author dehua
 */
class Bill extends Model {

    public $timestamps = true;
    
    protected $table = 'bill';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    public function card(){
        return $this->belongsTo(Credit::class, 'card_id');
    }

}
