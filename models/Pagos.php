<?php

namespace app\models;

use Yii;

class Pagos
{
	const PAY_PAL = 2;
	const OPEN_PAY = 12;

	// llaves 2gom
	const API_OPEN_PAY = "mgvepau0yawr74pc5p5x";
	const API_OPEN_PAY_SECRET = "sk_b1885d10781b4a05838869f02c211d48";
	const API_OPEN_PAY_PUBLIC = "pk_a4208044e7e4429090c369eae2f2efb3";
	const API_SANDBOX = true;

	// LLaves cliente
	//  const API_OPEN_PAY = "mdkj2jyrw5kagur64bfk";
	//  const API_OPEN_PAY_SECRET = "sk_10fb9b0e51a54412a4df34704b626eb5";
	//  const API_OPEN_PAY_PUBLIC = "pk_be60c6e82b4842dd9103c9e2630537d4";
	//  const API_SANDBOX = true;

	/**
	 * Generar codigo para poder pagar en las tiendas
	 */
	public function oPCodeBar($description = null, $orderId = null, $amount)
	{

		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');
		
		// Pruebas
		
		// $openpay = Openpay::getInstance('mgvepau0yawr74pc5p5x','pk_a4208044e7e4429090c369eae2f2efb3');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);
		
		// Para producción usar el que empieza con sk_ para pruebas el pk y
		
		// para producción hay que cambiar el valor de la variable $sandboxMode a false en el archivo OpenpayApi.php
		
		//$openpay = \Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );

		$custom = array(

			"name" => "-",

			"email" => "correo@dominio.com"
		);

		$chargeData = array(

			'method' => 'store',

			'amount' => $amount,

			'description' => $description,

			'customer' => $custom,

			'order_id' => $orderId
		);

		$charge = $openpay->charges->create($chargeData);


		return $charge;
	}

	/**
	 * Cargo
	 * 
	 * @param string $description        	
	 * @param string $orderId        	
	 * @param string $amount        	
	 * @return unknown
	 */
	public function createChargeCreditCard($description = null, $orderId = null, $amount = null, $tokenId = null, $deviceId = null)
	{
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');
		

		// pruebas
		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);
		
		// produccion
		//$openpay = Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );
		
		//$openpay = Openpay::getInstance ( 'muqckh3xbqhszkgapcer', 'sk_e4b7e0e618804517bea2a0fef5e0609e' );
		//$openpay = Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );
		$usuario = Yii::$app->user->identity->txt_username . ' ' . Yii::$app->user->identity->txt_apellido_paterno;
		$correo = Yii::$app->user->identity->txt_email;
		$custom = array(
			"name" => $usuario,
			"email" => $correo
		);

		$chargeData = array(
			'method' => 'card',
			'customer' => $custom,
			'source_id' => $tokenId,
			'amount' => ( float )$amount,
			'description' => $description,
			'order_id' => $orderId,
				// 'use_card_points' => $_POST["use_card_points"], // Opcional, si estamos usando puntos
			'device_session_id' => $deviceId
		);

		$charge = $openpay->charges->create($chargeData);
		return $charge;
	}

	/**
	 * Cargo
	 * 
	 * @param string $description        	
	 * @param string $orderId        	
	 * @param string $amount        	
	 * @return unknown
	 */
	public function addTarjetaCliente($description = null, $orderId = null, $amount = null, $tokenId = null, $deviceId = null, $idPlanOpenPay=null)
	{
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');
		

		// pruebas
		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);
		
		
		$usuario = Yii::$app->user->identity;

		if(!$usuario->txt_usuario_open_pay){
			$p = new Pagos();
			$respuesta = $p->guardarCliente($ususuarioer->nombreCompleto, $usuario->txt_email);
			$usuario->txt_usuario_open_pay = $respuesta->id;
			$usuario->save();
		}
	
		$customer = $openpay->customers->get($usuario->txt_usuario_open_pay);

		$cardDataRequest =[
			'token_id' => $tokenId,
			'device_session_id' => $deviceId
		];
		$card = $customer->cards->add($cardDataRequest);
		$tarjeta = new EntTarjetas();
		$tarjeta->id_usuario = $usuario->id_usuario;
		$tarjeta->txt_tarjeta = $card->id;
		$tarjeta->save();

		$subscriptionDataRequest = [
			'plan_id' => $idPlanOpenPay,
			'card_id' => $card->id,
			'order_id'=>$orderId];
		
		
		$subscription = $customer->subscriptions->add($subscriptionDataRequest);
		$s = new EntSubscripciones();
		$s->id_usuario = $usuario->id_usuario;
		$s->txt_subscipcion_open_pay = $subscription->id;
		$s->save();
		return $subscription;
	}

	public function generarPlan()
	{
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);

		$planDataRequest = [
			'amount' =>'250',
			'status_after_retry' => 'cancelled',
			'retry_times' => 2,
			'name' => "Donativo 250",
			'repeat_unit' => 'month',
			'trial_days' => '0',
			'repeat_every' => '1',
			'currency' => 'MXN'
		];

		$planDataRequest2 = [
			'amount' => '500',
			'status_after_retry' => 'cancelled',
			'retry_times' => 2,
			'name' => "Donativo 500",
			'repeat_unit' => 'month',
			'trial_days' => '0',
			'repeat_every' => '1',
			'currency' => 'MXN'
		];

		$planDataRequest3 = [
			'amount' => '1000',
			'status_after_retry' => 'cancelled',
			'retry_times' => 2,
			'name' => "Donativo 1000",
			'repeat_unit' => 'month',
			'trial_days' => '0',
			'repeat_every' => '1',
			'currency' => 'MXN'
		];

		$this->guardarPlan($openpay, $planDataRequest);
		$this->guardarPlan($openpay, $planDataRequest2);
		$this->guardarPlan($openpay, $planDataRequest3);

		

	}

	public function guardarCliente($nombre, $email){
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);

		$customerData = [
			'name' => $nombre,
			'email' => $email
		 ];
	   
	   $customer = $openpay->customers->add($customerData);

	   return $customer;
	}

	public function borrarCliente($id){
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);

		$customer = $openpay->customers->get($id);
		$customer->delete();
	}

	public function guardarPlan($openpay, $planDataRequest){
		$plan = $openpay->plans->add($planDataRequest);

		$catPlan = new CatPlanes();
		$catPlan->txt_plan_open_pay = $plan->id;
		$catPlan->txt_nombre = $plan->name;
		$catPlan->num_cantidad = $plan->amount;
		$catPlan->num_intentos = $plan->retry_times;
		$catPlan->num_dias_prueba = $plan->trial_days;
		$catPlan->num_dia_repeticion = $plan->repeat_every;
		$catPlan->txt_moneda = $plan->currency;

		$catPlan->save();
	}

	public function deletePlan($id)
	{
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);


		$plan = $openpay->plans->get($id);
		$plan->delete();
	}

	public function borrarSubscripcion($idCustomer, $idSubscripcion){
		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);

		$customer = $openpay->customers->get($idCustomer);
		$subscription = $customer->subscriptions->get($idSubscripcion);
		$subscription->delete();
	}

	public function borrarTarjeta($idCustomer, $idCard){

		$this->alias = Yii::getAlias('@app') . '/vendor/openpay';

		require($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');

		$openpay = \Openpay::getInstance(self::API_OPEN_PAY, self::API_OPEN_PAY_SECRET);
		$customer = $openpay->customers->get($idCustomer);
		$card = $customer->cards->get($idCard);
		$card->delete();
	}

}



