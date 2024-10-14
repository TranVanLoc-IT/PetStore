<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use repository\cypher\CypherQueryBuilder;
use App\Models\LPetTool;
use App\Models\Store;
class DashboardController extends Controller
{

    public function __construct(){
        parent::__construct();
    }
    /**
     * Lấy danh sách nhân viên hoạt động tích cực theo tháng
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function GetPetTopKPIList($month){
        $year = date("Y");
        $month = $month == null ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        $data = [];
        foreach($this->neo4j->run($this->queryDatasource["Dashboard"]["GetPetTopKPIList"], ['date' => $dateCriteria]) as $record)
        {
            $data[$record->get('petName').'-'.$record->get('img')] = $record->get('totalValue');
        }
        return $data;
    }
    /**
     * Lấy doanh thu cửa hàng
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetStoreExpense($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        $revenueLabels = [];
        $expenseLabels = [];
        $revenue = [];
        $expense = [];
        $revenueData = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetRevenueByMonth"], ['date' => $dateCriteria])->first();
        $expenseData = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetExpenseByMonth"], ['date' => $dateCriteria])->first();

        // gán data: $data['date'=>'value']
        for($count = 0; $count < sizeof($revenueData->get("date")->toArray()); $count++){
            $revenue[$revenueData->get("date")->toArray()[$count]] =  $revenueData->get("totalValue")->toArray()[$count];
        }

        for($count = 0; $count < sizeof($expenseData->get("date")->toArray()); $count++){
            $expense[$expenseData->get("date")->toArray()[$count]] =  $expenseData->get("totalValue")->toArray()[$count];
        }

        // gộp thành label chung
        $labels = array_unique(array_merge($revenueLabels, $expenseLabels));

        // xử lý data, date khong co => 0
        foreach($labels as $label)
        {
            if(!array_key_exists($label, $expense))
            {
                $expense[$label] = 0;
            }
            if(!array_key_exists($label, $revenue))
            {
                $revenue[$label] = 0;
            }
        }

        return response()->json(["revenue"=>$revenue, "expense"=>$expense, "labels" => $labels]);
    }

    /**
     * Lấy doanh thu thú cưng
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetPetRevenue($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';

        $petRevenue = [];
        $petToolRevenue = [];
        $petLabels = [];
        $petToolLabels = [];
        $datas = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetPPRevenueByMonth"], ['date' => $dateCriteria]);
        foreach($datas as $data)
        {
            if($data->get("productType") == "Pet")
            {
                $petLabels = $data->get("invoiceDate")->toArray();
                $petRevenue = $data->get("revenue")->toArray();
            }
            else{
                $petToolLabels = $data->get("invoiceDate")->toArray();;
                $petToolRevenue = $data->get("revenue")->toArray();;
            }
        }

        $labels = [];
        $labels = array_unique(array_merge($petLabels, $petToolLabels));

        foreach($labels as $label)
        {
            if(!array_key_exists($label, $petLabels))
            {
                $petRevenue[$label] = 0;
            }
            if(!array_key_exists($label, $petToolLabels))
            {
                $petToolLabels[$label] = 0;
            }
        }

        return response()->json(["PetRevenue" => $petRevenue, "PetToolRevenue" => $petToolRevenue, "labels" => $labels]);
    }
    /**
     * Lấy tổng hợp doanh thu cửa hàng
     * @return Store|null
     */
    public function GetStore($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';

        $store = [["totalExpense" => 0, "totalRevenue" => 0]];
        $neoResult = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetStore"], ["date" => $dateCriteria]);
        if ($neoResult->count() > 0) {
            // Lấy bản ghi đầu tiên
            $record = $neoResult->first();
            
            // Chuyển đổi node 's' thành mảng và tạo đối tượng Store: record->get('s')->getProperties()->toArray();
            $store = new Store(["totalExpense" => $record["expense"], "totalRevenue" => $record["revenue"]]);
        }
        return $store;
    }

    /**
     * Lấy tổng doanh thu cửa hàng theo tháng
     * @param mixed $month
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetTotalReAndExData($month){
        $data = $this->GetStore($month);
        return response()->json(["data" => $data], 200);
    }

    /**
     * Tổng thú cưng: SL bán, còn
     * @return mixed
     */
    public function GetTotalPets(){
        return $this->neo4j->run($this->queryDatasource["Dashboard"]["GetTotalPets"]);
    }

    /**
     * Lấy dữ liệu dashboard
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetDashboard(Request $request){
        // doanh thu cua hang
        $store = [];
        $petQuantity = [];
        $petTopKPIList = $this->GetPetTopKPIList(null);
        $store = $this->GetStore('0');
        $petQuantity = $this->GetTotalPets()[0]->toArray();
        
        return view('dashboard',compact("store", "petQuantity", "petTopKPIList"));
    }
    
    
}
