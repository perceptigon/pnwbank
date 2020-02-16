@extends('layouts.admin')

@section('content')

    <section class="content-header">
        <h1>Spreadsheet Exporting</h1>
        <br>
        <p>
            This will allow you to easily look at and share an overall target list. Simply do the following:
        </p>
            <ul>
                <li>Select everything in the text area (click in it and CTRL + A)</li>
                <li>Create a new text file on your computer and post it all in there, and save it.</li>
                <li>Go to <a href="http://sheets.google.com">Google Sheets</a> and open up a new spreadsheet.</li>
                <li>Go to File >> Import... >> Upload >> Select a file from your computer. Navigate to the text file you saved.</li>
                <li>Use the settings "Replace spreadsheet, comma, yes". Then import.</li>
                <li>You might have to do some formatting to make it look alright, but everything should be there.</li>
            </ul>

    </section>

    <section class="content">
        @include("admin.alerts")

        <textarea class="scrollbox" style="width: auto; height: auto">{{\App\Classes\Targets::spreadsheetExport()}}</textarea>
    </section>

@endsection