<?php

namespace DoSystem\Domain\Genre\Model;

class Genre
{
    /**
     * @var GenreValueId
     */
    private $id;

    /**
     * @var GenreValueName
     */
    private $name;

    /**
     * @var self|null
     */
    private $parent;

    /**
     * Constructor
     *
     * @param GenreValueId $id
     * @param GenreValueName $name
     * @param self|null $parent
     */
    public function __construct(GenreValueId $id, GenreValueName $name, ?self $parent = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;
    }
}
