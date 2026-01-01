<?php



namespace App\Providers;



use App\Contracts\Common\LookupContract;

use App\Contracts\GLProcessContract;
use App\Managers\GLProcessManager;
use App\Managers\LookupManager;

use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider

{

    /**

     * Register any application services.

     *

     * @return void

     */

    public function register()

    {

        $this->app->bind(LookupContract::class, LookupManager::class);
        $this->app->bind(GLProcessContract::class, GLProcessManager::class);

    }



    /**

     * Bootstrap any application services.

     *

     * @return void

     */

    public function boot()

    {

        //

    }

}

