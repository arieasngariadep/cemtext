<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\QRDisputeModel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
// use DateTime;

class QRDisputeExport implements FromView, WithColumnFormatting, WithEvents
{
    use RegistersEventListeners;

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function columnFormats(): array
    {
        return [
            'D' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'E' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'G' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'H' => '[Blue]#,##0.00_);[Red](#,##0.00)'
        ];
    }

    /**
    * @return \Carbon\Carbon|null
    */
    public function transformDate($value, $format = 'd/m/y')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function view(): View
    {
        $tanggalReport = QRDisputeModel::getTanggalReport($this->userId);
        $tanggalReport = $this->transformDate($tanggalReport->tanggalReport); // "d/m/y" corresponds to the input format
        setlocale(LC_TIME, 'id_ID');
        \Carbon\Carbon::setLocale('id');
        $tanggalReport = $tanggalReport->isoFormat('D MMMM Y');

        return view('QR_Dispute.reportQRDispute', [
            'kode30hak' => QRDisputeModel::getDataDisputeKode30Hak($this->userId),
            'kode31hak' => QRDisputeModel::getDataDisputeKode31Hak($this->userId),
            'kode40hak' => QRDisputeModel::getDataDisputeKode40Hak($this->userId),
            'kode41hak' => QRDisputeModel::getDataDisputeKode41Hak($this->userId),
            'kode81hak' => QRDisputeModel::getDataDisputeKode81Hak($this->userId),
            'kode82hak' => QRDisputeModel::getDataDisputeKode82Hak($this->userId),
            'kode91hak' => QRDisputeModel::getDataDisputeKode91Hak($this->userId),
            'kode92hak' => QRDisputeModel::getDataDisputeKode92Hak($this->userId),
            'kode93hak' => QRDisputeModel::getDataDisputeKode93Hak($this->userId),
            'kode94hak' => QRDisputeModel::getDataDisputeKode94Hak($this->userId),
            'kode30kewajiban' => QRDisputeModel::getDataDisputeKode30Kewajiban($this->userId),
            'kode31kewajiban' => QRDisputeModel::getDataDisputeKode31Kewajiban($this->userId),
            'kode40kewajiban' => QRDisputeModel::getDataDisputeKode40Kewajiban($this->userId),
            'kode41kewajiban' => QRDisputeModel::getDataDisputeKode41Kewajiban($this->userId),
            'kode81kewajiban' => QRDisputeModel::getDataDisputeKode81Kewajiban($this->userId),
            'kode82kewajiban' => QRDisputeModel::getDataDisputeKode82Kewajiban($this->userId),
            'kode91kewajiban' => QRDisputeModel::getDataDisputeKode91Kewajiban($this->userId),
            'kode92kewajiban' => QRDisputeModel::getDataDisputeKode92Kewajiban($this->userId),
            'kode93kewajiban' => QRDisputeModel::getDataDisputeKode93Kewajiban($this->userId),
            'kode94kewajiban' => QRDisputeModel::getDataDisputeKode94Kewajiban($this->userId),
            'tanggalReport' => $tanggalReport,
        ]);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $styleArray = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $event->getSheet()->getDelegate()->getStyle('A4:I4')->applyFromArray($styleArray);

        $event->getSheet()->getPageMargins()->setTop(0.4);
        $event->getSheet()->getPageMargins()->setRight(0.6);
        $event->getSheet()->getPageMargins()->setLeft(0.5);
        $event->getSheet()->getPageMargins()->setBottom(0.4);
        $event->getSheet()->getPageMargins()->setHeader(0.4);
        $event->getSheet()->getPageMargins()->setFooter(0);
        $event->getSheet()->getPageSetup()->setHorizontalCentered(true);
        $event->getSheet()->getPageSetup()->setFitToPage(false);
        $event->getSheet()->getPageSetup()->setScale(70);

        $event->getSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $event->getSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    }
}
