<?php

namespace App\Console\Commands;

use Workspace\FaviconGenerator;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Started test Command');
        $regionDataType = DataType::where('slug', 'products')->firstOrFail();


        // $dataRow = $this->dataRow($regionDataType, 'id');
        // if (!$dataRow->exists) {
        //     $dataRow->fill([
        //         'type'         => 'number',
        //         'display_name' => __('voyager-site::seeders.data_rows.id'),
        //         'required'     => 1,
        //         'browse'       => 1,
        //         'read'         => 0,
        //         'edit'         => 0,
        //         'add'          => 0,
        //         'delete'       => 0,
        //         'order'        => 1,
        //     ])->save();
        // }

        $menu = Menu::where('name', 'admin')->firstOrFail();

        $structureMenuItem = MenuItem::where('title','Products')->where('menu_id',$menu->id)->first();

        // // CONTENT ROOT MENU
        // $structureMenuItem = MenuItem::firstOrNew([
        //     'menu_id' => $menu->id,
        //     'title'   => __('voyager-site::seeders.menu_items.content'),
        //     'url'     => '',
        // ]);
        // if (!$structureMenuItem->exists) {
        //     $structureMenuItem->fill([
        //         'target'     => '_self',
        //         'icon_class' => 'voyager-medal-rank-star',
        //         'color'      => null,
        //         'parent_id'  => null,
        //         'order'      => 99,
        //     ])->save();
        // }


        // // PAGES
        // $menuItem = MenuItem::firstOrNew([
        //     'menu_id' => $menu->id,
        //     'title'   => __('voyager-site::seeders.menu_items.pages'),
        //     'url'     => '',
        //     'route'   => 'voyager.pages.index',
        // ]);
        // if (!$menuItem->exists) {
        //     $menuItem->fill([
        //         'target'     => '_self',
        //         'icon_class' => 'voyager-file-text',
        //         'color'      => null,
        //         'parent_id'  => $structureMenuItem->id,
        //         'order'      => 1,
        //     ])->save();
        // }

        $this->info($menu);

        $this->info($structureMenuItem);

        $this->info($regionDataType);

        return Command::SUCCESS;
    }
}
