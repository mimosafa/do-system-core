<?php

namespace DoSystemCoreMock\Database\Seeder;

trait SeederTrait
{
    /**
     * Generated data
     *
     * @var array[]
     */
    protected $data = [];

    /**
     * Get injected data
     *
     * @return array[]
     * @throws \Exception
     */
    public function get(): array
    {
        if (empty($this->data)) {
            throw new \Exception('No data. You must exec `seed` method before `get`.');
        }
        return $this->data;
    }
}
