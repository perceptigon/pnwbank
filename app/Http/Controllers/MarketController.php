<?php

namespace App\Http\Controllers;

use App\Classes\Verify;
use App\Classes as Classes;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    /**
     * @var Classes\Output
     */
    private $output;

    /**
     * Store a PWClient if needed.
     *
     * @var Classes\PWClient
     */
    private $client;

    /**
     * MarketController constructor.
     */
    public function __construct()
    {
        $this->output = new Classes\Output();
    }

    /**
     * GET: /market.
     *
     * View Market page
     *
     * @return mixed
     */
    public function market()
    {
        $system = \App\Models\Settings::where("sKey", "allianceMarketSystem")->firstOrFail();

        $resources = \App\Models\Market::all();

        return view("market", [
            'system' => $system->value,
            'resources' => $resources,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /market.
     *
     * Submit a market request
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqMarket(Request $request)
    {
        $settings = \App\Models\Settings::where("sKey", "allianceMarketSystem")->firstOrFail();

        if ($settings === 1)
        {
            echo "The Market system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
            return false;
        }

        try
        {
            $nation = new Classes\Nation($request->nID);
            $verify = new Verify($nation);

            if ($verify->reqMarket($request))
            {
                $details = \App\Models\Market::getResourceInfo($request->resource);

                // Create offer
                $offer = new \App\Models\MarketDeals();
                $offer->nationID = $nation->nID;
                $offer->nationName = $nation->nationName;
                $offer->leader = $nation->leader;
                $offer->resource = $request->resource;
                $offer->amount = $request->amount;
                $offer->ppu = $details->ppu;
                $offer->cost = $details->ppu * $request->amount;
                $offer->code = \App\Classes\Codes::generateCode();
                $offer->save();

                // Subtract the amount being bought with the amount available
                $details->amount = $details->amount - $request->amount;
                $details->save();

                $this->client = new Classes\PWClient();
                $this->client->login();
                $message = "Hi {$nation->leader}, \n \n You requested an offer to sell the alliance resources. Here's the info: \n \n Code: {$offer->code}\n Resource: {$offer->resource} \n Amount: {$offer->amount}\n PPU: $".number_format($offer->ppu)."\n Total: $".number_format($offer->ppu * $offer->amount)."\n \n Please deposit the correct amount of resources into the bank within an hour. ONLY put the code in the transaction note. \n \n The system checks for transactions every hour at :05 so please allow up to an hour for a confirmation email and your money to be sent. If you do not deposit the resources into the bank within an hour, your offer will expire, so send in the resources ASAP.".Classes\PWFunctions::endMessage();

                $this->client->sendMessage($offer->leader, "Offer Details", $message);
                \App\Models\Log::createLog("market", "Accepted Offer ($nation->nID)");

                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("market", "Not eligible for offer ($nation->nID)");
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::market();
    }
}
