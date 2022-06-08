
@forelse ( $connections as $user )
	<div class="my-2 shadow text-white bg-dark p-1 connection-panel" id="">
		<div class="d-flex justify-content-between">
			<table class="ms-1">
				<td class="align-middle">{{ $user->receiver_user->name }}</td>
				<td class="align-middle"> - </td>
				<td class="align-middle">{{ $user->receiver_user->email }}</td>
				<td class="align-middle">
			</table>
			<div>
				<button style="width: 220px" id="get_connections_in_common_{{ $user->id }}" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $user->id }}" aria-expanded="false" aria-controls="collapseExample">
					Connections in common (0)
				</button>
				<button id="create_request_btn_{{ $user->id }}" class="btn btn-danger me-1" onclick="removeConnection(this, {{ $user->id }})">Remove Connection</button>
			</div>
		</div>
		<div class="collapse" id="collapse_{{ $user->id }}">
			<div id="content_" class="p-2">
				{{-- Display data here --}}
				<x-connection_in_common />
			</div>
			<div id="connections_in_common_skeletons_{{ $user->id }}">
				{{-- Paste the loading skeletons here via Jquery before the ajax to get the connections in common --}}
			</div>
			<div class="d-flex justify-content-center w-100 py-2">
				<button class="btn btn-sm btn-primary" id="load_more_connections_in_common_{{ $user->id }}">Load more</button>
			</div>
		</div>
	</div>
@empty
	<div class="my-2 shadow text-white bg-dark p-1 request-panel" id="">
		<div class="text-center p-2">
			No connections!
		</div>
	</div>
@endforelse

@if ( $connectionsCount >= 10 && $connectionsCount > $offset )
	<div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
	    <button class="btn btn-primary" onclick="getMoreConnections(this);" id="load_more_btn">Load more</button>
	</div>
@endif
