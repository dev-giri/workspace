<?php

namespace Modules\Chat\Http\Controllers;

use Modules\Chat\Events\MessageSendEvent;
use Modules\Chat\Http\Requests\SendMessageRequest;
use Modules\Chat\Entities\Message;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    public function user_list()
    {
        $users = User::latest()->where( 'id', '!=', auth()->user()->id )->get();

        if ( Request::wantsJson() ) {
            return response()->json( $users, 200 );
        }

        return abort( 404 );
    }

    public function user_message( $id )
    {
        $user = User::findOrFail( $id );
        $messages = $this->message_by_user_id( $id );

        if ( Request::wantsJson() ) {
            return response()->json( [
                'messages' => $messages,
                'user'     => $user,
            ] );
        }

        return abort( 404 );
    }

    public function send_message( SendMessageRequest $request )
    {
        if ( !Request::wantsJson() ) {
            return abort( 404 );
        }

        $messages = Message::create( [
            'message' => $request->message,
            'from'    => auth()->user()->id,
            'to'      => $request->user_id,
            'type'    => false,
        ] );

        $messages = Message::create( [
            'message' => $request->message,
            'from'    => auth()->user()->id,
            'to'      => $request->user_id,
            'type'    => true,
        ] );

        broadcast( new MessageSendEvent( $messages ) );

        return response()->json( $messages, 201 );
    }

    public function delete_single_message( $message_id )
    {
        if ( !Request::wantsJson() ) {
            return abort( 404 );
        }
        Message::findOrFail( $message_id )->delete();

        return response( 'Deleted', 200 );
    }

    public function delete_all_message( $user_id )
    {
        if ( !Request::wantsJson() ) {
            return abort( 404 );
        }
        $messages = $this->message_by_user_id( $user_id );
        foreach ( $messages as $message ) {
            Message::findOrFail( $message->id )->delete();
        }

        return response()->json( 'All Message Deleted', 200 );
    }

    public function message_by_user_id( $id )
    {
        $messages = Message::where( function ( $q ) use ( $id ) {
            $q->where( 'from', auth()->user()->id );
            $q->where( 'to', $id );
            $q->where( 'type', 0 );
        } )->orWhere( function ( $q ) use ( $id ) {
            $q->where( 'from', $id );
            $q->where( 'to', auth()->user()->id );
            $q->where( 'type', 1 );
        } )->with( 'user' )->get();

        return $messages;
    }
}