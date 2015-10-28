<?php

use APOSite\Models\CarouselItem as CarouselItem;
use Illuminate\Database\Seeder;

class CarouselItemTableSeeder extends Seeder
{
    public function run()
    {
        $first = new CarouselItem();
        $first->title = 'Welcome to APO!';
        $first->background_image = 'css/images/bluebackground.jpg';
        $first->event_id = -1;
        $first->save();

        $second = new CarouselItem();
        $second->title = 'Cats!';
        $second->background_image = 'css/images/catbackground.jpg';
        $second->action_text = 'Pet the Cat!';
        $second->event_id = -1;
        $second->action_url = '#';
        $second->save();
    }
}
