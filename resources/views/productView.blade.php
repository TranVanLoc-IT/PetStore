@extends("layouts.app")
@section("title", "Sản phẩm")
@php
    const SP_RE_OPT = "portfolioOptions";
    const SP_RE_FUNC = "LoadTableData()";
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
@section('content')<!-- Start block -->
<section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div
                class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t dark:border-gray-700">
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <div class="relative inline-block text-left">
                        <select id="tableOption"
                            class="border p-2 border-blue-500 rounded-md hover:border-blue-700 focus:ring focus:ring-blue-300 focus:outline-none w-64"
                            onchange="LoadTableData()">
                            <option value="/san-pham">Sản phẩm</option>
                            <option value="/nhan-vien">Nhân viên</option>
                        </select>
                        <x-dropdown-button :optionCollection="$months" :componentId="SP_RE_OPT"
                            :changeFunction="SP_RE_FUNC" />
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="tableView" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                </table>
            </div>
        </div>
    </div>

</section>
<script src="{{ asset('js/portfolioChartConfig.js') }}"></script>
@endsection
