<?php
namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\Utils;
use app\models\EntOrdenesCompras;
use app\models\EntPagosRecibidos;
use app\modules\ModUsuarios\models\EntUsuarios;


class IPNPayPal {
	const DEBUG = 1;
	const USE_SANDBOX = 1;

     public function crearLog($nombreArchivo,$message){
        
        $basePath = Yii::getAlias('@app'); 
        $fichero = $basePath.'/logsPagos/'.$nombreArchivo.'.log';

        $persona =  Utils::getFechaActual()."\n".$message."\n\n";
        
        $fp = fopen($fichero,"a");
        fwrite($fp,$persona);
        fclose($fp);
    }
	
	/**
	 * IPN para paypal
	 */
	public function payPalIPN() {
		$save_post = array ();
		
		// Read POST data
		// reading posted data directly from $_POST causes serialization
		// issues with array data in POST. Reading raw POST data from input stream instead.
		$raw_post_data = file_get_contents ( 'php://input' );
		$raw_post_array = explode ( '&', $raw_post_data );
		$myPost = array ();
		foreach ( $raw_post_array as $keyval ) {
			$keyval = explode ( '=', $keyval );
			$this->crearLog('PayPal', "LLave: " . json_encode($keyval));
			
			if (count ( $keyval ) == 2)
				$myPost [$keyval [0]] = urldecode ( $keyval [1] );
				
				// Copia el post para futuro uso
			$save_post [$keyval [0]] = urldecode ( $keyval [1] );
		}
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if (function_exists ( 'get_magic_quotes_gpc' )) {
			$get_magic_quotes_exists = true;
		}
		
		foreach ( $myPost as $key => $value ) {
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc () == 1) {
				$value = urlencode ( stripslashes ( $value ) );
			} else {
				$value = urlencode ( $value );
			}
			$req .= "&$key=$value";
		}
		
		// Post IPN data back to PayPal to validate the IPN data is genuine
		// Without this step anyone can fake IPN data
		
		if (self::USE_SANDBOX) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		$ch = curl_init ( $paypal_url );
		if ($ch == FALSE) {
			return FALSE;
		}
		
		curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $req );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
		
		if (self::DEBUG == true) {
			curl_setopt ( $ch, CURLOPT_HEADER, 1 );
			curl_setopt ( $ch, CURLINFO_HEADER_OUT, 1 );
		}
		
		// CONFIG: Optional proxy configuration
		// curl_setopt($ch, CURLOPT_PROXY, $proxy);
		// curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		
		// Set TCP timeout to 30 seconds
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Connection: Close' 
		) );
		
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below. Ensure the file is readable by the webserver.
		// This is mandatory for some environments.
		
		// $cert = __DIR__ . "./cacert.pem";
		// curl_setopt($ch, CURLOPT_CAINFO, $cert);
		
		$res = curl_exec ( $ch );
		if (curl_errno ( $ch ) != 0) // cURL error
{
			if (self::DEBUG == true) {
                $this->crearLog('PayPal', "\n\r Can't connect to PayPal to validate IPN message: " . curl_error ( $ch ));
				
			}
			curl_close ( $ch );
			exit ();
		} else {
			// Log the entire HTTP response if debug is switched on.
			if (self::DEBUG == true) {
                $this->crearLog('PayPal', "\n\r HTTP request of validation request:" . curl_getinfo ( $ch, CURLINFO_HEADER_OUT ) . " for IPN payload: $req");
                $this->crearLog('PayPal', "\n\r HTTP response of validation request: $res");
				
			}
			curl_close ( $ch );
		}
		
		// Inspect IPN validation result and act accordingly
		
		// Split response headers and payload, a better way for strcmp
		$tokens = explode ( "\r\n\r\n", trim ( $res ) );
		$res = trim ( end ( $tokens ) );
		
		if (strcmp ( $res, "VERIFIED" ) == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment and mark item as paid.
			
			if (self::DEBUG == true) {
                $this->crearLog('PayPal', "\n\r Verified IPN: $req ");
				
			}
			
            $this->crearLog('PayPal',  "\n\r A procesar el pago y guardar.");
			
			$this->processPayment ( $save_post, $req );
		} else if (strcmp ( $res, "INVALID" ) == 0) {
			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			if (self::DEBUG == true) {
                $this->crearLog('PayPal',  "\n\r Invalid IPN: $req");
			}
		}
	}
	
	/**
	 * Procesa la respuesta de paypal
	 * 
	 * @param unknown $_req        	
	 * @param unknown $req        	
	 */
	public function processPayment($_req, $req) {
		$item_name = $_req ['item_name'];
		$item_number = $_req ['item_number'];
		$quantity = $_req ['quantity'];
		$payer_email = $_req ['payer_email'];
		$payment_status = $_req ['payment_status'];
		$payment_amount = $_req ['mc_gross'];
		$payment_currency = $_req ['mc_currency'];
		$txn_id = $_req ['txn_id'];
		$receiver_email = $_req ['receiver_email'];
		$payer_email = $_req ['payer_email'];
		$custom = $_req ['custom'];
		$mc_gross = $_req ['mc_gross'];
		$verify_sign = $_req ['verify_sign'];
		$txt_cadena_pago = $req;
		
         $this->crearLog('PayPal'.$custom,  "\n\r------------- PAGO RECIBIDO de $payer_email transacción :$txn_id -----------");
		
		if (self::DEBUG == true) {
			
            $this->crearLog('PayPal'.$custom,  "\n\r Item name:" . $item_name);
            $this->crearLog('PayPal'.$custom,  "\n\r Item number :" . $item_number);
            $this->crearLog('PayPal'.$custom,  "\n\r quantity :" . $quantity);
            $this->crearLog('PayPal'.$custom,  "\n\r Payment Status :" . $payment_status);
            $this->crearLog('PayPal'.$custom,  "\n\r Payment amount :" . $payment_amount);
            $this->crearLog('PayPal'.$custom,  "\n\r receiver email :" . $receiver_email);
            $this->crearLog('PayPal'.$custom,  "\n\r Txn Id :" . $txn_id);
            $this->crearLog('PayPal'.$custom,  "\n\r custom :" . $custom);
            $this->crearLog('PayPal'.$custom,  "\n\r mc gross :" . $mc_gross);
            $this->crearLog('PayPal'.$custom,  "\n\r verify_sign :" . $verify_sign);
			
		}

        // Verifica que no este pagada la orden de compra
		$ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$item_number, 'b_pagado'=>0])->one();
		
		if (empty ( $ordenCompra )) {
            $this->crearLog('PayPal'.$custom,  "\n\r No existe orden de compra: $item_number ");
			
			return;
		}
		
		$existeTransaccion = $this->existeTransaccion ( $txn_id );
		if ($existeTransaccion) {
            $this->crearLog('PayPal'.$custom,  "\n\r TRANSACCION REPETIDA: $txn_id ");
			
			return;
		}
		
		$isPriceEqual = $this->isPriceEqual ( $ordenCompra, $mc_gross );
		// Verifica el precio vs el producto
		if (! $isPriceEqual) {
            $this->crearLog('PayPal'.$custom,  "\n\r PRODUCTO Y MONTO INCORRECTO: id_product=".(double)$item_number." AND num_price=".(double)$mc_gross);
			
			return;
		}
		
		// Verifica que la cantidad de productos adquiridos sean 1
		if ($quantity != 1) {
            $this->crearLog('PayPal'.$custom,  "\n\r CANTIDAD DE PRODUCTOS INCORRECTO: quantity=$quantity");
			
			return;
		}

        $this->crearLog('PayPal'.$custom, "\n\r id_usuario " . $custom);
        $this->crearLog('PayPal'.$custom,  "\n\r item_number " . $item_number);
        $this->crearLog('PayPal'.$custom,  "\n\r mc_gross " . $mc_gross);
        $this->crearLog('PayPal'.$custom,  "\n\r txn_id " . $txn_id);
        $this->crearLog('PayPal'.$custom,  "\n\r txt_cadena_pago " . $txt_cadena_pago);
        $this->crearLog('PayPal'.$custom,  "\n\r verify_sign " . $verify_sign);
		
	$pagoRecibido = new EntPagosRecibidos();
		$pagoRecibido->id_usuario = $ordenCompra->id_usuario;
		$pagoRecibido->id_tipo_pago = 1;
		$pagoRecibido->txt_transaccion_local = 'Local';
		$pagoRecibido->txt_notas = 'Notas';
		$pagoRecibido->txt_estatus = $payment_status;
		$pagoRecibido->txt_transaccion = $txn_id;
		$pagoRecibido->txt_cadena_comprador = $req;
		$pagoRecibido->txt_monto_pago = $mc_gross;
		$pagoRecibido->id_orden_compra = $ordenCompra->id_orden_compra;
		
		

		$transaction = Yii::$app->db->beginTransaction();
		$error = false;
		try {
			if ($pagoRecibido->save ()) {
				
				
					$ordenCompra->b_pagado = 1;
					
					if (! $ordenCompra->save ()) {
						$error = true;
                        $this->crearLog('PayPal'.$custom, "Error al guardar orden de compra " . json_encode($ordenCompra->errors));
						
					}else{
							$usuario = EntUsuarios::find()->where(['id_usuario'=>$ordenCompra->id_usuario])->one();

							$numBoletos = intval($ordenCompra->num_total/100);

							for($i=0; $i<$numBoletos; $i++){
								$boleto = new EntBoletos();
								$boleto->id_orden_compra = $ordenCompra->id_orden_compra;
								$boleto->id_pago_recibido = $pagoRecibido->id_pago_recibido;
								$boleto->id_usuario = $ordenCompra->id_usuario;
								$boleto->txt_codigo = Utils::generateBoleto($ordenCompra->id_orden_compra);
								$boleto->fch_creacion = Utils::getFechaActual(); 
								
								if($boleto->save()){

								}else{
									$this->crearLog('PayPal'.$custom, "Error al guardar boleto " . json_encode($boleto->errors));
								}
							}

					$utils = new Utils();
					$parametrosEmail = [
							'nombre' => $usuario->txt_username,
							'transaccion'=>$ordenCompra->txt_order_number,
							'totalPagado'=>$ordenCompra->num_total
					];
				
					//$utils->sendPagoNotificacion($usuario->txt_email, $parametrosEmail );
					}
				
				
				
			}else{
				$error = true;
                $this->crearLog('PayPal'.$custom,"Error al guardar el pago " . json_encode($pagoRecibido->errors));
				
				
			}
			if ($error) {
					$transaction->rollback ();
					return;
				} else {
					$transaction->commit ();
				}
			
		} catch ( ErrorException $e ) {
			$this->crearLog('PayPal'.$custom,"Ocurrio un problema al guardar la información=print_r($e)\n\r");
			
			$transaction->rollback ();
		}
		$this->crearLog('PayPal'.$custom,"\n\r ------------- FIN DE PAGO de $payer_email transacción :$txn_id -----------");
		
	}
	
	public function sendEmail($asunto, $view, $data, $usuario) {
		$template = $this->generateTemplatePagoCompletado ( $view, $data );
		$sendEmail = new SendEMail ();
		$sendEmail->SendMailPass ( $asunto, $usuario->txt_correo, $usuario->txt_nombre . " " . $usuario->txt_apellido_paterno, $template );
	}
	
	/**
	 * Generamos template con la informacion necesaria
	 */
	public function generateTemplatePagoCompletado($view, $data) {
	
		// Render view and get content
		// Notice the last argument being `true` on render()
		$content = $this->renderPartial ( $view, array (
				'data' => $data
		), true );
	
		return $content;
	}
	
	 /**
	 * VALIDA QUE LA TRANSACCION NO SE ENCUIENTRE REGISTRADA EN LA BASE DE DATOS PREVIAMENTE
	 */
	public function existeTransaccion($txn_id) {

        $existeTransaccion = EntPagosRecibidos::find()->where(['txt_transaccion'=>$txn_id])->one();
		
		if (empty ( $existeTransaccion )) {
			
			return false;
		}
		return true;
	}
	
	/**
	 * Verifica que el precio enviado por paypal sea igual al del producto
	 */
	public function isPriceEqual($ordenCompra, $mc_gross) {
		
		
		if ((double)$ordenCompra->num_total != (double)$mc_gross) {
            $this->crearLog('PayPal'.$ordenCompra->id_usuario,  "\n\r PRODUCTO Y MONTO INCORRECTO: id_product=$ordenCompra->num_total AND num_price=$mc_gross");
			
			return false;
		}
		
		return true;
	}
	

   
	
}