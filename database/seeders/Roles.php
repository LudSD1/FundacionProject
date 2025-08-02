<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // Role::create(['name' => 'Usuario']);
                // Role::create(['name' => 'Invitado']);
                // Role::create(['name' => 'Moderador']);
                // Role::create(['name' => 'Administrador']);
                // Reset cached roles and permissions
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                // create permissions
                Permission::create(['name' => 'Crear Curso']);
                Permission::create(['name' => 'Realizar Tareas']);
                Permission::create(['name' => 'Publicar Tareas']);
                Permission::create(['name' => 'Añadir Estudiantes']);
                Permission::create(['name' => 'Añadir Docentes']);
                Permission::create(['name' => 'Realizar Aportes']);
                Permission::create(['name' => 'Responder Foro']);
                Permission::create(['name' => 'Realizar Asistencias']);
                Permission::create(['name' => 'Personalizar Curso']);
                
                // create roles and assign created permissions
        
                // this can be done as separate statements
                $role = Role::create(['name' => 'En Espera']);

                $role = Role::create(['name' => 'Estudiante']);
                $role->givePermissionTo('Responder Foro');
                $role->givePermissionTo('Realizar Tareas');
                $role->givePermissionTo('Realizar Aportes');
                $role->givePermissionTo('Realizar Asistencias');
        
                // or may be done by chaining
                $role = Role::create(['name' => 'Docente']);
                $role ->givePermissionTo(['Publicar Tareas']);
                $role ->givePermissionTo(['Añadir Estudiantes']);
                $role ->givePermissionTo(['Personalizar Curso']);
                
                $role = Role::create(['name' => 'Coordinador']);
                $role = Role::create(['name' => 'Administrador']);
                $role->givePermissionTo(Permission::all());



            }
    }


