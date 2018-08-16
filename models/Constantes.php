<?php
namespace app\models;
class Constantes{
    const STATUS_CREADA = 1;
    const STATUS_AUTORIZADA_POR_SUPERVISOR = 2;
    const STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL = 3;
    const STATUS_AUTORIZADA_POR_ADMINISTRADOR_CC = 4;
    const STATUS_CANCELADA_SUPERVISOR_CC = 5;
    const STATUS_CANCELADA_SUPERVISOR_TELCEL = 11;
    const STATUS_CANCELADA_ADMINISTRADOR_CC = 12;
    const STATUS_CANCELADA_ADMINISTRADOR_TELCEL = 13;
    const STATUS_CANCELADA_POR_MASTER_BRIGHT_STAR = 17;
    const STATUS_CANCELADA_POR_MASTER_TELCEL = 18;
    const STATUS_CANCELADAS_POR_MASTER_CALL_CENTER = 19;

    const STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL = 7;
    const STATUS_AUTORIZADA_POR_MASTER_BRIGHT_STAR= 14;
    const STATUS_AUTORIZADA_POR_MASTER_TELCEL = 15;
    const STATUS_AUTORIZADA_POR_MASTER_CALL_CENTER = 16;
    
    const STATUS_RECIBIDO_MENSAJERIA = 21;
    const STATUS_LISTO_ENTREGA = 22;
    const STATUS_ANOMALO = 23;
    const STATUS_ENTREGADO = 24;
    const STATUS_CANCELADO = 25;
    const STATUS_NO_ENTREGADO = 26;
    const STATUS_NO_VISITADO = 27;
    const STATUS_PRIMERA_VISITA = 28;
    const STATUS_SEGUNDA_VISITA = 29;
    const STATUS_DEVOLUCION_TRANSITO = 30;
    const STATUS_DEVOLUCION = 31;
    const STATUS_INCIDENCIA = 32;
    const STATUS_REPROGRAMACION= 33;
    const STATUS_NO_ASIGNADO = 34;

    const STATUS_RECHAZO_SUPERVISOR_CC = 35;
    const STATUS_RECHAZO_SUPERVISOR_TELCEL = 36;
    const STATUS_RECHAZO_ADMINISTRADOR_CC = 37;
    const STATUS_RECHAZO_ADMINISTRADOR_TELCEL = 38;
    const STATUS_RECHAZO_POR_MASTER_BRIGHT_STAR = 39;
    const STATUS_RECHAZO_POR_MASTER_TELCEL = 40;
    const STATUS_RECHAZO_POR_MASTER_CALL_CENTER = 41;

    //TIPOS DE USUARIOS
    const USURIO_ADMIN = "admin";
    const USUARIO_CALL_CENTER = "call-center";
    const USUARIO_SUPERVISOR = "supervisor-call-center";
    const USUARIO_SUPERVISOR_TELCEL = "supervisor-tel";
    const USUARIO_ADMINISTRADOR_TELCEL = "administrador-tel";
    const USUARIO_ADMINISTRADOR_CC = "administrador-call-center";
    const USUARIO_MASTER_TELCEL = "master-telcel";
    const USUARIO_MASTER_BRIGHT_STAR = "master-bright-star";
    const USUARIO_MASTER_CALL_CENTER = "master-call-center";

    // Colores status
    const COLOR_STATUS_CREADA = "warning";
    const COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR = "green-light";
    const COLOR_STATUS_AUTORIZADA_POR_SUPERVISOR_TELCEL = "green-medium";
    const COLOR_STATUS_RECHAZADA = "danger";
    const COLOR_STATUS_CANCELADA = "danger";
    const COLOR_STATUS_AUTORIZADA_POR_ADMINISTRADOR_TELCEL = "green";

    // Estatus del api
    const STATUS_API_CREACION = 1;
    const STATUS_API_RECIBIO_MENSAJERIA = 2;
    const STATUS_API_LISTO_ENTREGA = 3;
    const STATUS_API_ANOMALO = 4;
    const STATUS_API_ENTREGADO = 5;
    const STATUS_API_CANCELADO = 6;
    const STATUS_API_NO_ENTREGADO = 7;
    const STATUS_API_NO_VISITADO = 10;
    const STATUS_API_PRIMERA_VISITA = 11;
    const STATUS_API_SEGUNDA_VISITA = 12;
    const STATUS_API_DEVOLUCION_EN_TRANSITO = 13;
    const STATUS_API_DEVUELTO = 14;
    const STATUS_API_INCIDENCIA = 15;
    const STATUS_API_REPROGRAMACION = 16;
    const STATUS_API_NO_ASIGNADO = 17;

    // Tiempo que se puede editar una cita
    const TIEMPO_EDICION = 2;

    // Identificador del cliente
    const IDENTIFICADOR_CLIENTE = "BSMH-";

    // Sin equipo
    const SIN_EQUIPO = 1;

    // tipos de errors
    const CALL_CENTER = "call-center";
    const TELCEL = "telcel";

    // tipos de usuarios excel
    const USUARIO_EXCEL_ADMINISTRADOR = "Administrador";
    const USUARIO_EXCEL_SUPERVISOR = "Supervisor";
    const USUARIO_EXCEL_CALL_CENTER = "Usuario call center";

    // botones acciones de cita
    const BTN_APROBAR = 2;
    const BTN_EDITAR = 3;
    const BTN_CANCELAR = 4;
    const BTN_RECHAZAR = 5;
}