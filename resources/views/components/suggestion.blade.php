@forelse ( $suggestions as $user )
	<div class="my-2 shadow  text-white bg-dark p-1 suggestion-panel" id="">
		<div class="d-flex justify-content-between">
			<table class="ms-1">
				<td class="align-middle">{{ $user->name }}</td>
				<td class="align-middle"> - </td>
				<td class="align-middle">{{ $user->email }}</td>
				<td class="align-middle"> 
			</table>
			<div>
				<button id="create_request_btn_{{ $user->id }}" class="btn btn-primary me-1 connect-user" data-id="{{ $user->id }}">Connect</button>
			</div>
		</div>
	</div>
@empty
	<div class="my-2 shadow text-white bg-dark p-1 request-panel" id="">
		<div class="text-center p-2">
			No Suggestions!
		</div>
	</div>
@endforelse

@if ( $suggestionsCount >= 10 && $suggestionsCount > $offset )
	<div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
	    <button class="btn btn-primary" onclick="getMoreSuggestions(this);" id="load_more_btn">Load more</button>
	</div>
@endif
