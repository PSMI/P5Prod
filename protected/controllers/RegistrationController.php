<?php

/*
 * @author : owliber
 * @date : 2014-02-01
 */

class RegistrationController extends Controller
{
    public $layout = 'column2';
    
    public $dialogTitle;
    public $dialogMessage;
    public $showDialog = false;
    public $showConfirm = false;
    public $alertType = 'info';
    public $errorCode;
    
    public function actionIndex()
    {
        $model = new RegistrationForm();
        $model->member_id = Yii::app()->session['member_id'];
        
        if ($_POST['hidden_flag'] == 1)
        {
            $model->attributes = $_POST['RegistrationForm'];
            
            //process registration
            $retval = $model->register();                    
            if($retval['result_code'] == 0)
            {
                $model->triggerRunningAccountAfterInsert();
                //send email notification
                $param['member_id'] = $model->new_member_id;
                $param['plain_password'] = $model->plain_password;

                Mailer::sendVerificationLink($param);

                $param2['upline_id'] = $model->upline_id;
                $param2['new_member_id'] = $model->new_member_id;
                $param2['endorser_id'] = $model->member_id;                      

                Mailer::sendUplineNotification($param2);

                $this->dialogMessage = '<strong>Well done!</strong> You have successfully registered our new business partner.';
            }
            else
            {
                $this->dialogMessage = '<strong>Ooops!</strong> A problem encountered during the registration. Please contact P5 support.';
            }

            $this->errorCode = $retval['result_code'];
            $this->showDialog = true;
        }
        
        else if(isset($_POST['RegistrationForm']))
        {
            $model->attributes = $_POST['RegistrationForm'];
            
            if($model->validate())
            {
                $activation = new ActivationCodeModel();
                //Validate activation code
                $result = $activation->validateActivationCode($model->activation_code);
                
                if(count($result) > 0)
                {
                    $retname = $model->validateMemberName();
                    
                    if (is_array($retname)) 
                    {
                        $this->dialogMessage = '<strong>Ooops!</strong> Member name already exist. Please use another name or append some characters you preferred to make it unique.';
                        $this->errorCode = 6;
                        $this->showDialog = true;
                    }
                    else 
                    {
                        $this->showConfirm = true;
                    }
                    
                }
                else
                {
                    $this->dialogMessage = '<strong>Ooops!</strong> The activation code entered is invalid. Please make sure you have entered the code correctly.';
                    $this->errorCode = 6; //Activation code already in used.
                    $this->showDialog = true;
                }
                $this->dialogTitle = 'IBP Registration';
            }
        }
        
        $this->render('index',array('model'=>$model));
    }
    
    public function actionDownlines()
    {
        if(Yii::app()->request->isAjaxRequest && isset($_GET['term']))
        {
            $model = new RegistrationForm();

            $result = $model->selectDownlines($_GET['term']);

            if(count($result)>0)
            {
                foreach($result as $row)
                {
                    $arr[] = array(
                        'id'=>$row['member_id'],
                        'value'=>$row['member_name'],
                        'label'=>$row['member_name'],
                    );
                }

                echo CJSON::encode($arr);
                Yii::app()->end();
            }
            
        }
    }
    
    public function actionConfirm()
    {
        $info = array();
        
        if (isset($_POST)) {
            $info[0]["member_name"] = strtoupper($_POST["last_name"] . ", " . $_POST["first_name"] . " " . $_POST["middle_name"]);
            $info[0]["upline_name"] = Networks::getMemberName($_POST["upline_id"]);
            $info[0]["endorser_name"] = Networks::getMemberName(Yii::app()->user->getId());
        }
        
        $dataProvider = new CArrayDataProvider($info, array(
                        'keyField' => false,
                        'pagination' => false
        ));
        
        $this->renderPartial('_position', array('dataProvider'=>$dataProvider));
    }
    
}
?>
