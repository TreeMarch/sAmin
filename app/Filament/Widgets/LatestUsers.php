<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;


class LatestUsers extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return User::query()->latest()->limit(5); // Lấy 5 người dùng mới nhất
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('email'),
            BadgeColumn::make('name')->badge('primary'),
        ];
    }
}
