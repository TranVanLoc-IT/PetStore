@extends("layouts.app")
@section("title", "Xuất file thống kê")
@section('content')
<div>
    @php
        const TABLE_OPT = "tableOptions";
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
            $dataType = ['/pet' => "Sản phẩm", '/store' => "Cửa hàng"]
    @endphp
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
    <div class="my-3">Chọn thời gian:
        <x-dropdown-button :optionCollection="$months" :componentId="TIME_OPT" :changeFunction="EX_FUNC" />
    </div>
    <div>Chọn dữ liệu:
        <x-dropdown-button :optionCollection="$dataType" :componentId="TABLE_OPT" :changeFunction="EX_FUNC" />
    </div>
    <button id="submit-export"
        class="btn bg-green-400 hover:bg-green-500 p-4 rounded text-white fw-bold mx-auto container w-50">Xuất
        file</button>
</div>
<script>
    $(document).ready(function () {
        $('#submit-export').click(function () {
            if ($('#timeOptions').val() == null && $('#tableOptions').val() == null) return;
            window.location.href = window.location.href + $('#tableOptions').val() + '/' + $(
                '#timeOptions').val();
        })
    });

</script>
@endsection
