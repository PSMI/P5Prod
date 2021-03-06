<?php

/**
 * @author Noel Antonio
 * @date 01-28-2014
 */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'index-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),        
    'htmlOptions'=>array('class'=>'well'),
));

$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Generate Activation Codes',
    'type'=>'primary',
    'size'=>'large',
    'htmlOptions'=>array('onclick'=>'location.href="' . Yii::app()->createUrl("codes/create") . '";')
));

$this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'placement-grid',
        'type'=>'striped bordered condensed',
        'dataProvider'=>$dataProvider,
        'enablePagination' => true,
        'columns' => array(
                        array('name'=>'ActivationCodeBatchID',
                            'header'=>'Batch ID',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["activation_code_batch_id"])', 
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        array('name'=>'GeneratedBy',
                            'header'=>'Generated By',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["member_name"])',  
                        ),
                        array('name'=>'DateGenerated',
                            'header'=>'Date Generated',
                            'type'=>'raw',
                            'value'=>'CHtml::encode(date("M d, Y h:i a", strtotime($data["date_generated"])))',  
                        ),
                        array('name'=>'IPAddress',
                            'header'=>'IP Address',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["generated_from_ip"])',
                        ),
                        array('name'=>'Quantity',
                            'header'=>'Quantity',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["batch_quantity"])',
                            'htmlOptions' => array('style' => 'text-align:center'), 
                            'headerHtmlOptions' => array('style' => 'text-align:center'),
                        ),
                        /*array('name'=>'ActiveCodes',
                            'header'=>'Active Codes',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["batch_quantity"])',
                            'htmlOptions' => array('style' => 'text-align:center'),    
                        ),
                        array('name'=>'UsedCodes',
                            'header'=>'Used Codes',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["batch_quantity"])',
                            'htmlOptions' => array('style' => 'text-align:center'),    
                        ),
                        array('name'=>'Percentage',
                            'header'=>'Percentage',
                            'type'=>'raw',
                            'value'=>'CHtml::encode($data["batch_quantity"])',
                            'htmlOptions' => array('style' => 'text-align:center'),    
                        ),*/
//                        array('name'=>'Action',
//                            'header'=>'Action',
//                            'type'=>'raw',
//                            //'icon'=>'icon-download-alt',
//                            'value'=>'CHtml::link("View Codes", array("codes/codes", "id"=>$data["activation_code_batch_id"]))',
//                            'htmlOptions' => array('style' => 'text-align:center'),    
//                            'headerHtmlOptions' => array('style' => 'text-align:center'),  
//                        ),
                        array('class'=>'bootstrap.widgets.TbButtonColumn',
                            'template'=>'{view}',
                            'buttons'=>array
                            (
                                'view'=>array
                                (
                                    'label'=>'View Codes',
                                    'url'=>'Yii::app()->createUrl("/codes/codes", array("id" =>$data["activation_code_batch_id"]))',
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

$this->endWidget();
?>