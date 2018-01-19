<?php
namespace app\models;
class Constantes{
    const STATUS_CREADA = 1;
    const STATUS_AUTORIZADA_POR_SUPERVISOR = 2;
    const STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL = 3;
    const STATUS_RECHAZADA = 4;
    const STATUS_CANCELADA = 5;
    const STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL = 7;

    //TIPOS DE USUARIOS
    const USURIO_ADMIN = "admin";
    const USUARIO_CALL_CENTER = "call-center";
    const USUARIO_SUPERVISOR = "supervisor-call-center";
    const USUARIO_SUPERVISOR_TELCEL = "supervisor-tel";
    const USUARIO_ADMINISTRADOR_TELCEL = "administrador-tel";

    // Colores status
    const COLOR_STATUS_CREADA = "warning";
    const COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR = " bg-light-green-500";
    const COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL = " bg-light-green-800";
    const COLOR_STATUS_RECHAZADA = "danger";
    const COLOR_STATUS_CANCELADA = "danger";
    const COLOR_STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL = " bg-green-800";
}