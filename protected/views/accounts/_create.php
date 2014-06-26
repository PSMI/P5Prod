<?php

/**
 * @author Noel Antonio
 * @date 01-30-2014
 */
?>
<h1>Create Account Profile</h1>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'update-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions'=>array('class'=>'well'),
    ));
?>

<p class="note">Fields with <span style="color: red">*</span> are required.</p>

<table style="width: 50%;">
    <tr>
        <td><?php echo $form->textFieldRow($membersModel, 'member_id', array('readonly'=>true, 'value'=>$maxId)); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($membersModel, 'username'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->passwordFieldRow($membersModel, 'password'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->dropDownListRow($membersModel, 'account_type_id', CHtml::listData($accountList, 'account_type_id', 'account_type_name'), array('prompt'=>'Please Select')); ?></td>
    </tr>
</table>

<div style="border: solid 1px gainsboro; width: auto; margin-top: 2%; margin-bottom: 2%;"></div>

<table style="width: 40%;">
    <tr>
        <td><?php echo $form->textFieldRow($model, 'last_name'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'first_name'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'middle_name'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textAreaRow($model, 'address1'); ?></td>
        <td><?php echo $form->textAreaRow($model, 'address2'); ?></td>
        <td><?php echo $form->textAreaRow($model, 'address3'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'zip_code'); ?></td>
        <td><?php echo $form->dropDownListRow($model, 'gender', array('1'=>'Male', '2'=>'Female'), array('prompt'=>'Please Select')); ?></td>
        <td><?php echo $form->dropDownListRow($model, 'civil_status', array('Please Select', 'Single', 'Married', 'Widow', 'Separated')); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model,'birth_date', array('id'=>'birth_date','readonly'=>'true', 'style'=>'width: 120px; text-align: center;')).
                  CHtml::image(Yii::app()->request->baseUrl."/images/calendar.png","calendar", array("id"=>"calbutton","class"=>"pointer","style"=>"cursor: pointer;"));
                  $this->widget('application.extensions.calendar.SCalendar',
                  array(
                    'inputField'=>'birth_date',
                    'button'=>'calbutton',
                    'showsTime'=>false,
                    'ifFormat'=>'%Y-%m-%d',
                  )); ?>
        </td>
        <td><?php echo $form->textFieldRow($model, 'mobile_no'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'telephone_fax_no'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'email'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'tin_number'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'company'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'occupation_id'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'spouse_name'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'spouse_contact_no'); ?></td>
    </tr>
    <tr>
        <td><?php echo $form->textFieldRow($model, 'beneficiary'); ?></td>
        <td><?php echo $form->textFieldRow($model, 'relationship'); ?></td>
    </tr>
    <tr>
        <td><?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add Account')); $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'label'=>'Back', 'htmlOptions'=>array('onclick'=>'location.href = "' . Yii::app()->createUrl("accounts/index") . '";'))); ?></td>
    </tr>
</table>

<?php $this->endWidget(); ?>

<!-- dialog box -->
<?php 
$trigger = false;
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
                    location.href = "'. Yii::app()->createUrl('accounts/index') . '";
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
            'buttons'=>$buttons
        ),
)); ?>

<br />
<?php echo $this->msg; ?>
<br />

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<!-- dialog box -->