<?php

namespace DoSystem\Core\Application\Brand\Data;

interface UpdateBrandInputInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string|null
     */
    public function getName(): ?string;
}
