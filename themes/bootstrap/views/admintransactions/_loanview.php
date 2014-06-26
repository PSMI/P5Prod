<?php
/*------------------------
 * Author: J.O. Pormento
 * Date Created: 02-07-2014
------------------------*/

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'loans-grid',
        'type'=>'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'htmlOptions'=>array('style'=>'font-size:12px'),
        'enablePagination' => true,
        'columns' => array(
                        array(
                            'header' => '',
                            'value' => '$row + ($this->grid->dataProvider->pagination->currentPage
                            * $this->grid->dataProvider->pagination->pageSize + 1)',
                        ),
                        array('name'=>'member_name',
                              'header'=>'Member Name',
                              'htmlOptions' => array('style' => 'text-align:left'),
                              'headerHtmlOptions' => array('style' => 'text-align:left'),
                              'footer'=>'<strong>Total Loans</strong>',
                              'footerHtmlOptions'=>array('style'=>'font-size:14px'),
                        ),
                        array('name'=>'loan_type_id',
                              'header'=>'Type',
                              'value' => '$data["loan_type_id"] == 1 ? "Direct" : "Completion"',
                              'htmlOptions' => array('style' => 'text-align:center'),
                              'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'level_no',
                            'header'=>'Level',
                            'value'=>'$data["loan_type_id"] == 1 ? "" : $data["level_no"]',
                            'htmlOptions' => array('style' => 'text-align:center'),
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'loan_amount',
                            'header'=>'Amount',
                            'value'=>'AdmintransactionsController::numberFormat($data["loan_amount"])',
                            'htmlOptions' => array('style' => 'text-align:center'),
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                            'footer'=>'<strong>'.number_format($total,2).'</strong>',
                            'footerHtmlOptions'=>array('style'=>'text-align:right; font-size:14px'),
                        ),
                        array('name'=>'date_completed',
                            'header'=>'Date Completed',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'date_approved',
                            'header'=>'Date Approved',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'approved_by',
                            'header'=>'Approved By',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'date_claimed',
                            'header'=>'Date Claimed',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'claimed_by',
                            'header'=>'Processed By',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'status',
                            'header'=>'Status',
                            'value' => 'AdmintransactionsController::getStatus($data["status"], 1)',
                            'htmlOptions' => array('style' => 'text-align:center'),  
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('class'=>'bootstrap.widgets.TbButtonColumn',
                            'template'=>'{approve}{claim}{download}',
                            'buttons'=>array
                            (
                                'approve'=>array
                                (
                                    'label'=>'Approve',
                                    'icon'=>'ok-sign',
                                    'url'=>'Yii::app()->createUrl("/admintransactions/processtransaction", array("id" =>$data["loan_id"], "status" => "3", "transtype" => "loan"))',
                                    'visible'=>'AdmintransactionsController::getStatusForButtonDisplayLoan($data["status"], 1)',
                                    'options' => array(
                                        'class'=>"btn btn-small",
                                        'confirm'=>'Are you sure you want to APPROVE?',
                                        'ajax' => array(
                                            'type' => 'GET',
                                            'dataType'=>'json',
                                            'url' => 'js:$(this).attr("href")',
                                            'success' => 'function(data){
                                                if(data.result_code == 0)
                                                {
                                                    alert(data.result_msg);
                                                    $.fn.yiiGridView.update("loans-grid");
                                                    location.reload();
                                                }
                                                else
                                                    alert(data.result_msg);
                                             }',
                                        ),

                                    ),
                                    array('id' => 'send-link-'.uniqid())
                                ),
                                'claim'=>array
                                (
                                    'label'=>'Claim',
                                    'icon'=>'ok-sign',
                                    'url'=>'Yii::app()->createUrl("/admintransactions/processtransaction", array("id" =>$data["loan_id"], "status" => "4", "transtype" => "loan"))',
                                    'visible'=>'AdmintransactionsController::getStatusForButtonDisplayLoan($data["status"], 2)',
                                    'options' => array(
                                        'class'=>"btn btn-small",
                                        'confirm'=>'Are you sure you want to CLAIM?',
                                        'ajax' => array(
                                            'type' => 'GET',
                                            'dataType'=>'json',
                                            'url' => 'js:$(this).attr("href")',
                                            'success' => 'function(data){
                                                if(data.result_code == 0)
                                                {
                                                    alert(data.result_msg);
                                                    $.fn.yiiGridView.update("loans-grid");
                                                }
                                                else
                                                    alert(data.result_msg);
                                             }',
                                        ),

                                    ),
                                    array('id' => 'send-link-'.uniqid())
                                ),
                                'download'=>array
                                (
                                    'label'=>'Download',
                                    'icon'=>'icon-download-alt',
                                    'url'=>'Yii::app()->createUrl("/admintransactions/pdfloans", array("id" =>$data["loan_id"], "member_id" =>$data["member_id"], "loan_type_id" =>$data["loan_type_id"], "level_no" =>$data["level_no"], "member_name" =>$data["member_name"], "loan_amount" =>$data["loan_amount"]))',
                                    'options' => array(
                                        'class'=>"btn btn-small",
                                    ),
                                    array('id' => 'send-link-'.uniqid())
                                ),
                            ),
                            'header'=>'Action',
                            'htmlOptions'=>array('style'=>'width:80px;text-align:center'),
                            ),
        )
        ));
?>
