<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        编辑演示
        <small class="text-muted">请根据具体项目修改视图模板</small>
    </h3>
    <form class="form-horizontal" action="{{ route('DummyViewRouteClass.update') }}" method="post">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>名称</label>
                <input type="text" class="form-control" name="title" value="" placeholder="请输入名称" autocomplete="off">
            </div>
        </div>
        <input type="hidden" name="{{ $data->getKeyName() }}" value="{{ $data->getKey() }}"/>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>状态</label>
                <div class="form-group">
                    @foreach($filter['state'] as $key => $item)
                        @if($data->state == $key)
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
        @csrf
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
</body>
</html>