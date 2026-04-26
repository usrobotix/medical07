<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CommonMarkConverter::class, function () {
            return new CommonMarkConverter([
                'html_input'         => 'escape',
                'allow_unsafe_links' => false,
                'max_nesting_level'  => 10,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('markdown', function ($expression) {
            return "<?php echo app(\League\CommonMark\CommonMarkConverter::class)->convert($expression)->getContent(); ?>";
        });
    }
}
