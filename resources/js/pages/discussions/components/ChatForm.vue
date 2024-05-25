<template>
    <main class="h-50">
        <div
            class="flex justify-center items-center bg-gradient-to-tl from-black-2 to-black-4 w-full p-2"
        >
            <input
                id="btn-input"
                type="text"
                name="message"
                class="w-width_90 p-2 rounded-lg mr-2 outline-none"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage"
                @keyup="sendTypingEvent"
            />

            <button id="btn-chat" @click="sendMessage">
                <i class="fa-solid fa-paper-plane text-white text-xl ml-2"></i>
            </button>
        </div>
    </main>
</template>

<script>
import axios from "axios";

export default {
    props: ["user", "chatId"],

    data() {
        return {
            newMessage: "",
            typingEventSent: false,
        };
    },

    methods: {
        sendTypingEvent() {
            Echo.join("chat").whisper("typing", { user: this.user });
        },

        async sendMessage() {
            if (this.chatId) {

                let userAndMsg = { message: this.newMessage, user: this.user };
                this.$emit("messageSent", userAndMsg);

                let message = this.newMessage;
                this.newMessage = "";
                await axios.post('/send-message-using-id', {
                    chatId: this.chatId,
                    message: message,
                    sender: this.user,
                }).then((response) => {
                    // console.log("Sending message!");
                    // console.log(response);
                });

                this.newMessage = "";
                return;
            }

            Echo.join("chat").whisper("MessageSent", {
                message: this.newMessage,
                user: this.user,
            });

            let userAndMsg = { message: this.newMessage, user: this.user };
            this.$emit("messageSent", userAndMsg);

            this.newMessage = "";
        },
    },
};
</script>
