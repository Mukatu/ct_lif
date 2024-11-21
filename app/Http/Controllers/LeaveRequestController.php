<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Notifications\LeaveRequestSubmitted;
use App\Notifications\LeaveRequestStatusUpdated;

class LeaveRequestController extends Controller
{
    /**
     * Afficher la liste des demandes de congés
     */
    public function index(): JsonResponse
    {
        $leaveRequests = LeaveRequest::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $leaveRequests
        ]);
    }

    /**
     * Créer une nouvelle demande de congé
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:paid,unpaid,sick,other',
            'reason' => 'required|string|min:10'
        ]);

        $leaveRequest = LeaveRequest::create([
            'user_id' => auth()->id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);

        // Notifier les RH
        $hrUsers = User::role('hr')->get();
        foreach ($hrUsers as $hrUser) {
            $hrUser->notify(new LeaveRequestSubmitted($leaveRequest));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de congé soumise avec succès',
            'data' => $leaveRequest
        ], 201);
    }

    /**
     * Mettre à jour le statut d'une demande de congé (pour les RH)
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('update', $leaveRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string'
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
            'hr_comment' => $validated['comment'] ?? null
        ]);

        // Notifier l'employé
        $leaveRequest->user->notify(new LeaveRequestStatusUpdated($leaveRequest));

        return response()->json([
            'status' => 'success',
            'message' => 'Statut de la demande mis à jour',
            'data' => $leaveRequest
        ]);
    }

    /**
     * Annuler une demande de congé
     */
    public function cancel(LeaveRequest $leaveRequest): JsonResponse
    {
        $this->authorize('cancel', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible d\'annuler une demande déjà traitée'
            ], 400);
        }

        $leaveRequest->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Demande de congé annulée avec succès'
        ]);
    }

    /**
     * Obtenir les statistiques des congés
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_days_taken' => LeaveRequest::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->whereYear('start_date', date('Y'))
                ->sum('duration'),
            'pending_requests' => LeaveRequest::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->count(),
            'approved_requests' => LeaveRequest::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->count()
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}