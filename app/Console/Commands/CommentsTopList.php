<?php

namespace App\Console\Commands;

use App\Song;
use App\HotComment;
use App\Jobs\HotCommentsSync;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CommentsTopList extends Command
{
    /**
     * 控制台命令 signature 的名称。
     *
     * @var string
     */
    protected $signature = 'comments:toplist';

    /**
     * 控制台命令说明。
     *
     * @var string
     */
    protected $description = 'Sync from top list';

    /**
     * 创建一个新的命令实例。
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令。
     *
     * @return mixed
     * @throws GuzzleException
     */
    public function handle()
    {
        $song = new Song();
        dispatch(new HotCommentsSync($song->getTopList()));

        $this->info('Top list sync complete!');
    }
}
