<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Import;

use App\Util\JsonSchema\NullType;
use FeeOffice\ContactAdministration\Model\ContactCard;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Prooph\EventMachine\JsonSchema\JsonSchemaAssertion;
use Prooph\EventMachine\JsonSchema\JustinRainbowJsonSchemaAssertion;
use Psr\Container\ContainerInterface;

chdir(dirname(__DIR__, 3));

require_once 'vendor/autoload.php';

//Utils
final class ContactData {
    const ID = 'id';
    const FIRST_NAME = 'firstName';
    const LAST_NAME = 'lastName';
    const COMPANY = 'company';
}

function get_schema_assertion(): JsonSchemaAssertion {
    static $assertion;

    if(null === $assertion) {
        $assertion = new JustinRainbowJsonSchemaAssertion();
    }

    return $assertion;
}

function contact_card_from_data(array $data): ContactCard {
    if(is_person($data)) {
        validate_person_data($data);

        return ContactCard::forPerson(
            ContactCard\ContactCardId::fromString($data[ContactData::ID]),
            ContactCard\FirstName::fromString($data[ContactData::FIRST_NAME]),
            ContactCard\LastName::fromString($data[ContactData::LAST_NAME])
        );
    }

    validate_company_data($data);

    return ContactCard::forCompany(
        ContactCard\ContactCardId::fromString($data[ContactData::ID]),
        ContactCard\Company::fromString($data[ContactData::COMPANY])
    );
}

function contact_card_id(array $data): ContactCard\ContactCardId {
    if(! $data[ContactData::ID] ?? null) {
        throw new \RuntimeException("Missing id property in dataset: " . json_encode($data));
    }

    return ContactCard\ContactCardId::fromString($data[ContactData::ID]);
}

function is_person(array $data): bool {
    return null === $data[ContactData::COMPANY];
}

function validate_person_data(array $data): void {
    $assertion = get_schema_assertion();

    $assertion->assert('ContactCard', $data, JsonSchema::object([
        ContactData::ID => JsonSchema::uuid(),
        ContactData::FIRST_NAME => JsonSchema::string()->withMinLength(1),
        ContactData::LAST_NAME => JsonSchema::string()->withMinLength(1),
        ContactData::COMPANY => new NullType(),
    ])->toArray());
}

function validate_company_data(array $data): void {
    $assertion = get_schema_assertion();

    $assertion->assert('ContactCard', $data, JsonSchema::object([
        ContactData::ID => JsonSchema::uuid(),
        ContactData::COMPANY => JsonSchema::string()->withMinLength(1),
        ContactData::FIRST_NAME => new NullType(),
        ContactData::LAST_NAME => new NullType(),
    ])->toArray());
}

function success(ContactCard\ContactCardId $contactCardId): string {
    return one_line(colored_text(Color::GREEN, "Imported contact: $contactCardId"));
}

function unexpected_error(\Throwable $error): string {
    return one_line(colored_text(Color::RED, $error->getMessage())) . one_line($error->getTraceAsString());
}

function import_error(ContactCard\ContactCardId $contactCardId, \Throwable $error): string {
    return one_line(colored_text(Color::RED, "Failed to import contact: $contactCardId"))
        . one_line(colored_text(Color::RED, $error->getMessage()))
        . one_line($error->getTraceAsString());
}

function one_line(string $text): string {
    return "$text\n";
}

function colored_text(string $color, string $text): string {
    return "\033[{$color}m{$text}\033[0m";
}

final class Color
{
    const RED = '31';
    const GREEN = '32';
}

//Script

/** @var ContainerInterface $appContainer */
$appContainer = include 'config/container.php';

/** @var ContactCardCollection $contactCardCollection */
$contactCardCollection = $appContainer->get(ContactCardCollection::class);

$contacts = json_decode(
    file_get_contents(realpath(__DIR__ . '/../import/contacts.json')),
    true
);

$total = count($contacts);
$errors = 0;

echo one_line("Importing $total contacts ...");

array_walk($contacts, function (array $contactData) use ($contactCardCollection, &$errors) {
    try {
        $contactCardId = contact_card_id($contactData);
    } catch (\Throwable $error) {
        echo unexpected_error($error);
        $errors++;
        return;
    }

    try {
        $contactCardCollection->add(contact_card_from_data($contactData));
    } catch (\Throwable $error) {
        echo import_error($contactCardId, $error);
        $errors++;
        return;
    }

    echo success($contactCardId);
});

echo one_line("Finished import with $errors/$total errors");



