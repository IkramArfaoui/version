<?php


namespace Alteis\Bundle\VersionBundle\Entity;


class ReleaseNoteParams
{
    /**
     * @var int $projectId
     */
    private $projectId;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $token
     */
    private $token;

    /**
     * @var string $baseUrl
     */
    private $baseUrl;


    public function __construct(string $baseUrl,int $projectId, string $token,string $path)
    {
        $this->baseUrl = $baseUrl;
        $this->projectId = $projectId;
        $this->token = $token;
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(?int $projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(?string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(?string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(?string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }



}