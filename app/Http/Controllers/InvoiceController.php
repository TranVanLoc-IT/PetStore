<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\RPurchase;
use App\Models\Transaction;
use Carbon\Carbon;
use Log;
class InvoiceController extends Controller
{
    /** 
     * 
     * Lay het hoa don theo thang, default = thang hien tai
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetInvoice($month = null){
        // thiet lap dieu kien ngay dang nam-thang.*
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        $invoices = [];

        // Goi chuoi truy van va lap qua cac ket qua /queries/queries.yaml, truyền tham số cho chuỗi
        foreach($this->neo4j->run($this->queryDatasource["Invoice"]["GetInvoices"],["date" => $dateCriteria]) as $invoice){
            // Khi truy cập có thể dạng đối tượng dùng toán tử ->
            $inv = new Invoice($invoice->get("i")->toArray());
            array_push($invoices, $inv);
        }
        // Trả về view và dữ liệu thông qua compact()
        return view('invoiceView', compact("invoices"));
    }

    /**
     * Xoa hoa don bang id hoa don
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function Delete(){
        // Lấy data submit từ Javascript trong phần data của Ajax
        $data = json_decode(file_get_contents("php://input"), true);
        try{
            $change = $this->neo4j->run($this->queryDatasource["Invoice"]["DeleteInvoice"],["id" => $data["id"]]);
            // Phản hồi kết quả 
            return response()->json(["Inform", "Thành công", 200]);
        }catch(\Exception $e){
            return response()->json(['Inform', "Có lỗi cú pháp"], 404);
        }
    }

    /**
     * Lay chi tiet thong tin hoa don: hoa don, san pham mua, giao dich
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetDetailInvoice($invoiceId){
        // Lấy thông tin id từ tham số trên url thay thế cho chuỗi truy vấn, lấy chi tiết
        $data = $this->neo4j->run($this->queryDatasource["Invoice"]["GetDetailInvoice"],["id" => $invoiceId])->first();
        if($data->isEmpty())
        {
            // Không có
            return response()->json(["transaction" => "", "productList" => ""]);
        }
        // Lay tung phan data
        $transaction = $data->get('transaction')->toArray();
        $productList = $data->get('productList')->toArray();
        return response()->json(["transaction" => $transaction, "productList" => $productList]);
    }
   
}
