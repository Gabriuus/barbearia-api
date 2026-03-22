<?php

namespace App\Services;

use App\Models\Scheduling;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SchedulingService
{
    /**
     * @param User $user
     * @param array $data
     * @return Scheduling
     * @throws ValidationException
     */
    public function createSchedule(User $user, array $data): Scheduling
    {
        if (!$user->client) {
            throw ValidationException::withMessages([
                'client' => ['O usuário logado não possui um perfil de cliente.'],
            ]);
        }

        // Verify overlapping times across ALL schedulings
        $conflict = Scheduling::where(function ($query) use ($data) {
            $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                  ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                  ->orWhere(function ($q) use ($data) {
                      $q->where('start_date', '<=', $data['start_date'])
                        ->where('end_date', '>=', $data['end_date']);
                  });
        })->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'schedule' => ['Já existe um agendamento conflitante neste horário.'],
            ]);
        }

        $scheduling = Scheduling::create([
            'client_id' => $user->client->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        \App\Jobs\SendSchedulingNotificationJob::dispatch($scheduling);

        return $scheduling;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listSchedules(array $filters = [], int $perPage = 15)
    {
        $query = Scheduling::with('client.user');

        if (!empty($filters['date'])) {
            $query->whereDate('start_date', $filters['date']);
        }

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }
        
        $query->orderBy('start_date', 'asc');

        return $query->paginate($perPage);
    }
}
