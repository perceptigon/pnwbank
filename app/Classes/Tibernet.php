<?php

namespace App\Classes;

use App\Models\Noob;
use App\Classes\Forums;

class Tibernet
{
    /**
     * Gets a member's forum ID by their nation ID.
     *
     * @param int $nation_id
     * @return int
     */
    public static function getForumID(int $nation_id) : int
    {
        try
        {
            $forumProfile = new ForumProfile($nation_id);

            $forumProfile->getForumProfile();

            return $forumProfile->profile["id"];
        }

        catch (\Exception $e)
        {
            return -1;
        }
    }

    /**
     * Creates a new noob.
     *
     * @param int $nation_id
     * @param string $nation_name
     * @param string $nation_ruler
     * @param int $forum_id
     * @param string $forum_name
     */
    public static function addNoob(int $nation_id, string $nation_name, string $nation_ruler, int $forum_id, string $forum_name)
    {
        $noob = new Noob;
        $noob->nation_id = $nation_id;
        $noob->nation_name = $nation_name;
        $noob->nation_ruler = $nation_ruler;
        $noob->forum_id = $forum_id;
        $noob->forum_name = $forum_name;
        $noob->member = false;
        $noob->save();
    }

    /**
     * Returns a status for something?
     *
     * @param string $forum_name
     * @param int $forum_id
     * @param \App\Classes\Nation $nation
     * @return string
     */
    public static function status(string $forum_name, int $forum_id, Nation $nation)
    {
        if ($nation->alliance != 'The Rothschild Family')
            return 'not_bk';
        elseif ($forum_id < 1600)
            return 'too_old';
        elseif ($forum_name == 'LordStrum')
            return 'no_account'; // TODO wtf shouldn't this check if there's no forum account?
        else
            return 'posted';
    }

    /**
     * Checks if the noob is accepted.
     *
     * @param $f_id
     * @return string
     */
    public static function is_accepted(int $f_id)
    {
        $forums = new Forums;
        $params = [
            "forums" => 98,
            "authors" => $f_id
        ];
        $topics = $forums->getAllTopics($params);

        $topics = json_decode($topics);
        $topics = $topics->totalResults;

        return $topics;
    }

    /**
     * Checks if the noob is denied.
     *
     * @param $f_id
     * @return string
     */
    public static function is_rejected(int $f_id)
    {
        $forums = new Forums;
        $params = [
            "forums" => 99,
            "authors" => $f_id
        ];
        $topics = $forums->getAllTopics($params);

        $topics = json_decode($topics);
        $topics = $topics->totalResults;

        return $topics;
    }

    public static function getMask($f_id)
    {
        $forums = new Forums;
        $response = $forums->getMember($f_id);
        $response = json_decode($response);
        $mask = $response->primaryGroup->id;

        return $mask;
    }
}
