<html>
    <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    </head>
    <body>
        <section>
            <div class="row">
                <div class="col-md-12 p-2 pr-5">
                    <button class="btn btn-success float-right" id="add" data-toggle="modal" data-target="#exampleModalCenter">Add Employees</button>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee Code</th>
                                    <th>Department</th>
                                    <th>Age</th>
                                    <th>Experience</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($employee_data)){
                                        foreach($employee_data as $employee){
                                ?>
                                <tr>
                                    <td><?= ucwords($employee['emp_name']) ?></td>
                                    <td><?= $employee['emp_code'] ?></td>
                                    <td><?= $employee['emp_depart'] ?></td>
                                    <td><?= date_diff(date_create($employee['emp_dob']), date_create('today'))->y."Yr ".date_diff(date_create($employee['emp_dob']), date_create('today'))->m . "Mths " ?></td>
                                    <td><?= date_diff(date_create($employee['emp_join']), date_create('today'))->y."Yr ".date_diff(date_create($employee['emp_join']), date_create('today'))->m . "Mths " ?></td>
                                </tr>
                                <?php
                                        }
                                    }else{
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align:center;">No data found</td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add Employees</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12 uploaded-fields" style="display:none">

                            </div>
                            <div class="col-md-12 upload-employee">
                                <p>Upload a csv file for adding employees. At a time you can only upload maximum of 20 employees.</p>
                                <input type="file" name="upload_employee" class="employee-upload">
                            </div>
                        </div>
                        <div class="modal-footer upload-btn" style="display:none">
                            <button class="btn btn-success float-right" id="upload-btn">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script src="<?= base_url() ?>assets/js/index.js"></script>
        <script>
            var base_url = "<?= base_url() ?>";
        </script>
    </body>
</html>