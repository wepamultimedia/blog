<?php

namespace Wepa\Blog\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;


class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
	        'url' => request()->root() . '/' . $this->seo->slug,
	        'start_at' => $this->start_at,
	        'category_name' => $this->category_name,
	        'slug' => $this->seo->slug,
	        'body' => $this->when(!$request->routeIs('*blog*.index'), function(){
				return $this->body;
	        })
        ];
    }
}
