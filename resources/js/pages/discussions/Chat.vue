<template>
    <!-- bg-gradient-to-tr from-black-2 to-black-4 -->
    <main
        class="hidden lg:flex flex-col justify-between bg-gradient-to-tr from-black-2 to-black-4"
        :class="styleClasses"
    >
        <div>
            <div class="flex items-end w-full bg-black-4 h-50">
                <h1 class="p-2 text-2xl font-semibold text-gray-200 text-left">
                    {{ title }}
                </h1>
                <span v-if="typer" class="text-sm text-gray-300 self-end"
                    >{{ typer }} is typing...
                </span>
            </div>
            <ChatMessages
                :messages="messages"
                :currentUserMessages="currentUserMessages"
                :user="this.user"
                :heightFromProp="styleClasses.includes('h') ? 'h-auto':''"
            ></ChatMessages>
        </div>
        <ChatForm :chatId="chatId" :user="this.user" @messageSent="addMessage"></ChatForm>
    </main>
</template>

<script>
import axios from "axios";
import ChatForm from "./components/ChatForm.vue";
import ChatMessages from "./components/ChatMessages.vue";
import _ from "lodash";

export default {
    components: {
        ChatForm,
        ChatMessages,
    },

    props: {
        chatId: {
            type: String,
            default: null,
        },
        styleClasses: {
            type: String,
            default: 'bg-gradient-to-tr from-black-2 to-black-4',
        },
        title: {
            type: String,
            default: 'Discussions',
        },
    },

    // get current user from inertia's auth
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },

    data() {
        return {
            messages: [],
            currentUserMessages: [],
            users: [],
            typer: "", // who is typing
        };
    },

    created() {
        if (this.chatId) {
            this.fetchMessagesWithChatId();

            let chatChannel = 'chat.' + this.chatId;
            Echo.channel(chatChannel)
                .listen('MessageSent', (event) => {
                    console.log("Message was sent and broadcast was successful!");
                    console.log(event);

                    if (this.user.user_id === event.message.user.user_id) {
                        return;
                    }

                    this.messages.push({
                        message: event.message.message,
                        user: event.message.user,
                    });
            });
        } else {
            this.fetchMessages();

            Echo.join("chat")
                .here((users) => {
                    this.users = users;
                    console.log("Total users: ", users.length);
                })
                .joining((user) => {
                    this.users.push(user);
                })
                .leaving((user) => {
                    this.users = this.users.filter((u) => u.id !== user.id);
                })
                .listenForWhisper("typing", (e) => {
                    if (!this.typer) {
                        this.typer = this.throttledFunction(e);
                        setTimeout(() => {
                            this.typer = "";
                        }, 1000);
                    }
                })
                .listenForWhisper("MessageSent", (event) => {
                    this.messages.push({
                        message: event.message,
                        user: event.user,
                    });

                    // this.addMessage(event); => not calling from here bcz 2 instances are added to db

                    this.typer = "";
                });
        }
    },

    methods: {
        throttledFunction: _.throttle((e) => {
            return e.user.username;
        }, 1000),

        fetchMessages() {
            axios.get("/messages").then((response) => {
                this.messages = response.data;
            });
        },

        async fetchMessagesWithChatId() {
            axios.get('/get-messages-using-id', {
                params: {
                    chatId: this.chatId,
                }
            }).then((response) => {
                this.messages = response.data.messages;
            })
        },

        addMessage(userAndMsg) {
            // console.log(userAndMsg.message);
            // console.log(userAndMsg.user);
            this.messages.push({
                message: userAndMsg.message,
                user: userAndMsg.user,
            });
            this.$inertia.post(
                "/messages",
                {
                    message: userAndMsg.message,
                    user: userAndMsg.user,
                },
                {
                    preserveScroll: true,
                },
            );
            this.$forceUpdate();
        },
    },
};
</script>

<style scoped>
/* Hide scroll bar */
div::-webkit-scrollbar {
    width: 0.1rem;
}

div::-webkit-scrollbar-track {
    background-color: transparent;
}

div::-webkit-scrollbar-thumb {
    background-color: transparent;
}
</style>
