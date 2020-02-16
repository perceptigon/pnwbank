<?php

    $client = new App\Classes\PWClient();
    $html = $client->getPage("http://yoso.dev/bank/test.html");
        $dom = new \Symfony\Component\DomCrawler\Crawler($html);
        //$x = $dom->filter('#scrollme > table > tbody > tr:nth-child(3) > td:nth-child(2) > img')->attr("title");
//$y = $x->filter("img")->attr("title");
    //echo $x;

    for ($row = 2; $row < 52; $row++)
    {
        try {
            $timestamp = strtotime($dom->filter("#scrollme > table > tbody > tr:nth-child($row) > td:nth-child(2)")->text());
            $note = $dom->filter("#scrollme > table > tbody > tr:nth-child($row) > td:nth-child(2) > img")->attr("title");
            $sender = $dom->filter("#scrollme > table > tbody > tr:nth-child($row) > td:nth-child(3) > a")->text();
            $money = $dom->filter("#scrollme > table > tbody > tr:nth-child($row) > td:nth-child(6)")->text();
            echo "$timestamp - $note - $sender - $money <br>";
        }
        catch (\Exception $e) {
            continue;
        }

    }