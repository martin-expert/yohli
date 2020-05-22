@extends('layouts.dashboard_master')
@section('title', 'Messages')
@push('custom-styles')
<link href="{{ asset('css/chatpage.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
<script src="{{ asset('js/websocket.js') }}"></script>

@endpush
@section('content')
<!-- Messages Container -->
<div id='app'>
  <!-- ============================================================== -->
  <!-- Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  
  <!-- ============================================================== -->
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Start Page Content -->
  <!-- ============================================================== -->
  <div class="row">
    <div class="col-12">
      <div class="card m-b-0">
        <!-- .chat-row -->
        <div class="chat-main-box">
          <!-- .chat-left-panel -->
          <div class="chat-left-aside">
            <div class="open-panel bg-orange-500 text-white"><i class="icon-line-awesome-angle-right"></i></div>
            <div class="chat-left-inner">
              <div class="form-material">
                <p class="p-3">Chat Contacts</p>
              </div>
              <ul class="chatonline style-none ">
              @forelse ($conversations as $c)
                @foreach($c->participants as $p)
                  @if($p->user->id != auth()->user()->id)
                    <li>
                      <a class="cursor-pointer" :class="[isActive == {{ $c->id }} ? activeClass : '']" href="#{{ $c->id }}" @click="findConversation({{ $c->id }}, {{ $p->user->profile }}, {{ $c->job_id }})">
                        <div class="flex flex-row flex-no-wrap chat-badge">
                          <div class="mr-1 img-margin">
                            @isset($p->user->profile)
                              @if (sizeof($p->user->profile->getMedia('profile')) == 0)
                              <img class="rounded-full  object-contain w-10" src="{{ asset('assets/images/user-avatar-placeholder.png') }}" alt="">
                              @else
                              <img class="rounded-full object-contain w-10" src="{{ $p->user->profile->getFirstMediaUrl('profile', 'thumb') }}" alt=""/> 
                              @endif
                              {!! get_user_plan($p->user->id)['badge'] !!}
                            @endisset
                          </div>
                          <div class="flex flex-col justify-between">
                            <span class="font-light">{{ $p->user->name }}</span>
                            <span class=""><b>Job:</b> {{ $p->conversationWithLastMessage->job->title ?? 'Job Deleted' }}</span>
                            @if($p->conversationWithLastMessage->last_message)
                            <small>{{ $p->conversationWithLastMessage->last_message->created_at->timezone($timezone)->diffForHumans() }}</small>
                            @else
                            <small>{{ $p->created_at->timezone($timezone)->diffForHumans() }}</small>
                            @endif
                          </div>
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
          </div>
          <!-- .chat-left-panel -->
          <!-- .chat-right-panel -->
          <div class="chat-right-aside">
            <div class="chat-main-header">
              <div class="p-3 b-b">
                <h4 class="box-title">Chat Message</h4>
              </div>
            </div>
            <div class="chat-rbox"  v-chat-scroll="{always: true, smooth: true, scrollonremoved:true, smoothonremoved: true}">
              <ul class="chat-list p-3">
                <!--chat Row -->
                <template v-for="message in conversation" >
                  <li v-if="message.sender.email == user.email" class="reverse">
                    <div class="chat-content">
                      <h5>@{{ message.sender.name }}</h5>
                      <div class="box bg-gray-200 text-black" v-if="message.body">@{{ message.body }}</div>
                      <div v-if="showAttachemnts(message.media)"  class="flex flex-col flex-no-wrap mr-0" >
                          <div v-for="media_file in message.media">
                            <a :href="file(media_file)" class="text-gray-600  cursor-pointer" download>
                              <span class="text-capitalize  hover:text-black cursor-pointer">@{{ media_file.file_name }} </span>
                              <!-- <i class="text-uppercase  hover:text-black cursor-pointer">@{{ media_file.mime_type }}</i>  -->
                            </a>
                          </div>
                        </div>
                      <div class="chat-time">@{{ dateFormat(message.created_at) }}</div>
                    </div>
                    <div class="chat-img"><img v-bind:src="image(message.sender.profile)" alt=""/></div>
                  </li>
                  <!--chat Row -->
                  <li v-else>
                    <div class="chat-img"><img  v-bind:src="image(message.sender.profile)" alt="" />
                    </div>
                    <div class="chat-content">
                      <h5>@{{ message.sender.name }}</h5>
                      <div class="box bg-light-info" v-if="message.body">@{{ message.body }}</div>
                      <div v-if="showAttachemnts(message.media)" class="flex flex-col cursor-pointer">
                        <div v-for="media_file in message.media">
                          <a :href="file(media_file)" title="Click to Download" target="new" class="text-gray-600 ripple-effect cursor-pointer" download>
                            <span class="cursor-pointer text-capitalize hover:text-black ">@{{ media_file.file_name }} </span>
                            <!-- <i class="cursor-pointer text-uppercase hover:text-white">@{{ media_file.mime_type }}</i>  -->
                          </a>
                        </div>
                      </div>
                      <div class="chat-time ml-0 text-left">@{{ dateFormat(message.created_at) }}</div>
                    </div>
                  </li>
                </template> 
                <span id="pane"></span>

              </ul>
            </div>
            <!-- Reply Area -->
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
      <!-- .chat-right-panel -->
      </div>
    <!-- /.chat-row -->
  

  <!-- ============================================================== -->
  <!-- End PAge Content -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Right sidebar -->
  <!-- ============================================================== -->

  <!-- ============================================================== -->
  <!-- End Right sidebar -->
  <!-- ============================================================== -->



</div>
  <!-- Message Content -->

  </div>

</div>
</div>

<!-- Messages Container / End -->

<script>

var conversations = <?= json_encode($conversations); ?>;
var user = <?= json_encode(auth()->user()); ?>;
var timezone = "{{ $timezone }}";
Vue.config.devtools = true
Vue.use(VueChatScroll);
const app = window.app = new Vue({
  el: '#app',
  data:{
    isActive: '',
    activeClass: 'bg-gray-200',
    conversations: conversations,
    single_conversation: {},
    user: user,
    profile: {},
    body: null,
    canSendMessage: false,
    job_id: '',
    files: null,
    spinner: false,
    previousChannel: null,
  },

  computed: {
    conversation: function(){
      return this.single_conversation;
    },

  },

  methods: {
    send(){
      var self = this;
      if(this.body == null & this.files == null){
        return alert("message can't be empty")
      }

      var formData = new FormData();
      if(this.files != null){
        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i];

          formData.append('files[' + i + ']', file);
        }
      }
      if(this.body != null){
        formData.append('body', this.body);
      }
      
      if(this.single_conversation[0] != undefined){
        this.spinner = true;
        axios.post('../chats/'+ this.single_conversation[0].conversation_id,
          formData, {
            headers: {
            'Content-Type': 'multipart/form-data'
            }
          }
        ).then(function(r){
          console.log('send message');
          console.log(r);
          self.body = null;
          self.files = null;
          self.single_conversation.push(r.data);
          self.spinner = false;
          // console.log(self.$refs.files.files);
        }).catch(function(){
          // console.log(e);
          self.spinner = false;
          
        })
      }else{
        this.body = null;
        this.files = null;
        alert("you can not send messages to user")
      }
    

    },

    // getConversations: function(){
    //  self.spinner = true;
    //  axios.get('../conversations').then(function(r){
    //    self.conversations = r.data;
    //    conversations = r.data;
    //    self.spinner = false;
    //    // console.log(self.$refs.files.files);
    //  }).catch(function(){
    //    // console.log(e);
    //    self.spinner = false;
        
    //  })

      
    // },

    showAttachemnts: function(media){
      if(media != undefined){
        if(media.length > 0){
          return true; 
        }
      }
      return false;
      
    },

    file: function(media){
        var path = '../storage/' + media.id + '/' + media.file_name;
        // console.log(path);
                return path; 
      
    },

    handleFilesUpload: function() {
      this.files = this.$refs.files.files;
      // console.log(this.files);
      
    },

    markSeen(id){
      axios.put('../chats/'+ id + '/markseen/', {
      }).then(function(r){
        // console.log(r);
      }).catch(function(e){
        // console.log(e);
        
      })

    },

    dateFormat(d){
      var date = Moment.tz(d, timezone).fromNow();
      // console.log(date);
      if(date == "Invalid date"){
        return d
      }
      return date;
      
    },

    image(profile) {
      if (
        profile.photo == null ||
        profile.photo == "" ||
        profile.photo == undefined
      ) {
        return "assets/images/user-avatar-placeholder.png";
      } else {
        return profile.photo;
      }
    },
    init: function(){
      var self = this
      var BreakException = {};
      if(conversations.length > 0){
        this.canSendMessage = true;
      }
      try {
        for (var index = 0; index < conversations.length; index++) {
          const element = conversations[index];
          element.participants.forEach(function(p) {
            if(p.user.id != self.user.id){
              // console.log(p);
              // console.log(element);
              
              self.single_conversation = element.messages;
              self.profile = p.user.profile
              self.job_id = element.job_id;
              throw BreakException;
            } 
          });
        }
      
      } catch (e) {
        if (e !== BreakException){
          throw e;
        } 
      }

      if(this.single_conversation[0] != undefined)
      {
        this.activateWebsocket()
        

      }else{
        this.canSendMessage = false;
        // this.scrollDown()
      }
      

    },
    findConversation:  function(id, profile, job_id){
      // console.log(id);
      if(this.previousChannel != null){
        try {
          Echo.leave(this.previousChannel);
        } catch (error) {
          console.log(error);
          
        }
      }
      this.body = null;
      this.files = null;
      this.job_id = job_id;
      this.profile = profile;
      var self = this;
      this.conversations.forEach(function(c) {
        if(c.id == id){
          self.single_conversation = c.messages;
          // console.log(c);
        } 
      });

      if(this.single_conversation[0] != undefined)
      {
        this.activateWebsocket()
        
      }else{
        this.canSendMessage = false;
        this.scrollDown()
      }

      
      
    },

    activateWebsocket: function(){
      var self = this;
      this.isActive = this.single_conversation[0].conversation_id;
      this.markSeen(this.single_conversation[0].conversation_id);

      if(this.job_id == null){
        this.canSendMessage = false;
      }else{
        this.canSendMessage = true;
      }

      this.scrollDown();
      this.previousChannel = `chat-room.${this.single_conversation[0].conversation_id}`;
      console.log(`chat-room.${this.single_conversation[0].conversation_id}`);
      Echo.channel(`chat-room.${this.single_conversation[0].conversation_id}`)
        .listen('MessageWasSent', function(e) {
          console.log('chat-room - MessageWasSent');
          console.log(e);
          if(self.single_conversation[0].conversation_id == e.message.conversation_id)
          {
            var checkMultipleMessage = 0
            self.single_conversation.forEach(function(c) {
              if(c.id == e.message.id){
                checkMultipleMessage = 1
              }

            });

            if(checkMultipleMessage == 0){
              self.single_conversation.push(e.message);
            }
            
          } 
      });

    
    },

    scrollDown: function(){
      // console.log('clicked');
      
      var pane = document.getElementById('pane');
      if(pane != null){
        // pane.scrollIntoView();
        pane.scrollTop = pane.scrollHeight;
      }
    
        
    }
  },

  mounted: function(){
    this.init() 
  }
});
</script>
@push('custom-scripts')
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
@endpush
@endsection