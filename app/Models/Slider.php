<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slider extends Model
{
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'title',
        'type',
        'image',
        'video',
        'link',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = ['image_url', 'video_url'];
   
  public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-slider.jpg');
    }

    public function getVideoUrlAttribute()
    {
        return $this->video
            ? asset('storage/' . $this->video)
            : null;
    }
    public function scopeForBranch($query, $branchId = null)
{
    return $query->where('status', 1)
        ->where(function ($q) use ($branchId) {
            $q->whereNull('branch_id');

            if ($branchId) {
                $q->orWhere('branch_id', $branchId);
            }
        })
        ->orderBy('sort_order')
        ->orderBy('id');
}

    protected function mediaUrl(?string $path): ?string
    {
        if (! $path) return null;
        if (str_starts_with($path, 'http')) return $path;

        // الملفات القديمة مثل images/cacke2.mp4 قد تكون في public مباشرة
        return file_exists(public_path($path))
            ? asset($path)
            : asset('storage/' . $path);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

  
}