<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\PetTool;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\RChargeOf; // role
use App\Models\RPaySalary;// removed
use App\Models\Staff;
use Carbon\Carbon;
use DateTime;
use Log;
use Str;

class PorfolioController extends Controller
{
    // co loc theo thoi gian
    /**
     * Xoa du lieu, thong ke doanh thu theo thang. 
     * Danh sanh cac thu cung, gia ban, so luong ban, so luong ton, doanh thu. Tong doanh thu, tong chi. Danh sach hoa don
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetProductData($month){
        // Thiết lập điều kiện tháng năm nếu tháng không chọn thì mặc định là tháng hiện tại
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        // san pham + hoa don di kem
        $products = [];
        $invoices = [];
        $totalRevenue= [];
        $totalSold = [];
        $data = $this->neo4j->run($this->queryDatasource["Product"]["GetProducts"],['date' => $dateCriteria]);
        foreach($data as $d)
        {
            $products[] = $d->get("p")->getProperties()->toArray();
            $invoices[] = $d->get("invoiceList");
            $totalSold[] = $d->get("totalQuantitySold");
            $totalRevenue[] = $d->get("totalRevenue");
        }
        
        return response()->json(["products" => $products, "totalRevenue" => $totalRevenue, "totalSold" => $totalSold, "invoice" => $invoices], 200);
    }
    /**
     * Cập nhật giá bán
     * @return void
     */
    public function UpdateProductPrice(){
        // Lấy dữ liệu submit
        $data = json_decode(file_get_contents('php://input'), true);
        $query = $this->queryDatasource["Product"]["UpdateProductPrice"];

        // Truyền tham số dữ liệu cho chuỗi query
        $this->neo4j->run($query, ["id" => $data["productId"], "newPrice" => $data["newPrice"]]);

        return response()->json(["Inform" => "Sửa giá thành công"], 200);
    }
    /**
     * Nhan vien , thong tin gio lam, vi tri, ket qua lam viec(so hoa don). Xep hang nhan vien, luong chi tra, tong luong chi tra
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetStaffData($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';

        // st, roles, shiftworks, paid
        $staffs = [];
        $role = [];
        $paylary = [];
        $shiftWork = []; // chargeof
        $paid = [];
        $data = $this->neo4j->run($this->queryDatasource["Staff"]["GetStaffsWorking"], ['date'=> $dateCriteria]);
        foreach($data as $d)
        {
            $staff = new Staff($d->get("st")->getProperties()->toArray());
            $staffs[] = $staff;
            $paid[] = $d->get("paid");
            $shiftWork[] = $d->get("shiftworks")->toArray();
            $role[] = $d->get("role");
            $salary[] = $d->get("salary");
        }
        return response()->json(["staffs" => $staffs, "role"=>$role, "shiftWorks" => $shiftWork, "paid" => $paid, 'salary' => $salary], 200);
    }

    /**
     * Trả lương nhân viên
     * @param mixed $month
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function PaySalary($month){
        $data = json_decode(file_get_contents('php://input'), true);
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $date = $this->GetLastDayOfMonth( $year, $month);
        $autoId =  (string)Str::uuid();
        $pay = [
            "id" => $autoId,
            "money" => $data["money"],
            "date" => $date,
            "staffId" => $data["staffId"]
        ];
        $this->neo4j->run($this->queryDatasource["Staff"]["PaySalary"], $pay);
        return response()->json(["Inform" => $autoId], 200);
    }
    function GetLastDayOfMonth($year, $month) {
        $date = new DateTime("$year-$month-01");
        $date->modify('last day of this month');
        return $date->format('Y-m-d');
    }
}
