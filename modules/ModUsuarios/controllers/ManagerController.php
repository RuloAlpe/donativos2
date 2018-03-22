<?php

namespace app\modules\ModUsuarios\controllers;

use Yii;
use yii\web\Controller;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\LoginForm;
use vendor\facebook\FacebookI;
use app\modules\ModUsuarios\models\Utils;
use app\modules\ModUsuarios\models\EntUsuariosActivacion;
use app\modules\ModUsuarios\models\EntUsuariosCambioPass;
use app\modules\ModUsuarios\models\EntUsuariosFacebook;
use app\models\EntOrdenesCompras;
use yii\helpers\Url;
use app\models\CatPlanes;
use app\models\Pagos;

/**
 * Default controller for the `musuarios` module
 */
class ManagerController extends Controller {
	
	/**
	 * Registrar usuario en la base de datos
	 */
	public function actionSignUp() {

		$idPlan = null;
		$isSubscripcion = 0;
		$monto = 0;
		if(isset($_POST["plan"])){
			$idPlan = $_POST["plan"];
			$plan = CatPlanes::find()->where(["num_cantidad"=>$idPlan])->one();

			if(!$plan){
				$pagos = new Pagos();
				$plan = $pagos->generarPlan($idPlan);
			}

			$isSubscripcion = isset($_POST["susbcripcion"])?$_POST["susbcripcion"]:0;
			$monto = $plan->num_cantidad;

		}

		if($monto==0){
			return $this->goHome();
		}

		$model = new EntUsuarios ( [ 
				'scenario' => 'registerInput' 
		] );
		// Enviar correo de activación
		$utils = new Utils ();

		$model->password = $utils->generateBoleto();
		$model->repeatPassword = $model->password;
		
		if ($model->load ( Yii::$app->request->post () )){

			$usuarioExiste = EntUsuarios::find()->where(['txt_email'=>$model->email])->one();
			
			if($usuarioExiste){
				
				if (Yii::$app->getUser()->login($usuarioExiste)) {
					if(isset($_POST["plan"]) && isset($_POST["monto"]) ){
            
						$idPlan = $_POST["plan"];
						$plan = CatPlanes::find()->where(["num_cantidad"=>$idPlan])->one();
						$isSubscripcion = isset($_POST["susbcripcion"])?$_POST["susbcripcion"]:0;
						$monto = $plan->num_cantidad;
						
						
						$ordenCompra = new EntOrdenesCompras();
						$ordenCompra->num_total = $monto;
						$ordenCompra->txt_order_number = Utils::generateToken("oc_");
						$ordenCompra->id_usuario = $usuarioExiste->id_usuario;
						$ordenCompra->txt_description = "Donativo";
						$ordenCompra->id_plan = $plan->id_plan;
						$ordenCompra->b_subscripcion = $isSubscripcion;
				
						if ($ordenCompra->save()) {
				
							return $this->redirect(['/site/forma-pago','token'=>$ordenCompra->txt_order_number]);
						}
						
					}
				}
			}
			
			if($user = $model->signup()){

				if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
					
					// Parametros para el email
					$parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([ 
							'/site/ingresar?token=' . $user->txt_token 
					] );
					$parametrosEmail ['user'] = $user->getNombreCompleto ();
					$parametrosEmail ['email'] = $user->txt_email;
					$parametrosEmail ['password'] = $model->repeatPassword;
					
					// Envio de correo electronico
					$utils->sendEmailIngresar ( $user->txt_email,$parametrosEmail );
					/*$this->redirect ( [ 
							'login' 
					] );*/
				}/*else {
					
				}*/
				if(Yii::$app->getUser()->login($user)){
					$idUsuario = $user->id_usuario;
					if(isset($_POST["plan"]) && isset($_POST["monto"]) ){
            
						$idPlan = $_POST["plan"];
						$plan = CatPlanes::find()->where(["num_cantidad"=>$idPlan])->one();
						$isSubscripcion = isset($_POST["susbcripcion"])?$_POST["susbcripcion"]:0;
						$monto = $plan->num_cantidad;
						
						$ordenCompra = new EntOrdenesCompras();
						$ordenCompra->num_total = $monto;
						$ordenCompra->txt_order_number = Utils::generateToken("oc_");
						$ordenCompra->id_usuario = $idUsuario;
						$ordenCompra->txt_description = "Donativo";
						$ordenCompra->id_plan = $plan->id_plan;
						$ordenCompra->b_subscripcion = $isSubscripcion;
				
						if ($ordenCompra->save()) {
				
							return $this->redirect(['/site/forma-pago','token'=>$ordenCompra->txt_order_number]);
						}
						
					}
				}
			}
		}

		return $this->render ( 'signUp', [ 
				'model' => $model,
				'idPlan' =>$idPlan,
				'subscripcion'=>$isSubscripcion,
				'monto'=>$monto
		] );
	}
	
	/**
	 * Crea peticion para el cambio de contraseña
	 */
	public function actionPeticionPass() {
		$model = new LoginForm ();
		$model->scenario = 'recovery';
		if ($model->load ( Yii::$app->request->post () ) && $model->validate ()) {
			
			$peticionPass = new EntUsuariosCambioPass ();
			
			$peticionPass->saveUsuarioPeticion ( $model->userEncontrado->id_usuario );
			$user = $peticionPass->idUsuario;
			
			// Enviar correo de activación
			$utils = new Utils ();
			// Parametros para el email
			$parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl ( [ 
					'cambiar-pass/' . $peticionPass->txt_token 
			] );
			$parametrosEmail ['user'] = $user->getNombreCompleto ();
			
			// Envio de correo electronico
			$utils->sendEmailRecuperarPassword ($user->txt_email, $parametrosEmail );

			Yii::$app->session->setFlash('success', "Se ha enviado un email a: ".$user->txt_email." para conseguir su contraseña.");
		}
		return $this->render ( 'peticionPass', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Cambia la contraseña del usuario
	 *
	 * @param string $t        	
	 */
	public function actionCambiarPass($t = null) {
		$peticion = $this->findPeticionByToken ( $t );
		if (empty ( $peticion )) {
			/**
			 *
			 * @todo Poner mensaje de que la peticion ha expirado
			 */
			return $this->redirect ( [ 
					'peticion-pass' 
			] );
		}
		
		$model = new EntUsuarios ();
		$model->scenario = 'cambiarPass';
		
		// Si los campos estan correctos por POST
		if ($model->load ( Yii::$app->request->post () )) {
			$user = $peticion->idUsuario;
			$user->setPassword ( $model->password );
			$user->save ();
			
			$peticion->updateUsuarioPeticion ();
			


			if (Yii::$app->getUser ()->login ( $user )) {
				return $this->goHome ();
			}
		}
		
		return $this->render ( 'cambiarPass', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Activa la cuenta del usuario
	 *
	 * @param string $t        	
	 */
	public function actionActivarCuenta($t = null) {
		$activacion = $this->findActivacionByToken ( $t );
		
		$usuario = $activacion->idUsuario;
		
		if ($usuario->id_status == EntUsuarios::STATUS_ACTIVED) {
			return $this->goHome ();
		}
		
		$usuario->activarUsuario ();
		$activacion->actualizaActivacion ();
		
		if (Yii::$app->getUser ()->login ( $usuario )) {
			return $this->goHome ();
		}
	}
	
	/**
	 * Loguea al usuario
	 */
	public function actionLogin() {

		//return $this->goHome ();
		$this->layout = "@app/views/layouts/main";
		
		if (! Yii::$app->user->isGuest && $monto != 0) {
			$idUsuario = Yii::$app->user->identity->id_usuario;
        	$ordenCompra = new EntOrdenesCompras();
			$ordenCompra->num_total = $monto;
			$ordenCompra->txt_order_number = Utils::generateToken("oc_");
			$ordenCompra->id_usuario = $idUsuario;
			$ordenCompra->txt_description = "Donativo";

			if ($ordenCompra->save()) {

				return $this->redirect(['site/forma-pago','token'=>$ordenCompra->txt_order_number]);
			}
		}
		
		$model = new LoginForm ();
		$model->scenario = 'login';
		if ($model->load ( Yii::$app->request->post () ) && $model->login ()) {
			
			return $this->goBack ();
		}

		return $this->render ( 'login', [ 
				'model' => $model 
		] );
	}
	
	/**
	 * Callback para facebook
	 */
	public function actionCallbackFacebook($monto=0) {
		$fb = new FacebookI ();
		
		// Obtenemos la respuesta de facebook
		$data = $fb->recoveryDataUserJavaScript ();
		
		// Si no existe la informacion enviada de facebook
		if (gettype ( $data ) == "string") {
			if ($data == "error" || empty ( $data )) {
				$this->redirect ( [ 
						'site/login' 
				] );
			}
		}
		
		// asi podemos obtener sus datos de los amigos
		// foreach($data['friendsInApp'] as $key=>$value){
		// 	$value->id;
		// 	$value->name;
		// }
		
		// Buscamos al usuario por email
		if(!isset($data ['profile'] ['email'])){
			$correo = $data ['profile'] ['id']."@fbemail.com";
			$data ['profile'] ['email'] = $correo;
		}else{
			$correo = $data ['profile'] ['email'];
		}
		$existUsuario = EntUsuarios::findByEmail ( $correo );
		
		// Si no existe creamos su cuenta
		if (! $existUsuario) {
			$entUsuario = new EntUsuarios ();
			$entUsuario->addDataFromFaceBook ( $data );
			
			$existUsuario = $entUsuario->signup (true);
		}
		
		// Buscamos si existe la cuenta de facebook en la base de datos
		$existUsuarioFacebook = EntUsuariosFacebook::getUsuarioFaceBookByIdFacebook ( $data ['profile'] ['id'] );
		
		// Si no existe
		if (! $existUsuarioFacebook) {
			$existUsuarioFacebook = new EntUsuariosFacebook ();
		}
		$existUsuarioFacebook->id_usuario = $existUsuario->id_usuario;
		$usuarioGuardado = $existUsuarioFacebook->saveDataFacebook ( $data );
		
		if (Yii::$app->getUser ()->login ( $existUsuario )) {
			//return $this->redirect(['site/index', 'monto'=>$monto]);
			return $this->goHome ();
		}
	}
	public function actionTest() {
		$utils = new Utils ();
		$utils->sendEmailActivacion ();
	}
	
	/**
	 * Busca la peticion de cambio de contraseña por el token
	 * Si no se encuentra, un 404 HTTP exception sera arrojada.
	 *
	 * @param string $t        	
	 * @return EntUsuariosCambioPass
	 */
	protected function findPeticionByToken($t = null) {
		if (($model = EntUsuariosCambioPass::getPeticionByToken ( $t )) !== null) {
			
			return $model;
		}
	}
	
	/**
	 * Busca la activacion por el token
	 * Si no se encuentra, un 404 HTTP exception sera arrojada.
	 *
	 * @param string $t        	
	 * @return EntUsuariosActivacion
	 * @throws NotFoundHttpException
	 */
	protected function findActivacionByToken($t = null) {
		if (($model = EntUsuariosActivacion::getActivacionByToken ( $t )) !== null) {
			
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
	
	/**
	 * Busca al usuario
	 * Si no se encuentra, un 404 HTTP exception sera arrojada.
	 *
	 * @param string $t        	
	 * @return EntUsuarios
	 * @throws NotFoundHttpException
	 */
	protected function findUsuarioById($id = null) {
		if (($model = EntUsuarios::findIdentity ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
