<div class="relative inline-block text-left">
    <!-- Dropdown menu -->
    <div role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <select id="{{$componentId}}" onchange="{{$changeFunction}}" title="Chọn tháng" class="p-1 text-gray-400 ring-1 ring-pink-200 hover:ring-offset-2:ring-pink-500">
            <option disabled selected value="Filter">Lọc</option>
            @foreach ($optionCollection as $key => $value)
                <option href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" value="{{$key}}">{{$value}}</a>
            @endforeach
        </select>
    </div>
</div>
