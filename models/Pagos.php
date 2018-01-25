<?php

namespace app\models;

use Yii;

class Pagos {
	const PAY_PAL = 2;
	const OPEN_PAY =12;
	
	/**
	 * Generar codigo para poder pagar en las tiendas
	 */
	public function oPCodeBar($description = null, $orderId = null, $amount) {
		
		$this->alias = Yii::getAlias ( '@app' ) . '/vendor/openpay';
		
		require ($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');
		
		// Pruebas
		
		// $openpay = Openpay::getInstance('mgvepau0yawr74pc5p5x','pk_a4208044e7e4429090c369eae2f2efb3');
		
		$openpay = \Openpay::getInstance ( 'mgvepau0yawr74pc5p5x', 'sk_b1885d10781b4a05838869f02c211d48' );
		
		// Para producción usar el que empieza con sk_ para pruebas el pk y
		
		// para producción hay que cambiar el valor de la variable $sandboxMode a false en el archivo OpenpayApi.php
		
		//$openpay = \Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );
		
		$custom = array (
				
				"name" => "-",
				
				"email" => "correo@dominio.com" 
		);
		
		$chargeData = array (
				
				'method' => 'store',
				
				'amount' => $amount,
				
				'description' => $description,
				
				'customer' => $custom,
				
				'order_id' => $orderId 
		);
		
		$charge = $openpay->charges->create ( $chargeData );
		
		
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
	public function createChargeCreditCard($description = null, $orderId = null, $amount = null, $tokenId=null, $deviceId=null) {
		$this->alias = Yii::getAlias ( '@app' ) . '/vendor/openpay';
		
		require ($this->alias . DIRECTORY_SEPARATOR . 'Openpay.php');
		

		// pruebas
		$openpay = \Openpay::getInstance ( 'mgvepau0yawr74pc5p5x', 'sk_b1885d10781b4a05838869f02c211d48' );
		
		// produccion
		//$openpay = Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );
		
		//$openpay = Openpay::getInstance ( 'muqckh3xbqhszkgapcer', 'sk_e4b7e0e618804517bea2a0fef5e0609e' );
		//$openpay = Openpay::getInstance ( 'mxmzxkxphmwhz8hnbzu8', 'sk_a9c337fd308f4838854f422c802f4645' );
		$usuario = Yii::$app->user->identity->txt_username.' '. Yii::$app->user->identity->txt_apellido_paterno;
		$correo = Yii::$app->user->identity->txt_email;
		$custom = array (
				"name" => $usuario,
				"email" => $correo 
		);
		
		$chargeData = array (
				'method' => 'card',
				'customer' => $custom,
				'source_id' => $tokenId,
				'amount' => ( float ) $amount,
				'description' => $description,
				'order_id' => $orderId,
				// 'use_card_points' => $_POST["use_card_points"], // Opcional, si estamos usando puntos
				'device_session_id' => $deviceId
		);
		
		$charge = $openpay->charges->create ( $chargeData );
		return $charge;
	}
}



