<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;

class LinkController extends Controller
{
    public function store(StoreLinkRequest $request): LinkResource
    {
        $link = Link::create([
            'url' => $request->validated('url'),
            'code' => $request->validated('code') ?? Link::generateCode(),
        ]);

        return new LinkResource($link);
    }

    public function show(Link $link): LinkResource
    {
        return new LinkResource($link);
    }
}
