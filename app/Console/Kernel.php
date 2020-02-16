<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\CheckLoanPayments::class,
        Commands\CheckLateLoans::class,
        Commands\CheckLoansDueToday::class,
        Commands\CheckMarketOffers::class,
        Commands\MemberUpdate::class,
        Commands\StatsUpdate::class,
        Commands\TaxCollect::class,
        Commands\CheckNewWars::class,
        Commands\CheckApplications::class,
        Commands\CheckMasks::class,
        Commands\DefenseDaily::class,
        Commands\SignInReset::class,
        Commands\CheckCities::class,
        Commands\UpdateMembers::class,
        Commands\CheckInactiveNations::class,
        Commands\RecruitNations::class,
        Commands\CheckEmployment::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("loan:checkLoanPayments")->cron('10 * * * *');
        $schedule->command("loan:CheckLateLoans")->cron('11 0 * * *');
        $schedule->command("loan:checkLoansDueToday")->cron('12 0 * * *');
        $schedule->command("market:checkOffers")->cron('13 * * * *');
        $schedule->command("tax:collect")->cron("5 */2 * * *");
        $schedule->command("stats:update")->dailyAt("00:10");
        $schedule->command("checkWars")->everyFiveMinutes();
        $schedule->command("defense:daily")->cron("7 20 * * *");
        $schedule->command("defense:signinreset")->cron("10 0 * * 1,4");
        $schedule->command("tibernet:CheckApplications")->cron('9 * * * *');
        $schedule->command("tibernet:CheckMasks")->cron('10 * * * *');
        $schedule->command("tibernet:CheckCities")->cron('11 */4 * * *');
        $schedule->command("tibernet:UpdateMembers")->cron('9 20 * * *');
        $schedule->command("inactiveCheck")->cron('5 1 * * *');
        //$schedule->command("recruitNations")->cron('4,9,14,19,24,29,34,39,44,49,54 * * * * ');
        $schedule->command("check:employment")->cron('30 3 * * *');
    }
}
