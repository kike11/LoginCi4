<?= $this->extend('layout/template')?>

<?= $this->section('content'); ?>
 <h1>Bienvenido</h1>
 <a href="<?= base_url('logout') ?>" class="btn btn-primary"> cerrar</a>

<?= $this->endSection(); ?>