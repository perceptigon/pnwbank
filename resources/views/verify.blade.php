@extends('layouts.app')

@section('content')
    <h1 class="text-center">Verify <span class="text-capitalize">{{ Auth::user()->username }}</span></h1>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Verify</div>
                <div class="panel-body">
                    <form method="post">
                        <div class="form-group">
                            <input type="text" name="verifyToken" placeholder="Verify Token" class="form-control" value="{{ $token ?? "" }}" required>
                            <span class="help-block">This should be filled out for you. If it isn't, check the message that was sent to you for the code</span>
                        </div>
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="{{ env("G-CAPTCHA-KEY") }}"></div>
                        </div>
                        <div class="form-group">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary btn-block" value="Verify">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("headScripts")
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection