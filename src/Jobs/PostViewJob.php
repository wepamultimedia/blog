<?php

namespace Wepa\Blog\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wepa\Blog\Models\Post;

class PostViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Post $post;
    public string $clientIp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, string $clientIp)
    {
        $this->post = $post;
        $this->clientIp = $clientIp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $views = collect($this->post->views);

        if (!$views->contains('ip', $this->clientIp)) {
            $this->post->views = $views->merge([['ip' => $this->clientIp]])->toArray();
            $this->post->save();
        }
    }
}
