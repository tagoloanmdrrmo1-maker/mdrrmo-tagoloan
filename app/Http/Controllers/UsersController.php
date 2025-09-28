<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Services\PdfDownloadLoggerService;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('user_id');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_num', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->paginate(10);
        return view('users', compact('users'));
    }

    public function store(UserFormRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => $validated['role'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'contact_num' => $validated['contact_num'],
                ]);
            });

            return $this->jsonOrRedirect($request, 'User created successfully.', 'users');

        } catch (ValidationException $e) {
            return $this->handleValidationError($request, $e);
        } catch (\Exception $e) {
            return $this->handleGeneralError($request, 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    public function update(UserFormRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($user, $validated) {
                $userData = [
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'contact_num' => $validated['contact_num'],
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $user->update($userData);
            });

            return redirect()->route('users')->with('status', 'User updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the user.');
        }
    }

    // Check if username already exists
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $excludeUserId = $request->input('exclude_user_id');

        $query = User::where('username', $username);
        
        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    // Check if email already exists
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $excludeUserId = $request->input('exclude_user_id');

        $query = User::where('email', $email);
        
        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = User::query();
            
            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('role', 'LIKE', "%{$search}%")
                      ->orWhere('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('middle_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('contact_num', 'LIKE', "%{$search}%");
                });
            }
            
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }
            
            $users = $query->paginate(10);

            // Log PDF download
            (new PdfDownloadLoggerService())->logPdfDownload(
                PdfDownloadLoggerService::getReportTypes()['users'],
                $request->only(['search', 'role'])
            );

            $pdf = app('dompdf.wrapper')->loadView('pdf.users', compact('users'))
                ->setPaper('a4', 'portrait');

            return $pdf->download('users_report_' . now()->format('Y-m-d_H-i-s') . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function jsonOrRedirect(Request $request, string $message, string $route)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route($route)->with('status', $message);
    }

    private function handleValidationError(Request $request, ValidationException $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        }
        throw $e;
    }

    private function handleGeneralError(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 500);
        }
        return redirect()->back()->with('error', 'An error occurred while creating the user.');
    }
}