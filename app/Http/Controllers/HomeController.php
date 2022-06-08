<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserConnection;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        $suggestionsCount = User::getSuggestions( 0, true );
        $sentRequestsCount = UserConnection::getRequests( 'sender_id', 0, 0, true );
        $receivedRequestsCount = UserConnection::getRequests( 'receiver_id', 0, 0, true );
        $connectionsCount = UserConnection::getRequests( 'sender_id', 1, 0, true );

        $this->data = array(
            'suggestionsCount' => $suggestionsCount,
            'sentRequestsCount' => $sentRequestsCount,
            'receivedRequestsCount' => $receivedRequestsCount,
            'connectionsCount' => $connectionsCount,
        );

        return view('home', $this->data);
    }

    public function getConnections( Request $request ) {
        if ($request->ajax()) {
            
            $userId = auth()->id();
            $offset = $request->offset ?? 0;

            switch ( $request->view ) {
                case 'suggestions':


                    $suggestionsCount = User::getSuggestions( $offset, true );
                    $suggestions = User::getSuggestions( $offset );

                    $this->data = array(
                        'suggestions' => $suggestions,
                        'suggestionsCount' => $suggestionsCount,
                        'offset' => $offset,
                    );

                    $view = view('components.suggestion', $this->data)->render();
                    $count = $suggestionsCount;
                    break;

                case 'sent_requests':

                    $sentRequestsCount = UserConnection::getRequests( 'sender_id', 0, $offset, true );
                    $sentRequests = UserConnection::getRequests( 'sender_id', 0, $offset );
                    
                    $this->data = array(
                        'requestsCount' => $sentRequestsCount,
                        'requests' => $sentRequests,
                        'mode' => 'sent',
                        'offset' => $offset,
                    );

                    $view = view('components.request', $this->data)->render();
                    $count = $sentRequestsCount;
                    break;

                case 'received_requests':

                    $receivedRequestsCount = UserConnection::getRequests( 'receiver_id', 0, $offset, true );
                    $receivedRequests = UserConnection::getRequests( 'receiver_id', 0, $offset );
                    
                    $this->data = array(
                        'requestsCount' => $receivedRequestsCount,
                        'requests' => $receivedRequests,
                        'mode' => 'receive',
                        'offset' => $offset,
                    );

                    $view = view('components.request', $this->data)->render();
                    $count = $receivedRequestsCount;
                    break;
                
                case 'connection':

                    $connectionsCount = UserConnection::getRequests( 'sender_id', 1, $offset, true );
                    $connections = UserConnection::getRequests( 'sender_id', 1, $offset );
                    
                    $this->data = array(
                        'connectionsCount' => $connectionsCount,
                        'connections' => $connections,
                        'offset' => $offset,
                    );

                    $view = view('components.connection', $this->data)->render();
                    $count = $connectionsCount;
                    break;
                
            }


            return response()->json(array(
                'status' => true,
                'data' => array(
                    'html' => $view,
                    'count' => $count,
                ),
            ));
        }
    }

    public function connect( Request $request ) {
        if ( $request->ajax() ) {
            $receiverId = $request->receiverId;
            $user = User::select('name')->where(array('id' => $receiverId))->first();
            $connected = UserConnection::isConnected( $receiverId )->first();
            
            if ( $connected ) {
                $this->data = array(
                    'status' => false,
                    'message' => 'You have already sent the request to ' . $user->name . '.',
                    'data' => array(),
                );
            } else {
                UserConnection::create(array(
                    'receiver_id' => $receiverId,
                    'sender_id' => auth()->id(),
                    'status' => 0,
                    'request_sent_at' => date('Y-m-d h:i:s'),
                ));

                $suggestionsCount = User::getSuggestions( 0, true );
                $sentRequestsCount = UserConnection::getRequests( 'sender_id', 0, 0, true );

                $this->data = array(
                    'status' => true,
                    'message' => 'You have sent the request to ' . $user->name . '.',
                    'data' => array(
                        'suggestionsCount' => $suggestionsCount,
                        'sentRequestsCount' => $sentRequestsCount,
                    ),
                );
            }

            return response()->json($this->data);
        }
    }

    public function removeRequest( Request $request ) {
        if ( $request->ajax() ) {
            $requestId = $request->requestId;
            $user = UserConnection::find($requestId);
            if ( $user ) {
                $user->delete();

                $suggestionsCount = User::getSuggestions( 0, true );
                $sentRequestsCount = UserConnection::getRequests( 'sender_id', 0, 0, true );

                $this->data = array(
                    'status' => true,
                    'message' => 'Request has been withdrawed',
                    'data' => array(
                        'suggestionsCount' => $suggestionsCount,
                        'sentRequestsCount' => $sentRequestsCount,
                    ),
                );
            } else {
                $this->data = array(
                    'status' => false,
                    'message' => 'Something went wrong, connection not found',
                    'data' => array(),
                );
            }

            return response()->json($this->data);
        }
    }

    public function acceptRequest( Request $request ) {
        if ( $request->ajax() ) {
            $requestId = $request->requestId;
            $user = UserConnection::find($requestId);
            if ( $user ) {
                $user->status = 1;
                $user->save();
                $this->data = array(
                    'status' => true,
                    'message' => 'Request has been accepted',
                    'data' => array(),
                );
            } else {
                $this->data = array(
                    'status' => false,
                    'message' => 'Something went wrong, connection not found',
                    'data' => array(),
                );
            }

            return response()->json($this->data);
        }
    }

    public function removeConnection( Request $request ) {
        if ( $request->ajax() ) {
            $requestId = $request->requestId;
            $user = UserConnection::find($requestId);
            if ( $user ) {
                $user->delete();
                $this->data = array(
                    'status' => true,
                    'message' => 'Connection has been removed',
                    'data' => array(),
                );
            } else {
                $this->data = array(
                    'status' => false,
                    'message' => 'Something went wrong, connection not found',
                    'data' => array(),
                );
            }

            return response()->json($this->data);
        }
    }
}
