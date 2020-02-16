@extends('layouts.app')

@section('content')
    <h1 class="text-center">Activity Grants</h1>
    @if ($system === 1)
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form method="post">
                    <div class="form-group">
                        <label for="nID">Nation ID</label>
                        <input type="number" class="form-control" id="nID" name="nID" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="Request">
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 style="font-size: 20px;">Sorry</h4>
            <p>The activity grant system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <h4>Thresholds</h4>
        <ul>
            <li>20 Posts - $50,000</li>
            <li>100 Posts - $500,000</li>
            <li>$500,000 for every 100 posts after that (i.e. 200, 300, 400...)</li>
            <li>An additional $500,000 for every 1,000 posts (that is a total of 1m for 1k posts)</li>
        </ul>
        <h4>Rules</h4>
        <ul>
            <li>You can only claim the latest threshold you've passed. <strong>No retroactive grants</strong></li>
            <li>You can only claim 1 threshold at a time</li>
            <li>
                While spam posts do count, you may not make pointless spam. That means that you may not make pointless topics or make multiple posts in the same topic (unless it actually makes sense for you to do so).
                <ul>
                    <li>
                        Example of what is okay:
                        <ul>
                            <li>Posting in all the topics in the strip club</li>
                        </ul>
                    </li>
                    <li>
                        Example of what is not okay:
                        <ul>
                            <li>Posting 100 times in one topic in the strip club</li>
                            <li>Gravedigging 100 old topics in the General Assembly</li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>As always, these terms are subject to change at any time</li>
        </ul>
    </div>
@endsection