<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\Widgets\CustomerStats;
use Filament\Forms\Components\TextInput;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-m-user';
    protected static ?string $label = 'Thông tin khách hàng';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('db.name'))
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->required()
                    ->maxLength(11)
                    ->label(__('db.phone'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->badge(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),

                Tables\Columns\TextColumn::make('total_products')
                    ->label('Total products purchased')
                    ->getStateUsing(function ($record) {
                        // Kiểm tra nếu orders tồn tại, nếu không thì trả về 0
                        if (!$record->orders) {
                            return 0;
                        }
                        return $record->orders->sum(fn($order) => $order->products?->sum('quantity') ?? 0);
                    }),

                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total amount spent')
                    ->getStateUsing(function ($record) {
                        return number_format($record->orders?->sum('total_price') ?? 0, 0, ',', '.') . ' VNĐ';
                    }),

                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getWidgets(): array
    {
        return [
            CustomerStats::class,
        ];
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
