<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\DatabaseJob;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    // Export Excel cho In ra doanh thu / chi theo thang
    public function GetExport(){
        return view('exportView');
    }

    /**
     * Doanh so ban hang cac loai thu cung
     * @return void
     */
    public function ExportPetRevenue(DashboardController $dashboardController,$month){
        return Excel::download($dashboardController->GetStoreExpense($month), 'PetRevenue.xlsx');
    }

    /**
     * Chi tieu: dich vu, nhan vien, hopdong. Doanh thu: phu kien, thu cung, thuoc
     * @return void
     */
    public function ExportStoreExpense(DashboardController $dashboardController,$month){
        return Excel::download($dashboardController->GetPetRevenue($month), 'StoreExpense.xlsx');
    }
}
