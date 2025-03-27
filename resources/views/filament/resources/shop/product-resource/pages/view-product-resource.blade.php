<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('Thông tin sản phẩm') }}
        </x-slot>
        <x-filament::grid :default="1" :sm="2" :md="3" :lg="4">
            <x-filament::card>
                <p><strong>Tên:</strong> {{ $record->name }}</p>
                <p><strong>Giá:</strong> {{ number_format($record->price) }} VND</p>
                <p><strong>Mô tả:</strong> {{ $record->description }}</p>
                <p><strong>Trạng thái:</strong> {{ $record->status }}</p>
                <p><strong>Danh mục:</strong> {{ $record->category->name }}</p>
                <p><strong>Người dùng:</strong> {{ $record->user->name }}</p>
                <p><strong>Hoạt động:</strong> {{ $record->isActive ? 'Có' : 'Không' }}</p>

                @if($record->image)
                    <img src="{{ asset('storage/' . $record->image) }}" alt="{{ $record->name }}" style="max-width: 200px; margin-top: 10px;">
                @endif
            </x-filament::card>
        </x-filament::grid>
    </x-filament::section>


    <x-filament::section>
        <x-slot name="heading">
            {{ __('Sản phẩm khác của người dùng này') }}
        </x-slot>
        <x-filament::grid :default="1" :sm="2" :md="3" :lg="4">
            @forelse($relatedProducts as $product)
                <x-filament::card>
                    <a href="{{ route('filament.admin.resources.products.view', $product) }}">
                        <p><strong>{{ $product->name }}</strong></p>
                        <p>Giá: {{ number_format($product->price) }} VND</p>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 100px; margin-top: 10px;">
                        @endif
                    </a>
                </x-filament::card>
            @empty
                <x-filament::card>
                    <p>Không có sản phẩm liên quan.</p>
                </x-filament::card>
            @endforelse
        </x-filament::grid>
    </x-filament::section>
</x-filament-panels::page>
