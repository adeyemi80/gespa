<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RoleController extends Controller
{
   

public function exportPdf($role)
{
    $view = 'roles.pdf.' . $role; // ex: roles/pdf/censeur.blade.php

    $pdf = Pdf::loadView($view);

    return $pdf->download("role-$role.pdf");
}
}
