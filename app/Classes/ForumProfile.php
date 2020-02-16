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
    public $profile;

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
    public $posts;

    /**
     * An array of integers with their group IDs.
     *
     * @var array
     */
    public $groups = [];

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
     * @throws \Exception
     */
    public function getForumProfile()
    {
        if (! $this->getMemberID())
            throw new \Exception("Nation ID not found in profile fields"); // We'll throw another exception here so it can go up and stop the request
        $this->fetchProfile();
        $this->organizeProfile();
    }

    /**
     * Get and stores the member's ID from the ForumFields table in the forums database.
     *
     * @return bool
     */
    private function getMemberID() : bool
    {
        try // We get the member ID by querying the profile fields table for the nation ID field
        {
            $fields = \App\Forums\ForumFields::where("field_11", $this->nID)->firstOrFail();
        }
        catch (\Exception $ex) // If it throws an exception, then the field doesn't exist
        {
            return false;
        }

        $this->mID = $fields->member_id;

        return true;
    }

    /**
     * Tries to fetch the user's profile from the forum API.
     *
     * @return void
     * @throws \Exception
     */
    private function fetchProfile()
    {
        $forum = new Forums();
        $profile = $forum->getMember($this->mID);

        $json = \json_decode($profile, true);

        if (isset($json["errorCode"]))
            throw new \Exception("Couldn't get member profile - {$json["errorCode"]} - {$json["errorMessage"]}");
        $this->profile = $json; // Save the entire array
    }

    /**
     * Takes the user profile and organizes it for easy use.
     *
     * @throws \Exception
     */
    private function organizeProfile() // Set things that I need as a property of this object
    {
        array_push($this->groups, $this->profile["primaryGroup"]["id"]);

        // Run loop to store secondary groups
        foreach ($this->profile["secondaryGroups"] as $sec)
            array_push($this->groups, $sec["id"]);

        if (! isset($this->profile["posts"])) // I have to manually add the posts field to the API, so if it isn't set (cuz I updated) throw an exception
            throw new \Exception("Couldn't get member's post count");
        /*
         * So I remember in the future, in order to get the post count on the forum API do this
         * Go to /system/Member/Member.php (On the forum software obviously)
         * In the function "apiOutput()" add to the return array "'posts' => $this->member_posts
         */

        $this->posts = $this->profile["posts"];
    }
}
