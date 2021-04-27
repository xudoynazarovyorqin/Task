<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Material;
use App\WarehouseMaterial;
use App\WarehouseProduct;
use App\WarehouseType;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_File;
use PHPExcel_STYLE_ALIGNMENT;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Settings;
use dompdf;

class ReportController extends Controller
{
    /**
     * @var Response
     */
    protected $response; 

    public function __construct(Response $response)
    {
        $this->middleware('auth:api');
        $this->response = $response;
    }

    public function getReportMaterials()
    {
        $warehouseMaterials = WarehouseMaterial::get();
        
        $reportMaterials = array();

        foreach ($warehouseMaterials as $warehouseMaterial)
        {
            if( !isset($reportMaterials[$warehouseMaterial->material_id]) )
            {
                $reportMaterials[$warehouseMaterial->material_id] = array();
                $reportMaterials[$warehouseMaterial->material_id]['material_id'] = $warehouseMaterial->material_id;
                $reportMaterials[$warehouseMaterial->material_id]['name'] = $warehouseMaterial->name;
                $reportMaterials[$warehouseMaterial->material_id]['price'] = $warehouseMaterial->price;
                $reportMaterials[$warehouseMaterial->material_id]['qty_weight'] = 0;
                $reportMaterials[$warehouseMaterial->material_id]['remainder'] = 0;
                $reportMaterials[$warehouseMaterial->material_id]['price_sum'] = 0;
                $reportMaterials[$warehouseMaterial->material_id]['material_count'] = 0;
            }

            $reportMaterials[$warehouseMaterial->material_id]['qty_weight'] += $warehouseMaterial->qty_weight;
            $reportMaterials[$warehouseMaterial->material_id]['remainder'] += $warehouseMaterial->remainder;
            $reportMaterials[$warehouseMaterial->material_id]['price_sum'] += $warehouseMaterial->price;
            $reportMaterials[$warehouseMaterial->material_id]['material_count']++;
        }

        return $reportMaterials;
    }

    public function reportMaterials()
    {
        $reportMaterials = $this->getReportMaterials();

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'reportMaterials'  => $reportMaterials
               ]
           ]
        ]);
    }

    public function exportExcelMaterials()
    {
        // $export = new ClientsExport([
        //     [1, 2, 3],
        //     [4, 5, 6]
        // ]);
        //return Excel::download(new ClientsExport, 'users.xls', \Maatwebsite\Excel\Excel::XLS);
        

        $reportMaterials = $this->getReportMaterials();        

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        
        /** Include PHPExcel */        
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
                
        // Add some data
       
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№ п/п')
        ->setCellValue('B1', 'Наименование сырья')
        ->setCellValue('C1', 'Остаток')
        ->setCellValue('D1', 'Общее количество')
        ->setCellValue('E1', 'Средняя цена');
        
        // Adding information
        
        $y_count = 2;
        $poryadkoviy_nomer = 1;

        foreach ($reportMaterials as $reportMaterial) 
        {
            $x_count = 'A';

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$y_count, $poryadkoviy_nomer);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B'.$y_count, $reportMaterial['name'] );

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C'.$y_count, number_format($reportMaterial['remainder'], 0, '.', ' ') );

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D'.$y_count, number_format($reportMaterial['qty_weight'], 0, '.', ' ') );

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('E'.$y_count, number_format($reportMaterial['price_sum'] / $reportMaterial['material_count'], 0, '.', ' ') );            

            $poryadkoviy_nomer++;
            $y_count++;
             
        } 
        
                
        // Стили для верхней надписи (первая строка)
        
        $sheet = $objPHPExcel->getActiveSheet();
        
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        
//        Header styles        
        
        $style_header = array(
            // Шрифт
            'font'=>array(
                'bold' => true,
            ),            
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Background color
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFF00')
            )
        );
        $sheet->getStyle('A1:'.$sheet->getHighestDataColumn().'1')->applyFromArray($style_header);
        $sheet->getRowDimension(1)->setRowHeight(30);

//        Body styles
        //$poryadkoviy_nomer += 4;
        
        $style = array(
            // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
        );
        $sheet->getStyle('A2:'.$sheet->getHighestDataColumn().$poryadkoviy_nomer)->applyFromArray($style);

        $sheet->getStyle('A1:E'.$poryadkoviy_nomer)->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
        );
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Отчёт');        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report.xls"');        
        $objWriter->save('php://output');


        // $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
        // $rendererLibrary = 'dompdf';
        // //$rendererLibraryPath = dirname(__FILE__). 'libs/classes/dompdf' . $rendererLibrary;
        // $rendererLibraryPath = base_path() . '\vendor\dompdf\dompdf\\';

        // if (!PHPExcel_Settings::setPdfRenderer(
        //         $rendererName,
        //         $rendererLibraryPath
        //     )) {
        //     die(
        //         'NOTICE: Please set the $rendererName and $rendererLibraryPath values at the top of this script as appropriate for your directory structure'
        //     );
        // }
        
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        // header('Content-Type: application/pdf');
        // header('Content-Disposition: attachment;filename="Report_Quantity.pdf"');
        // $objWriter->writeAllSheets();
        // $objWriter->save('php://output');
    }

    public function getReportProducts()
    {
        $warehouseProducts = WarehouseProduct::get();
        
        $reportProducts = array();

        foreach ($warehouseProducts as $warehouseProduct)
        {

            if( !isset($reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]) )
            {
                $warehouseType = WarehouseType::find($warehouseProduct->warehouse_type_id);

                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id] = array();
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['product_id'] = $warehouseProduct->product_id;

                if( $warehouseType )
                {
                    $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['warehouse_type'] = $warehouseType->name;
                }
                else
                {
                    $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['warehouse_type'] = '';
                }

                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['name'] = $warehouseProduct->name;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['price'] = $warehouseProduct->price;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['selling_price'] = $warehouseProduct->selling_price;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['qty_weight'] = 0;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['remainder'] = 0;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['price_sum'] = 0;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['selling_price_sum'] = 0;
                $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['product_count'] = 0;
            }

            $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['qty_weight'] += $warehouseProduct->qty_weight;
            $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['remainder'] += $warehouseProduct->remainder;
            $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['price_sum'] += $warehouseProduct->price;
            $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['selling_price_sum'] += $warehouseProduct->selling_price;
            $reportProducts[$warehouseProduct->warehouse_type_id][$warehouseProduct->product_id]['product_count']++;
        }

        return $reportProducts;
    }

    public function reportProducts()
    {
        $reportProducts = $this->getReportProducts();

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data'    => [
                   'reportProducts'  => $reportProducts
               ]
           ]
        ]);
    }

    public function exportExcelProducts()
    {
        $reportProducts = $this->getReportProducts();

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        
        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
        
        /** Include PHPExcel */        
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
                
        // Add some data
       
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '№ п/п')
        ->setCellValue('B1', 'Тип склада')
        ->setCellValue('C1', 'Наименование продукта')
        ->setCellValue('D1', 'Остаток')
        ->setCellValue('E1', 'Общее количество')
        ->setCellValue('F1', 'Средняя закупочная цена')
        ->setCellValue('G1', 'Средняя продажная цена');
        
        // Adding information
        
        $y_count = 2;
        $poryadkoviy_nomer = 1;

        foreach ($reportProducts as $reportProduct) 
        {
            foreach ($reportProduct as $item) 
            {   
                $x_count = 'A';

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$y_count, $poryadkoviy_nomer);

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('B'.$y_count, $item['warehouse_type'] );

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('C'.$y_count, $item['name'] );

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('D'.$y_count, number_format($item['remainder'], 0, '.', ' ') );

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('E'.$y_count, number_format($item['qty_weight'], 0, '.', ' ') );

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F'.$y_count, number_format($item['price_sum'] / $item['product_count'], 0, '.', ' ') );

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('G'.$y_count, number_format($item['selling_price_sum'] / $item['product_count'], 0, '.', ' ') );                

                $poryadkoviy_nomer++;
                $y_count++;
            }
        } 
        
                
        // Стили для верхней надписи (первая строка)
        
        $sheet = $objPHPExcel->getActiveSheet();
        
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        
//        Header styles        
        
        $style_header = array(
            // Шрифт
            'font'=>array(
                'bold' => true,
            ),            
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Background color
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFF00')
            )
        );
        $sheet->getStyle('A1:'.$sheet->getHighestDataColumn().'1')->applyFromArray($style_header);
        $sheet->getRowDimension(1)->setRowHeight(30);

//        Body styles
        //$poryadkoviy_nomer += 4;
        
        $style = array(
            // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
        );
        $sheet->getStyle('A2:'.$sheet->getHighestDataColumn().$poryadkoviy_nomer)->applyFromArray($style);

        $sheet->getStyle('A1:G'.$poryadkoviy_nomer)->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => '000000')
                    )
                )
            )
        );
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Отчёт');        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report.xls"');        
        $objWriter->save('php://output');
    }

}
