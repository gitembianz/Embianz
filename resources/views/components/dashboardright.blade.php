
    <div class="container-right">
        <div class="card_todo">
            <div class="card_todo_body">
                <h3>Task List</h3>
                <form action="{{ route('store') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="input-grups">
                        <input type="text" name="content" placeholder="Add a new task">
                        <button type="submit"><span><svg width="64px" height="64px" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
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
                            <li class="list-task-item">
                                <form action="{{ route('destroy', $todolist->id) }}" method="post">
                                    {{ $todolist->content }}
                                    @csrf
                                    @method('delete')
                                    <button type="submit"><span><svg fill="#000000" width="64px" height="64px"
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
                @else
                    <p class="talign-c mt-3">You have no tasks!</p>
                @endif
            </div>
        </div>
    </div>

