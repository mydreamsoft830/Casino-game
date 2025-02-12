<?php

namespace Database\Seeders;

use App\Helpers\PackageManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(PackageManager $packageManager)
    {
        // run extra packages seeders if there are any
        foreach ($packageManager->getEnabled() as $package) {
            $seederFile = sprintf('%sPackageSeeder.php', Str::of($package->id)->title()->replace('-', ''));

            if (file_exists(base_path(sprintf('packages/%s/database/seeders/%s', $package->base_path, $seederFile)))) {
                $this->call(str_replace('.php', '', $seederFile));
            }
        }
    }
}
