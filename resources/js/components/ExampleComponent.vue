<template>
  <v-card class="mx-auto overflow-hidden container" height="550">
    
    <v-app-bar color="deep-purple" light>
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
      <v-toolbar-title><img id="avatar" :src="selectedUserAvatar"></v-toolbar-title>
      <v-toolbar-title>{{ selectedUserName }}</v-toolbar-title>
      <v-toolbar-title class="ml-3 text-danger">{{ typing }}</v-toolbar-title>
      <div class="row">
            <div class="col-sm">
              <img id="imagePreview" class="rounded mx-auto d-block">
            </div>
      </div>
    </v-app-bar>
    
    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list nav dense>
        <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
          <v-list-item>
            <v-list-item-icon>
              <v-icon style="margin-top: 30px;">mdi-account-search</v-icon>
            </v-list-item-icon>
            
            <v-list-item-title>
                 <v-text-field v-model="search" placeholder="Search by name"></v-text-field>
            </v-list-item-title>
          
          </v-list-item>
          
          <div v-if="usersPages.length > 0">
          <v-list-item v-for="user in usersPages[currentPage -1]" :key="user.id">
            <v-list-item-icon>
              <img :src="user.avatar" @click="userSelected(user.id,user.name,user.avatar)" style="height: 35px;width: 35px;border-radius: 100px;margin-top: -6px;">
            </v-list-item-icon>
            <v-list-item-title style="margin-left: 17px;" @click="userSelected(user.id,user.name,user.avatar)">
              {{ user.name }}
            </v-list-item-title>
              <!-- <span class="badge badge-success">1</span>
              <span class="badge badge-danger">0</span> -->
          </v-list-item>
          </div>
          <div v-else>
            <h4 class="text-danger">{{ message }}</h4>
          </div>

        <div class="container">
          <div class="row">
            <div class="col-sm-12 m-auto">
              <paginate
                  v-if="pageCount > 1"
                  v-model="currentPage"
                  :page-count="pageCount"
                  :container-class="'pagination pagination-sm justify-content-center'"
                  :page-class="'page-item'"
                  :page-link-class="'page-link'"
                  :prev-class="'page-item'"
                  :prev-link-class="'page-link'"
                  :next-class="'page-item'"
                  :next-link-class="'page-link'"
                ></paginate>
            </div>
          </div>
        </div>

        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <div class="chat" v-chat-scroll="{always: false, smooth: true}">
       <ul>
         <div v-for="chat in chats" :key="chat.id">
         <li class="text-right" v-show="chat.sender_id == authId"><small>{{ chat.created_at }}</small> {{ chat.chat }}</li>
         <li class="text-right" v-show="chat.sender_id == authId" v-if="chat.image"><img :title="chat.created_at" :src="'/chats_pics/'+chat.image" style="height: 150px;width: 150px;"></li>

         <li class="text-left" v-show="chat.sender_id != authId" style="color: red;">{{ chat.chat }} <small>{{ chat.created_at }}</small></li>
         <li class="text-left" v-show="chat.sender_id != authId" v-if="chat.image"><img :title="chat.created_at" :src="'/chats_pics/'+chat.image" style="height: 150px;width: 150px;"></li>
         </div>
       </ul>
    </div>
    <form @submit.prevent="store()" enctype="multipart/form-data">
    <div class="row container m-auto">
      <v-btn @click="$refs.inputUpload.click()" elevation="2" :disabled="btnDisable" icon>
        <v-icon>mdi-folder-multiple-image</v-icon>
      </v-btn>
      <input v-show="false" ref="inputUpload" type="file" id="image" @change="fileSelected">
      <v-text-field :disabled="btnDisable" v-model="chat"></v-text-field>
      <v-btn :disabled="btnDisable" type="submit" elevation="2" icon>
        <v-icon>mdi-send</v-icon>
      </v-btn>
    </div>
    </form>

  </v-card>
</template>

<script>
  export default {
    data: () => ({
      drawer: false,
      group: null,
      selectedUserName : '',
      selectedUserAvatar : null,
      selectedUserId : null,
      chats : [],
      authId : Number(document.getElementById('authId').value),
      chat : '',
      btnDisable : true,

      users : [], // pagination fact
      usersPages: [],
      perPage: 25,
      pageCount: 1,
      currentPage: 1,

      search: '', // search fact
      message: 'No Data Found',

      inputUpload : null, // null after store

      typing : '', // typing fact
      typingValue : '',
      authName : document.getElementById('authName').value,
    }),
    mounted (){
      Echo.join(`chat`)
          .here((users) => {
            console.log(users);
          })
          .joining((user) => {
              console.log(user.name + ' joined');
          })
          .leaving((user) => {
              console.log(user.name + ' leaved');
          });
      Echo.private('chat')
          .listen('ChatEvent', (e) => {
                this.userSelected(this.selectedUserId,this.selectedUserName,this.selectedUserAvatar)
                this.getUsers()
          })
            // client side event true korte hobe atar jonno config/broadcast.php -> app[]
            // It's for typing
          .listenForWhisper('typing', (e) => {
              if(e.typingValue.length > 0){
                     if (e.name == this.selectedUserName) {
                        this.typing = 'is typing...'
                     }
               }else{
                  this.typing = ''
               }
          })
      this.getUsers()
    },
    watch :{
        search (value) {
            this.setCurrentPage(1)
            this.generatePages(this.users)
            if (this.search !== '' || this.search !== ' ') {
                let search = this.users.filter(e => {
                    if (e.name.indexOf(this.search) !== -1) {
                        return e
                    }
                })
                this.generatePages(search)
            }
        },
        chat(){
           Echo.private('chat')
               .whisper('typing', {
                   name: this.authName,
                   typingValue : this.chat
               })
        }
    },
    methods:{
        setCurrentPage (page) {
            this.currentPage = page
        },
        generatePages (users) {
            this.usersPages = _.chunk(users, this.perPage)
            this.pageCount = 0
            this.pageCount = this.usersPages.length
            if (this.pageCount == 0) {
                this.message = "Sorry! No User Found"
            }
        },
        getUsers(){
            axios.get('/users')
            .then((response) =>{
                this.users = response.data
                this.generatePages(this.users)
            })
            .catch((error) =>{
                console.log(error);
            })
        },
        userSelected(e,name,avatar){
          this.btnDisable = false
          this.selectedUserName = name
          this.selectedUserAvatar = avatar
          this.selectedUserId = e
          let image = document.getElementById('avatar')
          image.style.height = '45px'
          image.style.width = '45px'
          image.style.borderRadius = '100px'
          image.style.marginRight = '6px'
          axios.get('/user/'+e)
          .then(response =>{
            this.chats = response.data
          })
          .catch(error =>{
            console.log(error);
          })
        },
        fileSelected(e){
          if (e.target.files[0]) {
             let imageFile = e.target.files[0];
             let reader = new FileReader();
             reader.onload = function () {
                 let output = document.getElementById('imagePreview');
                 output.src = reader.result;
                 output.style.height = '66px'
                 output.style.width = '100px'
             }
             reader.readAsDataURL(e.target.files[0])
          }
        },
        store(){
          let data = new FormData();
          let image = document.getElementById("image").files[0];
          data.append('image', image); // conditional undefined
          data.append('receiver_id', this.selectedUserId);
          data.append('chat', this.chat);
          axios.post('/store',data)
          .then(response =>{
            let output = document.getElementById('imagePreview');
            output.style.height = '0'
            output.style.width = '0'
            this.chat = ''
            this.$refs.inputUpload.value = null
            this.userSelected(this.selectedUserId,this.selectedUserName,this.selectedUserAvatar)
          })
          .catch(error =>{
            // if(error.response.status && error.response.status === 422){
            //   console.log('Chat Required');
            // }
            console.log(error);
          })
        },
   }
  }
</script>
<style scoped>
   button{
     outline: none;
   }
   .chat{
      padding: 25px;
      height: 413px;
      overflow-x: overlay;
   }
   ul {
     list-style-type: none;
   }
   li{
     font-size: 20px;
   }
   small{
     font-size: 10px;
   }
</style>