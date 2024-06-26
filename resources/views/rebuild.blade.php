@extends('layouts.app')

@section('content')

<h1 class="text-center">Request Rebuild</h1>
@if ($settings["rebuildSystem"] == 1)
	<div class="row">
	    <div class="col-md-6 col-md-offset-3">
	        <form method="post">
	            <fieldset>
	                <div class="form-group">
	                    <label class="control-label" for="nID">Nation ID</label>
	                    <input type="number" id="nID" name="nID" class="form-control" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
	                    <span class="help-block">The nation ID is the numbers at the end of the URL when you view your nation. EX: https://politicsandwar.com/nation/id=XXXXX</span>
	                </div>
	                <div class="form-group">
	                    <label class="control-label" for="amount">Amount</label>
	                    <input type="number" id="amount" name="amount" class="form-control" min="1" max="{{ $settings["maxLoan"] }}" required="">
	                </div>
	                <div class="form-group">
	                    <label class="control-label" for="reason">Reason</label>
	                    <textarea class="form-control" id="reason" name="reason" required=""></textarea>
	                </div>
	                <div class="form-group">
						{{ csrf_field() }}
	                    <input type="submit" name="reqRebuild" class="btn btn-raised btn-primary" value="Request Loan">
	                </div>
	            </fieldset>
	        </form>
	    </div>
	</div>
@else
	<div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4 style="font-size: 20px;">Sorry</h4>
        <p>The loan system is currently turned off.</p>
    </div>
@endif

	<div class="bs-callout bs-callout-primary">
	    <h2>Information</h2>
	    <ul>
			<li>Infra Rebuild Level: {{ $settings["rebuildInfra"] }} infra/city</li>
		    <li>Max Request: ${{ number_format($settings["maxRebuild"]) }}</li>
		    <li>After your request is initially approved, it will have to be manually approved by an economics staff member. This process may take up to 24 hours</li>
	    </ul>
	</div>

	<div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h3 class="pageTitle">Loan Lookup</h3>
					<div class="form-group">
						<label class="control-label" for="code">Code</label>
						<input type="number" id="code" name="code" class="form-control">
					</div>
					<div class="form-group">
						<input type="button" class="btn btn-raised btn-primary" value="Find" onclick="lookupLoan()">
					</div>
					<script>
						function lookupLoan()
						{
							// Get loan code
							var code = document.getElementById("code").value;
							// Redirect to page
							document.location.href="{{ url("/lookup") }}/" + code;
						}
					</script>
            </div>
        </div>

@endsection