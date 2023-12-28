@if ($content?->id)
    <div class="flex justify-end mt-2">
        <a href="/admin/products/{{ $content->code }}/edit?action=edit_category" class="btn btn-sm btn-outline-secondary">
            <small>
                <i class="el-icon-edit mr-2"></i>
                Editar categoria
            </small>
        </a>
    </div>
@endif
