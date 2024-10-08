async function GetData(url){
    try {
      const response = await fetch(url);
      const data = await response.json();
      return data;
    } catch (error) {
      console.error(error);
    }
  }

function LoadTableData(value) {
    let content = "";
    GetData(document.getElementById("tableStatistic").value).then(data => {
        data.forEach((e)=>{
            content += `
        <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center mr-3">
                    <img src="https://flowbite.s3.amazonaws.com/blocks/application-ui/devices/xbox-series-x.png" alt="iMac Front Image" class="h-8 w-auto mr-3">
                    {{$promotion->id}}
                </div>
            </th>
            <td class="px-4 py-3">
                <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">Gaming/Console</span>
            </td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center">
                    <div class="h-4 w-4 rounded-full inline-block mr-2 bg-orange-500"></div>
                    {{$promotion->title}}
                </div>
            </td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$promotion->description}}</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{$promotion->totalCost}}</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">ABC, XYZ</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{$activeOn->startDate}} - {{$activeOn->endDate}}
            </td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center space-x-4">
                    <button onclick="DeleteDetails({{$promotion->id}})" type="button" data-modal-target="delete-modal-{{$promotion->id}}" data-modal-toggle="delete-modal-{{$promotion->id}}" class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Xóa
                    </button>
                </div>
            </td>
        </tr>`;
        })

    });
    const tableBody = `<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">Tên sản phẩm</th>
                            <th scope="col" class="p-4">Giá bán</th>
                            <th scope="col" class="p-4">Số lượng còn</th>
                            <th scope="col" class="p-4">Số lượng đã bán</th>
                            <th scope="col" class="p-4">Danh sách hóa đơn</th>
                            <th scope="col" class="p-4">Doanh thu</th>
                        </tr>
                    </thead><tbody>${content}</tbody>`;



}