@extends('layouts.admin')

@section('content')

    <section class="content">
        @include("admin.alerts")

        <form method="post">
            <div class="row">
                <div class="col-sm-2">
                        <div class="form-group label-floating">
                            <label class="control-label" for="days">Days</label>
                            <input type="number" id="days" name="days" class="form-control" required>
                        </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" name="apply" class="btn btn-raised btn-primary" value="Run">
                    </div>
                    </div>
                </div>
        </form>
    </section>

@endsection