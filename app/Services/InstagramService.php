<?php

namespace App\Services;
use Smochin\Instagram\Crawler;


/**
 * Class InstagramService
 *
 * @package App\Services
 */
class InstagramService
{

    public function fetchPostWithTag($tag = 'smallBizHack')
    {
        $crawler = new Crawler();
        $media = $crawler->getMediaByTag($tag);
        dd($media[0]);
        return $this->response->array($media);
    }
}