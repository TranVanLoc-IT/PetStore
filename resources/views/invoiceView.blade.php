@extends("layouts.app")
@section("title", "Hóa đơn")
@section('content')
@php
const INV_FUNC = "ReloadData()";
const INV_OPT = "invoiceOptions";
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
@endphp
<div class="container p-2">
    <div class="flex w-full">
        <div class="flex-1">
            <div class="font-bold mb-2">
                Số lượng hóa đơn:
                <span id="totalInvoice" class="ring-2 ring-offset-2 text-yellow-400 ring-offset-blue-300 hover:ring-offset-blue-500 pt-1"> {{sizeof($invoices)}}
                </span>
            </div>
            <div class="font-bold mt-2">
                Tổng lợi nhuận: 
                <span id="totalRevenue" class="ring-2 ring-offset-2 text-green-400 ring-offset-blue-300 hover:ring-offset-blue-500 pt-1">
                    {{array_sum(array_column($invoices, 'totalCost'))}}
                </span>
            </div>  
        </div>
        <div class="flex-1 w-full">
            <div class="flex-1 font-bold mb-2 float-start">
                Chọn thời gian:
            </div>
            <div class="flex-1 float-end">
                <x-dropdown-button :optionCollection="$months" :componentId="INV_OPT" :changeFunction="INV_FUNC" />
                <button type="button" onclick="DeleteAll()"  class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">xóa hết</button>
            </div>
        </div>
    </div>
    <div id="invoice-view-block" class="relative flex min-h-screen flex-row flex-wrap justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
        <?php
            foreach($invoices as $invoice){
                ?>
                    <div
                    class="group max-h-sm relative cursor-pointer overflow-hidden bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl sm:mx-auto sm:max-w-sm sm:rounded-lg sm:px-10">
                    <span class="absolute top-10 z-0 h-20 w-20 rounded-full bg-sky-500 transition-all duration-300 group-hover:scale-[10]"></span>
                    <div class="relative z-10 mx-auto max-w-md">
                        <span class="grid h-20 w-20 place-items-center rounded-full bg-sky-500 transition-all duration-300 group-hover:bg-sky-400">
                           {{$invoice->invoiceId}}
                        </span>
                        <div
                            class="space-y-6 pt-5 text-base leading-7 text-gray-600 transition-all duration-300 group-hover:text-white/90">
                            <ol type="1">
                                <li>Số lượng sản phẩm: {{$invoice->totalAmount}}</li>
                                <li>Tổng tiền: {{$invoice->totalCost}}</li>
                                <li>Ngày tạo: {{$invoice->dateCreated}}</li>
                            </ol>
                        </div>
                        <div class="pt-5 text-base font-semibold leading-7">
                            <p>
                                <button onclick="GetViewDetail('{{$invoice->invoiceId}}')" type="button" id="viewProductButton" data-modal-toggle="viewInvoiceModel" class="text-sky-500 transition-all duration-300 group-hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2 -ml-0.5">
                                        <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                    </svg>
                                    Xem chi tiết
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
    </div>
</div>

<div id="viewInvoiceModel" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center overflow-y-auto overflow-x-hidden">
    <div class="relative p-4 w-full max-w-3xl h-auto max-h-[90vh] overflow-y-auto">
        <!-- Nội dung -->
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow  sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Chi tiết hóa đơn</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="viewInvoiceModel">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Đóng</span>
                </button>
            </div>
            <!-- Modal body -->
            <div id="view-detail-invoice">

            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/invoiceScript.js')}}"></script>

@endsection