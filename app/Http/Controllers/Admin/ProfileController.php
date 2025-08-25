<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Actualiza el perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500',
        ]);

        // Actualizar avatar si se proporcionó uno nuevo
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Muestra el formulario para cambiar la contraseña
     */
    public function showChangePassword()
    {
        return view('admin.profile.change-password');
    }

    /**
     * Actualiza la contraseña del usuario
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual es incorrecta',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Contraseña actualizada exitosamente');
    }

    /**
     * Muestra el manual de usuario
     */
    public function showManual()
    {
        return view('admin.support.manual');
    }

    /**
     * Muestra el formulario de contacto de soporte
     */
    public function showSupport()
    {
        return view('admin.support.contact');
    }

    /**
     * Envía un mensaje de soporte
     */
    public function sendSupportMessage(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachments.*' => 'nullable|file|max:5120', // Max 5MB por archivo
        ]);

        // Procesar archivos adjuntos
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments', 'public');
                $attachments[] = $path;
            }
        }

        // Enviar correo al equipo de soporte
        \Mail::to(config('mail.support_email'))->send(new \App\Mail\SupportRequest(
            Auth::user(),
            $validated['subject'],
            $validated['message'],
            $validated['priority'],
            $attachments
        ));

        return redirect()->route('admin.support.contact')
            ->with('success', 'Mensaje enviado exitosamente');
    }

    /**
     * Muestra la información sobre el sistema
     */
    public function about()
    {
        $systemInfo = [
            'version' => config('app.version'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database' => config('database.connections.' . config('database.default') . '.driver'),
            'environment' => config('app.env'),
        ];

        return view('admin.support.about', compact('systemInfo'));
    }
}