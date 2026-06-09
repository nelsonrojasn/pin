<?php

class SetupPage
{

    private function createTable(string $name, string $query) {
        db_exec("CREATE TABLE IF NOT EXISTS $name($query)"); 
        echo "Table '$name' created or already exists.<br>";
    }

    public function index()
    {
        if (!defined('ALLOW_SETUP') || ALLOW_SETUP !== true) {
            throw new Exception("El instalador está desactivado por seguridad.", 403);
        }

        load_partial("templates/header");
        echo "<h1>Instalador</h1>";

        $this->createTable('company',
        'id integer primary key AUTOINCREMENT,
        name text not null,
        status text default "active",
        created_at text');

        $this->createTable('user', 
        'id integer primary key AUTOINCREMENT,
        company_id int not null,
        login text not null unique, 
        salt text,
        password_hash text, 
        name text,
        created_at text,
        updated_at text');
        
        $this->createTable('profile', 
        'id integer primary key AUTOINCREMENT,
        name text not null unique,
        created_at text,
        updated_at text');

        $this->createTable('resource',
        'id integer primary key AUTOINCREMENT,
        name text,
        page text not null,
        action text not null,
        UNIQUE(page, action)');

        $this->createTable('profile_resource',
        'id integer primary key AUTOINCREMENT,
        profile_id int not null,
        resource_id int not null,
        created_at text');
        
        $this->createTable('profile_user', 
        'id integer primary key AUTOINCREMENT,
        user_id int not null,
        profile_id not null,
        created_at text,
        updated_at text');
        
        // 1. Asegurar Empresa Inicial
        $company_exists = db_get_scalar("SELECT count(*) FROM company WHERE id = 1;");
        if ($company_exists === 0) {
            db_insert("company", ['name' => 'Empresa Maestra', 'created_at' => date('Y-m-d H:i:s')]);
            echo "Default company created.<br>";
        }

        // 2. Asegurar Usuario Admin
        $admin_exists = db_get_scalar("SELECT count(*) FROM user WHERE login = 'admin';");
        if ($admin_exists === 0) {
            $salt = rand(99, 999) . date('YmdHis');
            $data = [
                'id' => null,
                'company_id' => 1,
                'login' => 'admin',
                'name' => 'admin',
                'salt' => $salt,
                'password_hash' => password_hash( $salt . 'pin', PASSWORD_BCRYPT, ['cost' => 12]),
                'created_at' => date('Y-m-d H:i:s')
            ];
            db_insert("user", $data);
            echo "Default user 'admin' has been created.<br>";
        }	

        // 3. Asegurar Perfil Admin
        $profile_exists = db_get_scalar("SELECT count(*) FROM profile WHERE name = 'admin';");
        if ($profile_exists === 0) {
            db_insert("profile", ['name' => 'admin', 'created_at' => date('Y-m-d H:i:s')]);
            echo "Profile 'admin' created.<br>";
        }

        // 4. Asegurar Recurso Maestro (*.*)
        $resource_exists = db_get_scalar("SELECT count(*) FROM resource WHERE page = '*' AND action = '*';");
        if ($resource_exists === 0) {
            db_insert("resource", ['name' => 'Acceso Total', 'page' => '*', 'action' => '*']);
            echo "Master resource created.<br>";
        }

        // 5. Vincular Perfil con Recurso (Permiso)
        $link_res_exists = db_get_scalar("SELECT count(*) FROM profile_resource WHERE profile_id = 1 AND resource_id = 1;");
        if ($link_res_exists === 0) {
            db_insert("profile_resource", ['profile_id' => 1, 'resource_id' => 1, 'created_at' => date('Y-m-d H:i:s')]);
            echo "Profile admin linked to master resource.<br>";
        }

        // 6. Vincular Usuario con Perfil
        $link_exists = db_get_scalar("SELECT count(*) FROM profile_user WHERE user_id = 1 AND profile_id = 1;");
        if ($link_exists === 0) {
            db_insert("profile_user", ['user_id' => 1, 'profile_id' => 1, 'created_at' => date('Y-m-d H:i:s')]);
            echo "User 'admin' linked to profile 'admin'.<br>";
        }

        load_partial("templates/footer");
    }
}