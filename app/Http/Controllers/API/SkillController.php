<?php

namespace App\Http\Controllers\API;

class SkillController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Skill';
        $this->modelResource = 'App\Http\Resources\Skill';
        $this->modelCollectionResource = 'App\Http\Resources\SkillCollection';
    }
}
