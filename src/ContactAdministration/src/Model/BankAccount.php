<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Model;

use FeeOffice\ContactAdministration\Model\BankAccount\BIC;
use FeeOffice\ContactAdministration\Model\BankAccount\IBAN;
use Prooph\EventMachine\Data\ImmutableRecord;
use Prooph\EventMachine\Data\ImmutableRecordLogic;

final class BankAccount implements ImmutableRecord
{
    use ImmutableRecordLogic;

    const IBAN = 'iban';
    const BIC = 'bic';

    /**
     * @var IBAN
     */
    private $iban;

    /**
     * @var BIC
     */
    private $bic;

    public static function fromIbanAndBic(IBAN $IBAN, BIC $BIC): self
    {
        return self::fromRecordData([
            self::IBAN => $IBAN,
            self::BIC => $BIC,
        ]);
    }

    /**
     * @return IBAN
     */
    public function iban(): IBAN
    {
        return $this->iban;
    }

    /**
     * @return BIC
     */
    public function bic(): BIC
    {
        return $this->bic;
    }
}
