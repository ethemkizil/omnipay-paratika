# Omnipay: Paratika

**Paratika (Asseco) (Akbank, TEB, Halkbank, Finansbank, İş Bankası, Şekerbank, Vakıfbank ) gateway for Omnipay payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Paratika (Turkish Payment Gateways) support for Omnipay.


Paratika Api Entegrasyonu, Asseco firmasının yeni sanal pos hizmeti için Omnipay kütüphanesi. 
Akbank, TEB, Halkbank, Finansbank, İş Bankası, Şekerbank ve Vakıfbank taksit imkanı sunuyor. 


## Installation

    composer require ethemkizil/omnipay-paratika

## Basic Usage

The following gateways are provided by this package:

* Paratika
    - Akbank
    - TEB
    - Hakbank 
    - Finansbank
    - İş Bankası 
    - Şekerbank
    - Vakıfbank 

Gateway Methods

* authorize($options) - authorize an amount on the customer's card
* capture($options) - capture an amount you have previously authorized
* purchase($options) - authorize and immediately capture an amount on the customer's card
* refund($options) - refund an already processed transaction
* void($options) - generally can only be called up to 24 hours after submitting a transaction
* session($options) - session parameters required to purchase.
* query($options) - query for various other inquiries.

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.
 
## Samples

			require __DIR__ . '/vendor/autoload.php';

			use Omnipay\Omnipay;

			$gateway = Omnipay::create('Paratika');
			$gateway->setMerchant('10000000');
			$gateway->setMerchantUser('test@test.net');
			$gateway->setMerchantPassword('Paratika123');
			$gateway->setSecretKey('QOClasdJUuDDWasdasdasd');
			$gateway->setBank('ISBANK');

			$gateway->setMode("NonDirectPost3D");
			//Diğer paremetreler: api DirectPost3D NonDirectPost3D
			//3D test için işlem şifresi a ya da 1

			// Zorunlu parametreler
			$card = [
			    'number'        => '5456165456165454',
			    'expiryMonth'   => '12',
			    'expiryYear'    => '2020',
			    'cvv'           => '000',

			    'email'         => 'info@test.com',
			    'firstname'     => 'Insya',
			    'lastname'      => 'Bilisim',
			    'phone'         => '95555050505',

			    'billingAddress1' => 'Test sokak',
			    'billingCity'     => 'Tekirdag',
			    'billingPostcode' => '59850',
			    'billingCountry'  => 'Turkey',

			    'shippingAddress1' => 'Test sokak',
			    'shippingCity'     => 'Tekirdag',
			    'shippingPostcode' => '59850',
			    'shippingCountry'  => 'Turkey'
			];

			try {
			 
			    $options = [
			        'amount'        => 100.00,
			        'currency'      => 'TRY',
			        //'installment'   => 0, // Taksit
			        'orderId'       => 'S-12341308', // Benzersiz olmalı.
			        'returnUrl'     => 'http://local.desktop/Paratika/callback.php',
			        'cancelUrl'     => 'http://local.desktop/Paratika/callback.php',
			        'sessionType'   => 'PAYMENTSESSION', //Diğer parametreler: PAYMENTSESSION WALLETSESSION
			        'card'          => $card,
			    ];

			    // SessionToken almak için oturum açalım
			    $sessionResponse = $gateway->session($options)->send();
			    
			    if ($sessionResponse->isSuccessful()) {
			        
			            $sessionToken =  $sessionResponse->getSessionToken();

			            // Oturum değiştikenini satış ve diğer işlemlerde kullanmak için tanımlayalım.
			            $gateway->setSessionToken($sessionToken);

			            // Auth (Satış) işlemi
			            $response = $gateway->purchase($options)->send();

			            if ($response->isSuccessful()) {
			                echo "İşlem başarılı transactionId:". $response->getTransactionId();
			            } elseif ($response->isRedirect()) {
			                $response->redirect();
			            } else {
			                echo $response->getMessage();
			            }

			    } elseif ($sessionResponse->isRedirect()) {
			        $sessionResponse->redirect();
			    } else {
			        echo $sessionResponse->getMessage();
			    }

			 
			} catch (\Exception $e) {
			    echo $e->getMessage();
			}
