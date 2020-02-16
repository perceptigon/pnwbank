<div class="panel panel-default">
    <div class="panel-heading">Transactions</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Money</th>
                    <th>Coal</th>
                    <th>Oil</th>
                    <th>Uranium</th>
                    <th>Lead</th>
                    <th>Iron</th>
                    <th>Bauxite</th>
                    <th>Gas</th>
                    <th>Munitions</th>
                    <th>Steel</th>
                    <th>Aluminum</th>
                    <th>Food</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $tran)
                    <tr>
                        <td>{{ $tran->created_at }}</td>
                        @if ($tran->fromAccount && $tran->fromAccountRel != null)
                            <td><a href="{{ url("/accounts/".$tran->fromAccountID) }}">{{ $tran->fromAccountRel->name}}</a></td>
                        @else
                            <td>{{ $tran->fromName ?? "Deleted Account" }}</td>
                        @endif
                        @if ($tran->toAccount)
                            <td><a href="{{ url("/accounts/".$tran->toAccountID) }}">{{ $tran->toAccountRel->name }}</a></td>
                        @elseif (! $tran->toAccount)
                            <td>{{ $tran->toName }}</td>
                        @elseif ($tran->toAccountRel == null)
                            <td>Deleted Account</td>
                        @else
                            <td>"o fuk"</td>
                        @endif
                        <td>${{ number_format($tran->money, 2) }}</td>
                        <td>{{ number_format($tran->coal, 2) }}</td>
                        <td>{{ number_format($tran->oil, 2) }}</td>
                        <td>{{ number_format($tran->uranium, 2) }}</td>
                        <td>{{ number_format($tran->lead, 2) }}</td>
                        <td>{{ number_format($tran->iron, 2) }}</td>
                        <td>{{ number_format($tran->bauxite, 2) }}</td>
                        <td>{{ number_format($tran->gas, 2) }}</td>
                        <td>{{ number_format($tran->munitions, 2) }}</td>
                        <td>{{ number_format($tran->steel, 2) }}</td>
                        <td>{{ number_format($tran->aluminum, 2) }}</td>
                        <td>{{ number_format($tran->food, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    </div>
</div>

@if ($account->logs->count() > 0)
<div class="panel panel-default">
    <div class="panel-heading">Manual Transactions</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Editor</th>
                        <th>Money</th>
                        <th>Coal</th>
                        <th>Oil</th>
                        <th>Uranium</th>
                        <th>Lead</th>
                        <th>Iron</th>
                        <th>Bauxite</th>
                        <th>Gas</th>
                        <th>Munitions</th>
                        <th>Steel</th>
                        <th>Aluminum</th>
                        <th>Food</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($account->logs as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->editor }}</td>
                        <td>${{ number_format($log->money, 2) }}</td>
                        <td>{{ number_format($log->coal, 2) }}</td>
                        <td>{{ number_format($log->oil, 2) }}</td>
                        <td>{{ number_format($log->uranium, 2) }}</td>
                        <td>{{ number_format($log->lead, 2) }}</td>
                        <td>{{ number_format($log->iron, 2) }}</td>
                        <td>{{ number_format($log->bauxite, 2) }}</td>
                        <td>{{ number_format($log->gas, 2) }}</td>
                        <td>{{ number_format($log->munitions, 2) }}</td>
                        <td>{{ number_format($log->steel, 2) }}</td>
                        <td>{{ number_format($log->aluminum, 2) }}</td>
                        <td>{{ number_format($log->food, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif