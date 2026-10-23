<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class DemoPdfController extends Controller
{
    public function show(): Response
    {
        $content = "BT\n/F1 22 Tf\n72 740 Td\n(DbAbility - PDF di prova) Tj\n/F1 12 Tf\n0 -42 Td\n";
        $content .= "(Una breve anteprima del materiale digitale.) Tj\n0 -24 Td\n";
        $content .= "(Le risorse complete saranno disponibili dopo l'acquisto.) Tj\nET\n";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length '.strlen($content)." >>\nstream\n".$content.'endstream',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";

        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf('%010d 00000 n ', $offset)."\n";
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n".$xrefOffset."\n%%EOF";

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="dbability-pdf-di-prova.pdf"',
        ]);
    }
}
