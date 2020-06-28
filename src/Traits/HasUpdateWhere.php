<?php


namespace LTools\Traits;


use Illuminate\Database\Eloquent\Model;

trait HasUpdateWhere
{
    protected $updateWhere = [];

    /**
     * @param $column
     * @param null $operator
     * @param $value
     * @param string $boolean
     * @return Model
     * @author luffyzhao@vip.126.com
     */
    public function setUpdateWhere($column, $operator = null, $value = null, $boolean = 'and'): Model
    {
        $this->updateWhere[] = [$column, $operator, $value, $boolean];
        return $this;
    }

    /**
     * @param $id
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    public function updateWhere($id) : int
    {

        return $this->getConnection()->transaction(function ()use($id) {
            $query = $this->newModelQuery();

            $this->setAttribute($this->getKeyName(), $id);

            if ($this->usesTimestamps()) {
                $this->updateTimestamps();
            }

            $dirty = $this->getDirty();

            $result = 0;
            if (count($dirty) > 0) {
                $query = $this->setKeysForSaveQuery($query);
                if (!empty($this->updateWhere)) {
                    foreach ($this->updateWhere as $key => $item) {
                        if(strtoupper($item[1]) === 'IN'){
                            $query->whereIn($item[0], $item[2], $item[3]);
                        }else{
                            $query->where(...$item);
                        }
                    }
                }
                $result = $query->update($dirty);
            }

            return $result;
        });
    }
}
