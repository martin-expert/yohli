@extends('layouts.dashboard_master')
@section('title', 'Messages')

@section('content')
<!-- Messages Container -->
<div class="messages-container margin-top-0">
	<div class="messages-container-inner">

	<!-- Messages -->
	<div class="messages-inbox">
		<div class="messages-headline">
			<!-- <div class="input-with-icon">
					<input id="autocomplete-input" type="text" placeholder="Search">
				<i class="icon-material-outline-search"></i>
			</div> -->
		</div>

		<ul>
			<li class="active-message">
				<a href="#">
					<div class="message-avatar">
                        <i class="status-icon status-offline"></i>
                        <img src="{{ asset('assets/images/user-avatar-small-02.jpg') }}" alt="" />
                    </div>
                    <div class="message-by">
						<div class="message-by-headline">
							<h5>Sindy Forest</h5>
							<span>Yesterday</span>
						</div>
						<p>Hi Tom! Hate to break it to you but I'm actually on vacation</p>
					</div>
				</a>
            </li>
        </ul>
	</div>
	<!-- Messages / End -->

	<!-- Message Content -->
	<div class="message-content">

		<div class="messages-headline">
		    <h4>Sindy Forest</h4>
		    <!-- <a href="#" class="message-action"><i class="icon-feather-trash-2"></i> Delete Conversation</a> -->
		</div>
							
		<!-- Message Content Inner -->
		<div class="message-content-inner">
									
		    <!-- Time Sign -->
		    <div class="message-time-sign">
		    	<span>28 June, 2019</span>
            </div>
            <div class="message-bubble me">
		    	<div class="message-bubble-inner">
		    		<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-01.jpg') }}" alt="" /></div>
		    		<div class="message-text"><p>Thanks for choosing my offer. I will start working on your project tomorrow.</p></div>
		    	</div>
		    	<div class="clearfix"></div>
		    </div>

			<div class="message-bubble">
				<div class="message-bubble-inner">
					<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-02.jpg') }}" alt="" /></div>
					<div class="message-text"><p>Great. If you need any further clarification let me know. 👍</p></div>
				</div>
				<div class="clearfix"></div>
            </div>
            <div class="message-bubble me">
				<div class="message-bubble-inner">
					<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-01.jpg') }}" alt="" /></div>
					<div class="message-text"><p>Ok, I will. 😉</p></div>
				</div>
				<div class="clearfix"></div>
			</div>

			<!-- Time Sign -->
			<div class="message-time-sign">
				<span>Yesterday</span>
			</div>

			<div class="message-bubble me">
				<div class="message-bubble-inner">
					<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-01.jpg') }}" alt="" /></div>
					<div class="message-text"><p>Hi Sindy, I just wanted to let you know that project is finished and I'm waiting for your approval.</p></div>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="message-bubble">
				<div class="message-bubble-inner">
					<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-02.jpg') }}" alt="" /></div>
					<div class="message-text"><p>Hi Tom! Hate to break it to you, but I'm actually on vacation 🌴 until Sunday so I can't check it now. 😎</p></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="message-bubble me">
				<div class="message-bubble-inner">
					<div class="message-avatar"><img src="{{ asset('assets/images/user-avatar-small-01.jpg') }}" alt="" /></div>
					<div class="message-text"><p>Ok, no problem. But don't forget about last payment. 🙂</p></div>
				</div>
				<div class="clearfix"></div>
			</div>

		</div>
		<!-- Message Content Inner / End -->
		
							
		<!-- Reply Area -->
		<div class="message-reply">
            <textarea cols="1" rows="1" placeholder="Your Message" data-autoresize></textarea>                                
            <button class="button button-sliding-icon ripple-effect">
                Send
                <i class="icon-feather-send"></i>
            </button>
            <button class="button dark button-sliding-icon ripple-effect ml-1">
                File
                <i class="icon-feather-file-plus"></i>
            </button>
		</div>

	</div>
	
	<!-- Message Content -->

	<div class="messages-container margin-top-0">
	<div class="messages-container-inner">

	<!-- Messages -->
	<div class="messages-inbox">
		<div class="messages-headline">
			<!-- <div class="input-with-icon">
					<input id="autocomplete-input" type="text" placeholder="Search">
				<i class="icon-material-outline-search"></i>
			</div> -->
		</div>

		<ul>
			@forelse ($conversations as $c)
				@foreach($c->participants as $p)
					@if($p->user->id != auth()->user()->id)
						<li>
							<a class="cursor-pointer" @click="findConversation({{ $c->id }}, {{ $p->user->profile }}, {{ $c->job_id }})">
								<div class="message-avatar">
									<i class="status-icon status-offline"></i>
									@if (sizeof($p->user->profile->getMedia('profile')) == 0)
									<img src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
									@else
										<img src="{{ $p->user->profile->getFirstMediaUrl('profile', 'big') }}" alt=""/> 
									@endif
								</div>
								<div class="message-by">
									<div class="message-by-headline">
										<h5>{{ $p->user->name }}</h5>
										@if($p->conversationWithLastMessage->last_message)
										<span>{{ $p->conversationWithLastMessage->last_message->created_at->diffForHumans() }}</span>
										@else
										<span>{{ $p->created_at->diffForHumans() }}</span>
										@endif
									</div>
									@if($p->conversationWithLastMessage->last_message)
									<p>{{ $p->conversationWithLastMessage->last_message->body }}</p>
									@else
									<p>No Messages yet</p>
									@endif
								</div>
							</a>
						</li>
					@endif
				@endforeach
			@empty
				<p class="text-center text-muted py-3">Zero Conversation.. its quiet out here</p>
				
			@endforelse
        </ul>
	</div>
	<!-- Messages / End -->

	<!-- Message Content -->
	<div class="message-content">

		<div class="messages-headline">
			<h4>@{{ profile.name }}</h4>
			<!-- <span  @click="scrollDown()" class="message-action"><i class="icon-feather-arrow-down-circle cursor-pointer"></i> Go to Bottom</span> -->
		</div>
		<!-- Message Content Inner -->
			<div class="message-content-inner" v-chat-scroll="{always: true, smooth: true, scrollonremoved:true, smoothonremoved: true}">
				<div v-for="message in conversation">					
				<!-- Time Sign -->
				<div class="message-time-sign">
					<span>@{{ dateFormat(message.created_at) }}</span>
				</div>
				<div v-if="message.sender.email == user.email" class="message-bubble  me">
					<div class="message-bubble-inner ">
						<div :title="message.sender.name" class="message-avatar"><img v-bind:src="image(message.sender.profile)" alt=""/>
						 </div>
						<div class="message-text bg-light-blue-400">
							<p v-if="message.body">@{{ message.body }}</p>
							<div v-if="showAttachemnts(message.media)" class="attachments-container  flex flex-col">
								<div v-for="media_file in message.media">
									<a :href="file(media_file)" class="attachment-box bg-light-blue-400 ripple-effect cursor-pointer" download>
										<span class="text-capitalize text-white hover:text-black ">@{{ media_file.file_name }} </span>
										<i class="text-uppercase text-white hover:text-black">@{{ media_file.mime_type }}</i> 
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

				<div v-else class="message-bubble">
					<div class="message-bubble-inner">
						<div :title="message.sender.name" class="message-avatar"><img  v-bind:src="image(message.sender.profile)" alt="" /></div>
						<div class="message-text">
							<p v-if="message.body">@{{ message.body }}</p>
							<div v-if="showAttachemnts(message.media)" class="attachments-container flex flex-col">
								<div v-for="media_file in message.media">
									<a :href="file(media_file)" target="new" class="attachment-box ripple-effect cursor-pointer" download>
										<span class="text-capitalize hover:text-white ">@{{ media_file.file_name }} </span>
										<i class="text-uppercase hover:text-white">@{{ media_file.mime_type }}</i> 
									</a>
								</div>
							</div>
						</div>
						<span id="pane"></span>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		
	<!-- Message Content Inner / End -->

					
<!-- Reply Area -->
<div v-show="canSendMessage" class="message-reply flex flex-row flex-wrap">
	<div class="flex flex-row flex-wrap">
		<div class="uploadButton margin-top-0">
			<input class="uploadButton-input" ref="files" v-on:change="handleFilesUpload()" type="file" accept="image/*, application/*, video/*" id="upload" name="files[]" multiple/>
			<label class="uploadButton-button ripple-effect" for="upload"><i class="icon-material-outline-attach-file"> </i></label>
			<div class="flex flex-col justify-start px-2">
				<span v-for="file in files">
					<div class="text-gray-700">@{{ file.name }}</div>
				</span>
			</div>
		</div>
	</div>   
	<textarea class="p-2 min-w-xxs" v-model="body"  cols="1" rows="1" placeholder="Your Message" data-autoresize></textarea>  
                           
	<button @click="send()"  class="button button-sliding-icon ripple-effect">
		<span v-if="!spinner">Send</span>
		<span id="spinner" v-if="spinner" class="spinner-border text-warning w-8 h-8 my-2"></span>
	</button>

</div>

</div>

	</div>
</div>
<!-- Messages Container / End -->

@endsection