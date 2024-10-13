<?php

namespace App\Exports;

use App\Models\ProductDataExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Log;
class DataExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
   
    protected $records;
    protected $headings;
    protected $sheetName;


    public function __construct($records, $headings, $sheetName)
    {
        $this->records = $records;
        $this->headings = $headings;
        $this->sheetName = $sheetName;
    }

    /**
     * Xử lý dữ liệu rows
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [];
        if($this->sheetName == "Product") {

            foreach ($this->records as $row) {
                $expandedRow = [
                    $row[0]
                ];
    
                // Dữ liệu các ngày
                foreach ($row[1] as $revenue) {
                    $expandedRow[] = number_format((float)$revenue,0,',','.').' VND';
                }
    
                // Các cột còn lại
                $expandedRow[] = $row[2]; 
                $expandedRow[] = $row[3]; 
                $expandedRow[] = $row[4];
    
                $data[] = $expandedRow;
            }    
        }
        else{
        foreach ($this->records as $row) {
            $expandedRow = [
                $row[0],
                $row[1]
            ];

            // Dữ liệu các sản phẩm
            foreach ($this->headings[2] as $productName) {
                if(array_key_exists($productName, $row[2]))
                {
                    $expandedRow[] = $row[2][$productName];
                }
                else{
                    $expandedRow[] = "";
                }
            }

            // Các cột còn lại
            $expandedRow[] = $row[3]; 
            $expandedRow[] = $row[4]; 
            $expandedRow[] = $row[5];

            $data[] = $expandedRow;
        }
        }
        return collect($data);
    }

    /**
     * Cấu hình tên sheet
     * @return string
     */
    public function title(): string
    {
        return $this->sheetName;
    }

    /**
     * Tên các cột
     * @return array
     */
    public function headings(): array
    {
        if($this->sheetName == 'Product')
        {

            $columns = [
                $this->headings[0], // Tên sản phẩm
            ];
    
            // Giải nén các giá trị trong cột "Doanh thu các ngày"
            foreach ($this->headings[1] as $date) {
                $columns[] = $date;
            }
    
            // Các cột còn lại
            $columns[] = $this->headings[2]; // Số lượng đã bán
            $columns[] = $this->headings[3]; // Số lượng còn
            $columns[] = $this->headings[4]; // Tổng doanh thu
        }
        if($this->sheetName == "Store")
        {
            
        $columns = [
            $this->headings[0], // Tên Tài liệu
            $this->headings[1], // Ngày tạo
        ];

        // Sản phẩm
        foreach ($this->headings[2] as $date) {
            $columns[] = $date;
        }

        // Các cột còn lại
        $columns[] = $this->headings[3]; // Mã GD
        $columns[] = $this->headings[4]; // Trạng thái GD
        $columns[] = $this->headings[5]; // Tổng doanh thu
        }

        return $columns;
    }

    /**
     * Cấu hình phông chữ, in đậm
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array[]
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            // Làm đậm hàng đầu tiên (tiêu đề)
            1 => ['font' => ['bold' => true]],

            // Làm đậm và thay đổi màu sắc, kích thước cho các cột
            'A' => ['font' => ['bold' => true, 'color' => ['rgb' => 'FF0000'], 'size' => 14]],
            'B' => ['font' => ['bold' => true, 'color' => ['rgb' => '0000FF'], 'size' => 12]],

        ];
    }
   
}
