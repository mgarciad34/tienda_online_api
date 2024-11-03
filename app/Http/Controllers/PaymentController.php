<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Establece tu clave secreta de Stripe desde la configuración de Laravel
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Crea el Payment Intent con el monto recibido desde el frontend
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->input('amount'), // Monto en céntimos
                'currency' => 'eur',
                'payment_method_types' => ['card'],
            ]);

            // Retorna el client secret al frontend para completar el pago
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            // Maneja los errores de Stripe
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
