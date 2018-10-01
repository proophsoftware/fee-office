<?php

declare(strict_types=1);

namespace FeeOffice\RealtyRegistration\Api;

class Payload
{
    /**
     * It is recommended to define all possible message payload keys as constants instead of using strings in your code.
     * This makes it easy to find all places in the source code that work with the payload.
     *
     * @example
     *
     * const USER_ID = 'userId';
     * const USERNAME = 'username';
     *
     * Let's say you have a Command::REGISTER_USER and you want to get the USERNAME from the command payload:
     *
     * $username = $registerUser->get(Payload::USERNAME); //This is readable and eases refactoring in a larger code base.
     */

    //Predefined keys for query payloads, see App\Api\Schema::queryPagination() for further information
    const SKIP = 'skip';
    const LIMIT = 'limit';

    const BUILDING_ID = 'buildingId';
    const NAME = 'name';
}
