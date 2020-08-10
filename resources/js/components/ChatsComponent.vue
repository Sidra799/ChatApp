<template>
  <div id="app" class="row">
    <div class="col-8">
      <div class="card card-default">
        <div class="card-header">Messages</div>
        <div class="card-body p-0">
          <ul class="list-unstyled" style="height:300px; overflow-y:scroll" v-chat-scroll>
            <li class="p-2" v-for="(message, index) in messages" :key="index">
              <strong>{{message.user.name}}</strong>
              {{message.message}}
            </li>
          </ul>
        </div>
        <input
          @keydown="startTypingEvent"
          @keyup.enter="sendMessage"
          v-model="newMessage"
          type="text"
          name="message"
          placeholder="Enter Your Message..."
          class="form-control"
        />
      </div>
      <span class="text-muted" v-if="activeUser">{{activeUser.name}} is typing...</span>
    </div>
    
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      messages: [],
      newMessage: "",
      users: [],
      activeUser: false,
      typingTimer: false,
      sendMessageTo: ""
    };
  },
  created() {

    this.fetchMessages();
    console.log("App.User." + this.user.id);

    Echo.join("App.User." + this.user.id)
      .here((user) => {
        this.users = user;
      })
      .joining((user) => {
        this.users.push(user);
      })
      .leaving((user) => {
        this.users = this.users.filter((u) => u.id != user.id);
      })
      .listen("MessageSent", (e) => {
        this.messages.push(e.message);
      })
      .listenForWhisper("typing", (user) => {
        this.activeUser = user;
        if (this.typingTimer) {
          clearTimeout(this.typingTimer);
        }

        this.typingTimer = setTimeout(() => {
          this.activeUser = false;
        }, 3000);
      });
  },
  methods: {
    fetchMessages() {
      axios.get("messages").then((response) => {
        this.messages = response.data;
      });
    },
    sendMessage() {
      this.messages.push({
        user: this.user,
        message: this.newMessage,
      });
      axios.post("messages", { userId: this.sendMessageTo,message: this.newMessage });
      this.newMessage = "";
    },
    startTypingEvent() {
      Echo.join("App.User." + this.sendMessageTo).whisper("typing", this.user);
    },
  },
  mounted() {
    if(location.hash.substring(1, location.hash.length)){
      this.sendMessageTo = location.hash.substring(1, location.hash.length);
    }
    else{
      location.assign('./contact')
    }
  }
};
</script>
