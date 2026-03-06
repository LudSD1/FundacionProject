<?php

namespace App\Services;

use App\Models\Aportes;
use App\Models\Factura;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Service to simulate SIAT (Sistema de Facturación en Línea)
 * This generates compliant-looking but simulated billing data
 */
class SiatSimulationService
{
    // Mock Configuration for the "Issuer" (The Foundation)
    protected $nitEmisor = '1020304050'; // Example NIT
    protected $razonSocialEmisor = 'FUNDACION EDUCACION FUTURO';
    protected $direccionEmisor = 'Av. 6 de Agosto Nro. 123, La Paz, Bolivia';
    protected $codigoPuntoVenta = 0; // 0 for main office
    protected $codigoSucursal = 0; // 0 for main office

    // Leyendas set by SIAT (Ley N° 453)
    protected $leyendas = [
        "Ley N° 453: El proveedor deberá entregar el bien o suministrar el servicio en las condiciones ofertadas.",
        "Ley N° 453: Tienes derecho a recibir información fidedigna, veraz, completa, adecuada, gratuita y oportuna.",
        "Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea."
    ];

    /**
     * Generates a billing simulation for a given payment (Aporte)
     */
    public function emitirFactura(Aportes $aporte)
    {
        // Check if invoice already exists
        $existingFactura = Factura::where('aporte_id', $aporte->id)->first();
        if ($existingFactura) {
            return $existingFactura;
        }

        // Generate Simulated SIAT Codes
        $cufd = $this->generarCufd(); // Valid for 24 hours usually
        $cuf = $this->generarCuf($aporte); // Unique per invoice

        // Determine Customer Details
        // Providing fallbacks if user data is missing
        $nitCliente = $aporte->paganteci ?? '0';
        $razonSocialCliente = $aporte->pagante ?? 'S/N';

        // If NIT is 0 or empty, use '0' (Control)
        if (empty($nitCliente)) $nitCliente = '0';
        if (empty($razonSocialCliente)) $razonSocialCliente = 'SN';

        // Calculate Totals
        // Assuming no discount for simplicity in this simulation, or logic for it
        $montoTotal = $aporte->monto_pagado ?? $aporte->monto;
        $descuento = 0;
        $montoFinal = $montoTotal - $descuento;

        // Select a random legend
        $leyenda = $this->leyendas[array_rand($this->leyendas)];

        // Create the Invoice Record
        $factura = Factura::create([
            'aporte_id' => $aporte->id,
            'numero_factura' => $this->generarNumeroFactura(),
            'cuf' => $cuf,
            'cufd' => $cufd,
            'codigo_control' => Str::random(12), // Legacy simulation
            'nit_emisor' => $this->nitEmisor,
            'razon_social_emisor' => $this->razonSocialEmisor,
            'direccion_emisor' => $this->direccionEmisor,
            'fecha_emision' => Carbon::now(),
            'nit_cliente' => $nitCliente,
            'razon_social_cliente' => $razonSocialCliente,
            'monto_total' => $montoTotal,
            'descuento' => $descuento,
            'monto_final' => $montoFinal,
            'leyenda' => $leyenda,
            'estado' => 'VALIDADA',
            'xml_representacion' => '<xml>Simulated SIAT XML Structure</xml>'
        ]);

        return $factura;
    }

    /**
     * Generates a Mock CUF (Código Único de Facturación)
     * Format is typically a long hex string derived from fields.
     */
    protected function generarCuf($aporte)
    {
        // In real SIAT, this is a complex hash of:
        // NIT + Fecha + Sucursal + Modalidad + TipoEmision + TipoFactura + TipoDocSector + NumeroFactura + PuntoVenta
        // + DigitoVerificador + ControlCode...

        // For simulation, we create a hash that looks right (approx 64 chars hex)
        $data = $this->nitEmisor . Carbon::now()->timestamp . $aporte->id . Str::random(10);
        return strtoupper(hash('sha256', $data));
    }

    /**
     * Generates a Mock CUFD (Código Único de Facturación Diaria)
     */
    protected function generarCufd()
    {
        // In real SIAT, retrieved daily via API.
        // We simulate a hash based on the current date, so it's consistent for the day.
        $seed = Carbon::now()->format('Y-m-d') . 'SaltSecret';
        // CUFD is usually simpler/distinct from CUF? Actually check format.
        // It's also a hash string.
        return strtoupper(substr(hash('sha256', $seed), 0, 50) . 'A1'); // Append some control chars simulation
    }

    protected function generarNumeroFactura()
    {
        // Simple sequential or random simulation
        // In real app, query max(numero_factura) + 1
        $max = Factura::max('numero_factura');
        return $max ? $max + 1 : 1;
    }
}
