@extends("layouts.app")
@section("title", "Hóa đơn")
@section('content')
@php
const INV_FUNC = "";
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
                Tổng hóa đơn:
                <span class="ring-2 ring-offset-2 text-yellow-400 ring-offset-blue-300 hover:ring-offset-blue-500 pt-1"> <?php echo sizeof($invoices);?>
                </span>
            </div>
            <div class="font-bold mt-2">
                Tổng lợi nhuận: 
                <span class="ring-2 ring-offset-2 text-green-400 ring-offset-blue-300 hover:ring-offset-blue-500 pt-1"> <?php echo array_reduce($invoices, function($carry, $invoice) {
                    return $carry + $invoice['totalCost'];
                }, 0);?>
                </span>
            </div>  
        </div>
        <div class="flex-1 w-full">
            <div class="flex-1 font-bold mb-2 float-start">
                Chọn thời gian:
            </div>
            <div class="flex-1 float-end">
                <x-dropdown-button :optionCollection="$months" :componentId="INV_OPT" :changeFunction="INV_FUNC" />
            </div>
        </div>
    </div>
    <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
        <?php
            foreach($invoices as $invoice){
                ?>
                    <div
                    class="group relative cursor-pointer overflow-hidden bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl sm:mx-auto sm:max-w-sm sm:rounded-lg sm:px-10">
                    <span class="absolute top-10 z-0 h-20 w-20 rounded-full bg-sky-500 transition-all duration-300 group-hover:scale-[10]"></span>
                    <div class="relative z-10 mx-auto max-w-md">
                        <span class="grid h-20 w-20 place-items-center rounded-full bg-sky-500 transition-all duration-300 group-hover:bg-sky-400">
                           Hóa đơn thanh toán
                        </span>
                        <div
                            class="space-y-6 pt-5 text-base leading-7 text-gray-600 transition-all duration-300 group-hover:text-white/90">
                            <ol type="1">
                                <li>Số lượng sản phẩm: <?php echo $invoice->totalAmount;?></li>
                                <li>Loại sản phẩm: <?php echo $invoice->productType;?></li>
                                <li>Tổng tiền: <?php echo $invoice->totalCost;?></li>
                            </ol>
                        </div>
                        <div class="pt-5 text-base font-semibold leading-7">
                            <p>
                                <a href="#" class="text-sky-500 transition-all duration-300 group-hover:text-white">Xem chi tiết
                                    &rarr;
                                </a>
                                <span class='text-gray-300 text-right'><?php echo $invoice->totalCost;?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        
    </div>
</div>
@endsection