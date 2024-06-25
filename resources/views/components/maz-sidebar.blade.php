@props(['href','logo', 'title' => 'Menu'])

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            {{-- <div class="d-flex justify-content-between align-items-center"> --}}
                <div class="logo">
                    <a href="{{ $href }}"><img style="width: 200px; height: 90px;" src="{{ $logo }}" alt="Logo"
                            srcset=""></a>
                </div>
                {{--
            </div> --}}
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title"></li>
                {{ $slot ?? '' }}
            </ul>
        </div>

    </div>
</div>