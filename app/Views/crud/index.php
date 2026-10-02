<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div class="eyebrow">DATA MANAGEMENT</div><h1><?= esc($title) ?></h1><p>Kelola data operasional dan konfigurasi sistem secara terpusat.</p></div>
<?php if(session('errors')): ?><div class="alert alert-danger"><?php foreach(session('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach ?></div><?php endif ?>
<div class="row g-4"><div class="col-lg-4"><div class="card"><div class="card-header fw-semibold" id="formTitle">Tambah Data</div><div class="card-body">
<form method="post" action="/admin/<?= esc($type) ?>/save" id="crudForm"><?= csrf_field() ?><input type="hidden" name="id" id="field-id">
<?php foreach($config['fields'] as $name=>$label): ?><div class="mb-3"><label class="form-label"><?= esc($label) ?></label>
<?php if($name==='role'): ?><select class="form-select" name="role"><option value="staff">staff</option><option value="admin">admin</option></select>
<?php elseif($name==='is_active'): ?><select class="form-select" name="is_active"><option value="1">Ya</option><option value="0">Tidak</option></select>
<?php else: ?><input class="form-control" name="<?= esc($name) ?>" value="<?= esc(old($name)) ?>" <?= str_contains($name,'time')?'type="time"':'' ?> required><?php endif ?></div><?php endforeach ?>
<?php if($type==='staff'): ?><div class="mb-3"><label class="form-label">Password awal</label><input class="form-control" type="password" name="password" placeholder="Default ChangeMe123!"></div><?php endif ?>
<button class="btn btn-primary">Simpan</button> <button type="button" class="btn btn-light" onclick="this.form.reset();document.getElementById('field-id').value='';document.getElementById('formTitle').textContent='Tambah Data'">Reset</button></form></div></div></div>
<div class="col-lg-8"><div class="card"><div class="table-responsive"><table class="table table-sm table-hover mb-0"><thead><tr><th>ID</th><?php foreach($config['fields'] as $label): ?><th><?= esc($label) ?></th><?php endforeach ?><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= $r['id'] ?></td><?php foreach($config['fields'] as $field=>$label): ?><td><?= esc($r[$field]??'-') ?></td><?php endforeach ?><td class="text-nowrap"><button type="button" class="btn btn-outline-primary btn-sm edit-row" data-row="<?= esc(json_encode($r), 'attr') ?>">Edit</button> <form class="d-inline" method="post" action="/admin/<?= esc($type) ?>/delete/<?= $r['id'] ?>" onsubmit="return confirm('Hapus data ini?')"><?= csrf_field() ?><button class="btn btn-outline-danger btn-sm">Hapus</button></form></td></tr><?php endforeach ?>
</tbody></table></div></div></div></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?><script>document.querySelectorAll('.edit-row').forEach(b=>b.onclick=()=>{const r=JSON.parse(b.dataset.row),f=document.getElementById('crudForm');document.getElementById('field-id').value=r.id;Object.keys(r).forEach(k=>{const el=f.elements[k];if(el)el.value=r[k]??''});document.getElementById('formTitle').textContent='Edit Data #'+r.id;scrollTo({top:0,behavior:'smooth'})});</script><?= $this->endSection() ?>
