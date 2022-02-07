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
                            <option value="1226"> Blackbird - Terra Mariana</option> 
                            <option value="1139"> Factorian Kallio - Asset Management von Rothschild</option>
                            <option value="1186"> Loki - Loki: god of markets</option>  
                            <option value="1181"> Naruu - Frosties magical money printer</option>
                            <option value="1181"> Patton - CP</option>
                            <option value="1178"> Patton - THCO (SW) Holdings </option>
                            <option value="1051"> Whizzy - My savings account</option> 

                            <optgroup label="ONN Accounts">
<option value="1057"> 226261 - ONN - Lyonia</option> 
<option value="1115"> 91386  - OICF - Parish</option> 
<option value="1055"> 227732 - ONN - The Shashwat</option> 

<optgroup label="The Moonlight Accounts">
<option value="1177"> nID: 49745 - The Moonlight</option>

                            <optgroup label="All Accounts <01/Oct/21>">
                            <option value="1181"> nID: 29685 - Frosties magical money printer</option>
<option value="1043"> nID: 49745 - Banque lumiere Charges account</option>
<option value="1044"> nID: 49745 - Banque lumiere current account</option>
<option value="1045"> nID: 49745 - Banque lumiere reserves</option>
<option value="1051"> nID: 49745 - My savings account</option>
<option value="1122"> nID: 49745 - Min axct</option>
<option value="1131"> nID: 49745 - Fractional reserve account </option>
<option value="1133"> nID: 49745 - fractional reserve lending interest </option>
<option value="1149"> nID: 49745 - AJAL funds </option>
<option value="1182"> nID: 49745 - Brokerage funds </option>
<option value="1177"> nID: 49745 - The Moonlight</option>
<option value="1010"> nID: 88504 - CP</option>
<option value="1178"> nID: 88504 - THCO (SW) Holdings </option>
<option value="1201"> nID: 90038 - asui</option>
<option value="1114"> nID: 91386 - OWN Official Funds</option>
<option value="1115"> nID: 91386 - Orbis International Corp Funds</option>
<option value="1158"> nID: 109723 - Brokerage account </option>
<option value="1184"> nID: 113037 - Ryan2233</option>
<option value="1136"> nID: 123750 - The Vault</option>
<option value="976"> nID: 142951 - BusinessPlonker</option>
<option value="1072"> nID: 142951 - TEB</option>
<option value="1139"> nID: 142951 - Asset Management von Rothschild</option>
<option value="1031"> nID: 146200 - Xantrooph2</option>
<option value="1034"> nID: 146200 - Rivers Trading Corporation</option>
<option value="1151"> nID: 150928 - P1 Dividend Fund</option>
<option value="1152"> nID: 150928 - E1 Dividend Fund</option>
<option value="1153"> nID: 150928 - E2 Dividend Fund</option>
<option value="1157"> nID: 150928 - Long Term Trading Fund</option>
<option value="1174"> nID: 150928 - GCIS Main Account</option>
<option value="1187"> nID: 150928 - Lysander Commission Account</option>
<option value="1194"> nID: 150928 - Daxx's Personal</option>
<option value="1214"> nID: 150928 - Loan Funding Pool</option>
<option value="1215"> nID: 150928 - Loan Payments Pool</option>
<option value="1147"> nID: 153339 - Glorious</option>
<option value="1186"> nID: 162656 - Loki: god of markets</option>
<option value="1137"> nID: 163441 - Jarrett</option>
<option value="1227"> nID: 164932 - 2048</option>
<option value="1226"> nID: 175001 - Terra Mariana</option>
<option value="1035"> nID: 187328 - alliance account</option>
<option value="1065"> nID: 187328 - egg bank capital</option>
<option value="1142"> nID: 189701 - Iwboy s offshore</option>
<option value="1013"> nID: 190999 - Revenue</option>
<option value="1092"> nID: 192447 - Big Mac's Account</option>
<option value="1117"> nID: 193160 - Tom_SW</option>
<option value="1118"> nID: 193160 - ALMC</option>
<option value="1135"> nID: 193160 - Almighty Corporation - Transfers</option>
<option value="992"> nID: 193189 - Dividends</option>
<option value="1087"> nID: 193411 - à¼ºAhsoka Tanoà¼»</option>
<option value="1070"> nID: 195090 - Personal</option>
<option value="1071"> nID: 195090 - TopHat - Funds</option>
<option value="1086"> nID: 195090 - Rockefeller AA</option>
<option value="1124"> nID: 195090 - Resources Lending Account</option>
<option value="1022"> nID: 195377 - Original Chimpy</option>
<option value="1180"> nID: 203750 - Holdings</option>
<option value="1196"> nID: 203750 - Solaris Start-up Funding</option>
<option value="1155"> nID: 204586 - Savings</option>
<option value="1141"> nID: 206759 - HistoriaX</option>
<option value="1176"> nID: 210327 - ODM Banking</option>
<option value="1209"> nID: 216184 - Daviesolu</option>
<option value="1028"> nID: 217537 - The Orbit Stock Exchange</option>
<option value="1213"> nID: 224472 - Dawn Rising AA</option>
<option value="1220"> nID: 224472 - Dawn of Orbis Times</option>
<option value="1003"> nID: 226261 - Lyonia</option>
<option value="1057"> nID: 226261 - ONN - Lyonia</option>
<option value="1104"> nID: 226261 - RG Offshoring</option>
<option value="1162"> nID: 226261 - GCIS Offshoring</option>
<option value="1188"> nID: 226261 - SOSE</option>
<option value="1222"> nID: 226261 - Lenny Liquidation</option>
<option value="1221"> nID: 226279 - Investments</option>
<option value="1053"> nID: 227732 - brightland</option>
<option value="1055"> nID: 227732 - ONN-The Shashwat</option>
<option value="1025"> nID: 229318 - Dividends</option>
<option value="1079"> nID: 233135 - Alps Finances and Investments.Co</option>
<option value="1119"> nID: 242225 - Business</option>
<option value="1165"> nID: 246232 - Swing Holdings</option>
<option value="1166"> nID: 246232 - TOSE External </option>
<option value="1168"> nID: 246232 - Ad Astra Operating</option>
<option value="1140"> nID: 246571 - MsPaintArtist</option>
<option value="1170"> nID: 246571 - MERC</option>
<option value="1171"> nID: 246571 - Kiet</option>
<option value="1197"> nID: 246571 - MSPaint</option>
<option value="1207"> nID: 247054 - BigG</option>
<option value="1208"> nID: 247054 - BigG</option>
<option value="1132"> nID: 247716 - Bongonon</option>
<option value="1120"> nID: 251720 - Orbis Flags</option>
<option value="1223"> nID: 253032 - i am poor</option>
<option value="1123"> nID: 262908 - chof64-primary</option>
<option value="1156"> nID: 262908 - friend-safekeep</option>
<option value="1191"> nID: 263152 - Alon's reparations</option>
<option value="1205"> nID: 263152 - GCIS</option>
<option value="1206"> nID: 263152 - Modi loves Bruno</option>
<option value="1210"> nID: 263152 - GCIS Profits</option>
<option value="1211"> nID: 263152 - Savings</option>
<option value="1212"> nID: 263152 - Savings</option>
<option value="1219"> nID: 269085 - Emirus</option>
<option value="1193"> nID: 270309 - Savings</option>
<option value="1195"> nID: 270309 - Dawn Rising Alliance Bank</option>
<option value="1202"> nID: 270309 - Loan Repayment Emergency Fund</option>
<option value="1203"> nID: 270309 - Ari Safekeeping</option>
<option value="1204"> nID: 270309 - Emergency Loan repayment for Weeby</option>
<option value="1218"> nID: 270309 - Safekeeping</option>
<option value="1134"> nID: 271766 - Hoylt Account</option>
<option value="1175"> nID: 275191 - MHD</option>
<option value="1127"> nID: 281161 - Veritam National Funds</option>
<option value="1129"> nID: 281419 - Hello</option>
<option value="1143"> nID: 283300 - Spook Main</option>
<option value="1150"> nID: 283300 - Spook Buisness</option>
<option value="1130"> nID: 284901 - Olympus</option>
<option value="1217"> nID: 292549 - Darth Noxix</option>
<option value="1200"> nID: 293209 - Shares </option>
<option value="1198"> nID: 293792 - TSheikh</option>
<option value="1199"> nID: 312374 - savings</option>
<option value="1216"> nID: 326052 - The sleepy boy</option>
<option value="1224"> nID: 332799 - gibmony</option>
<option value="1225"> nID: 332799 - Manraj Singh</option>
<option value="1228"> nID: 363704 - Berry Usoro</option>
<option value="1229"> nID: 333371 - Alfred Nicholas I</option>
<option value="1230"> nID: 334321 - VectorLord</option>
<option value="1231"> nID: 345051 - Savings</option>








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