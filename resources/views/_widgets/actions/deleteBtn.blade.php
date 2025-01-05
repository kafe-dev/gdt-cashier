<button type="button" class="{!! $classes !!}" onclick="confirmRemove({{ $id }})" title="Delete">{!! $html !!}</button>

<form id="_delete-form-{{ $id }}" method="post" action="{{ $route }}" style="display: none;">
    @csrf
</form>

<script>
    function confirmRemove(id) {
        let msg = 'This action cannot be undone. Are you sure you want to remove this?';

        if (confirm(msg) === true) {
            $('#_delete-form-' + id).submit();
        }

        return false;
    }
</script>
