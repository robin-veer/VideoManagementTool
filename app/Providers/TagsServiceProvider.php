<?php

namespace App\Providers;

use App\Tag;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class TagsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layout.video.side-menu', function($view)
        {
            $view->with('tags', Tag::withCount('videos')->get()->sortBy('name'));
        });

        view()->composer('layout.video.form.tags', function($view)
        {
            $view->with('tags', Tag::All()->sortBy('name'));
        });
    }
}
