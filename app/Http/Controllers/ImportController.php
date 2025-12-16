<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Cursos;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function showImportForm()
    {
        $congresos = Cursos::where('tipo', 'congreso')
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('Administrador.userExcel', compact('congresos'));
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
            'congreso_id' => 'required|exists:cursos,id',
        ], [
            'excel_file.required' => 'Debe seleccionar un archivo Excel',
            'excel_file.file' => 'El archivo no es válido',
            'excel_file.mimes' => 'El archivo debe ser de tipo Excel (xlsx, xls) o CSV',
            'congreso_id.required' => 'Debe seleccionar un congreso',
            'congreso_id.exists' => 'El congreso seleccionado no existe',
        ]);

        try {
            $import = new UsersImport($request->congreso_id);
            Excel::import($import, $request->file('excel_file'));

            $results = $import->getResults();

            Log::channel('admin')->info('Importación masiva de usuarios', [
                'admin_id' => auth()->id(),
                'congreso_id' => $request->congreso_id,
                'results' => $results,
            ]);

            return redirect()->back()->with([
                'success' => 'Importación completada con éxito',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error("Error en importación masiva: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return redirect()->back()->withErrors([
                'import_error' => "Error al procesar el archivo: {$e->getMessage()}"
            ]);
        }
    }
}
