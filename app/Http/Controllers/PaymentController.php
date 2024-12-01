<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function gestionCestas($id)
    {
        $cestas = new UserCestasController();

        $cestas->cerrarCesta($id);

        $data = new Request([
            "usuario_id" => $id,
            "total" => 0,
            "estado" => "abierta"
        ]);

        $cestas->anadirCesta($data);
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $usuarioId = $request->input('usuarioId');

            // Validar que usuarioId no es nulo
            if (is_null($usuarioId)) {
                return response()->json(['error' => 'El usuarioId es requerido'], 400);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->input('amount'),
                'currency' => 'eur',
                'payment_method_types' => ['card'],
            ]);

            if (!empty($paymentIntent->client_secret)) {
                $this->gestionCestas($usuarioId);
            }

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
