@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Recruiting</h1>
    </section>
    <section class="content">
        @include("admin.alerts")

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Status</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form method="post">
                    <div class="form-group">
                        <label>Recruiting Script Status</label>
                        <div class="radio">
                            <label><input type="radio" name="onOff" value="1" class="radio" {{ $onOff->status === 1 ? 'checked' : '' }}> On</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="onOff" value="0" class="radio" {{ $onOff->status === 0 ? 'checked' : '' }}> Off</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save" name="editOnOff" class="btn btn-warning">
                        {{ csrf_field() }}
                    </div>
                </form>
                <p>Total Messages Sent - {{ number_format($nations->total()) }}</p>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Message</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="post">
                            <div class="form-group">
                                <label>Message</label>
                                <p>Hey {nation->leader},</p>
                                <textarea rows="15" name="recruitMessage" class="form-control">{{ $recruitMessage->value }}</textarea>
                                <p>
                                    <br>
                                    <br>
                                    As always, if you have any questions about Camelot or the game, in general, let me know and I will get back to you as soon as possible.
                                    <br>
                                    <br>
                                    Thanks!</p>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="editMessage" value="Save" class="btn btn-warning">
                                {{ csrf_field() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Latest Messages</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>Time</th>
                            <th>Nation ID</th>
                        </tr>
                        @foreach ($nations as $nation)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($nation->inputDate)->toDateTimeString() }}</td>
                                <td><a href="https://politicsandwar.com/nation/id={{ $nation->nationID }}" target="_blank">{{ $nation->nationID }}</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="text-center">
                    {{ $nations->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection