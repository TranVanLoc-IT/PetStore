@extends("layouts.app")
@section("title", "Xuất file thống kê")
@section('content')
<div>
    @php
      const STORE_EX_OPT = "storeExpenseOptions";
      const STORE_EX_FUNC = "CallGetExpenseData()";
      $months =[];
      foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $m)
      {
        if($m < 10 & $m > 0)
        {
          $months['0'.$m] = "Tháng ".$m;
        }
        else{
          $months[$m] = "Tháng ".$m;
        }
      }
      $dataType = [1 => "Pet", 2 => "Cửa hàng"]
    @endphp
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
    <div>Chọn thời gian: 
        <x-dropdown-button :optionCollection="$months" :componentId="STORE_EX_OPT" :changeFunction="STORE_EX_FUNC"/>
    </div>
    <div>Chọn dữ liệu: 
        <x-dropdown-button :optionCollection="$dataType" :componentId="STORE_EX_OPT" :changeFunction="STORE_EX_FUNC"/>
    </div>
    <button class="btn bg-green-400 hover:bg-green-500 p-4 rounded text-white fw-bold mx-auto container w-50">Xuất file</button>
</div>
@endsection