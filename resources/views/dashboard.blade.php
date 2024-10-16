@extends("layouts.app")
@section("title", "Tổng quan")
@section('content')
@php
    const STORE_EX_OPT = "storeExpenseOptions";
    const PET_RE_OPT = "petRevenueOptions";

    const STORE_EX_FUNC = "CallGetExpenseData()";
    const PET_RE_FUNC = "CallGetRevenueData()";

    const STORE_TT_DA_OPT = "storeDataOptions";
    const STORE_TT_RE_FUNC = "LoadTotalReAndExData()";
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
        $totalExpense = number_format(floor($store->totalExpense));
        $totalRevenue = number_format(floor($store->totalRevenue));
        $profit = floor($store->totalRevenue - $store->totalExpense);
@endphp
<main class="p-6 sm:p-10 space-y-6">
    <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row justify-between">
        <div class="mr-6">
            <h1 class="text-4xl font-semibold mb-2">Tổng quan</h1>
            <h2 class="text-gray-600 ml-0.5">Phần mềm quản lý doanh thu PetStore</h2>
        </div>
        <div class="flex flex-wrap items-start justify-end -mb-3">
                <div class="flex items-center flex-col p-8 bg-white shadow rounded-lg">
                    <div
                        class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6">
                        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="block text-2xl font-bold totalRevenue">{{number_format($storeProfit->totalExpense)}} {{($storeProfit->totalExpense > 1000000000 ? "Tỉ": $storeProfit->totalExpense > 1000000) ? "Triệu": "Nghìn"}} VND</span>
                        <span class="block text-gray-500">Đã chi tiêu</span>
                    </div>
                    <div class="text-left">
                        <span class="block text-2xl font-bold totalRevenue">{{number_format($storeProfit->totalRevenue)}} {{($storeProfit->totalRevenue > 1000000000 ? "Tỉ": $storeProfit->totalRevenue > 1000000) ? "Triệu": "Nghìn"}} VND</span>
                        <span class="block text-gray-500">Ngân sách</span>
                    </div>
                </div>
        </div>
    </div>
    <section class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-dropdown-button :optionCollection="$months" :componentId="STORE_TT_DA_OPT"
        :changeFunction="STORE_TT_RE_FUNC" />
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
                class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6">
                <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <span class="block text-2xl font-bold totalRevenue">{{ $totalRevenue }} {{($store->totalRevenue > 1000000000 ? "Tỉ": $store->totalRevenue > 1000000) ? "Triệu": "Nghìn"}} VND</span>
                <span class="block text-gray-500">Tổng doanh thu</span>
            </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
                class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-red-600 bg-red-100 rounded-full mr-6">
                <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                </svg>
            </div>
            <div>
                <span
                    class="inline-block text-xl text-gray-500 font-semibold totalExpense">{{ $totalExpense }} {{($store->totalExpense > 1000000000 ? "Tỉ": $store->totalExpense > 1000000) ? "Triệu": "Nghìn"}} VND</span>
                <span class="block text-gray-500">Tổng chi</span>
            </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
                class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-blue-600 bg-blue-100 rounded-full mr-6">
                <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <span
                    class="block text-2xl font-bold totalProfit">{{ number_format($profit) }} {{($profit > 1000000000 ? "Tỉ": $profit > 1000000) ? "Triệu": "Nghìn"}} VND</span>
                <span class="block text-gray-500">Tổng lợi nhuận</span>
            </div>
        </div>
    </section>
    <section class="grid md:grid-cols-2 xl:grid-cols-4 xl:grid-rows-3 xl:grid-flow-col gap-6">
        <div class="flex flex-col md:col-span-2 md:row-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-5 font-semibold border-b border-gray-100">Doanh thu hàng tháng</div>
            <x-dropdown-button :optionCollection="$months" :componentId="STORE_EX_OPT"
                :changeFunction="STORE_EX_FUNC" />
            <canvas id="storeExpenseChart" class="p-4 flex-grow">
                <div
                    class="flex items-center justify-center h-full px-4 py-16 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md">
                    Chart</div>
            </canvas>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
                class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-yellow-600 bg-yellow-100 rounded-full mr-6">
                <i class="fa-brands fa-sellcast bg-lime-500 text-lg"></i>
            </div>
            <div>
                <span class="block text-2xl font-bold"><?php echo strval($petQuantity["sold"]);?></span>
                <span class="block text-gray-500">Đã bán</span>
            </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
                class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-teal-600 bg-teal-100 rounded-full mr-6">
                <i class="fa-solid fa-warehouse bg-amber-300 text-lg"></i>
            </div>
            <div>
                <span class="block text-2xl font-bold"><?php echo strval($petQuantity["available"]);?></span>
                <span class="block text-gray-500">Còn</span>
            </div>
        </div>
        <div class="row-span-3 bg-white shadow rounded-lg">
            <div class="flex items-center justify-between px-6 py-5 font-semibold border-b border-gray-100">
                <span>Bảng xếp hạng doanh số</span>
            </div>
            <div class="overflow-y-auto" style="max-height: 24rem;">
                <ul class="p-6 space-y-6">
                    <?php
              foreach($petTopKPIList as $pet => $kpi)
              {
                list($petName, $img) = explode('-', $pet);
                echo '<li class="flex items-center">
                    <div class="h-10 w-10 mr-3 bg-gray-100 rounded-full overflow-hidden">
                      <img src="../img/'.$img.'" alt="">
                    </div>
                    <span class="text-gray-600">'.$petName.'</span>
                    <span class="ml-auto font-semibold">'.$kpi.'Đ</span>
                  </li>';
              }
              ?>
                </ul>
            </div>
        </div>
        <div class="flex flex-col row-span-3 bg-white shadow rounded-lg">

            <div class="px-6 py-5 font-semibold border-b border-gray-100">Doanh số bán hàng</div>
            <x-dropdown-button :optionCollection="$months" :componentId="PET_RE_OPT" :changeFunction="PET_RE_FUNC" />
            <div class="p-4 flex-grow overflow-x-scroll w-100">
                <canvas id="petRevenueChart">

                </canvas>
            </div>
        </div>
    </section>
</main>
<script src=" {{ asset('js/petExpenseChartConfig.js') }} ">

</script>
<script src=" {{ asset('js/storeExpenseChartConfig.js') }} ">

</script>
@endsection
