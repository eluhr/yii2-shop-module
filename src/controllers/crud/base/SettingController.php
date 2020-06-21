<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace eluhr\shop\controllers\crud\base;

use eluhr\shop\models\Setting;
    use eluhr\shop\models\search\Setting as SettingSearch;
use eluhr\shop\controllers\crud\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;

/**
* SettingController implements the CRUD actions for Setting model.
*/
class SettingController extends Controller
{


/**
* @var boolean whether to enable CSRF validation for the actions in this controller.
* CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
*/
public $enableCsrfValidation = false;


/**
* Lists all Setting models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel  = new SettingSearch;
    $dataProvider = $searchModel->search($_GET);

Tabs::clearLocalStorage();

Url::remember();
\Yii::$app->session['__crudReturnUrl'] = null;

return $this->render('index', [
'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
]);
}

/**
* Displays a single Setting model.
* @param string $key
*
* @return mixed
*/
public function actionView($key)
{
\Yii::$app->session['__crudReturnUrl'] = Url::previous();
Url::remember();
Tabs::rememberActiveState();

return $this->render('view', [
'model' => $this->findModel($key),
]);
}

/**
* Creates a new Setting model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new Setting;

try {
if ($model->load($_POST) && $model->save()) {
return $this->redirect(['view', 'key' => $model->key]);
} elseif (!\Yii::$app->request->isPost) {
$model->load($_GET);
}
} catch (\Exception $e) {
$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
$model->addError('_exception', $msg);
}
return $this->render('create', ['model' => $model]);
}

/**
* Updates an existing Setting model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param string $key
* @return mixed
*/
public function actionUpdate($key)
{
$model = $this->findModel($key);

if ($model->load($_POST) && $model->save()) {
return $this->redirect(Url::previous());
} else {
return $this->render('update', [
'model' => $model,
]);
}
}

/**
* Deletes an existing Setting model.
* If deletion is successful, the browser will be redirected to the 'index' page.
* @param string $key
* @return mixed
*/
public function actionDelete($key)
{
try {
$this->findModel($key)->delete();
} catch (\Exception $e) {
$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
\Yii::$app->getSession()->addFlash('error', $msg);
return $this->redirect(Url::previous());
}

// TODO: improve detection
$isPivot = strstr('$key',',');
if ($isPivot == true) {
return $this->redirect(Url::previous());
} elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
Url::remember(null);
$url = \Yii::$app->session['__crudReturnUrl'];
\Yii::$app->session['__crudReturnUrl'] = null;

return $this->redirect($url);
} else {
return $this->redirect(['index']);
}
}

/**
* Finds the Setting model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param string $key
* @return Setting the loaded model
* @throws HttpException if the model cannot be found
*/
protected function findModel($key)
{
if (($model = Setting::findOne($key)) !== null) {
return $model;
} else {
throw new HttpException(404, 'The requested page does not exist.');
}
}
}
