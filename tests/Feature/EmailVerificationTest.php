<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_verify_email_with_valid_link()
    {
        Event::fake();

        // Crear usuario sin verificar
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generar URL de verificación válida
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => encrypt($user->id),
                'hash' => sha1($user->email),
            ]
        );

        // Hacer petición de verificación
        $response = $this->get($verificationUrl);

        // Verificar redirección exitosa
        $response->assertRedirect(route('Inicio'));
        $response->assertSessionHas('success', '¡Tu cuenta ha sido verificada correctamente!');

        // Verificar que el usuario está marcado como verificado
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // Verificar que se disparó el evento
        Event::assertDispatched(Verified::class);
    }

    /** @test */
    public function user_cannot_verify_email_with_invalid_hash()
    {
        // Crear usuario sin verificar
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generar URL con hash inválido
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => encrypt($user->id),
                'hash' => 'invalid-hash',
            ]
        );

        // Hacer petición de verificación
        $response = $this->get($verificationUrl);

        // Verificar redirección con error
        $response->assertRedirect(route('Inicio'));
        $response->assertSessionHas('error', 'El enlace de verificación no es válido.');

        // Verificar que el usuario NO está verificado
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function user_cannot_verify_email_with_expired_link()
    {
        // Crear usuario sin verificar
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Generar URL expirada (tiempo pasado)
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(10), // Expirada hace 10 minutos
            [
                'id' => encrypt($user->id),
                'hash' => sha1($user->email),
            ]
        );

        // Hacer petición de verificación
        $response = $this->get($verificationUrl);

        // Verificar redirección con error de expiración
        $response->assertRedirect(route('Inicio'));
        $response->assertSessionHas('error', 'El enlace de verificación ha expirado o no es válido.');

        // Verificar que el usuario NO está verificado
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function already_verified_user_gets_info_message()
    {
        // Crear usuario ya verificado
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // Generar URL de verificación válida
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => encrypt($user->id),
                'hash' => sha1($user->email),
            ]
        );

        // Hacer petición de verificación
        $response = $this->get($verificationUrl);

        // Verificar redirección con mensaje informativo
        $response->assertRedirect(route('Inicio'));
        $response->assertSessionHas('info', 'Tu cuenta ya está verificada.');
    }

    /** @test */
    public function user_can_resend_verification_email()
    {
        Notification::fake();

        // Crear usuario sin verificar
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Autenticar usuario
        $this->actingAs($user);

        // Hacer petición de reenvío
        $response = $this->post(route('verification.resend'));

        // Verificar redirección exitosa
        $response->assertRedirect();
        $response->assertSessionHas('resent', true);

        // Verificar que se envió la notificación
        Notification::assertSentTo($user, CustomVerifyEmail::class);
    }

    /** @test */
    public function verified_user_cannot_resend_verification_email()
    {
        Notification::fake();

        // Crear usuario ya verificado
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // Autenticar usuario
        $this->actingAs($user);

        // Hacer petición de reenvío
        $response = $this->post(route('verification.resend'));

        // Verificar redirección con mensaje informativo
        $response->assertRedirect();
        $response->assertSessionHas('info', 'Tu cuenta ya está verificada.');

        // Verificar que NO se envió la notificación
        Notification::assertNotSentTo($user, CustomVerifyEmail::class);
    }

    /** @test */
    public function unverified_user_cannot_access_protected_routes()
    {
        // Crear usuario sin verificar con rol Estudiante
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $user->assignRole('Estudiante');

        // Autenticar usuario
        $this->actingAs($user);

        // Intentar acceder a ruta protegida
        $response = $this->post('/Inscribirse-Curso/1');

        // Verificar redirección a página de verificación
        $response->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function verified_user_can_access_protected_routes()
    {
        // Crear usuario verificado con rol Estudiante
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Estudiante');

        // Autenticar usuario
        $this->actingAs($user);

        // Intentar acceder a ruta protegida (puede fallar por otros motivos, pero no por verificación)
        $response = $this->post('/Inscribirse-Curso/1');

        // No debe redirigir a verificación (puede redirigir por otros motivos)
        $response->assertStatus(302);
        $this->assertNotEquals(route('verification.notice'), $response->headers->get('Location'));
    }
}
