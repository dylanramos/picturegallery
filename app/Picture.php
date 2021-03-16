<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
  protected $fillable = [
    'title',
    'storage_path',
  ];

}
