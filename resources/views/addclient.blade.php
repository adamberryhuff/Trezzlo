@extends('layouts.app')

@section('content')

<!-- Padding on top goes away on mobile -->
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>
<?php
if (!empty($disabled)) {
    ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
            <div class="alert alert-success" role="alert">We have succesfully received your feedback. Thank you!</div>
        </div>
    </div>
    <?php
} else if ($error) {
        ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
            <div class="alert alert-danger" role="alert">An error occured. We have been notified!</div>
        </div>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>

<!-- main panel -->
<form method="GET" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
        <div class="panel panel-default feedback_panel">
            <div class="panel-heading">Add Client</div>
            <div class="panel-body">
                <!-- Company -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <h4 class="page-header" id="panels">Company Information</h4>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="name">Client Name</label>
                                <input type="text" class="form-control" id="name" name="client_name" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Primary Contact -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <h4 class="page-header" id="panels">Primary Contact</h4>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="email">Name</label>
                                <input type="text" class="form-control" id="email" name="contact_name" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="contact_email" <?php echo $disabled ?>>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="contact_phone" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- system -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <h4 class="page-header" id="panels">System Configuration</h4>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="email">Review Site URL</label>
                                <input type="text" class="form-control" id="email" name="review" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="email">Preferred Area Code</label>
                                <input type="text" class="form-control" id="email" name="area_code" <?php echo $disabled ?>>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="phone">Package Cost</label>
                                <input type="text" class="form-control" id="phone" name="cost" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-12">
                            <input type="hidden" name="secret" value="<?php echo isset($_GET['secret']) ? $_GET['secret'] : ''?>" />
                            <button type="submit" class="btn btn-primary pull-right feedback_submit_button" <?php echo $disabled ?>>Submit</button>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
</form>
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>
@endsection

<style>
.feedback_submit_button {
    padding: 10px 50px 10px 50px !important;
    margin: 0px 0px 10px 0px !important;
}
.feedback_text_area {
    color: #555555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    width: 100%;
    border-radius: 6px;
    height: 200px;
    font-size:18px;
}
.feedback_divider {
    width:90%;
}
.feedback_panel {
    margin-bottom:0px !important;
}
.feedback_header {
    width:90%; 
    margin:auto;
}
</style>