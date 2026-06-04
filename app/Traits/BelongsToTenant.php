<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void addGlobalScope(string $identifier, \Closure $scope)
 * @method static void creating(\Closure $callback)
 */
trait BelongsToTenant
{
    /**
     * Boot the trait to apply global tenant scope and auto-fill tenant_id.
     */
    protected static function bootBelongsToTenant(): void
    {
        // ১. ডাটা রিট্রিভ করার সময় অটোমেটিক tenant_id ফিল্টার হবে (Global Scope)
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $builder->where('tenant_id', Auth::user()->tenant_id);
            }
        });

        // ২. ডাটা ইনসার্ট করার সময় অটোমেটিক কারেন্ট ইউজারের tenant_id বসে যাবে
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}