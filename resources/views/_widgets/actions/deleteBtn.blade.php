<button type="button" class="{!! $classes !!}" onclick="confirmRemove({{ $id }})" title="Delete">{!! $html !!}</button>

<form id="_delete-form-{{ $id }}" method="post" action="{{ $route }}" style="display: none;">
    @csrf
</form>
