<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class Forums
{
    /**
     * The Forum's API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The GuzzleHttp client that we'll use.
     *
     * @var Client
     */
    protected $client;

    /**
     * Forums constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (env("FORUM_API_KEY") == null)
            throw new \Exception("FORUM_API_KEY not set");
        $this->apiKey = env("FORUM_API_KEY");

        $this->client = new Client([
            "base_uri" => "https://bkpw.net/api/",
            "verify"   => false,
            "curl"     => [
                CURLOPT_USERPWD => $this->apiKey,
            ],
        ]);
    }

    /**
     * GET: /core/hello.
     *
     * Get basic information about the community.
     *
     * @return StreamInterface
     */
    public function hello() : StreamInterface
    {
        return $this->client->get("core/hello")->getBody();
    }

    /**
     * GET: /core/members.
     *
     * Get list of members. 25 per page.
     *
     * @param int $page
     * @param string $sortBy
     * @param string $sortDir
     * @return StreamInterface
     */
    public function listMembers(int $page = 1, string $sortBy = "ID", string $sortDir = "asc") : StreamInterface
    {
        return $this->client->get("core/members", [
            "query" => [
                "page"    => $page,
                "sortBy"  => $sortBy,
                "sortDir" => $sortDir,
            ],
        ])->getBody();
    }

    /**
     * GET: /core/members/{id}.
     *
     * Get information about a specific member
     *
     * @param int $id
     * @return StreamInterface
     */
    public function getMember(int $id) : StreamInterface
    {
        return $this->client->get("core/members/{$id}")->getBody();
    }

    /**
     * POST: /core/members.
     *
     * Create a member
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int $group
     * @return StreamInterface
     */
    public function createMember(string $name, string $email, string $password, int $group) : StreamInterface
    {
        return $this->client->post("core/members", [
            "forum_params" => [
                "name"     => $name,
                "email"    => $email,
                "password" => $password,
                "group"    => $group,
            ],
        ])->getBody();
    }

    /**
     * POST: /core/members/{id}.
     *
     * Edit a member
     *
     * @param int $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @return StreamInterface
     */
    public function editMember(int $id, string $name = null, string $email = null, string $password = null) : StreamInterface
    {
        $postData = [];
        if ($name != null)
            $postData["name"] = $name;
        if ($email != null)
            $postData["email"] = $email;
        if ($password != null)
            $postData["password"] = $password;

        return $this->client->post("core/members/{$id}", [
            "forum_params" => $postData,
        ])->getBody();
    }

    /**
     * DELETE: /core/members/{id}.
     *
     * Deletes a member
     *
     * @param int $id
     * @return StreamInterface
     */
    public function deleteMember(int $id) : StreamInterface
    {
        return $this->client->delete("core/members/{$id}")->getBody();
    }

    /**
     * GET: /forums/posts.
     *
     * Get list of posts
     *
     * @param array $params
     * @return StreamInterface
     */
    public function listPosts(array $params = null) : StreamInterface
    {
        return $this->client->get("forums/posts", [
            "query" => $params,
        ])->getBody();
    }

    /**
     * GET: /forums/posts/{id}.
     *
     * View information about a specific post
     *
     * @param int $id
     * @return StreamInterface
     */
    public function getPost(int $id) : StreamInterface
    {
        return $this->client->get("forums/posts/{$id}")->getBody();
    }

    /**
     * POST: /forums/posts.
     *
     * Creates a post
     *
     * @param int $topic
     * @param int $author
     * @param string $post
     * @param array $other Contains all the other options for this call. It's optional.
     * @return StreamInterface
     */
    public function createPost(int $topic, int $author, string $post, array $other = []) : StreamInterface
    {
        // Setup postData properly with the $other array
        $postData = [
            "topic"  => $topic,
            "author" => $author,
            "post"   => $post,
        ];

        // Loop over the $other array and add it to the $postData array
        foreach ($other as $key => $value)
            $postData[$key] = $value;

        return $this->client->post("forums/posts", [
            "query" => $postData,
        ])->getBody();
    }

    /**
     * POST: /forums/posts/{id}.
     *
     * Edits a post
     *
     * @param int $id
     * @param array $params
     * @return StreamInterface
     */
    public function editPost(int $id, array $params) : StreamInterface
    {
        return $this->client->post("forums/posts/{$id}", [
            "forum_params" => $params,
        ])->getBody();
    }

    /**
     * DELETE: /forums/posts/{id}.
     *
     * Deletes a post
     *
     * @param int $id
     * @return StreamInterface
     */
    public function deletePost(int $id) : StreamInterface
    {
        return $this->client->delete("forums/posts/{$id}")->getBody();
    }

    /**
     * GET: /forums/topics.
     *
     * Gets a list of all topics. 25 per page
     *
     * @param array $params Optional param to add options as listed in the API reference
     * @return StreamInterface
     */
    public function getAllTopics(array $params = []) : StreamInterface
    {
        return $this->client->get("forums/topics", [
            "query" => $params,
        ])->getBody();
    }

    /**
     * GET: /forums/topics/{id}.
     *
     * Gets posts in a topic
     *
     * @param int $id
     * @param int $page
     * @return StreamInterface
     */
    public function getTopic(int $id, int $page = 1) : StreamInterface
    {
        return $this->client->get("forums/topics/{$id}", [
            "query" => [
                "page" => $page,
            ],
        ])->getBody();
    }

    /**
     * POST: /forums/topics.
     *
     * Creates a topic
     *
     * @param int $forum
     * @param int $author
     * @param string $title
     * @param string $post
     * @param array $params Optional array to add other parameters listed in the API reference
     * @return StreamInterface
     */
    public function createTopic(int $forum, int $author, string $title, string $post, array $params = []) : StreamInterface
    {
        // Setup postData properly with the $other array
        $postData = [
            "forum"  => $forum,
            "author" => $author,
            "title"   => $title,
            "post" => $post,
        ];

        // Loop over the $other array and add it to the $postData array
        foreach ($params as $key => $value)
            $postData[$key] = $value;

        return $this->client->post("forums/topics", [
            "form_params" => $postData,
        ])->getBody();
    }

    /**
     * POST: /forums/topics/{id}.
     *
     * Edits a topic
     *
     * @param int $id
     * @param array $params
     * @return StreamInterface
     */
    public function editTopic(int $id, array $params) : StreamInterface
    {
        return $this->client->post("forums/topics/{$id}", [
            "form_params" => $params,
        ])->getBody();
    }

    /**
     * DELETE: /forums/topics/{id}.
     *
     * Deletes a topic
     *
     * @param int $id
     * @return StreamInterface
     */
    public function deleteTopic(int $id) : StreamInterface
    {
        return $this->client->delete("forums/topics/{$id}")->getBody();
    }
}
