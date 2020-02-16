@extends('layouts.app')

@section('content')
    <h1 class="text-center">Alliance Market</h1>
    @if ($system === 1)
        <h3>Offers</h3>
        <table class="table table-striped table-hover text-center marketTable">
            <thead>
                <tr>
                    <th>Resource</th>
                    <th>Amount</th>
                    <th>PPU</th>
                    <th>Input</th>
                    <th>Cost</th>
                </tr>
            </thead>

            @foreach ($resources as $res)
                @if ($res->amount > 1)
                    <tr>
                        <td class="text-capitalize"><img src="images/resources/{{ $res->resource }}.png"> {{ $res->resource }}</td>
                        <td>{{ $res->amount }}</td>
                        <td id="{{ $res->resource }}PPU">{{ $res->ppu }}</td>
                        <td>
                            <form method="post">
                                <div class="form-group tinyGroup">
                                    <input class="form-control tinyInput" type="number" name="nID" placeholder="Nation ID" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                                </div>
                                <div class="form-group tinyGroup">
                                    <input class="form-control tinyInput" type="number" name="amount" placeholder="Amount" min="1" max="{{ $res->amount }}" oninput="calcCost('{{ $res->resource }}')" required id="{{ $res->resource }}Amount">
                                </div>
                                <div class="form-group tinyGroup">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $res->resource }}" name="resource">
                                    <input type="submit" class="btn btn-primary btn-sm smallSubmit" value="Sell" name="sellResources">
                                </div>
                            </form>
                        </td>
                        <td id="{{ $res->resource }}Cost">$0</td>
                    </tr>
                @endif
            @endforeach
        </table>

        <div class="bs-callout bs-callout-primary">
            <h2>Information</h2>
            <ul>
                <li>You can use this system to sell your excess resources to the bank.</li>
                <li>
                    If you want to sell resources to the bank, follow the following instructions:
                    <ul>
                        <li>Fill out your nation ID and the amount you want to sell in the form.</li>
                        <li>You'll be sent a message in-game with a 'code' and more detailed instructions.</li>
                        <li>Deposit the correct amount of resources into the bank with the 'code' as the transaction note.</li>
                        <li>Within an hour you will receive a payment and a message confirming the payment.</li>
                    </ul>
                </li>
                <li>If you do not deposit the resources within an hour, the offer will expire and you will have to request the offer again.</li>
            </ul>
        </div>

        <script>
            function addCommas(intNum) {
                return (intNum + '').replace(/(\d)(?=(\d{3})+$)/g, '$1,');
            }
            function calcCost(resource) {
                var amount = parseInt(document.getElementById(resource + "Amount").value);
                var ppu = parseInt(document.getElementById(resource + "PPU").innerText);
                var cost = parseInt(amount * ppu);
                if (isNaN(cost)) {
                    document.getElementById(resource + "Cost").innerHTML = "$0";
                } else {
                    document.getElementById(resource + "Cost").innerHTML = "$" + addCommas(cost);
                }
            }
        </script>
    @else
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 style="font-size: 20px;">Sorry</h4>
            <p>The market system is currently turned off.</p>
        </div>
    @endif

@endsection