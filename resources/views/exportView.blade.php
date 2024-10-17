@extends("layouts.app")
@section("title", "Xuất file thống kê")
@section('content')
<div>
    @php
        const TIME_OPT = "timeOptions";
        const EX_FUNC = "";
        $months =[];
        foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $m)
        {
        if($m < 10 & $m> 0)
            {
            $months['0'.$m] = "Tháng ".$m;
            }
            else{
            $months[$m] = "Tháng ".$m;
            }
            }
    @endphp
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
    <div class="my-3">Chọn thời gian:
        <x-dropdown-button :optionCollection="$months" :componentId="TIME_OPT" :changeFunction="EX_FUNC" />
    </div>
    <div>Chọn dữ liệu:
        <div class="relative inline-block text-left">
            <!-- Dropdown menu -->
            <div role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <select id="tableOptions" title="Chọn bảng dữ liệu" class="border border-blue-500 rounded-md p-2 hover:border-blue-700 focus:ring focus:ring-blue-300 focus:outline-none w-52">
                    <option disabled selected value="0">Chọn</option>
                        <option class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" value="/pet">Sản phẩm</a>
                        <option class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" value="/store">Cửa hàng</a>
                </select>
            </div>
        </div>
    </div>
    <button id="submit-export"
        class="btn bg-green-400 hover:bg-green-500 p-4 rounded text-white fw-bold mx-auto container w-50">Xuất
        file</button>
</div>
<script>
    $(document).ready(function () {
        $('#submit-export').click(function () {
            if ($('#tableOptions').val() == null) return;
            window.location.href = window.location.href + $('#tableOptions').val() + '/' + $(
                '#timeOptions').val();
        })
    });

</script>
@endsection
