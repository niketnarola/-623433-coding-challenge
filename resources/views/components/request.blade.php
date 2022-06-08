@forelse ( $requests as $user )
	<div class="my-2 shadow text-white bg-dark p-1 request-panel" id="">
		<div class="d-flex justify-content-between">
			<table class="ms-1">
				<td class="align-middle">
					@if ( $mode == 'sent' )
						{{ $user->receiver_user->name }}
					@else
						{{ $user->sender_user->name }}
					@endif
				</td>
				<td class="align-middle"> - </td>
				<td class="align-middle">
					@if ( $mode == 'sent' )
						{{ $user->receiver_user->email }}
					@else
						{{ $user->sender_user->email }}
					@endif
				</td>
				<td class="align-middle">
			</table>
			<div>
				@if ( $mode == 'sent' )
					<button id="cancel_request_btn_{{ $user->id }}" class="btn btn-danger me-1" onclick="deleteRequest(this, {{ $user->id }})">Withdraw Request</button>
				@else
					<button id="accept_request_btn_{{ $user->id }}" class="btn btn-primary me-1" onclick="acceptRequest(this, {{ $user->id }})">Accept</button>
				@endif
			</div>
		</div>
	</div>
@empty
	<div class="my-2 shadow text-white bg-dark p-1 request-panel" id="">
		<div class="text-center p-2">
			No {{ ($mode == 'sent') ? 'Send Request' : 'Receive Request' }}!
		</div>
	</div>
@endforelse

@if ( $requestsCount >= 10 && $requestsCount > $offset )
	<div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
	    <button class="btn btn-primary" onclick="getMoreRequests(this, {{ $mode == 'sent' ? "'sent_requests'" : "'received_requests'" }});" id="load_more_btn">Load more</button>
	</div>
@endif
