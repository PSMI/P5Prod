<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Yii::app()->clientScript->registerScript('ui','
         
     $(\'input[rel="tooltip"]\').tooltip();     
     var member_name = $("#MemberDetailsModel_autocomplete_name"),
         member_id = $("#member_id");
    
 ', CClientScript::POS_END);
?>

<!--<div align="right">-->
    <?php //echo CHtml::textField("txtSearch"); 
        
        echo CHtml::hiddenField('member_id');
        $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'model'=>$model,
                'attribute'=>'autocomplete_name',
                'sourceUrl'=>  Yii::app()->createUrl('members/search'),
                'options'=>array(
                    'minLength'=>'2',
                    'showAnim'=>'fold',
                    'focus' => 'js:function(event, ui){ member_name.val(ui.item["value"]) }',
                    'select' => 'js:function(event, ui){ member_id.val(ui.item["id"]); }',
                ),
                'htmlOptions'=>array(
                    'class'=>'span4',
                    'rel'=>'tooltip',
                    'title'=>'Please type your member\'s name.',
                    'autocomplete'=>'off',
                ),        
            ));
    ?>
    
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit', 
        'type'=>'primary',
        'icon'=>'icon-search',
        'label'=>'Search', 
        'htmlOptions'=>array('id'=>'btnSearch', 'name'=>'btnSearch','style'=>'margin-top:-10px;')
    )); ?>
<!--</div>-->