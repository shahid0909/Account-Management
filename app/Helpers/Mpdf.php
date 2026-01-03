<?php


namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;
use Mpdf\MpdfException;

class Mpdf extends \Mpdf\Mpdf
{
    public function generateReport($data, $viewPath, $fileName)
    {
        $time = Carbon::now();
        $footer = "<div style='text-align: right;'>Printed at: " . $time->toDateTimeString() . "<span></span></div>";

        try {
            //$data->image = public_path("/assets/pdf_logo.png"); //ADDDED THIS IMAGE PATH
            //$imagePath = public_path("/img/naif.jpeg");
            //$data[]['image_path'] = public_path("/img/naif.jpg");

            ob_end_flush();
            //$mpdf = new mPDF();
            $this->useSubstitutions = false;
            $this->simpleTables = true;
            $this->curlAllowUnsafeSslRequests = true;
            $this->showImageErrors = true;
            //$mpdf->Image($imagePath, 500, 500, 210, 297, 'jpeg', '', true, false);
            //$this->AddPage('L');

            $this->SetHTMLFooter('
<table width="100%" style="font-size: 12px;">
    <tr>
        <td width="33%">Printed at:{DATE j M Y h:i:s A}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right;">Developed By: Fanush Soft</td>
    </tr>
</table>');
            $html = View::make($viewPath)->with(compact('data'))->render();
            $this->WriteHTML($html,HTMLParserMode::HTML_BODY);

            $this->Output($fileName, 'I');
        }  catch (MpdfException $e) {
            dd($e->getMessage());
        }
    }
}
