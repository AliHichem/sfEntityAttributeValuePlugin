<div id="<?php echo $formId ?>" class="eav_dynamics" >
    <table class="nostyle">
        <tfoot>
            <?php echo $eavDynamicForm->renderHiddenFields() ?>
        </tfoot>
        <?php foreach ($eavDynamicForm as $key => $field): ?>
        <?php if($key == 'separator'): ?>
            <?php echo $field->render() ; ?>
        <?php else: ?>
        <?php echo $field->renderRow() ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>