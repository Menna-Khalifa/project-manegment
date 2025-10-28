<?php

namespace App\Services;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

class ArabicPdfService
{
    /**
     * إنشاء نسخة من Mpdf مع دعم للغة العربية
     *
     * @param array $config
     * @return Mpdf
     */
    public function getMpdf(array $config = []): Mpdf
    {
        $defaultConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'tempDir' => storage_path('app/mpdf'),
            'default_font_size' => 12,
            'default_font' => 'cairo',
            'font_dir' => storage_path('app/fonts/'),
            'font_data' => [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                ],
                'tajawal' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'B' => 'Tajawal-Bold.ttf',
                ],
            ],
        ];

        $config = array_merge($defaultConfig, $config);

        if (!is_dir($config['tempDir'])) {
            mkdir($config['tempDir'], 0755, true);
        }

        $mpdf = new Mpdf($config);
        $mpdf->SetDirectionality(app()->getLocale() == 'ar' ? 'rtl' : 'ltr');
        $mpdf->SetTitle(config('app.name') . ' - ' . __('invoices.invoice'));
        $mpdf->SetAuthor(config('app.name'));
        $mpdf->SetCreator(config('app.name'));

        return $mpdf;
    }

    /**
     * إنشاء ملف PDF من القالب
     *
     * @param string $view
     * @param array $data
     * @param array $config
     * @return Mpdf
     */
    public function generatePdf(string $view, array $data = [], array $config = []): Mpdf
    {
        $mpdf = $this->getMpdf($config);
        $html = view($view, $data)->render();
        $mpdf->WriteHTML($html);

        return $mpdf;
    }

    /**
     * إنشاء وتنزيل ملف PDF
     *
     * @param string $view
     * @param string $filename
     * @param array $data
     * @param array $config
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPdf(string $view, string $filename, array $data = [], array $config = [])
    {
        $mpdf = $this->generatePdf($view, $data, $config);
        return response($mpdf->Output($filename, 'I'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * إنشاء وحفظ ملف PDF
     *
     * @param string $view
     * @param string $path
     * @param array $data
     * @param array $config
     * @return string
     */
    public function savePdf(string $view, string $path, array $data = [], array $config = [])
    {
        $mpdf = $this->generatePdf($view, $data, $config);

        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $mpdf->Output($path, 'F');

        return $path;
    }

    /**
     * إنشاء وحفظ ملف PDF في مساحة التخزين
     *
     * @param string $view
     * @param string $path
     * @param array $data
     * @param array $config
     * @return string
     */
    public function storePdf(string $view, string $storagePath, array $data = [], array $config = [])
    {
        $mpdf = $this->generatePdf($view, $data, $config);

        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
        $mpdf->Output($tempFile, 'F');

        Storage::put($storagePath, file_get_contents($tempFile));

        unlink($tempFile);

        return $storagePath;
    }
}
