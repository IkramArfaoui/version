<?php

namespace Alteis\Bundle\VersionBundle\Helper;

use GuzzleHttp\Client as ClientGuzzle;
use Alteis\Bundle\VersionBundle\Entity\ReleaseNoteParams;

class ReleaseNote
{
    /**
     * @param ReleaseNoteParams $params
     * @param string $version
     * @return array
     */
    public function getApiContent(ReleaseNoteParams $releaseNoteParams, string $version): array
    {
        $client =  new ClientGuzzle(['verify' => false]);
        $baseurl = rtrim($releaseNoteParams->getBaseUrl(),'/') . '/';
        $uri = $baseurl . $releaseNoteParams->getProjectId() . '/issues?milestone=' . $version . '&state=closed&private_token=' . $releaseNoteParams->getToken();

        $content = $client->get($uri);
        $content = \json_decode($content->getBody()->getContents());

        return $content;
    }

    /**
     * @param array $content
     * @param string $path
     * @param string $version
     */
    public function generateFile(array $content, string $path, string $version): void
    {
        $filename = $path . DIRECTORY_SEPARATOR . 'note-de-version-' . $version . '.md';
        $title = " # Liste des demandes terminées :\n\n";
        foreach ($content as $con){
            $title  .= "- " . $con->title . "\n\n";
        }
        file_put_contents($filename,$title,FILE_APPEND | LOCK_EX);
    }

    /**
     * @param string $path
     * @param string $baseUrl
     * @param string $token
     * @param int $projectId
     * @param string $dir
     * @return ReleaseNoteParams
     */
    public function getParams(string $path, string $baseUrl, string $token, int $projectId, string $dir): ReleaseNoteParams
    {
        $path = '\\' . trim($path,'\\') . '\\';
        $allPath = str_replace(DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'app','',$dir);
        $allPath .= $path;

        $params = new ReleaseNoteParams($baseUrl,$projectId,$token,$allPath);

        return $params;
    }
}