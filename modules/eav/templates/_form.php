<?php $json = isset($eav_dynamics_json) ? $eav_dynamics_json : '' ?>
<style>
    #save-form {    
        display: none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){

        $('#form-builder').formbuilder({
            'save_url': "<?php echo url_for('eav/saveJson?ressource_id='.$ressource_id.'&entity_id='.$entity_id) ?>",
            'load_url': "<?php echo url_for('eav/loadJson?ressource_id='.$ressource_id.'&entity_id='.$entity_id) ?>",
            'load_data': "<?php echo $json ?>" 
        });
        $(document).find('form:first').unbind('submit').submit(function(){
            if (checkEmptyInputs("#form-builder")) {
                $('#save-form').trigger('click');
                $(document).ajaxComplete(function(){
                    $(document).find('form:first').unbind('submit').submit();
                });
                return false;
            }
            else {
                alert('<?php echo "All labels must be specified" ?>');
                return false;
            }
        });
    });
    var checkEmptyInputs = function(selector){
        /**
         * clean empty fields including those that contains
         * only whitespaces
         */
        $(selector + ' input:text').each(function(){
            if($(this).val().match(/^(\s)+$/)) {
                $(this).val('');
            }
        });
        return $(selector + ' input:text[value=""]').size() == 0 ? true : false ;
    }
</script>
<div id="form-wrap">
    <ul id="form-builder" class="frmb">
        <li>
        </li>
    </ul>
</div>
