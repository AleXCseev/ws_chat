<template>
    <div class="flex">
        <div class="w-3/4 mr-4 p-4 bg-white border border-gray-200">
            <h3 class="text-gray-700 mb-4 text-lg">{{ chat.title ?? 'Your chat' }}</h3>
            <div class="mb-4">

            </div>
            <div>
                    <h3 class="text-gray-700 mb-4 text-lg">Send message</h3>
                    <div>
                        <div class="mb-4">
                            <input placeholder="Message" class="rounded-full border border-gray-400" type="text" v-model="body">
                        </div>
                        <div>
                            <a @click.prevent="store" class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Send</a>
                        </div>
                    </div>
            </div>
        </div>
        <div class="w-1/4 p-4 bg-white border border-gray-200">
            <h3 class="text-gray-700 mb-4 text-lg">Users</h3>
            <div v-if="users">
                <div v-for="user in users" :key="user.id" class="flex items-center pb-2 mb-4 border-b border-grey-300">
                    <p class="mr-2">{{ user.id }}</p>
                    <p class="mr-4">{{ user.name }}</p>
                </div>
            </div>
        </div>
    </div>
   
</template>

<script>
import Main from '../../Layouts/Main.vue'
export default {
    name: "Show",

    props: [
        "chat",
        "users"
    ],

    data() {
        return {
            body: "",
        }
    },

    layout: Main,

    computed: {
        userIds() {
            return this.users.map(user => {
                return user.id
            }).filter(userId => {
                return userId !== this.$page.props.auth.user.id
            })
        }
    },  

    methods: {
        store() {
            axios.post('/messages', {
                chat_id: this.chat.id,
                body: this.body,
                user_ids: this.userIds
            }).then(res => {
                console.log(res)
            })
        },
    },
    
} 
</script>