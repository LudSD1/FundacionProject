<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::withTrashed()->ordered()->get();

        return view('Administrador.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Administrador.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank,mobile_payment,digital_wallet,cryptocurrency,other',
            'description' => 'nullable|string',
            'account_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'qr_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_info' => 'nullable|array'
        ], [
            'name.required' => 'El nombre del método de pago es obligatorio.',
            'type.required' => 'El tipo de método de pago es obligatorio.',
            'type.in' => 'El tipo de método de pago no es válido.',
            'qr_image.image' => 'El archivo debe ser una imagen.',
            'qr_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'qr_image.max' => 'La imagen no debe ser mayor a 2MB.',
            'sort_order.integer' => 'El orden debe ser un número entero.',
            'sort_order.min' => 'El orden debe ser mayor o igual a 0.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // Manejar la subida de la imagen QR
            if ($request->hasFile('qr_image')) {
                $qrImage = $request->file('qr_image');
                $filename = 'qr_' . time() . '_' . uniqid() . '.' . $qrImage->getClientOriginalExtension();
                $path = $qrImage->storeAs('payment_methods/qr_codes', $filename, 'public');
                $data['qr_image'] = $path;
            }

            // Convertir additional_info a JSON si existe
            if ($request->has('additional_info') && is_array($request->additional_info)) {
                $data['additional_info'] = array_filter($request->additional_info);
            }

            $paymentMethod = PaymentMethod::create($data);

            // Log de actividad del administrador
            Log::channel('admin')->info('Método de pago creado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'create_payment_method',
                'payment_method_data' => [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'account_number' => $data['account_number'] ?? null,
                    'account_holder' => $data['account_holder'] ?? null,
                    'is_active' => $data['is_active'] ?? true
                ],
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('payment-methods.index')
                ->with('success', 'Método de pago creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear método de pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el método de pago. Intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('Administrador.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('Administrador.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank,mobile_payment,digital_wallet,cryptocurrency,other',
            'description' => 'nullable|string',
            'account_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'qr_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'additional_info' => 'nullable|array'
        ], [
            'name.required' => 'El nombre del método de pago es obligatorio.',
            'type.required' => 'El tipo de método de pago es obligatorio.',
            'type.in' => 'El tipo de método de pago no es válido.',
            'qr_image.image' => 'El archivo debe ser una imagen.',
            'qr_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'qr_image.max' => 'La imagen no debe ser mayor a 2MB.',
            'sort_order.integer' => 'El orden debe ser un número entero.',
            'sort_order.min' => 'El orden debe ser mayor o igual a 0.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // Manejar la subida de nueva imagen QR
            if ($request->hasFile('qr_image')) {
                // Eliminar la imagen anterior si existe
                if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
                    Storage::disk('public')->delete($paymentMethod->qr_image);
                }

                $qrImage = $request->file('qr_image');
                $filename = 'qr_' . time() . '_' . uniqid() . '.' . $qrImage->getClientOriginalExtension();
                $path = $qrImage->storeAs('payment_methods/qr_codes', $filename, 'public');
                $data['qr_image'] = $path;
            }

            // Convertir additional_info a JSON si existe
            if ($request->has('additional_info') && is_array($request->additional_info)) {
                $data['additional_info'] = array_filter($request->additional_info);
            }

            $paymentMethod->update($data);

            // Log de actividad del administrador
            Log::channel('admin')->info('Método de pago actualizado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'update_payment_method',
                'payment_method_id' => $paymentMethod->id,
                'payment_method_data' => [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'account_number' => $data['account_number'] ?? null,
                    'account_holder' => $data['account_holder'] ?? null,
                    'is_active' => $data['is_active'] ?? true
                ],
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('payment-methods.index')
                ->with('success', 'Método de pago actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar método de pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el método de pago. Intente nuevamente.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->delete();

            // Log de actividad del administrador
            Log::channel('admin')->info('Método de pago eliminado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'delete_payment_method',
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('payment-methods.index')
                ->with('success', 'Método de pago eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar método de pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al eliminar el método de pago. Intente nuevamente.');
        }
    }

    /**
     * Restore a soft deleted payment method.
     */
    public function restore($id)
    {
        try {
            $paymentMethod = PaymentMethod::withTrashed()->findOrFail($id);
            $paymentMethod->restore();

            // Log de actividad del administrador
            Log::channel('admin')->info('Método de pago restaurado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'restore_payment_method',
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('payment-methods.index')
                ->with('success', 'Método de pago restaurado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al restaurar método de pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al restaurar el método de pago. Intente nuevamente.');
        }
    }

    /**
     * Toggle active status of payment method.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

            $status = $paymentMethod->is_active ? 'activado' : 'desactivado';

            // Log de actividad del administrador
            Log::channel('admin')->info('Estado de método de pago cambiado por administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name ?? 'Sistema',
                'action' => 'toggle_payment_method_status',
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'new_status' => $paymentMethod->is_active,
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            return redirect()->route('payment-methods.index')
                ->with('success', "Método de pago {$status} exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del método de pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al cambiar el estado del método de pago. Intente nuevamente.');
        }
    }

    /**
     * Get active payment methods for public display.
     */
    public function getActivePaymentMethods()
    {
        return PaymentMethod::active()->ordered()->get();
    }
}
