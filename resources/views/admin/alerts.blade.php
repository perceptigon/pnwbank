{{-- Display alert if this is a local environment --}}
@if (App::environment('local'))
    {{ App\Classes\Output::genAlert(["You are in a local development environment"], "warning", "Local Environment") }}
@endif
{{-- Display info alert if dev mode is on --}}
@if (App\Models\Settings::getSettings()["devMode"] == true)
    {{ App\Classes\Output::genAlert(["Dev mode is on. Nothing will actually be sent"], "info", "Dev Mode") }}
@endif

@if (isset($output->errors) && count($output->errors))
    {{ App\Classes\Output::genAlert($output->errors, "danger", "Error") }}
@endif
@if (isset($output->successes) && count($output->successes))
    {{ App\Classes\Output::genAlert($output->successes, "success", "Success") }}
@endif
@if (isset($output->info) && count($output->info))
    {{ App\Classes\Output::genAlert($output->info) }}
@endif