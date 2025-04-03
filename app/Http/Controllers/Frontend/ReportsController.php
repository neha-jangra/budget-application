<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Livewire\Livewire;
use PDF;
use Symfony\Component\Process\Process;
use App\Traits\NodeScripts;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Response;


class ReportsController extends Controller
{
    use NodeScripts;

    public function index()
    {
        return view('reports.index')->with('livewire', Livewire::mount('reports.reports'));
    }

    public function exportPdf(Request $request)
    {
        return view('reports.export')->with('livewire', Livewire::mount('reports.export'));
    }

    public function generatePDF(Request $request)
    {
        // Generate PDF using the provided content
        $pdf = PDF::loadHTML($request->input('content'));

        // Save the PDF file to the server
        $pdfPath = public_path('pdfs'); // Or any directory where you want to store PDFs
        $pdf->save($pdfPath . '/generated_pdf.pdf');

        // Return the path to the saved PDF
        return response()->json(['pdf_path' => '/pdfs/generated_pdf.pdf']);
    }

    public function takeScreenshot(Request $request)
    {

        $year = $request->year ?? date('Y');
        $options = [
            'waitUntil' => 'networkidle',
            'timeout' => 30000, // Increase timeout to 30 seconds:q
        ];
        // Generate the PDF content
        $pdfContent = Browsershot::url($request->getSchemeAndHttpHost() . '/export-report-pdf?year=' . $year)
            ->setDelay(1000) // Add delay to allow time for graphs to load
            ->waitUntilNetworkIdle() // Wait until network activity is idle before capturing
            ->setOptions($options) // Set additional options
            ->pdf();
        // Return the PDF as a downloadable file
        return Response::make($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="annual-report.pdf"');
    }
}
