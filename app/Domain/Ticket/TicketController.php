<?php

namespace App\Domain\Ticket;

use App\Domain\Ticket\Repositories\TicketRepository;
use App\Domain\Ticket\Requests\CreateTicketRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class TicketController extends Controller
{
    /**
     * возвращает список тикетов
     *
     * @return View
     */
    public function index(): View
    {
        $tickets = Ticket::all();

        return view("ticket", compact('tickets'));
    }

    /**
     * метод создания заявки
     *
     * @param CreateTicketRequest $request
     * @param TicketRepository $repository
     *
     * @return Application|RedirectResponse|Redirector
     * @throws \Throwable
     */
    public function create(CreateTicketRequest $request, TicketRepository $repository): Application|RedirectResponse|Redirector
    {
        $ticket = $repository->create($request);

        if ($ticket) {
            return redirect(route('user.ticket'));
        }

        return redirect(route('user.ticket'))->withErrors([
            'formError' => 'Произошла ошибка при создании заявки'
        ]);
    }
}
