<?php

namespace DoSystem\Application\Car\Data;

interface UpdateCarInputInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string|null
     */
    public function getVin(): ?string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @return string|null
     */
    public function getName(): ?string;
}
