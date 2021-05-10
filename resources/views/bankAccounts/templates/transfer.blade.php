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

                            <optgroup label="Rothschild Family Accounts">
                            <option value="1100"> Blackbird - BLACK</option> 
                            <option value="1093"> Factorian Kallio - FLPvR</option>
                            <option value="1091"> Loki - Loki investment fund</option>  
                            <option value="903"> Naruu - Frosties magical money printer</option> 
                            <option value="922"> Whizzy - Savings account 1</option> 

                            <option value="1113"> FLPvR</option> 

                            <optgroup label="ONN Accounts">
<option value="1057"> 226261 - ONN - Lyonia</option> 
<option value="1054"> 91386  - ONN - Parish</option> 
<option value="1056"> 217405 - ONN - Soprano</option> 
<option value="1055"> 227732 - ONN - The Shashwat</option> 

                            <optgroup label="All Accounts <10/05/21>">
                            <option value="995"> nID: 19818 - Dividends</option>
<option value="903"> nID: 29685 - Frosties magical money printer</option>
<option value="977"> nID: 29685 - Frosties magical money printer</option>
<option value="1122"> nID: 49745 - Min axct</option>
<option value="1043"> nID: 49745 - Banque lumiere Charges account</option>
<option value="1133"> nID: 49745 - fractional reserve lending interest </option>
<option value="1149"> nID: 49745 - AJAL funds </option>
<option value="1131"> nID: 49745 - Fractional reserve account </option>
<option value="1045"> nID: 49745 - Banque lumiere reserves</option>
<option value="1051"> nID: 49745 - My savings account</option>
<option value="1044"> nID: 49745 - Banque lumiere current account</option>
<option value="935"> nID: 49745 - Sui Bank account</option>
<option value="991"> nID: 86606 - Dividends</option>
<option value="1010"> nID: 88504 - CP</option>
<option value="1006"> nID: 89174 - It's Hans</option>
<option value="1115"> nID: 91386 - Orbis International Corp Funds</option>
<option value="1114"> nID: 91386 - OWN Official Funds</option>
<option value="1158"> nID: 109723 - Brokerage account </option>
<option value="1136"> nID: 123750 - The Vault</option>
<option value="966"> nID: 133181 - Thamous' personal bank</option>
<option value="967"> nID: 133181 - Sternway Sachs</option>
<option value="969"> nID: 133181 - Steel fund</option>
<option value="963"> nID: 137142 - Orbis Bank of Resources</option>
<option value="934"> nID: 142715 - Savings Account #142715</option>
<option value="1139"> nID: 142951 - Asset Management von Rothschild</option>
<option value="976"> nID: 142951 - BusinessPlonker</option>
<option value="1072"> nID: 142951 - TEB</option>
<option value="1034"> nID: 146200 - Rivers Trading Corporation</option>
<option value="1031"> nID: 146200 - Xantrooph2</option>
<option value="1024"> nID: 148936 - Dabigbluewhale</option>
<option value="1167"> nID: 150928 - Daxx's Commission Account</option>
<option value="1145"> nID: 150928 - GCIS Holdings</option>
<option value="1157"> nID: 150928 - Long Term Trading Fund</option>
<option value="1153"> nID: 150928 - E2 Dividend Fund</option>
<option value="1152"> nID: 150928 - E1 Dividend Fund</option>
<option value="1151"> nID: 150928 - P1 Dividend Fund</option>
<option value="1105"> nID: 152521 - Timey-wimey666</option>
<option value="1147"> nID: 153339 - Glorious</option>
<option value="945"> nID: 159651 - Edwardmbe Savings Account</option>
<option value="1058"> nID: 162076 - Aggeremid</option>
<option value="1091"> nID: 162656 - Loki investment fund</option>
<option value="985"> nID: 163371 - TKBO Storage</option>
<option value="1137"> nID: 163441 - Jarrett</option>
<option value="902"> nID: 164003 - The Gold Reserve</option>
<option value="906"> nID: 164003 - Retirement fund</option>
<option value="957"> nID: 164617 - -C- Account</option>
<option value="978"> nID: 164617 - Personal Alliance Holdings</option>
<option value="1019"> nID: 172844 - ACP Savings</option>
<option value="1017"> nID: 172844 - RSS Flipping</option>
<option value="1018"> nID: 172844 - City Savings</option>
<option value="1074"> nID: 173763 - Amarr Empire</option>
<option value="1062"> nID: 175001 - IB Stock Exchange</option>
<option value="1100"> nID: 175001 - BLACK</option>
<option value="907"> nID: 176831 - Cash</option>
<option value="908"> nID: 176831 - Retirement</option>
<option value="919"> nID: 178717 - Super Secret Loli Retirement Fund</option>
<option value="950"> nID: 183150 - Savings</option>
<option value="951"> nID: 183150 - General Spending</option>
<option value="979"> nID: 183150 - TKE</option>
<option value="940"> nID: 183380 - Ketya Savings</option>
<option value="1075"> nID: 186236 - Mr Rodgers</option>
<option value="930"> nID: 186828 - Aluminum Holdings</option>
<option value="1035"> nID: 187328 - alliance account</option>
<option value="1065"> nID: 187328 - egg bank capital</option>
<option value="956"> nID: 188000 - AccountNumber1</option>
<option value="987"> nID: 189573 - test</option>
<option value="988"> nID: 189573 - ">hi<"</option>
<option value="1142"> nID: 189701 - Iwboy s offshore</option>
<option value="1041"> nID: 189815 - New City Fund</option>
<option value="1042"> nID: 189815 - Project Fund</option>
<option value="1013"> nID: 190999 - Revenue</option>
<option value="1032"> nID: 190999 - Zeal Royal Casino (1)</option>
<option value="999"> nID: 190999 - Zeal Royal Casino</option>
<option value="996"> nID: 190999 - Savings account</option>
<option value="1047"> nID: 192284 - Emergency Account</option>
<option value="1048"> nID: 192284 - Emergency Account</option>
<option value="1092"> nID: 192447 - Big Mac's Account</option>
<option value="1135"> nID: 193160 - Almighty Corporation - Transfers</option>
<option value="1118"> nID: 193160 - ALMC</option>
<option value="1117"> nID: 193160 - Tom_SW</option>
<option value="992"> nID: 193189 - Dividends</option>
<option value="1087"> nID: 193411 - à¼ºAhsoka Tanoà¼»</option>
<option value="1070"> nID: 195090 - Personal</option>
<option value="1086"> nID: 195090 - Rockefeller AA</option>
<option value="1071"> nID: 195090 - TopHat - Funds</option>
<option value="1124"> nID: 195090 - Resources Lending Account</option>
<option value="1022"> nID: 195377 - Original Chimpy</option>
<option value="955"> nID: 195595 - Savings</option>
<option value="1084"> nID: 199695 - Leo's Savings</option>
<option value="1161"> nID: 203750 - Holdings</option>
<option value="1088"> nID: 203750 - Vincipela</option>
<option value="1155"> nID: 204586 - Savings</option>
<option value="980"> nID: 205780 - G_Pacifica</option>
<option value="1015"> nID: 206622 - United Holdings</option>
<option value="1016"> nID: 206622 - Debt Payments</option>
<option value="1141"> nID: 206759 - HistoriaX</option>
<option value="952"> nID: 213276 - Yo boi Silver</option>
<option value="982"> nID: 216593 - Goldsmith</option>
<option value="968"> nID: 216775 - Loan Account</option>
<option value="1110"> nID: 217405 - Tugger Personal</option>
<option value="1109"> nID: 217405 - Midas Group - Tugger</option>
<option value="1028"> nID: 217537 - The Orbit Stock Exchange</option>
<option value="990"> nID: 217537 - Swing reserve 3</option>
<option value="1001"> nID: 217563 - TheRealMichaelJordan's Pennies</option>
<option value="1005"> nID: 217563 - Casino</option>
<option value="1050"> nID: 225344 - Marvel Blazee</option>
<option value="1104"> nID: 226261 - RG Offshoring</option>
<option value="1162"> nID: 226261 - GCIS Offshoring</option>
<option value="1003"> nID: 226261 - Lyonia</option>
<option value="1057"> nID: 226261 - ONN - Lyonia</option>
<option value="1055"> nID: 227732 - ONN-The Shashwat</option>
<option value="1053"> nID: 227732 - brightland</option>
<option value="998"> nID: 227960 - Exelenxius</option>
<option value="1025"> nID: 229318 - Dividends</option>
<option value="1079"> nID: 233135 - Alps Finances and Investments.Co</option>
<option value="1107"> nID: 233135 - Midas Group Investment Command</option>
<option value="1080"> nID: 233135 - Keoneatyou-Rockefeller Group Account</option>
<option value="1077"> nID: 233135 - ONN-Keoneatyou</option>
<option value="1020"> nID: 237315 - Savings</option>
<option value="1030"> nID: 237768 - Anis</option>
<option value="1029"> nID: 237768 - Anis</option>
<option value="1119"> nID: 242225 - Business</option>
<option value="1064"> nID: 242982 - Personal</option>
<option value="1039"> nID: 243328 - Savings</option>
<option value="1168"> nID: 246232 - Ad Astra Operating</option>
<option value="1166"> nID: 246232 - TOSE External </option>
<option value="1165"> nID: 246232 - Swing Holdings</option>
<option value="1140"> nID: 246571 - MsPaintArtist</option>
<option value="1033"> nID: 246639 - The Bank Of Roaldlands offshore account</option>
<option value="1089"> nID: 247125 -   Business Bank</option>
<option value="1083"> nID: 247125 - Personal </option>
<option value="1132"> nID: 247716 - Bongonon</option>
<option value="1120"> nID: 251720 - Orbis Flags</option>
<option value="1076"> nID: 259619 - Personal</option>
<option value="1094"> nID: 261479 - Divyansh</option>
<option value="1123"> nID: 262908 - chof64-primary</option>
<option value="1156"> nID: 262908 - friend-safekeep</option>
<option value="1163"> nID: 270309 - Savings</option>
<option value="1134"> nID: 271766 - Hoylt Account</option>
<option value="1127"> nID: 281161 - Veritam National Funds</option>
<option value="1129"> nID: 281419 - Hello</option>
<option value="1150"> nID: 283300 - Spook Buisness</option>
<option value="1143"> nID: 283300 - Spook Main</option>
<option value="1130"> nID: 284901 - Olympus</option>









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