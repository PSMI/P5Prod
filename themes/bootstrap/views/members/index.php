<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php $this->breadcrumbs = array('Members'=>'#','Member Management'); ?>
<h3>Member Management</h3>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'index-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),        
    ));
?>

<?php echo $this->renderPartial('_search', array('model'=>$model)); ?>

<?php echo $this->renderPartial('_view', array('dataProvider'=>$dataProvider)); ?>

<?php $this->endWidget(); ?>