<?php

/*
 * @author : owliber
 * @date : 2014-03-10
 */

class AdminController extends Controller
{
    public $showDialog = false;
    public $dialogTitle;
    public $dialogMessage;
    public $alertTitle;
    public $alertDialog = false;
    public $alertMessage;
    public $errorCode;
    public $layout = "column2";
        
    public $PID;
    public $PIDFile;
    public $PIDLog;
    
    
    public function PID_exists()
    {
        $file = Yii::app()->file;
        $path = Yii::app()->basePath . '/runtime/';
        $this->PIDLog = $path . $this->PIDFile;
        
        if($file->set($this->PIDLog)->exists)
            return true;
        else
            return false;
    }
    
    public function actionScheduler()
    {
        $model = new TransactionQueue();
        $reference = new ReferenceModel();
        
        if(isset($_POST['status']))
        {
            $status = $_POST['status'];
            ($status == 1) ? $new_status = 2 : $new_status = 1;
            $reference->toggle_job_scheduler($new_status);
        }
        
        $rawData = $model->get_pending_transactions();
        
        $dataProvider = new CArrayDataProvider($rawData, array(
                        'keyField' => false,
                        'pagination' => array(
                        'pageSize' => 25,
                    ),
        ));
        
        $job_status = $reference->get_variable_value('JOB_SCHEDULER');
        
        $PIDS = array(
            'GOC.pid',
            'DirectEndorse.pid', 
            'Unilevel.pid',
            'LoanDirect.pid',
            'LoanCompletion.pid'
        );
        
        $path = Yii::app()->basePath . '/runtime/';
        $curdate = date('Y-m-d H:i');
        $max_time = 5;
        
        foreach($PIDS as $PID)
        {
            $this->PIDFile = $PID;
            $this->PIDLog = $path . $this->PIDFile;
            
            if($this->PID_exists())
            {
                $filelastmodified = date("Y-m-d H:i", filemtime($this->PIDLog));
                $unix_time = strtotime($curdate) - strtotime($filelastmodified);
                $elapsed_time = $unix_time / 60;
                $active_pid['PID'] = $PID;
                $active_pid['elapsed_time'] = $elapsed_time;
                $active_pid['last_modified'] = $filelastmodified;
                $active_pid['max_time'] = $max_time;
                $pid_lists[] = $active_pid;
            }
            
        }
                       
        $this->render('scheduler',array('dataProvider'=>$dataProvider,'job_status'=>$job_status,'pid_lists'=>$pid_lists));
    }
    
    public function actionOptions()
    {
        $reference = new ReferenceModel();
        
        if(isset($_POST['variable_id']) && isset($_POST['new_value']))
        {
            $variable_id = $_POST['variable_id'];
            $new_value = $_POST['new_value'];
                
            if(isset($_POST['schedule']))
            {
                $schedule = $_POST['schedule'];
                
                if($schedule == 'm')
                {
                    if($new_value >=1 && $new_value <= 12)
                    {
                        $new_schedule = $new_value . ' MONTH';
                        $reference->update_ref_variables($variable_id, $new_schedule);
                    }
                    else
                    {
                        $this->alertMessage = 'You entered an invalid number of months. Please enter only from values 1 to 12';
                    }
                }

                if($schedule == 'd')
                {
                    if($new_value >=1 && $new_value <= 365)
                    {
                        $new_schedule = $new_value . ' DAY';
                        $reference->update_ref_variables($variable_id, $new_schedule);
                    }
                    else
                    {
                        $this->alertMessage = 'You entered an invalid number of days. Please enter only from values 1 to 31';
                    }
                }

                if($schedule == 'w')
                {
                    if($new_value >=1 && $new_value <= 4)
                    {
                        $new_schedule = $new_value . ' WEEK';
                        $reference->update_ref_variables($variable_id, $new_schedule);
                    }
                    else
                    {
                        $this->alertMessage = 'You entered an invalid number of weeks. Please enter only from values 1 to 4';
                    }
                }
            }
            else
            {
                $reference->update_ref_variables($variable_id, $new_value);
            }
            
            if(!$reference->hasErrors())
                $this->alertMessage = 'You have successfully updated the option values.';
            else
                $this->alertMessage = 'Failed updating option values. Please contact your IT';
            
            $this->alertTitle = 'Modify Option Values';
            $this->alertDialog = true;
        }
        
        if(isset($_POST['payout_rate_id']) && isset($_POST['new_payout_amount']) && isset($_POST['transaction_type_id']))
        {
            $payout_rate_id = $_POST['payout_rate_id'];
            $amount = $_POST['new_payout_amount'];
            $trans_type_id = $_POST['transaction_type_id'];
            
            if($reference->verify_payout_rate($payout_rate_id, $trans_type_id, $amount))
            {
                $this->alertMessage = 'The amount entered is the same with the current payout rate.';
            }
            else
            {
                $reference->update_payout_rate($payout_rate_id,$trans_type_id,$amount);
            
                if(!$reference->hasErrors())
                    $this->alertMessage = 'You have successfully updated the payout rate values.';
                else
                    $this->alertMessage = 'Failed updating payout rate values. Please contact your IT';
            }
            
            $this->alertTitle = 'Modify Payout Rate Values';
            $this->alertDialog = true;
        }
                
        $schedule_options = $reference->get_schedule_variables();
        $rate_options = $reference->get_rates_variables();
        $payout_rates = $reference->get_payout_rates();
        
        $dataProvider = new CArrayDataProvider($schedule_options, array(
                        'keyField' => false,
                        'pagination' => array(
                        'pageSize' => 10,
                    ),
        ));
        
        $dataProvider2 = new CArrayDataProvider($rate_options, array(
                        'keyField' => false,
                        'pagination' => array(
                        'pageSize' => 10,
                    ),
        ));
        
        $dataProvider3 = new CArrayDataProvider($payout_rates, array(
                        'keyField' => false,
                        'pagination' => array(
                        'pageSize' => 10,
                    ),
        ));
        
        $this->render('options',array(
            'dataProvider'=>$dataProvider, 
            'dataProvider2'=>$dataProvider2,
            'dataProvider3'=>$dataProvider3,
        ));
    }
    
    public function actionGetVariableOptions()
    {
        if(Yii::app()->request->isAjaxRequest)
        {   
            $reference = new ReferenceModel();
            
            $variable_id = $_GET['id'];
            $result = $reference->get_variables_by_id($variable_id);
            
            $options[] = array('id'=>$variable_id,'value'=>$result['variable_value'],'text'=>$result['variable_text']);
            echo CJSON::encode($options);
        }
    }
    
    public function actionGetPayoutRates()
    {
        if(Yii::app()->request->isAjaxRequest)
        {   
            $reference = new ReferenceModel();
            
            $payout_id = $_GET['id'];
            $result = $reference->get_payout_rates_by_id($payout_id);
            
            $options[] = array('id'=>$payout_id,'value'=>$result['amount'],'text'=>$result['transaction_type_name'],'trans_type_id'=>$result['transaction_type_id']);
            echo CJSON::encode($options);
        }
    }
    
    public function actionEndProcess()
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            $path = Yii::app()->basePath . '/runtime/';
            $PID = $_GET['pid'];
            $this->PIDFile = $PID;
            $this->PIDLog = $path . $this->PIDFile;
            
            if(!unlink($this->PIDLog))
            {
                $result_code = 1;
                $result_msg = 'Error encountered while terminating the process.';
                
            }
            else
            {
                $result_code = 0;
                $result_msg = 'Process ID is successfully ended.';
            }
                        
            $result = array('result_code'=>$result_code, 'result_msg'=>$result_msg);
            echo CJSON::encode($result);
            Yii::app()->end();
        }
        
    }

}
?>
