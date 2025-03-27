<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Models\Product;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()->badge(fn () => Product::count()),
            'Published' => Tab::make()
                ->badge(fn () => Product::where('status', 'published')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'published')),
            'Draft' => Tab::make()
                ->badge(fn () => Product::where('status', 'draft')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'draft')),
            'Archived' => Tab::make()
                ->badge(fn () => Product::where('status', 'archived')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'archived')),
        ];

    }
}
