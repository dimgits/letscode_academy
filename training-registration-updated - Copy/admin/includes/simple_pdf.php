<?php
/**
 * SimplePDF
 * A tiny, dependency-free PDF writer good enough for simple text/table reports.
 * Uses the built-in PDF core fonts (Helvetica) so no font embedding is needed.
 *
 * This exists because Composer/packagist isn't reachable from this environment,
 * so a full library (TCPDF/mPDF/FPDF) can't be installed. If your hosting
 * environment does have Composer available, swapping to a real PDF library
 * later is straightforward -- this class only needs addPage()/text()/line().
 */
class SimplePDF
{
    private $pages = [];      // content stream per page
    private $currentPage = '';
    private $pageWidth = 595.28;  // A4 in points, portrait
    private $pageHeight = 841.89;

    public function __construct($landscape = false)
    {
        if ($landscape) {
            [$this->pageWidth, $this->pageHeight] = [$this->pageHeight, $this->pageWidth];
        }
        $this->addPage();
    }

    public function addPage()
    {
        if ($this->currentPage !== '') {
            $this->pages[] = $this->currentPage;
        }
        $this->currentPage = '';
    }

    public function width()
    {
        return $this->pageWidth;
    }

    public function height()
    {
        return $this->pageHeight;
    }

    private function escape($text)
    {
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        // Keep to WinAnsi-safe characters (basic Latin) to avoid corrupting the stream.
        return preg_replace('/[^\x20-\x7E]/', '?', $text);
    }

    public function text($x, $y, $text, $size = 10, $bold = false)
    {
        $font = $bold ? '/F2' : '/F1';
        $escaped = $this->escape((string) $text);
        $this->currentPage .= "BT $font $size Tf $x $y Td ($escaped) Tj ET\n";
    }

    public function line($x1, $y1, $x2, $y2, $width = 0.5)
    {
        $this->currentPage .= "$width w $x1 $y1 m $x2 $y2 l S\n";
    }

    public function rect($x, $y, $w, $h, $gray = 0.9)
    {
        $this->currentPage .= "$gray g $x $y $w $h re f\n0 g\n";
    }

    public function output($filename = 'document.pdf')
    {
        // Flush the last page being built.
        $this->pages[] = $this->currentPage;

        $objects = [];

        // 1: Catalog, 2: Pages, 3/4: Fonts. Page objects + content streams follow.
        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";

        $pageCount = count($this->pages);
        $pageObjIds = [];
        $contentObjIds = [];

        $nextId = 5;
        for ($i = 0; $i < $pageCount; $i++) {
            $pageObjIds[$i] = $nextId++;
            $contentObjIds[$i] = $nextId++;
        }

        $kids = implode(' ', array_map(fn($id) => "$id 0 R", $pageObjIds));
        $objects[2] = "<< /Type /Pages /Kids [$kids] /Count $pageCount /MediaBox [0 0 {$this->pageWidth} {$this->pageHeight}] >>";
        $objects[3] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[4] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";

        for ($i = 0; $i < $pageCount; $i++) {
            $objects[$pageObjIds[$i]] =
                "<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentObjIds[$i]} 0 R >>";

            $stream = $this->pages[$i];
            $objects[$contentObjIds[$i]] =
                "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}endstream";
        }

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0 => 0];

        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "$id 0 obj\n$body\nendobj\n";
        }

        $xrefStart = strlen($pdf);
        $maxId = max(array_keys($objects));

        $pdf .= "xref\n0 " . ($maxId + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($id = 1; $id <= $maxId; $id++) {
            if (isset($offsets[$id])) {
                $pdf .= sprintf("%010d 00000 n \n", $offsets[$id]);
            } else {
                $pdf .= "0000000000 00000 f \n";
            }
        }

        $pdf .= "trailer\n<< /Size " . ($maxId + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n$xrefStart\n%%EOF";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));
        echo $pdf;
    }
}
