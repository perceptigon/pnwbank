<style>
    .yn-table {
        width: 100%;
    }
    .yn-table td {
        border: 1px solid #484848;
        padding: 5px;
    }
</style>
<h2><a href="https://politicsandwar.com/nation/id={{ $defender->nID }}" target="_blank">{{ $defender->leader }}</a> has been attacked by <a href="https://politicsandwar.com/nation/id={{ $attacker->nID }}" target="_blank">{{ $attacker->leader }}</a></h2>

@if ($defender->aID != 877)
    <p style="color: red"><strong>This is a protectorate counter raid</strong></p>
@endif

<h3>{{ $defender->leader }}'s Information (Defender)</h3>
<table class="yn-table">
    <tr>
        <th>Leader</th>
        <th>Score</th>
        <th>Soldiers</th>
        <th>Tanks</th>
        <th>Planes</th>
        <th>Ships</th>
        <th>Mil Score</th>
    </tr>
    <tr>
        <td><a href="https://politicsandwar.com/nation/id={{ $defender->nID }}" target="_blank">{{ $defender->leader }}</a></td>
        <td>{{ number_format($defender->score) }}</td>
        <td>{{ number_format($defender->soldiers) }}</td>
        <td>{{ number_format($defender->tanks) }}</td>
        <td>{{ number_format($defender->aircraft) }}</td>
        <td>{{ number_format($defender->ships) }}</td>
        <td>{{ number_format($defender->calcMilScore()) }}</td>
    </tr>
</table>

<h3>{{ $attacker->leader }}'s Information (Attacker)</h3>
<table class="yn-table">
    <tr>
        <th>Leader</th>
        <th>Score</th>
        <th>Soldiers</th>
        <th>Tanks</th>
        <th>Planes</th>
        <th>Ships</th>
        <th>Mil Score</th>
    </tr>
    <tr>
        <td><a href="https://politicsandwar.com/nation/id={{ $attacker->nID }}" target="_blank">{{ $attacker->leader }}</a></td>
        <td>{{ number_format($attacker->score) }}</td>
        <td>{{ number_format($attacker->soldiers) }}</td>
        <td>{{ number_format($attacker->tanks) }}</td>
        <td>{{ number_format($attacker->aircraft) }}</td>
        <td>{{ number_format($attacker->ships) }}</td>
        <td>{{ number_format($attacker->calcMilScore()) }}</td>
    </tr>
</table>

<h4>Possible Counters</h4>
@if ($counters->count() === 0)
    <p>No possible counters founded</p>
@else
    <table class="yn-table">
        <tr>
            <th>Forum</th>
            <th>Leader</th>
            <th>Score</th>
            <th>Soldiers</th>
            <th>Tanks</th>
            <th>Planes</th>
            <th>Ships</th>
            <th>Mil Score</th>
            <th>Mil Score Diff</th>
        </tr>
        @foreach ($counters as $counter)
            <tr>
                <td>
                    @if ($counter->forumProfile != false)
                        @if (($counter->milScore / $attacker->milScore) > 1)
                            <a contenteditable="false" data-ipshover="" data-ipshover-target="{{ $counter->forumProfile->profile["profileUrl"] }}/?do=hovercard" data-mentionid="{{ $counter->forumProfile->profile["id"] }}" href="{{ $counter->forumProfile->profile["profileUrl"] }}" rel="" id="ips_uid_550_7"><?php echo "@".$counter->forumProfile->profile['name']; ?></a>
                        @else
                            {{ $counter->forumProfile->profile["name"] }}
                        @endif
                    @else
                        No Forum Profile
                    @endif
                </td>
                <td><a href="https://politicsandwar.com/nation/id={{ $counter->nID }}" target="_blank">{{ $counter->leader }}</a></td>
                <td>{{ number_format($counter->score) }}</td>
                <td>{{ number_format($counter->soldiers) }}</td>
                <td>{{ number_format($counter->tanks) }}</td>
                <td>{{ number_format($counter->planes) }}</td>
                <td>{{ number_format($counter->ships) }}</td>
                <td>{{ number_format($counter->milScore) }}</td>
                <td style="color: {{ ($counter->milScore / $attacker->milScore) > 1 ? "green" : "red" }}">{{ number_format($counter->milScore / $attacker->milScore, 2) }}</td>
            </tr>
        @endforeach
    </table>
@endif