<?php
declare(strict_types=1);

namespace FeeOffice\ContractManagement\Model\Contract;

final class EndDate
{
    public const FORMAT = \DateTime::ATOM;

    /**
     * @var \DateTimeImmutable
     */
    private $endDate;

    public static function fromDateTime(\DateTimeImmutable $endDate): self
    {
        $endDate = self::ensureUTC($endDate);

        return new self($endDate);
    }

    public static function fromString(string $endDate): self
    {
        $endDate = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $endDate,
            new \DateTimeZone('UTC')
        );

        $endDate = self::ensureUTC($endDate);

        return new self($endDate);
    }

    private function __construct(\DateTimeImmutable $endDate)
    {
        $this->endDate = $endDate;
    }

    public function toString(): string
    {
        return $this->endDate->format(self::FORMAT);
    }

    public function dateTime(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function add(\DateInterval $interval): self
    {
        return new self($this->endDate->add($interval));
    }

    public function sub(\DateInterval $interval): self
    {
        return new self($this->endDate->sub($interval));
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private static function ensureUTC(\DateTimeImmutable $endDate): \DateTimeImmutable
    {
        if ($endDate->getTimezone()->getName() !== 'UTC') {
            $endDate = $endDate->setTimezone(new \DateTimeZone('UTC'));
        }

        return $endDate;
    }
}
