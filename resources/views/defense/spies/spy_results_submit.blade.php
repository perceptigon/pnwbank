@extends('layouts.app')

@section('content')

    <h2 class="text-center">Submit Assignment Results</h2>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form method="post">
                <fieldset>
                    <div class="form-group">
                        <label class="control-label" for="nID"><strong>YOUR</strong> Nation ID</label>
                        <input type="number" id="nID" value="{{ $_GET["nID"] }}" name="nID" class="form-control" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                        <span class="help-block"><strong>YOUR</strong> nation ID, the numbers at the end of the URL when you view your nation. EX: https://politicsandwar.com/nation/id=XXXXX</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="aID">Assignment ID</label>
                        <input type="number" id="aID" value="{{ $_GET["aID"] }}" name="aID" class="form-control" required>
                        <span class="help-block">The assignment ID included in your message.</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="results">Result Text</label>
                        <textarea class="form-control" id="results" name="results" required=""></textarea>
                        <span class="help-block">Paste the entire results of your spy operation here.</span>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" name="submit" class="btn btn-raised btn-primary" value="Submit Results">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@endsection