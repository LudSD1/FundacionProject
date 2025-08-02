@section('titulo')

Editar Contraseña
@endsection


<style>
    #password-strength {
        font-size: 0.9rem;
        font-weight: bold;
        margin-top: 5px;
    }
</style>

@section('content')

<div class="border  p-3 my-1 ml-1 mr-1">
<a href="javascript:history.back()" class="btn btn-sm btn-primary">
    &#9668; Volver
</a>
<br>
<div class="col-15 -ml-3">


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Cambiar Contraseña</h2>

                        <form action="{{ route('cambiarContrasenaPost', encrypt(auth()->user()->id)) }}" method="POST">
                            @csrf

                            <!-- Contraseña antigua -->
                            <div class="mb-3">
                                <label for="oldpassword" class="form-label">Contraseña Antigua</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="oldpassword" name="oldpassword" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('oldpassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Nueva contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" oninput="checkPasswordStrength()" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div id="password-strength" class="text-muted"></div>
                            </div>

                            <!-- Confirmar nueva contraseña -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Botón de envío -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para alternar visibilidad de contraseña -->
    <script>
        function togglePassword(fieldId, button) {
            let input = document.getElementById(fieldId);
            let icon = button.querySelector("i");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }

        function checkPasswordStrength() {
            let password = document.getElementById("password").value;
            let strengthText = document.getElementById("password-strength");

            if (password.length < 6) {
                strengthText.textContent = "Débil 🔴";
                strengthText.classList.remove("text-success");
                strengthText.classList.add("text-danger");
            } else if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/) && password.length >= 8) {
                strengthText.textContent = "Fuerte ✅";
                strengthText.classList.remove("text-danger");
                strengthText.classList.add("text-success");
            } else {
                strengthText.textContent = "Moderada 🟡";
                strengthText.classList.remove("text-danger", "text-success");
                strengthText.classList.add("text-warning");
            }
        }
    </script>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

@include('layout')
