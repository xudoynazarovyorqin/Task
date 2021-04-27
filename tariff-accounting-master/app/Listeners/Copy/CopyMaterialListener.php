<?php

namespace App\Listeners\Copy;

use App\Events\Copy\CopyMaterialEvent;
use App\Material;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CopyMaterialListener
{

    public function __construct()
    {
        //
    }

    public function handle(CopyMaterialEvent $event)
    {
        if ($material = $event->getMaterial()){
            $model = Material::find($material->id);

            $newModel = $model->replicate();
            $newModel->name = $model->name . '(копия)';

            $newModel->push();
        }
    }
}
