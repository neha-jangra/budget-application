<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();


    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }



    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $columns = ['*'])
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = null, $skip = null, $limit = null)
    {
        $query = $this->model->newQuery();

        $searchable = $this->getFieldsSearchable();

        if (count($searchable)) {
            foreach ($searchable as  $value) {

                $query->orWhere($value, 'LIKE', "%{$search}%");
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }


    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($search = null, $skip = null, $limit = null, $columns = ['*'])
    {
        $query = $this->allQuery($search, $skip, $limit);
        return $query->get($columns);
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        if (array_key_exists("password", $input)) {
            $input['password'] = Hash::make($input['password']);
        }

        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    public function count()
    {
        $query = $this->model->newQuery();
        return $query->count();
    }

    public function where($data)
    {
        $query = $this->model->newQuery();

        return $query->where($data);
    }


    public function first()
    {
        $query = $this->model->newQuery();

        return $query->first();
    }

    public function WhereLike($column, $value)
    {
        $query = $this->model->newQuery();

        return $query->where($column, 'like', '%' . $value . '%');
    }

    public function with($array)
    {
        return $this->model->with($array);
    }


    public function whereHas($attribute, \Closure $closure = null)
    {
        return $this->model->whereHas($attribute, $closure);
    }


    public function whereIn($column, $value)
    {
        $query = $this->model->newQuery();

        return $query->whereIn($column, $value);
    }


    public function findMany($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    public function get()
    {
        $query = $this->model->newQuery();

        return $query->get();
    }

    public function wherefirst($column)
    {
        $query = $this->model->newQuery();

        return $query->where($column)->first();
    }

    public function search($attributes, $searchTerm)
    {
        $query = $this->model->newQuery();

        if (!$searchTerm || !$attributes) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($attributes, $searchTerm) {
            foreach (Arr::wrap($attributes) as $attribute) {
                $query->when(
                    str_contains($attribute, '.'),
                    function (Builder $query) use ($attribute, $searchTerm) {
                        [$relationName, $relationAttribute] = explode('.', $attribute);

                        $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                            $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                        });
                    },
                    function (Builder $query) use ($attribute, $searchTerm) {
                        $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                    }
                );
            }
        });
    }

    public function sum($where, $column)
    {
        $query = $this->model->newQuery();

        return $query->where($where)->sum($column);
    }

    public function select($column)
    {
        $query = $this->model->newQuery();

        return $query->select($column);
    }

    public function whereUpdate($where, $column)
    {
        $query = $this->model->newQuery();
        
        return $query->where($where)->update($column);
    }

    public function whereCount($where)
    {
        $query = $this->model->newQuery();
        
        return $query->where($where)->count();
    }

    public function oWhere($data)
    {
        $query = $this->model->newQuery();

        return $query->orWhere($data);
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function Wheredelete($column)
    {
        $query = $this->model->newQuery();

        $model = $query->where($column);

        return $model->delete();
    }

    public function wherePluck($condition,$id)
    {
        $query = $this->model->newQuery();

        return $query->where($condition)->pluck($id)->toArray();
    }

    public function orderBy($attribute, $closure = 'asc')
    {
        return $this->model->orderBy($attribute, $closure);
    }

    public function whereeHas($attribute,$where, \Closure $closure = null)
    {
        return $this->model->whereHas($attribute,

            function ($data) use ($where)  
            {

                $data->where($where);

            });
    }

    public function whereget($column)
    {
        $query = $this->model->newQuery();

        return $query->where($column)->get();
    }

    public function withWhereHas($attribute,$where,$with)
    {
        return $this->model->with($with)->whereHas($attribute,

            function ($data) use ($where)  
            {

                $data->where($where);

            });
    }

    public function whereHasUpdate($relation, $where, $column)
    {
        $query = $this->model->newQuery();
        
       
        $model = $query->whereHas($relation, function ($query) use ($where) 
        {
            $query->where($where);
            
        })->first();
        
        
        if ($model) 
        {
            $model->$relation()->update($column);

            return true; 
        }
        
        return false; 
    }

    public function whereDateBetween($with,$startfieldName,$endfieldName,$fromDate,$toDate,$where)
    {
        return $this->model->with($with)->whereHas($with,

        function ($data) use ($where)  
        {
           
            $data->where($where);

        })->where(function ($query) use($startfieldName,$fromDate,$toDate,$endfieldName) {
            $query->where($startfieldName,'>=',$fromDate)->where($startfieldName,'<=',$toDate)
            ->orWhere($endfieldName,'>=',$fromDate)->where($endfieldName,'<=',$toDate);
        });
    }

    public function withWhere($with,$column)
    {
        $query = $this->model->newQuery();

        return $query->with([$with])->where($column)->first();
    }

    public function whereNot($param,$value)
    {
        $query = $this->model->newQuery();

        return $query->whereNot($param,$value)->get();
    }

    public function whereNotIn($param,$value)
    {
        $query = $this->model->newQuery();

        return $query->whereNotIn($param,$value)->get();
    }

    public function whereNotInpaginate($param,$value,$recordsPerPage)
    {
        $query = $this->model->newQuery();

        return $query->whereNotIn($param,$value)->paginate($recordsPerPage);
    }
}