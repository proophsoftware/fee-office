<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

final class StartDate
{
    public const FORMAT = \DateTime::ATOM;

    /**
     * @var \DateTimeImmutable
     */
    private $startDate;

    public static function fromDateTime(\DateTimeImmutable $startDate): self
    {
        if ($startDate->getTimezone()->getName() !== 'UTC') {
            $startDate = $startDate->setTimezone(new \DateTimeZone('UTC'));
        }

        return new self($startDate);
    }

    public static function fromString(string $startDate): self
    {
        $startDate = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $startDate,
            new \DateTimeZone('UTC')
        );

        return new self($startDate);
    }

    private function __construct(\DateTimeImmutable $startDate)
    {
        $this->startDate = $startDate;
    }

    public function toString(): string
    {
        return $this->startDate->format(self::FORMAT);
    }

    public function dateTime(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function add(\DateInterval $interval): self
    {
        return new self($this->startDate->add($interval));
    }

    public function sub(\DateInterval $interval): self
    {
        return new self($this->startDate->sub($interval));
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
