<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Inscritos;
use App\Models\Certificado;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected $congresoId;
    protected $results = [
        'total' => 0,
        'created' => 0,
        'existing' => 0,
        'registered' => 0,
        'errors' => [],
    ];

    public function __construct($congresoId)
    {
        $this->congresoId = $congresoId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->results['total']++;

            try {
                // Verificar si el usuario ya existe
                $user = User::where('email', $row['correo_electronico'])->first();

                if (!$user) {
                    // Crear nuevo usuario
                    $user = new User();
                    $user->name = $row['nombres'];
                    $user->lastname1 = explode(' ', $row['apellidos'])[0] ?? '';
                    $user->lastname2 = explode(' ', $row['apellidos'])[1] ?? '';
                    $user->CI = Str::random(10); // Generar CI aleatorio
                    $user->Celular = $row['telefono'] ?? 0;
                    $user->fechadenac = Carbon::parse('2000-01-01'); // Fecha por defecto
                    $user->email = $row['correo_electronico'];
                    $user->PaisReside = $row['pais_de_residencia'] ?? '';
                    $user->password = Hash::make(Str::random(8)); // Contraseña aleatoria
                    $user->save();

                    // Asignar rol de estudiante
                    $user->assignRole('Estudiante');

                    $this->results['created']++;
                } else {
                    $this->results['existing']++;
                }

                // Verificar si ya está inscrito en el congreso
                $inscrito = Inscritos::where('estudiante_id', $user->id)
                    ->where('cursos_id', $this->congresoId)
                    ->first();

                if (!$inscrito) {
                    // Inscribir al usuario en el congreso
                    $inscrito = Inscritos::create([
                        'cursos_id' => $this->congresoId,
                        'estudiante_id' => $user->id,
                        'estado' => 'activo',
                    ]);

                    // Generar certificado
                    // $this->generarCertificado($inscrito);

                    $this->results['registered']++;
                }
            } catch (\Exception $e) {
                $this->results['errors'][] = "Error en fila {$this->results['total']}: {$e->getMessage()}";
                Log::error("Error importando usuario: {$e->getMessage()}", [
                    'row' => $row,
                    'exception' => $e,
                ]);
            }
        }
    }

    protected function generarCertificado($inscrito)
    {
        // Verificar si ya tiene certificado
        $certificadoExistente = Certificado::where('inscrito_id', $inscrito->id)->first();

        if (!$certificadoExistente) {
            // Crear certificado
            $certificado = new Certificado();
            $certificado->inscrito_id = $inscrito->id;
            $certificado->uuid = (string) Str::uuid();
            $certificado->url_verificacion = route('verificar.certificado',  $certificado->uuid);
            $certificado->save();
        }
    }

    public function getResults()
    {
        return $this->results;
    }
}
