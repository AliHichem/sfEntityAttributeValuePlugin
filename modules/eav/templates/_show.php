<div id="<?php echo $formId ?>" class="eav_dynamics" >
    <table class="nostyle">
        <tbody>
            <?php foreach ($eavDynamicShowForm as $key => $field): ?>
                <?php if ($key != 'separator'): ?>
                <tr>
                    <td><?php echo $field->renderLabel(); ?></td>
                    <td><?php echo $field->getValue(); ?></td>
                </tr>
                 <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>