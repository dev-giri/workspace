<?php

namespace App\Console\Commands;

use Workspace\FaviconGenerator;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Illuminate\Console\Command;

class FaviconGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favicon:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Favicon Generator Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Started Favicon Generator Command');

//shell_exec("ls");
//  $output = shell_exec("magick");
// echo "<pre>$output</pre>";


// $manager = new ImageManager(['driver' => 'imagick']);

// // to finally create image instances
// $image = $manager->make('public/workspace/favicon.png')->resize(300, 200);


         // if (!extension_loaded('imagick')){
         //        echo 'imagick not installed';
         //    }

        $favicon = new FaviconGenerator(public_path('workspace/favicon.png'), 'favicon');
        $favicon->generateFaviconsFromImagePath();

        return Command::SUCCESS;
    }
}
