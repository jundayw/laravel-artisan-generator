<?php

namespace DummyNamespace;

use DummyRootNamespaceHttp\Controllers\Controller;
use DummyRepositoryClassNamespace;
use Illuminate\Http\Request;

/**
 * @module DummyLabel
 * @controller DummyLabel管理
 */
class DummyControllerClass extends Controller
{
    private $repository;

    public function __construct(DummyRepositoryClass $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @action DummyLabel列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        return view('DummyViewVariableList', $this->repository->list($request));
    }

    /**
     * @action 新增DummyLabel
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('DummyViewVariableCreate', $this->repository->create($request));
    }

    /**
     * @action 保存DummyLabel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->repository->store($request) ? $this->render('保存成功', true, route('DummyViewVariableList')) : $this->render($this->repository->getError());
    }

    /**
     * @action 编辑DummyLabel
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('DummyViewVariableEdit', $this->repository->edit($request));
    }

    /**
     * @action 更新DummyLabel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        return $this->repository->update($request) ? $this->render('修改成功', true, route('DummyViewVariableList')) : $this->render($this->repository->getError());
    }

    /**
     * @action 删除DummyLabel
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        return $this->repository->destroy($request) ? $this->render('删除成功', true, route('DummyViewVariableList')) : $this->render($this->repository->getError());
    }

    private function render($message, $state = false, $url = null)
    {
        $data = [
            'state' => $state,
            'messsage' => $message,
            'url' => $url,
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
