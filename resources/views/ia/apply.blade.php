@extends('layouts.app')

@section('content')

    <p style="text-align:center;padding-top:10px;"><img src="{{ url("/images/tibernet_logo.png") }}" alt="Tibernet Logo" style="width:400px;height:150px;"><img src="{{ url("/images/flag.png") }}" alt="BK Flag" style="width:300px;height:150px;"></p>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h1 class="text-center">Apply to Camelot</h1>
            <p>Please visit our Discord server at 'https://discord.gg/9eNvpHZ' this module isn't available yet this</p><br>
            <form method="post" action="apply">
                <fieldset>
                    <div class="form-group label-floating">
                        <label class="control-label" for="nation_ID">Nation ID</label>
                        <input type="number" id="nation_ID" name="nation_ID" class="form-control" required>
                        <p class="help-block">The nation ID is the numbers at the end of the URL when you view your nation. EX: https://politicsandwar.com/nation/id=<b>XXXXX</b>. You need to be sure it is YOUR nation id. Getting this wrong will mess up the entire application.</p> </div>
                    <div class="form-group label-floating">
                        <label class="control-label" for="prev_alliances">Prevous Alliances and Positions</label>
                        <textarea class="form-control" id="prev_alliances" name="prev_alliances" required></textarea>
                    </div>
                    <label for="pissed_off">Have you done anything to piss someone off in PW?</label>
                    <br>
                    <div class="radio-inline">
                        <label><input type="radio" name="pissed_off" value="yes" data-toggle="radio" class="custom-radio" required>Yes</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="pissed_off" value="no" data-toggle="radio" class="custom-radio" required>No</label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group label-floating">
                        <label class="control-label" for="skills">What skills can you offer BK?</label>
                        <textarea class="form-control" id="skills" name="skills" required></textarea>
                    </div>
                    <label for="MMR">We are a military alliance, which means you will be required to stockpile a warchest (stockpile of resources and money) which will cut into your growth, are you okay with this?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="MMR" value="yes" data-toggle="radio" class="custom-radio" required>Yes</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="MMR" value="no" data-toggle="radio" class="custom-radio" required>No</label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" name="apply" class="btn btn-raised btn-primary" value="Submit Application">
                    </div>
                </fieldset>

@endsection