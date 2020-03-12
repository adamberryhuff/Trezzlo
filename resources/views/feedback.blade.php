@extends('layouts.app')

@section('content')


<!-- Padding on top goes away on mobile -->
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>
@if($disabled != '')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
            <div class="alert alert-success" role="alert">We have succesfully received your feedback. Thank you!</div>
        </div>
    </div>
@endif
@if(count($errors) > 0)
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
            <div class="alert alert-danger" role="alert">
                The following errors occured:<br>
                <ul>
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-12 hidden-xs">&nbsp</div>
</div>

<!-- main panel -->
<form method="GET" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-8 col-md-offset-2 col-sm-offset-2">
        <div class="panel panel-default feedback_panel">
            <div class="panel-body">
                <!-- main panel header -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="feedback_header">
                            <h2>{{ $client }}</h2>
                            <p>
                                We value your opinion. Please fill out the form below to tell us about your experience.
                            </p>
                        </div>
                    </div>
                </div>
                <hr class="feedback_divider">
                <br>

                <!-- name -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $first_name }}" <?php echo $disabled ?>>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $last_name }}" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- email and phone -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="email">Email Address</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ $email }}" <?php echo $disabled ?>>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $phone }}" <?php echo $disabled ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- feedback -->
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-12">
                            <div class="form-group form-group-lg">
                                <label for="comments">Comments</label><br>
                                <textarea class="feedback_text_area" id="comments" name="comments" <?php echo $disabled ?>>{{ $feedback }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-sm-offset-1">
                        <div class="col-md-12">
                            <button type="submit" name="submit" class="btn btn-primary pull-right feedback_submit_button" <?php echo $disabled ?>>Submit</button>
                        </div>
                    </div>
                </div>
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