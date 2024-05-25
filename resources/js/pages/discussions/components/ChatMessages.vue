<template>
    <main
        class="max-h-[515px] overflow-hidden overflow-y-scroll"
    >
        <ul class="p-2">
            <li v-for="message in messages">
                <div class="mb-[2px]">
                    <div
                        class="flex items-start gap-2"
                        :class="{
                            'flex-row-reverse':
                                message.user.user_id === user.user_id,
                        }"
                    >
                        <div
                            v-if="isFirstMessage(message)"
                            class="w-8 h-8">
                            <img
                                src="../../../assets/logo.png"
                                alt=""
                                class="w-full h-full object-cover rounded-full"
                            />
                        </div>

                        <!-- current user messages on right, and others on left -->
                        <!-- Messages on left -->
                        <div
                            class="text-gray-300 bg-gradient-to-br from-black-2 to-black-4 max-w-[200px] p-2 rounded-sm shadow-sm shadow-black-5"
                            :class="{
                                'bg-gradient-to-tr from-gray-600 to-gray-900 pr-4':
                                    message.user.user_id === user.user_id,
                                 'mt-1': isFirstMessage(message),
                                 'mr-10': !isFirstMessage(message) && message.user.user_id === user.user_id,
                                 'ml-10': !isFirstMessage(message) && message.user.user_id !== user.user_id
                            }"
                        >
                            <span class="text-sm text-orange-500" :class="{'hidden': !isFirstMessage(message),}">
                                <span
                                    v-if="message.user.user_id === user.user_id"
                                >
                                    {{ messages[messages.indexOf(message) - 1] && messages[messages.indexOf(message) - 1].user.user_id === user.user_id ? '':'You' }}
                                </span>
                                <span v-else>
                                    {{ message.user.username }}
                                </span>
                            </span>
                            <br v-if="isFirstMessage(message)" />
                            <span class="text-lg">{{ message.message }}</span>
                        </div>

                        <!-- Messages on right -->
                    </div>
                </div>
            </li>
        </ul>
    </main>
</template>

<script>
import axios from "axios";

export default {
    props: ["messages", "currentUserMessages", "user", "heightFromProp"],

    computed: {
        mainContainerHeight() {
            return innerHeight - 106 + "px";
        },
    },

    updated() {
        this.handleScroll();
    },

    methods: {

        isFirstMessage(message) {
            if (!message || !this.messages.length) {
                return false;
            }
            const previousMessage = this.messages[this.messages.indexOf(message) - 1];
            return !previousMessage || previousMessage.user.user_id !== message.user.user_id;
        },

        handleScroll() {
            const element = this.$el;
            element.scrollTop = element.scrollHeight - element.clientHeight; // Scroll to bottom
        },

        async fetchProfilePhoto() {
            await axios.get('/fetch-profile-photo')
                .then((response) => {
                    this.profile_photo = response.data.photoUrl;
                })
                .catch((error) => {
                    console.error('Error fetching user details:', error);
                });
        },
    },
};
</script>

<style scoped>
main::-webkit-scrollbar {
    width: 2px;
}
</style>
