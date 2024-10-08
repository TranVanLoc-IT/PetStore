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

class PorfolioController extends Controller
{
    // co loc theo thoi gian
    /**
     * Xoa du lieu, thong ke doanh thu theo thang. 
     * Danh sanh cac thu cung, gia ban, so luong ban, so luong ton, doanh thu. Tong doanh thu, tong chi. Danh sach hoa don
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetProductData($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        // san pham + hoa don di kem
        $product = [];
        $invoices = [];
        $data = $this->neo4j->run($this->queryDatasource["Product"]["GetProducts"],['date' => $dateCriteria]);
        foreach($data as $d)
        {
            $pet = new Pet($d->get("p")->values());
            $products[] = $pet;
            $invoices[] = $d->get("invoiceList");
        }
        
        return view('petView',compact('products', 'invoices'));
    }
    /**
     * Nhan vien , thong tin gio lam, vi tri, ket qua lam viec(so hoa don). Xep hang nhan vien, luong chi tra, tong luong chi tra
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetStaffData($month){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';

        $staffs = [];
        $paysalary = [];
        $shiftWork = []; // chargeof
        $data = $this->neo4j->run($this->queryDatasource["Service"]["GetStaffShiftWorkData"], ['date'=> $dateCriteria]);
        foreach($data as $d)
        {
            $staff = new Staff($d->get("s")->values());
            $staffs[] = $staff;
            $shifts[$staff->id] = $d->get("shiftList");
            $paySalary[$staff->id] = $d->get("salary");
        }
        return view('staffView', compact('staffs', 'shiftWork'));
    }
}
