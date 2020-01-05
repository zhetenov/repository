<?php

namespace Zhetenov\Repository;

abstract class BaseRepository
{
    /**
     * Current model.
     *
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $model;

    /**
     * CoreRepository constructor.
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * Returns current model.
     *
     * @return mixed
     */
    abstract protected function getModelClass():string;

    /**
     * Returns clone of the current model.
     *
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }
}