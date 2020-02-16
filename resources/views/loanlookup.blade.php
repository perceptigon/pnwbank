@extends('layouts.app')

@section('content')
    <h1 class="text-center">{{ $loan->leader }}</h1>
    <p>Timestamp - {{ $loan->timestamp }}</p>
    <p>Original Amount - ${{ number_format($loan->originalAmount) }}</p>
    <p>Amount Left - ${{ number_format($loan->amount) }}</p>
    <p>Due - {{ date_format(new \DateTime($loan->due), "l, F j, Y") }}</p>
    @if ($loan->Approved)
        <p>Status - Pending</p>
    @elseif ($loan->isDenied)
        <p>Status - Denied</p>
    @elseif ($loan->isActive)
        <p>Status - Active</p>
    @elseif ($loan->isPaid)
        <p>Status - Paid</p>
    @else
        <p>Status - Unknown</p>
    @endif

    @if (!Auth::guest() && Auth::user()->isAdmin)
        <h3>Edit Loan</h3>
        <div class="row">
            <div class="col-lg-4">
                <form method="post">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{ $loan->amount }}" required>
                    </div>
                    <div class="form-group">
                        <label for="due">Due</label>
                        <input type="date" name="due" id="due" class="form-control" value="{{ $loan->due }}" required>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="Edit" name="editLoan">
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <form method="post">
            {{ csrf_field() }}
            <input type="submit" class="btn btn-primary" value="Pay Off Loan" name="markLoanComplete">
            <p class="help-block">Use this button to mark a loan as paid off.</p>
        </form>
        <hr>
        <table class="table table-striped table-hover text-center text-capitalize">
            <tr>
                <th class="text-center">Timestamp</th>
                <th class="text-center">User</th>
                <th class="text-center">Message</th>
            </tr>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->timestamp }}</td>
                    <td>{{ $log->username }}</td>
                    <td>{{ $log->message }}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection