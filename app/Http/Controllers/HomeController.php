<?php

namespace App\Http\Controllers;

use App\HotComment;
use App\Jobs\HotCommentsSync;
use App\Song;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * 主接口
     */
    public function index()
    {
        Redis::connection()->incr('counter');
        return response()->json(HotComment::getOneRandHotComment());
    }

    /**
     * 统计
     */
    public function getCount()
    {
        return response()->json([
            'songs_count'       => Song::count(),
            'comments_count'    => HotComment::count(),
            'api_request_count' => (int)app('redis')->get('counter'),
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
