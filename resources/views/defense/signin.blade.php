@extends('layouts.app')

@section('content')
    <h1 class="text-center">Sign In</h1>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form method="post" class="form-horizontal">
                <fieldset>
                    <legend>Nation Info</legend>
                    <div class="form-group">
                        <label for="nID">Nation ID</label>
                        <input type="number" name="nID" id="nID" placeholder="Nation ID" class="form-control" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                        <span class="help-block">Your Nation ID are the numbers at the end of the URL when you're viewing your nation. https://politicsandwar.com/nation/id=<strong>XXXXX</strong></span>
                    </div>
                    <legend>Resources</legend>
                    <div class="form-group">
                        <label for="money">Money</label>
                        <input type="number" id="money" name="money" step="any" class="form-control" placeholder="Money" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="food">Food</label>
                        <input type="number" name="food" id="food" step="any" class="form-control" placeholder="Food" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="uranium">Uranium</label>
                        <input type="number" name="uranium" id="uranium" step="any" class="form-control" placeholder="Uranium" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="steel">Steel</label>
                        <input type="number" name="steel" id="steel" step="any" class="form-control" placeholder="Steel" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="gas">Gas</label>
                        <input type="number" name="gas" id="gas" step="any" class="form-control" placeholder="Gas" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="munitions">Munitions</label>
                        <input type="number" name="munitions" id="munitions" step="any" class="form-control" placeholder="Munitions" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="aluminum">Aluminum</label>
                        <input type="number" name="aluminum" id="aluminum" step="any" class="form-control" placeholder="Aluminum" min="0" required>
                    </div>
                    <legend>Military</legend>
                    <div class="form-group">
                        <label for="spies">Spies</label>
                        <input type="number" name="spies" id="spies" step="any" class="form-control" placeholder="Spies" min="0" required>
                    </div>
                    <legend>Activity</legend>
                    <div class="form-group">
                        <label class="control-label">Do you get on Discord?</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="discord" value="yes" required> Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="discord" value="no" required checked> No
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update" class="control-label">What days can you usually get on at update?</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" value="never" checked> Never
                            </label>
                        </div>
                        <hr>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="monday"> Monday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="tuesday"> Tuesday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="wednesday"> Wednesday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="thursday"> Thursday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="friday"> Friday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="saturday"> Saturday
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="update[]" id="up_never" class="up_day" value="sunday"> Sunday
                            </label>
                        </div>
                        <span class="help-block">Update is at 12 AM in-game time (GMT)</span>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="Sign In">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        // Super awesome update day shit
        $("#up_never").change(function() {
            if(this.checked) {
                $(".up_day").prop('checked', false);
            }
        });

        $(".up_day").change(function() {
            if(this.checked) {
                $("#up_never").prop('checked', false);
            }
        });
    </script>
@endsection