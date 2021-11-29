<?php

namespace DummyNamespace;

use DummyModelClassNamespace;
use Illuminate\Http\Request;

class DummyRepositoryClass
{
    private $DummyModelVariable;
    private $error;

    public function __construct(DummyModelClass $DummyModelVariable)
    {
        $this->DummyModelVariable = $DummyModelVariable;
    }

    public function list(Request $request)
    {
        $data = $this->DummyModelVariable
            ->when($request->get('state'), function ($query) use ($request) {
                $query->where('state', $request->get('state'));
            })
            ->latest('state')
            ->latest($this->DummyModelVariable->getKeyName());

        $data = $data->Paginate($request->get('per', 10), ['*'], 'page', $request->get('page', 1));

        $data->transform(function ($items) {
            $items->edit    = route('DummyRepositoryRoute.edit', [$items->getKeyName() => $items->getKey()]);
            $items->destroy = route('DummyRepositoryRoute.destroy', [$items->getKeyName() => $items->getKey()]);
            $items->state   = $items->getStateAttributes($items->state);
            return $items;
        });

        $filter = [
            'state' => $this->DummyModelVariable->getStateAttributes(),
        ];

        return compact('filter', 'data');
    }

    public function create(Request $request)
    {
        $filter = [
            'state' => $this->DummyModelVariable->getStateAttributes(),
        ];

        return compact('filter');
    }

    public function store(Request $request)
    {
        $item = $this->DummyModelVariable->create([
            'state' => $request->get('state'),
        ]);

        if ($item) {
            return $item;
        }

        return $this->error('新增失败');
    }

    public function edit(Request $request)
    {
        $data = $this->DummyModelVariable->find($request->get($this->DummyModelVariable->getKeyName()));

        if (is_null($data)) {
            return $this->error('暂无记录');
        }

        $filter = [
            'state' => $this->DummyModelVariable->getStateAttributes(),
        ];

        return compact('filter', 'data');
    }

    public function update(Request $request)
    {
        $data = $this->DummyModelVariable->find($request->get($this->DummyModelVariable->getKeyName()));
        if (is_null($data)) {
            return $this->error('更新失败');
        }
        return $data->update([
            'state' => $request->get('state'),
        ]);
    }

    public function destroy(Request $request)
    {
        if ($this->DummyModelVariable->destroy($request->get($this->DummyModelVariable->getKeyName())) === 0) {
            return $this->error('删除失败');
        }
        return true;
    }

    private function error($error)
    {
        $this->error = $error;
        return false;
    }

    public function getError()
    {
        return $this->error;
    }
}
