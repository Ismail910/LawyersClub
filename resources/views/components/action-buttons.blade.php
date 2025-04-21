<div class="btn-group" role="group" aria-label="Actions">
    @isset($showRoute)
        <a href="{{ route($showRoute, $row->id) }}" class="btn btn-sm btn-info" title="@lang('translation.show')">
            <i class="fas fa-eye"></i>
        </a>
    @endisset

    @isset($editRoute)
        <a href="{{ route($editRoute, $row->id) }}" class="btn btn-sm btn-primary" title="@lang('translation.edit')">
            <i class="fas fa-edit"></i>
        </a>
    @endisset

    @isset($deleteRoute)
        <form action="{{ route($deleteRoute, $row->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger  delete-btn delete-object" title="@lang('translation.delete')">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endisset
</div>
