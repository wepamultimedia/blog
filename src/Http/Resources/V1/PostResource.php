<?php

namespace Wepa\Blog\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'summary' => $this->summary,
            'cover' => $this->cover,
            'cover_alt' => $this->cover_alt,
            'video_cover' => $this->video_cover,
            'url' => request()->root() . '/' . $this->seo->slug,
            'start_at' => Carbon::createFromDate($this->start_at)->translatedFormat('d M Y'),
            'category_name' => $this->category_name,
            'visits' => $this->visits()->count(),
            'draft' => $this->draft,
            'views' => $this->when($request->routeIs('*blog*.index'), function () {
                return count($this->views);
            }),
            'position' => $this->when($request->routeIs('*blog*.index'), function () {
                return $this->position;
            }),
            'likes' => $this->likes,
            'slug' => $this->seo->slug,
            'body' => $this->when(!$request->routeIs('*blog*.index'), function () {
                return $this->body;
            }),
            'survey_id' => $this->survey_id
        ];
    }
}
