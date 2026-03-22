<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchedulingRequest;
use App\Services\SchedulingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    private SchedulingService $schedulingService;

    public function __construct(SchedulingService $schedulingService)
    {
        $this->schedulingService = $schedulingService;
    }

    /**
     * List all schedulings, with filtering via query params
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['date', 'client_id']);
        $schedules = $this->schedulingService->listSchedules($filters, $request->input('per_page', 15));

        return response()->json($schedules);
    }

    /**
     * Create a new scheduling for the authenticated client
     */
    public function store(StoreSchedulingRequest $request): JsonResponse
    {
        try {
            $scheduling = $this->schedulingService->createSchedule($request->user(), $request->validated());

            return response()->json([
                'message' => 'Agendamento criado com sucesso.',
                'scheduling' => $scheduling
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar agendamento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(\App\Models\Scheduling $scheduling): JsonResponse
    {
        $scheduling->load('client.user');
        return response()->json($scheduling);
    }

    public function update(Request $request, \App\Models\Scheduling $scheduling): JsonResponse
    {
        $data = $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $scheduling->update($data);
        return response()->json(['message' => 'Agendamento atualizado.', 'scheduling' => $scheduling]);
    }

    public function destroy(\App\Models\Scheduling $scheduling): JsonResponse
    {
        $scheduling->delete();
        return response()->json(['message' => 'Agendamento deletado.']);
    }
}
