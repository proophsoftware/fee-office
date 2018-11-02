<?php
declare(strict_types=1);

namespace FeeOffice\ContactAdministration\Infrastructure\Persistence;

use App\Util\MapIterator;
use FeeOffice\ContactAdministration\Model\ContactCard;
use FeeOffice\ContactAdministration\Model\ContactCard\ContactCardId;
use FeeOffice\ContactAdministration\Model\ContactCardCollection;
use FeeOffice\ContactAdministration\Model\Exception\ContactCardNotFound;
use Prooph\EventMachine\Persistence\DocumentStore;

final class ContactCardDocumentStore implements ContactCardCollection
{
    const COLLECTION = 'contact_card';

    /**
     * @var DocumentStore
     */
    private $documentStore;

    public function __construct(DocumentStore $documentStore)
    {
        $this->documentStore = $documentStore;
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function add(ContactCard $contactCard): void
    {
        $now = (new \DateTime())->format(DATE_ATOM);

        $this->documentStore->addDoc(
            self::COLLECTION,
            $contactCard->contactCardId()->toString(),
            array_merge($contactCard->toArray(), [
                'createdAt' => $now,
                'updatedAt' => $now,
                'name' => $this->getNameStringFromCard($contactCard)
            ])
        );
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function replace(ContactCard $contactCard): void
    {
        $this->documentStore->updateDoc(
            self::COLLECTION,
            $contactCard->contactCardId()->toString(),
            array_merge($contactCard->toArray(), [
                'updatedAt' => (new \DateTime())->format(DATE_ATOM),
                'name' => $this->getNameStringFromCard($contactCard)
            ])
        );
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function remove(ContactCardId $cardId): void
    {
        $this->documentStore->deleteDoc(self::COLLECTION, $cardId->toString());

    }

    /**
     * @inheritdoc
     */
    public function get(ContactCardId $cardId): ContactCard
    {
        $data = $this->documentStore->getDoc(self::COLLECTION, $cardId->toString());

        if(!$data) {
            throw ContactCardNotFound::withCardId($cardId);
        }

        return $this->cardFromDoc($data);
    }

    /**
     * @inheritdoc
     */
    public function getMany($limit = 100, $offset = 0): iterable
    {
        $cursor = $this->documentStore->filterDocs(
            self::COLLECTION,
            new DocumentStore\Filter\AnyFilter(),
            $offset,
            $limit,
            DocumentStore\OrderBy\Asc::byProp('createdAt')
        );

        return new MapIterator($cursor, function (array $doc): ContactCard {
            return $this->cardFromDoc($doc);
        });
    }

    /**
     * @inheritdoc
     */
    public function findByName(string $name, $limit = 100, $offset = 0): iterable
    {
        $cursor = $this->documentStore->filterDocs(
            self::COLLECTION,
            new DocumentStore\Filter\LikeFilter('name', '%'.mb_strtolower($name).'%'),
            $offset,
            $limit,
            DocumentStore\OrderBy\Asc::byProp('name')
        );

        return new MapIterator($cursor, function (array $doc): ContactCard {
            return $this->cardFromDoc($doc);
        });
    }

    private function cardFromDoc(array $doc): ContactCard
    {
        unset($doc['createdAt'], $doc['updatedAt'], $doc['name']);
        return ContactCard::fromArray($doc);
    }

    private function getNameStringFromCard(ContactCard $card): string
    {
        if(null === $card->company()) {
            return mb_strtolower($card->firstName() . ' ' . $card->lastName());
        }

        return mb_strtolower($card->company()->toString());
    }
}
