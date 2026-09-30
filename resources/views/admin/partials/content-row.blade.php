<fieldset class="content-row" data-row><legend>{{ $title }}</legend><div class="content-row-top"><x-admin.field :name="'items['.$index.'][name]'" label="Nama" :value="$row['name'] ?? ''" required /><button type="button" class="a-icon danger" data-remove-row title="Hapus item" aria-label="Hapus item"><x-icon name="trash-2" /></button></div>
@if($section === 'legal')
    <x-admin.field :name="'items['.$index.'][value]'" label="Nomor / Informasi dokumen" :value="$row['value'] ?? ''" /><x-admin.toggle :name="'items['.$index.'][public]'" label="Publikasikan informasi dokumen di website" :value="$row['public'] ?? false" />
@else
    <x-admin.field :name="'items['.$index.'][description]'" label="Deskripsi" type="textarea" rows="3" :value="$row['description'] ?? ''" required />
@endif
@if(in_array($section, ['focus', 'solutions']))<x-admin.field :name="'items['.$index.'][icon]'" label="Ikon" type="select" :options="array_combine($icons, $icons)" :value="$row['icon'] ?? 'cog'" />@endif
@if($section === 'solutions')<fieldset class="product-choices"><legend>Produk terkait</legend>@foreach($products as $choice)<label><input type="checkbox" name="items[{{ $index }}][product_ids][]" value="{{ $choice->id }}" @checked(in_array($choice->id, $row['product_ids'] ?? []))>{{ $choice->name }}@unless($choice->published)<small>Draft</small>@endunless</label>@endforeach</fieldset>@endif
</fieldset>
