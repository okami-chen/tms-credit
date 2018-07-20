<?php

namespace OkamiChen\TmsCredit\Observer;

use Illuminate\Encryption\Encrypter;

/**
 * Description of ActiveCronObserver
 *
 * @author 陈德华
 * @email admin@0001000.xyz
 */
class CreditObserver {

    public function saving($card) {
        
        $key = hash_hmac('md5', $card->key, md5($card->key));
        $this->encryp = new Encrypter($key, 'AES-256-CBC');

        if (strlen($card->code) < 6) {
            $card->code = $this->encryp->encrypt($card->code);
        }

        if (strlen($card->expire) < 6) {
            $card->expire = $this->encryp->encrypt($card->expire);
        }
        
        $data   = $card->toArray();
        unset($data['key']);
        $card->setRawAttributes($data);
        return $card;
    }

}
