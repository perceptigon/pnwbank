<?php

namespace App\Models;

use App\Classes\PWBank;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class MarketDeals extends Model
{
    public $timestamps = false;
    protected $table = 'marketdeals';

    /**
     * Get the last 15 most recent offers.
     *
     * @return Collection
     */
    public static function getLast15Deals() : Collection
    {
        return self::orderBy("timestamp", 'desc')
            ->limit(15)
            ->get();
    }

    /**
     * Delete an offer/deal.
     *
     * @param int $dealCode
     */
    public static function deleteDeal(int $dealCode)
    {
        self::where("code", $dealCode)->delete();
    }

    /**
     * Get the information about a deal.
     *
     * @param int $dealCode
     * @return MarketDeals
     */
    public static function getDealInfo(int $dealCode) : MarketDeals
    {
        return self::where("code", $dealCode)->firstOrFail();
    }

    /**
     * Get all of the currently pending offers.
     *
     * @return Collection
     */
    public static function getPendingOffers() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Expire a deal that's a couple hours old.
     *
     * @param PWClient $client
     * @throws \Exception
     */
    public function expireDeal(PWClient $client)
    {
        $this->isExpired = true;
        $this->isPending = false;
        $this->save();

        // Update pool of resources
        $resource = \App\Models\Market::getResourceInfo($this->resource);
        $resource->amount += $this->amount;
        $resource->save();

        $message = "Hi $this->leader, \n \n Your tranaction where you tried to sell $this->amount units of $this->resource with the code $this->code has expired. If you want to sell those resources again, please resubmit a request.".PWFunctions::endMessage();

        $client->sendMessage($this->leader, "Transaction Expired", $message);
        Log::createLog("market", "offer $this->code expired");
    }

    /**
     * Mark an offer/loan as paid.
     *
     * @param int $value
     * @param PWClient $client
     * @throws \Exception
     */
    public function markPaid(int $value, PWClient $client)
    {
        if ($this->verify($value)) // Offer is valid, go through with marking it paid
        {
            $this->isPending = false;
            $this->isPaid = true;
            $this->save();

            $cost = $this->amount * $this->ppu;
            // Send the money
            $bank = new PWBank();
            $bank->recipient = $this->nationName;
            $bank->note = "Resource Purchase";
            $bank->money = $cost;
            $bank->send($client);

            $message = "Hi $this->leader \n \n Your offer was verified and the money has been sent to you. Thank you!".PWFunctions::endMessage();
            $client->sendMessage($this->leader, "You've Been Paid!", $message);
            Log::createLog("market", "Deal Complete - $this->code");
        }
        else // Offer not valid, send error message
        {
            $message = "Hi $this->leader, \n \n We saw that you tried to deposit some resources into the bank. It seems that there was an error while processing your request. This could be due to you sending in the wrong resource or the wrong amount. Please contact us with the link below in order to resolve this. \n \n The transaction in question is with the code $this->code to sell $this->amount units of $this->resource.".PWFunctions::endMessage();
            $client->sendMessage($this->leader, "Market Deposit Error", $message);
            Log::createLog("market", "Payment Error - $this->code");
        }
    }

    /**
     * Verify that the amount of resources they deposited into the bank is what is expected.
     *
     * @param int $value
     * @return bool
     */
    private function verify(int $value) : bool
    {
        if ($value == $this->amount)
            return true;
        else
            return false;

    }

    /**
     * Counts the amount of money sent to them in market deals.
     *
     * @param int $nID
     * @return int
     */
    public static function getTotalMemberSent(int $nID) : int
    {
        return self::where("isPaid", true)->where("nationID", $nID)->sum("cost");
    }
}
