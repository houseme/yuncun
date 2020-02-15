<?php

namespace App\Http\Controllers;

use App\Jobs\HotCommentsSync;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use App\Song;
use App\HotComment;
use Illuminate\Routing\Redirector;

class HomeController extends Controller
{
    /**
     * 主接口
     */
    public function index()
    {
        return response()->json(HotComment::getOneRandHotComment());
    }

    /**
     * 统计
     */
    public function getCount()
    {
        return response()->json([
            'songs_count' => Song::count(),
            'comments_count' => HotComment::count(),
            'api_request_count' => (int)app('redis')->get('counter_/'),
        ]);
    }

    /**
     * 重定向歌曲 URL.
     *
     * @param $song_id
     *
     * @return RedirectResponse|Redirector
     */
    public function redirectMusicUrl($song_id)
    {
        $url = sprintf('https://music.163.com/song/media/outer/url?id=%s.mp3', $song_id);
        return redirect($url);
    }

    /**
     * 提交歌单
     *
     * @param $id
     *
     * @throws GuzzleException
     */
    public function submit($id)
    {
        $song = new Song();
        dispatch(new HotCommentsSync($song->getPlayList($id)));
    }
}
