{{-- 由LaravelMenu或自動生成 --}}
<div class="ui massive center aligned secondary pointing menu transition fixed"
     style="z-index: 3; background-color: white" id="navbar">
    <div class="ui container">
        <a class="toc item inverted">
            <i class="sidebar icon"></i>
        </a>
        <div class="header item">2016逢甲大學新鮮人成長營</div>
        <div class="right menu">
            <div class="ui action input">
                {{ Form::open(['method' => 'get', 'id' => 'ticket_search_form']) }}
                    <input type="text" placeholder="抽獎券編號">
                    <button type="submit" class="ui button">Go</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="ui sidebar inverted vertical labeled icon menu" style="z-index: 999;">
    @include('navbar.sidebar-item', ['items' => Menu::get('sidebar')->roots()])
</div>
