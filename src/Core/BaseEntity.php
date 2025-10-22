<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class BaseEntity extends Model
{
    use HasFactory, SoftDeletes;

    public static function getCacheModelName(): string
    {
        return Str::of(class_basename(new static))
            ->snake()
            ->plural()
            ->lower();
    }
}
