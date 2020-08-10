<template>
  <div>
    <div class="alert alert-success alert-dismissible fade show" style="display:none">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Success!</strong>
      <a :href="newMessageLink">New Message</a>
    </div>
    <ul>
      <li v-for="(user,index) in users" :key="index">
        {{user.name}}
        <button class="btn-primary" :id="user.id" @click="chat">Chat</button>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  props: ["user"],
  mounted() {
    this.getUsers();
  },
  created() {
    console.log("App.User." + this.user.id);
    Echo.join("App.User." + this.user.id)
      .here((user) => {
        console.log(user);
      })
      .listen("MessageSent", (e) => {
        console.log(e.message);
               
        this.newMessageLink = "/chat#" + e.message.user_id;
        var alertModal = document.querySelector('.alert');
        alertModal.style.display="block";
      });
  },
  data() {
    return {
      users: [],
      newMessageLink: null,
    };
  },
  methods: {
    getUsers() {
      axios
        .get("users")
        .then((res) => {
          var otherUsers = res.data.filter((user) => user.id != this.user.id);
          this.users.push(...otherUsers);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    chat(e) {
      location.assign("./chat#" + e.target.id);
    },
  },
};
</script>