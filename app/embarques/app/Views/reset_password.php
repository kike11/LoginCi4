<?= $this->extend('layout/template')?>

<?= $this->section('content'); ?>
 <div class="card shadow-lg form-signin">
            <div class="card-body p-5">
                <h1 class="fs-4 card-title fw-bold mb-4">Restablecer la contraseña</h1>
                <form method="POST" action="<?= base_url('password/reset'); ?>" autocomplete="off">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <div class="mb-3">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="repassword">Confirmar Contraseña</label>
                        <input type="password" class="form-control" name="repassword" id="repassword" required>
                    </div>

                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary ms-auto">
                            Restablecer contraseña
                        </button>
                    </div>
                </form>
                <?php if (session()->getFlashdata('errors')!== null): ?>
                  <div class="alert alert-danger my-3" role="alert" >
                    <?= session()->getFlashdata('errors') ?>
                  </div> 
                <?php endif; ?>
            </div>
        </div>

<?= $this->endSection(); ?>