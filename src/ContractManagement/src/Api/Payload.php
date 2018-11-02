<?php

declare(strict_types=1);

namespace FeeOffice\ContractManagement\Api;

class Payload
{
    public const CONTRACT_ID = 'contractId';
    public const SUPERIOR_PARTY = 'superiorParty';
    public const SUBORDINATE_PARTY = 'subordinateParty';
    public const APARTMENT_ID = 'apartmentId';
    public const START_DATE = 'startDate';
    public const END_DATE = 'endDate';
    public const CONTRACT_PERIOD = 'contractPeriod';

    //Predefined keys for query payloads, see App\Api\Schema::queryPagination() for further information
    const SKIP = 'skip';
    const LIMIT = 'limit';
}
