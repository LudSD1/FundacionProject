<?php

namespace App\Http\Controllers;

use App\Models\Inscritos;
use App\Models\Cursos;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentTrendsController extends Controller
{
    /**
     * Devuelve datos JSON de tendencias de inscripción para gráficos y tablas.
     */
    public function getData(Request $request)
    {
        $periodo = $request->input('periodo', '12meses'); // 7dias, 30dias, 12meses, mes
        $mes     = $request->input('mes');   // formato YYYY-MM para drill-down diario
        $anio    = $request->input('anio', now()->year);

        $data = [];

        // ── KPIs ───────────────────────────────────────────────
        $data['kpis'] = $this->getKpis();

        // ── Datos mensuales (últimos 12 meses) ─────────────────
        $data['monthly'] = $this->getMonthly();

        // ── Datos diarios (drill-down de un mes) ───────────────
        if ($mes) {
            $data['daily'] = $this->getDaily($mes);
        }

        // ── Top cursos ─────────────────────────────────────────
        $data['topCourses'] = $this->getTopCourses($periodo, $mes);

        // ── Inscripciones por categoría ────────────────────────
        $data['byCategory'] = $this->getByCategory($periodo, $mes);

        // ── Inscripciones por día de la semana ─────────────────
        $data['byWeekday'] = $this->getByWeekday($periodo, $mes);

        return response()->json($data);
    }

    /* ══════════════════════════════════════════════════════════
     *  HELPERS
     * ══════════════════════════════════════════════════════════ */

    private function getKpis(): array
    {
        $inicioMesActual   = Carbon::now()->startOfMonth();
        $finMesActual      = Carbon::now()->endOfMonth();
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior    = Carbon::now()->subMonth()->endOfMonth();

        $inscripcionesMesActual   = Inscritos::whereBetween('created_at', [$inicioMesActual, $finMesActual])->count();
        $inscripcionesMesAnterior = Inscritos::whereBetween('created_at', [$inicioMesAnterior, $finMesAnterior])->count();

        $variacion = $inscripcionesMesAnterior > 0
            ? round((($inscripcionesMesActual - $inscripcionesMesAnterior) / $inscripcionesMesAnterior) * 100, 1)
            : ($inscripcionesMesActual > 0 ? 100 : 0);

        // Curso más popular este mes
        $cursoTop = Inscritos::select('cursos_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$inicioMesActual, $finMesActual])
            ->groupBy('cursos_id')
            ->orderByDesc('total')
            ->first();

        $cursoTopNombre = $cursoTop
            ? (Cursos::find($cursoTop->cursos_id)->nombreCurso ?? 'N/A')
            : 'Sin datos';

        // Categoría más popular este mes
        $categoriaTop = DB::table('inscritos')
            ->join('curso_categoria', 'inscritos.cursos_id', '=', 'curso_categoria.curso_id')
            ->join('categoria', 'curso_categoria.categoria_id', '=', 'categoria.id')
            ->whereBetween('inscritos.created_at', [$inicioMesActual, $finMesActual])
            ->whereNull('inscritos.deleted_at')
            ->select('categoria.name', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria.name')
            ->orderByDesc('total')
            ->first();

        return [
            'inscripcionesMes'    => $inscripcionesMesActual,
            'variacion'           => $variacion,
            'cursoTop'            => $cursoTopNombre,
            'cursoTopTotal'       => $cursoTop->total ?? 0,
            'categoriaTop'        => $categoriaTop->name ?? 'Sin datos',
            'categoriaTopTotal'   => $categoriaTop->total ?? 0,
        ];
    }

    private function getMonthly(): array
    {
        $desde = Carbon::now()->subMonths(11)->startOfMonth();

        $rows = Inscritos::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periodo"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $desde)
            ->whereNull('deleted_at')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        // Rellenar meses vacíos
        $result = [];
        $cursor = $desde->copy();
        for ($i = 0; $i < 12; $i++) {
            $key = $cursor->format('Y-m');
            $found = $rows->firstWhere('periodo', $key);
            $result[] = [
                'periodo' => $key,
                'label'   => $cursor->translatedFormat('M Y'),
                'total'   => $found ? $found->total : 0,
            ];
            $cursor->addMonth();
        }

        return $result;
    }

    private function getDaily(string $mes): array
    {
        $inicio = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $rows = Inscritos::select(
                DB::raw("DATE(created_at) as dia"),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$inicio, $fin])
            ->whereNull('deleted_at')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $result = [];
        $cursor = $inicio->copy();
        while ($cursor <= $fin) {
            $key = $cursor->format('Y-m-d');
            $found = $rows->firstWhere('dia', $key);
            $result[] = [
                'dia'   => $key,
                'label' => $cursor->format('d'),
                'total' => $found ? $found->total : 0,
            ];
            $cursor->addDay();
        }

        return $result;
    }

    private function getTopCourses(string $periodo, ?string $mes): array
    {
        $query = Inscritos::select('cursos_id', DB::raw('COUNT(*) as total'))
            ->whereNull('deleted_at');

        $this->applyPeriodFilter($query, $periodo, $mes);

        $top = $query->groupBy('cursos_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $result = [];
        foreach ($top as $row) {
            $curso = Cursos::with('categorias')->find($row->cursos_id);
            if (!$curso) continue;

            $result[] = [
                'id'         => $curso->id,
                'nombre'     => $curso->nombreCurso,
                'codigo'     => $curso->codigoCurso,
                'imagen'     => $curso->imagen ? asset('storage/' . $curso->imagen) : asset('assets/img/bg2.png'),
                'tipo'       => $curso->tipo,
                'categorias' => $curso->categorias->pluck('name')->toArray(),
                'total'      => $row->total,
            ];
        }

        return $result;
    }

    private function getByCategory(string $periodo, ?string $mes): array
    {
        $query = DB::table('inscritos')
            ->join('curso_categoria', 'inscritos.cursos_id', '=', 'curso_categoria.curso_id')
            ->join('categoria', 'curso_categoria.categoria_id', '=', 'categoria.id')
            ->whereNull('inscritos.deleted_at')
            ->select('categoria.name', DB::raw('COUNT(*) as total'));

        $this->applyPeriodFilterRaw($query, $periodo, $mes);

        $rows = $query->groupBy('categoria.name')
            ->orderByDesc('total')
            ->get();

        return $rows->map(fn($r) => ['name' => $r->name, 'total' => $r->total])->toArray();
    }

    private function getByWeekday(string $periodo, ?string $mes): array
    {
        $query = Inscritos::select(
                DB::raw("DAYOFWEEK(created_at) as dia_semana"),
                DB::raw('COUNT(*) as total')
            )
            ->whereNull('deleted_at');

        $this->applyPeriodFilter($query, $periodo, $mes);

        $rows = $query->groupBy('dia_semana')
            ->orderBy('dia_semana')
            ->get();

        $diasNombre = ['', 'Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $result = [];
        for ($d = 1; $d <= 7; $d++) {
            $found = $rows->firstWhere('dia_semana', $d);
            $result[] = [
                'dia'   => $diasNombre[$d],
                'total' => $found ? $found->total : 0,
            ];
        }

        return $result;
    }

    /* ── Helpers de filtro ───────────────────────────────────── */

    private function applyPeriodFilter($query, string $periodo, ?string $mes): void
    {
        if ($mes) {
            $inicio = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
            $fin    = $inicio->copy()->endOfMonth();
            $query->whereBetween('created_at', [$inicio, $fin]);
            return;
        }

        match ($periodo) {
            '7dias'   => $query->where('created_at', '>=', now()->subDays(7)),
            '30dias'  => $query->where('created_at', '>=', now()->subDays(30)),
            default   => $query->where('created_at', '>=', now()->subMonths(12)),
        };
    }

    private function applyPeriodFilterRaw($query, string $periodo, ?string $mes): void
    {
        if ($mes) {
            $inicio = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
            $fin    = $inicio->copy()->endOfMonth();
            $query->whereBetween('inscritos.created_at', [$inicio, $fin]);
            return;
        }

        match ($periodo) {
            '7dias'   => $query->where('inscritos.created_at', '>=', now()->subDays(7)),
            '30dias'  => $query->where('inscritos.created_at', '>=', now()->subDays(30)),
            default   => $query->where('inscritos.created_at', '>=', now()->subMonths(12)),
        };
    }
}
