<?php

use App\Http\Resources\BannerResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\StatResource;
use App\Http\Resources\TalkResource;
use App\Http\Resources\TeamResource;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Stat;
use App\Models\Talk;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/banners', function () {
    return BannerResource::collection(Banner::all());
});

Route::get('/events', function () {
    return EventResource::collection(Event::all());
});


Route::get('/stats', function () {
    return StatResource::collection(Stat::all());
});


Route::get('/talks', function () {
    return TalkResource::collection(Talk::all());
});

Route::get('/teams', function () {
    return TeamResource::collection(Team::all());
});
