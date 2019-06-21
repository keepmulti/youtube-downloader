<?php

namespace App\Http\Controllers;

use App\Http\Libraries\Youtube\Youtube;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.home');
    }

    /**
     * get video Info
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getVideoInfo(Request $request)
    {
        // validate url
        $this->validate($request, ['url' => 'required|url']);

        $url = $request->get('url');
        $youtubeObject = new Youtube($url);

        if (!$youtubeObject->getVideoID()) {
            return response()->json(['status' => 500, 'message' => ''], 500);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'title' => $youtubeObject->getTitle(),
                'thumbnail' => $youtubeObject->getThumbnail(),
                'downloadlink' => $youtubeObject->getLinkDownload(),
            ]
        ]);
    }
}
