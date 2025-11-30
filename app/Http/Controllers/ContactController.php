<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Aquí puedes guardar en la base de datos o enviar un correo
        // Ejemplo de envío de correo:
        try {
            Mail::to('hernandezgomezd606@gmail.com')->send(new ContactFormMail($validated));
            return back()->with('success', '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.');
        } catch (\Exception $e) {
            \Log::error('Error al enviar el correo: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.');
        }
    }
}