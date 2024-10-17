<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\DatabaseJob;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class ExportController extends Controller
{
    // Export Excel cho In ra doanh thu / chi theo thang
    public function GetExport(){
        return view('exportView');
    }

    /**
     * Doanh so ban hang: Ma sp, ten sp, doanh thu cac ngay, sl ban, sl con, tong doanh thu
     * @return void
     */
    public function ExportPetRevenue($month){
        $columns = [];
        $year = date("Y");
        $month = $month == 'null' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year . '-' . $month . '.*';
        $today = date('d-m-Y');

        $rows = [];
        $columnStorage = [];
        $columns = [
            'Tên sản phẩm'];
        $record = $this->neo4j->run($this->queryDatasource["Export"]["ProductRevenue"], ["date" => $dateCriteria]);
        foreach($record as $r){
            $row = [$r->get("name")];
            $values = [];
            foreach($r->get("values") as $v)
            {
                array_push($values, $v);
            }
            foreach($r->get("dates") as $d)
            {
                array_push($columnStorage, $d);
            }
            array_push($row, $values);
            array_push($row, $r->get("quantitySold"));
            array_push($row, $r->get("availableQuantity"));
            array_push($row, $r->get("totalRevenue"));
            array_push($rows, $row);
        }
        array_push($columns, array_unique($columnStorage));

        
        // Chuyển đổi mảng thành chuỗi JSON hoặc format khác dễ đọc
        array_push($columns, 
            'Số lượng đã bán', 
            'Số lượng còn', 
            'Tổng doanh thu');
        // Tạo export và trả về file Excel
        return Excel::download(new DataExport($rows, $columns, "Product"), $today.'-DT-BanHang-'.$dateCriteria.'.xlsx');

    }

    /**
     * Chi tieu: danh sach hoa don, hop dong, tong chi, tong thu, cac khoan khac
     * @return void
     */
    public function ExportStoreExpense($month){
        $columns = [];
        $year = date("Y");
        $month = $month == 'null' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year . '-' . $month . '.*';
        $today = date('d-m-Y');

        $rows = [];
        $columnStorage = [];
        $columns = [
            'Tên tài liệu', 'Ngày tạo'];
        $products = $this->neo4j->run($this->queryDatasource["Product"]["GetAllProductName"]);
        $record = $this->neo4j->run($this->queryDatasource["Export"]["StoreRevenue"], ["date" => $dateCriteria]);
        foreach($products as $d)
        {
            array_push($columnStorage, $d->get("name"));
        }
        foreach($record as $r){
            $row = [$r->get("name")];
            array_push($row, [$r->get("dateCreated")]);
            $product = [];
            $productNames = $r->get("productName")->toArray();
            $amounts = $r->get("amounts")->toArray();

            if (count($productNames) > 0 && count($amounts) > 0 && count($productNames) === count($amounts)) {
                for ($count = 0; $count < count($productNames); $count++) {
                    // Fine
                    $product[$productNames[$count]] = $amounts[$count];
                }
            }
            array_push($row, $product);
            array_push($row, $r->get("tranId"));
            array_push($row, $r->get("status"));
            array_push($row, $r->get("totalCost"));
            array_push($rows, $row);
        }
        array_push($columns, $columnStorage);

        // Chuyển đổi mảng thành chuỗi JSON hoặc format khác dễ đọc
        array_push($columns, 
            'Mã giao dịch', 
            'Trạng thái giao dịch', 
            'Tổng doanh thu');
        // Tạo export và trả về file Excel
        return Excel::download(new DataExport($rows, $columns, "Store"), $today.'-DT-CuaHang-'.$dateCriteria.'.xlsx');
    }
}
