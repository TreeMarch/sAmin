<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Filament\Tables\Filters\{Filter, SelectFilter, Layout};
use Filament\Forms\Components\DatePicker;
use App\Filament\Widgets\StatOrder;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Count;
use Illuminate\Database\Query\Builder;




class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Quản lý đơn hàng';

    public static function getNavigationBadge(): ?string
    {
        return Order::query()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getNavigationBadge() > 5 ? 'success' : 'primary';
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->label('Khách hàng')
                ->required(),

            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Sản phẩm')
                ->required(),

            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('total_price')
                ->numeric()
                ->disabled()
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'shipped' => 'Shipped',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])
                ->default('pending'),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Ngày đặt hàng')->dateTime(),
                Tables\Columns\TextColumn::make('product.name')->label('Sản phẩm'),
                Tables\Columns\TextColumn::make('quantity')->label('Số lượng'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('VND')
                    ->label('Tổng tiền')
                    ->summarize([
                        Sum::make()->label('Tổng tiền (VND)'),
                    ]),
                Tables\Columns\TextColumn::make('user.name')->label('Khách hàng'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
                    ->label('Trạng thái')
                    ->summarize(
                        Count::make()->query(fn(Builder $query) => $query->where('status', 'completed'))->label('Đã hoàn thành'),
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'shipped' => 'Shipped',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (QueryBuilder $query, array $data): QueryBuilder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(QueryBuilder $query, $date): QueryBuilder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(QueryBuilder $query, $date): QueryBuilder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getHeaderWidgets(): array
    {
        return [
            StatOrder::class,
        ];
    }
}
