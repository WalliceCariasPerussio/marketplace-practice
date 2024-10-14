<?php

namespace App\Http\Controllers;

use App\UseCases\OfferImporter\Contracts\OfferImporterCreatorInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportOffersController extends Controller
{
    /**
     * ImportOfferRequestService instance.
     *
     * @var OfferImporterCreatorInterface
     */
    protected OfferImporterCreatorInterface $offerImporterCreator;

    /**
     * ImportOffersController's constructor.
     *
     * @param OfferImporterCreatorInterface $offerImporterCreator
     */
    public function __construct(OfferImporterCreatorInterface $offerImporterCreator)
    {
        $this->offerImporterCreator = $offerImporterCreator;
    }

    /**
     * Processa a requisição de importação de ofertas.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        $user = $request->user();
        $offerImporter = $this->offerImporterCreator->scheduleImport($user->id);

        return $this->response([
            'message' => 'Importação de ofertas agendada com sucesso!',
            'offer_import' => $offerImporter
        ], 200);
    }
}
