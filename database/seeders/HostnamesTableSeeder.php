<?php

namespace Database\Seeders;

use Workspace\Hostname;
use Illuminate\Database\Seeder;

class HostnamesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $hostname = Hostname::firstOrNew(['fqdn' => 'localhost']);

        if (!$hostname->exists) {
            $hostname->fill([
                    'fqdn' => 'localhost',
                ])->save();
        }
    }
}