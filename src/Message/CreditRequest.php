<?php

/*
Omnipay-Paratika

Paratika (Asseco) MOTO/3D gateway for Omnipay payment processing library

İnsya Bilişim Teknolojileri
http://insya.com

@yasinkuyu
07.06.2017
*/

namespace Omnipay\Paratika\Message;

class CreditRequest extends AbstractTransaction
{

	// İade
    public function getData()
    {
 
        $this->actionType = 'CREDIT';
        return parent::getData();
    }

}
