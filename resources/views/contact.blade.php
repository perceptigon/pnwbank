@extends('layouts.app')

@section('content')
	<h1 class="text-center">Contact</h1>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form method="post">
                <div class="form-group">
                    <label for="leader">Your Leader Name</label>
                    <input type="text" name="leader" id="leader" class="form-control" required @if (Auth::check()) value="{{ Auth::user()->username }}" @endif>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" required></textarea>
                </div>
                {{ csrf_field() }}
                <input type="submit" class="btn btn-raised btn-primary" name="createReq">
            </form>
		</div>
	</div>
	@if (!Auth::guest() && Auth::user()->isAdmin && count($pendReqs) > 0)
		<hr>
    	<table class="table table-hover table-striped">
    		<tr>
    			<td>Timestamp</td>
    			<td>Leader</td>
    			<td>Message</td>
    			<td>Status</td>
    		</tr>
    		@foreach ($pendReqs as $req)
    			<tr>
    				<td>{{ $req->timestamp }}</td>
    				<td>{{ $req->leader }}</td>
    				<td>{{ $req->message }}</td>
    				<td>
    					<form method="post">
    						<input type="submit" class="btn btn-primary" value="Set Complete" name="setComplete">
    						<input type="hidden" value="{{ $req->id }}" name="cID">
    						{{ csrf_field() }}
    					</form>
    				</td>
    			</tr>
    		@endforeach
    	</table>
    @endif
@endsection