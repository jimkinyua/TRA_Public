<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class featuredServices extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $services = \DB::table('FeaturedServices')->get(['Title', 'ShortDecsription']);
        return view("widgets.featured_services", [ 'services' => $services ]);
    }
}
