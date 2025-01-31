<button type="button" class="{!! $classes !!}" onclick="confirmMarkclosed({{ $id }})" title="Mark as closed">{!! $html !!}</button>

<form id="_mark-closed-form-{{ $id }}" method="post" action="{{ $route }}" style="display: none;">
    @csrf
</form>

<script>
    function confirmMarkclosed(id) {
        let msg = 'This action cannot be undone. Are you sure you want to mark as closed this?';

        if (confirm(msg) === true) {
            $('#_mark-closed-form-' + id).submit();
        }

        return false;
    }
</script>
