<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntOrdenesCompras;
use app\modules\ModUsuarios\models\Utils;
use app\models\EntBoletos;
use app\modules\ModUsuarios\models\EntUsuarios;

use app\models\CatPlanes;
use app\models\Pagos;

use app\models\EntPagosRecibidos;


class SiteController extends Controller
{

    public function beforeAction($event)
    {

        // if(isset($_GET['monto'])){
        //     $monto = $_GET['monto'];
        // }else{
        //     $monto = 0;
        // }
        // $session = Yii::$app->session;
        // $session->set('monto', $monto);
        
        return parent::beforeAction($event);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'mis-donaciones', 'forma-pago'],
                'rules' => [
                    [
                        'actions' => ['logout', 'ingreso', 'mis-donaciones', 'forma-pago'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         //'logout' => ['post'],
            //     ],
            // ],
        ];
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($monto=0){

        if($monto<0){
         $monto = $monto * -1;   
        }

        $planes = CatPlanes::find()->all();

        if(!$planes){
            $p = new Pagos();
            $p->generarPlan();
            
            $planes = CatPlanes::find()->all();
        }
          

        return $this->render('index' , ['planes'=>$planes]);
    }

    public function actionMisDonaciones(){
        $idUsuario = Yii::$app->user->identity->id_usuario;
        $boletosUsuario = EntPagosRecibidos::find()->where(['id_usuario'=>$idUsuario])->all();
        
        return $this->render("mis-boletos", ['boletos'=>$boletosUsuario]);   
    }

    /**
    * Action para seleccionar la orden de pago (Paypal u OpenPay)
    */
    public function actionFormaPago($token=null){
        $existeOrdenCompra = $this->findOrdenCompra($token);
        if(!$token || !$existeOrdenCompra ){
            return $this->redirect("index");
        }


        return $this->render("forma-pago", ["tokenOc"=>$token, 'ordenCompra'=>$existeOrdenCompra]);

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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(["index"]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionProcesando($oc=null){
        $ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$oc, 'b_pagado'=>1])->one();

        if(empty($ordenCompra)){
            return $this->render('procesando-pago',  ['oc'=>$oc]);
        }else{
            return $this->redirect(["mis-boletos"]);
        }

    }

    public function actionVerificarPago($oc=null){
        $ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$oc, 'b_pagado'=>1])->one();
        
                if(empty($ordenCompra)){
                  echo "0";
                }else{
                    echo "1";
                }
    }

    public function actionIngresar($token){
        $usuario = EntUsuarios::find()->where(['txt_token'=>$token])->one();
        if($usuario){
            if (Yii::$app->getUser()->login($usuario)) {
                //return $this->goHome ();
                return $this->redirect('index');
            }
        } else {
            echo "Token invalido";
            //$this->render();
        }
    }

    public function actionGuardarOrden($monto){
        $user = Yii::$app->user->identity;

        $idPlan = null;
		$isSubscripcion = 0;
		//$monto = 0;
		if(isset($_POST["plan"]) && isset($_POST["monto"])){
			$idPlan = $_POST["plan"];
			$isSubscripcion = isset($_POST["susbcripcion"]);
			$monto = $_POST["monto"];
			
		}

        $idUsuario = $user->id_usuario;
        $ordenCompra = new EntOrdenesCompras();
        $ordenCompra->num_total = $monto;
        $ordenCompra->txt_order_number = Utils::generateToken("oc_");
        $ordenCompra->id_usuario = $idUsuario;
        $ordenCompra->txt_description = "Donativo";
        $ordenCompra->id_plan = $idPlan;
		$ordenCompra->b_subscripcion = $isSubscripcion;

        if ($ordenCompra->save()) {

            return $this->redirect(['/site/forma-pago','token'=>$ordenCompra->txt_order_number]);
        }
        
    }

    public function actionGracias(){

        return $this->render("gracias");
    }

}
