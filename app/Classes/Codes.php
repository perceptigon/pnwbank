public static function generateCode(): int
{
    $attemptCount = 0;
    $code = "";

    do {
        if ($attemptCount === 20) {
            throw new UserErrorException("Couldn't generate unique code, try again");
        }

        $code = rand(1000, 2000000);

        $uniqueCheck = Loans::where("code", $code)
            ->orWhere("code", $code)
            ->orWhere("code", $code)
            ->count();

        if ($uniqueCheck === 0) {
            break;
        }

        $attemptCount++;
    } while (true);

    return $code;
}
