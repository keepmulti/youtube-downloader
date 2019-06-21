<?php


namespace App\Http\Libraries\Youtube;

use App\Http\Libraries\Request\Request;

class Youtube
{
    /**
     * HTML code of videoURL
     *
     * @var $string ;
     */
    private $htmlCode;

    /**
     * URL of YoutubeVideo
     *
     * @var string;
     */
    private $url;


    /**
     * itag of video quantity youtube
     *
     * @var array
     */
    private $itagInfo = [
        18 => "360P",
        22 => "720P",
        37 => "1080P",
        38 => "3072P",
        59 => "MP4480P",
        78 => "MP4480P",
        43 => "WebM360P",
        17 => "3GP144P",
        313 => '4K',
        271 => '2K',
        137 => '1080p',
        248 => '1080p',
        136 => '720p',
        247 => '720p',
        135 => '480p',
        244 => '480p',
        134 => '360p',
        243 => '360p',
        133 => '240p',
        242 => '240p',
        160 => '144p',
        278 => '144p',
        140 => 'audio/mp4',
        171 => 'audio/webm',
        249 => 'audio/webm',
        250 => 'audio/webm',
        251 => 'audio/webm',
    ];

    /**
     * create new Youtube instance
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;

        $this->parseHTML();
    }

    /**
     * Get video ID of URL
     *
     * @return mixed
     */
    public function getVideoID()
    {
        $regexVideoID = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";

        if (preg_match($regexVideoID, $this->url, $matches)) {
            return @$matches[1];
        }

        return null;
    }


    /**
     * send request and get HTML
     */
    private function parseHTML()
    {
        $request = new Request($this->url);

        $this->htmlCode = $request->get();
    }

    /**
     * get Thumbnail of video
     *
     * @return string
     */
    public function getThumbnail()
    {
        return 'https://i.ytimg.com/vi/' . $this->getVideoID() . '/maxresdefault.jpg';
    }

    /**
     * get title of video
     *
     * @return mixed|null
     */
    public function getTitle()
    {
        if (preg_match('/<title>(.*?)<\/title>/', $this->htmlCode, $matches)) {
            // remove ' - YouTube' after video tile
            return str_replace(' - YouTube', '', $matches[1]);
        }

        return null;
    }

    /**
     * handle get Link download video Of Youtube
     *
     * @return |null
     */
    public function getLinkDownload()
    {
        // link video after "adaptive_fmts":"
        if (preg_match('/adaptive_fmts["\']:\s*["\']([^"\'\s]*)/', $this->htmlCode, $matches)) {

            // explode video type by ','
            $parts = explode(",", $matches[1]);

            foreach ($parts as $p) {

                $query = str_replace('\u0026', '&', $p);

                parse_str($query, $arr);

                $url = $arr['url'];

                if (isset($arr['sig'])) {
                    $url = $url . '&signature=' . $arr['sig'];

                } elseif (isset($arr['signature'])) {
                    $url = $url . '&signature=' . $arr['signature'];
                }

                $itag = @$arr['itag'];

                // mapping video format
                $format = isset($this->itagInfo[$itag]) ? $this->itagInfo[$itag] : 'N/A';

                // get video extension
                $option = explode(';', $arr['type']);
                $exts = explode('/', $option[0]);
                $ext = end($exts);

                $result[] = [
                    'url' => $url,
                    'video' => $format,
                    'id' => explode('/', trim($option[0]))[0],
                    'full' => false,
                    'type' => trim($option[0]),
                    'encode' => trim($option[1]),
                    'ext' => $ext
                ];
            }

            return $result;
        }

        return null;
    }
}