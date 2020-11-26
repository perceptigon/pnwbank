<div class="panel panel-default">
    <div class="panel-heading">Transfer <div class="pull-right"><button onclick="window.location.href = '/outsidetransferaa';">Account to Nation Transfer</button><button onclick="window.location.href = '/outsidetransfer';">Account to Alliance Transfer</button></div></div>
    <div class="panel-body">
        <form method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tran_from">From</label>
                        <select class="form-control" name="from" id="tran_from">
                            <optgroup label="Accounts">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} - ${{ number_format($account->money, 2) }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tran_to">To</label>
                        <select class="form-control" name="to" id="tran_to">
                            <optgroup label="Nation">
                                <option value="nation">Nation - {{ Auth::user()->nID }}</option>
                            </optgroup>

                            <optgroup label="Accounts">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} - ${{ number_format($account->money, 2) }}</option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Other Accounts">

<option value="927"> 187328 - Freyu - Vander Lord's account</option> 
<option value="933"> 49745 - whizzy - Banque Lumiere Reserve Account</option> 
<option value="935"> 49745 - whizzy - Sui Bank account</option> 
<option value="957"> 164617 - James Heron - -C- Account</option> 
<option value="974"> 175001 - Blackbird - The World's Debt</option> 
<option value="975"> 175001 - Blackbird - Wonderland Shareholder Account</option> 
<option value="983"> 91386 - Parrish - Orbis International Corporation </option> 
<option value="985"> 163371 - Konungariket Sverige - TKBO Storage</option> 
<option value="989"> 217537 - Benj - Dividends</option> 
<option value="990"> 217537 - Benj - Swing reserve 3</option> 
<option value="991"> 86606 - Haris the Guy - Dividends</option> 
<option value="992"> 193189 - Arkin - Dividends</option> 
<option value="995"> 19818 - BlackAsLight - Dividends</option> 
<option value="996"> 190999 - Damon - Savings account</option> 
<option value="999"> 190999 - Damon - Zeal Royal Casino</option> 
<option value="1003"> 226261 - Lysander the Great - Lyonia </option> 
<option value="1009"> 8360 - Greene - Taith Group</option> 
<option value="1011"> 91386 - Parrish - dividends</option> 
<option value="1013"> 190999 - Damon - Revenue</option> 
<option value="1017"> 172844 - Lord Vader - RSS Flipping</option> 
<option value="1020"> 237315 - Jorge Robledo - Savings</option> 
<option value="1022"> 195377 - Chimpy_Fire - Original Chimpy</option> 
<option value="1024"> 148936 - Dabigbluewhale - Dabigbluewhale</option> 
<option value="1025"> 229318 - TigerFire - Dividends</option> 
<option value="1026"> 8360 - Greene - Taith Group - Share Dividends</option> 
<option value="1027"> 233135 - Keoneatyou - Keoneatyou</option> 
<option value="1028"> 217537 - Benj - The Orbit Stock Exchange</option> 
<option value="1030"> 237768 - Anis - Anis</option> 
<option value="1031"> 146200 - Tamunominini - Xantrooph2</option> 
<option value="1032"> 190999 - Damon - Zeal Royal Casino (1)</option> 
<option value="1033"> 246639 - Roald - The Bank Of Roaldlands offshore account</option> 
<option value="1054"> 91386  - ONN - Parish</option> 
<option value="1055"> 227732 - ONN - The Shashwat</option> 
<option value="1056"> 217405 - ONN - Soprano</option> 
<option value="1057"> 226261 - ONN - Lyonia</option> 





                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
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
                            <tr>
                                <td>
                                    <input type="number" class="form-control" name="money" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="coal" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="oil" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="uranium" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="lead" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="iron" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="bauxite" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="gas" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="munitions" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="steel" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="aluminum" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="food" value="0" step="any" min="0">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" name="transfer" value="Transfer" class="btn btn-block btn-primary">
                </div>