<div class="col-md-12">
    <?= form_open_multipart('', ['id' => 'add_employee_form','class'=>'add_employee_form']); ?>
        <table class="table">
            <tr>
                <th>Required Field</th>
                <th>Uploaded Field</th>
                <th>Validation</th>
            </tr>
            <?php
                foreach($data_structure as $required_key => $required_data){
            ?>
            <tr>
                <td><?= $required_data ?></td>
                <td>
                    <select class="custom-select selected-upload" name="uploaded_data[]" data-id="<?= $required_key ?>">
                        <option value="">Select field</option>
                        <?php
                            if(!empty($uploaded_data)){
                                foreach($uploaded_data as $upload_key => $upload_data){
                        ?>
                        <option value="<?= $upload_key ?>"><?= $upload_data ?></option>
                        <?php
                                }
                            }
                        ?>
                        
                    </select>
                </td>
                <td class="validation-field"></td>
            </tr>
            <?php
                }
            ?>
        </table> 
    <?= form_close(); ?>   
</div>