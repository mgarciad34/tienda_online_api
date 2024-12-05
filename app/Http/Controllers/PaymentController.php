<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function gestionCestas($idUsuario, $idCesta)
    {
        $cestas = new UserCestasController();

        $cestas->cerrarCesta($idCesta);

        $data = new Request([
            "usuario_id" => $idUsuario,
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
            $cestaId = $request->input('cestaId');

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
                $this->gestionCestas($usuarioId, $cestaId);
            }

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
