@php
    $isEdit = request()?->page_type === 'edit';
@endphp

<pre-crud-select card_title="Categoria" title="Selecione a categoria" field="category_code"
    description="Selecione a categoria do produto que deseja {{ $isEdit ? 'editar' : 'cadastrar' }}"
    model="App\Http\Models\Category" btn_text="{{ $isEdit ? 'Alterar' : 'Prosseguir' }}"
    postback="{{ $isEdit ? '/admin/products/' . $content?->code . '/set-category' : '/admin/products/create' }}"
    icon="el-icon-success"></pre-crud-select>
