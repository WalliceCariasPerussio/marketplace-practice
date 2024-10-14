<?php

namespace App\UseCases\OfferImporter;

use App\Jobs\ImportOffersJob;
use App\Repositories\AccountRepository;
use App\Repositories\OfferImportRepository;
use App\UseCases\OfferImporter\Contracts\OfferImporterCreatorInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OfferImporterCreator implements OfferImporterCreatorInterface
{
    protected AccountRepository $accountRepository;

    protected OfferImportRepository $offerImportRepository;

    /**
     * ImportOfferRequestService's constructor.
     *
     * @param AccountRepository $accountRepository
     * @param OfferImportRepository $offerImportRepository
     */
    public function __construct(AccountRepository $accountRepository, OfferImportRepository $offerImportRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->offerImportRepository = $offerImportRepository;
    }

    /**
     * Agenda o job de importação.
     *
     * @param int $userId
     * @return array
     * @throws ValidationException
     */
    public function scheduleImport(int $userId): array
    {
        $accountId = $this->accountRepository
            ->findAccountId($userId);

        if (!$accountId) {
            throw ValidationException::withMessages([
                'account_id' => 'Nenhuma conta encontrada para o usuário.',
            ]);
        }

        // Verificar se existe um registro de importação de ofertas, se existir agendar a importação existente, caso não exista, criar um novo registro
        $offerImport = $this->offerImportRepository->findOfferImportInProgressByAccountId($accountId);

        if (!$offerImport) {
            $offerImport = $this->offerImportRepository->create([
                'account_id' => $accountId,
            ]);
        }

        ImportOffersJob::dispatch($offerImport->id);

        return $offerImport->toArray();
    }
}
