<div class="relative inline-block text-left">
    <!-- Dropdown menu -->
    <div role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <select id="{{$componentId}}" onchange="{{$changeFunction}}" title="Chọn tháng" class="border border-blue-500 rounded-md p-2 hover:border-blue-700 focus:ring focus:ring-blue-300 focus:outline-none w-52">
            <option disabled selected value="0">Tháng này</option>
            @foreach ($optionCollection as $key => $value)
                <option href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" value="{{$key}}">{{$value}}</a>
            @endforeach
        </select>
    </div>
</div>
