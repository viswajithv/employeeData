$(function(){
    $(document).on('change', '.employee-upload', function(e){
        e.preventDefault();
        var file_name = this.files[0].name;
        var file_data = file_name.split('.');
        var input = this.files[0];
        if(file_data[file_data.length-1].toLowerCase() != "csv"){
            alert("Please upload csv file");
        }else{
            var form_data = new FormData();
            form_data.append('emp_upload', $('.employee-upload')[0].files[0]);
            form_data.append("upload_data", 1);
            $.ajax({
                url: base_url+'index.php/employee_details/mapping_data',
                dataType: 'json', 
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                    if(response.status){
                        $('.uploaded-fields').html(response.data);
                        $('.uploaded-fields').show();
                        // console.log(response.data);
                    }else{
                        alert(response.message);
                    }
                }
            });
        }
    });

    $(document).on('change', '.selected-upload', function(e){
        var field_no = $(this).val();
        if(field_no != ""){
            var data_no = $(this).data("id");
            var selected_dropdown = $(this);
            var form_data = new FormData();
            form_data.append('emp_upload', $('.employee-upload')[0].files[0]);
            form_data.append('data_position', data_no);
            form_data.append('field_position', field_no);
            $.ajax({
                url: base_url+'index.php/employee_details/validate_data',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                    if(response.validation){
                        selected_dropdown.closest("tr").find(".validation-field").html(response.validation_msg);
                    }
                    
                    var validation_length = 0;
                    var validation_fields = 0;
                    $('.validation-field').each(function(){
                        if($(this).html() != ""){
                            validation_length++;
                        }
                    });
                    $('.selected-upload').each(function(){
                        if($(this).val() == ""){
                            validation_fields++;
                        }
                    });

                    if(validation_length == 0 && validation_fields == 0){
                        $('.upload-employee').hide();
                        $('.upload-btn').show();
                    }else{
                        $('.upload-employee').show();
                        $('.upload-btn').hide();
                    }
                    
                }
            });
        }
    });

    $(document).on('click', '#upload-btn', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var form_data = new FormData();
        form_data.append('emp_upload', $('.employee-upload')[0].files[0]);

        var input_data = $('#add_employee_form').serializeArray();
        $.each(input_data,function(key,input){
            form_data.append(input.name,input.value);
        });
        $.ajax({
            url: base_url+'index.php/employee_details/upload_data',
            dataType: 'json', 
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(response){
                if(response.status){
                    alert(response.message);
                    location.reload();
                }
            }
        });

    });
});