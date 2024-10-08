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
    public function GetStaffTopKPIList($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        return $this->neo4j->run($this->queryDatasource["Dashboard"]["GetStaffTopKPIList"], ['date' => $dateCriteria]);
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
        $revenueData = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetRevenueByMonths"], ['date' => $dateCriteria]);
        $expenseData = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetExpenseByMonths"], ['date' => $dateCriteria]);

        foreach( $revenueData as $r ){
            array_push($revenueLabels, $r['date']);
            $expense[$r['date']] =  $r['totalValue'];
        }

        foreach( $expenseData as $e ){
            array_push($expenseLabels, $e['date']);
            $expense[$e['date']] =  $e['totalValue'];
        }

        $labels = array_unique(array_merge($revenueLabels, $expenseLabels));

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
        $datas = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetPPRevenueByMonths"], ['date' => $dateCriteria]);
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
    public function GetStore(){
        $store = [["totalExpense" => 0, "totalRevenue" => 0]];
        $neoResult = $this->neo4j->run($this->queryDatasource["Dashboard"]["GetStore"]);
        if ($neoResult->count() > 0) {
            // Lấy bản ghi đầu tiên
            $record = $neoResult->first();
            
            // Chuyển đổi node 's' thành mảng và tạo đối tượng Store: record->get('s')->getProperties()->toArray();
            $store = new Store(["totalExpense" => $record["expense"], "totalRevenue" => $record["revenue"]]);
        }
        return $store;
    }

    /**
     * Tổng thú cưng
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
        $staffTopKPIList = [];
        foreach($this->GetStaffTopKPIList($request) as $staff){
            $staffTopKPIList[$staff["staffName"]] = $staff["totalValue"];
        }

        $store = $this->GetStore();
        $petQuantity = $this->GetTotalPets()[0]->toArray();
        
        return view('dashboard',compact("store", "petQuantity", "staffTopKPIList"));
    }
    
    
}
