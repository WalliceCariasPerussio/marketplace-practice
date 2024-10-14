<?php

namespace App\UseCases\OfferImporter;

use App\Jobs\ImportOffersJob;
use App\Repositories\OfferImportRepository;
use Exception;
use App\UseCases\OfferImporter\States\{
    OfferImporting,
    OfferImportingCanceled,
    OfferImportingCompleted,
    OfferImportingOffers
};
use RuntimeException;

class OfferImportProcessManager
{
    protected OfferImportRepository $offerImportRepository;

    /**
     * Estados do processo de importação de ofertas.
     *
     * @var array
     */
    protected array $states = [
        'pending'           => OfferImporting::class,
        'importing-offers'  => OfferImportingOffers::class,
        'completed'         => OfferImportingCompleted::class,
        'canceled'          => OfferImportingCanceled::class,
    ];

    public function __construct(OfferImportRepository $offerImportRepository)
    {
        $this->offerImportRepository = $offerImportRepository;
    }

    /**
     * Processa a importação das ofertas.
     *
     * @param int $offerImportId
     * @return void
     */
    public function process(int $offerImportId): void
    {
        $offerImportProcess = $this->getOfferImportProcess($offerImportId);
        $currentState = $this->getCurrentState($offerImportProcess);

        // Chama o método process independentemente do estado.
        $currentState->process($offerImportProcess);

        // Atualiza o estado do processo no repositório.
        $this->offerImportRepository->update([
            'id' => $offerImportProcess->id,
            'status' => $offerImportProcess->status,
        ]);

        // Se o estado atual for diferente de 'completed', reagenda o job.
        if ($this->canProcess($offerImportProcess)) {
            ImportOffersJob::dispatch($offerImportProcess->id);
        }
    }

    private function canProcess(object $offerImportProcess): bool
    {
        return !in_array($offerImportProcess->status, ['completed', 'canceled']);
    }

    /**
     * Cancela o processo de importação.
     *
     * @param int $offerImportId
     * @param string $message
     * @return void
     * @throws Exception
     */
    public function cancel(int $offerImportId): void
    {
        $offerImportProcess = $this->getOfferImportProcess($offerImportId);

        // Define o estado como cancelado e executa o processo de cancelamento.
        $cancelState = new OfferImportingCanceled($offerImportProcess);
        $cancelState->process();

        // Atualiza o repositório após o cancelamento.
        $this->offerImportRepository->update([
            'id' => $offerImportProcess->id,
            'status' => $offerImportProcess->status,
        ]);
    }

    /**
     * Retorna o processo de importação de ofertas.
     *
     * @param int $offerImportId
     * @return mixed
     * @throws RuntimeException
     */
    protected function getOfferImportProcess(int $offerImportId)
    {
        $offerImportProcess = $this->offerImportRepository->find($offerImportId);

        if (!$offerImportProcess) {
            throw new RuntimeException('Processo de importação de ofertas não encontrado.');
        }

        return $offerImportProcess;
    }

    /**
     * Retorna o estado atual do processo de importação de ofertas.
     *
     * @param $offerImportProcess
     * @return mixed
     * @throws RuntimeException
     */
    protected function getCurrentState($offerImportProcess)
    {
        $stateClass = $this->states[$offerImportProcess->status] ?? null;

        if (!$stateClass) {
            throw new RuntimeException('Estado do processo de importação de ofertas não encontrado.');
        }

        return new $stateClass($offerImportProcess);
    }
}
