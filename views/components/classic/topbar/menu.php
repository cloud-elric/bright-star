<?php

use yii\helpers\Url;
use app\models\Constantes;
use app\modules\ModUsuarios\models\EntUsuarios;
$usuario = EntUsuarios::getUsuarioLogueado();
?>
<div class="site-menubar site-menubar-light">
  <div class="site-menubar-body">
    <div>
      <div>
        <ul class="site-menu" data-plugin="menu">
          <li class="site-menu-category">General</li>
          <li class="dropdown site-menu-item">
            <a data-toggle="dropdown" href="<?=Url::base()?>" data-dropdown-toggle="false">
              <i class="site-menu-icon pe-7s-edit" aria-hidden="true"></i>
              <span class="site-menu-title">Dashboard</span>
            </a>
          </li>
          
          
          <li class="dropdown site-menu-item has-sub">
            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
              <i class="site-menu-icon pe-7s-headphones" aria-hidden="true"></i>
              <span class="site-menu-title">Citas</span>
              <span class="site-menu-arrow"></span>
            </a>
            <div class="dropdown-menu">
              <div class="site-menu-scroll-wrap is-list">
                <div>
                  <div>
                    <ul class="site-menu-sub site-menu-normal-list">
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/citas">
                          <span class="site-menu-title">
                            <i class="site-menu-icon pe-7s-bookmarks" aria-hidden="true"></i>
                            Listado de citas</span>
                        </a>
                      </li>
                      <?php
                      if(\Yii::$app->user->can(Constantes::USUARIO_CALL_CENTER)){?>
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/citas/create">
                          <span class="site-menu-title">
                            <i class="site-menu-icon pe-7s-plus" aria-hidden="true"></i>
                            Agregar cita
                          </span>
                        </a>
                      </li>
                      <?php
                      }
                      ?>

                      <?php
                      if(\Yii::$app->user->can(Constantes::USUARIO_SUPERVISOR)){?>
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/citas/exportar">
                          <span class="site-menu-title">
                            <i class="site-menu-icon pe-7s-download" aria-hidden="true"></i>
                            Descarga de reportes
                          </span>
                        </a>
                      </li>
                      <?php
                      }
                      ?>

                      <?php
                      if(\Yii::$app->user->can(Constantes::USUARIO_MASTER_BRIGHT_STAR)){?>
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/citas/subir-archivos">
                          <span class="site-menu-title">
                            <i class="site-menu-icon pe-7s-upload" aria-hidden="true"></i>
                            Subir evidencias
                          </span>
                        </a>
                      </li>

                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/codigo-postal-disponibilidad">
                          <span class="site-menu-title">
                            <i class="site-menu-icon pe-7s-upload" aria-hidden="true"></i>
                              Habilitar horarios C.P.
                          </span>
                        </a>
                      </li>
                      <?php
                      }
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
          
          <?php
          if(\Yii::$app->user->can(Constantes::USUARIO_ADMINISTRADOR_TELCEL) || $usuario->txt_auth_item == Constantes::USUARIO_ADMINISTRADOR_CC){?>
          <li class="dropdown site-menu-item has-sub">
            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
              <i class="site-menu-icon pe-users" aria-hidden="true"></i>
              <span class="site-menu-title">Usuarios</span>
              <span class="site-menu-arrow"></span>
            </a>
            <div class="dropdown-menu">
              <div class="site-menu-scroll-wrap is-list">
                <div>
                  <div>
                    <ul class="site-menu-sub site-menu-normal-list">
                      <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/usuarios">
                          <span class="site-menu-title">
                          <i class="site-menu-icon pe-7s-users" aria-hidden="true"></i>
                            Listado de usuarios
                            </span>
                        </a>
                      </li>
                     
                      <!-- <li class="site-menu-item">
                        <a class="animsition-link" href="<?=Url::base()?>/usuarios/importar-data">
                          <span class="site-menu-title">
                          <i class="site-menu-icon pe-7s-cloud-upload" aria-hidden="true"></i>
                              Importar usuarios
                          </span>
                        </a>
                      </li> -->
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>
         <?php
          }
         ?>
        </ul>
      </div>
    </div>
  </div>
</div>