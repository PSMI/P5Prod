<?php

class SiteController extends Controller
{
        public $layout = "column2";
        public $msg = '';
        public $title = '';
        public $showDialog = false;
        public $showRedirect = false;
                
    	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
                if(!Yii::app()->user->hasUserAccess() && !Yii::app()->user->isSuperAdmin()) 
                    $this->redirect(array('site/login'));
                
                $model = new Alerts();
                $model->member_id = Yii::app()->user->getId();
                $new_placement = $model->check_new_placements();
                $unassigned_downlines = $model->check_floating_downline();
                                
                $alerts = array
                (
                    'new-placement'=>$new_placement,
                    'unassigned-downline'=>$unassigned_downlines,
                );
                
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',array('alert'=>$alerts));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
                if(isset(Yii::app()->session['member_id']) && isset(Yii::app()->session['account_type_id']))
                        $this->redirect(array("site/index"));
            
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
                
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
                        
			// validate user input and redirect to the previous page if valid
			if ($model->validate())
                        {
                            $err_code = $model->login();
                            
                            if ($err_code == 0) {
                                $this->redirect(array("site/index"));
                            } else if ($err_code == 4) {
                                $members = new Members();
                                $result = $members->getUplineDetailsByUserName($model->username);
                                
                                $table = '<br/><br/><table border="1" style="font-size: 0.8em; width: 100%; text-align: center;">
                                            <tr>
                                                <th>Upline Name</th>
                                                <th>Mobile Number</th>
                                                <th>Telephone Number</th>
                                            </tr>
                                            <tr>
                                                <td>'.$result["upline_name"].'</td>
                                                <td>'.$result["mobile_no"].'</td>
                                                <td>'.$result["telephone_no"].'</td>
                                            </tr>
                                        </table>';
                                
                                Helpers::commonModal("MEMBERSHIP APPROVAL", "Your membership needs to be approved
                                    by your upline before you can login to the system. Kindly contact your upline for approval.
                                    Thank you! Here are the following details of your upline:" . $table);
                            }
                        }
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
        public function action404()
        {
            $this->render('404');
        }
            
       public function actionForgot()
       {
           $model = new LoginForm();
           
           if (isset($_POST['LoginForm']))
           {
               $username = $_POST['LoginForm']['reset_username'];
               $email = $_POST['LoginForm']['email'];
               
               if ($username == "" || $email == "")
               {
                    $this->title = "NOTIFICATION";
                    $this->msg = "Please fill up the required fields!";
                    $this->showDialog = true;
               }
               else
               {
                    $membersModel = new MembersModel();
                    $record = $membersModel->checkExistingEmailAndUsername($email, $username);
                    if (count($record) > 0 && is_array($record)) 
                    {
                         $member_id = $record['member_id'];

                         $reference = new ReferenceModel();
                         $max_rand_lenth = $reference->get_variable_value('MAX_RAND_PASSWORD');
                         $password = Helpers::randomPassword($max_rand_lenth);

                         
                         $retval = $membersModel->changePassword($member_id, $password);
                         if ($retval) 
                         {
                             $param['member_id'] = $member_id;
                             $param['plain_password'] = $password;
                             Mailer::sendChangePassword($param);

                             $this->title = "SUCCESSFUL!";
                             $this->msg = "Your account password successfully changed. An email
                                         notification was sent on your email address. Thank you!";
                             $this->showRedirect = true;
                         }
                         else
                         {
                             $this->title = "NOTIFICATION";
                             $this->msg = "Reset Password Failed!";
                             $this->showDialog = true;
                         }
                    }
                    else
                    {
                         $this->title = "NOTIFICATION";
                         $this->msg = "Account does not exists. Please try again.";
                         $this->showDialog = true;
                    }
               }
           }
           
           $this->render('_forgot', array('model'=>$model));
       }
}
