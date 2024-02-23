<div class="right">

	<div class="right__content">

		<div class="container-right">
			<div class="card_calendar bg-white br-xs">
				<div class="calendar">
					<div class="calendar-header">
						<span class="month-picker" id="month-picker"> May </span>
						<div class="year-picker" id="year-picker">
							<span class="year-change" id="pre-year">
								<pre><</pre>
							</span>
							<span id="year">2020 </span>
							<span class="year-change" id="next-year">
								<pre>></pre>
							</span>
						</div>
					</div>

					<div class="calendar-body">
						<div class="calendar-week-days">
							<div>Sun</div>
							<div>Mon</div>
							<div>Tue</div>
							<div>Wed</div>
							<div>Thu</div>
							<div>Fri</div>
							<div>Sat</div>
						</div>
						<div class="calendar-days">
						</div>
					</div>
					<div class="calendar-footer">
					</div>
					<div class="date-time-formate ml-2">
						<div class="day-text-formate pr-2">TODAY</div>
						<div class="date-time-value">
							<div class="time-formate"></div>
							<div class="date-formate"></div>
						</div>
					</div>
					<div class="month-list mt-1"></div>
				</div>
			</div>
			<div class="card_todo bg-white br-xs">
				<div class="card_todo_body p-1">
					<form action="{{ route("store") }}" method="post" autocomplete="off">
						@csrf
						<div class="input-grups">
							<input type="text" name="content" class="input_style p-1 br-xs" placeholder="Add a new task">
							<button type="submit"><span class="bg-white"><svg class="bg-white" width="35px" height="35px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#35424b">
										<g id="SVGRepo_bgCarrier" stroke-width="1"></g>
										<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.288"></g>
										<g id="SVGRepo_iconCarrier">
											<path d="M6 12h6V6h1v6h6v1h-6v6h-1v-6H6z"></path>
											<path fill="none" d="M0 0h24v24H0z"></path>
										</g>
									</svg></span></button>
						</div>
					</form>
					{{-- if tasks exist --}}

					@if (count($todolists))
						<div class="list-group-div mb-1">
							<ul class="list-group">
								@foreach ($todolists as $todolist)
									<li class="list-task-item talign-c">
										<form class="formtodo" action="{{ route("destroy", $todolist->id) }}" method="post">
											<span class="text-black bg-white fw-500 talign-l pl-2" style="display: inline-block; width: 200px; word-wrap: break-word;">{{ $todolist->content }} </span>
											@csrf
											@method("delete")
											<button type="submit"><span class="bg-white"><svg fill="#ffffff" width="64px" height="64px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
														<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
														<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
														<g id="SVGRepo_iconCarrier">
															<path d="M589 307v-51H435v51H307v51h410v-51M333 410v358h358V410H333zm102 307h-51V461h51v256zm103 0h-52V461h52v256zm102 0h-51V461h51v256z">
															</path>
														</g>
													</svg></span></button>
										</form>
									</li>
								@endforeach
							</ul>
						</div>

						<p class="count talign-c text-bg pt-1">You have {{ count($todolists) }} tasks active!</p>
					@else
						<p class="talign-c text-bg mt-1">You have no tasks!</p>
					@endif
				</div>

			</div>
		</div>

	</div>
</div>
