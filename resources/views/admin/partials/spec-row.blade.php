<div class="spec-row" data-row>
    <x-admin.field :name="'specifications['.$index.'][label]'" label="Parameter" :value="$row['label'] ?? ''" required />
    <x-admin.field :name="'specifications['.$index.'][value]'" label="Nilai / Satuan" :value="$row['value'] ?? ''" required />
    <button type="button" class="a-icon danger" data-remove-row title="Hapus spesifikasi" aria-label="Hapus spesifikasi"><x-icon name="trash-2" /></button>
</div>
