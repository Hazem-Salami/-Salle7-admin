<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::getQuery()->delete();
        $test = false;
        $routes = [];
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();
            if (array_key_exists('as', $action)) {
                if ($action['as'] == "cms.workshop.request.reject") {
                    $routes[] = $action['as'];
                    break;
                }
                if ($test){
                    if ($action['as'] == "cms.login")
                        continue;
                    $routes[] = $action['as'];
                }
                else {
                    if ($action['as'] == "users.wallets.charges.get") {
                        $test = true;
                    }
                }
            }
        }

        for ($i = 0; $i < sizeof($routes); $i++)
            Permission::create([
                'name' => $routes[$i]
            ]);
    }
}
