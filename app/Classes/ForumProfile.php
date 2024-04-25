use App\Forums\ForumFields;
use App\Forums\Forums;

<?php

namespace App\Classes;


class ForumProfile
{
    /**
     * Store the nation ID of the forum profile.
     *
     * @var int
     */
    protected $nID;

    /**
     * An array to store information about their forum profile.
     *
     * @var array
     */
    private $profile;

    /**
     * The user's member ID on the forums.
     *
     * @var int
     */
    protected $mID;

    /**
     * The member's total amount of posts.
     *
     * @var int
     */
    private $posts;

    /**
     * An array of integers with their group IDs.
     *
     * @var array
     */
    private $groups = [];

    /**
     * ForumProfile constructor.
     *
     * @param int $nID
     */
    public function __construct(int $nID)
    {
        $this->nID = $nID;
    }

    /**
     * Calls functions to retrieve the member's forum profile.
     *
     * @return void
     * @throws ForumProfileException
     */
    public function getForumProfile(): void
    {
        if (!$this->getMemberID()) {
            throw new ForumProfileException("Nation ID not found in profile fields");
        }

        $this->fetchProfile();
        $this->organizeProfile();
    }

    /**
     * Get the member's ID from the ForumFields table in the forums database.
     *
     * @return bool
     */
    private function getMemberID(): bool
    {
        try {
            $fields = ForumFields::where("field_2", $this->nID)->firstOrFail();
        } catch (\Exception $ex) {
            return false;
        }

        $this->mID = $fields->member_id;

        return true;
    }

    /**
     * Fetches the user's profile from the forum API.
     *
     * @throws ForumProfileException
     */
    private function fetchProfile(): void
    {
        $forum = new Forums();
        $profile = $forum->getMember($this->mID);

        $json = \json_decode($profile, true);

        if (isset($json["errorCode"])) {
            throw new ForumProfileException("Couldn't get member profile - {$json["errorCode"]} - {$json["errorMessage"]}");
        }

        $this->profile = $json;
    }

    /**
     * Takes the user profile and organizes it for easy use.
     *
     * @throws ForumProfileException
     */
    private function organizeProfile(): void
    {
        array_push($this->groups, $this->profile["primaryGroup"]["id"]);

        foreach ($this->profile["secondaryGroups"] as $sec) {
            array_push($this->groups, $sec["id"]);
        }

        if (!isset($this->profile["posts"])) {
            throw new ForumProfileException("Couldn't get member's post count");
        }

        $this->posts = $this->profile["posts"];
    }

    /**
     * Get the member's profile.
     *
     * @return array
     */
    public function getProfile(): array
    {
        return $this->profile;
    }

    /**
     * Get the member's total amount of posts.
     *
     * @return int
     */
    public function getPosts(): int
    {
        return $this->posts;
    }

    /**
     * Get the member's group IDs.
     *
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}

class ForumProfileException extends \Exception
{
}
