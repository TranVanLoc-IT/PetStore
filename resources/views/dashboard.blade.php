@extends("layouts.app")
@section("title", "Tổng quan")
@section('content')
    @php
      const STORE_EX_OPT = "storeExpenseOptions";
      const PET_RE_OPT = "petRevenueOptions";
      const STORE_EX_FUNC = "CallGetExpenseData()";
      const PET_RE_FUNC = "CallGetRevenueData()";
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
    <main class="p-6 sm:p-10 space-y-6">
      <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row justify-between">
        <div class="mr-6">
          <h1 class="text-4xl font-semibold mb-2">Tổng quan</h1>
          <h2 class="text-gray-600 ml-0.5">Phần mềm quản lý doanh thu PetStore</h2>
        </div>
        <div class="flex flex-wrap items-start justify-end -mb-3">
          <button class="inline-flex px-5 py-3 text-purple-600 hover:text-purple-700 focus:text-purple-700 hover:bg-purple-100 focus:bg-purple-100 border border-purple-600 rounded-md mb-3">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="flex-shrink-0 h-5 w-5 -ml-1 mt-0.5 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Quản lí biểu đồ
          </button>
          <button class="inline-flex px-5 py-3 text-white bg-purple-600 hover:bg-purple-700 focus:bg-purple-700 rounded-md ml-6 mb-3">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="flex-shrink-0 h-6 w-6 text-white -ml-1 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tạo mới biểu đồ
          </button>
        </div>
      </div>
      <section class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
        
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
          <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
          <div>
            <span class="block text-2xl font-bold"><?php echo $store->totalRevenue;?></span>
            <span class="block text-gray-500">Tổng doanh thu</span>
          </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
          <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-red-600 bg-red-100 rounded-full mr-6">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
            </svg>
          </div>
          <div>
            <span class="inline-block text-2xl font-bold">9</span>
            <span class="inline-block text-xl text-gray-500 font-semibold">(14%)</span>
            <span class="block text-gray-500">Tiến độ công việc</span>
          </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
          <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-blue-600 bg-blue-100 rounded-full mr-6">
            <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <span class="block text-2xl font-bold">83%</span>
            <span class="block text-gray-500">Công việc đã hoàn thành</span>
          </div>
        </div>
      </section>
      <section class="grid md:grid-cols-2 xl:grid-cols-4 xl:grid-rows-3 xl:grid-flow-col gap-6">
        <div class="flex flex-col md:col-span-2 md:row-span-2 bg-white shadow rounded-lg">
          <div class="px-6 py-5 font-semibold border-b border-gray-100">Doanh thu hàng tháng</div>
          <x-dropdown-button :optionCollection="$months" :componentId="STORE_EX_OPT" :changeFunction="STORE_EX_FUNC"/>
          <canvas id="storeExpenseChart" class="p-4 flex-grow">
            <div class="flex items-center justify-center h-full px-4 py-16 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md">Chart</div>
          </canvas>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
          <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-yellow-600 bg-yellow-100 rounded-full mr-6">
            <i class="fa-brands fa-sellcast bg-lime-500 text-lg"></i>
          </div>
          <div>
            <span class="block text-2xl font-bold"><?php echo strval($petQuantity["sold"]);?></span>
            <span class="block text-gray-500">Đã bán</span>
          </div>
        </div>
        <div class="flex items-center p-8 bg-white shadow rounded-lg">
          <div class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-teal-600 bg-teal-100 rounded-full mr-6">
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
            <button type="button" class="inline-flex justify-center rounded-md px-1 -mr-1 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-600" id="options-menu" aria-haspopup="true" aria-expanded="true">
              Tăng dần
              <svg class="-mr-1 ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <!-- Refer here for full dropdown menu code: https://tailwindui.com/components/application-ui/elements/dropdowns -->
          </div>
          <div class="overflow-y-auto" style="max-height: 24rem;">
            <ul class="p-6 space-y-6">
              <?php
              foreach($staffTopKPIList as $staff => $kpi)
              {
                
                echo '<li class="flex items-center">
                    <div class="h-10 w-10 mr-3 bg-gray-100 rounded-full overflow-hidden">
                      <img src="https://randomuser.me/api/portraits/women/82.jpg" alt="Annette Watson profile picture">
                    </div>
                    <span class="text-gray-600">'.$staff.'</span>
                    <span class="ml-auto font-semibold">'.$kpi.'</span>
                  </li>';
              }
              ?>
            </ul>
          </div>
        </div>
        <div class="flex flex-col row-span-3 bg-white shadow rounded-lg">
          
          <div class="px-6 py-5 font-semibold border-b border-gray-100">Doanh số bán hàng</div>
          <x-dropdown-button :optionCollection="$months" :componentId="PET_RE_OPT" :changeFunction="PET_RE_FUNC"/>
          <div class="p-4 flex-grow overflow-x-scroll w-100">
            <canvas id="petRevenueChart">

            </canvas>
          </div>
        </div>
      </section>
    </main>
    <script src=" {{asset('js/petExpenseChartConfig.js')}} ">
            
    </script>
     <script src=" {{asset('js/storeExpenseChartConfig.js')}} ">
            
     </script>
    @endsection