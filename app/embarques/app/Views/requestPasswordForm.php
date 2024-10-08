<?= $this->extend('layout/template') ?>

<? $this->section('content'); ?>
<div class="card shadow-lg form-signin">
            <div class="card-body p-5">
                <h1 class="fs-4 card-title fw-bold mb-4">Has olvidado tu contraseña</h1>
                <form method="POST" action="<?= base_url('password-email'); ?>" autocomplete="off">

                    <div class="mb-3">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" required autofocus>
                    </div>

                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary ms-auto">
                            Enviar enlace
                        </button>
                    </div>
                </form>
                <?php  if(session()->getFlashdata('errors') !== null): ?>
                  <div class="alert alert-danger my-3" role="alert" >
                      <?= session()->getFlashdata('errors') ?>
                  </div>
                <?php endif ?>
            </div>

            <div class="card-footer py-3 border-0">
                <div class="text-center">
                    <a href="<?= base_url() ?>">Iniciar sesión</a>
                </div>
            </div>
        </div>
<? $this->endSection(); ?>