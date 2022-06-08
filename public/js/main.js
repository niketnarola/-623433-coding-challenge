"use strict";

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
	}
});

let contentId = 'content';
let loadingHTML = `
	<div class="d-flex justify-content-center mt-2 py-3" id="load_more_btn_parent">
	    <button class="btn bg-transparent text-white d-flex align-items-center" id="load_more_btn">
	    	<div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div> <span>Loading ...</span>
    	</button>
	</div>
`;

function loadViews( viewName, countElementSelector ) {
	let $contentAppendWrapper = $(document).find(`#${contentId}`);
	
	$.ajax({
		url: `${HOME_URL}/connections`,
		type: 'post',
		dataType: 'json',
		async: false,
		cache: false,
		data: {
			view: viewName,
		},
		beforeSend: () => {
			$contentAppendWrapper.html(loadingHTML);
		},
		complete: response => {
			let resp = response.responseJSON;
			if ( resp ) {
				if ( resp.status ) {
					let $countElementSelector = $(document).find(`${countElementSelector}`);
					if ($contentAppendWrapper.length) {
						$contentAppendWrapper.html(resp.data.html);
						$countElementSelector.html(`(${resp.data.count})`)
					}

				} else {

				}
			} else {
				console.error('Something went wrong ...')
			}
		},
		error: error => {
		},
	});
}

function getRequests( view ) {
	loadViews( view, `#${view}_count` );
}

function getMoreRequests( $this, view ) {
  	let offset = $(document).find('.request-panel').length;
  	$($this).parent()[0].innerHTML = loadingHTML;
  	$.ajax({
  		url: `${HOME_URL}/connections`,
  		type: 'post',
  		dataType: 'json',
  		data: {
  			offset: offset,
  			view: view,
  		},
  		complete: response => {
			let resp = response.responseJSON;
  			if ( resp ) {
  				if ( resp.status ) {
  					let $contentAppendWrapper = $(document).find(`#${contentId}`);
  					let $countElementSelector = $(document).find(`#${view}_count`);

  					if ($contentAppendWrapper.length) {
  						$contentAppendWrapper.append(resp.data.html);
  						$countElementSelector.html(`(${resp.data.count})`)
  					}

  				}
  			} else {
  				console.error('Something went wrong ...')
  			}
  		},
  	}).always(() => {
  		$('#load_more_btn_parent').remove();
  	});
}

function getConnections( view ) {
  	loadViews( view, `#${view}_count` );
}

function getMoreConnections() {
  	let offset = $(document).find('.connection-panel').length;
  	$($this).parent()[0].innerHTML = loadingHTML;
  	$.ajax({
  		url: `${HOME_URL}/connections`,
  		type: 'post',
  		dataType: 'json',
  		data: {
  			offset: offset,
  			view: 'suggestions',
  		},
  		complete: response => {
			let resp = response.responseJSON;
  			if ( resp ) {
  				if ( resp.status ) {
  					let $contentAppendWrapper = $(document).find(`#${contentId}`);
  					let $countElementSelector = $(document).find(`#suggestions_count`);

  					if ($contentAppendWrapper.length) {
  						$contentAppendWrapper.append(resp.data.html);
  						$countElementSelector.html(`(${resp.data.count})`)
  					}

  				}
  			} else {
  				console.error('Something went wrong ...')
  			}
  		},
  	}).always(() => {
  		$('#load_more_btn_parent').remove();
  	});
}

function getConnectionsInCommon(userId, connectionId) {
  	// your code here...
}

function getMoreConnectionsInCommon(userId, connectionId) {
  	// Optional: Depends on how you handle the "Load more"-Functionality
  	// your code here...
}

function getSuggestions( view ) {
	loadViews( view, `#${view}_count` );
}

function getMoreSuggestions( $this ) {
  	let offset = $('.suggestion-panel').length;
  	$($this).parent()[0].innerHTML = loadingHTML;
  	$.ajax({
  		url: `${HOME_URL}/connections`,
  		type: 'post',
  		dataType: 'json',
  		data: {
  			offset: offset,
  			view: 'suggestions',
  		},
  		complete: response => {
			let resp = response.responseJSON;
  			if ( resp ) {
  				if ( resp.status ) {
  					let $contentAppendWrapper = $(document).find(`#${contentId}`);
  					let $countElementSelector = $(document).find(`#suggestions_count`);

  					if ($contentAppendWrapper.length) {
  						$contentAppendWrapper.append(resp.data.html);
  						$countElementSelector.html(`(${resp.data.count})`)
  					}

  				}
  			} else {
  				console.error('Something went wrong ...')
  			}
  		},
  	}).always(() => {
  		$('#load_more_btn_parent').remove();
  	});
}

function sendRequest( $this, userId ) {
	$this.attr('disabled', 'disabled');
  	$.ajax({
  		url: `${HOME_URL}/connect`,
  		type: 'post',
  		dataType: 'json',
  		async: false,
  		cache: false,
  		data: {
  			receiverId: userId
  		},
  		beforeSend: () => {
  			$this.attr('disabled', 'disabled');
  		},
  		complete: response => {
  			let resp = response.responseJSON;
  			if (resp) {
  				if ( resp.status ) {
		  			$this.parents('.suggestion-panel').fadeOut('fast', function() {
		  				$this.parents('.suggestion-panel').remove();
		  			});
		  			$(document).find('#suggestions_count').html(`(${resp.data.suggestionsCount})`);
		  			$(document).find('#sent_requests_count').html(`(${resp.data.sentRequestsCount})`);
  				} else {

  				}
  			}
  		},
  		error: error => {
			$this.removeAttr('disabled');
  		},
  	});
}

function deleteRequest($this, requestId) {
  	if (confirm('Are you sure you want to withdraw the connection')) {
  		$.ajax({
  			url: `${HOME_URL}/remove-request`,
  			type: 'post',
  			dataType: 'json',
  			async: false,
  			cache: false,
  			data: {
  				requestId: requestId,
  			},
  			beforeSend: () => {
  				$($this).attr('disabled', 'disabled');
  			},
  			complete: response => {
  				let resp = response.responseJSON;
  				if ( resp ) {
  					if ( resp.status ) {
  						$($this).parents('.request-panel').fadeOut('fast', function() {
  							$($this).parents('.request-panel').remove();
  						});
  						$(document).find('#suggestions_count').html(`(${resp.data.suggestionsCount})`);
  						$(document).find('#sent_requests_count').html(`(${resp.data.sentRequestsCount})`);
  					}
  				}
  			},
  			error: error => {

  			},
  		});
  	}
}

function acceptRequest($this, requestId) {
	$($this).attr('disabled', 'disabled');
  	$.ajax({
  		url: `${HOME_URL}/accept-request`,
  		type: 'post',
  		dataType: 'json',
  		async: false,
  		cache: false,
  		data: {
  			requestId: requestId,
  		},
  		beforeSend: () => {
  			$($this).attr('disabled', 'disabled');
  		},
  		complete: response => {
  			let resp = response.responseJSON;
  			if ( resp ) {
  				if ( resp.status ) {
  					$($this).parents('.request-panel').fadeOut('slow', function() {
  						$($this).parents('.request-panel').remove();
  					});
  					loadViews( 'received_requests', `#received_requests_count` );
  				}
  			}
  		},
  		error: error => {},
  	});
}

function removeConnection( $this, requestId ) {
  	if (confirm('Are you sure you want to remove this connection?')) {
  		$($this).attr('disabled', 'disabled');
  		$.ajax({
  			url: `${HOME_URL}/remove-connection`,
  			type: 'post',
  			dataType: 'json',
  			async: false,
  			cache: false,
  			data: {
  				requestId: requestId,
  			},
  			beforeSend: () => {
  				$($this).attr('disabled', 'disabled');
  			},
  			complete: response => {
  				let resp = response.responseJSON;
  				if ( resp ) {
  					if ( resp.status ) {
  						$($this).parents('.request-panel').fadeOut('slow', function() {
  							$($this).parents('.request-panel').remove();
  						});
  						loadViews( 'connections', `#connections_count` );
  					}
  				}
  			},
  			error: error => {},
  		});
  	}
}

$(document).on('click', '.connect-user', function(event) {
	event.preventDefault();
	let senderId = $(this).attr('data-id');
	sendRequest($(this), senderId);
});

$(function () {
  	loadViews( 'suggestions', '#suggestions_count' );
});