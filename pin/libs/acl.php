<?php

function has_permission(string $page, string $action): bool
{
    $user_id = session_get('user_id');
    if (!$user_id) {
        return false;
    }

    // El primer usuario (ID 1) es el Dios de este sistema
    if ($user_id == 1) {
        return true;
    }

    // Verificamos si existe una relación entre el usuario y el recurso solicitado
    // a través de cualquiera de sus perfiles asignados.
    $has_access = db_get_scalar("
        SELECT COUNT(*) 
        FROM profile_user pu
        JOIN profile_resource pr ON pu.profile_id = pr.profile_id
        JOIN resource r ON pr.resource_id = r.id
        WHERE pu.user_id = :user_id
        AND (
            (r.page = :page AND r.action = :action) OR 
            (r.page = :page AND r.action = '*') OR
            (r.page = '*' AND r.action = '*')
        )", [
            'user_id' => $user_id,
            'page'    => $page,
            'action'  => $action
        ]);

    return (int)$has_access > 0;
}