
    <div class="container-right p-1 talign-c">
        <div class="card_todo br-xs">
            <div class="card_todo_body p-1">
                <h3 class="mb-1 text-white ls-2">Task List</h3>
                <form action="{{ route('store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="input-grups">
                        <input type="text" name="content" class="input_style p-1 br-xs" placeholder="Add a new task">
                        <button type="submit"><span class="bg-bg"><svg class="bg-bg" width="35px" height="35px" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" fill="#bafcdd">
                                    <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                                        stroke="#CCCCCC" stroke-width="0.288"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M6 12h6V6h1v6h6v1h-6v6h-1v-6H6z"></path>
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                    </g>
                                </svg></span></button>
                    </div>
                </form>
                {{-- if tasks exist --}}
                @if (count($todolists))
                    <ul class="list-group">
                        @foreach ($todolists as $todolist)
                            <li class="list-task-item talign-c">
                                <form class="formtodo" action="{{ route('destroy', $todolist->id) }}" method="post">
                                    <span class="text-white talign-l pl-2" style="display: inline-block; width: 200px; word-wrap: break-word;">{{ $todolist->content }} </span>
                                    @csrf
                                    @method('delete')
                                    <button type="submit"><span class="bg-bg"><svg fill="#000000" width="64px" height="64px"
                                                viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M589 307v-51H435v51H307v51h410v-51M333 410v358h358V410H333zm102 307h-51V461h51v256zm103 0h-52V461h52v256zm102 0h-51V461h51v256z">
                                                    </path>
                                                </g>
                                            </svg></span></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    <p class="count talign-c text-white pt-1">You have {{ count($todolists) }} tasks active!</p>
                @else
                    <p class="talign-c text-white mt-1">You have no tasks!</p>
                @endif
            </div>
        </div>
    </div>

