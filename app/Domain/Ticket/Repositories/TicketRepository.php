<?php

namespace App\Domain\Ticket\Repositories;

use App\Domain\Ticket\Requests\CreateTicketRequest;
use App\Domain\Ticket\Ticket;
use App\Domain\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class TicketRepository
{
    /**
     * метод создания тикета
     * @param CreateTicketRequest $input
     *
     * @return Ticket
     * @throws Throwable
     */
    public function create(CreateTicketRequest $input): Ticket
    {
        DB::beginTransaction();

        try {
            if ($input->hasFile('image')) {
                $image = $input->file('image');
                $path = $image->store('images');
            }

            $ticket = Ticket::create([
                'title'       => $input->title,
                'description' => $input->description,
                'user_id'     => Auth::id(),
                'image_url'   => $path ?? null
            ]);

            $currentUser = User::where('id', Auth::id())->first();
            $currentUser->last_ticket = Carbon::now();
            $currentUser->save();

            DB::commit();

            return $ticket->refresh();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
