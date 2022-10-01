<?php

namespace App\Abstracts\Service;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use PDOException;

abstract class Service
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * create a new record in the database
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    protected function create(array $data): ?Model
    {
        try {
            $newModel = $this->model->create($data);
            $this->setModel($newModel);
            return $this->getModel();
        } catch (Exception $exception) {
            $this->handleException($exception);
            return null;
        }
    }

    /**
     * update record in the database
     *
     * @param array $data
     * @param $id
     * @return bool
     * @throws Exception
     */
    protected function update(array $data, $id): bool
    {
        try {
            $recordModel = $this->model->findOrFail($id);
            $this->setModel($recordModel);
            return $this->model->update($data);
        } catch (Exception $exception) {
            $this->handleException($exception);
            return false;
        }

    }

    /**
     * show the record with the given id
     * @param $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    protected function show($id, bool $purge = false)
    {
        $newModel = null;
        try {
            if ($purge === true)
                $newModel = $this->model->withTrashed()->findOrFail($id);
            else
                $newModel = $this->model->findOrFail($id);

        } catch (ModelNotFoundException $exception) {
            $this->handleException($exception);
        } finally {
            return $newModel;
        }
    }

    /**
     * remove record from the database
     * @param $id
     * @return bool
     */
    protected function delete($id): bool
    {
        return (bool)$this->model->destroy($id);
    }

    /**
     * remove record from the database
     * @param $id
     * @return bool
     */
    protected function restore($id): bool
    {
        return (bool)$this->model->withTrashed()->find($id)->restore($id);
    }

    /**
     * Get the associated model
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Associated Dynamically  model
     * @param Model|string $model
     * @return void
     */
    public function setModel($model)
    {
        if (is_string($model)) {
            $model = App::make($model);
        }

        $this->model = $model;
    }

    /**
     * Handle All catch Exceptions
     *
     * @param $exception
     * @throws Exception
     */
    public function handleException($exception)
    {
        //if application is on production keep silent
        if (App::environment('production'))
            Log::error($exception->getMessage());

        //Eloquent Model Exception
        else if ($exception instanceof ModelNotFoundException)
            throw new ModelNotFoundException($exception->getMessage());

        //DB Error
        else if ($exception instanceof PDOException)
            throw new PDOException($exception->getMessage());

        else if ($exception instanceof \BadMethodCallException)
            throw new \BadMethodCallException($exception->getMessage());

        //Through general Exception
        else
            throw new Exception($exception->getMessage());

    }

}