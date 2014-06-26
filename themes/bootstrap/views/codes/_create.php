<?php

/**
 * @author Noel Antonio
 * @date 01-27-2014
 */
$this->breadcrumbs = array(
    'Activation Code Generator'
);
?>

<h3>Activation Code Generator</h3>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'index-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions'=>array('class'=>'well'),
    ));
?>

<div id="form-details">
    <?php echo $form->textFieldRow($model, 'quantity', array('autocomplete'=>'off')); ?>
</div>

<div id="btns">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Generate'));
          $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>'Back', 'htmlOptions'=>array('onclick'=>'location.href = "' . Yii::app()->createUrl("codes/index") . '";'))); ?>
</div>


<?php $this->endWidget(); ?>

<!-- dialog box -->
<?php 
$trigger = false;
$buttons = array();
if ($this->showDialog) 
{
    $buttons = array(
                'OK'=>'js:function(){
                    $(this).dialog("close");
                }'
            );
    $trigger = $this->showDialog;
}
else if ($this->showRedirect) 
{
    $buttons = array(
                'OK'=>'js:function(){
                    location.href = "' . Yii::app()->createUrl("codes/index") . '";
                }'
            );
    $trigger = $this->showRedirect;
}
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'dialog-box',
        'options'=>array(
            'title'=>$this->title,
            'modal'=>true,
            'width'=>'350',
            'height'=>'auto',
            'resizable'=>false,
            'autoOpen'=>$trigger,
            'buttons'=>$buttons,
        ),
)); ?>

<br />
<?php echo $this->msg; ?>
<br />

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- dialog box -->


<!-- confirmation dialog box -->
<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'confirm-box',
        'options'=>array(
            'title'=>$this->title,
            'modal'=>true,
            'width'=>'350',
            'height'=>'auto',
            'resizable'=>false,
            'autoOpen'=>$this->showConfirm,
            'buttons'=>array(
                'Yes'=>'js:function(){
                    $("#hiddenQty").val($("#ActivationCodeModel_quantity").val());
                    $("#generate-form").submit();
                    $(this).dialog("close");
                }',
                'No'=>'js:function(){
                    $(this).dialog("close");
                }'
            )
        ),
)); 
?>

<br />
<?php echo $this->msg; ?>
<br />

<?php echo CHtml::beginForm(array('codes/create'), 'POST', array(
        'id'=>'generate-form',
        'name'=>'generate-form')); 
      echo CHtml::hiddenField('hiddenQty');
      echo CHtml::endForm(); 
      
$this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- confirmation dialog box -->