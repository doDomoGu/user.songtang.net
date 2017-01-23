<?php
namespace api\controllers;

use Qiniu\Http\Response;
use Yii;

class SiteController extends BaseController
{


    public function actionIndex()
    {
        $errFile = __DIR__.'/../runtime/logs/api.log';
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();


        $result = json_encode(['get'=>$get,'post'=>$post]);
        error_log(date('Y-m-d H:i:s').'  '.$result."\n",3,$errFile);

        return ['get'=>$get,'post'=>$post];


        /*return ['222','333'];
$g = Yii::$app->request->get();
$p = Yii::$app->request->post();
        var_dump($g);echo '<br/>';
        var_dump($p);echo '<br/>';


        Yii::$app->end();*/
    }

    public function actionError(){
        $result = ['error_response'=>
            [
                'code'=>404,
                'msg'=>'非法请求'
            ]
        ];
        $response=Yii::$app->response;
        $response->format= \yii\web\Response::FORMAT_JSON;
        $response->data = $result;

        return $response->send();
    }
}
