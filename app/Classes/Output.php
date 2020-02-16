<?php

namespace App\Classes;

class Output
{
    /**
     * An array of all the errors.
     *
     * @var array
     */
    public $errors = [];

    /**
     * An array with all the success messages.
     *
     * @var array
     */
    public $successes = [];

    /**
     * An array with all of the info messages.
     *
     * @var array
     */
    public $info = [];

    /**
     * An array with all the warning messages
     *
     * @var array
     */
    public $warning = [];

    /**
     * Add an error to the errors array.
     *
     * @return void
     * @param string $message
     */
    public function addError(string $message)
    {
        array_push($this->errors, $message);
    }

    /**
     * Add a success message to the success array.
     *
     * @return void
     * @param string $message
     */
    public function addSuccess(string $message)
    {
        array_push($this->successes, $message);
    }

    /**
     * Add an info message to the info array.
     *
     * @return void
     * @param string $message
     */
    public function addInfo(string $message)
    {
        array_push($this->info, $message);
    }

    /**
     * Adds a warning message
     *
     * @param string $message
     */
    public function addWarning(string $message)
    {
        array_push($this->warning, $message);
    }

    /**
     * Counts the pending requests from everything.
     *
     * @return null|int
     */
    public static function countPendingReqs() // Counts the number of pending requests for... everything
    {
        $num = 0;

        $num += \App\Models\Grants\ActivityGrant::countPendReqs();
        $num += \App\Models\Grants\CityGrantRequests::countPendReqs();
        $num += \App\Models\Loans::countPendReqs();
        $num += \App\Models\Grants\EntranceAid::countPendReqs();
        $num += \App\Models\Grants\IDGrants::countPendReqs();
        $num += \App\Models\Grants\OilGrant::countPendReqs();
        $num += \App\Models\Grants\EGRGrant::countPendReqs();
        $num += \App\Models\Grants\irondomeGrants::countPendReqs();
        $num += \App\Models\Grants\mlpGrants::countPendReqs();
        $num += \App\Models\Grants\pbGrants::countPendReqs();
        $num += \App\Models\Grants\cceGrants::countPendReqs();
        $num += \App\Models\Grants\nrfGrants::countPendReqs();
        return $num > 0 ? $num : null;
    }

    /**
     * Spits out HTML for the alert message.
     *
     * @param array $messages
     * @param string $type
     * @param string $header
     */
    public static function genAlert(array $messages = [], string $type = "info", string $header = "Hey!")
    {
        // Not the right way to do it, but the way we'll do it anyway
        ?>
        <div class="alert alert-<?php echo $type ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <h4 style="font-size: 20px;"><?php echo $header ?></h4>
    <ul>
<?php
                foreach ($messages as $message)
                    echo "<li>$message</li>";
                ?>
            </ul>
    </div>
        <?php
    }
}
