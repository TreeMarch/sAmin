<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ProductStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;


class Product extends Model
{
    use HasFactory;

    // Để xác định các thuộc tính có thể được gán hàng loạt
    protected $fillable = [
        'user_id',
        'name',
        'image',
        'description',
        'price',
        'discount', // Thêm trường discount
        'isActive', // Thêm trường isActive
        'status',
        'category_id'
    ];

    // Mối quan hệ với model User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected $casts = [
        'status' => ProductStatus::class,
        'isActive' => 'boolean', // Chuyển đổi trường isActive thành boolean
        'image' => 'json'
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }
    


    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            $images = Arr::wrap($product->image); // Đảm bảo luôn là mảng
            Storage::delete(array_map(fn($image) => "public/$image", $images));
        });

        static::updating(function (Product $product) {
            $oldImages = Arr::wrap($product->getOriginal('image'));
            $newImages = Arr::wrap($product->image);

            $imagesToDelete = array_diff($oldImages, $newImages);

            Storage::delete(array_map(fn($image) => "public/$image", $imagesToDelete));
        });
    }
}
