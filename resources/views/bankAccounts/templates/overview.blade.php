<div class="panel panel-default">
    <div class="panel-heading">Accounts</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Name</th>
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
                @foreach ($accounts as $account)
                    <tr>
                        <td><a href="{{ url("/accounts/".$account->id) }}">{{ $account->name }}</a></td>
                        <td>${{ number_format($account->money, 2) }}</td>
                        <td>{{ number_format($account->coal, 2) }}</td>
                        <td>{{ number_format($account->oil, 2) }}</td>
                        <td>{{ number_format($account->uranium, 2) }}</td>
                        <td>{{ number_format($account->lead, 2) }}</td>
                        <td>{{ number_format($account->iron, 2) }}</td>
                        <td>{{ number_format($account->bauxite, 2) }}</td>
                        <td>{{ number_format($account->gas, 2) }}</td>
                        <td>{{ number_format($account->munitions, 2) }}</td>
                        <td>{{ number_format($account->steel, 2) }}</td>
                        <td>{{ number_format($account->aluminum, 2) }}</td>
                        <td>{{ number_format($account->food, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include("bankAccounts.templates.create")