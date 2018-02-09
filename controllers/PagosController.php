<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use vendor\facebook\FacebookI;
use app\models\Pagos;
use app\modules\ModUsuarios\models\Utils;
use app\models\CatTiposPago;
use app\models\EntOrdenesCompras;
use yii\web\BadRequestHttpException;
use app\models\EntPagosRecibidos;
use app\models\IPNPayPal;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\EntBoletos;
use app\models\EntTarjetas;

class PagosController extends Controller
{

	public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors() {
		return [ 
				'access' => [ 
						'class' => \yii\filters\AccessControl::className (),
						'only' => [ 
								'generar-ticket-open-pay',
                                'forma-pago',
                                'seleccionar-producto',
                                'generar-orden-compra',
                                'pagar-tarjeta-open-pay'
						],
						'rules' => [
								
								// allow authenticated users
								[ 
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								] 
						] 
				] 
		];
		// everything else is denied
	}

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Genera la orden de compra
     */
    public function actionGenerarOrdenCompra($token=null, $tc=false){

        if(isset($_POST['formaPago'])  ){
           
            $ordenCompra = $this->findOrdenCompra($token);
            $formaPago = $this->getFormaPagoByToken($_POST['formaPago']);
            $ordenCompra->id_tipo_pago = $formaPago->id_tipo_pago;
            $ordenCompra->save();
            return $this->vistaPago($ordenCompra, $tc);

        }

    }

    /**
     * Busca la orden de compra en la base de datosd.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntVoluntario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
     protected function findOrdenCompra($token)
     {
         if (($model = EntOrdenesCompras::findOne(["txt_order_number"=>$token])) !== null) {
             return $model;
         } else {
             throw new NotFoundHttpException('The requested page does not exist.');
         }
     }

	

	/**
	 * IPN para payl pal
	 */
	public function actionIpnPaypal() {
		$payPal = new IPNPayPal ();
		$payPal->payPalIPN ();
	}

    private function vistaPago($ordenCompra, $tc=false){
        
        switch ($ordenCompra->id_tipo_pago){
			case 1:
                return $this->renderAjax('formPayPal', ['ordenCompra'=>$ordenCompra]);
            break;
			case 2:
			
				if($tc){
					$ordenCompraTarjetaCredito = $this->crearOrdenCompra($ordenCompra);
					return $this->renderAjax('openPayCreditCard', [
						"ordenCompra"=>$ordenCompraTarjetaCredito,
						"description" =>  $ordenCompraTarjetaCredito->txt_description,
						"orderId" => $ordenCompraTarjetaCredito->txt_order_number,
						"amount" => $ordenCompraTarjetaCredito->num_total,]);
				}else{
					$ordenCompra = $this->crearOrdenCompra($ordenCompra);
					$charger =  $this->generarOrdenCompraOpenPay($ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total);
				}
				

                
                return $this->renderAjax('openPay', ['charger'=>$charger, 'ordenCompra'=>$ordenCompra]);
            break;

        }
    }

    public function crearOrdenCompra($ordenCompraGuardada){
        
           $ordenNumber = Utils::generateToken('oc_');
           

           $ordenCompra = new EntOrdenesCompras();
           $ordenCompra->txt_order_number = $ordenNumber;
           $ordenCompra->txt_description = $ordenCompraGuardada->txt_description;
           $ordenCompra->id_usuario = $ordenCompraGuardada->id_usuario;
           $ordenCompra->id_tipo_pago = $ordenCompraGuardada->id_tipo_pago;
           $ordenCompra->fch_creacion = Utils::getFechaActual();
           $ordenCompra->b_pagado = 0;
           $ordenCompra->num_total = $ordenCompraGuardada->num_total;
		   $ordenCompra->b_habilitado = 1;
		   $ordenCompra->id_plan = $ordenCompraGuardada->id_plan;
		   $ordenCompra->b_subscripcion = $ordenCompraGuardada->b_subscripcion;
           $ordenCompra->save();
          return  $ordenCompra;
   }

    

    public function actionSeleccionarProducto(){
       $productos = Products::find()->where(['b_enabled'=>1])->orderBy('num_order')->all();


        return $this->render('formaPago', ['productos'=>$productos]);
    }

    private function getProductoByToken($token){
        $producto = Products::find()->where(['txt_product_number'=>$token])->one();

        if($producto){
            return $producto;
        }else{
            throw new NotFoundHttpException ( 'The requested page does not exist.' );
        }
        
    }

    private function getFormaPagoByToken($token){
         $formaPago = CatTiposPago::find()->where(['txt_token'=>$token])->one();

        if($formaPago){
            return $formaPago;
        }else{
            throw new NotFoundHttpException ( 'The requested page does not exist.' );
        }
    }
    
    private function generarOrdenCompraOpenPay($description='Descripción del producto', $orderNumber=null, $monto ){
    	$pago = new Pagos();
    	return $pago->oPCodeBar($description, $orderNumber, $monto);
    }


    public function actionPagarTarjetaOpenPay(){

            $pago = new Pagos();

            if (isset ( $_POST ["token_id"] ) && $_POST ["orderId"] && $_POST['deviceIdHiddenFieldName']) {
			
            $ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$_POST['orderId']])->one();
	
			if (empty ( $ordenCompra )) {
				throw new BadRequestHttpException ( 400, 'Datos requeridos.' );
			}
			
			try {
				$charge = $pago
                    ->createChargeCreditCard ( $ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total, $_POST ["token_id"], $_POST['deviceIdHiddenFieldName']);
				
				echo "success";
			} catch ( Exception $e ) {
				

				echo $e->getMessage ();
				
			}
			
			
			exit;
			
		}
	}
	
	public function actionCrearSubscripcion(){

		$pago = new Pagos();

		if (isset ( $_POST ["token_id"] ) && $_POST ["orderId"] && $_POST['deviceIdHiddenFieldName']) {
		
		$ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$_POST['orderId']])->one();
		$plan = $ordenCompra->idPlan;

		if (empty ( $ordenCompra )) {
			throw new BadRequestHttpException ( 400, 'Datos requeridos.' );
		}
		
		try {
			$charge = $pago
				->addTarjetaCliente ( $ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total, $_POST ["token_id"], $_POST['deviceIdHiddenFieldName'], $plan->txt_plan_open_pay);
			
			echo "success";
		} catch ( Exception $e ) {
			

			echo $e->getMessage ();
			
		}
		
		
		exit;
		
	}
}

    /**
	 * Open pay hara el registro del pago en este action
	 */
	public function actionOpWebHook() {
		$entityBody = file_get_contents ( 'php://input' );
		$json = json_decode ( $entityBody, true );
		
        $this->crearLog('Open Pay', '------------- RECEPCION DE WEBHOOK -------------------');
        $this->crearLog('Open Pay','type: ' . $json ['type'] );
		$this->crearLog('Open Pay',"event_date: " . $json ['event_date']);
		
		
		switch ($json ['type']) {
			case "verification" :
                $this->crearLog('Open Pay',"Codigo de verificacion: " . $json ['verification_code']);
                $this->crearLog('Open Pay',"Id de peticion: " . $json ["id"]);
			
				break;
			
			case "charge.succeeded" :
			 	$this->crearLog('Open Pay',"Inicio de proceso de pago");
				$this->processPaymentOP ( $json, $entityBody );
				break;
		}
	}

    // $userCreditos = new EntUsuariosCreditos();
	// 		$userCreditos->agregarCreditos($comentario->id_usuario, $contestar->costo);

    /**
	 * Guarda registro del pago
	 *
	 * @param unknown $json        	
	 * @param unknown $data        	
	 */
	private function processPaymentOP($json, $data) {
		$transaction = $json ['transaction'];
		
		$txn_id = $transaction ['id'];
		$payment_amount = $transaction ['amount'];
		$payment_currency = "NOT DEFINED";
		$payment_status = $transaction ['status'];
		$quantity = 1;
		$mc_gross = $transaction ['amount'];
		$order_id = $transaction ['order_id'];
		
		$ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$order_id,'b_pagado'=>0])->one();
		
		if(empty($ordenCompra)){

			
			if(!$transaction['order_id'] && ($transaction['method'] == "card")){

				$tarjeta = EntTarjetas::find()->where(["txt_tarjeta"=>$transaction['card']["id"]])->one();

				$pagoRecibido = new EntPagosRecibidos ();
				$pagoRecibido->id_usuario = $tarjeta->id_usuario;
				$pagoRecibido->id_tipo_pago = 2;
				$pagoRecibido->txt_transaccion_local = 'Local';
				$pagoRecibido->txt_notas = 'Notas';
				$pagoRecibido->txt_estatus = $payment_status;
				$pagoRecibido->txt_transaccion = $txn_id;
				$pagoRecibido->txt_cadena_comprador = $data;
				$pagoRecibido->txt_monto_pago = $mc_gross;
				$pagoRecibido->id_orden_compra = null;
				$pagoRecibido->save();

				
			}else{

				$this->crearLog ('OpenPayError', "El order ID no existe o ya esta marcado como completo :" . $order_id );
				return;
			}
		}
			// Carga la orden
		$item_number = $ordenCompra->txt_order_number;
		$custom = $ordenCompra->id_usuario;
		$item_name = $ordenCompra->txt_description;
		
		
		$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "------------- PAGO RECIBIDO de transacción :$txn_id -----------\n\r" );
		
		// Solo genera el log cambiar al de yii
		if (true) {
			
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, 
                                "Item name:" . $item_name . "\n\r" . 
                                "Item number :" . $item_number . "\n\r" . 
                                "quantity :" . $quantity . "\n\r" . 
                                "Payment Status :" . $payment_status . "\n\r" . 
                                "Payment amount :" . $payment_amount . "\n\r" . 
                                "Txn Id :" . $txn_id . "\n\r" . 
                                "custom :" . $custom . "\n\r" . 
                                "mc gross :" . $mc_gross . "\n\r" );
		}
		
		// VALIDA QUE LA TRANSACCION NO SE ENCUIENTRE REGISTRADA EN LA BASE DE DATOS PREVIAMENTE
        $pagoRecibed = EntPagosRecibidos::find()->where(['txt_transaccion'=>$txn_id])->one();

		
		if (! empty ( $pagoRecibed )) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "TRANSACCION REPETIDA: $txn_id \n\r" );
			return;
		}
		
		// Verifica el precio vs el producto
		if (( double ) $ordenCompra->num_total != ( double ) $mc_gross) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "PRODUCTO Y MONTO INCORRECTO: id_product=$item_number AND num_price=$mc_gross\n\r" );
			return;
		}
		
		// Verifica que la cantidad de productos adquiridos sean 1
		if ($quantity != 1) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "CANTIDAD DE PRODUCTOS INCORRECTO: quantity=$quantity\n\r" );
		}
		
		$pagoRecibido = new EntPagosRecibidos ();
		$pagoRecibido->id_usuario = $ordenCompra->id_usuario;
		$pagoRecibido->id_tipo_pago = 2;
		$pagoRecibido->txt_transaccion_local = 'Local';
		$pagoRecibido->txt_notas = 'Notas';
		$pagoRecibido->txt_estatus = $payment_status;
		$pagoRecibido->txt_transaccion = $txn_id;
		$pagoRecibido->txt_cadena_comprador = $data;
		$pagoRecibido->txt_monto_pago = $mc_gross;
		$pagoRecibido->id_orden_compra = $ordenCompra->id_orden_compra;
		
		

		$transaction = Yii::$app->db->beginTransaction();
		$error = false;
		try {
			if ($pagoRecibido->save ()) {

				$ordenCompra->b_pagado = 1;
				if($ordenCompra->save()){
					


					$utils = new \app\modules\ModUsuarios\models\Utils();
					$parametrosEmail = [
							'nombre' => $usuario->txt_username,
							'transaccion'=>$ordenCompra->txt_order_number,
							'totalPagado'=>$ordenCompra->num_total
					];
				
					//$utils->sendPagoNotificacion($usuario->txt_email, $parametrosEmail );

				}else{
					$error = true;
					$this->crearLog('OpenPayUser'.$ordenCompra->id_usuario, 'No se puedo actualizar la orden de compra'. json_encode ( $ordenCompra->errors) );
				}
				
			} else {
				$error = true;
				$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "Error al guardar el pago " . json_encode ( $pagoRecibido->errors) );
			}
			if ($error) {
				$transaction->rollback ();
				return;
			} else {
				$transaction->commit ();
			}
		} catch ( ErrorException $e ) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "Ocurrio un problema al guardar la información=print_r($e)\n\r" );
			$transaction->rollback ();
		}
		
		$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "------------------- PAGO CORRECTO ---------------------\n\r" );
	}
    

    public function crearLog($nombreArchivo,$message){
        
        $basePath = Yii::getAlias('@app'); 
        $fichero = $basePath.'/logsPagos/'.$nombreArchivo.'.log';

        $persona =  Utils::getFechaActual()."\n".$message."\n\n";
        
        $fp = fopen($fichero,"a");
        fwrite($fp,$persona);
        fclose($fp);
    }
	
	public function actionCrearPlanes(){
		$pago = new Pagos();
		$pago->generarPlan();
	}

	public function actionBorrarPlan($id=null){
		$pago = new Pagos();
		$pago->deletePlan($id);
	}

	public function actionBorrarCliente($id=null){
		$pago = new Pagos();
		$pago->borrarCliente($id);
	}

	public function actionBorrarTarjetaCliente($id=null, $idt){
		$pago = new Pagos();
		$pago->borrarTarjeta($id, $idt );
	}

	public function actionBorrarSubscripcionCliente($id=null, $ids=null){
		$pago = new Pagos();
		$pago->borrarSubscripcion($id, $ids);
	}

}
