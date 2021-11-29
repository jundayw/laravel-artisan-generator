<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    <title>DummyViewUrlMethod</title>
</head>
<body>
<div class="container">
    <h3 class="pt-5 pb-3">
        列表演示
        <small class="text-muted">请根据具体项目修改视图模板</small>
    </h3>
    <form class="form-horizontal" action="{{ route('DummyViewRouteMethod') }}" method="get">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>名称</label>
                <input type="text" class="form-control" name="title" value="{{ app('request')->get('title') }}" placeholder="请输入名称" autocomplete="off">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>状态</label>
                <div class="form-group">
                    @foreach($filter['state'] as $key => $item)
                        @if(app('request')->get('state')==$key)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="state" value="{{ $key }}" checked>
                                <label class="form-check-label">{{ $item }}</label>
                            </div>
                        @else
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="state" value="{{ $key }}">
                                <label class="form-check-label">{{ $item }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">查询</button>
        <a class="btn btn-primary" href="{{ route('DummyViewRouteMethod') }}" role="button">重置</a>
        <a class="btn btn-primary" href="{{ route('DummyViewRouteClass.create') }}" role="button">新增</a>
    </form>
    <div class="pt-5 pb-3">
        <table class="table table-bordered">
            @if(!$data->isEmpty())
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">state</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $items)
                    <tr>
                        <td scope="row">{{ $items->getKey() }}</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>{{ $items->state }}</td>
                        <td>
                            <a href="{{ $items->edit }}">编辑</a>
                            <a href="{{ $items->destroy }}" rel-action="confirm" rel-certain="删除" rel-msg="确定执行删除操作">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @else
                <tr>
                    <td>暂无数据</td>
                </tr>
            @endif
        </table>
        {{ $data->links() }}
    </div>
</div>
</body>
</html>