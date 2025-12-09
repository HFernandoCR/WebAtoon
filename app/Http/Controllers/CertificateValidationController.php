<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateValidationController extends Controller
{
    public function validateCertificate(Request $request)
    {
        $folio = $request->query('folio');

        // Simulación de validación por ahora
        return view('certificates.validate', [
            'folio' => $folio,
            'valid' => !empty($folio)
        ]);
    }
}
